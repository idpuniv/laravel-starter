<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Person;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateRootUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-root {--quick : Skip personal info fields (first name, last name, username)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the root user of the application (bootstrap only)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            // Ensure root role exists before proceeding
            $rootRole = $this->getOrCreateRootRole();
            
            if (!$rootRole) {
                $this->error('Failed to create or retrieve root role.');
                return Command::FAILURE;
            }

            // Check if a root user already exists
            if ($this->rootUserExists()) {
                $this->error('A root user already exists.');
                return Command::FAILURE;
            }

            // Collect user input based on environment or quick flag
            $userData = $this->collectUserInput();
            
            if ($userData === null) {
                return Command::FAILURE;
            }

            // Create the root user within a database transaction
            $this->createRootUser($userData, $rootRole);

            $this->newLine();
            $this->info('Root user created successfully.');
            $this->line("Email: {$userData['email']}");

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('Root creation failed: ' . $e->getMessage());
            report($e);
            return Command::FAILURE;
        }
    }

    /**
     * Get or create the root role.
     *
     * @return Role|null
     */
    private function getOrCreateRootRole(): ?Role
    {
        $role = Role::where('name', 'root')
            ->where('guard_name', 'web')
            ->first();

        if (!$role) {
            $this->warn('Root role does not exist. Creating it...');
            
            $role = Role::create([
                'name' => 'root',
                'guard_name' => 'web',
            ]);
            
            $this->info('Root role created successfully.');
        }

        return $role;
    }

    /**
     * Check if a root user already exists in the system.
     *
     * @return bool
     */
    private function rootUserExists(): bool
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'root')
                ->where('guard_name', 'web');
        })->exists();
    }

    /**
     * Get the minimum password length based on environment.
     *
     * @return int
     */
    private function getMinPasswordLength(): int
    {
        // Production requires 8 characters minimum
        if (app()->isProduction()) {
            return 8;
        }
        
        // Development/local requires only 3 characters minimum
        return 3;
    }

    /**
     * Collect and validate user input from the console.
     * Uses quick mode for local environment or when --quick flag is provided.
     *
     * @return array|null
     */
    private function collectUserInput(): ?array
    {
        $this->newLine();
        $this->line('Creating root user...');
        $this->newLine();

        // Use quick mode if --quick flag is set OR environment is local
        $quickMode = $this->option('quick') || app()->isLocal();
        $minPasswordLength = $this->getMinPasswordLength();

        if ($quickMode) {
            $this->info("Quick mode enabled. Only email and password required (min {$minPasswordLength} characters).");
            
            $email = $this->ask('Email');
            $password = $this->secret('Password');
            $passwordConfirmation = $this->secret('Confirm password');

            $data = [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $passwordConfirmation,
                'first_name' => 'Root',
                'last_name' => 'User',
                'username' => 'root_' . time(),
            ];
        } else {
            $this->info("Full mode. All fields required (password min {$minPasswordLength} characters).");
            
            $firstName = $this->ask('First name');
            $lastName = $this->ask('Last name');
            $email = $this->ask('Email');
            $username = $this->ask('Username (optional)', null);
            $password = $this->secret('Password');
            $passwordConfirmation = $this->secret('Confirm password');

            $data = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'username' => $username,
                'password' => $password,
                'password_confirmation' => $passwordConfirmation,
            ];
        }

        $validator = $this->validateInput($data, $quickMode);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return null;
        }

        return $data;
    }

    /**
     * Validate the user input based on mode.
     *
     * @param array $data
     * @param bool $quickMode
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateInput(array $data, bool $quickMode): \Illuminate\Contracts\Validation\Validator
    {
        $minPasswordLength = $this->getMinPasswordLength();
        
        $rules = [
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', "min:{$minPasswordLength}", 'same:password_confirmation'],
        ];

        if (!$quickMode) {
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
            $rules['username'] = ['nullable', 'string', 'max:255', 'unique:users,username'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create the root user with associated person record.
     *
     * @param array $userData
     * @param Role $rootRole
     * @return void
     */
    private function createRootUser(array $userData, Role $rootRole): void
    {
        DB::transaction(function () use ($userData, $rootRole) {
            $quickMode = $this->option('quick') || app()->isLocal();

            if ($quickMode) {
                // Quick mode: use factory with generated random data
                $person = Person::factory()->create([
                    'email' => $userData['email'],
                ]);
            } else {
                // Full mode: use provided data
                $person = Person::factory()->create([
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'email' => $userData['email'],
                ]);
            }

            // Prepare user data
            $userPayload = [
                'email' => $userData['email'],
                'person_id' => $person->id,
                'password' => Hash::make($userData['password']),
            ];

            // Add username for quick mode or if provided in full mode
            if ($quickMode) {
                $userPayload['username'] = $userData['username'];
            } elseif (!empty($userData['username'])) {
                $userPayload['username'] = $userData['username'];
            }

            // Create the user
            $user = User::create($userPayload);

            // Assign the root role
            $user->assignRole($rootRole);
        });
    }
}