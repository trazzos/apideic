<?php

namespace Database\Seeders;


use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Actions\Fortify\CreateNewUser;

class DatabaseSeeder extends Seeder
{
    public function __construct(private readonly CreateNewUser $creator)
    {

    }
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->creator->create([
            'name' => 'Superusuario',
            'email' => 'super@codisoft.com.mx',
            'password' => 'super2025',
            'password_confirmation' => 'super2025',
        ]);
    }
}
