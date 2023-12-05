<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function sendEmail(PHPMailer $mailer, $recipientEmail, $projectName, $reasons)
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


function sendEmailToGroupMembers(PHPMailer $mailer, $globalGroup, $projectName,  $reasonsString)
{
    $members = $globalGroup->members;

    foreach ($members as $member) {
        sendEmail($mailer, $member->email, $projectName, $reasonsString);
    }
}
