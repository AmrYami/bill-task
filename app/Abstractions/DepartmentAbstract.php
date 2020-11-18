<?php


namespace App\Abstractions;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class DepartmentAbstract
{
    /**
     * @var Model
     */
    protected $model;

    public function create($input)
    {
        $model = $this->model->newInstance($input);
        $model->save();
        return $model;
    }

    public function update($input, $id)
    {
        $query = $this->model->newQuery();
        $model = $query->findOrFail($id);
        $model->fill($input);
        $model->save();
        return $model;
    }

    abstract function find_by(Request $request);


    public function find(int $id, $columns = ['*'])
    {
        $query = $this->model->newQuery();
        return $query->find($id, $columns);
    }

    abstract function save(Request $request, ?int $id = null);


}
