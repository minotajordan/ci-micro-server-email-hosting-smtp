<?php
namespace App\Libraries;
use CodeIgniter\Libraries; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailer_Lib {

    public function load(){    
        $mail = new PHPMailer;
        return $mail;
    }
}