<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | TaskFlow</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family:'Plus Jakarta Sans',sans-serif;
            background:#020617;
            color:#e2e8f0;
        }

        .glass{
            background:rgba(255,255,255,.03);
            backdrop-filter:blur(12px);
            border:1px solid rgba(255,255,255,.1);
        }

        .modal-overlay{
            opacity:0;
            pointer-events:none;
            transition:.3s;
            z-index:50;
        }

        .modal-overlay:target{
            opacity:1;
            pointer-events:auto;
        }

        .modal-box{
            transform:scale(.95);
            transition:.3s;
        }

        .modal-overlay:target .modal-box{
            transform:scale(1);
        }
    </style>
</head>

<body class="flex">

    <!-- SIDEBAR -->
    <aside class="w-64 glass border-r border-white/10 flex flex-col fixed h-full p-6 z-10">

        <div class="flex items-center gap-2 text-blue-500 font-bold text-xl mb-10">

            <i class="fa-solid fa-layer-group"></i>

            TaskFlow.

        </div>

        <p class="text-[10px] text-slate-500 font-bold tracking-widest uppercase mb-4">
            Workspace
        </p>

        <nav class="space-y-2 mb-8">

            <a href="{{ route('dashboard') }}"
               class="block px-4 py-2 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold">

                <i class="fa-solid fa-house mr-2"></i>
                Dashboard

            </a>

            <a href="{{ route('planners.index') }}"
               class="block px-4 py-2 rounded-xl hover:bg-white/5 transition text-slate-400">

                <i class="fa-solid fa-list-check mr-2"></i>
                My Tasks

            </a>

        </nav>

        <p class="text-[10px] text-slate-500 font-bold tracking-widest uppercase mb-4">
            Account
        </p>

        <nav class="space-y-2 flex-1">

            <a href="{{ route('profile.edit') }}"
               class="block px-4 py-2 rounded-xl hover:bg-white/5 transition text-slate-400">

                <i class="fa-solid fa-user-gear mr-2"></i>
                Settings

            </a>

        </nav>

        @if(auth()->user()->role === 'admin')

        <p class="text-[10px] text-purple-500 font-bold tracking-widest uppercase mb-4 mt-8">
            Admin Zone
        </p>

        <nav class="space-y-2 mb-8">

            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-2 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20 font-bold">

                <i class="fa-solid fa-shield-halved mr-2"></i>
                Admin Panel

            </a>

        </nav>

        @endif

        <form method="POST"
              action="{{ route('logout') }}"
              class="mt-auto">

            @csrf

            <button type="submit"
                    class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-500/10 rounded-xl transition text-sm font-bold">

                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>
                Logout

            </button>

        </form>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 ml-64 p-8 max-w-7xl">

        <!-- HEADER -->
        <div class="flex justify-between items-start mb-10">

            <div>

                <h1 class="text-3xl font-bold text-white">

                    Welcome back, {{ auth()->user()->name }}! 👋

                </h1>

                <p class="text-slate-400 mt-1">

                    Cek progres tugas lu hari ini.

                </p>

            </div>

        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

            <div class="glass p-6 rounded-3xl relative overflow-hidden">

                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">

                    Total Tasks

                </p>

                <h3 class="text-4xl font-extrabold mt-2 text-white">

                    {{ $totalTasks }}

                </h3>

                <div class="absolute bottom-4 right-4 w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-400">

                    <i class="fa-solid fa-briefcase"></i>

                </div>

            </div>

            <div class="glass p-6 rounded-3xl relative overflow-hidden">

                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">

                    Completed

                </p>

                <h3 class="text-4xl font-extrabold mt-2 text-white">

                    {{ $completedTasks }}

                </h3>

                <div class="absolute bottom-4 right-4 w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

            </div>

            <div class="glass p-6 rounded-3xl relative overflow-hidden">

                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">

                    In Progress

                </p>

                <h3 class="text-4xl font-extrabold mt-2 text-white">

                    {{ $inProgressTasks }}

                </h3>

                <div class="absolute bottom-4 right-4 w-12 h-12 rounded-2xl bg-yellow-500/10 flex items-center justify-center text-yellow-400">

                    <i class="fa-solid fa-spinner"></i>

                </div>

            </div>

            <div class="glass p-6 rounded-3xl relative overflow-hidden">

                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">

                    Files Hosted

                </p>

                <h3 class="text-4xl font-extrabold mt-2 text-white">

                    {{ $filesHosted }}

                </h3>

                <div class="absolute bottom-4 right-4 w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-400">

                    <i class="fa-solid fa-file-shield"></i>

                </div>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- DEADLINES -->
            <div class="lg:col-span-2">

                <div class="flex items-center gap-3 mb-5">

                    <div class="w-10 h-10 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400">

                        <i class="fa-solid fa-calendar-days"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold text-white">

                            Upcoming Deadlines

                        </h2>

                        <p class="text-sm text-slate-500">

                            Semua task deadline aktif.

                        </p>

                    </div>

                </div>

                <div class="space-y-4">

                    @forelse($upcomingDeadlines as $task)

                    <a href="#taskModal-{{ $task->id }}"
                       class="glass p-5 rounded-3xl border-l-4 border-red-500 flex justify-between items-start hover:border-red-400 hover:bg-white/[0.02] transition group">

                        <div class="flex-1">

                            <div class="flex items-center gap-4 mb-3">

                                <div class="w-12 h-12 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 text-lg">

                                    <i class="fa-solid fa-list-check"></i>

                                </div>

                                <div>

                                    <h3 class="font-bold text-xl text-white group-hover:text-red-400 transition">

                                        {{ $task->title }}

                                    </h3>

                                    <p class="text-sm text-slate-500 mt-1">

                                        {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}

                                    </p>

                                </div>

                            </div>

                            <p class="text-sm text-slate-400">

                                {{ $task->description ?: 'Tidak ada deskripsi task.' }}

                            </p>

                        </div>

                        <span class="px-3 py-2 bg-red-500/10 text-red-400 text-[10px] font-bold uppercase rounded-2xl border border-red-500/20">

                            {{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}

                        </span>

                    </a>

                    <!-- MODAL -->
                    <div id="taskModal-{{ $task->id }}"
                         class="modal-overlay fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center">

                        <div class="modal-box glass w-full max-w-2xl mx-4 rounded-[2rem] overflow-hidden border border-white/10">

                            <!-- HEADER -->
                            <div class="p-8 border-b border-white/5">

                                <div class="flex justify-between items-start gap-4">

                                    <div>

                                        <div class="flex items-center gap-4 mb-5">

                                            <div class="w-16 h-16 rounded-3xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 text-2xl">

                                                <i class="fa-solid fa-calendar-check"></i>

                                            </div>

                                            <div>

                                                <p class="text-xs uppercase tracking-[3px] text-slate-500 font-bold mb-1">

                                                    Task Detail

                                                </p>

                                                <h2 class="text-3xl font-bold text-white">

                                                    {{ $task->title }}

                                                </h2>

                                            </div>

                                        </div>

                                        <div class="flex items-center gap-3 flex-wrap">

                                            <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-4 py-2 rounded-2xl text-xs font-bold uppercase">

                                                {{ $task->status }}

                                            </span>

                                            <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-4 py-2 rounded-2xl text-xs font-bold uppercase">

                                                {{ \Carbon\Carbon::parse($task->deadline)->format('d F Y') }}

                                            </span>

                                        </div>

                                    </div>

                                    <a href="#"
                                       class="w-12 h-12 rounded-2xl bg-white/5 hover:bg-red-500/20 hover:text-red-400 flex items-center justify-center transition text-slate-400">

                                        <i class="fa-solid fa-xmark text-xl"></i>

                                    </a>

                                </div>

                            </div>

                            <!-- BODY -->
                            <div class="p-8 space-y-8">

                                <div>

                                    <p class="text-xs uppercase tracking-[3px] text-slate-500 font-bold mb-4">

                                        Description

                                    </p>

                                    <div class="glass rounded-3xl p-6 border border-white/5 text-slate-300 leading-relaxed">

                                        {{ $task->description ?: 'Tidak ada deskripsi task.' }}

                                    </div>

                                </div>

                                @if($task->attachments->count())

                                <div>

                                    <p class="text-xs uppercase tracking-[3px] text-slate-500 font-bold mb-4">

                                        Attachment

                                    </p>

                                    <div class="glass rounded-3xl p-5 border border-white/5 flex items-center justify-between">

                                        <div class="flex items-center gap-4">

                                            <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-xl">

                                                <i class="fa-solid fa-file"></i>

                                            </div>

                                            <div>

                                                <p class="text-white font-semibold">

                                                    {{ $task->attachments->first()->filename }}

                                                </p>

                                                <p class="text-xs text-slate-500 mt-1">

                                                    {{ number_format($task->attachments->first()->size / 1024,2) }} KB

                                                </p>

                                            </div>

                                        </div>

                                        <a href="{{ route('planners.download',$task->id) }}"
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-3 rounded-2xl font-semibold transition">

                                            Download

                                        </a>

                                    </div>

                                </div>

                                @endif

                            </div>

                        </div>

                    </div>

                    @empty

                    <div class="glass p-10 rounded-3xl text-center text-slate-500">

                        Tidak ada deadline task.

                    </div>

                    @endforelse

                </div>

            </div>

            <!-- RECENT ACTIVITY -->
            <div>

                <div class="flex items-center gap-3 mb-5">

                    <div class="w-10 h-10 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">

                        <i class="fa-solid fa-clock-rotate-left"></i>

                    </div>

                    <div>

                        <h2 class="text-2xl font-bold text-white">

                            Recent Activities

                        </h2>

                        <p class="text-sm text-slate-500">

                            Aktivitas terbaru akun kamu.

                        </p>

                    </div>

                </div>

                <div class="space-y-4">

                    @forelse($recentActivities as $activity)

                    @php
                        $logData = explode('|',$activity->action);
                        $actionType = $logData[0];
                        $taskTitle = $logData[1] ?? '';

                        $actionText = '';
                        $icon = 'fa-bolt';
                        $iconColor = 'text-blue-500';
                        $iconBg = 'bg-blue-500/10';

                        switch($actionType){

                            case 'created_planner':
                                $actionText = 'Task Baru Ditambahkan';
                                $icon = 'fa-plus';
                                $iconColor = 'text-emerald-500';
                                $iconBg = 'bg-emerald-500/10';
                            break;

                            case 'uploaded_document':
                                $actionText = 'Berhasil Upload Dokumen';
                                $icon = 'fa-upload';
                                $iconColor = 'text-blue-500';
                                $iconBg = 'bg-blue-500/10';
                            break;

                            case 'deleted_planner':
                                $actionText = 'Task Dihapus';
                                $icon = 'fa-trash-can';
                                $iconColor = 'text-red-500';
                                $iconBg = 'bg-red-500/10';
                            break;

                            case 'updated_status_to_completed':
                                $actionText = 'Task Diselesaikan';
                                $icon = 'fa-check-double';
                                $iconColor = 'text-emerald-500';
                                $iconBg = 'bg-emerald-500/10';
                            break;

                            case 'updated_status_to_pending':
                                $actionText = 'Task Batal Selesai';
                                $icon = 'fa-rotate-left';
                                $iconColor = 'text-yellow-500';
                                $iconBg = 'bg-yellow-500/10';
                            break;

                        }
                    @endphp

                    <div class="glass rounded-3xl p-5 flex items-center gap-4 border border-white/5 hover:border-blue-500/20 transition">

                        <div class="w-14 h-14 {{ $iconBg }} rounded-2xl flex items-center justify-center {{ $iconColor }} text-xl">

                            <i class="fa-solid {{ $icon }}"></i>

                        </div>

                        <div class="flex-1">

                            <p class="text-white font-semibold">

                                {{ $actionText }}

                            </p>

                            <p class="text-xs text-slate-500 mt-2">

                                {{ $taskTitle }}

                            </p>

                        </div>

                        <span class="text-[10px] text-slate-500">

                            {{ $activity->created_at->diffForHumans() }}

                        </span>

                    </div>

                    @empty

                    <div class="glass p-10 rounded-3xl text-center text-slate-500">

                        Belum ada aktivitas.

                    </div>

                    @endforelse

                </div>

            </div>

        </div>

    </main>

</body>
</html>