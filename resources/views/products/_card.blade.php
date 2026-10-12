{{-- Product card — Modern e-commerce marketplace style --}}
<div class="group flex flex-col bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:border-slate-200/80 hover:-translate-y-1 transition-all duration-300">

    {{-- Container Gambar --}}
    <a href="{{ route('products.show', $product) }}" class="relative aspect-square bg-slate-50 overflow-hidden block">
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
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/>
                </svg>
            </div>
        @endif

        {{-- Badges Pojok Kiri Atas --}}
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 z-10">
            @if(($product->stock ?? 0) <= 0)
                <span class="bg-slate-900/85 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">
                    Habis
                </span>
            @elseif($hasDiscount && $discountPct > 0)
                <span class="bg-rose-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-sm">
                    -{{ $discountPct }}%
                </span>
            @elseif(($product->stock ?? 99) <= 5)
                <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                    Sisa {{ $product->stock }}
                </span>
            @endif
        </div>

        {{-- Badge Pojok Kanan Atas --}}
        @if(!empty($product->is_featured))
            <span class="absolute top-2.5 right-2.5 bg-emerald-600/90 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm z-10">
                Unggulan
            </span>
        @endif
    </a>

    {{-- Informasi Produk --}}
    <div class="flex flex-col flex-1 p-3.5 sm:p-4">

        {{-- Kategori & Rating --}}
        <div class="flex items-center justify-between gap-2 text-[11px] text-slate-400 mb-1">
            <span class="truncate font-medium hover:text-emerald-600 transition">
                {{ $product->category?->name ?? 'Produk' }}
            </span>
            <span class="flex items-center gap-1 text-amber-500 font-semibold shrink-0">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span>4.9</span>
            </span>
        </div>

        {{-- Nama Produk --}}
        <a href="{{ route('products.show', $product) }}"
           class="text-xs sm:text-sm font-semibold text-slate-900 line-clamp-2 leading-snug group-hover:text-emerald-600 transition min-h-[2.5rem]">
            {{ $product->name }}
        </a>

        {{-- Harga & Diskon --}}
        <div class="mt-2.5">
            <div class="flex items-baseline gap-1.5">
                <span class="text-sm sm:text-base font-extrabold text-emerald-600">
                    Rp{{ number_format((float) $product->final_price, 0, ',', '.') }}
                </span>
                @if($hasDiscount)
                    <span class="text-[11px] text-slate-400 line-through">
                        Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Footer Kartu: Terjual / Tombol Beli --}}
        <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
            <span class="text-[11px] text-slate-400">
                @if(!empty($product->sold_count) && $product->sold_count > 0)
                    {{ $product->sold_count }} terjual
                @elseif(($product->stock ?? 0) > 0)
                    Stok {{ $product->stock }}
                @else
                    Habis
                @endif
            </span>

            @if(($product->stock ?? 0) > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                            title="Tambah ke Keranjang"
                            class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-all duration-200 active:scale-90">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </form>
            @endif
        </div>

    </div>
</div>
