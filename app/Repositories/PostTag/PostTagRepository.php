<?php namespace App\Repositories\PostTag;

use App\Repositories\PostTag\PostTagRepositoryInterface;
use App\Repositories\Tag\TagRepositoryInterface;
use App\Models\PostTag;

class PostTagRepository implements PostTagRepositoryInterface
{
    private $tagRepo;
    private $tags;

    public function __construct(TagRepositoryInterface $tagRepo)
    {
        $this->tagRepo = $tagRepo;
        $this->tags = collect();
    }

    public function add($tagName)
    {
        // $tag = $this->tagRepo->findByName($tagName);

        // create tag if not exist

        $tag = $this->tagRepo->create(['name' => $tagName]);

        $this->tags->push(new PostTag([
          'tag_id' => $tag->id
        ]));
    }

    public function addMany(array $tags)
    {
        // make sure tags is not empty
        if(count($tags) == 0) {
            return;
        }

        foreach ( $tags as $tag ) {
            $this->add($tag['value']);
        }
    }

    /**
    * Return tags
    */
    public function list()
    {
        return $this->tags;
    }
}
