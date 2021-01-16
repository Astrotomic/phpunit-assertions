<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comment extends Model
{
    protected $table = 'comments';
    protected $guarded = [];

    public static function migrate(): void
    {
        Schema::create((new self)->table, static function (Blueprint $table): void {
            $table->increments('id');
            $table->text('message');
            $table->foreignId('post_id')->constrained('posts');
            $table->timestamps();
        });
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
