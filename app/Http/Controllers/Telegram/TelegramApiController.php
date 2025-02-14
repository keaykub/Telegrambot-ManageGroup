<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramService;
use App\Models\TelegramManage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use Telegram\Bot\Api;
use GuzzleHttp\Client;

class TelegramApiController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function AdminPanel($userId)
    {
        $keyboard = $this->getKeyboardAdmin();
        $message = 'หน้าหลัก🏠';
        $this->sendMessage($userId, $message, $keyboard);
        return "success";
    }

    private function getKeyboardAdmin()
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => '🔑 Code 1 วัน'],
                    ['text' => '🔑 Code 7 วัน'],
                    ['text' => '🔑 Code 30 วัน']
                ],
                [
                    ['text' => '🔑 Code 1 ชม.'],
                    ['text' => '▶️ เพิ่มเติม']
                ]
            ],
            'resize_keyboard' => true
        ]);
    }

    private function sendMessage($userId, $message, $keyboard)
    {
        $telegram = $this->telegramService->getTelegram();
        try {
            $telegram->sendMessage([
                'chat_id' => $userId,
                'text' => $message,
                'reply_markup' => $keyboard
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
        }
    }

    public function createCode()
    {
        $randomCode = rand(100000, 999999);
        while (true) {
            $code = TelegramManage::checkRepeatCodeDb($randomCode);
            if ($code == null) {
                break;
            }
            $randomCode = rand(100000, 999999);
        }

        return $randomCode;
    }

    public function saveCode($code, $day)
    {
        TelegramManage::saveCreateCodeDb($code, $day);
    }

    public function checkUserInDb($fName, $lName, $userName, $userId, $language)
    {
        $userData = [
            'fName'    => $fName,
            'lName'    => $lName,
            'userName' => $userName,
            'userId'   => $userId,
            'language' => $language
        ];
        TelegramManage::checkUser($userData);
    }

    public function userPanel($userId)
    {
        $keyboard = $this->getKeyboardUser();
        $message = 'หน้าหลัก🏠';
        $this->sendMessage($userId, $message, $keyboard);
        return "success";
    }

    private function getKeyboardUser()
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => 'เข้ากลุ่มซิกนอล 📈']
                ],
                [
                    ['text' => 'ตรวจสอบวันหมดอายุ ⌛️']
                ]
            ],
            'resize_keyboard' => true
        ]);
    }

    public function checkCodeUser($userId, $code, $fName)           //ACTIVATE = พร้อมใช้งาน, INACTIVE = ใช้งานแล้ว, N = ไม่มีอยู่ในระบบ, closed = ปิดใช้งานไปแล้ว
    {
        $codeStatus = TelegramManage::checkRepeatCodeDb($code);
        $message = '';
        if($code){
            $statusCode = $codeStatus->CTEG_CODESTATUS ?? 'N';
            if($statusCode == 'ACTIVE'){
                TelegramManage::updateCodeUser($userId, $code, $fName);
                $this->unbanUserInChat($userId);
                $link = $this->createLinkInvite(env('GROUP_CHAT_ID'), env('TELEGRAM_BOT_TOKEN'));
                $message = $link['invite_link'];
                $message = "กดที่ลิงก์เพื่อเข้ากลุ่มภายใน 5 นาที\n\n✅ " . $message;
                $this->sendMessage($userId, $message, null);
            }else if($statusCode == 'INACTIVE'){
                $message = 'รหัสนี้ถูกใช้งานแล้ว';
                $this->sendMessage($userId, $message, null);
            }else if($statusCode == 'N'){
                $message = 'รหัสไม่ถูกต้อง';
                $this->sendMessage($userId, $message, null);
            }
        }
    }

    public function createLinkInvite($chatId, $token)
    {
        $url = "https://api.telegram.org/bot{$token}/createChatInviteLink";
        $expire_date = time() + 300;

        $payload = [
            'chat_id' => $chatId,
            'member_limit' => 1,
            'expire_date' => $expire_date,
        ];

        $response = Http::post($url, $payload);
        $data = $response->json();
        if (!empty($data['result'])) {
            return $data['result'];
        } else {
            return "มีข้อผิดพลาด รบกวนติดต่อแอดมิน";
        }
    }

    public function unbanUserInChat($userId)
    {
        $telegram = $this->telegramService->getTelegram();
        $response = $telegram->unbanChatMember([
            'chat_id' => env('GROUP_CHAT_ID'),
            'user_id' => $userId,
        ]);
    }

    public function checkExpireCode($userId)
    {
        $codeStatus = TelegramManage::checkExpireCodeDb($userId);
        $codeStatus = empty($codeStatus) ? "n" : $codeStatus;
        $message = '';
        if($codeStatus){
            $statusCode = $codeStatus->CTEG_CODESTATUS ?? 'N';
            if($statusCode == 'INACTIVE'){
                $remainingDays = $this->findExpiredTimeUser($userId);
                $message = 'รหัสยังไม่หมดอายุ';
                $message = $this->checkTimeExpire($codeStatus);
                $message ="💬 ข้อมูลผู้ใช้งาน\n\n" .
                "🆔 $userId\n" .
                "▶️ $message\n\n" .
                "⏳ เหลือเวลา: " .
                "$remainingDays";
                $this->sendMessage($userId, $message, null);
            }else{
                $message = 'คุณไม่ได้อยู่ในกลุ่ม';
                $message ="💬 ข้อมูลผู้ใช้งาน\n\n" .
                "🆔 $userId\n" .
                "▶️ คุณไม่ได้อยู่ในกลุ่ม";
                $this->sendMessage($userId, $message, null);
            }
        }else{
            $message ="💬 ข้อมูลผู้ใช้งาน\n\n" .
            "🆔 $userId\n" .
            "▶️ คุณไม่ได้อยู่ในกลุ่ม";
            $this->sendMessage($userId, $message, null);
        }
    }

    public function checkTimeExpire($codeStatus)
    {
        $codeDay = $codeStatus->CTEG_CODEDAY;
        $timeJoinGroup = $codeStatus->CTEG_CODE_JOINDATE;
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $timeJoinGroup);

        $message = '';
        if($codeDay == 'D1'){
            $endTime = $startTime->copy()->addDay();
            $expiryDateTime = $endTime->toDateTimeString();
            $expiryDateTimeCustom = $endTime->format('d-m-Y H:i:s');
            $message = $expiryDateTimeCustom;
        }else if($codeDay == 'D7'){
            $endTime = $startTime->copy()->addDays(7);
            $expiryDateTime = $endTime->toDateTimeString();
            $expiryDateTimeCustom = $endTime->format('d-m-Y H:i:s');
            $message = $expiryDateTimeCustom;
        }else if($codeDay == 'D30'){
            $endTime = $startTime->copy()->addDays(30);
            $expiryDateTime = $endTime->toDateTimeString();
            $expiryDateTimeCustom = $endTime->format('d-m-Y H:i:s');
            $message = $expiryDateTimeCustom;
        }else if($codeDay == 'H1'){
            $endTime = $startTime->copy()->addHour();
            $expiryDateTime = $endTime->toDateTimeString();
            $expiryDateTimeCustom = $endTime->format('d-m-Y H:i:s');
            $message = $expiryDateTimeCustom;
        }
        return $message;
    }

    public function checkUserExpriedGroup($userId){
        $dataReturn = TelegramManage::getDataUserGroupDb();
        if ($dataReturn->isEmpty()) {
            Log::info('No data found in the database.');
        }else{
            foreach ($dataReturn as $data) {
                $codeUserId = $data->CTEG_USERID;
                $codeDate = $data->CTEG_CODE_JOINDATE;
                $codeDay = $data->CTEG_CODEDAY;
                $codeId = $data->CTEG_CODEID;

                $currentTime = Carbon::now();
                $codeTime = Carbon::parse($codeDate);
                switch ($codeDay) {
                    case 'D1':
                        $expireTime = $codeTime->addDay();
                        break;
                    case 'D7':
                        $expireTime = $codeTime->addDays(7);
                        break;
                    case 'D30':
                        $expireTime = $codeTime->addDays(30);
                        break;
                    case 'H1':
                        $expireTime = $codeTime->addHour();
                        break;
                    default:
                        $expireTime = $codeTime;
                        break;
                }
                $remainingTime = $expireTime->diffForHumans($currentTime, syntax: true);
                if ($expireTime->isPast()) {
                    $this->banUserInGroup($codeUserId);
                    $status = TelegramManage::updateStatusCodeDb($codeId, "CLOSED");
                } else {
                    Log::info("$remainingTime ที่เหลืออยู่");
                }
            }
        }
    }

    public function banUserInGroup($userId){
        $telegram = $this->telegramService->getTelegram();
        $status = $telegram->kickChatMember([
            'chat_id' => env('GROUP_CHAT_ID'),
            'user_id' => $userId,
        ]);

        if($status == 1){
            return 'success';
        }
    }

    public function findExpiredTimeUser($userId)
    {
        $dataReturn = TelegramManage::findUserDataInGroupDb($userId);

        if (!$dataReturn) {
            Log::info('No data found in the database for user ID: ' . $userId);
            return 'No data found';
        }

        $codeUserId = $dataReturn->CTEG_USERID ?? null;
        $codeDate = $dataReturn->CTEG_CODE_JOINDATE ?? null;
        $codeDay = $dataReturn->CTEG_CODEDAY ?? null;

        if (!$codeDate || !$codeDay) {
            Log::warning('Missing required data for user ID: ' . $userId);
            return 'Invalid data';
        }

        $currentTime = Carbon::now();
        $codeTime = Carbon::parse($codeDate);

        $expireTime = $codeTime;

        switch ($codeDay) {
            case 'D1':
                $expireTime = $codeTime->addDay();
                break;
            case 'D7':
                $expireTime = $codeTime->addDays(7);
                break;
            case 'D30':
                $expireTime = $codeTime->addDays(30);
                break;
            case 'H1':
                $expireTime = $codeTime->addHour();
                break;
            default:
                Log::warning('Unknown codeDay: ' . $codeDay . ' for user ID: ' . $userId);
                break;
        }

        $remainingTime = $currentTime->greaterThan($expireTime)
            ? 'Expired'
            : $expireTime->diffForHumans($currentTime, syntax: true);

        return $remainingTime;
    }
}
