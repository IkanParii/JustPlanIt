<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | TaskFlow</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
          rel="stylesheet">

    <style>
        body{
            font-family:'Plus Jakarta Sans',sans-serif;
            background-color:#020617;
            background-image:
                radial-gradient(circle at 0% 0%, rgba(29,78,216,.15) 0%, transparent 40%),
                radial-gradient(circle at 100% 100%, rgba(30,64,175,.1) 0%, transparent 40%);
        }

        .glass{
            background:rgba(255,255,255,.03);
            backdrop-filter:blur(12px);
            border:1px solid rgba(255,255,255,.1);
        }

        .sidebar-item:hover{
            background:rgba(59,130,246,.1);
            border-right:3px solid #3b82f6;
        }

        .modal{
            opacity:0;
            visibility:hidden;
            transition:.2s ease;
        }

        .modal:target{
            opacity:1;
            visibility:visible;
        }

        .modal-box{
            transform:scale(.95);
            transition:.2s ease;
        }

        .modal:target .modal-box{
            transform:scale(1);
        }

        ::-webkit-scrollbar{
            width:6px;
        }

        ::-webkit-scrollbar-track{
            background:#020617;
        }

        ::-webkit-scrollbar-thumb{
            background:#1e293b;
            border-radius:10px;
        }
    </style>
</head>

<body class="text-slate-200 min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 glass border-r border-white/10 flex flex-col fixed h-full z-10">

        <div class="p-6">

            <div class="flex items-center gap-3 text-blue-500 font-bold text-xl">

                <i class="fa-solid fa-layer-group"></i>

                <span class="text-white tracking-tight">
                    TaskFlow<span class="text-blue-500">.</span>
                </span>

            </div>

        </div>

        <nav class="flex-1 mt-4">

            <div class="px-4 mb-4 text-xs font-semibold text-slate-500 uppercase tracking-widest">
                Main Menu
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-item flex items-center gap-3 px-6 py-3 text-slate-300 transition-all">

                <i class="fa-solid fa-chart-line w-5"></i>
                Dashboard

            </a>

            <a href="{{ route('admin.users') }}"
               class="sidebar-item flex items-center gap-3 px-6 py-3 bg-blue-500/10 text-blue-400 border-r-2 border-blue-500 transition-all">

                <i class="fa-solid fa-users w-5"></i>
                Manage Users

            </a>

        </nav>

        <div class="p-4 border-t border-white/5">

            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition">

                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=3b82f6&color=fff"
                     class="w-8 h-8 rounded-full border border-blue-500/50">

                <div>

                    <p class="text-sm font-semibold text-white">
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </p>

                    <p class="text-xs text-slate-500">
                        System Administrator
                    </p>

                </div>

            </div>

        </div>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 ml-64 p-8">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-10">

            <div>

                <h1 class="text-3xl font-bold text-white tracking-tight">
                    Manage Users
                </h1>

                <p class="text-slate-400 mt-1">
                    Kelola semua user yang terdaftar di TaskFlow.
                </p>

            </div>

            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button type="submit"
                        class="bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 px-4 py-2 rounded-lg transition text-sm font-semibold">

                    Logout

                </button>

            </form>

        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            <div class="glass p-6 rounded-2xl">

                <p class="text-slate-400 text-sm">
                    Total Users
                </p>

                <h3 class="text-4xl font-bold mt-2 text-white">
                    {{ $users->count() }}
                </h3>

                <p class="text-blue-400 text-xs mt-2 font-semibold">
                    User Terdaftar
                </p>

            </div>

            <div class="glass p-6 rounded-2xl">

                <p class="text-slate-400 text-sm">
                    Active Accounts
                </p>

                <h3 class="text-4xl font-bold mt-2 text-white">
                    {{ $users->count() }}
                </h3>

                <p class="text-emerald-400 text-xs mt-2 font-semibold">
                    Semua akun aktif
                </p>

            </div>

            <div class="glass p-6 rounded-2xl">

                <p class="text-slate-400 text-sm">
                    System Status
                </p>

                <h3 class="text-4xl font-bold mt-2 text-white">
                    Stable
                </h3>

                <p class="text-blue-400 text-xs mt-2 font-semibold flex items-center gap-1">

                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>

                    Monitoring On

                </p>

            </div>

        </div>

        <!-- TABLE -->
        <div class="glass rounded-2xl overflow-hidden border border-white/5">

            <table class="w-full">

                <thead class="bg-slate-900/50 text-slate-400 text-xs uppercase tracking-widest border-b border-white/10">

                    <tr>

                        <th class="px-6 py-4 text-left">User</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Tasks</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-white/5">

                    @forelse($users as $user)

                    <tr class="hover:bg-white/[0.02] transition">

                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center text-xs font-bold">

                                    {{ strtoupper(substr($user->name,0,2)) }}

                                </div>

                                <div>

                                    <p class="text-white font-medium">
                                        {{ $user->name }}
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        ID: #{{ $user->id }}
                                    </p>

                                </div>

                            </div>

                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $user->planners_count }}
                        </td>

                        <td class="px-6 py-4">

                            <span class="bg-green-500/10 text-green-400 px-2 py-1 rounded-md text-[10px] font-bold uppercase border border-green-500/20">

                                Active

                            </span>

                        </td>

                        <td class="px-6 py-4 text-right">

                            <a href="#delete-{{ $user->id }}"
                               class="bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 px-4 py-2 rounded-lg transition text-sm font-semibold inline-flex items-center gap-2">

                                <i class="fa-solid fa-trash"></i>

                                Delete

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5"
                            class="text-center py-10 text-slate-500">

                            Belum ada user.

                        </td>

                    </tr>

                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <!-- MODALS OUTSIDE TABLE -->
    @foreach($users as $user)

    <div id="delete-{{ $user->id }}"
         class="modal fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center z-50">

        <div class="modal-box glass p-8 rounded-3xl max-w-md w-full mx-4 border border-red-500/30 text-center">

            <div class="w-16 h-16 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center text-2xl mx-auto mb-4 border border-red-500/30">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">
                Hapus User?
            </h3>

            <p class="text-slate-400 text-sm mb-6">
                Semua data user
                <span class="text-white font-semibold">
                    {{ $user->name }}
                </span>
                akan dihapus permanen.
            </p>
            <div class="flex gap-3">
                <a href="#"
                   class="flex-1 py-3 rounded-2xl bg-white/5 text-white font-bold hover:bg-white/10 transition">
                    Batal
                </a>
                <form action="{{ route('admin.users.destroy',$user->id) }}"
                      method="POST"
                      class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full py-3 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition shadow-lg shadow-red-500/20">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>