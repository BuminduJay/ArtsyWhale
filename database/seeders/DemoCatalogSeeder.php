<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Category::factory(5)->create()->each(function ($cat) {
        \App\Models\Product::factory(12)->create(['category_id' => $cat->id]);
    });

    // optional: create an admin user
    \App\Models\User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@artsy.test',
        'password' => bcrypt('password'),
        'role' => 'admin', // add 'role' column via a tiny migration if you haven’t yet
    ]);
}

}
