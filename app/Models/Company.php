<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\Employee;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Omitted for brevity


    /**
     * Get the comments for the blog post.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function getLogoAttribute($value): string|null {

        if ( !empty($value)) {
            if (Storage::disk('public')->exists($value)){
                return url( Storage::url($value));
            }
        }

        return $value;
    }
}
