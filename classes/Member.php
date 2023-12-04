<?php

class Member
{
    public $id;
    public $email;

    public function __construct($id, $email)
    {
        $this->id = $id; 
        $this->email = $email;
    }
}