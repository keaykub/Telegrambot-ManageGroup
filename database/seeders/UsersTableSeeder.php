<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins_telegram')->insert([
            'ADMIN_USERNAME' => 'tester',
            'ADMIN_PASSWORD' => Hash::make('keay123456'),
            'ADMIN_ROLE' => 'TESTER',
            'ADMIN_IDTELEGRAM' => 'NO',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
