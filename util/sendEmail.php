<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * sendEmail function sends an email using PHPMailer.
 *
 * @param PHPMailer $mailer The PHPMailer instance.
 * @param string $recipientEmail The email address of the recipient.
 * @param string $projectName The name of the project for which the email is sent.
 * @param string $reasons A string containing reasons for the email notification.
 */
function sendEmail(PHPMailer $mailer, $recipientEmail, $projectName, $reasons)
{
    $mailer->SMTPDebug = SMTP::DEBUG_SERVER;
    $mailer->isSMTP();
    $mailer->Host = 'smtp.gmail.com';
    $mailer->SMTPAuth = true;
    $mailer->Username = 'youremail@gmail.com'; // <- this line
    $mailer->Password = 'password'; // <- this line
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mailer->Port = 465;

    $mailer->setFrom('youremail@gmail.com', 'Mailer');
    $mailer->addAddress($recipientEmail);

    $mailer->isHTML(true);
    $mailer->Subject = 'Amortization Notification';
    $mailer->Body = "Amortization not processed for project $projectName. Reasons: $reasons";

    $mailer->send();
}

/**
 * sendEmailToGroupMembers function sends an email notification to all members of a group.
 *
 * @param PHPMailer $mailer The PHPMailer instance.
 * @param object $globalGroup The global group object containing members.
 * @param string $projectName The name of the project for which the email is sent.
 * @param string $reasonsString A string containing reasons for the email notification.
 */
function sendEmailToGroupMembers(PHPMailer $mailer, $globalGroup, $projectName, $reasonsString)
{
    $members = $globalGroup->members;

    foreach ($members as $member) {
        sendEmail($mailer, $member->email, $projectName, $reasonsString);
    }
}