<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $total = 10;
        for ($i = 0; $i < $total; $i++) {
          User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => Hash::make('123456')
          ]);
        }
    }
}
