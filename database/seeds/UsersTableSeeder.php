<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete users table records
        DB::table('users')->delete();

        // Create users tabel and fill with default dummy records
        DB::table('users')->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'burndown',
        ]);
    }
}
