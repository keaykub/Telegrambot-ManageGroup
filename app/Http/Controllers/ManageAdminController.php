<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ManageAdmin;
use Illuminate\Support\Facades\Log;

class ManageAdminController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }
        return view('admin.manageadmins');
    }

    public function getAdmins(Request $request)
    {
        if ($request->ajax()) {
            $admins = ManageAdmin::getAdmins();
            return DataTables::of($admins)->make(true);
        }
    }

    public function edit(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }

        $role = $request->input('role');
        $idtelegram = $request->input('idtelegram');
        $usercheck = $request->input('user');

        if($idtelegram == null || $role == null || $usercheck == null){
            $idtelegram = "NO";
        }

        $result = ManageAdmin::editAdmin($role, $idtelegram, $usercheck);

        return response()->json(['status' => $result]);
    }
}
