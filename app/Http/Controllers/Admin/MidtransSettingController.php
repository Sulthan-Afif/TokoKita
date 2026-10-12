<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransSettingController extends Controller
{
    public function index(MidtransService $midtrans)
    {
        $merchantId = SiteSetting::get('midtrans_merchant_id', config('midtrans.merchant_id', ''));
        $clientKey  = SiteSetting::get('midtrans_client_key', config('midtrans.client_key', ''));
        $serverKey  = SiteSetting::get('midtrans_server_key', config('midtrans.server_key', ''));
        $serverKeyConfigured = trim((string) $serverKey) !== '';
        $serverKey = '';
        
        $isProdVal = SiteSetting::get('midtrans_is_production', null);
        $isProduction = $isProdVal !== null
            ? filter_var($isProdVal, FILTER_VALIDATE_BOOLEAN)
            : (bool) config('midtrans.is_production', false);

        $simVal = SiteSetting::get('midtrans_enable_simulator', null);
        $enableSimulator = $simVal !== null
            ? filter_var($simVal, FILTER_VALIDATE_BOOLEAN) && ! $isProduction
            : app()->environment('local') && ! $isProduction;

        $testResult = $midtrans->testConnection();

        return view('admin.midtrans.index', compact(
            'merchantId',
            'clientKey',
            'serverKey',
            'serverKeyConfigured',
            'isProduction',
            'enableSimulator',
            'testResult'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'merchant_id'      => 'nullable|string|max:100',
            'client_key'       => 'nullable|string|max:150',
            'server_key'       => 'nullable|string|max:150',
            'is_production'    => 'required|in:0,1',
            'enable_simulator' => 'required|in:0,1',
        ]);

        SiteSetting::set('midtrans_merchant_id', trim((string) $request->merchant_id));
        SiteSetting::set('midtrans_client_key', trim((string) $request->client_key));
        if (trim((string) $request->server_key) !== '') {
            SiteSetting::set('midtrans_server_key', trim((string) $request->server_key));
        }
        SiteSetting::set('midtrans_is_production', $request->is_production == '1' ? '1' : '0');
        SiteSetting::set('midtrans_enable_simulator', $request->enable_simulator == '1' ? '1' : '0');

        SiteSetting::clearCache();

        return back()->with('success', 'Pengaturan Midtrans berhasil disimpan!');
    }

    public function test(Request $request, MidtransService $midtrans)
    {
        $result = $midtrans->testConnection();

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
