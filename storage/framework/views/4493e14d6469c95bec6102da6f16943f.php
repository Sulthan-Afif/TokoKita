<?php $__env->startSection('title', 'Pesanan ' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>
<?php
    $statusColors = [
        'pending'    => 'bg-amber-50 text-amber-700 border-amber-200',
        'paid'       => 'bg-blue-50 text-blue-700 border-blue-200',
        'processing' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'shipped'    => 'bg-violet-50 text-violet-700 border-violet-200',
        'completed'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'cancelled'  => 'bg-rose-50 text-rose-700 border-rose-200',
    ];
    $statusLabels = [
        'pending'    => 'Menunggu Pembayaran',
        'paid'       => 'Sudah Dibayar',
        'processing' => 'Sedang Diproses',
        'shipped'    => 'Dalam Pengiriman',
        'completed'  => 'Pesanan Selesai',
        'cancelled'  => 'Dibatalkan',
    ];
    $paymentLabels = [
        'midtrans'      => 'Midtrans (Online)',
        'transfer_bank' => 'Transfer Bank Manual',
        'e_wallet'      => 'E-Wallet',
        'cod'           => 'Bayar di Tempat (COD)',
    ];

    $steps = ['pending', 'paid', 'processing', 'shipped', 'completed'];
    $currentStepIdx = array_search($order->status, $steps);
    if ($currentStepIdx === false) {
        $currentStepIdx = $order->status === 'cancelled' ? -1 : 0;
    }
?>

<div class="max-w-3xl mx-auto py-4 sm:py-6 space-y-6">

    
    <div class="flex items-center justify-between">
        <a href="<?php echo e(route('orders.index')); ?>"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-600 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Daftar Pesanan Saya
        </a>
        <button onclick="window.print()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 shadow-sm transition">
            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            <span>Cetak Invoice</span>
        </button>
    </div>

    
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs text-slate-400">Nomor Pesanan</p>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5"><?php echo e($order->order_number); ?></h1>
                <p class="text-xs text-slate-400 mt-1">Dibuat pada <?php echo e($order->created_at->translatedFormat('d F Y, H:i')); ?> WIB</p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold border <?php echo e($statusColors[$order->status] ?? 'bg-slate-100 text-slate-700'); ?>">
                    <span class="w-2 h-2 rounded-full <?php echo e($order->status === 'completed' ? 'bg-emerald-500' : ($order->status === 'cancelled' ? 'bg-rose-500' : 'bg-amber-500')); ?>"></span>
                    <?php echo e($statusLabels[$order->status] ?? $order->status); ?>

                </span>
            </div>
        </div>

        
        <?php if($order->status !== 'cancelled'): ?>
            <div class="mt-8 pt-6 border-t border-slate-100">
                <div class="grid grid-cols-5 text-center gap-1 text-[11px] sm:text-xs">
                    <?php $__currentLoopData = [
                        ['label' => 'Pesanan Dibuat', 'key' => 'pending'],
                        ['label' => 'Terbayar', 'key' => 'paid'],
                        ['label' => 'Diproses', 'key' => 'processing'],
                        ['label' => 'Dikirim', 'key' => 'shipped'],
                        ['label' => 'Selesai', 'key' => 'completed'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isPassed = $currentStepIdx >= $i;
                            $isCurrent = $currentStepIdx === $i;
                        ?>
                        <div class="flex flex-col items-center">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-bold text-xs mb-1.5 transition-all
                                        <?php echo e($isPassed ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-400'); ?>">
                                <?php if($isPassed): ?>
                                    ✓
                                <?php else: ?>
                                    <?php echo e($i + 1); ?>

                                <?php endif; ?>
                            </div>
                            <span class="<?php echo e($isCurrent ? 'font-bold text-emerald-600' : ($isPassed ? 'font-semibold text-slate-700' : 'text-slate-400')); ?> line-clamp-1">
                                <?php echo e($step['label']); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <?php if($order->status === 'pending' && in_array($order->payment_method, ['transfer_bank', 'e_wallet', 'midtrans'])): ?>
        <div class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-amber-50/50 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-amber-950 flex items-center gap-2">
                        <span>Menunggu Pembayaran Anda</span>
                    </h3>
                    <p class="text-xs text-amber-800/80 mt-1">
                        Total tagihan sebesar <strong class="text-sm font-extrabold text-amber-900">Rp<?php echo e(number_format($order->total, 0, ',', '.')); ?></strong> via <?php echo e($paymentLabels[$order->payment_method] ?? ''); ?>.
                    </p>
                </div>
                <a href="<?php echo e(route('orders.payment', $order)); ?>"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 text-white font-bold px-6 py-3.5 hover:bg-emerald-500 shadow-md shadow-emerald-600/20 active:scale-95 transition text-sm">
                    <span>Bayar Sekarang &rarr;</span>
                </a>
            </div>
        </div>
    <?php elseif($order->status === 'paid'): ?>
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50/80 p-5 shadow-sm text-sm text-emerald-900 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="font-bold">Pembayaran Telah Dikonfirmasi!</p>
                <p class="text-xs text-emerald-700 mt-0.5">
                    Dibayar pada <?php echo e($order->paid_at ? $order->paid_at->format('d M Y, H:i') : now()->format('d M Y, H:i')); ?> WIB. Pesanan sedang disiapkan oleh tim TokoKita.
                </p>
            </div>
        </div>
    <?php elseif($order->status === 'pending' && $order->payment_method === 'cod'): ?>
        <div class="rounded-3xl border border-blue-200 bg-blue-50/80 p-5 shadow-sm text-sm text-blue-900 flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold">Metode Bayar di Tempat (COD)</p>
                <p class="text-xs text-blue-700 mt-0.5">
                    Siapkan uang tunai pas sebesar <strong>Rp<?php echo e(number_format($order->total, 0, ',', '.')); ?></strong> saat kurir mengantarkan paket.
                </p>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
        <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">Daftar Produk Pesanan</h3>

        <div class="divide-y divide-slate-100 my-2">
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="py-4 flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 overflow-hidden shrink-0 border border-slate-100">
                        <?php
                            $img = $item->product?->thumbnail_url ?? $item->product?->image_url;
                        ?>
                        <?php if($img): ?>
                            <img src="<?php echo e($img); ?>" alt="<?php echo e($item->product_name); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-slate-800 leading-snug"><?php echo e($item->product_name); ?></h4>
                        <p class="text-xs text-slate-400 mt-1">
                            <?php echo e($item->quantity); ?> x Rp<?php echo e(number_format($item->price, 0, ',', '.')); ?>

                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-sm font-bold text-slate-900">
                            Rp<?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                        </span>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="pt-4 border-t border-slate-100 space-y-2 text-xs sm:text-sm">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal Produk</span>
                <span class="font-medium text-slate-800">Rp<?php echo e(number_format($order->subtotal, 0, ',', '.')); ?></span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Biaya Pengiriman</span>
                <span class="font-medium text-slate-800">Rp<?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?></span>
            </div>
            <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                <span class="text-sm font-bold text-slate-900">Total Pembayaran</span>
                <span class="text-2xl font-extrabold text-emerald-600">
                    Rp<?php echo e(number_format($order->total, 0, ',', '.')); ?>

                </span>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

        
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-2.5 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Alamat Pengiriman</span>
            </h3>
            <?php if($order->address): ?>
                <p class="text-xs font-bold text-slate-800"><?php echo e($order->address->recipient_name); ?> <span class="font-normal text-slate-500 font-mono">(<?php echo e($order->address->phone); ?>)</span></p>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                    <?php echo e($order->address->full_address); ?>, <?php echo e($order->address->city); ?>, <?php echo e($order->address->province); ?> <?php echo e($order->address->postal_code); ?>

                </p>
            <?php else: ?>
                <p class="text-xs text-slate-400">Alamat tidak ditemukan.</p>
            <?php endif; ?>
        </div>

        
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-2.5 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Metode Pembayaran</span>
            </h3>
            <p class="text-xs font-semibold text-slate-800">
                <?php echo e($paymentLabels[$order->payment_method] ?? $order->payment_method); ?>

            </p>

            <?php if($order->status === 'pending'): ?>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <form action="<?php echo e(route('orders.updatePayment', $order)); ?>" method="POST" class="space-y-2">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <label class="block text-[11px] text-slate-400">Ganti metode pembayaran:</label>
                        <div class="flex flex-wrap gap-1.5">
                            <?php $__currentLoopData = ['midtrans' => 'Midtrans', 'transfer_bank' => 'Transfer', 'e_wallet' => 'E-Wallet', 'cod' => 'COD']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="text-xs px-2.5 py-1 rounded-lg border border-slate-200 cursor-pointer has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                                    <input type="radio" name="payment_method" value="<?php echo e($val); ?>"
                                           <?php if($order->payment_method === $val): echo 'checked'; endif; ?> class="hidden" onchange="this.form.submit()">
                                    <?php echo e($label); ?>

                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>

    </div>

    
    <?php if($order->status === 'pending'): ?>
        <div class="rounded-3xl border border-rose-100 bg-rose-50/50 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h4 class="text-xs font-bold text-rose-900">Ingin membatalkan pesanan?</h4>
                <p class="text-[11px] text-rose-700 mt-0.5">Pesanan yang dibatalkan akan otomatis mengembalikan stok produk ke toko.</p>
            </div>
            <form action="<?php echo e(route('orders.cancel', $order)); ?>" method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="px-4 py-2 text-xs font-bold text-rose-600 hover:text-white hover:bg-rose-600 rounded-xl border border-rose-200 transition">
                    Batalkan Pesanan
                </button>
            </form>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\proyekporto\project\project laravel\TokoKita\resources\views/orders/show.blade.php ENDPATH**/ ?>