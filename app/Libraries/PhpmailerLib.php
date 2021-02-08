<?php
namespace App\Libraries;
use CodeIgniter\Libraries; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PhpmailerLib {

    public function load(){    
        $mail = new PHPMailer;
        return $mail;
    }
}