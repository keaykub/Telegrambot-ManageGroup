<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManageAdmin extends Model
{
    public static function getAdmins()
    {
        $admins = DB::table('admins_telegram')
                    ->select(
                        'ADMIN_USERNAME',
                        'ADMIN_ROLE',
                        'ADMIN_IDTELEGRAM',
                        'created_at',
                        'updated_at'
                    )
                    ->get();
        return $admins;
    }

    public static function editAdmin($role, $idtelegram, $usercheck)
    {
        Log::info('Role: '.$role . ' ID Telegram: ' . $idtelegram . ' Usercheck: ' . $usercheck);
        $result = DB::table('admins_telegram')
                    ->where('ADMIN_USERNAME', $usercheck)
                    ->update([
                        'ADMIN_ROLE' => $role
                    ]);

        return "success";
    }
}
