<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        DB::table('menus')->insert([
            'name' => $faker->country,
            'enabledFrom' => Carbon::now(),
            'enabledUntil' => Carbon::now()->addMonth(),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        for($i = 0; $i < 3; $i++) {
            DB::table('products')->insert([
                'name' => $faker->city,
                'menu_id' => 1,
                'position' => $i,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}
