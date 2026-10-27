<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'title',
        'description',
        'location',
        'event_date',
        'photo_path',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function scopeFilter($query, array $f)
    {
        return $query
            ->when($f['q'] ?? null, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%$s%")
                ->orWhere('description', 'like', "%$s%")
                ->orWhere('location', 'like', "%$s%")))
            ->when($f['type'] ?? null, fn ($q, $t) => $q->where('type', $t))
            ->when($f['category'] ?? null, fn ($q, $c) => $q->where('category_id', $c))
            ->when($f['status'] ?? null, fn ($q, $s) => $q->where('status', $s));
    }
}
