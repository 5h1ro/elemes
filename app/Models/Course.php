<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == 0 ? 'Berbayar' : 'Gratis',
        );
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid()->getHex();
        });
    }

    protected $fillable = [
        'name',
        'price',
        'sell',
        'detail',
        'thumbnail',
        'mentor',
        'status',
        'fk_category'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'fk_category');
    }
}
