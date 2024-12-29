<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Post_Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'login' => 'mehrab',
            'email' => 'mehrabgevorgyan@gmail.com',
            'password' => 111111,
            'user_image' => 'img/avatar.png',
        ]);

         User::factory(10)->create();
         Post::factory(35)->create();
         Like::factory(100)->create();
         Tag::factory(50)->create();
         Post_Tag::factory(100)->create();
         Comment::factory(100)->create();

    }
}
