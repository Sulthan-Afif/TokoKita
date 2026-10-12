@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-6xl mx-auto py-2 sm:py-4">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-emerald-600 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-emerald-600 transition">Produk</a>
        @if($product->category)
            <span>/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-emerald-600 transition">
                {{ $product->category->name }}
            </a>
        @endif
        <span>/</span>
        <span class="text-slate-800 font-semibold truncate max-w-[200px] sm:max-w-xs">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

        {{-- KOLOM KIRI: Galeri Gambar --}}
        <div class="lg:col-span-5 flex flex-col items-center">
            <div class="relative w-full max-w-[420px] aspect-square rounded-3xl bg-slate-50 overflow-hidden border border-slate-200/80 shadow-sm group">
                @php
                    $img = $product->thumbnail_url
                        ?? $product->image_url
                        ?? (optional($product->images->first())->url ?? null);
                    $hasDiscount = !empty($product->has_discount);
                    $discountPct = ($hasDiscount && (float)$product->price > 0)
                        ? round((( (float)$product->price - (float)$product->final_price ) / (float)$product->price) * 100)
                        : 0;
                @endphp

                @if($img)
                    <img src="{{ $img }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                        <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                        </svg>
                    </div>
                @endif

                {{-- Badges --}}
                <div class="absolute top-4 left-4 flex flex-col gap-1.5 z-10">
                    @if($hasDiscount && $discountPct > 0)
                        <span class="bg-rose-500 text-white text-xs font-extrabold px-3 py-1 rounded-full shadow-md">
                            HEMAT {{ $discountPct }}%
                        </span>
                    @endif
                    @if(!empty($product->is_featured))
                        <span class="bg-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                            Unggulan
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Detail & Aksi Beli --}}
        <div class="lg:col-span-7 flex flex-col space-y-6">

            <div>
                @if($product->category)
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                       class="text-xs font-bold text-emerald-600 uppercase tracking-widest hover:underline">
                        {{ $product->category->name }}
                    </a>
                @endif

                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 leading-snug">
                    {{ $product->name }}
                </h1>

                {{-- Rating & Terjual --}}
                <div class="flex items-center gap-3 mt-2.5 text-xs text-slate-500">
                    <span class="flex items-center gap-1 text-amber-500 font-bold">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span>4.9</span>
                        <span class="font-normal text-slate-400">(48 ulasan)</span>
                    </span>
                    <span class="text-slate-300">&bull;</span>
                    <span>Terjual {{ $product->sold_count ?? 120 }}+ unit</span>
                </div>
            </div>

            {{-- Harga --}}
            <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-100 flex items-baseline gap-3">
                <span class="text-3xl sm:text-4xl font-extrabold text-emerald-600">
                    Rp{{ number_format((float) $product->final_price, 0, ',', '.') }}
                </span>
                @if($hasDiscount)
                    <span class="text-lg text-slate-400 line-through">
                        Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                    </span>
                @endif
            </div>

            {{-- Ketersediaan Stok --}}
            <div class="flex items-center gap-2 text-sm font-semibold">
                @if(($product->stock ?? 0) > 0)
                    <span class="inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Stok Tersedia ({{ $product->stock }} unit)
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-rose-700 bg-rose-50 px-3 py-1 rounded-full text-xs">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Stok Habis
                    </span>
                @endif
            </div>

            {{-- Deskripsi Produk --}}
            @if($product->description)
                <div class="pt-2 border-t border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 mb-2">Deskripsi Produk</h3>
                    <div class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                        {{ $product->description }}
                    </div>
                </div>
            @endif

            {{-- Form Pembelian --}}
            <div class="pt-4 border-t border-slate-100">
                @if(($product->stock ?? 0) > 0)
                    @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-semibold text-slate-700">Jumlah:</span>
                                <div class="flex items-center rounded-full border border-slate-200 bg-slate-50 p-1">
                                    <button type="button" onclick="const input = this.nextElementSibling; if(input.value > 1) input.stepDown()"
                                            class="w-8 h-8 rounded-full bg-white text-slate-600 hover:bg-slate-200 flex items-center justify-center font-bold text-sm shadow-sm transition">
                                        −
                                    </button>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                           class="w-14 border-0 bg-transparent text-center text-sm font-bold text-slate-800 p-0 focus:ring-0">
                                    <button type="button" onclick="const input = this.previousElementSibling; if(input.value < {{ $product->stock }}) input.stepUp()"
                                            class="w-8 h-8 rounded-full bg-white text-slate-600 hover:bg-slate-200 flex items-center justify-center font-bold text-sm shadow-sm transition">
                                        +
                                    </button>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                <button type="submit"
                                        class="flex-1 flex items-center justify-center gap-2 rounded-full bg-emerald-600 text-white font-bold py-4 hover:bg-emerald-500 active:scale-[0.99] shadow-lg shadow-emerald-600/20 transition-all text-sm sm:text-base">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span>Tambah ke Keranjang</span>
                                </button>
                            </div>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 text-white font-bold px-8 py-4 hover:bg-emerald-500 shadow-lg shadow-emerald-600/20 active:scale-95 transition text-sm">
                            <span>Masuk untuk Membeli</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endauth
                @else
                    <button disabled class="w-full sm:w-auto rounded-full bg-slate-200 text-slate-400 px-8 py-4 font-bold text-sm cursor-not-allowed">
                        Stok Produk Habis
                    </button>
                @endif
            </div>

            {{-- Trust & Jaminan Belanja --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4.5 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        ✓
                    </div>
                    <span>100% Produk Original & Bergaransi</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        ⚡
                    </div>
                    <span>Pembayaran Aman Midtrans (QRIS, VA)</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        🚚
                    </div>
                    <span>Pengiriman Cepat Seluruh Indonesia</span>
                </div>
            </div>

        </div>

    </div>

    {{-- PRODUK TERKAIT --}}
    @if($related->isNotEmpty())
        <section class="mt-20 pt-10 border-t border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Produk Terkait Lainnya</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Produk sejenis yang mungkin Anda sukai</p>
                </div>
                <a href="{{ route('products.index') }}"
                   class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($related as $item)
                    @include('products._card', ['product' => $item])
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
