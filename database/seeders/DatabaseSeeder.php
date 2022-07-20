<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
    {
        $this->truncate();

        $this->call([
            ParametersSeeder::class, // Parametros
            ParameterValuesSeeder::class, // Valores parametros
            UsersSeeder::class, //Usuarios
            CentersSeeders::class, //Centros de negocios
            SchedulesSeeder::class, //Horarios
        ]);
    }

    private function truncate(){
        // Nombre de tablas para eliminar antes de accionarse el seeder
        $tables = [
            'parameters',
            'parameter_values',
            'users',
            'centers',
            'schedules'
        ];
        DB::statement('SET foreign_key_checks=0');
        foreach ($tables as $key) {
            DB::table($key)->truncate();
        }
        DB::statement('SET foreign_key_checks=1');
    }
}
