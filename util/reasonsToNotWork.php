<?php

/**
 * reasonsToNotWork function checks for reasons why a transaction may not be processed.
 *
 * @param string $amortizationDate The expected amortization date.
 * @param DateTime $givenDate The date provided for the transaction.
 * @param object $PROJECT The project object containing balance information.
 * @param float $amount The transaction amount.
 *
 * @return array 
 */
function reasonsToNotWork($amortizationDate, $givenDate, $PROJECT, $amount) : array
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