<?php $__env->startSection('title', 'Pengaturan Midtrans'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Midtrans Payment Gateway</h1>
            <p class="text-sm text-slate-500 mt-1">Konfigurasi kunci API Midtrans (Snap) untuk pembayaran online otomatis (QRIS, VA, E-Wallet, Kartu Kredit).</p>
        </div>
        <a href="https://dashboard.midtrans.com/" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
            <span>Buka Dashboard Midtrans</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>

    
    <div class="rounded-2xl border p-5 transition shadow-sm <?php echo e($testResult['success'] ? 'border-emerald-200 bg-emerald-50/80' : ($testResult['status'] === 'unauthorized' ? 'border-amber-200 bg-amber-50/80' : 'border-slate-200 bg-white')); ?>">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 <?php echo e($testResult['success'] ? 'bg-emerald-500 text-white' : ($testResult['status'] === 'unauthorized' ? 'bg-amber-500 text-white' : 'bg-slate-200 text-slate-600')); ?>">
                    <?php if($testResult['success']): ?>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    <?php elseif($testResult['status'] === 'unauthorized'): ?>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    <?php else: ?>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold <?php echo e($testResult['success'] ? 'text-emerald-900' : ($testResult['status'] === 'unauthorized' ? 'text-amber-900' : 'text-slate-900')); ?>">
                            Status Koneksi: <?php echo e($testResult['success'] ? 'Terhubung (Aktif)' : ($testResult['status'] === 'unauthorized' ? 'Kunci Unauthorized / Kadaluarsa' : 'Perlu Konfigurasi')); ?>

                        </span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold <?php echo e($isProduction ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'); ?>">
                            <?php echo e($isProduction ? 'Production' : 'Sandbox (Testing)'); ?>

                        </span>
                    </div>
                    <p class="text-xs mt-0.5 <?php echo e($testResult['success'] ? 'text-emerald-700' : ($testResult['status'] === 'unauthorized' ? 'text-amber-700' : 'text-slate-500')); ?>">
                        <?php echo e($testResult['message']); ?>

                    </p>
                </div>
            </div>

            <form action="<?php echo e(route('admin.midtrans.test')); ?>" method="POST" class="shrink-0">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="px-4 py-2 text-xs font-semibold rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 shadow-sm transition">
                    🔄 Uji Ulang Koneksi
                </button>
            </form>
        </div>
    </div>

    
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100">Kredensial & Pengaturan</h2>

        <form action="<?php echo e(route('admin.midtrans.update')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Mode Lingkungan (Environment)</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 cursor-pointer has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 transition">
                        <input type="radio" name="is_production" value="0" <?php if(! $isProduction): echo 'checked'; endif; ?> class="text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="block text-sm font-bold text-slate-800">Sandbox (Pengujian)</span>
                            <span class="block text-xs text-slate-500">Gunakan untuk simulasi & development dengan kartu dummy</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 cursor-pointer has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 transition">
                        <input type="radio" name="is_production" value="1" <?php if($isProduction): echo 'checked'; endif; ?> class="text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="block text-sm font-bold text-slate-800">Production (Live)</span>
                            <span class="block text-xs text-slate-500">Gunakan untuk memproses uang nyata di toko live</span>
                        </div>
                    </label>
                </div>
            </div>

            
            <div>
                <label for="merchant_id" class="block text-sm font-semibold text-slate-700 mb-1">
                    Merchant ID
                </label>
                <input type="text" id="merchant_id" name="merchant_id"
                       value="<?php echo e(old('merchant_id', $merchantId)); ?>"
                       placeholder="Contoh: M123456789"
                       class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono">
                <p class="text-xs text-slate-400 mt-1">Dapat ditemukan di dashboard Midtrans pojok kanan atas atau di menu Settings.</p>
            </div>

            
            <div>
                <label for="client_key" class="block text-sm font-semibold text-slate-700 mb-1">
                    Client Key
                </label>
                <input type="text" id="client_key" name="client_key"
                       value="<?php echo e(old('client_key', $clientKey)); ?>"
                       placeholder="Contoh: SB-Mid-client-xxxxxxxxxxxxxxxx"
                       class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono">
                <p class="text-xs text-slate-400 mt-1">Digunakan untuk memuat script popup pembayaran Midtrans Snap di frontend.</p>
            </div>

            
            <div x-data="{ showKey: false }">
                <label for="server_key" class="block text-sm font-semibold text-slate-700 mb-1">
                    Server Key
                </label>
                <div class="relative">
                    <input :type="showKey ? 'text' : 'password'" id="server_key" name="server_key"
                           value="<?php echo e(old('server_key', $serverKey)); ?>"
                           placeholder="<?php echo e($serverKeyConfigured ? 'Kunci tersimpan. Isi hanya untuk menggantinya.' : 'Contoh: SB-Mid-server-xxxxxxxxxxxxxxxx'); ?>"
                           class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 font-mono pr-20">
                    <button type="button" @click="showKey = !showKey"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs text-slate-500 hover:text-indigo-600 px-2 py-1 font-medium">
                        <span x-text="showKey ? 'Sembunyikan' : 'Tampilkan'"></span>
                    </button>
                </div>
                <p class="text-xs text-slate-400 mt-1"><?php echo e($serverKeyConfigured ? 'Server Key sudah tersimpan dan tidak ditampilkan. Kosongkan untuk mempertahankan kunci saat ini.' : 'Kunci rahasia untuk autentikasi backend ke Midtrans API. Jangan dibagikan ke publik.'); ?></p>
            </div>

            
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="hidden" name="enable_simulator" value="0">
                    <input type="checkbox" name="enable_simulator" value="1"
                           <?php if($enableSimulator): echo 'checked'; endif; ?>
                           class="mt-1 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                    <div>
                        <span class="block text-sm font-bold text-slate-800">Aktifkan Mode Simulator Sandbox (Fallback)</span>
                        <span class="block text-xs text-slate-500 mt-0.5">
                            Sangat direkomendasikan untuk portofolio & pengujian lokal: Jika kunci Midtrans Sandbox ditolak (401 Unauthorized) atau server Midtrans offline, sistem akan secara otomatis beralih ke halaman simulator pembayaran yang interaktif sehingga checkout pesanan tetap dapat diselesaikan hingga lunas tanpa error.
                        </span>
                    </div>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full sm:w-auto px-7 py-3 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-500 shadow-md shadow-indigo-100 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 mb-3">Panduan Konfigurasi Midtrans</h3>
        <ol class="list-decimal list-inside space-y-2.5 text-sm text-slate-600 leading-relaxed">
            <li>
                Masuk ke dashboard Midtrans di <a href="https://dashboard.midtrans.com/" target="_blank" class="text-indigo-600 underline font-medium">dashboard.midtrans.com</a>.
            </li>
            <li>
                Pastikan tombol mode di kiri atas berada pada posisi <strong>SANDBOX</strong> untuk testing.
            </li>
            <li>
                Masuk ke menu <strong>Settings &rarr; Access Keys</strong>. Salin <strong>Merchant ID</strong>, <strong>Client Key</strong>, dan <strong>Server Key</strong> ke form di atas.
            </li>
            <li>
                Masuk ke menu <strong>Settings &rarr; Configuration</strong>, masukkan Notification URL webhook berikut:
                <div class="mt-1.5 p-2.5 rounded-lg bg-slate-100 font-mono text-xs text-slate-800 select-all break-all">
                    <?php echo e(url('/midtrans/notification')); ?>

                </div>
            </li>
            <li>
                Klik <strong>Simpan Perubahan</strong> di atas, lalu klik <strong>Uji Ulang Koneksi</strong> untuk memverifikasi kunci.
            </li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\proyekporto\project\project laravel\TokoKita\resources\views/admin/midtrans/index.blade.php ENDPATH**/ ?>