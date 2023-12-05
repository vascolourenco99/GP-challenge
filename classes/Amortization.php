<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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

        if ($amortizationDate == $givenDate->format('Y-m-d') && $this->state === 'pending' && $this->totalPayments() == $this->amount && $PROJECT->balance >= $this->amount) {
            $totalAmount = $this->amount;
            $PROJECT->balance -= $totalAmount;
            $this->state = 'paid';

            return "Amortization ID:{$this->id} payments processed successfully";
        }

        if ($givenDate < $this->schedule_date && $this->state === 'pending') {
            return "Amortization ID:{$this->id} has not been paid yet because the date is not today";
        }

        try {

            $reasons = $this->reasonsToNotWork($amortizationDate, $givenDate, $PROJECT);
            $reasonsString = implode(', ', $reasons);
            $this->sendEmail($mailer, $PROMOTER->email, $PROJECT->name, $reasonsString);  // <- comment this to run the test without sending emails
            $this->sendEmailToGroupMembers($mailer, $globalGroup, $PROJECT->name,  $reasonsString); // <- comment this to run the test without sending emails
        } catch (Exception $e) {
            return "Error sending email: {$e->getMessage()}";
        }
    }

    private function reasonsToNotWork($amortizationDate, $givenDate, $PROJECT)
    {
        $reasons = [];

        if ($amortizationDate !== $givenDate->format('Y-m-d')) {
            $reasons[] = "Date mismatch (Amortization date: $amortizationDate, Given date: {$givenDate->format('Y-m-d')})";
        }


        if ($PROJECT->balance < $this->amount) {
            $reasons[] = "Insufficient balance in the project";
        }

        return $reasons;
    }

    private function sendEmail(PHPMailer $mailer, $recipientEmail, $projectName, $reasons)
    {
        $mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'YOUREMAIL@gmail';
        $mailer->Password = 'PASSWORD';
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mailer->Port = 465;

        $mailer->setFrom('youremail@gmail.com', 'Mailer');
        $mailer->addAddress($recipientEmail);

        $mailer->isHTML(true);
        $mailer->Subject = 'Amortization Notification';
        $mailer->Body = "Amortization not processed for project $projectName. Reasons: $reasons";

        $mailer->send();
    }

    private function sendEmailToGroupMembers(PHPMailer $mailer, $globalGroup, $projectName,  $reasonsString)
    {
        $members = $globalGroup->members;

        foreach ($members as $member) {
            $this->sendEmail($mailer, $member->email, $projectName, $reasonsString);
        }
    }
}
