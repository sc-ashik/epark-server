<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['name'=>"john doe",'email'=>"johndoe@mail.com",'password'=>bcrypt('pass#$')]);
        User::create(['name'=>"john doe",'email'=>"ashik@mail.com",'password'=>bcrypt('pass#$')]);
    }
}
