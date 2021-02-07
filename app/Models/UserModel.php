<?php namespace App\Models;
// LibMin
use CodeIgniter\Model;
$db = \Config\Database::connect();

class UserModel extends Model {

    protected $table = 'user';

    function checkEmail ($email){
        if($email != NULL or $email != ''){
            $query = $this->db->query('select * from user where email = "'.$email.'"');
            foreach ($query->getResultArray() as $row) {
                if ($row['email'] === $email){
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    function checkLogin ($email, $password){
        if($email != NULL or $email != ''){
            $query = $this->db->query('select * from user where email = "'.$email.'" and password = "'.$password.'" limit 1');
            foreach ($query->getResultArray() as $row) {
                if ($row['email'] === $email){
                    if ($row['password'] === $password){
                        $data['response'] = true;
                        $data['data'] = $query->getResultArray();
                        return $data;
                    }
                    return false;
                }
            }
        } else {
            return false;
        }
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