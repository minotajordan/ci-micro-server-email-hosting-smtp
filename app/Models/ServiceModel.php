<?php namespace App\Models;
// LibMin
use CodeIgniter\Model;
$db = \Config\Database::connect();

class ServiceModel extends Model {

    protected $table = 'service';

    function getService () {
        $query = $this->db->query('SELECT * FROM service');
        return $query->getResultArray();
    }

    function getExistEmail ($email = ''){
        $query = $this->db->query('SELECT * FROM user where email = "'.$email.'"');
        foreach ($query->getResultArray() as $row) {
            return true;
        }
        return false;
    }

    function sendEmailContact($data){
        echo implode($data);
        $to = "cefiso5357@glenwoodave.com";
        $body = '<h3>'.$data['subject'].'<h3>
                 <p>Mi nombre es: '.$data['name'].'<p>
                 <p>Mi telefono es: '.$data['tel'].'<p>
                 <p>Mi correo es: '.$data['email'].'<p>
                 <hr>
                 <p>'.$data['message'].'<p>
                 <br>
                 <span>Fecha de registro: '.date('Y-m-d H:i:s').'<span>';
        return mail($to, 'Cliente interesado: '.$data['name'], $body);
    }
}