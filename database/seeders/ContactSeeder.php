<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contacts')->truncate();

        DB::table('contacts')->insert([
            ['user_id' => 1, 'acquaintance_id' => 2, 'alias_name' => 'Bob', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'acquaintance_id' => 3, 'alias_name' => 'Charlie', 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 2, 'acquaintance_id' => 1, 'alias_name' => 'Alice', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'acquaintance_id' => 3, 'alias_name' => 'Charlie', 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 3, 'acquaintance_id' => 1, 'alias_name' => 'Alice', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'acquaintance_id' => 2, 'alias_name' => 'Bob', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
