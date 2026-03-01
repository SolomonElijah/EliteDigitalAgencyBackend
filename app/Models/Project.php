<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'thumbnail', 'images',
        'tech_stack', 'demo_url', 'github_url', 'client_name',
        'category', 'status', 'featured', 'sort_order',
    ];

    protected $casts = [
        'images'     => 'array',
        'tech_stack' => 'array',
        'featured'   => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    public function financials()
    {
        return $this->hasMany(ProjectFinancial::class);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : null;
    }

    public function getImagesUrlAttribute()
    {
        return collect($this->images ?? [])->map(fn($img) => asset('storage/' . $img));
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
