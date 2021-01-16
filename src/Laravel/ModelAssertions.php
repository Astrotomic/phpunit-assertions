<?php

namespace Astrotomic\PhpunitAssertions\Laravel;

use Illuminate\Database\Eloquent\Model;
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
}
