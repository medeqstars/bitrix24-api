<?php


namespace Medeq\Bitrix24\Models\User;


use Medeq\Bitrix24\Models\Model;

/**
 * Class User
 * @package Bitrix24\user
 *
 * @property int $id
 * @property string $active
 * @property string $email
 * @property string $name
 * @property string $last_name
 * @property string $second_name
 * @property string $personal_gender
 * @property string $personal_profession
 * @property string $personal_www
 * @property string $personal_birthday
 * @property string $personal_photo
 * @property string $personal_icq
 * @property string $personal_phone
 * @property string $personal_fax
 * @property string $personal_mobile
 * @property string $personal_pager
 * @property string $personal_street
 * @property string $personal_city
 * @property string $personal_state
 * @property string $personal_zip
 * @property string $personal_country
 * @property string $work_company
 * @property string $work_position
 * @property string|array $uf_department
 * @property string $uf_interests
 * @property string $uf_skills
 * @property string $uf_web_sites
 * @property string $uf_xing
 * @property string $uf_linkedin
 * @property string $uf_facebook
 * @property string $uf_twitter
 * @property string $uf_skype
 * @property string $uf_district
 * @property string $uf_phone_inner
 * @method static User find(int $id)
 */
class User extends Model
{
    protected $botPost = 'БОТ';

    public function paths(): array
    {
        return [
            'fields' => 'user.fields',
            'create' => 'user.add',
            'update' => 'user.update',
            'get' => 'user.get',
            'all' => 'user.get',
        ];
    }

    public function isBot(): bool
    {
        return $this->work_position === $this->botPost;
    }
}