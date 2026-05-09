<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Tasks | TaskFlow</title>

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
class="block px-4 py-2 rounded-xl hover:bg-white/5 transition text-slate-400">
<i class="fa-solid fa-house mr-2"></i>
Dashboard
</a>

<a href="{{ route('planners.index') }}"
class="block px-4 py-2 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold">
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

<form method="POST" action="{{ route('logout') }}" class="mt-auto">
@csrf

<button type="submit"
class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-500/10 rounded-xl transition text-sm font-bold">
<i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>
Logout
</button>

</form>

</aside>

<!-- MAIN -->
<main class="flex-1 ml-64 p-8">

<h1 class="text-4xl font-bold text-white mb-10">
Task Manager
</h1>

@if(session('success'))

<div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl">
{{ session('success') }}
</div>

@endif

@if ($errors->any())

<div class="fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center z-50">

<div class="glass p-8 rounded-[2rem] max-w-md w-full mx-4 border border-red-500/30 text-center">

<div class="w-20 h-20 rounded-full bg-red-500/20 border border-red-500/30 flex items-center justify-center text-red-500 text-3xl mx-auto mb-5">
<i class="fa-solid fa-circle-exclamation"></i>
</div>

<h2 class="text-3xl font-bold text-white mb-3">
Upload Ditolak
</h2>

<p class="text-slate-400 text-sm mb-6">
File tidak valid. Hanya PDF, DOC, dan DOCX.
</p>

<div class="glass rounded-3xl p-5 mb-6 text-left">

<ul class="space-y-2 text-sm text-red-400">

@foreach ($errors->all() as $error)

<li>• {{ $error }}</li>

@endforeach

</ul>

</div>

<a href="{{ route('planners.index') }}"
class="block w-full py-3 rounded-2xl bg-red-600 hover:bg-red-700 transition text-white font-bold">
Oke Mengerti
</a>

</div>

</div>

@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

<!-- FORM -->
<div>

<div class="glass p-6 rounded-[2rem] sticky top-8">

<h2 class="text-2xl font-bold text-white mb-6">
New Task
</h2>

<form action="{{ route('planners.store') }}"
method="POST"
enctype="multipart/form-data"
class="space-y-5">

@csrf

<div>

<label class="text-xs text-slate-400 block mb-2">
Title
</label>

<input type="text"
name="title"
required
class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white outline-none focus:border-blue-500">

</div>

<div>

<label class="text-xs text-slate-400 block mb-2">
Description
</label>

<textarea name="description"
rows="4"
class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white outline-none focus:border-blue-500"></textarea>

</div>

<div>

<label class="text-xs text-slate-400 block mb-2">
Deadline
</label>

<input type="date"
name="deadline"
class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3 text-white outline-none">

</div>

<div>

<label class="text-xs text-slate-400 block mb-2">
Attachment
</label>

<input type="file"
name="document"
accept=".pdf,.doc,.docx"
class="text-[11px] text-slate-500 file:bg-white/10 file:border-none file:px-4 file:py-2 file:text-white file:rounded-xl">

<p class="text-[10px] text-slate-500 mt-2">
Hanya PDF, DOC, dan DOCX.
</p>

</div>

<button type="submit"
class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-bold py-3 rounded-2xl shadow-lg shadow-blue-500/20">
Create Task
</button>

</form>

</div>

</div>

<!-- TASKS -->
<div class="lg:col-span-2 space-y-5">

@forelse($planners as $task)

<div class="glass p-6 rounded-[2rem] border-l-4 {{ $task->status === 'completed' ? 'border-emerald-500' : 'border-blue-500' }}">

<div class="flex justify-between gap-6">

<div class="flex-1">

<div class="flex items-center gap-3 mb-3">

<h2 class="text-2xl font-bold {{ $task->status === 'completed' ? 'line-through text-slate-500' : 'text-white' }}">
{{ $task->title }}
</h2>

<span class="px-3 py-1 rounded-xl text-[10px] uppercase font-bold tracking-wider
{{ $task->status === 'completed'
? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
: 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' }}">

{{ $task->status }}

</span>

</div>

@if($task->description)

<p class="text-slate-400 mb-5">
{{ $task->description }}
</p>

@endif

@if($task->deadline)

<div class="flex items-center gap-2 text-sm text-red-400 mb-5">

<i class="fa-solid fa-calendar-days"></i>

{{ \Carbon\Carbon::parse($task->deadline)->format('d F Y') }}

<span class="text-slate-500">
• {{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}
</span>

</div>

@endif

@if($task->attachments->count())

<div class="flex items-center gap-3 mb-5">

<div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">

<i class="fa-solid fa-file"></i>

</div>

<div>

<p class="text-white font-semibold">
{{ $task->attachments->first()->filename }}
</p>

<p class="text-[11px] text-slate-500">
{{ number_format($task->attachments->first()->size / 1024,2) }} KB
</p>

</div>

</div>

@endif

<div class="flex flex-wrap gap-3">

<!-- COMPLETE -->
<form action="{{ route('planners.status',$task->id) }}"
method="POST">

@csrf
@method('PATCH')

<button type="submit"
class="px-4 py-2 rounded-2xl text-sm font-bold transition
{{ $task->status === 'completed'
? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 hover:bg-yellow-500/20'
: 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20' }}">

<i class="fa-solid {{ $task->status === 'completed' ? 'fa-rotate-left' : 'fa-check' }} mr-2"></i>

{{ $task->status === 'completed' ? 'Undo' : 'Complete' }}

</button>

</form>

<!-- DOWNLOAD -->
@if($task->attachments->count())

<a href="{{ route('planners.download',$task->id) }}"
class="px-4 py-2 rounded-2xl text-sm font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 hover:bg-blue-500/20 transition">

<i class="fa-solid fa-download mr-2"></i>

Download

</a>

@endif

<!-- DETAIL -->
<a href="#taskModal-{{ $task->id }}"
class="px-4 py-2 rounded-2xl text-sm font-bold bg-white/5 text-white border border-white/10 hover:bg-white/10 transition">

<i class="fa-solid fa-eye mr-2"></i>

Detail

</a>

<!-- DELETE -->
<a href="#deleteTask-{{ $task->id }}"
class="px-4 py-2 rounded-2xl text-sm font-bold bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition">

<i class="fa-solid fa-trash mr-2"></i>

Delete

</a>

</div>

</div>

</div>

</div>

<!-- DETAIL MODAL -->
<div id="taskModal-{{ $task->id }}"
class="modal-overlay fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center">

<div class="modal-box glass w-full max-w-2xl mx-4 rounded-[2rem] overflow-hidden border border-white/10">

<div class="p-8 border-b border-white/5">

<div class="flex justify-between items-start gap-4">

<div>

<div class="flex items-center gap-4 mb-5">

<div class="w-16 h-16 rounded-3xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-2xl">

<i class="fa-solid fa-list-check"></i>

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

@if($task->deadline)

<span class="bg-red-500/10 text-red-400 border border-red-500/20 px-4 py-2 rounded-2xl text-xs font-bold uppercase">
{{ \Carbon\Carbon::parse($task->deadline)->format('d F Y') }}
</span>

@endif

</div>

</div>

<a href="#"
class="w-12 h-12 rounded-2xl bg-white/5 hover:bg-red-500/20 hover:text-red-400 flex items-center justify-center transition text-slate-400">

<i class="fa-solid fa-xmark text-xl"></i>

</a>

</div>

</div>

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

<!-- DELETE MODAL -->
<div id="deleteTask-{{ $task->id }}"
class="modal-overlay fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center">

<div class="modal-box glass max-w-md w-full mx-4 rounded-[2rem] border border-red-500/20 p-8 text-center">

<div class="w-20 h-20 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 text-3xl mx-auto mb-5">

<i class="fa-solid fa-triangle-exclamation"></i>

</div>

<h2 class="text-3xl font-bold text-white mb-3">
Hapus Task?
</h2>

<p class="text-slate-400 mb-8">

Task
<span class="font-bold text-white">
{{ $task->title }}
</span>
akan dihapus permanen.

</p>

<div class="flex gap-4">

<a href="#"
class="flex-1 py-3 rounded-2xl bg-white/5 hover:bg-white/10 text-white font-bold transition">

Batal

</a>

<form action="{{ route('planners.destroy',$task->id) }}"
method="POST"
class="flex-1">

@csrf
@method('DELETE')

<button type="submit"
class="w-full py-3 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-bold transition">

Ya, Hapus

</button>

</form>

</div>

</div>

</div>

@empty

<div class="glass rounded-[2rem] p-16 text-center text-slate-500">

Belum ada task.

</div>

@endforelse

</div>

</div>

</main>

</body>
</html>