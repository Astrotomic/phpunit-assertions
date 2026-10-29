<?php

namespace Alcohol;

final class ISO4217
{
    public function getByAlpha3(string $currency): array
    {
        if ($currency === 'EUR') {
            return ['exp' => 2];
        }

        if ($currency === 'JPY') {
            return ['exp' => 0];
        }

        throw new \OutOfBoundsException;
    }
}
