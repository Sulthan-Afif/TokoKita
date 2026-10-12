@extends('layouts.app')

@section('title', 'Pembayaran - ' . $order->order_number)

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">

    {{-- Breadcrumb / Back --}}
    <div class="mb-5">
        <a href="{{ route('orders.show', $order) }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-emerald-600 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke detail pesanan
        </a>
    </div>

    {{-- Kartu Utama Pembayaran --}}
    <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">

        {{-- Header Status --}}
        <div class="flex items-start justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 mb-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Menunggu Pembayaran
                </span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Selesaikan Pembayaran</h1>
                <p class="text-xs text-slate-400 mt-0.5">Nomor Pesanan: <span class="font-mono font-semibold text-slate-600">{{ $order->order_number }}</span></p>
            </div>
            <div class="text-right shrink-0">
                <div class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>23:59:00</span>
                </div>
            </div>
        </div>

        {{-- Rincian Tagihan --}}
        <div class="my-6 rounded-2xl bg-slate-50 p-5 space-y-3 text-sm">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal Produk ({{ $order->items->sum('quantity') }} barang)</span>
                <span class="font-medium text-slate-800">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Biaya Pengiriman</span>
                <span class="font-medium text-slate-800">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="pt-3 border-t border-slate-200 flex justify-between items-baseline">
                <div>
                    <span class="text-sm font-bold text-slate-900 block">Total Tagihan</span>
                    <span class="text-[11px] text-slate-400">Termasuk pajak & biaya penanganan</span>
                </div>
                <span class="text-2xl font-extrabold text-emerald-600">
                    Rp{{ number_format($order->total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- JIKA REAL MIDTRANS SNAP TOKEN TERSEDIA --}}
        @if(!$isSimulated && $snapToken)
            <div class="space-y-4">
                <button id="pay-button"
                        class="w-full flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 text-white font-bold py-4 hover:bg-emerald-500 active:scale-[0.99] shadow-lg shadow-emerald-600/20 transition-all text-base">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Bayar Sekarang (Midtrans Snap)</span>
                </button>

                <div class="flex items-center justify-center gap-2 text-xs text-slate-400">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Didukung QRIS, GoPay, ShopeePay, Virtual Account & Kartu Kredit</span>
                </div>
            </div>

        {{-- JIKA DALAM MODE SIMULASI SANDBOX (KUNCI ERROR/UNAUTHORIZED ATAU TESTING) --}}
        @else
            <div class="rounded-2xl border border-blue-200 bg-blue-50/70 p-5 space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-950">Mode Simulasi Midtrans Sandbox Aktif</h3>
                        <p class="text-xs text-blue-800/90 mt-1 leading-relaxed">
                            Kunci API Midtrans Sandbox Anda saat ini belum aktif atau unauthorized. Fitur simulator diaktifkan agar Anda dapat menguji alur checkout hingga selesai tanpa hambatan.
                        </p>
                    </div>
                </div>

                {{-- Opsi Pembayaran Simulasi --}}
                <form action="{{ route('orders.simulatePayment', $order) }}" method="POST" class="space-y-3 pt-2">
                    @csrf
                    <input type="hidden" name="action" value="success">

                    <label class="block text-xs font-semibold text-slate-600">Pilih Metode Simulasi:</label>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-blue-200 bg-white cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="sim_payment_type" value="qris" checked class="text-emerald-600">
                            <span class="font-medium text-slate-700">QRIS / GoPay</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-blue-200 bg-white cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="sim_payment_type" value="bca_va" class="text-emerald-600">
                            <span class="font-medium text-slate-700">BCA Virtual Account</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-blue-200 bg-white cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="sim_payment_type" value="mandiri_va" class="text-emerald-600">
                            <span class="font-medium text-slate-700">Mandiri VA</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-blue-200 bg-white cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="sim_payment_type" value="credit_card" class="text-emerald-600">
                            <span class="font-medium text-slate-700">Kartu Kredit</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full mt-3 flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 text-white font-bold py-3.5 hover:bg-emerald-500 active:scale-[0.99] shadow-md shadow-emerald-600/20 transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>✓ Simulasikan Bayar Sukses (Lunas)</span>
                    </button>
                </form>

                @if(auth()->user()->isAdmin())
                    <div class="pt-2 text-center">
                        <a href="{{ route('admin.midtrans.index') }}"
                           class="text-xs text-indigo-600 hover:underline font-semibold inline-flex items-center gap-1">
                            <span>⚙️ Masukkan Kunci Midtrans Asli di Admin Panel</span>
                        </a>
                    </div>
                @endif
            </div>
        @endif

        {{-- Pilihan Alternatif --}}
        <div class="mt-6 pt-5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
            <form action="{{ route('orders.updatePayment', $order) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="payment_method" value="cod">
                <button type="submit" class="text-slate-500 hover:text-emerald-600 underline">
                    Ganti ke Bayar di Tempat (COD)
                </button>
            </form>

            <form action="{{ route('orders.cancel', $order) }}" method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                @csrf
                <button type="submit" class="text-rose-500 hover:text-rose-700">
                    Batalkan Pesanan
                </button>
            </form>
        </div>

    </div>

    {{-- Panduan Pembayaran --}}
    <div class="mt-8 rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-slate-800">Petunjuk Pembayaran</h3>
        <div class="space-y-3 text-xs text-slate-600">
            <div class="flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold shrink-0">1</span>
                <p>Klik tombol <strong>Bayar Sekarang</strong> untuk membuka jendela pembayaran Midtrans Snap.</p>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold shrink-0">2</span>
                <p>Pilih metode yang Anda inginkan (Scan QRIS, Virtual Account bank, atau GoPay/ShopeePay).</p>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold shrink-0">3</span>
                <p>Selesaikan pembayaran sebelum batas waktu berakhir. Status pesanan akan otomatis terupdate secara real-time.</p>
            </div>
        </div>
    </div>

</div>
@endsection

@if(!$isSimulated && $snapToken)
    @push('scripts')
    {{-- Midtrans Snap JS --}}
    <script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    // Status pembayaran dikonfirmasi oleh webhook Midtrans yang tervalidasi.
                    window.location.href = '{{ route('orders.show', $order) }}?status=success';
                },
                onPending: function (result) {
                    window.location.href = '{{ route('orders.show', $order) }}?status=pending';
                },
                onError: function (result) {
                    alert('Pembayaran gagal atau dibatalkan.');
                    console.error(result);
                },
                onClose: function () {
                    // Jendela ditutup
                }
            });
        });
    </script>
    @endpush
@endif
