<?php

namespace App\Services;

class BidCalculationService
{
    /**
     * Create a new class instance.
     */

     private float $price;
     private string $type;

    public function __construct(float $price, string $type)
    {
        $this->price = $price;
        $this->type = strtolower($type);
    }

    public function calculate(): array
    {
        $basicFee = $this->calculateBasicFee();
        $specialFee = $this->calculateSpecialFee();
        $associationFee = $this->calculateAssociationFee();
        $storageFee = 100.00;

        $total = $this->price + $basicFee + $specialFee + $associationFee + $storageFee;

        return [
            'vehicle_price' => $this->price,
            'vehicle_type' => $this->type,
            'fees' => [
                'basic_buyer_fee' => round($basicFee, 2),
                'special_fee' => round($specialFee, 2),
                'association_fee' => round($associationFee, 2),
                'storage_fee' => $storageFee,
            ],
            'total' => round($total, 2),
        ];
    } 

    private function calculateBasicFee(): float
    {
        $percent = $this->price * 0.10;

        if ($this->type === 'common') {
            return min(max($percent, 10), 50);
        }

        if ($this->type === 'luxury') {
            return min(max($percent, 25), 200);
        }

        return 0;
    }
    
    private function calculateSpecialFee(): float
    {
        $rate = $this->type === 'luxury' ? 0.04 : 0.02;
        return $this->price * $rate;
    }

    private function calculateAssociationFee(): float
    {
        if ($this->price <= 500) return 5;
        if ($this->price <= 1000) return 10;
        if ($this->price <= 3000) return 15;
        return 20;
    }


}
