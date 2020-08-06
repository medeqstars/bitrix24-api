<?php


namespace Medeq\Bitrix24\src\Models\Task;


use Illuminate\Support\Arr;
use Medeq\Bitrix24\Facades\Bitrix24;

class Builder extends \Medeq\Bitrix24\Models\Builder
{
    public function insert() : ? int
    {
        $result = Bitrix24::call($this->getPath('create'), [
            'fields' => $this->model->toArray(),
        ]);

        if(!$result) return null;

        return Arr::get($result, 'result.task.id');
    }
}
