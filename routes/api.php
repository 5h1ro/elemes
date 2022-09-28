<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\CategoryController as UserCategoryController;
use App\Http\Controllers\User\CourseController as UserCourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('login', 'login')->name('login');
            Route::post('register', 'register')->name('register');
        });
    });

    Route::group(['middleware' => 'api'], function () {
        Route::prefix('auth')->group(function () {
            Route::controller(AuthController::class)->group(function () {
                Route::get('logout', 'logout')->name('logout');
            });
        });
        Route::group(['middleware' => 'role:Admin'], function () {
            Route::prefix('admin')->group(function () {
                Route::controller(UserController::class)->group(function () {
                    Route::get('user', 'index')->name('user');
                    Route::delete('user/{id}', 'delete')->name('delete');
                });

                Route::controller(CourseController::class)->group(function () {
                    Route::get('course', 'index')->name('admin.course');
                    Route::get('course/{id}', 'find')->name('admin.course.find');
                    Route::post('course/store', 'store')->name('admin.course.store');
                    Route::put('course/update/{id}', 'update')->name('admin.course.update');
                    Route::delete('course/{id}', 'destroy')->name('admin.course.delete');
                });

                Route::controller(DashboardController::class)->group(function () {
                    Route::get('dashboard', 'index')->name('admin.dashboard');
                });

                Route::controller(CategoryController::class)->group(function () {
                    Route::get('category', 'index')->name('admin.category');
                });
            });
        });
        Route::group(['middleware' => 'role:User'], function () {
            Route::prefix('user')->group(function () {
                Route::controller(UserCourseController::class)->group(function () {
                    Route::get('course/{sort?}', 'index')->name('user.course');
                    Route::get('course/detail/{id}', 'find')->name('user.course.find');
                    Route::post('course/search/{sort?}', 'search')->name('user.course.search');
                });

                Route::controller(UserCategoryController::class)->group(function () {
                    Route::get('category', 'index')->name('user.category');
                    Route::get('category/popular', 'popular')->name('user.category.popular');
                });
            });
        });
    });
});
