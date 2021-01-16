<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel;

use Astrotomic\PhpunitAssertions\Laravel\ModelAssertions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class ModelAssertionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('posts', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public function it_can_validate_exists(): void
    {
        $model = new class extends Model {
            protected $table = 'posts';
            protected $guarded = [];
        };

        $model->title = self::randomString();
        $model->save();

        ModelAssertions::assertExists($model);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public function it_can_validate_same(): void
    {
        $model = new class extends Model {
            protected $table = 'posts';
            protected $guarded = [];
        };

        $model->title = self::randomString();
        $model->save();

        ModelAssertions::assertSame($model, $model->query()->first());
    }
}
