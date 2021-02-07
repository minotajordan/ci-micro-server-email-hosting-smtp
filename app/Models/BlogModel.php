<?php namespace App\Models;
// LibMin
use CodeIgniter\Model;
$db = \Config\Database::connect();

class BlogModel extends Model {

    protected $table = 'blog';

    function getBlog($title = false) {
        if ($title){
            $query = $this->db->query('SELECT * FROM blog where title = "'.$title.'"');
            return $query->getResultArray();
        } else {
            $query = $this->db->query('SELECT * FROM blog');
            return $query->getResultArray();
        }

    }
}