<?php

namespace App\Http\Controllers;

use App\Models\Planner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlannerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $planners = auth()->user()
            ->planners()
            ->with('attachments')
            ->latest()
            ->get();

        return view('planners.index', compact('planners'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'nullable|date',

            // HANYA PDF / DOC / DOCX
            'document'    => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [

            'document.mimes' =>
                'File ditolak! Hanya PDF, DOC, dan DOCX yang diperbolehkan.',

            'document.max' =>
                'Ukuran file maksimal 10MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE PLANNER
        |--------------------------------------------------------------------------
        */

        $planner = auth()->user()->planners()->create([
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
            'status'      => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FILE UPLOAD
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('document')) {

            $file = $request->file('document');

            $filename =
                time() . '_' .
                str_replace(' ', '_', $file->getClientOriginalName());

            $path = $file->storeAs(
                'attachments',
                $filename,
                'public'
            );

            $planner->attachments()->create([
                'filename'  => $filename,
                'path'      => $path,
                'size'      => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            $actionLog = 'uploaded_document';

        } else {

            $actionLog = 'created_planner';
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE LOG
        |--------------------------------------------------------------------------
        */

        auth()->user()->activityLogs()->create([
            'action'     => $actionLog,
            'ip_address' => $request->ip(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('planners.index')
            ->with('success', 'Task berhasil dibuat.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, Planner $planner)
    {
        if ($planner->user_id !== auth()->id()) {
            abort(403);
        }

        $newStatus =
            ($planner->status === 'completed')
            ? 'pending'
            : 'completed';

        $planner->update([
            'status' => $newStatus
        ]);

        auth()->user()->activityLogs()->create([
            'action'     => 'updated_status_to_' . $newStatus,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Status berhasil diupdate.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Planner $planner)
    {
        if ($planner->user_id !== auth()->id()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE FILES
        |--------------------------------------------------------------------------
        */

        foreach ($planner->attachments as $attachment) {

            if (Storage::disk('public')->exists($attachment->path)) {

                Storage::disk('public')->delete($attachment->path);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE PLANNER
        |--------------------------------------------------------------------------
        */

        $planner->delete();

        return redirect()
            ->route('planners.index')
            ->with('success', 'Task berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */

    public function download(Planner $planner)
    {
        if ($planner->user_id !== auth()->id()) {
            abort(403);
        }

        $attachment = $planner->attachments()->first();

        if (
            !$attachment ||
            !Storage::disk('public')->exists($attachment->path)
        ) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $attachment->path,
            $attachment->filename
        );
    }
}