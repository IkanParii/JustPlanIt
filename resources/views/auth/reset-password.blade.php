<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | TaskFlow</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            border:1px solid rgba(255,255,255,.08);
            backdrop-filter:blur(12px);
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
    </style>
</head>

<body class="min-h-screen flex items-center justify-center text-white px-6">

<div class="w-full max-w-md glass rounded-3xl p-8 shadow-2xl">

    <div class="text-center mb-8">

        <div class="flex justify-center mb-4">

            <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 text-2xl border border-blue-500/20">

                <i class="fa-solid fa-key"></i>

            </div>

        </div>

        <h1 class="text-3xl font-bold text-white">
            Reset Password
        </h1>

        <p class="text-slate-400 mt-2 text-sm">
            Buat password baru untuk akun kamu.
        </p>

    </div>

    <form method="POST"
          action="{{ route('password.store') }}"
          class="space-y-5">

        @csrf

        <input type="hidden"
               name="token"
               value="{{ $request->route('token') }}">

        <div>

            <label class="text-sm text-slate-400 mb-2 block">
                Email Address
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                class="input w-full rounded-2xl px-4 py-3"
                placeholder="you@example.com">

            @error('email')
                <p class="text-red-400 text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <div>

            <label class="text-sm text-slate-400 mb-2 block">
                New Password
            </label>

            <input
                type="password"
                name="password"
                required
                class="input w-full rounded-2xl px-4 py-3"
                placeholder="••••••••">

            @error('password')
                <p class="text-red-400 text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror

        </div>

        <div>

            <label class="text-sm text-slate-400 mb-2 block">
                Confirm Password
            </label>

            <input
                type="password"
                name="password_confirmation"
                required
                class="input w-full rounded-2xl px-4 py-3"
                placeholder="••••••••">

        </div>

        <button
            type="submit"
            class="w-full bg-blue-500 hover:bg-blue-600 transition rounded-2xl py-3 font-semibold shadow-lg shadow-blue-500/20">

            Reset Password

        </button>

    </form>

</div>

</body>
</html>