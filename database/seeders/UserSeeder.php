<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => config('app.default_user.name'),
            'email' => config('app.default_user.email'),
            'password' => \Illuminate\Support\Facades\Hash::make(config('app.default_user.password'))
        ]);
    }
}
