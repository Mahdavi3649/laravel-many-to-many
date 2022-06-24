<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i=0; $i < 10; $i++) { 
            $new_tag = new App\Tag();
            $new_tag->name = $faker->word(); 
            $new_tag->save();
        }
    }
}
