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
        $user1=User::create(['name'=>"john doe",'email'=>"client@mail.com",'password'=>bcrypt('pass#$')]);
        $user1->assignRole("client");
        $user2=User::create(['name'=>"john doe",'email'=>"admin@mail.com",'password'=>bcrypt('pass#$')]);
        $user2->assignRole("admin");
        $user3=User::create(['name'=>"john doe",'email'=>"viewer@mail.com",'password'=>bcrypt('pass#$')]);
        $user3->assignRole("viewer");
    }
}
