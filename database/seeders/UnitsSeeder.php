<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'g'],
            ['name' => 'kg'],
            ['name' => 'piece']
        ];

        foreach ($units as $unit) {
            Unit::query()->updateOrCreate(['name' => $unit['name']], $unit);
        }
    }
}
