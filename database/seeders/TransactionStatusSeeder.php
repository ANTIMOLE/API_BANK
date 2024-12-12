<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('transaction_status')->insert([
            ['id' => 1, 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'status' => 'approved', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'status' => 'rejected', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
