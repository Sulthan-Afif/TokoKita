<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Auth::user()->orders()
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.product', 'address');
        return view('orders.show', compact('order'));
    }

    public function payment(Order $order, MidtransService $midtrans)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan ini sudah tidak menunggu pembayaran.');
        }

        if (!in_array($order->payment_method, ['transfer_bank', 'e_wallet', 'midtrans'])) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Halaman pembayaran hanya untuk metode online.');
        }

        $order->load(['items', 'address', 'user']);

        // Generate Snap Token (atau simulasi jika kunci sandbox error/unauthorized)
        $snap = $midtrans->createSnapToken($order);
        $snapToken = $snap['token'] ?? null;
        $isSimulated = $snap['is_simulated'] ?? false;
        $simulationReason = $snap['simulation_reason'] ?? null;

        if (!$snapToken) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Gagal memproses pembayaran Midtrans. Silakan periksa pengaturan atau ganti metode pembayaran.');
        }

        return view('orders.payment', [
            'order'            => $order,
            'snapToken'        => $snapToken,
            'isSimulated'      => $isSimulated,
            'simulationReason' => $simulationReason,
            'clientKey'        => $midtrans->getClientKey(),
            'snapJsUrl'        => $midtrans->getSnapJsUrl(),
            'isConfigured'     => $midtrans->isConfigured(),
        ]);
    }

    /**
     * Eksekusi simulasi bayar / batal saat mode sandbox simulator aktif
     */
    public function simulatePayment(Request $request, Order $order, MidtransService $midtrans)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        abort_unless(! $midtrans->isProduction() && $midtrans->isSimulatorEnabled(), 404);

        $request->validate([
            'action' => 'required|in:success,cancel',
            'sim_payment_type' => 'nullable|in:qris,bca_va,mandiri_va,credit_card',
        ]);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan ini sudah tidak menunggu pembayaran.');
        }

        $action = $request->input('action', 'success');

        if ($action === 'success') {
            $paymentType = $request->input('sim_payment_type', 'qris');
            $update = [
                'status'  => 'paid',
                'paid_at' => now(),
            ];
            if (Schema::hasColumn('orders', 'payment_type')) {
                $update['payment_type'] = $paymentType;
            }
            if (Schema::hasColumn('orders', 'midtrans_transaction_id')) {
                $update['midtrans_transaction_id'] = 'SIM-' . strtoupper(Str::random(12));
            }
            $order->update($update);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pembayaran Simulasi Midtrans Sandbox Berhasil! Status pesanan kini telah lunas.');
        }

        if ($action === 'cancel') {
            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }
                $order->update(['status' => 'cancelled']);
            });

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibatalkan dan stok produk dikembalikan.');
        }

        return redirect()->route('orders.show', $order);
    }

    public function processPayment(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        return redirect()->route('orders.show', $order)
            ->with('success', 'Terima kasih! Status akan update otomatis setelah Midtrans mengonfirmasi pembayaran.');
    }

    public function cancel(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }
            $order->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function updatePaymentMethod(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Metode pembayaran tidak dapat diubah.');
        }

        $request->validate([
            'payment_method' => 'required|in:transfer_bank,e_wallet,cod,midtrans',
        ]);

        $data = ['payment_method' => $request->payment_method];
        if (Schema::hasColumn('orders', 'snap_token')) {
            $data['snap_token'] = null;
        }
        $order->update($data);

        return back()->with('success', 'Metode pembayaran berhasil diubah.');
    }
}
