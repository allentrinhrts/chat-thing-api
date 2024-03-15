<?php

namespace Database\Seeders;

use App\Models\RagResource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RagResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RagResource::factory()->count(1)->create();
    }
}
