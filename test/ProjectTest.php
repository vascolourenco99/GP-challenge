<?php

use PHPUnit\Framework\TestCase;

require_once 'classes/Project.php';
require_once 'classes/Amortization.php';
require_once 'classes/Payment.php';

/**
 * Test class for the Project class.
 */
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

    /**
     * Test if the Project class returns the correct amortization instance by ID.
     */

    public function testProject_ReturnsCorrectAmortization()
    {
        $project = $this->testProject();
        $amortizationId = 1;

        $amortizationTest = $this->testAmortization(['id' => $amortizationId]);
        $project->addAmortization($amortizationTest);

        $amortization = $project->getAmortizationById($amortizationId);

        $this->assertInstanceOf(Amortization::class, $amortization);
        $this->assertEquals($amortizationId, $amortization->id);
    }

     /**
     * Test if the Project class returns null for a non-existing amortization ID.
     */

    public function testProject_ReturnsNullForNonExistingAmortization()
    {
        $project = $this->testProject();
        $nonExistingAmortizationId = 999;

        $amortization = $project->getAmortizationById($nonExistingAmortizationId);

        $this->assertNull($amortization);
    }

    /**
     * Test if the Project class updates the balance correctly after adding a payment.
     */
    public function testProject_UpdatesBalance()
    {
        $project = $this->testProject();
        $amortization = $this->testAmortization();
        $payment = $this->testPayment();

        $project->addAmortization($amortization);
        $project->addPaymenToBalance($payment);

        $expectedBalance = 300.0;
        
        $this->assertEquals($expectedBalance, $project->balance);
    }

    /**
     * Test if the Project class does not update the balance if the payment amount is negative.
     */
    public function testProject_NoUpdatesBalanceIfAmoutIsNegative()
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


    /**
     * Test if the Project class ignores adding payment for a paid amortization.
     */
    public function testProject_IgnoresPaidAmortization()
    {
        $project = $this->testProject();
        $amortization = $this->testAmortization(['state' => 'paid']);
        $payment = $this->testPayment();

        $project->addAmortization($amortization);
        $project->addPaymenToBalance($payment);

        $this->assertEquals(0.0, $project->balance);
    }
    
}
