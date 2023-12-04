<?php

class Payment
{
    public $id;
    public $amortization_id;
    public $amount;
    public $global_group_id;
    public $state;

    public function __construct($id, $amortization_id, $amount, $global_group_id, $state)
    {
        $this->id = $id;
        $this->amortization_id = $amortization_id;
        $this->amount = $amount;
        $this->global_group_id = $global_group_id;
        $this->state = $state;
    }
}