<?php

include_once 'classes/Amortization.php';

class Project
{
    public $id;
    public $promoter_id;
    public $balance;
    public $name;
    public $global_group_id;
    public $amortizations = [];

    public function __construct($id, $promoter_id, $balance, $name, $global_group_id, $amortizations = [])
    {
        $this->id = $id;
        $this->promoter_id = $promoter_id;
        $this->balance = $balance;
        $this->name = $name;
        $this->global_group_id = $global_group_id;
        $this->amortizations = $amortizations;
    }

    public static function find($projectId)
    {
        /*
            This function serves to return a project. 
            However, I want to emphasize that this function 
            should ideally call an API endpoint 
            or fetch records from a database.
        */
        return new Project(1, 1, 1000.0, 'Dummy Project', 1, []);
    }

    public function addAmortization($amortization)
    {
        $this->amortizations[] = $amortization;
    }

    /* 
        To process large quantities of amortizations, 
        I found that dividing the array into multiple segments could improve performance and memory management. 
        To achieve this result, I start by deciding a size to segment the array (smaller is better for a lot of data), 
        the total number of amortizations, and an array to store the results. 
        
        I used a for loop to divide the array with the parameters I previously defined, 
        aiming to avoid overloading the processPaymentsOnAmortization function.
    */

    public static function projectAmortizationOptimize($amortizations, $givenDate, $mailer, $PROJECT, $PROMOTER, $globalGroup)
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
