<?php

if (!function_exists('msg')) {
    function msg(string $group, ?string $key = null, array $data = []): string
    {
        /*
        |--------------------------------------------------------------------------
        | 1. MESSAGE MÉTIER
        |--------------------------------------------------------------------------
        */

        if ($key !== null) {
            $value = __("messages.$group.$key");

            // fallback vers default du groupe si clé inexistante
            if ($value === "messages.$group.$key") {
                $value = __("messages.$group.default");
            }
        } else {
            /*
            |--------------------------------------------------------------------------
            | 2. FALLBACK GROUPE
            |--------------------------------------------------------------------------
            */

            $value = __("messages.$group.default");
        }

        /*
        |--------------------------------------------------------------------------
        | 3. FALLBACK FINAL (sécurité ultime)
        |--------------------------------------------------------------------------
        */

        if ($value === "messages.$group.default") {
            return 'Message non disponible.';
        }

        /*
        |--------------------------------------------------------------------------
        | 4. INJECTION DES VARIABLES
        |--------------------------------------------------------------------------
        */

        foreach ($data as $placeholder => $replace) {
            $value = str_replace(":$placeholder", $replace, $value);
        }

        return $value;
    }
}