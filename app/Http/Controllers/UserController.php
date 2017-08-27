<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;

class UserController extends Controller
{
    private $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        return $this->userRepo->all();
    }

    public function getUserById($id)
    {
        return $this->userRepo->findById($id);
    }

    public function store(Request $request)
    {
        return $this->userRepo->create($request->all());
    }

    public function updateUserById(Request $request, $id)
    {
        return $this->userRepo->updateById($request->all(), $id);
    }
}
