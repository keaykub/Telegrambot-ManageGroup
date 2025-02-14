<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManageUser extends Model
{
    public static function getUsers()
    {
        $users = DB::table('code_telegram')
                ->select(
                    'CTEG_CODEID',
                    'CTEG_USERID',
                    'CTEG_USERNAME',
                    'CTEG_CODESTATUS',
                    'CTEG_CODEDAY',
                    'CTEG_CODE_JOINDATE'
                )
                ->get();
        return $users;
    }

    public static function deleteUser($codeid)
    {
        $result = DB::table('code_telegram')
                ->where('CTEG_CODEID', $codeid)
                ->delete();

        return $result;
    }
}
