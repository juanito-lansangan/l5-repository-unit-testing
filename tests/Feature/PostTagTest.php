<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostTagTest extends TestCase
{

    public function testItCanAddTag()
    {
        $postTagRepo = \App::make('App\Repositories\PostTag\PostTagRepository');
        $postTagRepo->add('laravel');
        $tags = $postTagRepo->list();

        $this->assertEquals(1, $tags->count());
    }

    public function testItCanAddManyTags()
    {
        $postTagRepo = \App::make('App\Repositories\PostTag\PostTagRepository');
        $tags = [
            ['value' => 'laravel'],
            ['value' => 'php'],
            ['value' => 'framework'],
            ['value' => 'unit testing'],
        ];
        $postTagRepo->addMany($tags);
        $tags = $postTagRepo->list();

        $this->assertEquals(4, $tags->count());
    }

    public function testCantAddEmptyTags()
    {
        $postTagRepo = \App::make('App\Repositories\PostTag\PostTagRepository');
        $tags = [];
        $postTagRepo->addMany($tags);
        $tags = $postTagRepo->list();

        $this->assertEquals(0, $tags->count());
    }
}
