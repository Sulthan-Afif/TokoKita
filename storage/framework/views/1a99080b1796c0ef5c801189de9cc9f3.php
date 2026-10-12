<footer class="mt-20 border-t border-slate-200/80 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
            <div class="col-span-2">
                <a href="<?php echo e(route('home')); ?>" class="text-2xl font-extrabold tracking-tight inline-block">
                    <span class="text-emerald-600">Toko</span><span class="text-slate-800">Kita</span>
                </a>
                <p class="mt-3 text-sm text-slate-500 leading-relaxed max-w-sm">
                    Platform belanja online terpercaya dengan produk berkualitas, jaminan produk original, dan kemudahan pembayaran otomatis via Midtrans.
                </p>

                <div class="mt-5 flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-3 py-1.5 rounded-full w-fit">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Garansi Belanja Aman & Terpercaya</span>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-3.5">Jelajahi</h4>
                <ul class="space-y-2.5 text-sm text-slate-500">
                    <li><a href="<?php echo e(route('products.index')); ?>" class="hover:text-emerald-600 transition">Semua Produk</a></li>
                    <li><a href="<?php echo e(route('pages.about')); ?>" class="hover:text-emerald-600 transition">Tentang Kami</a></li>
                    <li><a href="<?php echo e(route('pages.contact')); ?>" class="hover:text-emerald-600 transition">Hubungi Kami</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-3.5">Akun Saya</h4>
                <ul class="space-y-2.5 text-sm text-slate-500">
                    <?php if(auth()->guard()->check()): ?>
                        <li><a href="<?php echo e(route('orders.index')); ?>" class="hover:text-emerald-600 transition">Pesanan Saya</a></li>
                        <li><a href="<?php echo e(route('cart.index')); ?>" class="hover:text-emerald-600 transition">Keranjang Belanja</a></li>
                        <li><a href="<?php echo e(route('profile.edit')); ?>" class="hover:text-emerald-600 transition">Profil & Alamat</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo e(route('login')); ?>" class="hover:text-emerald-600 transition">Masuk Akun</a></li>
                        <li><a href="<?php echo e(route('register')); ?>" class="hover:text-emerald-600 transition">Daftar Akun Baru</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-3.5">Bantuan & Kontak</h4>
                <ul class="space-y-2.5 text-sm text-slate-500">
                    <li class="flex items-center gap-2">
                        <span>support@tokokita.com</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>+62 812-3456-7890</span>
                    </li>
                    <li>Jakarta, Indonesia</li>
                    <li class="text-xs text-slate-400">Jam Layanan: 08:00 - 21:00 WIB</li>
                </ul>
            </div>
        </div>

        
        <div class="mt-10 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 flex-wrap text-xs text-slate-400">
                <span class="font-semibold text-slate-600">Metode Pembayaran:</span>
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-bold text-[11px]">Midtrans</span>
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-bold text-[11px]">QRIS</span>
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-bold text-[11px]">BCA</span>
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-bold text-[11px]">Mandiri</span>
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-bold text-[11px]">BNI</span>
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-bold text-[11px]">BRI</span>
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-bold text-[11px]">GoPay</span>
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-bold text-[11px]">Visa / Mastercard</span>
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 font-bold text-[11px]">COD</span>
            </div>

            <div class="text-xs text-slate-400 text-center sm:text-right">
                &copy; <?php echo e(date('Y')); ?> TokoKita. Hak Cipta Dilindungi.
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\proyekporto\project\project laravel\TokoKita\resources\views/components/footer.blade.php ENDPATH**/ ?>