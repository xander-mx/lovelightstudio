<?php

namespace Database\Seeders;

use App\Models\ParameterValues;
use Illuminate\Database\Seeder;

class ParameterValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parameterValue = new ParameterValues;
        $parameterValue->idParameter = 1;
        $parameterValue->name = "Administrador";
        $parameterValue->save();

        $parameterValue = new ParameterValues;
        $parameterValue->idParameter = 1;
        $parameterValue->name = "Supervisor Foto";
        $parameterValue->save();

        $parameterValue = new ParameterValues;
        $parameterValue->idParameter = 1;
        $parameterValue->name = "Fotógrafo";
        $parameterValue->save();

        $parameterValue = new ParameterValues;
        $parameterValue->idParameter = 1;
        $parameterValue->name = "Reservacionista";
        $parameterValue->save();

        $parameterValue = new ParameterValues;
        $parameterValue->idParameter = 1;
        $parameterValue->name = "Bodas";
        $parameterValue->state = 1;
        $parameterValue->save();

        $parameterValue = new ParameterValues;
        $parameterValue->idParameter = 1;
        $parameterValue->name = "Cliente";
        $parameterValue->save();


        $parameterValue = new ParameterValues;
        $parameterValue->idParameter = 2;
        $parameterValue->name = "Max hora";
        $parameterValue->extra="24";
        $parameterValue->save();

        $parameterValue = new ParameterValues;
        $parameterValue->idParameter = 1;
        $parameterValue->name = "Supervisor reserva";
        $parameterValue->save();
    }
}
