<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DirectionSeeder::class);

        $this->call(UsersPreferencesSeeder::class);

        $this->call(AccountsSeeder::class);

        $this->call(BanksSeeder::class);

        $this->call(ChargesSeeder::class);
    }
}
