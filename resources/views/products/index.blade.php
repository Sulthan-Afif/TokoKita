@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')

{{-- ========== KATEGORI HORIZONTAL PILL BAR ========== --}}
<div class="mb-6 -mx-3 sm:mx-0">
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 p-2 sm:p-2.5 shadow-sm">
        <div class="flex items-center gap-1.5 overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <a href="{{ route('products.index', request()->only('search', 'sort')) }}"
               class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all
                      {{ !request('category') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                Semua Produk
            </a>

            @foreach($categories as $category)
                <a href="{{ route('products.index', array_merge(request()->only('search', 'sort'), ['category' => $category->slug])) }}"
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all
                          {{ request('category') == $category->slug ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:text-emerald-600 hover:bg-slate-50' }}">
                    {{ $category->name }}
                    @if(isset($category->products_count))
                        <span class="ml-1 text-[11px] opacity-75">({{ $category->products_count }})</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ========== HEADER & SORTING ========== --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">
            @if(request('category'))
                {{ $categories->firstWhere('slug', request('category'))?->name ?? 'Kategori' }}
            @elseif(request('search'))
                Hasil Pencarian: “<span class="text-emerald-600">{{ request('search') }}</span>”
            @else
                Semua Produk Unggulan
            @endif
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Menampilkan {{ $products->total() }} produk berkualitas
        </p>
    </div>

    {{-- Filter Sorting --}}
    <form method="GET" class="flex items-center gap-2 self-start sm:self-auto">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="category" value="{{ request('category') }}">

        <label class="text-xs font-semibold text-slate-500 shrink-0">Urutkan:</label>
        <select name="sort" onchange="this.form.submit()"
                class="text-xs sm:text-sm rounded-xl border-slate-200 bg-white py-2 pl-3 pr-8 font-semibold text-slate-700 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 cursor-pointer">
            <option value="">Terbaru</option>
            <option value="price_asc" @selected(request('sort')=='price_asc')>Harga: Terendah &rarr; Tertinggi</option>
            <option value="price_desc" @selected(request('sort')=='price_desc')>Harga: Tertinggi &rarr; Terendah</option>
        </select>
    </form>
</div>

{{-- ========== GRID PRODUK ========== --}}
@if($products->isEmpty())
    <div class="text-center py-20 rounded-3xl border-2 border-dashed border-slate-200 bg-white p-8">
        <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800">Tidak ada produk ditemukan</h3>
        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
        <a href="{{ route('products.index') }}"
           class="mt-5 inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-500 shadow-md shadow-emerald-600/20 transition">
            Reset Pencarian &rarr;
        </a>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($products as $product)
            @include('products._card', ['product' => $product])
        @endforeach
    </div>

    <div class="mt-10">
        {{ $products->links() }}
    </div>
@endif

@endsection
