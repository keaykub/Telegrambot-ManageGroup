<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminManage extends Model
{
    public static function getDataForChat()
    {
        $data = DB::table('code_telegram')
                ->select(
                    DB::raw(value: "DATE(CTEG_CODE_CREATEDATE) as date"),
                    DB::raw("COUNT(*) as total")
                )
                ->where('CTEG_CODESTATUS', 'INACTIVE')
                ->whereMonth('CTEG_CODE_CREATEDATE', Carbon::now()->month)
                ->whereYear('CTEG_CODE_CREATEDATE', Carbon::now()->year)
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

        return $data;
    }

    public static function deleteCodeUser($code)
    {
        $status = DB::table('code_telegram')
                ->where('CTEG_CODEID', $code)
                ->update(['CTEG_CODESTATUS' => 'CLOSED']);

        return $status;
    }

    public static function reduceDays($code, $days)
    {
        $getDays = DB::table('code_telegram')
                ->select('CTEG_CODE_JOINDATE')
                ->where('CTEG_CODEID', $code)
                ->first();

        $newDate = Carbon::parse($getDays->CTEG_CODE_JOINDATE)->subDays($days);

        $status = DB::table('code_telegram')
                ->where('CTEG_CODEID', $code)
                ->update(['CTEG_CODE_JOINDATE' => $newDate]);

        return $status;
    }

    public static function plusDays($code, $days)
    {
        $getDays = DB::table('code_telegram')
                ->select('CTEG_CODE_JOINDATE')
                ->where('CTEG_CODEID', $code)
                ->first();

        $newDate = Carbon::parse($getDays->CTEG_CODE_JOINDATE)->addDays($days);

        $status = DB::table('code_telegram')
                ->where('CTEG_CODEID', $code)
                ->update(['CTEG_CODE_JOINDATE' => $newDate]);

        return $status;
    }

    public static function reduceDaysAll($days)
    {
        $status = DB::table('code_telegram')
                ->where('CTEG_CODESTATUS', 'INACTIVE')
                ->update(['CTEG_CODE_JOINDATE' => DB::raw("DATE_SUB(CTEG_CODE_JOINDATE, INTERVAL $days DAY)")]);

        return $status;
    }

    public static function plusDaysAll($days)
    {
        $status = DB::table('code_telegram')
                ->where('CTEG_CODESTATUS', 'INACTIVE')
                ->update(['CTEG_CODE_JOINDATE' => DB::raw("DATE_ADD(CTEG_CODE_JOINDATE, INTERVAL $days DAY)")]);

        return $status;
    }
}
