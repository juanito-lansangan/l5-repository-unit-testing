<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Post\PostRepositoryInterface;

/**
 * Class PostController
 *
 * @SWG\Definition(
 *     definition="StaticPost",
 *     required={"title","body"},
 *     @SWG\Property(property="title", type="string", example="Lorem ipsum dolor sit amet, consectetur adipisicing elit."),
 *     @SWG\Property(property="body", type="string", example="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."),
 * )
 *
 * @SWG\Definition(
 *     definition="Author",
 *     @SWG\Property(property="id", type="string", example="15"),
 * )
 *
 * @SWG\Definition(
 *     definition="Post",
 *     @SWG\Property(property="post", ref="#/definitions/StaticPost"),
 *     @SWG\Property(property="tags", type="array", @SWG\Items(ref="#/definitions/Tag")),
 *     @SWG\Property(property="author", ref="#/definitions/Author"),
 * )
 *
 * @SWG\Definition(
 *     definition="Tag",
 *     @SWG\Property(property="value", type="string", example="Tag1"),
 * )
 * @package App\Http\Controllers
 */
class PostController extends BaseController
{
    private $postRepo;

    public function __construct(PostRepositoryInterface $postRepo)
    {
        $this->postRepo = $postRepo;
    }

    /**
     * @SWG\Get(path="/posts",
     *   tags={"info/posts"},
     *   summary="Post list",
     *   description="Return the list of posts",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *   )
     * )
     */
    public function index()
    {
        $posts = $this->postRepo->all();
        $this->setMeta(['count' => $posts->count()]);
        return $this->sendResponse($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @SWG\Post(
     *     path="/posts",
     *     summary="Insert Post",
     *     tags={"data/post"},
     *     @SWG\Parameter(
     *          name="post",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              ref="#/definitions/Post"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="A user object",
     *         @SWG\Schema(ref="#/definitions/ResponseModel")
     *     ),
     * )
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $this->postRepo->create($request->all());
        $this->setMeta(['resource_id' => $post->id]);

        return $this->sendResponse([
            'message' => 'Record added successfully'
        ]);
    }

    /**
     * @SWG\Get(path="/posts/{id}",
     *   tags={"info/posts"},
     *   summary="Get post by id",
     *   description="Return single post",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="id",
     *     type="string",
     *     in="path"
     *     ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *   )
     * )
     */
    public function getByPostId($id)
    {
        $post = $this->postRepo->findById($id);
        return $this->sendResponse($post);
    }

    /**
     * Update Post.
     *
     * @SWG\Put(
     *     path="/posts/{id}",
     *     summary="Update Post",
     *     tags={"data/post"},
     *   @SWG\Parameter(
     *     name="id",
     *     type="string",
     *     in="path"
     *     ),
     *     @SWG\Parameter(
     *          name="post",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              ref="#/definitions/Post"
     *          )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="A user object",
     *         @SWG\Schema(ref="#/definitions/ResponseModel")
     *     ),
     * )
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function updateByPostId(Request $request, $id)
    {
        $post = $this->postRepo->updateById($request->all(), $id);
        return $this->sendResponse([
            'message' => 'Record updated successfully'
        ]);
    }

    /**
     * Delete Post.
     *
     * @SWG\Delete(
     *     path="/posts/{id}",
     *     summary="Delete Post",
     *     tags={"data/post"},
     *   @SWG\Parameter(
     *     name="id",
     *     type="string",
     *     in="path"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Delete a Post",
     *         @SWG\Schema(ref="#/definitions/ResponseModel")
     *     ),
     * )
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function deleteByPostId($id)
    {
        $this->postRepo->deleteById($id);

        return $this->sendResponse('Post successfully deleted', 200);
    }
}
