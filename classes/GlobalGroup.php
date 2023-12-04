<?php
include_once "classes/Member.php";

class GlobalGroup
{
    public $id;
    public $name;
    public $members;

    public function __construct($id, $name, $members)
    {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
    }

    public function addMember(Member $member)
    {
        $this->members[] = $member;
    }
}