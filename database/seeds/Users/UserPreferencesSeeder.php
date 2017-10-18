<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class UsersPreferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('preferences')->insert([

            'uuid'      => (string) Uuid::generate(4),
            'style'     => 'bg-blue',
            'lang'      => 'es',
            'zoom'      => 80,
            'user_id'   => 1
        ]);
    }
}
