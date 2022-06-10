<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TodoList;
use Illuminate\Support\Facades\DB;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        TodoList::truncate();
        User::create([
            'name' => 'test-admin',
            'email' => 'testadmin@gmail.com',
            'password' => Hash::make('123123'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'test-user',
            'email' => 'testuser@gmail.com',
            'password' => Hash::make('456456'),
            'role' => 'user',
        ]);

        $users = User::all();
        foreach($users as $user){
            for($i = 0; $i < rand(1,9); $i++){
                $task = TodoList::create([
                    'user_id' => $user->id,
                    'body' => 'Test task #'.($i+1),
                    'is_complete' => 0,
                ]);
            }
        }
    }
}
