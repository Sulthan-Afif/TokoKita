<?php $__env->startSection('title', 'Keranjang Belanja'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto py-4 sm:py-6">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Keranjang Belanja</h1>
            <p class="text-sm text-slate-500 mt-1">Periksa item pilihan Anda sebelum lanjut ke checkout.</p>
        </div>
        <?php if(!$cart->items->isEmpty()): ?>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                <?php echo e($cart->items->sum('quantity')); ?> Item
            </span>
        <?php endif; ?>
    </div>

    <?php if($cart->items->isEmpty()): ?>
        <div class="text-center py-20 rounded-3xl border-2 border-dashed border-slate-200 bg-white p-8">
            <div class="w-20 h-20 mx-auto mb-4 rounded-3xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.892-4.7 2.354-7.135A1.125 1.125 0 0018.75 5.25H5.106M7.5 14.25L5.106 5.25M7.5 14.25L4.5 18.75m0 0h15" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Keranjang belanja Anda kosong</h3>
            <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Yuk, temukan beragam produk pilihan dengan penawaran menarik!</p>
            <a href="<?php echo e(route('products.index')); ?>"
               class="mt-6 inline-flex items-center gap-2 rounded-full bg-emerald-600 text-white font-bold px-7 py-3.5 hover:bg-emerald-500 shadow-md shadow-emerald-600/20 active:scale-95 transition text-sm">
                <span>Mulai Belanja Sekarang</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            
            <div class="lg:col-span-8 space-y-4">
                <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-3xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-sm hover:border-slate-300 transition flex flex-col sm:flex-row sm:items-center gap-4">

                        
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-slate-50 overflow-hidden shrink-0 border border-slate-100">
                            <?php
                                $img = $item->product->thumbnail_url ?? $item->product->image_url;
                            ?>
                            <?php if($img): ?>
                                <img src="<?php echo e($img); ?>" alt="<?php echo e($item->product->name); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        
                        <div class="flex-1 min-w-0">
                            <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider">
                                <?php echo e($item->product->category?->name ?? 'Produk'); ?>

                            </span>
                            <a href="<?php echo e(route('products.show', $item->product->slug)); ?>"
                               class="font-bold text-slate-800 text-sm sm:text-base hover:text-emerald-600 line-clamp-1 block mt-0.5">
                                <?php echo e($item->product->name); ?>

                            </a>
                            <div class="mt-1 flex items-baseline gap-2">
                                <span class="text-sm font-bold text-slate-900">
                                    Rp<?php echo e(number_format($item->product->final_price, 0, ',', '.')); ?>

                                </span>
                                <?php if($item->product->has_discount): ?>
                                    <span class="text-xs text-slate-400 line-through">
                                        Rp<?php echo e(number_format($item->product->price, 0, ',', '.')); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="flex items-center justify-between sm:justify-end gap-4 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                            <form action="<?php echo e(route('cart.update', $item->id)); ?>" method="POST"
                                  class="flex items-center rounded-full border border-slate-200 bg-slate-50 p-1">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="button"
                                        onclick="const input = this.nextElementSibling; if(input.value > 1) { input.stepDown(); input.form.submit(); }"
                                        class="w-7 h-7 rounded-full bg-white text-slate-600 hover:bg-slate-200 flex items-center justify-center font-bold text-sm shadow-sm transition">
                                    −
                                </button>
                                <input type="number" name="quantity" value="<?php echo e($item->quantity); ?>" min="1"
                                       max="<?php echo e($item->product->stock); ?>"
                                       onchange="this.form.submit()"
                                       class="w-12 border-0 bg-transparent text-center text-xs font-bold text-slate-800 p-0 focus:ring-0">
                                <button type="button"
                                        onclick="const input = this.previousElementSibling; if(input.value < <?php echo e($item->product->stock); ?>) { input.stepUp(); input.form.submit(); }"
                                        class="w-7 h-7 rounded-full bg-white text-slate-600 hover:bg-slate-200 flex items-center justify-center font-bold text-sm shadow-sm transition">
                                    +
                                </button>
                            </form>

                            <div class="text-right min-w-[90px]">
                                <span class="text-sm font-extrabold text-emerald-600 block">
                                    Rp<?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                                </span>
                            </div>

                            
                            <form action="<?php echo e(route('cart.remove', $item->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                        class="w-9 h-9 rounded-full text-slate-400 hover:text-rose-500 hover:bg-rose-50 flex items-center justify-center transition"
                                        title="Hapus barang">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="lg:col-span-4 sticky top-24">
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm space-y-5">
                    <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">Ringkasan Belanja</h3>

                    <div class="space-y-2.5 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Total Harga (<?php echo e($cart->items->sum('quantity')); ?> barang)</span>
                            <span class="font-semibold text-slate-900">Rp<?php echo e(number_format($cart->total, 0, ',', '.')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Estimasi Ongkir</span>
                            <span class="text-slate-400 text-xs">Dihitung di checkout</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                            <span class="text-sm font-bold text-slate-900">Total Sementara</span>
                            <span class="text-2xl font-extrabold text-emerald-600">
                                Rp<?php echo e(number_format($cart->total, 0, ',', '.')); ?>

                            </span>
                        </div>
                    </div>

                    <a href="<?php echo e(route('checkout.index')); ?>"
                       class="w-full flex items-center justify-center gap-2 rounded-full bg-emerald-600 text-white font-bold py-4 hover:bg-emerald-500 shadow-lg shadow-emerald-600/20 active:scale-[0.99] transition text-sm sm:text-base">
                        <span>Lanjut ke Checkout</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>

                    <a href="<?php echo e(route('products.index')); ?>"
                       class="block text-center text-xs font-semibold text-slate-500 hover:text-emerald-600 transition">
                        &larr; Lanjut belanja produk lainnya
                    </a>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-center gap-2 text-xs text-slate-400">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Transaksi Aman & Terpercaya</span>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\proyekporto\project\project laravel\TokoKita\resources\views/cart/index.blade.php ENDPATH**/ ?>