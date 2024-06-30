<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchants = [
            ['name' => 'grocers market', 'email' => 'grocers@test.com'],
            ['name' => 'beef market', 'email' => 'beef@test.com']
        ];

        foreach ($merchants as $merchant) {
            Merchant::query()->updateOrCreate(['name' => $merchant['name']], $merchant);
        }
    }
}
