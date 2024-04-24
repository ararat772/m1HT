<?php

namespace App\Tests\Payments;

use App\PaymentProcessor\StripePaymentProcessor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StripePaymentTest extends KernelTestCase
{
    public function testPaymentIsNotWorkingWithIncorrectValue(): void
    {
        $paymentProcessor = new StripePaymentProcessor();

        $result = $paymentProcessor->processPayment(5);

        $this->assertFalse($result);

    }

    public function testPaymentIsWorkingWithValue(): void
    {
        $paymentProcessor = new StripePaymentProcessor();

        $result = $paymentProcessor->processPayment(120);

        $this->assertTrue($result);
    }
}