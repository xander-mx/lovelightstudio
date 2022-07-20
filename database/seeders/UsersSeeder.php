<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = "Admin";
        $user->lastname = "LLS";
        $user->email = "rcarrillo@lovelightstudio.com.mx";
        $user->password = Hash::make('rcarrillo123');
        $user->cellphone = "123";
        $user->indicative = "+52";
        $user->idPerfil = 1;
        $user->state = 1;
        $user->save();


        $user = new User;
        $user->name = "Lcarrillo";
        $user->lastname = "Ad";
        $user->email = "Lcarrillo@lovelightstudio.com.mx";
        $user->password = Hash::make('Love810511$');
        $user->cellphone = "123";
        $user->indicative = "+52";
        $user->idPerfil = 1;
        $user->state = 1;
        $user->save();
    }
}
