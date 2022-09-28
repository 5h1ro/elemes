<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $name = [
            'Backend',
            'Frontend',
            'Mobile',
            'UI/UX'
        ];


        foreach ($name as $value) {
            $model = new Category();
            $model->name = $value;
            $model->save();
        }
    }
}
