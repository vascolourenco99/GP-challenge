<?php

class Project
{
    public $id;
    public $promoter_id;
    public $balance;
    public $name;
    public $global_group_id;

    public function __construct($id, $promoter_id, $balance, $name, $global_group_id)
    {
        $this->id = $id;
        $this->promoter_id = $promoter_id;
        $this->balance = $balance;
        $this->name = $name;
        $this->global_group_id = $global_group_id;
    }
    
    public static function find($projectId)
    {
        // Here shoud be an API call to get the project information
        return new Project(1, 1, 1000.0, 'Dummy Project', 1);
    }
}