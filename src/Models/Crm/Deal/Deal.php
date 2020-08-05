<?php

namespace Medeq\Bitrix24\Models\Crm\Deal;

use Medeq\Bitrix24\Models\Crm\Model;

/**
 * Class Deal
 * @package Medeq\Bitrix24\Models\Crm\Deal
 *
 * @method static Deal find(int $id)
 * @property int $id
 * @property string $title
 * @property int $type_id
 * @property int $stage_id
 * @property string $probability
 * @property int $currency_id
 * @property string $opportunity
 * @property int $company_id
 * @property int $contact_id
 * @property string $begindate
 * @property string $closedate
 * @property string $opened
 * @property string $closed
 * @property string $comments
 * @property int $assigned_by_id
 * @property int $created_by_id
 * @property int $modify_by_id
 * @property string $date_create
 * @property string $date_modify
 * @property int $lead_id
 * @property string $additional_info
 * @property int $originator_id
 * @property int $origin_id
 * @property int $category_id
 */
class Deal extends Model
{
    protected $mappedFields = [
        'uf_crm_5af2ba267d40b' => 'type',
    ];

    public function paths(): array
    {
        return [
            'find'      => 'crm.deal.get',
            'all'       => 'crm.deal.list',
            'get'       => 'crm.deal.list',
            'fields'    => 'crm.deal.fields',
            'create'    => 'crm.deal.add',
            'update'    => 'crm.deal.update',
        ];
    }

    public function alreadyInTurn(): bool
    {
        return in_array($this->assigned_by_id, [
            config('bitrix24.repeat_deal_assigned_id'),
            config('bitrix24.new_deal_assigned_id'),
        ]);
    }

    public function getUrlAttribute()
    {
        return config('bitrix24.domain'). "/crm/deal/details/{$this->id}/";
    }

    public function addComments($comments): Deal
    {
        if ($this->comments) {
            $this->comments .= "<br><br>";
        }

        $this->comments .= $comments;

        return $this;
    }
}
