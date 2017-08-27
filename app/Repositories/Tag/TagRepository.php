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

    public function getById($id)
    {
        return $this->tag->find($id);
    }

    public function findById($id)
    {
        $tag = $this->getById($id);

        if ( !$tag ) {
            return [
                'status' => 'error',
                'message' => "Tag with id of {$id} not found!"
            ];
        }
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
        $tag = $this->getById($id);

        if ( !$tag ) {
            return [
                'status' => 'error',
                'message' => "Tag with id of {$id} not found!"
            ];
        }

        $tag = $tag->fill($attributes);
        
        $tag->save();

        return [
            'status' => 'ok',
            'updated' => $tag
        ];
    }
}
