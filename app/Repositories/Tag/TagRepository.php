<?php namespace App\Repositories\Tag;

use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function create(array $attributes)
    {
        return $this->tag->create($attributes);
    }

    public function findById($id)
    {
        return $this->tag->find($id);
    }

    public function findByName($name)
    {
        return $this->tag->where('name', $name)->first();
    }

    public function all()
    {
        return $this->tag->all();
    }

    public function deleteById($id)
    {

    }

    public function updateById(array $attributes, $id)
    {
        $tag = $this->findById($id);

        if ( !$post ) {
            return [
                'status' => 'error',
                'message' => "Tag with id of {$id} not found!"
            ];
        }

        $tag = $tag->fill($attributes)->save();

        return [
            'status' => 'ok',
            'updated' => $tag
        ];
    }
}
