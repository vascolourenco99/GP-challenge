<?php

class Payment
{
    public $id;
    public $project_id;
    public $amortization_id;
    public $amount;
    public $global_group_id;
    public $state;

    public function __construct($id, $project_id, $amortization_id, $amount, $global_group_id, $state)
    {
        $this->id = $id;
        $this->project_id = $project_id;
        $this->amortization_id = $amortization_id;
        $this->amount = $amount < 0 ? 0 : $amount;
        $this->global_group_id = $global_group_id;
        $this->state = $state;
    }
}