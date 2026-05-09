<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow | Secure Task Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            background-image: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.15) 0%, transparent 50%);
        }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="text-slate-200 min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
    
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] -z-10"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-[100px] -z-10"></div>

    <div class="text-center z-10 max-w-2xl px-6">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass text-blue-400 text-sm font-bold mb-6">
            <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
            System Online & Secure
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-6">
            Manage your tasks <br> with <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">TaskFlow.</span>
        </h1>
        
        <p class="text-slate-400 text-lg md:text-xl mb-10 leading-relaxed">
            Platform manajemen tugas dengan keamanan tingkat tinggi. IDOR protection, secure file hosting, dan audit log bawaan.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-500/25 w-full sm:w-auto">
                    Go to Workspace <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-500/25 w-full sm:w-auto">
                    Masuk Akun
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-8 py-4 glass hover:bg-white/5 text-white font-bold rounded-2xl transition-all w-full sm:w-auto">
                        Daftar Gratis
                    </a>
                @endif
            @endauth
        </div>
    </div>
</body>
</html>