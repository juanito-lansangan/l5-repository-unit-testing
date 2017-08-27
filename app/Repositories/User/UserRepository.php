<?php namespace App\Repositories\User;

use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(array $attributes)
    {
        return $this->user->create($attributes);
    }

    public function all()
    {
        return $this
            ->user
            ->with('posts')
            ->get();
    }

    public function getById($id)
    {
        return $this
            ->user
            ->where('id', $id)
            ->with('posts')
            ->first();
    }

    public function findById($id)
    {
        $user = $this->getById($id);

        if ( !$user ) {
            return [
                'status' => 'error',
                'message' => "User with id of {$id} not found!"
            ];
        }

        return $user;
    }

    public function findByEmail($email)
    {
        return $this
            ->user
            ->where('email', $email)
            ->with('posts')
            ->first();
    }

    public function deleteById($id)
    {

    }

    public function updateById(array $attributes, $id)
    {
        $user = $this->getById($id);

        if ( !$user ) {
            return [
                'status' => 'error',
                'message' => "User with id of {$id} not found!"
            ];
        }

        $user = $user->fill($attributes);

        $user->save();

        return [
            'status' => 'ok',
            'updated' => $user
        ];
    }
}
