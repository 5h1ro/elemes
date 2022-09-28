<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

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
}
