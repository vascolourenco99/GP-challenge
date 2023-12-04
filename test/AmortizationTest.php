<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;

require_once 'classes/Project.php';
require_once 'classes/Amortization.php';
require_once 'classes/Payment.php';
require_once 'classes/Promoter.php';
require_once 'classes/GlobalGroup.php';
require_once 'classes/Member.php';


class processPaymentsOnAmortizationTest extends TestCase
{
    public function testProcessPaymentsOnAmortization_SuccessfulPayment()
    {
        $globalGroup = new GlobalGroup(1, 'test', []);
        $member1 = new Member(1, 'user1@gmail.com');
        $member2 = new Member(2, 'user2@gmail.com');

        $globalGroup->addMember($member1);
        $globalGroup->addMember($member2);

        $amortization = new Amortization(1, 1, 500.0, '2023-12-15', 'pending');
        $payment1 = new Payment(1, $amortization->id, 300.0, 1, 'pending');
        $payment2 = new Payment(2, $amortization->id, 200.0, 1, 'pending');
        $amortization->payments = [$payment1, $payment2];

        $PROJECT = Project::find($amortization->project_id);
        $PROMOTER = Promoter::find($PROJECT->promoter_id);

        $PROJECT->balance;

        $givenDate = '2023-12-16';
        $mailer = new PHPMailer(true);

        $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

        $this->assertEquals("Payments processed successfully", $result);
    }

    public function testProcessPaymentsOnAmortization_UnsuccessfulPaymentBeacuseOfPayment()
    {
        $globalGroup = new GlobalGroup(1, 'test', []);
        $member1 = new Member(1, 'user1@gmail.com');
        $member2 = new Member(2, 'user2@gmail.com');

        $globalGroup->addMember($member1);
        $globalGroup->addMember($member2);

        $amortization = new Amortization(1, 1, 500.0, '2023-12-15', 'pending');
        $payment1 = new Payment(1, $amortization->id, 200.0, 1, 'pending');
        $payment2 = new Payment(2, $amortization->id, 200.0, 1, 'pending');
        $amortization->payments = [$payment1, $payment2];

        $PROJECT = Project::find($amortization->project_id);
        $PROMOTER = Promoter::find($PROJECT->promoter_id);

        $PROJECT->balance;

        $givenDate = '2023-12-16';
        $mailer = new PHPMailer(true);

        $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

        $this->assertEquals("Payments not processed successfully", $result);
    }

    public function testProcessPaymentsOnAmortization_UnsuccessfulPaymentBeacuseOfDate()
    {
        $globalGroup = new GlobalGroup(1, 'test', []);
        $member1 = new Member(1, 'user1@gmail.com');
        $member2 = new Member(2, 'user2@gmail.com');

        $globalGroup->addMember($member1);
        $globalGroup->addMember($member2);

        $amortization = new Amortization(1, 1, 500.0, '2023-12-15', 'pending');
        $payment1 = new Payment(1, $amortization->id, 200.0, 1, 'pending');
        $payment2 = new Payment(2, $amortization->id, 200.0, 1, 'pending');
        $amortization->payments = [$payment1, $payment2];

        $PROJECT = Project::find($amortization->project_id);
        $PROMOTER = Promoter::find($PROJECT->promoter_id);

        $PROJECT->balance;

        $givenDate = '2023-12-14';
        $mailer = new PHPMailer(true);

        $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

    
        $this->assertEquals("Payments not processed successfully", $result);
    }

    public function testProcessPaymentsOnAmortization_UnsuccessfulPaymentZeroPayments()
    {
        $globalGroup = new GlobalGroup(1, 'test', []);
        $member1 = new Member(1, 'user1@gmail.com');
        $member2 = new Member(2, 'user2@gmail.com');

        $globalGroup->addMember($member1);
        $globalGroup->addMember($member2);

        $amortization = new Amortization(1, 1, 500.0, '2023-12-15', 'pending');

    
        $PROJECT = Project::find($amortization->project_id);
        $PROMOTER = Promoter::find($PROJECT->promoter_id);

        $PROJECT->balance;

        $givenDate = '2023-12-16';
        $mailer = new PHPMailer(true);

        $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);


        $this->assertEquals("No payments", $result);
    }

    public function testTotalpayment()
    {
        $amortization = new Amortization(1, 1, 500.0, '2023-12-15', 'pending');
        $payment1 = new Payment(1, $amortization->id, 200.0, 1, 'pending');
        $payment2 = new Payment(2, $amortization->id, 200.0, 1, 'pending');
        $amortization->payments = [$payment1, $payment2];

        $result = $amortization->totalPayments();
        $this->assertEquals($result, 400.0);

    }


    // MORE TESTS PLEASE
}
