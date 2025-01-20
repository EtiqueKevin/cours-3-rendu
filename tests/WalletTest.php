<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Wallet;

class WalletTest extends TestCase
{
    private Wallet $wallet;

    protected function setUp(): void
    {
        $this->wallet = new Wallet('USD');
    }

    public function testGetBalance(): void
    {
        $this->assertEquals(0, $this->wallet->getBalance());
    }

    public function testGetCurrency(): void
    {
        $this->assertEquals('USD', $this->wallet->getCurrency());
    }

    public function testSetBalance(): void
    {
        $this->wallet->setBalance(100);
        $this->assertEquals(100, $this->wallet->getBalance());

        $this->expectException(\Exception::class);
        $this->wallet->setBalance(-100);
    }

    public function testSetCurrency(): void
    {
        $this->wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $this->wallet->getCurrency());

        $this->expectException(\Exception::class);
        $this->wallet->setCurrency('TEST');
    }

    public function testAddFund(): void
    {
        $this->wallet->addFund(100);
        $this->assertEquals(100, $this->wallet->getBalance());

        $this->expectException(\Exception::class);
        $this->wallet->addFund(-100);
    }

    public function testRemoveFund(): void
    {
        $this->wallet->addFund(100);
        $this->wallet->removeFund(50);
        $this->assertEquals(50, $this->wallet->getBalance());
    }

    public function testRemoveFundException(): void
    {
        $this->expectException(\Exception::class);
        $this->wallet->removeFund(100);
    }

    public function testRemoveFundException2(): void
    {
        $this->expectException(\Exception::class);
        $this->wallet->removeFund(-100);
    }
}
