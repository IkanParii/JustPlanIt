<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | TaskFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(29, 78, 216, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 100% 100%, rgba(30, 64, 175, 0.1) 0%, transparent 40%);
        }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-item:hover { background: rgba(59, 130, 246, 0.1); border-right: 3px solid #3b82f6; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    </style>
</head>
<body class="text-slate-200 min-h-screen flex">

    <aside class="w-64 glass border-r border-white/10 flex flex-col fixed h-full z-10">
        <div class="p-6">
            <div class="flex items-center gap-3 text-blue-500 font-bold text-xl">
                <i class="fa-solid fa-layer-group"></i>
                <span class="text-white tracking-tight">TaskFlow<span class="text-blue-500">.</span></span>
            </div>
        </div>

        <nav class="flex-1 mt-4">
            <div class="px-4 mb-4 text-xs font-semibold text-slate-500 uppercase tracking-widest">Main Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 bg-blue-500/10 text-blue-400 border-r-2 border-blue-500 transition-all">
                <i class="fa-solid fa-chart-line w-5"></i> Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center gap-3 px-6 py-3 text-slate-300 transition-all">
                <i class="fa-solid fa-users w-5"></i> Manage Users
            </a>
        </nav>

        <div class="p-4 border-t border-white/5">
            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition cursor-pointer">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=3b82f6&color=fff" class="w-8 h-8 rounded-full border border-blue-500/50">
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold truncate text-white">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-slate-500 truncate">System Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8 relative">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Activity Control Center</h1>
                <p class="text-slate-400 mt-1">Monitoring sistem TaskFlow secara real-time.</p>
            </div>
            <div class="flex gap-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 px-4 py-2 rounded-lg transition text-sm font-semibold">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="glass p-6 rounded-2xl relative overflow-hidden group">
                <p class="text-slate-400 text-sm font-medium">Total Users</p>
                <h3 class="text-4xl font-bold mt-2 text-white">{{ $totalUsers }}</h3>
                <p class="text-blue-400 text-xs mt-2 font-semibold">User Terdaftar</p>
            </div>
            <div class="glass p-6 rounded-2xl relative overflow-hidden group">
                <p class="text-slate-400 text-sm font-medium">Storage Used</p>
                <h3 class="text-4xl font-bold mt-2 text-white">{{ $totalFilesGB }} GB</h3>
                <p class="text-emerald-400 text-xs mt-2 font-semibold">Total Dokumen Aman</p>
            </div>
            <div class="glass p-6 rounded-2xl relative overflow-hidden group">
                <p class="text-slate-400 text-sm font-medium">System Status</p>
                <h3 class="text-4xl font-bold mt-2 text-white">Active</h3>
                <p class="text-blue-400 text-xs mt-2 font-semibold flex items-center gap-1"><span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span> Monitoring On</p>
            </div>
        </div>

        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-list-ul text-blue-500"></i> Audit Trail
                </h2>
                <span class="text-xs text-slate-400 bg-white/5 px-3 py-1 rounded-full border border-white/10">Menampilkan Log Terbaru</span>
            </div>

            <div class="glass rounded-2xl overflow-hidden border border-white/5">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-900/50 text-slate-400 text-xs uppercase tracking-widest font-semibold border-b border-white/10">
                        <tr>
                            <th class="px-6 py-4">Timestamp</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Activity</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm">
                        @forelse($logs as $log)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="px-6 py-4 text-slate-400">{{ $log->created_at->format('d M, H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center text-[10px] font-bold">
                                        {{ strtoupper(substr($log->user->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <span class="text-slate-200">{{ $log->user->name ?? 'Unknown User' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-200">
                                {{ ucwords(str_replace('_', ' ', $log->action)) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-500/10 text-green-400 px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border border-green-500/20">Success</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada aktivitas yang terekam.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>