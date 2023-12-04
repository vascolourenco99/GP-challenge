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
    
    public function processPaymentsOnAmortization($givenDate, PHPMailer $mailer, $PROJECT, $PROMOTER, $globalGroup)
    {
        if (empty($this->payments)) {
            return "No payments";
        }
        

        if ($this->schedule_date <= $givenDate && $this->state === 'pending' && $this->totalPayments() == $this->amount && $PROJECT->balance >= $this->amount) {
            $totalAmount = $this->amount;
            $PROJECT->balance -= $totalAmount;
            $this->state = 'paid';
            
            return "Payments processed successfully";
        } else {
            try {
                // $this->sendEmail($mailer, $PROMOTER->email, $PROJECT->name);
                // $this->sendEmailToGroupMembers($mailer, $globalGroup, $PROJECT->name);

                return "Payments not processed successfully";
            } catch (Exception $e) {
                return "Error sending email: {$e->getMessage()}";
            }
        }
    }

    private function sendEmail(PHPMailer $mailer, $recipientEmail, $projectName)
    {
        $mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'vascocorreia99@gmail.com';
        $mailer->Password = 'pwevlfyzvseqebui';
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mailer->Port = 465;

        $mailer->setFrom('vascocorreia99@gmail.com', 'Mailer');
        $mailer->addAddress($recipientEmail);

        $mailer->isHTML(true);
        $mailer->Subject = 'Amortization Notification';
        $mailer->Body = 'Amortization not processed for project ' . $projectName;

        $mailer->send();
    }

    private function sendEmailToGroupMembers(PHPMailer $mailer, $globalGroup, $projectName)
    {
        $members = $globalGroup->members;

        foreach ($members as $member) {
            $this->sendEmail($mailer, $member->email, $projectName);
        }
    }
}
