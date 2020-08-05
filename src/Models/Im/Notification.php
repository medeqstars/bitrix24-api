<?php


namespace Medeq\Bitrix24\Models\Im;

use Medeq\Bitrix24\Facades\Bitrix24;

/**
 * Class Notification
 * @package Medeq\Bitrix24\Models\Im
 *
 * @property string|null $to
 * @property string|null $type
 * @property string|null $message
 */
class Notification
{
    protected $to;
    protected $type;
    protected $message;

    public static function make() : self
    {
        return new static;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function message($message) : self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return $this
     */
    public function system() : self
    {
        $this->type = 'SYSTEM';

        return $this;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function to($to) : self
    {
        $this->to = $to;

        return $this;
    }

    public function send()
    {
        Bitrix24::call('im.notify', [
            'to' => $this->to,
            'type' => $this->type,
            'message' => $this->message,
        ]);
    }
}