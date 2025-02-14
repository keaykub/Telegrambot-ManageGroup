<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class TelegramManage extends Model
{
    use HasFactory;

    public static function checkUser($request)
    {
        $fName       = $request['fName'] ?: null;
        $lName       = $request['lName'] ?: null;
        $userId      = $request['userId'] ?: null;
        $userName    = $request['userName'] ?: null;
        $language    = $request['language'] ?: 'TH';

        $user = DB::table('user_telegram')->where('UTEG_USERID', $userId)->first();

        if (!$user) {
            DB::table('user_telegram')->insert([
                'UTEG_FNAME'        => $fName,
                'UTEG_LNAME'        => $lName,
                'UTEG_USERNAME'     => $userName,
                'UTEG_USERID'       => $userId,
                'UTEG_USERLANGUAGE' => $language,
                'UTEG_STATUS'       => 'ACTIVE',
                'UTEG_CREATETIME'   => now(), // เวลาปัจจุบัน
            ]);
        }
    }

    public static function checkRepeatCodeDb($code)
    {
        $code = DB::table('code_telegram')->where('CTEG_CODEID', $code)->first();
        return $code;
    }

    public static function saveCreateCodeDb($code, $day)
    {
        $status = DB::table('code_telegram')->insert([
            'CTEG_CODEID'           => $code,
            'CTEG_USERID'           => 'NULL',
            'CTEG_USERNAME'         => 'NULL',
            'CTEG_CODESTATUS'       => 'ACTIVE',
            'CTEG_CODEDAY'          => $day,
            'CTEG_CODE_NAMECREATE'  => 'ADMIN-KEAY',
            'CTEG_CODE_CREATEDATE'  => now(),
            'CTEG_CODE_JOINDATE'    => now(),
        ]);
    }

    public static function updateCodeUser($userId, $code, $fName)
    {
        $status = DB::table('code_telegram')->where('CTEG_CODEID', $code)->update([
            'CTEG_USERID'        => $userId,
            'CTEG_USERNAME'      => $fName,
            'CTEG_CODESTATUS'    => 'INACTIVE',
            'CTEG_CODE_JOINDATE' => now(),
        ]);

        return $status;
    }

    public static function checkInActiveCodeDb($code)
    {
        $code = DB::table('code_telegram')->where('CTEG_CODEID', $code)->where('CTEG_CODESTATUS', 'ACTIVE')->first();
        return $code;
    }

    public static function checkExpireCodeDb($userId)
    {
        $code = DB::table('code_telegram')->where('CTEG_USERID', $userId)->where('CTEG_CODESTATUS', 'INACTIVE')->first();
        return $code;
    }

    public static function getDataUserGroupDb()
    {
        $data = DB::table('code_telegram')->where('CTEG_CODESTATUS', 'INACTIVE')->get();
        return $data;
    }

    public static function updateStatusCodeDb($code, $statusCode)
    {
        $status = DB::table('code_telegram')->where('CTEG_CODEID', $code)->update([
            'CTEG_CODESTATUS'    => $statusCode
        ]);
        return $status;
    }

    public static function findUserDataInGroupDb($userId)
    {
        $data = DB::table('code_telegram')->where('CTEG_USERID', $userId)->where('CTEG_CODESTATUS', 'INACTIVE')->first();
        return $data;
    }
}
