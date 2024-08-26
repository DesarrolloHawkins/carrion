<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Palcos;
use App\Models\Sillas;

class SillasSeeder extends Seeder
{
    public function run()
    {
        $palcos = Palcos::all();

        foreach ($palcos as $palco) {
            for ($i = 1; $i <= 8; $i++) {
                Sillas::create([
                    'numero' => $i,
                    'id_palco' => $palco->id,
                    'id_zona' => $palco->id_zona,
                    'fila' => 'F' . ($i <= 4 ? 1 : 2), // Asigna la fila correctamente
                ]);
            }
        }
    }
}
