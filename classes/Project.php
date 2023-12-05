<?php

/**
 * Class Project
 *
 * Represents a project with properties such as id, promoter_id, balance, name, global_group_id, and an array of amortizations.
 */
include_once 'classes/Amortization.php';

class Project
{
    public $id;
    public $promoter_id;
    public $balance;
    public $name;
    public $global_group_id;
    public $amortizations = [];

     /**
     * Project constructor.
     *
     * @param int    $id
     * @param int    $promoter_id
     * @param float  $balance
     * @param string $name
     * @param int    $global_group_id
     * @param array  $amortizations
     */

    public function __construct($id, $promoter_id, $balance, $name, $global_group_id, $amortizations = [])
    {
        $this->id = $id;
        $this->promoter_id = $promoter_id;
        $this->balance = $balance;
        $this->name = $name;
        $this->global_group_id = $global_group_id;
        $this->amortizations = $amortizations;
    }

    /**
     * Static method to find a project by its ID.
     *
     * @param int $projectId
     *
     * @return Project
     * Ideally, this function should call an API endpoint or fetch records from a database.
     */

    public static function find($projectId) : Project
    {
        /*
            This function serves to return a project. 
            However, I want to emphasize that this function 
            should ideally call an API endpoint 
            or fetch records from a database.
        */
        return new Project(1, 1, 1000.0, 'Dummy Project', 1, []);
    }

    /**
     * Get amortization by its ID.
     *
     * @param int $amortizationId
     *
     * @return Amortization|null
     */

    public function getAmortizationById($amortizationId) : ?Amortization
    {
        foreach ($this->amortizations as $amortization) {
            if ($amortization->id === $amortizationId) {
                return $amortization;
            }
        }
        return null;
    }

    /**
     * Add payment amount to project balance if the associated amortization is pending.
     *
     * @param Payment $payment
     */

    public function addPaymenToBalance(Payment $payment) : void
    {
        // Check if the associated amortization is pending before adding the payment to the balance
        $amortization = $this->getAmortizationById($payment->amortization_id);

        if ($amortization && $amortization->state === 'pending') {
            $this->balance += $payment->amount;
        }
    }

    /**
     * Add a new amortization to the project.
     *
     * @param Amortization $amortization
     */

    public function addAmortization($amortization)
    {
        $this->amortizations[] = $amortization;
    }

    /**
     * Process payments on multiple amortizations, optimizing performance by dividing the array into smaller chunks.
     * To process large quantities of amortizations, 
     * I found that dividing the array into multiple segments could improve performance and memory management. 
     * To achieve this result, I start by deciding a size to segment the array (smaller is better for a lot of data), 
     * the total number of amortizations, and an array to store the results. 
     * I used a for loop to divide the array with the parameters I previously defined, 
     * aiming to avoid overloading the processPaymentsOnAmortization function.
     *
     * @param array      $amortizations
     * @param string     $givenDate
     * @param PHPMailer  $mailer
     * @param Project    $PROJECT
     * @param Promoter   $PROMOTER
     * @param GlobalGroup $globalGroup
     *
     * @return array
     */

    public static function projectAmortizationOptimize($amortizations, $givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup) : array
    {
        $chunkSize = 10; // Adjust the chunk size as needed (smaller is better for a lot of data);
        $totalAmortizations = count($amortizations);
        $results = [];

        for ($offset = 0; $offset < $totalAmortizations; $offset += $chunkSize) {
            $partOfAmportizationsArray = array_slice($amortizations, $offset, $chunkSize);

            foreach ($partOfAmportizationsArray as $amortization) {
                $result = $amortization->processPaymentsOnAmortization($givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup);

                $results[] = $result;
            }
        }

        return $results;
    }
}
