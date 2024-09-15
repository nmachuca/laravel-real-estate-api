<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Persona;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Persona::factory()->count(5)->filterTest("a", ".net")->create();
        Persona::factory()->count(5)->filterTest("a", ".org")->create();
    }
}
