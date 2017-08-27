<?php namespace App\Repositories\Post;

use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\PostTag\PostTagRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{

    private $post;
    private $postTagRepo;

    public function __construct(Post $post, PostTagRepositoryInterface $postTagRepo)
    {
        $this->post = $post;
        $this->postTagRepo = $postTagRepo;
    }

    public function create(array $attributes)
    {
        $post = $this->post->create($attributes);
        // return an empty array if tags key is not exist
        $tags = $attributes['tags'] ?? [];
        // can use array or comma delimited
        $tags = is_array($tags) ? $tags : explode(',', $tags);
        // create tags if exist
        $this->postTagRepo->addMany($tags);
        // retrieve tags
        $tags = $this->postTagRepo->list();
        $post = $post->tags()->saveMany($tags);

        return $post;
    }

    public function findById($id)
    {
        return $this
            ->post
            ->where('id', $id)
            ->with(['author', 'tags'])
            ->first();
    }

    public function all()
    {
        return $this
            ->post
            ->with('author')
            ->get();
    }

    public function deleteById($id)
    {
        $post = $this->findById($id);

        if ( !$post ) {
            return [
                'status' => 'error',
                'message' => "Post with id of {$id} not found!"
            ];
        }

        $post = $post->delete();

        return [
            'status' => 'ok',
            'deleted' => $post
        ];
    }

    public function updateById(array $attributes, $id)
    {
        $post = $this->findById($id);

        if ( !$post ) {
            return [
                'status' => 'error',
                'message' => "Post with id of {$id} not found!"
            ];
        }

        $post = $post->fill($attributes)->save();

        return [
            'status' => 'ok',
            'updated' => $post
        ];
    }
}
