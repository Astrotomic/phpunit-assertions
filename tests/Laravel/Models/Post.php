<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Post extends Model
{
    protected $table = 'posts';
    protected $guarded = [];

    public static function migrate(): void
    {
        Schema::create((new self)->table, static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}