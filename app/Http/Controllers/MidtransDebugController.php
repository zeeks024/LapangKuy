<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransDebugController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Test Midtrans connection and configuration
     */
    public function testConnection(Request $request)
    {
        $result = $this->midtransService->testConnection();
        
        if ($request->wantsJson()) {
            return response()->json($result);
        }
        
        return view('debug.midtrans-test', compact('result'));
    }
}
