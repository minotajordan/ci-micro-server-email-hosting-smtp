<?php namespace App\Models;
// LibMin
use CodeIgniter\Model;
$db = \Config\Database::connect();

class ConfigurationModel extends Model {

    protected $table = 'service';
    function getTableMain () { return getenv('database'); }

    function getConfigurationGeneral () {
        $data = array();
        $query_schema = $this->db->query("select * from INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='".$this->getTableMain()."' and TABLE_NAME='configuration'");
        $query = $this->db->query('SELECT * FROM configuration where id_configuration = 1');
        foreach ($query_schema->getResultArray() as $schema) {
            foreach ($query->getResultArray() as $row) { $data[$schema['COLUMN_NAME']] = $row[$schema['COLUMN_NAME']]; }
        }
        return $data;
    }

    function getMessageTop () {
        $query = $this->db->query('SELECT * FROM card_home');
        return $query->getResultArray();
    }

    function getConfigurationSlider () {
        $query = $this->db->query('SELECT * FROM slider');
        return $query->getResultArray();
    }

    function getConfigurationFooter () {
        $query = $this->db->query('SELECT * FROM configuration where id_configuration = 1');
        return $query->getResultArray();
    }

    function getConfigurationContac () {
        $query = $this->db->query('SELECT * FROM configuration where id_configuration = 1');
        return $query->getResultArray();
    }
}