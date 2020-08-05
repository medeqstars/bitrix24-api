<?php


namespace Medeq\Bitrix24\Models\Crm\Contact;

use Illuminate\Support\Arr;
use Medeq\Bitrix24\Models\Crm\Model;

/**
 * Class Contact
 * @package Medeq\Bitrix24\Models\Crm\Contact\Contact
 *
 * @property int $id
 * @property string $name
 * @property string $second_name
 * @property string $last_name
 * @property string $photo
 * @property string $birthdate
 * @property int $type_id
 * @property int $source_id
 * @property string $source_description
 * @property string $post
 * @property string $address
 * @property string $address_2
 * @property string $address_city
 * @property int $address_postal_code
 * @property int $address_region
 * @property int $address_province
 * @property int $address_country
 * @property int $address_country_code
 * @property string $opened
 * @property string $comments
 * @property string $export
 * @property int $assigned_by_id
 * @property int $created_by_id
 * @property int $modify_by_id
 * @property string $date_create
 * @property string $date_modify
 * @property int $company_id
 * @property int $lead_id
 * @property array|null $phone
 * @property array|null $email
 * @property string $web
 * @property string $im
 * @property int $originator_id
 * @property int $origin_id
 *
 * @method static Contact|null find(int $id)
 * @method static Contact|null create(array $attributes)
 */
class Contact extends Model
{
    public function paths(): array
    {
        return [
            'find'      => 'Crm.contact.get',
            'all'       => 'Crm.contact.list',
            'get'       => 'Crm.contact.list',
            'fields'    => 'Crm.contact.fields',
            'create'    => 'Crm.contact.add',
            'update'    => 'Crm.contact.update',
        ];
    }

    public function getPlainEmailAttribute()
    {
        return Arr::get($this->email, "0.value");
    }
}
