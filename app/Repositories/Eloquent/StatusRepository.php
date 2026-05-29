<?php

namespace App\Repositories\Eloquent;

use App\Models\Status;
use App\Repositories\Interfaces\StatusRepositoryInterface;


class StatusRepository extends BaseRepository implements StatusRepositoryInterface
{
    public function __construct(Status $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->model->findOrFail($id)->update($data);
    }

    public function delete(int $id)
    {
        return $this->model->findOrFail($id)->delete();
    }
}
