<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Voucher::firstOrCreate(
            ['code' => 'adminfree'],
            ['type' => 'percent', 'value' => 100]
        );

        \App\Models\Voucher::firstOrCreate(
            ['code' => 'voucher10'],
            ['type' => 'fixed', 'value' => 10]
        );

        \App\Models\Voucher::firstOrCreate(
            ['code' => 'voucher15'],
            ['type' => 'fixed', 'value' => 15]
        );
    }
}
