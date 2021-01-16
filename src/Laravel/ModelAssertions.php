<?php

namespace Astrotomic\PhpunitAssertions\Laravel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Constraints\HasInDatabase;
use PHPUnit\Framework\Assert as PHPUnit;

trait ModelAssertions
{
    public static function assertExists($table, array $data = [], ?string $connection = null): void
    {
        if ($table instanceof Model) {
            $model = $table;

            $table = $model->getTable();
            $connection = $model->getConnectionName();
            $data = [
                $model->getKeyName() => $model->getKey(),
            ];
        }

        PHPUnit::assertThat(
            $table,
            new HasInDatabase(DB::connection($connection), $data)
        );
    }

    public static function assertSame(Model $expected, $actual): void
    {
        PHPUnit::assertInstanceOf(get_class($expected), $actual);
        PHPUnit::assertSame($expected->exists, $actual->exists);
        PHPUnit::assertTrue($expected->is($actual));
    }

    public static function assertRelated(Model $model, $actual, string $relation)
    {
        PHPUnit::assertInstanceOf(Model::class, $actual);
        PHPUnit::assertTrue(method_exists($model, $relation));
        PHPUnit::assertInstanceOf(Relation::class, $model->$relation());

        $related = $model->$relation()->whereKey($actual->getKey())->first();
        self::assertSame($actual, $related);
    }
}
