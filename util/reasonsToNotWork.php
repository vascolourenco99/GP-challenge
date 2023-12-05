<?php

function reasonsToNotWork($amortizationDate, $givenDate, $PROJECT, $amount)
{
    $reasons = [];

    if ($amortizationDate !== $givenDate->format('Y-m-d')) {
        $reasons[] = "Date mismatch (Amortization date: $amortizationDate, Given date: {$givenDate->format('Y-m-d')})";
    }


    if ($PROJECT->balance < $amount) {
        $reasons[] = "Insufficient balance in the project";
    }

    return $reasons;
}
