<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel;

use Astrotomic\PhpunitAssertions\Laravel\ModelAssertions;
use Astrotomic\PhpunitAssertions\Tests\Laravel\Models\Comment;
use Astrotomic\PhpunitAssertions\Tests\Laravel\Models\Post;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ModelAssertionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Post::migrate();
        Comment::migrate();
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public function it_can_validate_exists(): void
    {
        $post = Post::create([
            'title' => self::randomString(),
        ]);

        ModelAssertions::assertExists($post);
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public function it_can_validate_same(): void
    {
        $post = Post::create([
            'title' => self::randomString(),
        ]);

        ModelAssertions::assertSame($post, Post::first());
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public function it_can_validate_relationship(): void
    {
        $post = Post::create([
            'title' => self::randomString(),
        ]);

        $comment = Comment::create([
            'message' => self::randomString(),
            'post_id' => $post->getKey(),
        ]);

        ModelAssertions::assertRelated($post, 'comments', Comment::class);
        ModelAssertions::assertRelated($post, 'comments', $comment);

        ModelAssertions::assertRelated($post, 'comments', Comment::class, HasMany::class);
        ModelAssertions::assertRelated($post, 'comments', $comment, HasMany::class);

        ModelAssertions::assertRelated($comment, 'post', Post::class);
        ModelAssertions::assertRelated($comment, 'post', $post);

        ModelAssertions::assertRelated($comment, 'post', Post::class, BelongsTo::class);
        ModelAssertions::assertRelated($comment, 'post', $post, BelongsTo::class);
    }
}
