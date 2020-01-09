<?php
  /**
   * Mailer Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: mailer.class.php, v1.00 2014-06-05 10:12:05 gewa Exp $
   */
  
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class Mailer
  {
	  
	  private static $instance;

      /**
       * Mailer::__construct()
       * 
       * @return
       */
      private function __construct(){}

      /**
       * Mailer::instance()
       * 
       * @return
       */
	  public static function instance(){
		  if (!self::$instance){ 
			  self::$instance = new Mailer(); 
		  } 
	  
		  return self::$instance;  
	  }

      /**
       * Mailer::sendMail()
       * 
       * @return
       */
      public static function sendMail()
      {
          require_once (BASEPATH . 'lib/swift/swift_required.php');
          
          if (App::get("Core")->mailer == "SMTP") {
			  $SSL = (App::get("Core")->is_ssl) ? 'ssl' : null;
              $transport = Swift_SmtpTransport::newInstance(App::get("Core")->smtp_host, App::get("Core")->smtp_port, $SSL)
						  ->setUsername(App::get("Core")->smtp_user)
						  ->setPassword(App::get("Core")->smtp_pass);
		  } elseif (App::get("Core")->mailer == "SMAIL") {
			  $transport = Swift_SendmailTransport::newInstance(App::get("Core")->sendmail);
          } else
              $transport = Swift_MailTransport::newInstance();
          
          return Swift_Mailer::newInstance($transport);
	  }
	  
  }