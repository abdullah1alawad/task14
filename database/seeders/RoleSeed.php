<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert(['role_name'=>'admin']);
        DB::table('roles')->insert(['role_name'=>'vendor']);
        DB::table('roles')->insert(['role_name'=>'client']);
        DB::table('roles')->insert(['role_name'=>'visitor']);
    }
}
