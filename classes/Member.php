<?php

/**
 * Class Member
 *
 * Represents a member with an id and an email address.
 */
class Member
{
    public $id;
    public $email;

    /**
     * Member constructor.
     *
     * @param int    $id
     * @param string $email
     */
    public function __construct($id, $email)
    {
        $this->id = $id;
        $this->email = $email;
    }
}