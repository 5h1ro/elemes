<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = User::where('role', 2)->get();

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

    public function delete($id)
    {
        $data = User::find($id);

        if ($data) {
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data telah dihapus',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }
}
