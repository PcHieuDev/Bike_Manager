<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
      DB::table('users')->insert([
        'name' => 'Phan cong hieu',
        'email' => 'phanhieu1234567@gmail.com',
        'password' => Hash::make('12345678')
        
      ]);
    }
}
