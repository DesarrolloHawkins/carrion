<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HashUserPasswords extends Command
{
    protected $signature = 'hash:user-passwords';

    protected $description = 'Hashea las contraseñas de los usuarios en la base de datos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtén los usuarios de la base de datos
        $usuarios = DB::table('users')->get();

        foreach ($usuarios as $usuario) {
            // Verifica si la contraseña ya no está hasheada
            if (!Hash::needsRehash($usuario->password)) {
                continue; // Si ya está hasheada, no hagas nada
            }

            // Hashea la contraseña y actualiza la base de datos
            $hashedPassword = Hash::make($usuario->password);

            DB::table('users')
                ->where('id', $usuario->id)
                ->update(['password' => $hashedPassword]);

            $this->info("Contraseña de usuario {$usuario->username} hasheada.");
        }

        $this->info('Las contraseñas de los usuarios han sido hasheadas correctamente.');
    }
}
