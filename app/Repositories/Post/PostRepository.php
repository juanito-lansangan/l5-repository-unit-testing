<?php namespace App\Repositories\Post;

use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\PostTag\PostTagRepositoryInterface;
use App\Models\Post;
use App\Models\PostTag;


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
        $newPost = $this->post;
        return $this->save($newPost, $attributes);
    }

    public function findById($id)
    {
        $post = $this->getById($id);

        if ( !$post ) {
            return [
                'status' => 'error',
                'message' => "Post with id of {$id} not found!"
            ];
        }

        return $post;
    }

    public function getById($id)
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
        $post = $this->getById($id);

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
        $post = $this->getById($id);

        if ( !$post ) {
            return [
                'status' => 'error',
                'message' => "Post with id of {$id} not found!"
            ];
        }

        $updatedPost = $this->save($post, $attributes);

        return [
            'status' => 'ok',
            'updated' => $updatedPost,
        ];
    }

    private function save(Post $post, array $attributes)
    {
        $post = $post->fill($attributes);

        $post->save();
        // return an empty array if tags key is not exist
        $tags = $attributes['tags'] ?? [];
        // can use array or comma delimited
        $tags = is_array($tags) ? $tags : explode(',', $tags);
        // create tags if exist
        $this->postTagRepo->addMany($tags);
        // retrieve tags
        $tags = $this->postTagRepo->list();
        // delete existing attached tags
        PostTag::where('post_id', $post->id)->delete();
        // attach tags to post
        $post->tags()->saveMany($tags);

        return $post;
    }
}
