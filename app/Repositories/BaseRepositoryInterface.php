<?php namespace App\Repositories;

use Illuminate\Http\Request;

interface BaseRepositoryInterface
{
    public function create(array $attributes);
    public function findById($id);
    public function all();
    public function deleteById($id);
    public function updateById(array $attributes, $id);
}
