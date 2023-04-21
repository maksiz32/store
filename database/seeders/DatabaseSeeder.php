<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersRoles::class,
        ]);
        // \App\Models\User::factory(10)->create();

         User::factory()->create([
             'name' => 'Super Mega User',
             'email' => 'test@example.com',
             'users_role_id' => 20,
             'password' => Hash::make('123QWEasd'),
         ]);
    }
}
