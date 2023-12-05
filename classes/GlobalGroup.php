<?php
include_once "classes/Member.php";

/**
 * Class GlobalGroup
 *
 * Represents a global group with an id, name, and a collection of members.
 */
class GlobalGroup
{
    public $id;
    public $name;
    public $members;

    /**
     * GlobalGroup constructor.
     *
     * @param int      $id
     * @param string   $name
     * @param Member[] $members
     */
    public function __construct($id, $name, $members)
    {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
    }

    /**
     * Adds a member to the global group.
     *
     * @param Member $member
     */
    public function addMember(Member $member)
    {
        $this->members[] = $member;
    }
}