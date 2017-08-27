<?php namespace App\Repositories\PostTag;

interface PostTagRepositoryInterface
{
    public function add($tagName);
    public function addMany(array $tags);
    public function list();
}
