@extends('layouts.app')

@section('title', 'Beranda — TokoKita')

@section('content')

{{-- ========== HERO SECTION ========== --}}
<section
    x-data="{
        slides: {{ $heroSlides->map(fn($s) => ['url' => $s->image_url])->values()->toJson() }},
        current: 0,
        timer: null,
        init() {
            if (this.slides.length > 1) {
                this.timer = setInterval(() => {
                    this.current = (this.current + 1) % this.slides.length;
                }, 5000);
            }
        },
        destroy() {
            if (this.timer) clearInterval(this.timer);
        }
    }"
    class="relative overflow-hidden rounded-[2.5rem] text-white shadow-2xl shadow-emerald-900/10 min-h-[440px] sm:min-h-[500px] border border-emerald-500/10"
>
    {{-- Background: Gradient fallback ATAU slideshow images --}}
    <template x-if="slides.length === 0">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900 via-teal-900 to-slate-900"></div>
    </template>
    <template x-if="slides.length > 0">
        <div class="absolute inset-0">
            <template x-for="(slide, index) in slides" :key="index">
                <div
                    class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out"
                    :class="index === current ? 'opacity-100' : 'opacity-0'"
                    :style="'background-image: url(' + slide.url + ')'"
                ></div>
            </template>
            {{-- Dark overlay agar teks tetap nyaman terbaca --}}
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-emerald-950/70 to-slate-900/50"></div>
        </div>
    </template>

    {{-- Glowing Orbs --}}
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-16 w-96 h-96 rounded-full bg-teal-400/15 blur-3xl pointer-events-none"></div>

    <div class="relative grid lg:grid-cols-12 gap-8 items-center px-6 py-12 sm:px-12 sm:py-16 lg:px-16 lg:py-20">
        <div class="lg:col-span-7">
            {{-- Badge --}}
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-md px-4 py-1.5 text-xs font-semibold tracking-wide mb-6 border border-white/20">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>{{ $home['hero_badge'] }}</span>
            </span>

            {{-- Title --}}
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.25rem] font-extrabold tracking-tight leading-[1.12]">
                {{ $home['hero_title_1'] }}<br>
                <span class="bg-gradient-to-r from-emerald-300 via-teal-200 to-amber-200 bg-clip-text text-transparent">
                    {{ $home['hero_title_2'] }}
                </span>
            </h1>

            <p class="mt-5 text-slate-200 max-w-lg text-sm sm:text-base leading-relaxed">
                {{ $home['hero_subtitle'] }}
            </p>

            {{-- Buttons --}}
            <div class="mt-8 flex flex-wrap items-center gap-3.5">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold px-8 py-4 shadow-xl shadow-emerald-500/25 hover:scale-105 active:scale-95 transition-all duration-200 text-sm sm:text-base">
                    <span>Mulai Belanja</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a href="{{ route('pages.about') }}"
                   class="inline-flex items-center rounded-full bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold px-7 py-4 backdrop-blur transition text-sm sm:text-base">
                    Tentang Kami
                </a>
            </div>

            {{-- Stats --}}
            <div class="mt-12 pt-8 border-t border-white/15 flex flex-wrap gap-8 sm:gap-12">
                <div>
                    <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ $home['stat1_value'] }}</p>
                    <p class="text-xs text-slate-300 mt-0.5 font-medium">{{ $home['stat1_label'] }}</p>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ $home['stat2_value'] }}</p>
                    <p class="text-xs text-slate-300 mt-0.5 font-medium">{{ $home['stat2_label'] }}</p>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ $home['stat3_value'] }}</p>
                    <p class="text-xs text-slate-300 mt-0.5 font-medium">{{ $home['stat3_label'] }}</p>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Kartu Visual Interaktif --}}
        <div class="hidden lg:flex lg:col-span-5 justify-center relative">
            <div class="relative w-80 h-80">
                <div class="absolute inset-0 rounded-[2.5rem] bg-emerald-500/20 backdrop-blur-md border border-white/20 rotate-6 shadow-2xl"></div>
                <div class="absolute inset-0 rounded-[2.5rem] bg-slate-900/40 backdrop-blur-xl border border-white/30 -rotate-3 flex flex-col items-center justify-center p-8 text-center shadow-2xl">
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center mb-5 shadow-lg shadow-emerald-500/30">
                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                        </svg>
                    </div>
                    <p class="text-xl font-extrabold text-white leading-tight">{!! nl2br(e($home['hero_card_title'])) !!}</p>
                    <p class="text-xs text-slate-300 mt-2">{{ $home['hero_card_subtitle'] }}</p>
                </div>

                {{-- Badges Mengambang --}}
                <div class="absolute -top-3 -right-3 bg-gradient-to-r from-rose-500 to-orange-500 text-white text-xs font-extrabold px-3.5 py-1.5 rounded-full shadow-lg rotate-12">
                    {{ $home['hero_badge_promo'] }}
                </div>
                <div class="absolute -bottom-3 -left-4 bg-white text-emerald-700 text-xs font-extrabold px-3.5 py-1.5 rounded-full shadow-lg -rotate-6">
                    {{ $home['hero_badge_flash'] }}
                </div>
            </div>
        </div>
    </div>

    {{-- Indikator Slide (Dots) --}}
    <template x-if="slides.length > 1">
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2 z-10">
            <template x-for="(slide, index) in slides" :key="'dot-'+index">
                <button
                    type="button"
                    @click="current = index"
                    class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                    :class="index === current ? 'bg-white scale-125' : 'bg-white/40 hover:bg-white/70'"
                    :aria-label="'Slide ' + (index + 1)"
                ></button>
            </template>
        </div>
    </template>
</section>

{{-- ========== TRUST BAR VALUE PROPOSITION ========== --}}
<section class="mt-10 grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
    @foreach($home['trust'] as $item)
        <div class="flex items-center gap-3.5 rounded-3xl bg-white border border-slate-200/80 p-4.5 sm:p-5 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-200">
            <div class="w-12 h-12 shrink-0 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-extrabold text-slate-900">{{ $item['title'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $item['desc'] }}</p>
            </div>
        </div>
    @endforeach
</section>

{{-- ========== KATEGORI PILIHAN ========== --}}
@if($categories->isNotEmpty())
<section class="mt-16">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kategori Pilihan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Jelajahi produk berkualitas berdasarkan kategori</p>
        </div>
        <a href="{{ route('products.index') }}"
           class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 group">
            <span>Lihat semua</span>
            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    </div>

    @php
        $gradients = [
            'from-emerald-600 to-teal-500',
            'from-blue-600 to-cyan-500',
            'from-violet-600 to-indigo-500',
            'from-amber-600 to-orange-500',
            'from-rose-600 to-pink-500',
            'from-teal-600 to-emerald-500',
        ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4">
        @foreach($categories as $i => $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
               class="group relative overflow-hidden rounded-3xl p-5 text-center text-white shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 bg-gradient-to-br {{ $gradients[$i % count($gradients)] }}">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                <div class="relative">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <div class="text-sm font-bold">{{ $category->name }}</div>
                    <div class="text-[11px] text-white/80 mt-0.5">{{ $category->products_count }} produk</div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ========== PROMO STRIP BANNER ========== --}}
<section class="mt-16 relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 p-6 sm:p-9 text-white shadow-xl shadow-emerald-900/10">
    <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
    <div class="relative flex flex-col sm:flex-row items-center justify-between gap-5">
        <div>
            <p class="text-[11px] font-extrabold uppercase tracking-widest text-emerald-200 mb-1">{{ $home['promo_eyebrow'] }}</p>
            <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $home['promo_title'] }}</h3>
            <p class="text-sm text-slate-100 mt-1 max-w-xl">{{ $home['promo_subtitle'] }}</p>
        </div>
        <a href="{{ route('products.index') }}"
           class="shrink-0 rounded-full bg-white text-emerald-800 font-extrabold px-8 py-3.5 hover:scale-105 active:scale-95 shadow-xl transition-all duration-200 text-sm">
            {{ $home['promo_button'] }}
        </a>
    </div>
</section>

{{-- ========== PRODUK TERBARU ========== --}}
<section class="mt-16">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Produk Terbaru</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Produk pilihan terbaru berkualitas untuk Anda</p>
        </div>
        <a href="{{ route('products.index') }}"
           class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 group">
            <span>Lihat semua produk</span>
            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    </div>

    @if($featuredProducts->isEmpty())
        <div class="text-center py-20 rounded-3xl bg-white border border-dashed border-slate-200 text-slate-400">
            Belum ada produk yang tersedia.
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($featuredProducts as $product)
                @include('products._card', ['product' => $product])
            @endforeach
        </div>
    @endif
</section>

{{-- ========== CTA BAWAH ========== --}}
<section class="mt-20 relative overflow-hidden rounded-[2.5rem] bg-slate-950 text-white p-8 sm:p-14 text-center border border-slate-800 shadow-2xl">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/20 via-transparent to-teal-500/10 pointer-events-none"></div>
    <div class="relative max-w-xl mx-auto">
        <span class="inline-block text-xs font-bold uppercase tracking-wider text-emerald-400 mb-3 bg-emerald-500/10 px-3.5 py-1 rounded-full border border-emerald-500/20">
            Belanja Mudah & Cepat
        </span>
        <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight">Siap Mulai Belanja di TokoKita?</h2>
        <p class="mt-3 text-slate-300 text-sm sm:text-base leading-relaxed">
            Daftar gratis sekarang dan nikmati pengalaman berbelanja online terpercaya dengan metode pembayaran otomatis yang aman.
        </p>
        <div class="mt-8 flex flex-wrap justify-center gap-3.5">
            <a href="{{ route('products.index') }}"
               class="rounded-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold px-8 py-3.5 transition-all shadow-lg shadow-emerald-500/30 hover:scale-105 active:scale-95 text-sm">
                Jelajahi Katalog Produk
            </a>
            @guest
            <a href="{{ route('register') }}"
               class="rounded-full border border-white/30 hover:bg-white/10 text-white font-semibold px-8 py-3.5 transition text-sm">
                Daftar Akun Gratis
            </a>
            @endguest
        </div>
    </div>
</section>

@endsection
