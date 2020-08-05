<?php

namespace Medeq\Bitrix24\Models\Crm;

use Illuminate\Support\Arr;
use Medeq\Bitrix24\Facades\Bitrix24;
use Medeq\Bitrix24\Models\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    public function insert() : ? int
    {
        $result = Bitrix24::call($this->getPath('create'), [
            'fields' => $this->model->toArray(),
        ]);

        if(!$result) return null;

        return Arr::get($result, 'result');
    }

    public function orderBy($column, $direction = 'DESC') : BaseBuilder
    {
        $this->order = [$column => $direction];

        return $this;
    }
}