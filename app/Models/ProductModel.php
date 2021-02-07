<?php namespace App\Models;
// LibMin
use CodeIgniter\Model;
$db = \Config\Database::connect();

class ProductModel extends Model {

    protected $table = 'product';

    function getProduct ($id = 1){
        $query = '';
        if ($id == 1) {
            $query = $this->db->query('SELECT * FROM product INNER JOIN category on category.id_category = product.id_category');
        } else if ($id != NULL) {
            $query = $this->db->query('SELECT * FROM product INNER JOIN category on category.id_category = product.id_category WHERE id_product = '.$id);
        }
        return $query->getResultArray();
    }

    function getProductArray (){
        $array = '""';
        $query = $this->db->query("SELECT * FROM product");
        foreach ($query->getResultArray() as $column) {
            $array .= ',"'.$column['product'].'"';
        }
        return $array;
    }

    function getProductCategories (){
        $query = $this->db->query('select * from category');
        return $query->getResultArray();
    }

    function getIdCategories ($categories){
        $reult = '';
        $query = $this->db->query('select * from category where category = "'.$categories.'"');
        foreach ($query->getResultArray() as $row) {
            $reult .= $row['id_category'];
        }
        return $reult;
    }

    function getProductLike ($category = 'all', $search = '', $order = ''){;
        if ($search == 'all') $search = ''; 
        $query = '';
        if($category === 'all') {
            $query = $this->db->query("SELECT * FROM product inner join category on category.id_category = product.id_category WHERE description_short LIKE '%$search%' or product LIKE '%$search%' group by id_product");
        }else if ($category !== 'all') {
            $query = $this->db->query("SELECT * FROM product inner join category on category.id_category = product.id_category WHERE product.id_category LIKE '%".$this->getIdCategories($category)."%' group by id_product");
        }
        return $query->getResultArray();
    }

    function getDetailProduct ($title){
        $query = $this->db->query('SELECT * FROM product INNER JOIN category on category.id_category = product.id_category WHERE product = "'.str_replace("-", " ", $title).'" limit 1');
        return $query->getResultArray();
    }

    function getProductTop (){
        $query = $this->db->query('SELECT * FROM product INNER JOIN category on category.id_category = product.id_category WHERE top = 1');
        return $query->getResultArray();
    }

    function getProductBest (){
        $query = $this->db->query('SELECT * FROM product INNER JOIN category on category.id_category = product.id_category WHERE top_best = 1');
        return $query->getResultArray();
    }

    function setTablet ($table = '', $data){
        $builder = $this->db->table($table);
        return $builder->insert($data);
    }

    function updateTable ($table = '', $data, $id){
        $builder = $this->db->table($table);
        $builder->where('id_'.$table, $id);
        return $builder->update($data);
    }

    function removeTable ($table = '', $id){
        $builder = $this->db->table($table);
        $builder->where('id_'.$table, $id);
        return $builder->delete();
    }
}