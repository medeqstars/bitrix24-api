<?php


namespace Medeq\Bitrix24\Models;

use ArrayAccess;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * Class Model
 * @package Bitrix24
 * @method static Model find(int $id)
 * @method static Builder query()
 * @method static Builder select(array|string $columns)
 * @method static Builder where(string $field, string $operator = '=', $value = null)
 * @method static Builder whereIn(string $field, array $value)
 * @method static Builder orWhere(callable $where)
 * @method static Builder latest(string $column)
 * @method static Builder oldest(string $column)
 * @method static Builder limit(int $limit)
 * @method static Collection|null all(array $columns = [])
 * @method static array|Collection|null fields()
 */
abstract class Model implements ArrayAccess
{
    use ForwardsCalls, HasAttributes;

    abstract public function paths() : array;

    protected $mappedFields = [];

    protected $builder = Builder::class;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function toArray() : array
    {
        return $this->attributes;
    }

    public function __toString() : string
    {
        return json_encode($this->attributes);
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo(
            $this->newModelQuery(), $method, $parameters
        );
    }

    public function newModelQuery() : Builder
    {
        return (new $this->builder($this));
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    public function newInstance(array $attributes)
    {
        return new static((array) $attributes);
    }

    public function __get($name)
    {
        $methodName = 'get'.Str::studly($name).'Attribute';

        if(method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }

        return Arr::get($this->attributes, $this->getMappedFieldName($name));
    }

    public function __set($name, $value)
    {
        Arr::set($this->attributes, $this->getMappedFieldName($name), $value);

        return $this;
    }

    public function save() : bool
    {
        if($this->exists()) {
            return $this->performUpdate();
        }

        return $this->performInsert();
    }

    protected function exists() : bool
    {
        return !! $this->id;
    }

    protected function getMappedFieldName($name) : string
    {
        return Arr::get(array_flip($this->mappedFields), $name, $name);
    }

    protected function performUpdate() : bool
    {
        return $this->newModelQuery()->update();
    }

    protected function performInsert() : bool
    {
        $id = $this->newModelQuery()->insert();

        if(!$id) return false;

        $this->id = $id;

        return true;
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return ! is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }
}
