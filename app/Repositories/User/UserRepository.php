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

    public function findById($id)
    {
        return $this
            ->user
            ->where('id', $id)
            ->with('posts')
            ->first();
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
        $user = $this->findById($id);
        
        if ( !$user ) {
            return [
                'status' => 'error',
                'message' => "User with id of {$id} not found!"
            ];
        }

        $user = $user->fill($attributes)->save();

        return [
            'status' => 'ok',
            'updated' => $user
        ];
    }
}
