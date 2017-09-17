<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostTest extends TestCase
{
    public function testItCanAddPost()
    {
        $attributes = [
            'post' => [
                'title' => 'Sample Title for unit testing',
                'body' => 'Sample Body for unit testing',
            ],
            'author' => [
                'id' => 15
            ],
            'tags' => [
                ['value' => 'Tag1'],
                ['value' => 'Tag2'],
                ['value' => 'Tag3'],
            ],
        ];

        $this
            ->postJson('api/v1/posts', $attributes)
            ->assertStatus(200)
            ->assertJsonStructure([
                    "meta" => ["resource_id", "status", "self"],
                    "data" => ["message"],
            ]);
    }

    public function testItCanUpdatePost()
    {
        $attributes = [
            'post' => [
                'title' => 'Update Sample Title for unit testing',
                'body' => 'Update Sample Body for unit testing',
            ],
            'author' => [
                'id' => 15
            ],
            'tags' => [
                ['value' => 'Tag1'],
                ['value' => 'Tag2'],
                ['value' => 'Tag3'],
            ],
        ];

        $post = \App\Models\Post::latest()->first();

        $this
            ->putJson("api/v1/posts/{$post->id}", $attributes)
            ->assertStatus(200)
            ->assertJsonStructure([
                    "meta" => ["status", "self"],
                    "data" => ["message"],
            ]);
    }

    public function testItCanCheckExistingPost()
    {
        $post = \App\Models\Post::latest()->first();

        $this
            ->get("api/v1/posts/{$post->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                    "meta" => ["status", "self"],
                    "data" => ["id","post_title","post_body","post_author","created_at","updated_at",
                        "author" => ["id","name","email","created_at","updated_at"],
                        "tags" => [["id","post_id","tag_id"]]
                    ],
            ]);
    }

    public function testItCanCheckForNotFound()
    {
        $notExistId = 'notexist';
        $this
            ->get("api/v1/posts/{$notExistId}")
            ->assertStatus(404);
    }
}
