<?php declare(strict_types=1);

namespace OfxParserTest;

use PHPUnit\Framework\TestCase;
use OfxParser\Utils;

/**
 * Fake class for DateTime callback.
 */
final class MyDateTime extends \DateTime { }

/**
 * @covers OfxParser\Utils
 */
final class UtilsTest extends TestCase
{
    /**
     * @return array<int, array<string|float>>
     */
    public function amountConversionProvider(): array
    {
        return [
            '1000.00' => ['1000.00', 1000.0],
            '1000,00' => ['1000,00', 1000.0],
            '1,000.00' => ['1,000.00', 1000.0],
            '1.000,00' => ['1.000,00', 1000.0],
            '-1000.00' => ['-1000.00', -1000.0],
            '-1000,00' => ['-1000,00', -1000.0],
            '-1,000.00' => ['-1,000.00', -1000.0],
            '-1.000,00' => ['-1.000,00', -1000.0],
            '1' => ['1', 1.0],
            '10' => ['10', 10.0],
            '100' => ['100', 1.0], // @todo this is weird behaviour, should not really expect this
            '+1' => ['+1', 1.0],
            '+10' => ['+10', 10.0],
            '+1000.00' => ['+1000.00', 1000.0],
            '+1000,00' => ['+1000,00', 1000.0],
            '+1,000.00' => ['+1,000.00', 1000.0],
            '+1.000,00' => ['+1.000,00', 1000.0],
        ];
    }

    /**
     * @param string $input
     * @param float $output
     * @dataProvider amountConversionProvider
     */
    public function testCreateAmountFromStr(string $input, float $output): void
    {
        $actual = Utils::createAmountFromStr($input);
        self::assertSame($output, $actual);
    }

    public function testCreateDateTimeFromOFXDateFormats(): void
    {
        // October 5, 2008, at 1:22 and 124 milliseconds pm, Easter Standard Time
        $expectedDateTime = new \DateTime('2008-10-05 13:22:00');

        // Test OFX Date Format YYYYMMDDHHMMSS.XXX[gmt offset:tz name]
        $DateTimeOne = Utils::createDateTimeFromStr('20081005132200.124[-5:EST]');
        self::assertSame($expectedDateTime->getTimestamp(), $DateTimeOne->getTimestamp());

        // Test YYYYMMDD
        $DateTimeTwo = Utils::createDateTimeFromStr('20081005');
        self::assertSame($expectedDateTime->format('Y-m-d'), $DateTimeTwo->format('Y-m-d'));

        // Test YYYYMMDDHHMMSS
        $DateTimeThree = Utils::createDateTimeFromStr('20081005132200');
        self::assertSame($expectedDateTime->getTimestamp(), $DateTimeThree->getTimestamp());

        // Test YYYYMMDDHHMMSS.XXX
        $DateTimeFour = Utils::createDateTimeFromStr('20081005132200.124');
        self::assertSame($expectedDateTime->getTimestamp(), $DateTimeFour->getTimestamp());

        // Test empty datetime
        $DateTimeFive = Utils::createDateTimeFromStr('');
        self::assertNull($DateTimeFive);

        // Test DateTime factory callback
        Utils::$fnDateTimeFactory = function($format) { return new MyDateTime($format); };
        $DateTimeSix = Utils::createDateTimeFromStr('20081005');
        self::assertSame($expectedDateTime->format('Y-m-d'), $DateTimeSix->format('Y-m-d'));
        self::assertInstanceOf(\OfxParserTest\MyDateTime::class, $DateTimeSix);
        Utils::$fnDateTimeFactory = null;
    }
}
