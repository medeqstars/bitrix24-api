<?php

namespace Medeq\Bitrix24\Models\Crm\Lead;

use Medeq\Bitrix24\Models\Crm\Model;

/**
 * Class Lead
 * @package Bitrix24\Crm\lead
 *
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string $second_name
 * @property string $last_name
 * @property string $company_title
 * @property int $source_id
 * @property string $source_description
 * @property int $status_id
 * @property string $status_description
 * @property string $post
 * @property string $address
 * @property string $address_2
 * @property string $address_city
 * @property int $address_postal_code
 * @property string $address_region
 * @property string $address_province
 * @property string $address_country
 * @property int $address_country_code
 * @property int $currency_id
 * @property string $opportunity
 * @property string $opened
 * @property string $comments
 * @property int $assigned_by_id
 * @property int $created_by_id
 * @property int $modify_by_id
 * @property string $date_create
 * @property string $date_modify
 * @property int $company_id
 * @property int $contact_id
 * @property string $date_closed
 * @property string $phone
 * @property string $email
 * @property string $web
 * @property string $im
 * @property int $originator_id
 * @property int $origin_id
 *
 * @method static Lead|null find(int $id)
 * @method static Lead|null create(array $attributes)
 */
class Lead extends Model
{
    public function paths(): array
    {
        return [
            'find'      => 'crm.lead.get',
            'all'       => 'crm.lead.list',
            'get'       => 'crm.lead.list',
            'fields'    => 'crm.lead.fields',
            'create'    => 'crm.lead.add',
            'update'    => 'crm.lead.update',
        ];
    }
}