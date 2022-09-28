<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid()->getHex();
        });
    }

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function course()
    {
        return $this->hasMany(Course::class, 'fk_category');
    }
}
