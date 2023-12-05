<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;

require_once 'classes/Project.php';
require_once 'classes/Amortization.php';
require_once 'classes/Payment.php';
require_once 'classes/Promoter.php';
require_once 'classes/GlobalGroup.php';
require_once 'classes/Member.php';

/**
 * Test class for the processPaymentsOnAmortization method in the Amortization class.
 */

class processPaymentsOnAmortizationTest extends TestCase
{

    /**
     * Test the processPaymentsOnAmortization method for a successful payment.
     */
    public function testProcessPaymentsOnAmortization_SuccessfulPayment()
    {
        $givenDate = new DateTime('2023-12-06');
        $globalGroup = new GlobalGroup(1, 'test', []);
        $member1 = new Member(1, 'user1@gmail.com');
        $member2 = new Member(2, 'user2@gmail.com');
        $globalGroup->addMember($member1);
        $globalGroup->addMember($member2);

        $mailer = new PHPMailer(true);

        $amortization = new Amortization(1, 1, 500.0, new DateTime('2023-12-06'), 'pending');
        $payment = new Payment(1, $amortization->project_id, $amortization->id, 500.0, 1, 'pending');
        $amortization->payments = [$payment];

        $PROJECT = Project::find($amortization->project_id);
        $PROMOTER = Promoter::find($PROJECT->promoter_id);

        $PROJECT->addPaymenToBalance($payment);
        $PROJECT->addAmortization($amortization);

        $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

        $this->assertEquals("Amortization ID:1 payments processed successfully", $result);
    }

    /**
     * Test the processPaymentsOnAmortization method when there are no payments for the amortization.
     */
    public function testProcessPaymentsOnAmortization_NoPayments()
    {
        $givenDate = new DateTime('2023-12-05');
        $globalGroup = new GlobalGroup(1, 'test', []);
        $member1 = new Member(1, 'user1@gmail.com');
        $member2 = new Member(2, 'user2@gmail.com');
        $globalGroup->addMember($member1);
        $globalGroup->addMember($member2);

        $mailer = new PHPMailer(true);

        $amortization = new Amortization(1, 1, 500.0, new DateTime('2023-12-06'), 'pending');

        $PROJECT = Project::find($amortization->project_id);
        $PROMOTER = Promoter::find($PROJECT->promoter_id);
        $PROJECT->addAmortization($amortization);

        $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

        $this->assertEquals("No payments for amortization 1", $result);
    }

    /**
     * Test the processPaymentsOnAmortization method for an unsuccessful payment due to a date mismatch.
     */

    public function testProcessPaymentsOnAmortization_UnsuccessfullPaymentDelayedDate()
    {
        $givenDate = new DateTime('2023-12-06');
        $globalGroup = new GlobalGroup(1, 'test', []);
        $member1 = new Member(1, 'user1@gmail.com');
        $member2 = new Member(2, 'user2@gmail.com');
        $globalGroup->addMember($member1);
        $globalGroup->addMember($member2);

        $mailer = new PHPMailer(true);

        $amortization = new Amortization(1, 1, 500.0, new DateTime('2023-12-04'), 'pending');
        $amortizationDate = $amortization->schedule_date;
        $payment = new Payment(1, $amortization->project_id, $amortization->id, 500.0, 1, 'pending');
        $amortization->payments = [$payment];

        $PROJECT = Project::find($amortization->project_id);
        $PROMOTER = Promoter::find($PROJECT->promoter_id);

        $PROJECT->addPaymenToBalance($payment);
        $PROJECT->addAmortization($amortization);

        $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

        $this->assertEquals("Date mismatch (Amortization date: {$amortizationDate->format('Y-m-d')}, Given date: {$givenDate->format('Y-m-d')})", $result);
    }

     /**
     * Test the processPaymentsOnAmortization method for insufficient balance in the project.
     */

    public function testProcessPaymentsOnAmortization_InsufficientBalance()
    {
        $givenDate = new DateTime('2023-12-06');
        $globalGroup = new GlobalGroup(1, 'test', []);
        $member1 = new Member(1, 'user1@gmail.com');
        $member2 = new Member(2, 'user2@gmail.com');
        $globalGroup->addMember($member1);
        $globalGroup->addMember($member2);

        $mailer = new PHPMailer(true);

        $amortization = new Amortization(1, 1, 1000.0, new DateTime('2023-12-06'), 'pending');
        $payment = new Payment(1, $amortization->project_id, $amortization->id, 500.0, 1, 'pending');
        $amortization->payments = [$payment];

        $PROJECT = Project::find($amortization->project_id);
        $PROMOTER = Promoter::find($PROJECT->promoter_id);

        $PROJECT->addPaymenToBalance($payment);
        $PROJECT->addAmortization($amortization);

        // Set balance to less than the amount
        $PROJECT->balance = 400.0;

        $PROJECT->addPaymenToBalance($payment);
        $PROJECT->addAmortization($amortization);

        $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

        $this->assertEquals("Insufficient balance in the project", $result);
    }
    
    /**
     * Test adding multiple amortizations to a project.
     */
    public function testProcessPaymentsOnAmortization_AddAmortizations()
    {
        $amortization = new Amortization(1, 1, 500.0, new DateTime('2023-12-06'), 'pending');
        $payment1 = new Payment(1, $amortization->project_id, $amortization->id, 300.0, 1, 'pending');
        $payment2 = new Payment(2, $amortization->project_id, $amortization->id, 200.0, 1, 'pending');
        $amortization->payments = [$payment1, $payment2];

        // Associate amortizations with the same project
        $PROJECT = Project::find($amortization->project_id);
        $PROMOTER = Promoter::find($PROJECT->promoter_id);
        $PROJECT->addPaymenToBalance($payment1);
        $PROJECT->addPaymenToBalance($payment2);
        $PROJECT->addAmortization($amortization);

        $amortization2 = new Amortization(2, 1, 400.0, new DateTime('2023-12-05'), 'pending');
        $payment3 = new Payment(1, $amortization2->project_id, $amortization2->id, 300.0, 1, 'pending');
        $payment4 = new Payment(2, $amortization2->project_id, $amortization2->id, 200.0, 1, 'pending');
        $amortization2->payments = [$payment3, $payment4];

        // Associate amortizations with the same project
        $PROJECT->addPaymenToBalance($payment3);
        $PROJECT->addPaymenToBalance($payment4);
        $PROJECT->addAmortization($amortization2);

        $amortization3 = new Amortization(3, 1, 400.0, new DateTime('2023-12-07'), 'pending');
        $payment5 = new Payment(1, $amortization3->project_id, $amortization3->id, 150.0, 1, 'pending');
        $payment6 = new Payment(2, $amortization3->project_id, $amortization3->id, 200.0, 1, 'pending');
        $amortization3->payments = [$payment5, $payment6];

        // Associate amortizations with the same project
        $PROJECT->addPaymenToBalance($payment5);
        $PROJECT->addPaymenToBalance($payment6);
        $PROJECT->addAmortization($amortization3);

        $this->assertEquals(3, count($PROJECT->amortizations));
    }
}
