<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::unguard();
        
        DB::statement('Truncate users RESTART IDENTITY  CASCADE');
        factory(User::class)->create([
            'name'=>'admin',
            'email'=>'admin@task.app',
            'password'=>bcrypt('secret')
        ]);
    }
}
