<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel;

use Astrotomic\PhpunitAssertions\Laravel\ModelAssertions;
use Astrotomic\PhpunitAssertions\Tests\Laravel\Models\Comment;
use Astrotomic\PhpunitAssertions\Tests\Laravel\Models\Post;

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
     * @dataProvider hundredTimes
     */
    public function it_can_validate_related(): void
    {
        $post = Post::create([
            'title' => self::randomString(),
        ]);

        $comment = Comment::create([
            'message' => self::randomString(),
            'post_id' => $post->getKey(),
        ]);

        ModelAssertions::assertRelated($post, $comment, 'comments');
        ModelAssertions::assertRelated($comment, $post, 'post');
    }
}
