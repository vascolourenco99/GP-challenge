<?php
/* 
  This file is used to create instances of classes 
  to set up a "production environment" for testing 
  the projectAmortizationOptimize(line 53) function.
*/



require_once 'classes/Project.php';
require_once 'classes/Amortization.php';
require_once 'classes/Payment.php';
require_once 'classes/Promoter.php';
require_once 'classes/GlobalGroup.php';
require_once 'classes/Member.php';

use PHPMailer\PHPMailer\PHPMailer;

$givenDate = '2023-12-16'; 
$mailer = new PHPMailer(true);

$globalGroup = new GlobalGroup(1, 'Project Dummy Global Group', []);
$member1 = new Member(1, 'user1@gmail.com');
$member2 = new Member(2, 'user2@gmail.com');
$globalGroup->addMember($member1);
$globalGroup->addMember($member2);

$amortization = new Amortization(1, 1, 500.0, '2023-12-15', 'pending'); 
$payment1 = new Payment(1, $amortization->id, 300.0, 1, 'pending'); 
$payment2 = new Payment(2, $amortization->id, 200.0, 1, 'pending'); 
$amortization->payments = [$payment1, $payment2];


// Associate amortizations with projects
$PROJECT = Project::find($amortization->project_id);
$PROMOTER = Promoter::find($PROJECT->promoter_id);
$PROJECT->addAmortization($amortization);

/*
  I created this for loop to manipulate the quantity of amortizations 
  that a project can have, in order to rigorously test 
  the processing of the projectAmortizationOptimize function.
*/
for ($i = 0; $i < 20; $i++) {
    $amortization = new Amortization($i + 2, 1, 500.0 + ($i * $i), '2023-12-15', 'pending'); 
    $payment1 = new Payment($i + 1, $amortization->id, 300.0, 1, 'pending'); 
    $payment2 = new Payment($i + 2, $amortization->id, 200.0, 1, 'pending'); 
    $amortization->payments = [$payment1, $payment2];
    
    $PROJECT->addAmortization($amortization);
}

$result = Project::projectAmortizationOptimize($PROJECT->amortizations, $givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

foreach ($result as $result) {
    echo $result . '<br/>';
}

