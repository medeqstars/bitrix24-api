<?php


namespace Medeq\Bitrix24\Models\Crm;

use Medeq\Bitrix24\Models\Model as BaseModel;

abstract class Model extends BaseModel
{
    protected $builder = Builder::class;
}