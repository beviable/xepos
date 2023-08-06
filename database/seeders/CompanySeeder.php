<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Company::factory(100)->create();

        /*Company::factory(20)->create()
            ->each(function($user) {
            Post::create([
                'user_id' => $user->id,
                'title' => fake()->paragraph()
            ]);
        });*/
    }
}
