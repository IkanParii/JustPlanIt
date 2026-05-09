<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | TaskFlow</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        body{
            font-family:'Plus Jakarta Sans',sans-serif;
            background:#020617;
            background-image:
                radial-gradient(circle at top left, rgba(59,130,246,.15), transparent 30%),
                radial-gradient(circle at bottom right, rgba(37,99,235,.12), transparent 30%);
        }

        .glass{
            background:rgba(255,255,255,.03);
            backdrop-filter:blur(12px);
            border:1px solid rgba(255,255,255,.08);
        }

        .sidebar-item:hover{
            background:rgba(59,130,246,.1);
            border-right:3px solid #3b82f6;
        }

        .input{
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.08);
            color:white;
        }

        .input:focus{
            outline:none;
            border-color:#3b82f6;
            box-shadow:0 0 0 3px rgba(59,130,246,.15);
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

            <a href="{{ route('dashboard') }}"
               class="sidebar-item flex items-center gap-3 px-6 py-3 text-slate-300 transition-all">

                <i class="fa-solid fa-house w-5"></i>
                Dashboard
            </a>

            <a href="{{ route('planners.index') }}"
               class="sidebar-item flex items-center gap-3 px-6 py-3 text-slate-300 transition-all">

                <i class="fa-solid fa-list-check w-5"></i>
                My Tasks
            </a>

            <a href="{{ route('profile.edit') }}"
               class="sidebar-item flex items-center gap-3 px-6 py-3 bg-blue-500/10 text-blue-400 border-r-2 border-blue-500 transition-all">

                <i class="fa-solid fa-user-gear w-5"></i>
                Settings
            </a>

        </nav>

        <div class="p-4 border-t border-white/5">

            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition cursor-pointer">

                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=fff"
                     class="w-8 h-8 rounded-full border border-blue-500/50">

                <div class="overflow-hidden">

                    <p class="text-sm font-semibold truncate text-white">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-slate-500 truncate">
                        Account Settings
                    </p>

                </div>

            </div>

        </div>

    </aside>

    <!-- MAIN -->
    <main class="flex-1 ml-64 p-8">

        <!-- HEADER -->
        <div class="mb-10">

            <h1 class="text-3xl font-bold text-white tracking-tight">
                Account Settings
            </h1>

            <p class="text-slate-400 mt-1">
                Kelola profile dan keamanan akun kamu.
            </p>

        </div>

        <!-- PROFILE -->
        <div class="glass rounded-3xl p-8 mb-8">

            <div class="flex items-center gap-3 mb-6">

                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 text-lg border border-blue-500/20">

                    <i class="fa-solid fa-user"></i>

                </div>

                <div>

                    <h2 class="text-xl font-bold text-white">
                        Profile Information
                    </h2>

                    <p class="text-sm text-slate-400">
                        Update nama dan email akun kamu.
                    </p>

                </div>

            </div>

            <form method="POST"
                  action="{{ route('profile.update') }}"
                  class="space-y-5">

                @csrf
                @method('patch')

                <div>

                    <label class="text-sm text-slate-400 mb-2 block">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="input w-full rounded-2xl px-4 py-3">

                </div>

                <div>

                    <label class="text-sm text-slate-400 mb-2 block">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="input w-full rounded-2xl px-4 py-3">

                </div>

                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 transition rounded-2xl py-3 px-6 font-semibold shadow-lg shadow-blue-500/20 text-white">

                    Save Changes

                </button>

            </form>

        </div>

        <!-- PASSWORD -->
        <div class="glass rounded-3xl p-8">

            <div class="flex items-center gap-3 mb-6">

                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-400 text-lg border border-purple-500/20">

                    <i class="fa-solid fa-lock"></i>

                </div>

                <div>

                    <h2 class="text-xl font-bold text-white">
                        Update Password
                    </h2>

                    <p class="text-sm text-slate-400">
                        Gunakan password yang kuat dan aman.
                    </p>

                </div>

            </div>

            <form method="POST"
                  action="{{ route('password.update') }}"
                  class="space-y-5">

                @csrf
                @method('put')

                <div>

                    <label class="text-sm text-slate-400 mb-2 block">
                        Current Password
                    </label>

                    <input
                        type="password"
                        name="current_password"
                        required
                        class="input w-full rounded-2xl px-4 py-3">

                </div>

                <div>

                    <label class="text-sm text-slate-400 mb-2 block">
                        New Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="input w-full rounded-2xl px-4 py-3">

                </div>

                <div>

                    <label class="text-sm text-slate-400 mb-2 block">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="input w-full rounded-2xl px-4 py-3">

                </div>

                <button
                    type="submit"
                    class="bg-purple-500 hover:bg-purple-600 transition rounded-2xl py-3 px-6 font-semibold shadow-lg shadow-purple-500/20 text-white">

                    Save Password

                </button>

            </form>

        </div>

    </main>

</body>
</html>