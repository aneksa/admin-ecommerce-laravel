<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if(DB::table('admins')->count() == 0) {
            DB::table('admins')->insert([
                'name' => 'Admin',
                'phone' => '08123456789',
                'email' => 'admin@ecommerce.com',
                'password' => bcrypt('admin')
            ]);
        }
    }
}
