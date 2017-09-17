<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;

/**
 * Class UserController
 *
 * @SWG\Definition(
 *     definition="User",
 *     required={"name","email","password"},
 *     @SWG\Property(property="name", type="string", example="Juan Perico"),
 *     @SWG\Property(property="email", type="string", example="juan@gmail.com"),
 *     @SWG\Property(property="password", type="string", example="yoursecretpassword"),
 * )
 *
 * @package App\Http\Controllers
 */
class UserController extends BaseController
{
    private $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @SWG\Get(path="/users",
     *   tags={"info/users"},
     *   summary="User list",
     *   description="Return the list of users",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *   )
     * )
     */
    public function index()
    {
        $users = $this->userRepo->all();
        $this->setMeta(['count' => $users->count()]);
        return $this->sendResponse($users);
    }

    /**
     * @SWG\Get(path="/users/{id}",
     *   tags={"info/users"},
     *   summary="Get user by id",
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
    public function getUserById($id)
    {
        $user = $this->userRepo->findById($id);
        return $this->sendResponse($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @SWG\Post(
     *     path="/users",
     *     summary="Insert User",
     *     tags={"data/user"},
     *     @SWG\Parameter(
     *          name="user",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              ref="#/definitions/User"
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
        return $this->userRepo->create($request->all());
    }

    /**
     * Update user informations
     *
     * @SWG\Put(
     *     path="/users/{id}",
     *     summary="Update User",
     *     tags={"data/user"},
     *   @SWG\Parameter(
     *     name="id",
     *     type="string",
     *     in="path"
     *     ),
     *     @SWG\Parameter(
     *          name="user",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              ref="#/definitions/User"
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
    public function updateUserById(Request $request, $id)
    {
        return $this->userRepo->updateById($request->all(), $id);
    }
}
