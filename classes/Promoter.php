<?php

/**
 * Class Promoter
 *
 * Represents a promoter with properties such as `id`, `name`, and `email`.
 */
class Promoter 
{
    public $id;
    public $name;
    public $email;

    /**
     * Promoter constructor.
     *
     * @param int    $id
     * @param string $name
     * @param string $email
     */
    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name; 
        $this->email = $email;
    }
    
    /**
     * Static method to find a promoter by their ID.
     *
     * @param int $promoterId
     *
     * @return Promoter|null
     * Ideally, this function should call an API endpoint or fetch records from a database.
     */
    public static function find($promoterId) : ?Promoter
    {
        $promoters = [
            1 => new Promoter(1, 'user', 'user@gmail.com'),
        ];

        if (isset($promoters[$promoterId])) {
            return $promoters[$promoterId];
        } else {
            return null;
        }
    }
}