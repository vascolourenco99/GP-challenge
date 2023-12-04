<?php 
class Promoter 
{
    public $id;
    public $name;
    public $email;

    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name; 
        $this->email = $email;
    }
    
    public static function find($promoterId)
    {
        /*
            This function serves to return a promoter. 
            However, I want to emphasize that this function 
            should ideally call an API endpoint 
            or fetch records from a database.
        */

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