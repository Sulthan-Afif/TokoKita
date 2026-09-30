{{-- Product card — gaya marketplace (Tokopedia / Etsy) --}}
<a href="{{ route('products.show', $product) }}"
   class="group flex flex-col bg-white rounded-xl border border-slate-100 overflow-hidden
          hover:shadow-lg hover:border-slate-200 hover:-translate-y-0.5 transition-all duration-300">

    {{-- Image --}}
    <div class="relative aspect-square bg-slate-50 overflow-hidden">
        @php
            $img = $product->thumbnail_url
                ?? $product->image_url
                ?? (optional($product->images->first())->url ?? null);
        @endphp
        @if($img)
            <img src="{{ $img }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/>
                </svg>
            </div>
        @endif

        @if(($product->stock ?? 0) <= 0)
            <span class="absolute top-2 left-2 bg-slate-800/80 text-white text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded">
                Habis
            </span>
        @elseif(($product->stock ?? 99) <= 5)
            <span class="absolute top-2 left-2 bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded">
                Stok terbatas
            </span>
        @endif

        @if(!empty($product->is_featured))
            <span class="absolute top-2 right-2 bg-emerald-500 text-white text-[10px] font-bold px-2 py-0.5 rounded">
                Unggulan
            </span>
        @endif
    </div>

    {{-- Info --}}
    <div class="flex flex-col flex-1 p-3 sm:p-3.5">
        <h3 class="text-sm text-slate-800 line-clamp-2 leading-snug min-h-[2.5rem] group-hover:text-emerald-700 transition">
            {{ $product->name }}
        </h3>

        <p class="mt-2 text-base sm:text-lg font-bold text-slate-900">
            Rp {{ number_format((float) $product->price, 0, ',', '.') }}
        </p>

        <div class="mt-auto pt-2 flex items-center justify-between gap-2 text-[11px] text-slate-400">
            <span class="truncate">
                @if($product->category)
                    {{ $product->category->name }}
                @else
                    Produk
                @endif
            </span>
            @if(isset($product->sold_count) && $product->sold_count > 0)
                <span>{{ $product->sold_count }} terjual</span>
            @elseif(($product->stock ?? null) !== null)
                <span>Stok {{ $product->stock }}</span>
            @endif
        </div>
    </div>
</a>
