<?php

use PHPUnit\Framework\TestCase;

require_once 'classes/Project.php';
require_once 'classes/Amortization.php';
require_once 'classes/Payment.php';

class ProjectTest extends TestCase
{
    private function testProject()
    {
        return new Project(1, 1, 0.0, 'Test Project', 1, []);
    }

    private function testAmortization($options = [])
    {
        $defaultOptions = [
            'id' => 1,
            'project_id' => 1,
            'amount' => 500.0,
            'schedule_date' => '2023-12-15',
            'state' => 'pending',
        ];

        $amortizationOptions = array_merge($defaultOptions, $options);

        return new Amortization(
            $amortizationOptions['id'],
            $amortizationOptions['project_id'],
            $amortizationOptions['amount'],
            $amortizationOptions['schedule_date'],
            $amortizationOptions['state']
        );
    }

    private function testPayment()
    {
        return new Payment(1, 1, 1, 300.0, 1, 'pending');
    }

    private function testPaymentNegative()
    {
        return new Payment(1, 1, 1, -300.0, 1, 'pending');
    }

    public function testGetAmortizationById_ReturnsCorrectAmortization()
    {
        $project = $this->testProject();
        $amortizationId = 1;

        $amortizationTest = $this->testAmortization(['id' => $amortizationId]);
        $project->addAmortization($amortizationTest);

        $amortization = $project->getAmortizationById($amortizationId);

        $this->assertInstanceOf(Amortization::class, $amortization);
        $this->assertEquals($amortizationId, $amortization->id);
    }

    public function testGetAmortizationById_ReturnsNullForNonExistingAmortization()
    {
        $project = $this->testProject();
        $nonExistingAmortizationId = 999;

        $amortization = $project->getAmortizationById($nonExistingAmortizationId);

        $this->assertNull($amortization);
    }

    public function testAddPaymentToBalance_UpdatesBalance()
    {
        $project = $this->testProject();
        $amortization = $this->testAmortization();
        $payment = $this->testPayment();

        $project->addAmortization($amortization);
        $project->addPaymenToBalance($payment);

        $expectedBalance = 300.0;
        
        $this->assertEquals($expectedBalance, $project->balance);
    }

    public function testAddPaymentToBalance_NoUpdatesBalanceIfAmoutIsNegative()
    {
        $project = $this->testProject();
        $amortization = $this->testAmortization();
        $payment = $this->testPaymentNegative();

        $expectedBalance = $project->balance;

        $project->addAmortization($amortization);
        $project->addPaymenToBalance($payment);

        $result = $project->balance;
        
        $this->assertEquals($expectedBalance, $result);
    }

    public function testAddPaymentToBalance_IgnoresNonPendingAmortization()
    {
        $project = $this->testProject();
        $amortization = $this->testAmortization(['state' => 'paid']);
        $payment = $this->testPayment();

        $project->addAmortization($amortization);
        $project->addPaymenToBalance($payment);

        $this->assertEquals(0.0, $project->balance);
    }
    
}
