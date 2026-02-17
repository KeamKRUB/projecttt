<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Artist extends Model
{
    protected $fillable = [
        'name', 'image_path'
    ];

    public function songs(): HasMany {
        return $this->hasMany(Song::class);
    }

    public function artistImage() : Attribute
    {
        // นำไฟล์ default-artist.png ไปไว้ที่ storage/app/public/images/default-artist.png
        $url = "/storage/images/default-artist.png";

        if (Str::startsWith($this->image_path, 'http')) {
            $url = $this->image_path;
        } else if (!empty($this->image_path)) {
            $url = Storage::url($this->image_path);
        }

        return new Attribute(
            get: fn () => $url
        );
    }
}
