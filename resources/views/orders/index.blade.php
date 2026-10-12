@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-4xl mx-auto py-4 sm:py-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Pesanan Saya</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau dan kelola semua riwayat transaksi belanja Anda.</p>
        </div>
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center gap-2 text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 px-4 py-2.5 rounded-full transition w-fit">
            <span>+ Belanja Lagi</span>
        </a>
    </div>

    @php
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
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];

        $filterStatuses = [
            ''           => 'Semua',
            'pending'    => 'Menunggu Bayar',
            'paid'       => 'Dibayar',
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
        ];
    @endphp

    {{-- Filter Tabs Horizontal --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        @foreach($filterStatuses as $val => $label)
            <a href="{{ route('orders.index', $val ? ['status' => $val] : []) }}"
               class="px-4 py-2 text-xs font-semibold rounded-full whitespace-nowrap transition-all
                      {{ request('status') == $val ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- List Pesanan --}}
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="rounded-3xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-sm hover:border-slate-300 transition group">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400">Order</span>
                            <h3 class="text-sm font-bold text-slate-900 font-mono">{{ $order->order_number }}</h3>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <span class="text-xs text-slate-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $order->status === 'completed' ? 'bg-emerald-500' : ($order->status === 'cancelled' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                    </div>
                </div>

                {{-- Preview Items --}}
                <div class="py-4">
                    <p class="text-xs text-slate-500 line-clamp-1">
                        @foreach($order->items as $idx => $item)
                            {{ $item->product_name }} (x{{ $item->quantity }}){{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </p>
                </div>

                {{-- Footer Kartu: Total & Actions --}}
                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <span class="text-[11px] text-slate-400 block">Total Tagihan</span>
                        <span class="text-base font-extrabold text-emerald-600">
                            Rp{{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($order->status === 'pending')
                            <a href="{{ route('orders.payment', $order) }}"
                               class="px-4 py-2 rounded-full bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-500 shadow-sm transition">
                                Bayar Sekarang &rarr;
                            </a>
                        @endif
                        <a href="{{ route('orders.show', $order) }}"
                           class="px-4 py-2 rounded-full border border-slate-200 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 rounded-3xl border-2 border-dashed border-slate-200 bg-white p-8">
                <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum ada transaksi</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Anda belum memiliki pesanan dengan filter ini. Mulai jelajahi katalog produk kami sekarang!</p>
                <a href="{{ route('products.index') }}"
                   class="mt-5 inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-500 shadow-md shadow-emerald-600/20 transition">
                    Mulai Belanja &rarr;
                </a>
            </div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="mt-8">{{ $orders->links() }}</div>
    @endif

</div>
@endsection
