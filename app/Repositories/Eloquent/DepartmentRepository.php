<?php

namespace App\Repositories\Eloquent;

use App\Models\Department;
use App\Repositories\Interfaces\DepartmentRepositoryInterface;


class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{

    public function __construct(Department $model)
    {
        return parent::__construct($model);
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
