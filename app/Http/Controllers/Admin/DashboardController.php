<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::where('role', 2)->count();
        $course = Course::count();
        $freeCourse = Course::where('status', 'Gratis')->count();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil didapatkan',
            'data' => [
                'user' => $user,
                'course' => $course,
                'free_course' => $freeCourse
            ]
        ], 200);
    }
}
