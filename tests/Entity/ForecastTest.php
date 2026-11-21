<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['-10', '14'],
            ['20', '68'],
            ['37', '98.6'],
            ['-40', '-40'],
            ['15.5', '59.9'],
            ['25', '77'],
        ];
    }

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $forecast = new Forecast();
        $forecast->setTemperature((float) $celsius);

        $this->assertEquals((float) $expectedFahrenheit, $forecast->getFahrenheit());
    }
}
