<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CyeraCompensationPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Staking Details (Single Package: $50 to $2000, 0.5% Daily CPS, 2X Capping Default)
        DB::table('stacking_details')->truncate();
        DB::table('stacking_details')->insert([
            [
                'id'          => 1,
                'min_amount'  => 50.00,
                'max_amount'  => 2000.00,
                'cps'         => 0.50,
                'duration'    => 0,
                'status'      => 1,
                'roidouble'   => 1,
                'capping'     => 2.00,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // 2. Seed 15 Levels into level_details
        // Level 1: 5.00% (Direct referral, 0 min_amount, 0 direct_count)
        // Level 2 & 3: 3.00% (5 Directs, $1,000 Team Business)
        // Level 4 & 5: 2.00% (5 Directs, $2,000 Team Business)
        // Level 6 - 8: 1.00% (10 Directs, $5,000 Team Business)
        // Level 9 - 11: 0.75% (15 Directs, $10,000 Team Business)
        // Level 12 - 15: 0.50% (15 Directs, $10,000 Team Business)
        DB::table('level_details')->truncate();
        
        $levels = [
            [
                'id'           => 1,
                'levelname'    => 'Level 1',
                'min_amount'   => 0.00,
                'max_amount'   => 0.00,
                'direct_count' => 0,
                'open_level'   => 1,
                'cps'          => 5.000,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 2,
                'levelname'    => 'Level 2',
                'min_amount'   => 1000.00,
                'max_amount'   => 0.00,
                'direct_count' => 5,
                'open_level'   => 2,
                'cps'          => 3.000,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 3,
                'levelname'    => 'Level 3',
                'min_amount'   => 1000.00,
                'max_amount'   => 0.00,
                'direct_count' => 5,
                'open_level'   => 3,
                'cps'          => 3.000,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 4,
                'levelname'    => 'Level 4',
                'min_amount'   => 2000.00,
                'max_amount'   => 0.00,
                'direct_count' => 5,
                'open_level'   => 4,
                'cps'          => 2.000,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 5,
                'levelname'    => 'Level 5',
                'min_amount'   => 2000.00,
                'max_amount'   => 0.00,
                'direct_count' => 5,
                'open_level'   => 5,
                'cps'          => 2.000,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 6,
                'levelname'    => 'Level 6',
                'min_amount'   => 5000.00,
                'max_amount'   => 0.00,
                'direct_count' => 10,
                'open_level'   => 6,
                'cps'          => 1.000,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 7,
                'levelname'    => 'Level 7',
                'min_amount'   => 5000.00,
                'max_amount'   => 0.00,
                'direct_count' => 10,
                'open_level'   => 7,
                'cps'          => 1.000,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 8,
                'levelname'    => 'Level 8',
                'min_amount'   => 5000.00,
                'max_amount'   => 0.00,
                'direct_count' => 10,
                'open_level'   => 8,
                'cps'          => 1.000,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 9,
                'levelname'    => 'Level 9',
                'min_amount'   => 10000.00,
                'max_amount'   => 0.00,
                'direct_count' => 15,
                'open_level'   => 9,
                'cps'          => 0.750,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 10,
                'levelname'    => 'Level 10',
                'min_amount'   => 10000.00,
                'max_amount'   => 0.00,
                'direct_count' => 15,
                'open_level'   => 10,
                'cps'          => 0.750,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 11,
                'levelname'    => 'Level 11',
                'min_amount'   => 10000.00,
                'max_amount'   => 0.00,
                'direct_count' => 15,
                'open_level'   => 11,
                'cps'          => 0.750,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 12,
                'levelname'    => 'Level 12',
                'min_amount'   => 10000.00,
                'max_amount'   => 0.00,
                'direct_count' => 15,
                'open_level'   => 12,
                'cps'          => 0.500,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 13,
                'levelname'    => 'Level 13',
                'min_amount'   => 10000.00,
                'max_amount'   => 0.00,
                'direct_count' => 15,
                'open_level'   => 13,
                'cps'          => 0.500,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 14,
                'levelname'    => 'Level 14',
                'min_amount'   => 10000.00,
                'max_amount'   => 0.00,
                'direct_count' => 15,
                'open_level'   => 14,
                'cps'          => 0.500,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id'           => 15,
                'levelname'    => 'Level 15',
                'min_amount'   => 10000.00,
                'max_amount'   => 0.00,
                'direct_count' => 15,
                'open_level'   => 15,
                'cps'          => 0.500,
                'status'       => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        DB::table('level_details')->insert($levels);
    }
}
