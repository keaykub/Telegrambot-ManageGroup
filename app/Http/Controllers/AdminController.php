<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Telegram\TelegramApiController;
use App\Models\AdminManage;
use Carbon\Carbon;

class AdminController extends Controller
{
    protected $telegramApi;
    public function __construct(TelegramApiController $telegramApi)
    {
        $this->telegramApi = $telegramApi;
    }
    public function dashboard()
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }

        $dataChart = AdminManage::getDataForChat();
        $monthName = Carbon::now()->locale('th')->monthName;
        return view('admin.dashboard', compact('dataChart', 'monthName'));
    }
    public function kickUser(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }
        $status = $this->telegramApi->banUserInGroup($request->userid);
        Log::info($status  );
        if ($status == "success") {
            AdminManage::deleteCodeUser($request->code);
        }
        return response()->json(['status' => $status]);
    }

    public function reduceDays(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }
        $code = $request->code;
        $days = $request->days;

        $status = AdminManage::reduceDays($code, $days);
        return response()->json(['status' => 'success']);
    }

    public function plusDays(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }
        $code = $request->code;
        $days = $request->days;

        $status = AdminManage::plusDays($code, $days);
        return response()->json(['status' => 'success']);
    }

    public function reduceDaysAll(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }
        $days = $request->days;

        $status = AdminManage::reduceDaysAll($days);
        return response()->json(['status' => 'success']);
    }

    public function plusDaysAll(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }
        $days = $request->days;

        $status = AdminManage::plusDaysAll($days);
        return response()->json(['status' => 'success']);
    }

}
