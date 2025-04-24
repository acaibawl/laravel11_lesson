<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->isLocal()) {
            // 開発環境のみレコードを追加
            Admin::factory()
                ->count(10)
                ->sequence(function ($sequence) {
                    return [
                        'name' => sprintf('admin_%02d', $sequence->index + 1),
                        'password' => Hash::make('admin'),
                        'created_at' => '2022-12-30 11:22:33',
                        'updated_at' => '2022-12-31 23:58:59',
                    ];
                })
                ->create();
        }
    }
}
