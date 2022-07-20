<?php

namespace Database\Seeders;

use App\Models\Centers;
use Illuminate\Database\Seeder;

class CentersSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $centro = new  Centers;
        $centro->name = "Meliá Puerto Vallarta";
        $centro->description = " 30 min | $100 por foto";
        $centro->extra = "Galería de fotos A un costado de la alberca y de las toallas";
        $centro->state = 1;
        $centro->save();

        $centro = new  Centers;
        $centro->name = "Westin Puerto Vallarta";
        $centro->description = " 30 min | $100 por foto";
        $centro->extra = "Galería de Fotos a un costado de la cafetería";
        $centro->state = 1;
        $centro->save();

        $centro = new  Centers;
        $centro->name = "Occidental Nuevo Vallarta";
        $centro->description = " 30 min | $100 por foto";
        $centro->extra = "El fotógrafo los verá en el lobby";
        $centro->state = 1;
        $centro->save();

        $centro = new  Centers;
        $centro->name = "Krystal Grand Nuevo Vallarta";
        $centro->description = " 30 min | $100 por foto";
        $centro->extra = "El fotógrafo los verá en Coco Café el cual se encuentra abajo del Lobby";
        $centro->state = 1;
        $centro->save();

        $centro = new  Centers;
        $centro->name = "Hilton Puerto Vallarta";
        $centro->description = " 30 min | $100 por foto";
        $centro->extra = "A un costado del concierge en el Lobby de Hilton";
        $centro->state = 1;
        $centro->save();

        $centro = new  Centers;
        $centro->name = "Hotel externo";
        $centro->description = " 30 min | Por definir";
        $centro->state = 1;
        $centro->save();
    }
}
