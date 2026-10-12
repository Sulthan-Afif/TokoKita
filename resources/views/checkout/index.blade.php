@extends('layouts.app')

@section('title', 'Checkout Pembayaran')

@section('content')
<div class="max-w-6xl mx-auto py-4 sm:py-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-emerald-600 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('cart.index') }}" class="hover:text-emerald-600 transition">Keranjang</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Checkout</span>
    </nav>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Checkout Pesanan</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi alamat pengiriman dan pilih metode pembayaran Anda.</p>
        </div>
        <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-3.5 py-1.5 rounded-full">
            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span>Enkripsi Aman & Terverifikasi</span>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf

        {{-- KOLOM KIRI (Alamat + Metode Bayar) --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- 1. ALAMAT PENGIRIMAN --}}
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center">1</span>
                        <h2 class="text-lg font-bold text-slate-900">Alamat Pengiriman</h2>
                    </div>
                    <a href="{{ route('profile.edit') }}#alamat"
                       class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                        + Tambah / Kelola Alamat
                    </a>
                </div>

                @if($addresses->isEmpty())
                    <div class="rounded-2xl border-2 border-dashed border-amber-200 bg-amber-50/50 p-6 text-center">
                        <div class="w-12 h-12 mx-auto rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-amber-900">Belum ada alamat pengiriman</h4>
                        <p class="text-xs text-amber-700 mt-1 max-w-sm mx-auto">Silakan tambahkan alamat pengiriman di halaman profil Anda terlebih dahulu untuk melanjutkan checkout.</p>
                        <a href="{{ route('profile.edit') }}#alamat"
                           class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 rounded-full transition shadow-sm">
                            Tambah Alamat Sekarang &rarr;
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($addresses as $index => $address)
                            <label class="relative flex items-start gap-4 border border-slate-200 rounded-2xl p-4.5 cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition-all">
                                <input type="radio" name="address_id" value="{{ $address->id }}"
                                       @checked($index === 0)
                                       class="mt-1 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
                                <div class="flex-1 text-sm">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-bold text-slate-900">{{ $address->recipient_name }}</span>
                                        <span class="text-slate-400">&bull;</span>
                                        <span class="text-xs text-slate-500 font-mono">{{ $address->phone }}</span>
                                        @if($index === 0)
                                            <span class="ml-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Utama</span>
                                        @endif
                                    </div>
                                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                                        {{ $address->full_address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 2. METODE PEMBAYARAN --}}
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm">
                <div class="flex items-center gap-2.5 mb-5">
                    <span class="w-7 h-7 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center">2</span>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Metode Pembayaran</h2>
                        <p class="text-xs text-slate-400">Pilih metode yang paling nyaman untuk Anda</p>
                    </div>
                </div>

                <div class="space-y-3">
                    {{-- Opsi 1: Midtrans --}}
                    <label class="flex items-start gap-3.5 border border-slate-200 rounded-2xl p-4 cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition-all">
                        <input type="radio" name="payment_method" value="midtrans" checked
                               class="mt-1 text-emerald-600 focus:ring-emerald-500 border-slate-300" required>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                    Bayar Online (Midtrans)
                                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-bold">Otomatis & Realtime</span>
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                QRIS (GoPay, OVO, ShopeePay, DANA), Virtual Account (BCA, Mandiri, BNI, BRI), dan Kartu Kredit.
                            </p>
                            <div class="flex flex-wrap gap-1.5 mt-2.5">
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded">QRIS</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded">BCA VA</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded">Mandiri</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded">BNI</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded">BRI</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded">GoPay</span>
                            </div>
                        </div>
                    </label>

                    {{-- Opsi 2: Transfer Bank Manual --}}
                    <label class="flex items-start gap-3.5 border border-slate-200 rounded-2xl p-4 cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition-all">
                        <input type="radio" name="payment_method" value="transfer_bank"
                               class="mt-1 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                        <div class="flex-1">
                            <span class="text-sm font-bold text-slate-900 block">Transfer Bank Manual</span>
                            <p class="text-xs text-slate-500 mt-0.5">Transfer langsung ke rekening toko kita, konfirmasi manual.</p>
                        </div>
                    </label>

                    {{-- Opsi 3: E-Wallet --}}
                    <label class="flex items-start gap-3.5 border border-slate-200 rounded-2xl p-4 cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition-all">
                        <input type="radio" name="payment_method" value="e_wallet"
                               class="mt-1 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                        <div class="flex-1">
                            <span class="text-sm font-bold text-slate-900 block">E-Wallet Direct</span>
                            <p class="text-xs text-slate-500 mt-0.5">DANA, OVO, LinkAja, ShopeePay.</p>
                        </div>
                    </label>

                    {{-- Opsi 4: COD --}}
                    <label class="flex items-start gap-3.5 border border-slate-200 rounded-2xl p-4 cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 transition-all">
                        <input type="radio" name="payment_method" value="cod"
                               class="mt-1 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                        <div class="flex-1">
                            <span class="text-sm font-bold text-slate-900 block">Bayar di Tempat (COD)</span>
                            <p class="text-xs text-slate-500 mt-0.5">Bayar tunai kepada kurir saat paket tiba di alamat Anda.</p>
                        </div>
                    </label>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (Ringkasan Pesanan Sticky) --}}
        <div class="lg:col-span-5 sticky top-24">
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-sm space-y-6">
                <h3 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">Ringkasan Pesanan</h3>

                {{-- List Item Keranjang --}}
                <div class="space-y-3.5 max-h-64 overflow-y-auto pr-1">
                    @foreach($cart->items as $item)
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-100">
                                @php
                                    $img = $item->product->thumbnail_url ?? $item->product->image_url;
                                @endphp
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-semibold text-slate-800 truncate">{{ $item->product->name }}</h4>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $item->quantity }} x Rp{{ number_format($item->product->final_price, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-xs font-bold text-slate-900 shrink-0">
                                Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Kalkulasi Biaya --}}
                <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs sm:text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Total Harga ({{ $cart->items->sum('quantity') }} barang)</span>
                        <span class="font-medium text-slate-800">Rp{{ number_format($cart->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Pengiriman (Flat)</span>
                        <span class="font-medium text-slate-800">Rp15.000</span>
                    </div>
                    <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                        <span class="text-sm font-bold text-slate-900">Total Tagihan</span>
                        <span class="text-2xl font-extrabold text-emerald-600">
                            Rp{{ number_format($cart->total + 15000, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit"
                        @disabled($addresses->isEmpty())
                        class="w-full flex items-center justify-center gap-2 rounded-full bg-emerald-600 text-white font-bold py-4 hover:bg-emerald-500 active:scale-[0.99] disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-emerald-600/20 transition-all text-sm sm:text-base">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Bayar & Selesaikan Pesanan</span>
                </button>

                <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                    Dengan membuat pesanan, Anda menyetujui Ketentuan Layanan & Kebijakan Privasi TokoKita.
                </p>
            </div>
        </div>

    </form>
</div>
@endsection
