<?php


namespace Medeq\Bitrix24\Models\Task;


use Medeq\Bitrix24\Models\Model;
use Medeq\Bitrix24\src\Models\Task\Builder;

/**
 * Class Task
 * @package Bitrix24\task
 *
 * @property string $id
 * @method static Task|null create(array $attributes)
 */
class Task extends Model
{
    protected $builder = Builder::class;

    public function paths(): array
    {
        return [
            'create' => 'tasks.task.add',
            'update' => 'tasks.task.update',
            'find' => 'tasks.task.get',
            'all' => 'tasks.task.list',
            'get' => 'tasks.task.list',
            'fields' => 'tasks.task.fields',
        ];
    }
}
