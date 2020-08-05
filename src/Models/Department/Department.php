<?php


namespace Medeq\Bitrix24\Models\Department;

use Medeq\Bitrix24\Models\Model;

/**
 * Class Department
 * @package Medeq\Bitrix24\Models\Department
 *
 * @property int $id
 * @property string $name
 * @property string $sort
 * @property string $parent
 * @property string $uf_head
 * @property string|null $page
 * @method static Department|null create(array $attributes)
 */
class Department extends Model
{
    public function paths(): array
    {
        return [
            'fields'    => 'department.fields',
            'create'    => 'department.add',
            'update'    => 'department.update',
            'all'       => 'department.get',
            'get'       => 'department.get',
        ];
    }
}