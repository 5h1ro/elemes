<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::all();

        if ($data->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil didapatkan',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data kosong',
            ], 404);
        }
    }

    public function popular()
    {
        $data = Category::withSum('course', 'sell')
            ->orderBy('course_sum_sell', 'DESC')
            ->first();

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil didapatkan',
                'data' => $data->only('id', 'name')
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data kosong',
            ], 404);
        }
    }
}
