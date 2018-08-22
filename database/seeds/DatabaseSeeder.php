<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->createDemoAdmin();
        // $this->call(UsersTableSeeder::class);
    }

    /**
     * Create a demo user
     *
     * @param string $email
     * @param string $password
     * @param string $name
     * 
     * @return User
     */
    protected function createDemoAdmin(string $email = 'admin@admin.com', string $password = 'password', string $name = 'Super Admin'): User
    {
        return User::create([
            'email'     =>  $email,
            'password'  =>  bcrypt($password),
            'name'      =>  $name
        ]);
    }
}
