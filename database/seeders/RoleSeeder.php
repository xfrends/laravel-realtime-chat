<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert('insert into roles (id, name) values (?, ?)', [1, 'Direksi']);
        DB::insert('insert into roles (id, name) values (?, ?)', [2, 'Director']);
        DB::insert('insert into roles (id, name) values (?, ?)', [3, 'Manager']);
        DB::insert('insert into roles (id, name) values (?, ?)', [4, 'Senior Staff']);
        DB::insert('insert into roles (id, name) values (?, ?)', [5, 'Staff']);
        DB::insert('insert into roles (id, name) values (?, ?)', [6, 'Junior Staff']);
    }
}
