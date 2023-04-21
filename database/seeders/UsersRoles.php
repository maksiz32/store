<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersRoles extends Seeder
{
    public function run(): void
    {
        DB::table('users_roles')->upsert([
            [
                'id' => 1,
                'name' => 'Client',
                'description' => null,
            ], [
                'id' => 20,
                'name' => 'Admin',
                'description' => null,
            ]
        ],
        ['id', 'name', 'description']);
    }
}
