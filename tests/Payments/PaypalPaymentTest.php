<?php

namespace App\Tests\Payments;

use App\PaymentProcessor\PaypalPaymentProcessor;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaypalPaymentTest extends KernelTestCase
{
    public function testPaymentIsNotWorkingWithIncorrectValue(): void
    {
        $paymentProcessor = new PaypalPaymentProcessor();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Too high price');

        $paymentProcessor->pay(120);
    }

    /**
     * @throws Exception
     */
    public function testPaymentIsWorkingWithValue(): void
    {
        $paymentProcessor = new PaypalPaymentProcessor();

        $paymentProcessor->pay(80);

        $this->assertTrue(true);
    }
}