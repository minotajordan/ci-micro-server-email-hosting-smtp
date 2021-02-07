<?php namespace App\Models;
// LibMin
use CodeIgniter\Model;
$db = \Config\Database::connect();
class SystemModel extends Model {
    protected $table = 'user';
    function getTableMain () { return getenv('database'); }

    function getSchema ($table, $relation = NULL) {
        
        $tables_relation = '';
        if ($relation != NULL) {
            foreach ($relation as $row) {
                $tables_relation .= " or TABLE_NAME = '".$row['table']."'";
            }
        }
        $query = $this->db->query("select * from INFORMATION_SCHEMA.COLUMNS where table_schema = '".$this->getTableMain()."' and TABLE_NAME='$table' ".$tables_relation);
        return $query;
    }

    function getTablet ($table, $id = NULL, $ralation = NULL){
        $inner = '';
        if ($ralation != NULL){
            foreach ($ralation as $row) {
                $inner .= ' inner join '.$row['table'].' on '.$table.'.'.$row['field_relation'].' = '.$row['table'].'.'.$row['field_join'];
            }
        }
        $query = '';
        if ($id == NULL) {
            $query = $this->db->query('SELECT * FROM '.$table.' '.$inner);
        } else {
            $query = $this->db->query('SELECT * FROM '.$table.' '.$inner.' WHERE id_'.$table.' = '.$id);
        }
        
        return $query;
    }

    function getValidateToken($token_public, $email){
        $query = $this->db->query('SELECT * FROM afiliate WHERE token_public = "'.$token_public.'" AND config_email = "'.$email.'"');
        return $query;
    }

    function getStructure ($table = ''){
		$query = $this->db->query("select * from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='".$this->getTableMain()."' and TABLE_NAME='$table'");
        $data = $query->getResultArray();
		if ($query) return ($data);
        return false;
    }

    function setTablet ($table = '', $data){
        $builder = $this->db->table($table);
        $builder->insert($data);
        return $this->db->insertID();
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