<?php

namespace Astrotomic\PhpunitAssertions\Laravel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Constraints\HasInDatabase;
use PHPUnit\Framework\Assert as PHPUnit;

final class ModelAssertions
{
    /**
     * @param  string|\Illuminate\Database\Eloquent\Model  $table
     * @param  array  $data
     * @param  string|null  $connection
     */
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

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $expected
     * @param  \Illuminate\Database\Eloquent\Model|mixed  $actual
     */
    public static function assertSame(Model $expected, $actual): void
    {
        PHPUnit::assertInstanceOf(get_class($expected), $actual);
        PHPUnit::assertSame($expected->exists, $actual->exists);
        PHPUnit::assertTrue($expected->is($actual));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $relation
     * @param  string|\Illuminate\Database\Eloquent\Model|mixed  $actual
     * @param  string|null  $type
     */
    public static function assertRelated(Model $model, string $relation, $actual, ?string $type = null)
    {
        PHPUnit::assertTrue(method_exists($model, $relation));
        PHPUnit::assertInstanceOf(Relation::class, $model->$relation());

        if ($type) {
            PHPUnit::assertInstanceOf($type, $model->$relation());
        }

        $related = $model->$relation()->getRelated();
        PHPUnit::assertInstanceOf(Model::class, $related);

        if (is_string($actual)) {
            PHPUnit::assertInstanceOf($actual, $related);
        } else {
            PHPUnit::assertInstanceOf(get_class($actual), $related);
            self::assertSame(
                $actual,
                $model->$relation()->whereKey($actual->getKey())->first()
            );
        }
    }
}
