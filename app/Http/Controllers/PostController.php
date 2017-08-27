<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Post\PostRepositoryInterface;

class PostController extends Controller
{
    private $postRepo;

    public function __construct(PostRepositoryInterface $postRepo)
    {
        $this->postRepo = $postRepo;
    }

    public function index()
    {
        return $this->postRepo->all();
    }

    public function store(Request $request)
    {
        return $this->postRepo->create($request->all());
    }

    public function getByPostId($id)
    {
        return $this->postRepo->findById($id);
    }

    public function updateByPostId(Request $request, $id)
    {
        return $this->postRepo->updateById($request->all(), $id);
    }

    public function deleteByPostId($id)
    {
        return $this->postRepo->deleteById($id);
    }
}
