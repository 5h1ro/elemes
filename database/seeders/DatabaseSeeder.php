<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory(2)->state(new Sequence(
            [
                'email' => 'admin@admin.com',
                'role' => 1
            ],
            [
                'email' => 'user@user.com'
            ],
        ))->create();

        $this->call(
            CategorySeeder::class,
        );

        Course::factory(2)->state(new Sequence(
            ['name' => 'NestJs'],
            ['name' => 'Golang'],
        ))->category('Backend')->create();

        Course::factory(3)->state(new Sequence(
            ['name' => 'ReactJs'],
            ['name' => 'Vue'],
            ['name' => 'Ionic'],
        ))->category('Frontend')->create();

        Course::factory(2)->state(new Sequence(
            ['name' => 'Flutter'],
            ['name' => 'React Native'],
        ))->category('Mobile')->create();

        Course::factory(2)->state(new Sequence(
            ['name' => 'Figma'],
            ['name' => 'Adobe XD'],
        ))->category('UI/UX')->create();
    }
}
