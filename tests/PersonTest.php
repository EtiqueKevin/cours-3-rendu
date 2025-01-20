<?php

namespace Tests;

use App\Entity\Person;
use PHPUnit\Framework\TestCase;
use App\Entity\Wallet;
use App\Entity\Product;

class PersonTest extends TestCase
{

    private Person $person;
    private Person $person2;
    private Person $person3;
    private Person $person4;

    public function setUp(): void
    {
        $this->person = new Person('Jean', 'EUR');
        $this->person2 = new Person('Jeanne', 'EUR');
        $this->person3 = new Person('Jeanneau', 'USD');
        $this->person4 = new Person('jeannine', 'EUR');
    }

    public function testGetName() {
        $this->assertEquals('Jean', $this->person->getName());
    }

    public function testSetName() {
        $this->person->setName('Paul');
        $this->assertEquals('Paul', $this->person->getName());
    }

    public function testGetWallet() {
        $this->assertInstanceOf('App\Entity\Wallet', $this->person->getWallet());
        $this->assertEquals('EUR', $this->person->getWallet()->getCurrency());
    }

    public function testSetWallet() {
        $this->person->setWallet(new Wallet('USD'));
        $this->assertEquals('USD', $this->person->getWallet()->getCurrency());
    }

    //J'AI DU CHANGER LE NON EGAL STRICT CAR LE TYPE DE DONNEE N'EST PAS LE MEME (DANS L'ENTITY PERSON)
    public function testHasFund() {
        $this->person->getWallet()->setBalance(0);
        $this->assertEquals(0, $this->person->getWallet()->getBalance());
        $this->assertFalse($this->person->hasFund());
        $this->person->getWallet()->setBalance(100);
        $this->assertTrue($this->person->hasFund());
    }

    public function testTransfertFund() {
        $this->person->getWallet()->setBalance(100);
        $this->person->transfertFund(50, $this->person2);
        $this->assertEquals(50, $this->person->getWallet()->getBalance());
        $this->assertEquals(50, $this->person2->getWallet()->getBalance());
        $this->expectException(\Exception::class);
        $this->person->transfertFund(50, $this->person3);
    }

    public function testDividedWalletNormal() {
        $this->person->getWallet()->setBalance(100);
        $this->person->divideWallet([$this->person2, $this->person4]);
        $this->assertEquals(0, $this->person->getWallet()->getBalance());
        $this->assertEquals(50, $this->person2->getWallet()->getBalance());
        $this->assertEquals(50, $this->person4->getWallet()->getBalance());
    }

    public function testDividedWalletException() {
        $this->person->getWallet()->setBalance(100);
        $this->person->divideWallet([$this->person2, $this->person3]);
        $this->assertEquals(0, $this->person->getWallet()->getBalance());
        $this->assertEquals(100, $this->person2->getWallet()->getBalance());
        $this->assertEquals(0, $this->person3->getWallet()->getBalance());
    }

    public function testBuyProduct() {
        $product1 = new Product('product1', ['EUR' => 100.0], 'food');
        $product2 = new Product('product2', ['EUR' => 50.0, 'USD' => 80.0], 'other');
        $this->person->getWallet()->setBalance(200.0);
        $this->person->buyProduct($product1);
        $this->assertEquals(100, $this->person->getWallet()->getBalance());
        $this->person->buyProduct($product2);
        $this->assertEquals(50, $this->person->getWallet()->getBalance());
        $this->expectException(\Exception::class);
        $this->person->buyProduct($product2);
        $this->person3->getWallet()->setBalance(100);
        $this->expectException(\Exception::class);
        $this->person3->buyProduct($product1);
    }
}