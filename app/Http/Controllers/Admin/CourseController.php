<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $data = Course::orderBy('fk_category', 'DESC')->get();

        if ($data->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil didapatkan',
                'data' => $data->values()->map(
                    function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'thumbnail' => $item->thumbnail,
                            'price' => 'Rp. ' . number_format($item->price, 0, ',', '.'),
                            'sell' => $item->sell,
                            'detail' => $item->detail,
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
                    'thumbnail',
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

    public function store(Request $request)
    {
        $validate = [
            'name' => 'required|between:2,100',
            'price' => 'numeric|min:0',
            'detail' => 'required',
            'mentor' => 'required',
            'fk_category' => 'required',
            'thumbnail' => 'mimes:jpeg,jpg,png|max:1048',
        ];

        $message = [
            'name.required' => 'Nama kursus tidak boleh kosong',
            'name.between' => 'Nama kursus harus lebih dari 2 karakter dan kurang dari 100 karakter',
            'price.numeric' => 'Harga kursus harus berupa angka',
            'price.min' => 'Harga kursus harus lebih dari 0',
            'detail.required' => 'Detail tidak boleh kosong',
            'mentor.required' => 'Nama mentor tidak boleh kosong',
            'fk_category.required' => 'Kategori tidak boleh kosong',
            'thumbnail.mimes' => 'Gambar harus berformat jpeg/jpg/png',
            'thumbnail.max' => 'Gambar max berukuran 1Mb',
        ];

        $validator = Validator::make($request->all(), $validate, $message);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $category = Category::find($request->fk_category);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 400);
        }

        $data = new Course;
        $data->name = $request->name;
        $data->price = $request->price ? $request->price : 0;
        $data->detail = $request->detail;
        $data->mentor = $request->mentor;
        if ($request->thumbnail) {
            $thumbnail = $request->file('thumbnail');
            $filename = Carbon::now()->format('dmYHis') . '.' . $thumbnail->getClientOriginalExtension();
            $result = self::upload($thumbnail->getRealPath(), $filename);
            $data->thumbnail = $result;
        }
        $data->fk_category = $request->fk_category;
        $data->save();

        $data->price = 'Rp. ' . number_format($data->price, 0, ',', '.');
        $data->sell = 0;
        $data->category = $data->category->name;
        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil dibuat',
            'data' => $data->only(
                'id',
                'name',
                'thumbnail',
                'price',
                'detail',
                'mentor',
                'sell',
                'category',
                'status'
            )
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validate = [
            'name' => 'required|between:2,100',
            'price' => 'numeric|min:0',
            'detail' => 'required',
            'mentor' => 'required',
            'sell' => 'required',
            'fk_category' => 'required',
            'thumbnail' => 'mimes:jpeg,jpg,png|max:1048',
        ];

        $message = [
            'name.required' => 'Nama kursus tidak boleh kosong',
            'name.between' => 'Nama kursus harus lebih dari 2 karakter dan kurang dari 100 karakter',
            'price.numeric' => 'Harga kursus harus berupa angka',
            'price.min' => 'Harga kursus harus lebih dari 0',
            'detail.required' => 'Detail tidak boleh kosong',
            'mentor.required' => 'Nama mentor tidak boleh kosong',
            'sell.required' => 'Jumlah terjual tidak boleh kosong',
            'fk_category.required' => 'Kategori tidak boleh kosong',
            'thumbnail.mimes' => 'Gambar harus berformat jpeg/jpg/png',
            'thumbnail.max' => 'Gambar max berukuran 1Mb',
        ];

        $validator = Validator::make($request->all(), $validate, $message);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $category = Category::find($request->fk_category);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 400);
        }
        $data = Course::find($id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Kursus tidak ditemukan'
            ], 400);
        }
        $data->name = $request->name;
        $data->price = $request->price ? $request->price : 0;
        $data->detail = $request->detail;
        $data->mentor = $request->mentor;
        $data->fk_category = $request->fk_category;
        if ($request->thumbnail) {
            $thumbnail = $request->file('thumbnail');
            $filename = Carbon::now()->format('dmYHis') . '.' . $thumbnail->getClientOriginalExtension();
            $result = self::replace($data->thumbnail, $thumbnail->getRealPath(), $filename);
            $data->thumbnail = $result;
        }
        $data->sell = $request->sell;
        $data->save();

        $data->price = 'Rp. ' . number_format($data->price, 0, ',', '.');
        $data->category = $data->category->name;
        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil diperbarui',
            'data' => $data->only(
                'id',
                'name',
                'thumbnail',
                'price',
                'detail',
                'mentor',
                'sell',
                'category',
                'status',
            )
        ], 200);
    }

    public function destroy($id)
    {
        $data = Course::find($id);
        if (isset($data)) {
            self::delete($data->thumbnail);
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Kursus berhasil dihapus',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kursus tidak ditemukan',
            ], 404);
        }
    }

    public function path($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    public function upload($image, $filename)
    {
        $result = cloudinary()->upload(
            $image,
            [
                "public_id" => self::path($filename),
                "folder"    => 'course'
            ]
        )->getSecurePath();

        return $result;
    }

    public function replace($path, $image, $public_id)
    {
        self::delete($path);
        return self::upload($image, $public_id);
    }

    public function delete($path)
    {
        $public_id = 'course/' . self::path($path);
        return cloudinary()->destroy($public_id);
    }
}
