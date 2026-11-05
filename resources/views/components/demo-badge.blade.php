@if(config('app.demo_mode'))
    <div class="mb-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-amber-200 text-xs backdrop-blur-md">
        <div class="flex items-center gap-2 mb-2 font-bold text-amber-400">
            <i class="fa-solid fa-flask"></i>
            <span>DEMO MODE ACTIVE</span>
        </div>
        <p class="text-slate-300 leading-relaxed mb-3">
            Sistem berjalan dalam mode portofolio demo publik. Registrasi dinonaktifkan, silakan login menggunakan kredensial berikut:
        </p>
        <div class="space-y-1.5 font-mono text-[11px] bg-slate-950/60 p-2.5 rounded-xl border border-white/5">
            <div class="flex items-center justify-between">
                <span class="text-slate-400">Admin:</span>
                <span class="text-emerald-400 font-semibold select-all">admin@demo.com <span class="text-slate-500">/</span> DemoPassword123!</span>
            </div>
            <div class="flex items-center justify-between border-t border-white/5 pt-1">
                <span class="text-slate-400">User:</span>
                <span class="text-blue-400 font-semibold select-all">user@demo.com <span class="text-slate-500">/</span> DemoPassword123!</span>
            </div>
        </div>
    </div>
@endif
