<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ManageUser;
use Illuminate\Support\Facades\Log;

class ManageUserController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }

        return view('admin.manageusers');
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $users = ManageUser::getUsers();
            return DataTables::of($users)->make(true);
        }
    }

    public function deleteuser(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->ADMIN_ROLE !== 'ADMIN' && $user->ADMIN_ROLE !== 'TESTER') {
            abort(403, 'Unauthorized');
        }

        $codeid = $request->input('code');
        $result = ManageUser::deleteUser($codeid);
        return response()->json(data: $result);
    }

}
