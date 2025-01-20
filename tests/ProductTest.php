<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;

class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product('test', ['USD' => 100], 'food');
    }

    public function testGetName(): void
    {
        $this->assertEquals('test', $this->product->getName());
    }

    public function testGetPrices(): void
    {
        $this->assertEquals(['USD' => 100], $this->product->getPrices());
    }

    public function testGetType(): void
    {
        $this->assertEquals('food', $this->product->getType());
    }

    public function testSetName(): void
    {
        $this->product->setName('test2');
        $this->assertEquals('test2', $this->product->getName());
    }

    public function testSetPrices(): void
    {
        $this->product->setPrices(['USD' => 200]);
        $this->assertEquals(['USD' => 200], $this->product->getPrices());
    }

    public function testSetPricesNonAvailableCurrency(): void
    {
        $this->product->setPrices(['TEST' => 200]);
        $this->assertEquals(['USD' => 100], $this->product->getPrices());
    }

    public function testSetPricesNegativePrice(): void
    {
        $this->product->setPrices(['USD' => -100]);
        $this->assertEquals(['USD' => 100], $this->product->getPrices());
    }

    public function testSetType(): void
    {
        $this->product->setType('tech');
        $this->assertEquals('tech', $this->product->getType());
    }

    public function testSetTypeNonAvailableType(): void
    {
        $this->expectException(\Exception::class);
        $this->product->setType('TEST');
    }

    public function testGetTVA(): void
    {
        $this->assertEquals(0.1, $this->product->getTVA());
    }

    public function testListCurrencies(): void
    {
        $this->assertEquals(['USD'], $this->product->listCurrencies());
    }

    public function testGetPrice(): void
    {
        $this->assertEquals(100, $this->product->getPrice('USD'));
    }

    public function testGetPriceNonAvailableCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->product->getPrice('TEST');
    }

    public function testGetPriceNotSetCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->product->getPrice('EUR');
    }

}
