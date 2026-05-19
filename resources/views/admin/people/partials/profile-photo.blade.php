            <div class="position-relative d-inline-block">
                {{-- Photo circulaire fake --}}
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto"
                    style="width: 150px; height: 150px;">
                    <i class="bi bi-person-fill text-primary" style="font-size: 70px;"></i>
                </div>

                {{-- Lien de modification de photo --}}
                {{-- @can(\App\Permissions\PersonPermissions::UPDATE)
                    <div class="mt-3">
                        <a href="#" class="text-decoration-none small text-primary"
                            onclick="alert('Fonctionnalité d\'upload de photo à venir'); return false;">
                            <i class="bi bi-camera me-1"></i>
                            Modifier la photo
                        </a>
                    </div>
                @endcan --}}
            </div>