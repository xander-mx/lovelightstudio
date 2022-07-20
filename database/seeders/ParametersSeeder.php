<?php

namespace Database\Seeders;

use App\Models\Parameters;
use Illuminate\Database\Seeder;

class ParametersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parameter = new Parameters;
        $parameter->name = "Tipo Usuarios";
        $parameter->save();
        
        $parameter = new Parameters;
        $parameter->name = "Hora maxima";
        $parameter->save();
    }
}
