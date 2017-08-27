<?php namespace App\Repositories\User;

use Illuminate\Http\Request;
use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail($email);
}
