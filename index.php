<?php
/* 
  This file is used to create instances of classes 
  to set up a "production environment" for testing 
  the projectAmortizationOptimize(line 66) function.
*/


require_once 'classes/Project.php';
require_once 'classes/Amortization.php';
require_once 'classes/Payment.php';
require_once 'classes/Promoter.php';
require_once 'classes/GlobalGroup.php';
require_once 'classes/Member.php';

use PHPMailer\PHPMailer\PHPMailer;


$mailer = new PHPMailer(true);

// Set a fixed date for testing the projectAmortizationOptimize function.
$givenDate = new DateTime('2023-12-06'); 

// Create a global group for testing purposes.
$globalGroup = new GlobalGroup(1, 'Project Dummy Global Group', []);
$member1 = new Member(1, 'user1@gmail.com'); // <- this line 
$member2 = new Member(2, 'user2@gmail.com'); // <- this line
$globalGroup->addMember($member1);
$globalGroup->addMember($member2);

// Create an amortization with associated payments.
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

/*
  I created this for loop to manipulate the quantity of amortizations 
  that a project can have, in order to rigorously test 
  the processing of the projectAmortizationOptimize function.
*/
for ($i = 1; $i < 2; $i++) {
  $amortization = new Amortization($i + 2, 1, 500.0 + ($i * $i), new DateTime('2023-12-05'), 'pending'); 
  $payment1 = new Payment($i + 1, $amortization->project_id, $amortization->id, 300.0, 1, 'pending'); 
  $payment2 = new Payment($i + 2, $amortization->project_id, $amortization->id, 200.0, 1, 'pending'); 
  $amortization->payments = [$payment1, $payment2];
  $PROJECT->addAmortization($amortization);
}

for ($i = 1; $i < 2; $i++) {
  $amortization = new Amortization($i + 2, 1, 500.0 + ($i * $i), new DateTime('2023-12-07'), 'pending'); 
  $payment1 = new Payment($i + 1, $amortization->project_id, $amortization->id, 300.0, 1, 'pending'); 
  $payment2 = new Payment($i + 2, $amortization->project_id, $amortization->id, 200.0, 1, 'pending'); 
  $amortization->payments = [$payment1, $payment2];
  $PROJECT->addAmortization($amortization);
}

// Call the projectAmortizationOptimize
$result = Project::projectAmortizationOptimize($PROJECT->amortizations, $givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

// Display the results of the optimization process.
foreach ($result as $result) {
  echo $result . '<br/>';
}
