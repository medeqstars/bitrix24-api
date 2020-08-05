<?php


namespace Medeq\Bitrix24\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Medeq\Bitrix24\Facades\Bitrix24;

/**
 * Class Builder
 * @package Medeq\Bitrix24
 *
 * @property Model|null $model
 * @property array $where
 * @property string|array|null $order
 * @property string|null $sort
 * @property int|null $limit
 */
class Builder {

    protected $model;
    protected $where = [];
    protected $columns = [];
    protected $order;
    protected $sort;
    protected $limit;

    public function __construct(?Model $model)
    {
        $this->model = $model;
    }

    public function query() : self
    {
        return $this;
    }

    public function select($columns) : self
    {
        $this->columns = array_map('mb_strtoupper', (array) $columns);

        return $this;
    }

    public function orderBy($column, $direction = 'DESC') : self
    {
        $this->sort = $column;
        $this->order = $direction;

        return $this;
    }

    public function latest($column)
    {
        return $this->orderBy($column, 'DESC');
    }

    public function oldest($column)
    {
        return $this->orderBy($column, 'ASC');
    }

    public function find(int $id) : ? Model
    {
        if(! $this->hasFindMethod()) {
            return $this->findOneById($id);
        }

        $result = Bitrix24::call(
            $this->getPath('find'),
            ['id' => $id]
        );

        if(!$result || !isset($result['result'])) return null;

        return $this->model->newInstance($result['result']);
    }

    /**
     * @param string $field
     * @param string $operator
     * @param null $value
     * @return $this
     */
    public function where(string $field, string $operator = '=', $value = null): self
    {
        if (count(func_get_args()) === 2) {
            [$value, $operator] = [$operator, '='];
        }

        if (!in_array($operator, $this->allowedWhereOperators())) {
            return $this;
        }

        if ($operator == '=') {
            $operator = '';
        }

        if ($operator == '!=') {
            $operator = '!';
        }

        if ($operator == 'like') {
            $operator = '%';
        }

        $this->where[$operator . $field] = $value;

        return $this;
    }

    public function orWhere(callable $where): self
    {
        $this->where['LOGIC'] = 'OR';

        call_user_func($where, $this);

        return $this;
    }

    public function whereIn(string $field, array $value): self
    {
        $this->where[$field] = $value;

        return $this;
    }

    public function first()
    {
        return $this->get()->first();
    }

    public function all($columns = []) : Collection
    {
        return $this->select($columns)->get();
    }

    public function fields() : ? Collection
    {
        $result = Bitrix24::call($this->getPath('fields'));

        if(!$result || !isset($result['result'])) return null;

        return new Collection($result['result']);
    }

    public function get() : Collection
    {
        $results = Bitrix24::batch(
            $this->getPath('all'), $this->params()
        );

        return $this->hydrate($results);
    }

    public function limit($limit) : self
    {
        $this->limit = $limit;

        return $this;
    }

    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);

        if($model->save()) {
            return $model;
        }

        return null;
    }

    public function insert() : ? int
    {
        $result = Bitrix24::call(
            $this->getPath('create'), $this->model->toArray()
        );

        if(!$result) return null;

        return Arr::get($result, 'result');
    }

    public function update()
    {
        return !! Bitrix24::call(
            $this->getPath('update'),
            [
                'id' => $this->model->id,
                'fields' => $this->model->toArray(),
            ]
        );
    }

    public function getPath(string $key) : string
    {
        return Arr::get($this->model->paths(), $key);
    }

    protected function hydrate(array $items) : Collection
    {
        return collect($items)->map(function($item) {
            return $this->model->newInstance($item);
        });
    }

    protected function params() : array
    {
        $params = [];

        if($this->where) {
            $params['filter'] = $this->where;
        }

        if($this->columns) {
            $params['select'] = $this->columns;
        }

        if($this->order) {
            $params['order'] = $this->order;
        }

        if($this->sort) {
            $params['sort'] = $this->sort;
        }

        if($this->limit) {
            $params['limit'] = $this->limit;
        }

        return $params;
    }

    protected function hasFindMethod() : bool
    {
        return Arr::has($this->model->paths(), 'find');
    }

    protected function findOneById($id)
    {
        $results = Bitrix24::call($this->getPath('all'), ['id' => $id]);

        return $this->hydrate($results)->first();
    }

    protected function allowedWhereOperators(): array
    {
        return ['=', 'like', '!=', '>', '<', '>=', '<='];
    }

}