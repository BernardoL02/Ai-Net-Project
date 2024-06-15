<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =['title', 'genre_code', 'year', 'poster_filename','trailer_url', 'synopsis'];


    public function genre():BelongsTo
    {
        return $this->belongsTo(Genre::class,'genre_code','code')->withTrashed();

    }

    public function screenings():HasMany
    {
        return $this->hasMany(Screening::class);

    }

    public function getPosterFullUrlAttribute()
    {
        if ($this->poster_filename && Storage::exists("public/posters/{$this->poster_filename}")) {
            return asset("storage/posters/{$this->poster_filename}");
        } else {
            return asset("img/default_poster.png");
        }
    }

    public function screeningsDate($dateFilter){

        if($dateFilter != '-'){

            return $this->screenings()->where('date','=', $dateFilter)->orderBy('start_time')->get();
        }

        return $this->screenings()->where('date','>=', Carbon::today())->where('date','<=', Carbon::today()->addWeeks(2))->orderBy('date')->orderBy('start_time')->get();

    }


    public function getTrailerEmbedUrlAttribute()
    {
        if (str_contains($this->trailer_url, 'watch?v=')) {
            return str_replace('watch?v=', 'embed/', $this->trailer_url);
        } else {
            return $this->trailer_url;
        }
    }

}
