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
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@test.loc',
                'password' => bcrypt('password'),
                'is_admin' => true
            ]            
        ];
        
        \DB::table('users')->insert($data);
    }
}
