<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Planner;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $totalUsers = User::count();

        $totalTasks = Planner::count();

        $logs = ActivityLog::with('user')
            ->latest()
            ->take(20)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        $storagePath = storage_path('app');

        $totalBytes = 0;

        if (File::exists($storagePath)) {

            foreach (File::allFiles($storagePath) as $file) {
                $totalBytes += $file->getSize();
            }
        }

        $totalFilesGB = round($totalBytes / 1024 / 1024 / 1024, 2);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTasks',
            'logs',
            'totalFilesGB'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | MANAGE USERS
    |--------------------------------------------------------------------------
    */

    public function users()
    {
        $users = User::withCount('planners')
            ->latest()
            ->get();

        return view('admin.users', compact('users'));
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $user = User::findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | DELETE STORAGE
            |--------------------------------------------------------------------------
            */

            $userFolder = storage_path('app/users/' . $user->id);

            if (File::exists($userFolder)) {
                File::deleteDirectory($userFolder);
            }

            /*
            |--------------------------------------------------------------------------
            | DELETE RELATED DATA
            |--------------------------------------------------------------------------
            */

            if (method_exists($user, 'planners')) {
                $user->planners()->delete();
            }

            if (method_exists($user, 'logs')) {
                $user->logs()->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | DELETE USER
            |--------------------------------------------------------------------------
            */

            $user->delete();

            /*
            |--------------------------------------------------------------------------
            | SAVE LOG
            |--------------------------------------------------------------------------
            */

            ActivityLog::create([
                'user_id'    => auth()->id(),
                'action'     => 'delete_user|' . $user->name,
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}