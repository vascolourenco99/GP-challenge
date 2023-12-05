<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once __DIR__ . '/../util/sendEmail.php';
include_once __DIR__ . '/../util/reasonsToNotWork.php';

require 'vendor/autoload.php';


class Amortization
{
    public $id;
    public $project_id;
    public $amount;
    public $schedule_date;
    public $state;
    public $payments = [];

    public function __construct($id, $project_id, $amount, $schedule_date, $state)
    {
        $this->id = $id;
        $this->project_id = $project_id;
        $this->amount = $amount;
        $this->schedule_date = $schedule_date;
        $this->state = $state;
    }


    public function totalPayments()
    {
        $total = 0;

        foreach ($this->payments as $payment) {
            $total += $payment->amount;
        }

        return $total;
    }

    /*
        This function is responsible for overall management 
        to check if the amortizations have been paid. 
        If they haven't, an email notification is sent 
        to both the promoter and the project members.
    */
    public function processPaymentsOnAmortization($givenDate, PHPMailer $mailer, $PROJECT, $PROMOTER, $globalGroup)
    {
        if (empty($this->payments)) {
            return "No payments for amortization {$this->id}";
        }

        $amortizationDate = $this->schedule_date->format('Y-m-d');

        if ($this->ProcessPayments($givenDate, $PROJECT)) {
            $totalAmount = $this->amount;
            $PROJECT->balance -= $totalAmount;
            $this->state = 'paid';

            return "Amortization ID:{$this->id} payments processed successfully";
        }

        if ($this->shouldNotBePaidYet($givenDate)) {
            return "Amortization ID:{$this->id} has not been paid yet because the date is not today";
        }

        try {

            $reasons = reasonsToNotWork($amortizationDate, $givenDate, $PROJECT, $this->amount);
            $reasonsString = implode(', ', $reasons);
            sendEmail($mailer, $PROMOTER->email, $PROJECT->name, $reasonsString);  // <- comment this to run the test without sending emails
            sendEmailToGroupMembers($mailer, $globalGroup, $PROJECT->name,  $reasonsString); // <- comment this to run the test without sending emails
        } catch (Exception $e) {
            return "Error sending email: {$e->getMessage()}";
        }
    }

    private function ProcessPayments($givenDate, $PROJECT)
    {
        $amortizationDate = $this->schedule_date->format('Y-m-d');

        return (
            $amortizationDate == $givenDate->format('Y-m-d') &&
            $this->state === 'pending' &&
            $this->totalPayments() == $this->amount &&
            $PROJECT->balance >= $this->amount
        );
    }

    private function shouldNotBePaidYet($givenDate)
    {
        return $givenDate < $this->schedule_date && $this->state === 'pending';
    }
}
