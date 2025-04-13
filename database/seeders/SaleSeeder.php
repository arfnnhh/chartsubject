<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->pluck('id')->first();
        $memberId = DB::table('members')->pluck('id')->first();

        for ($i = 1; $i <= 20; $i++) {
            $month = rand(1, 12);
            $day = rand(1, 28);
            $createdAt = Carbon::create(now()->year, $month, $day, rand(0, 23), rand(0, 59));

            // random pricing logic
            $product1Qty = rand(1, 5);
            $product1Price = rand(20000, 50000);
            $product1Subtotal = $product1Qty * $product1Price;

            $product2Qty = rand(1, 3);
            $product2Price = rand(10000, 40000);
            $product2Subtotal = $product2Qty * $product2Price;

            $totalAmount = $product1Subtotal + $product2Subtotal;
            $paymentAmount = $totalAmount + rand(1000, 10000);
            $changeAmount = $paymentAmount - $totalAmount;

            DB::table('sales')->insert([
                'id' => Str::uuid(),
                'invoice_number' => 'INV-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'user_id' => $userId,
                'member_id' => $memberId,
                'customer_name' => 'Customer ' . $i,
                'product_data' => json_encode([
                    [
                        'product_id' => (string) Str::uuid(),
                        'name' => 'Product A',
                        'price' => $product1Price,
                        'quantity' => $product1Qty,
                        'subtotal' => $product1Subtotal
                    ],
                    [
                        'product_id' => (string) Str::uuid(),
                        'name' => 'Product B',
                        'price' => $product2Price,
                        'quantity' => $product2Qty,
                        'subtotal' => $product2Subtotal
                    ]
                ]),
                'total_amount' => $totalAmount,
                'payment_amount' => $paymentAmount,
                'change_amount' => $changeAmount,
                'notes' => 'Thank you for your purchase.',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
