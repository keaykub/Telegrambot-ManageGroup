<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Telegram\TelegramApiController;
use Illuminate\Http\Request;
use Telegram\Bot\Api;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramService;

class TelegramController extends Controller
{
    protected $telegramService;
    protected $telegramApi;

    public function __construct(TelegramService $telegramService, TelegramApiController $telegramApi)
    {
        $this->telegramService  = $telegramService;
        $this->telegramApi      = $telegramApi;
    }

    public function webhook(Request $request)
    {
        $telegram   = $this->telegramService->getTelegram();

        $adminId    = env('ADMIN_ID');
        $update     = $telegram->getWebhookUpdate();

        $userId     = $update->getMessage()->getChat()->getId();
        $text       = $update->getMessage()->getText();
        $userName   = $update->getMessage()->getChat()->getUsername();
        $fName      = $update->getMessage()->getChat()->getFirstName();
        $lName      = $update->getMessage()->getChat()->getLastName();
        $language   = $update->getMessage()->getChat()->getLanguageCode();

        $chatId     = env('GROUP_CHAT_ID');
        $token      = env('TELEGRAM_BOT_TOKEN');

        $this->telegramApi->checkUserInDb($fName, $lName, $userName, $userId, $language);

        if($userId == $adminId){
            if($text == '/start'){
                $this->telegramApi->AdminPanel($userId);
            }else if($text == '🔑 Code 1 วัน'){
                $code = $this->telegramApi->createCode();
                $this->telegramApi->saveCode($code, "D1");
                $telegram->sendMessage([
                    'chat_id' => $userId,
                    'text' => $code,
                ]);
            }else if($text == '🔑 Code 7 วัน'){
                $code = $this->telegramApi->createCode();
                $this->telegramApi->saveCode($code, "D7");
                $telegram->sendMessage([
                    'chat_id' => $userId,
                    'text' => $code,
                ]);
            }else if($text == '🔑 Code 30 วัน'){
                $code = $this->telegramApi->createCode();
                $this->telegramApi->saveCode($code, "D30");
                $telegram->sendMessage([
                    'chat_id' => $userId,
                    'text' => $code,
                ]);
            }else if($text == '🔑 Code 1 ชม.'){
                $code = $this->telegramApi->createCode();
                $this->telegramApi->saveCode($code, "H1");
                $telegram->sendMessage([
                    'chat_id' => $userId,
                    'text' => $code,
                ]);
            }else if($text == '▶️ เพิ่มเติม'){
                $telegram->sendMessage([
                    'chat_id' => $userId,
                    'text' => 'เพิ่มเติม',
                ]);
            }
        }else{
            if($text == '/start'){
                $this->telegramApi->UserPanel($userId, );
            }else if($text == 'เข้ากลุ่มซิกนอล 📈'){
                $telegram->sendMessage([
                    'chat_id' => $userId,
                    'text' => 'พิมพ์เลข Code 6 หลัก',
                ]);
            }else if($text == 'ตรวจสอบวันหมดอายุ ⌛️'){
                $this->telegramApi->checkExpireCode($userId);
            }else if($text == 'test'){
                $this->telegramApi->checkUserExpriedGroup($userId);
            }else{
                if(preg_match('/^\d{6}$/', $text)){
                    $this->telegramApi->checkCodeUser($userId, $text, $fName);
                }
            }
        }

        return response('OK', 200);
    }

    public function setWebhooksub(Request $request)
    {
        $urlWebhook = $request->webhookUrl;
        $botToken   = env('TELEGRAM_BOT_TOKEN');
        $apiUrl = "https://api.telegram.org/bot{$botToken}/setWebhook";

        $client = new Client();
        $response = $client->post($apiUrl, [
            'json' => [
                'url' => $urlWebhook,
            ],
        ]);

        $responseBody = json_decode($response->getBody(), true);
        return response()->json($responseBody);
    }

    public function setWebhook()
    {
        $botToken   = env('TELEGRAM_BOT_TOKEN');
        $webhookUrl = env('LINK_SETUP_TELEGRAM') . '/telegram/webhook';
        $apiUrl = "https://api.telegram.org/bot{$botToken}/setWebhook";

        $client = new Client();
        $response = $client->post($apiUrl, [
            'json' => [
                'url' => $webhookUrl,
            ],
        ]);

        $responseBody = json_decode($response->getBody(), true);

        return response()->json($responseBody);
    }

    public function deleteWebhook()
    {
        $botToken   = env('TELEGRAM_BOT_TOKEN');
        $apiUrl = "https://api.telegram.org/bot{$botToken}/deleteWebhook";

        $client = new Client();
        $response = $client->post($apiUrl);

        $responseBody = json_decode($response->getBody(), true);
        return response()->json($responseBody);
    }
}
