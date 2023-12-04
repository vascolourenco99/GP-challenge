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