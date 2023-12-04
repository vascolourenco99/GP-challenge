<?php

require_once 'classes/Project.php';
require_once 'classes/Amortization.php';
require_once 'classes/Payment.php';
require_once 'classes/Promoter.php';
require_once 'classes/GlobalGroup.php';
require_once 'classes/Member.php';

use PHPMailer\PHPMailer\PHPMailer;

// Create dummy data
$globalGroup = new GlobalGroup(1, 'Project Dummy Global Group', []);
$member1 = new Member(1, 'user1@gmail.com');
$member2 = new Member(2, 'user2@gmail.com');

$globalGroup->addMember($member1);
$globalGroup->addMember($member2);

$amortization = [];

for ($i = 0; $i < 1000; $i++) {
    $amortization = new Amortization($i + 1, 1, 500.0 + ($i * $i), '2023-12-15', 'pending'); 
    $payment1 = new Payment($i + 2, $amortization->id, 300.0, 1, 'pending'); 
    $payment2 = new Payment($i + 3, $amortization->id, 200.0, 1, 'pending'); 
    $amortization->payments = [$payment1, $payment2];

    $amortizations[] = $amortization;
}

foreach ($amortizations as $amortization) {
    echo $amortization->amount . "</br>";
}


// Associate payments with the amortization
$PROJECT = Project::find($amortization->project_id);
$PROMOTER = Promoter::find($PROJECT->promoter_id);

$PROJECT->balance . "</br>";

$givenDate = '2023-12-16'; 
$mailer = new PHPMailer(true);


// melhorar esta logica

$chunkSize = 10; // Adjust the chunk size as needed
$totalAmortizations = count($amortizations);

for ($offset = 0; $offset < $totalAmortizations; $offset += $chunkSize) {
    $chunk = array_slice($amortizations, $offset, $chunkSize);

    foreach ($chunk as $amortization) {
        // Process each amortization
        $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

        // Handle the result if needed
        // For example, log success or failure
        echo $result . "\n";
    }
}