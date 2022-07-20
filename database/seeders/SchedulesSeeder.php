<?php

namespace Database\Seeders;

use App\Models\Schedules;
use Illuminate\Database\Seeder;

class SchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $horaInicial = 8;
        for ($i= $horaInicial; $i <= 20; $i++) { 
            if(($i <= 11 || $i >= 16)){
                $horario = new Schedules;
                $horario->hour = $i >= 10 ? ''.$i:'0'.$i;
                $horario->minute = '00';
                $horario->state = 1;
                $horario->save();
                if($i != 11 && $i != 20)
                {
                    $horario = new Schedules;
                    $horario->hour = $i >= 10 ? ''.$i:'0'.$i;
                    $horario->minute = '30';
                    $horario->state = 1;
                    $horario->save();
                }
            }
        }
    }
}
