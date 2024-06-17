<?php

require_once("Model/Data/IBlogDAO.php");
require_once("Model/Data/IDatabase.php");
require_once("Model/Data/Database.php");

class BlogDAO implements IBlogDAO{

    private IDatabase $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllBlogs():array{
        $sql = "select * from blogs order by blog_date desc limit 6;";
        return $this->db->executeQuery($sql);
    }
}