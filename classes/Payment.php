<?php

/**
 * Class Payment
 *
 * Represents a payment associated with a project's amortization, belonging to a global group.
 */
class Payment
{
    public $id;
    public $project_id;
    public $amortization_id;
    public $amount;
    public $global_group_id;
    public $state;

    /**
     * Payment constructor.
     *
     * @param int    $id
     * @param int    $project_id
     * @param int    $amortization_id
     * @param float  $amount
     * @param int    $global_group_id
     * @param string $state
     */
    public function __construct($id, $project_id, $amortization_id, $amount, $global_group_id, $state)
    {
        // Ensure that the amount is non-negative
        $this->id = $id;
        $this->project_id = $project_id;
        $this->amortization_id = $amortization_id;
        $this->amount = $amount < 0 ? 0 : $amount;
        $this->global_group_id = $global_group_id;
        $this->state = $state;
    }
}