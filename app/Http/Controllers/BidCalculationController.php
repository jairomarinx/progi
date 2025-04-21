<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BidCalculationService;

class BidCalculationController extends Controller
{
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'vehicle_price' => 'required|numeric|min:1',
            'vehicle_type' => 'required|in:common,luxury',
        ]);

        $service = new BidCalculationService(
            $validated['vehicle_price'],
            $validated['vehicle_type']
        );

        return response()->json($service->calculate());
    }
}
