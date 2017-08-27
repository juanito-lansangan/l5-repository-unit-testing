<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $total = 100;
        $users = User::all();

        for ($i = 0; $i < $total; $i++) {
          Post::create([
            'post_title' => $faker->title,
            'post_body' => $faker->text,
            'post_author' => $users->random()->id
          ]);
        }
    }

}
