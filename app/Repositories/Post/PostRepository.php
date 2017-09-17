<?php namespace App\Repositories\Post;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\PostTag\PostTagRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Models\Post;
use App\Models\PostTag;


class PostRepository implements PostRepositoryInterface
{

    private $post;
    private $postTagRepo;
    private $userRepo;

    public function __construct(Post $post,
        PostTagRepositoryInterface $postTagRepo,
        UserRepositoryInterface $userRepo)
    {
        $this->post = $post;
        $this->postTagRepo = $postTagRepo;
        $this->userRepo = $userRepo;
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
            throw new NotFoundHttpException("Post with id of {$id} not found!");
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
        $post = $this->findById($id);

        $post = $post->delete();

        return $post;
    }

    public function updateById(array $attributes, $id)
    {
        $post = $this->findById($id);

        $updatedPost = $this->save($post, $attributes);

        return $updatedPost;
    }

    private function save(Post $post, array $attributes)
    {
        $authorId = $attributes['author']['id'] ?? 0;
        // check author if exist
        $this->userRepo->findById($authorId);

        $post->post_title = $attributes['post']['title'];
        $post->post_body = $attributes['post']['body'];
        $post->post_author = $authorId;
        $post->save();

        // return an empty array if tags key is not exist
        $tags = $attributes['tags'] ?? [];

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
