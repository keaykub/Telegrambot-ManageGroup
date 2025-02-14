<?php
namespace App\Services;
use Telegram\Bot\Api;

class TelegramService
{
    protected $telegram;
    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    public function getTelegram()
    {
        return $this->telegram;
    }

}

