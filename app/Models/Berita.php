<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'foto',
        'foto_caption',
        'user_id',
        'status',
        'published_at',
        'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Generate slug from judul
    public static function generateSlug($judul)
    {
        $slug = Str::slug($judul);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // Relationship with User (Author)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Alias for author
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope for published
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // Scope for draft
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        return $this->status === 'published' ? 'Dipublikasikan' : 'Draft';
    }

    // Get status color
    public function getStatusColorAttribute()
    {
        return $this->status === 'published' ? 'success' : 'secondary';
    }

    // Get formatted date
    public function getFormattedDateAttribute()
    {
        return $this->published_at
            ? $this->published_at->format('d F Y')
            : $this->created_at->format('d F Y');
    }

    // Get excerpt from konten
    public function getExcerptAttribute()
    {
        if ($this->ringkasan) {
            return $this->ringkasan;
        }
        return Str::limit(strip_tags($this->konten), 150);
    }
}
