<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index($sort = null)
    {
        $data = Course::all();

        $data = self::sort($data, $sort);

        if ($data->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil didapatkan',
                'data' => $data->values()->map(
                    function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'price' => 'Rp. ' . number_format($item->price, 0, ',', '.'),
                            'sell' => $item->sell,
                            'detail' => $item->detail,
                            'thumbnail' => $item->thumbnail,
                            'mentor' => $item->mentor,
                            'category' => $item->category->name,
                            'status' => $item->status,
                        ];
                    }
                )
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data kosong',
            ], 404);
        }
    }

    public function find($id)
    {
        $data = Course::find($id);
        if ($data) {
            $data->price = 'Rp. ' . number_format($data->price, 0, ',', '.');
            $data->category = $data->category->name;
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil didapatkan',
                'data' => $data->only(
                    'id',
                    'name',
                    'price',
                    'detail',
                    'mentor',
                    'sell',
                    'category',
                    'status'
                )
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }

    public function search(Request $request, $sort = null)
    {
        $validate = [
            'key' => 'required',
        ];

        $message_validator = [
            'key.required' => 'Kata kunci tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $validate, $message_validator);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $data = Course::where('name', 'like', "%" . $request->key . "%")
            ->orWhere('detail', 'like', "%" . $request->key . "%")
            ->orWhere('mentor', 'like', "%" . $request->key . "%")
            ->orWhereRelation('category', 'name', 'like', "%" . $request->key . "%")
            ->get();

        $data = self::sort($data, $sort);

        if ($data->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil didapatkan',
                'data' => $data->values()->map(
                    function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'price' => 'Rp. ' . number_format($item->price, 0, ',', '.'),
                            'sell' => $item->sell,
                            'detail' => $item->detail,
                            'thumbnail' => $item->thumbnail,
                            'mentor' => $item->mentor,
                            'category' => $item->category->name,
                            'status' => $item->status,
                        ];
                    }
                )
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }

    public function sort($data, $sort)
    {
        $data = $sort == 'ASC'
            ? $data->sortBy('price')
            : ($sort == 'DESC'
                ? $data->sortByDesc('price')
                : ($sort == 'free'
                    ? $data->sortByDesc('status')
                    : ($sort == 'paid'
                        ? $data->sortBy('status')
                        : $data->sortByDesc('fk_category')
                    )
                )
            );

        return $data;
    }
}
