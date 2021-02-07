<?php

namespace App\Controllers;

use \App\Models\SystemModel;
use App\Libraries\PHPMailer_Lib;
use App\Helpers\Login_Helper;
use \Config\Services;
use \Config\Encryption;
use \Config\Paths;

class Home extends BaseController
{
  protected $PHPMailer;
  protected $libmin;
  protected $request;
  protected $helpers = ['session', 'url', 'Login_helper'];
  protected $session;
  protected $paths;
  protected $key_login = 'JReLbtRzD5nPP9VqXpZyDw=='; // 't3nci@gr0.' // key = password

  public function __construct()
  {
    $this->libmin = new SystemModel();
    $this->PHPMailer = new PHPMailer_Lib();
    $this->request = service('request');
    $this->session = Services::session();
    $this->key_login;
    $this->paths = new Paths();
  }

  function index() {
    return view('welcome_message');
  }
  
  public function send()
  {
    try {

      $input = json_decode(file_get_contents('php://input'), true);
      // $data['date'] = '';
      $get_data['email_address_send'] = $input['email_address_send'];
      $get_data['email_reply_to'] = $input['email_reply_to'];
      $get_data['email_cc'] = $input['email_cc'];

      $get_data['config_user'] = $input['config_user'];
      $get_data['config_password'] = $input['config_password'];
      $get_data['config_email_show'] = $input['config_email_show'];
      $get_data['config_port'] = $input['config_port'];
      $get_data['config_host'] = $input['config_host'];

      $get_data['data_name'] = $input['name'];
      $get_data['data_title_from'] = $input['data_title_from'];
      $get_data['data_subject'] = $input['data_subject'];
      $get_data['data_body'] = $input['data_body'];
      
      // echo json_encode($get_data);
      // exit();

      //Server settings
      $mail = $this->PHPMailer->load();
      $mail->SMTPDebug = false;                             // Enable verbose debug output optional debug 2
      $mail->do_debug = 0;
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = $get_data['config_host'];               // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = $get_data['config_user'];           // SMTP username
      $mail->Password = $get_data['config_password'];       // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = $get_data['config_port'];               // TCP port to connect to

      // Recipients
      $mail->setFrom($get_data['config_email_show'], $get_data['data_title_from']);
      $mail->addAddress($get_data['email_address_send'], $get_data['data_name']);   // Add a recipient
      // $mail->addAddress('popjj@live.com');                         // Name is optional
      $mail->addReplyTo($get_data['email_reply_to'], 'Information');  // Information
      $mail->addCC($get_data['email_cc']);                            // Security

      // Attachments
      // $mail->addAttachment('https://i.ytimg.com/vi/2eH0JbEE-6k/maxresdefault.jpg');         // Add attachments
      // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = $get_data['data_subject'];
      $mail->Body    = $get_data['data_body'];
      $mail->AltBody = 'Sorry :( email no disponible';

      $mail->send();
      $data['status'] = 200;
      $data['message'] = 'Message has been sent';
      header('Content-type: application/json');
      echo json_encode($data);
      // return json_encode($data);
    } catch (Exception $e) {
      echo 'Message could not be sent.';
    }
  }

}
