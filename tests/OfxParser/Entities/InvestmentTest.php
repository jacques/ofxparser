<?php declare(strict_types=1);

namespace OfxParserTest\Entities;

use SimpleXMLElement;
use PHPUnit\Framework\TestCase;
use OfxParser\Entities\Investment;

/**
 * Need to extend the abstract Investment entity to test it.
 *
 * Defined properties, but no loadOfx method defined.
 */
class InvestmentNoLoadOfx extends Investment
{
    /**
     * @var string
     */
    public $public1 = 'value 100';

    /**
     * @var string
     */
    protected $protected1 = 'value 200';

    /**
     * @var string
     */
    private $private1 = 'value 300';
}

/**
 * Need to extend the abstract Investment entity to test it.
 *
 * This should be a "complete" investment entity
 */
class InvestmentValid extends InvestmentNoLoadOfx
{
    /**
     * @var string
     */
    private $private2 = 'value 310';

    /**
     * Imports the OFX data for this node.
     * @param SimpleXMLElement $node
     * @return $this
     */
    public function loadOfx(SimpleXMLElement $node)
    {
        // No-op: just need to test the exception
        return $this;
    }
}

/**
 * @covers OfxParser\Entities\Investment
 */
final class InvestmentTest extends TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testLoadOfxException(): void
    {
        static::expectException(\Exception::class);

        $xml = new SimpleXMLElement('<xml></xml>');
        $entity = new InvestmentNoLoadOfx();
        $entity->loadOfx($xml);
    }

    /**
     * If no exception thrown, we're good.
     */
    public function testLoadOfxValid(): void
    {
        $xml = new SimpleXMLElement('<xml></xml>');
        $entity = new InvestmentValid();
        $entity->loadOfx($xml);
        $this->assertTrue(true);
    }

    /**
     * If no exception thrown, we're good.
     */
    public function testGetProperties(): void
    {
        $expectedProps = [
            'public1',
            'protected1',
        ];

        $entity = new InvestmentValid();
        $actualProps = $entity->getProperties();

        $this->assertSame($expectedProps, array_keys($actualProps));
        $this->assertSame($expectedProps, array_values($actualProps));
    }
}
