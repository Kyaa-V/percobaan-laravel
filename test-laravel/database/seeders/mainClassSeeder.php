<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class mainClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('main_classes')->insert([
            ['id' => 1,'class' => '10'],
            ['id' => 2,'class' => '11'],
            ['id' => 3,'class' => '12'],
        ]);
    }
}
