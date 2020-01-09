<?php

  /**
   * Authentication Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: aout.class.php, v1.00 2014-06-05 10:12:05 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class Auth
  {

      public $logged_in = null;
      public $uid = 0;
      public $username;
      public $sesid;
      public $email;
      public $name;
      public $fname;
      public $lname;
	  public $avatar;
	  public $membership_id = 0;
      public $usertype = null;
      public $userlevel = 0;
      public $last_active;
      public $last_ip;
	  public $acl = array();
	  public static $userdata = array();
	  public static $udata = array();

      private static $db;


      /**
       * Auth::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          self::$db = Db::run();
          $this->logged_in = $this->loginCheck();

          if (!$this->logged_in) {
              $this->username = "Visitor";
              $this->sesid = sha1(session_id());
              $this->userlevel = 0;
          }

      }

      /**
       * Auth::loginCheck()
       * 
       * @return
       */
      private function loginCheck()
      {
          if (App::get('Session')->isExists('WCDP_username') and App::get('Session')->get('WCDP_username') != "Visitor") {
              $this->uid = App::get('Session')->get('userid');
              $this->username = App::get('Session')->get('WCDP_username');
              $this->email = App::get('Session')->get('email');
              $this->fname = App::get('Session')->get('fname');
              $this->lname = App::get('Session')->get('lname');
              $this->name = App::get('Session')->get('fname') . ' ' . App::get('Session')->get('lname');
			  $this->avatar = App::get('Session')->get('avatar');
              $this->last_active = App::get('Session')->get('last_active');
              $this->last_ip = App::get('Session')->get('last_ip');
              $this->sesid = App::get('Session')->get('sesid');
              $this->usertype = App::get('Session')->get('usertype');
              $this->userlevel = App::get('Session')->get('userlevel');
			  $this->membership_id = App::get('Session')->get('membership_id');
		      $this->acl = App::get('Session')->get('acl');
			  self::$userdata = App::get('Session')->get('userdata');
			  self::$udata = $this;
			  
              return true;
          } else {
              return false;
          }
      }

      /**
       * Auth::is_Admin()
       * 
       * @return
       */
      public function is_Admin()
      {
          $level = array(9,8,7);
          return (in_array($this->userlevel, $level));

      }

      /**
       * Auth::is_User()
       * 
       * @return
       */
      public function is_User()
      {
          $level = array(1);
          return (in_array($this->userlevel, $level) and $this->usertype == "user");

      }
	  
      /**
       * Auth::loginAdmin()
       * 
       * @param mixed $username
       * @param mixed $password
       * @return
       */
      public function loginAdmin($username, $password)
      {
          if ($username == "" && $password == "") {
              Message::$msgs['username'] = Lang::$word->LOGIN_R5;
          } else {
              $status = $this->checkStatus($username, $password, "admin");

              switch ($status) {
                  case "e":
                      Message::$msgs['username'] = Lang::$word->LOGIN_R1;
                      break;

                  case "b":
                      Message::$msgs['username'] = Lang::$word->LOGIN_R2;
                      break;

                  case "n":
                      Message::$msgs['username'] = Lang::$word->LOGIN_R3;
                      break;

                  case "t":
                      Message::$msgs['username'] = Lang::$word->LOGIN_R4;
                      break;
              }
          }
          if (empty(Message::$msgs) && $status == "y") {
              $row = $this->getUserInfo($username, "admin");
              $this->uid = App::get('Session')->set('userid', $row->id);
              $this->username = App::get('Session')->set('WCDP_username', $row->username);
              $this->fullname = App::get('Session')->set('fullname', $row->fname . ' ' . $row->lname);
              $this->fname = App::get('Session')->set('fname', $row->fname);
              $this->lname = App::get('Session')->set('lname', $row->lname);
              $this->email = App::get('Session')->set('email', $row->email);
              $this->userlevel = App::get('Session')->set('userlevel', $row->userlevel);
			  $this->avatar = App::get('Session')->set('avatar', $row->avatar);
              $this->usertype = App::get('Session')->set('usertype', $row->type);
              $this->last_active = App::get('Session')->set('last_active', Db::toDate());
              $this->last_ip = App::get('Session')->set('last_ip', Validator::sanitize($_SERVER['REMOTE_ADDR']));

			  $result = self::getAcl($row->type);
			  $privileges = array();
			  for($i = 0; $i < count($result); $i++){
				  $privileges[$result[$i]->code] = ($result[$i]->active == 1) ? true : false;
			  }
		      $this->acl = App::get('Session')->set('acl', $privileges);

              $data = array('last_active' => Db::toDate(), 'lastip' => Validator::sanitize($_SERVER['REMOTE_ADDR']));
              self::$db->update(Users::aTable, $data, array('id' => $row->id));
			  self::setUserCookies($row->username, $row->fname . ' ' . $row->lname, $row->avatar);
			  
              return true;
          } else
              Message::msgStatus();
      }

      /**
       * Auth::loginUser()
       * 
       * @param mixed $username
       * @param mixed $password
       * @return
       */
      public function loginUser($username, $password)
      {
          if ($username == "" && $password == "") {
              Message::$msgs['username'] = Lang::$word->LOGIN_R5;
          } else {
              $status = $this->checkStatus($username, $password, "front");

              $banwhere = array('item =' => Validator::sanitize($_SERVER['REMOTE_ADDR']), 'and type =' => 'IP');
              if (self::$db->first(Content::blTable, array('item'), $banwhere)) {
                  Message::$msgs['username'] = Lang::$word->LOGIN_R2;
              }

              switch ($status) {
                  case "e":
                      Message::$msgs['username'] = Lang::$word->LOGIN_R1;
                      break;

                  case "b":
                      Message::$msgs['username'] = Lang::$word->LOGIN_R2;
                      break;

                  case "n":
                      Message::$msgs['username'] = Lang::$word->LOGIN_R3;
                      break;

                  case "t":
                      Message::$msgs['username'] = Lang::$word->LOGIN_R4;
                      break;
              }
          }
          if (empty(Message::$msgs) && $status == "y") {
              $row = $this->getUserInfo($username, "front");
              $this->uid = App::get('Session')->set('userid', $row->id);
              $this->username = App::get('Session')->set('WCDP_username', $row->username);
              $this->fullname = App::get('Session')->set('fullname', $row->fname . ' ' . $row->lname);
              $this->fname = App::get('Session')->set('fname', $row->fname);
              $this->lname = App::get('Session')->set('lname', $row->lname);
              $this->email = App::get('Session')->set('email', $row->email);
              $this->userlevel = App::get('Session')->set('userlevel', $row->userlevel);
			  $this->membership_id = App::get('Session')->set('membership_id', $row->membership_id);
              $this->usertype = App::get('Session')->set('usertype', $row->usertype);
              $this->last_active = App::get('Session')->set('last_active', Db::toDate());
              $this->last_ip = App::get('Session')->set('last_ip', Validator::sanitize($_SERVER['REMOTE_ADDR']));
              $this->sesid = App::get('Session')->set('sesid', sha1(session_id()));
			  $this->acl = App::get('Session')->set('acl', '');

              $data = array('last_active' => Db::toDate(), 'lastip' => Validator::sanitize($_SERVER['REMOTE_ADDR']));
              self::$db->update(Users::mTable, $data, array('id' => $row->id));
			  self::$userdata = App::get('Session')->set('userdata', $row);

			  /* == Add Activity == */
			  $acdata = array(
				  'user_id' => $row->id,
				  'type' => "login"
				  );
			  self::$db->insert(Users::acTable, $acdata);

              return true;
          } else
              Message::msgStatus();
      }

      /**
       * Auth::checkStatus()
       * 
       * @param mixed $username
       * @param mixed $pass
       * @return
       */
      public function checkStatus($username, $pass, $table)
      {

          $username = Validator::sanitize($username, "string");
          $pass = Validator::sanitize($pass);
		  $tablename = ($table == "admin") ? Users::aTable : Users::mTable;

          $where = array('username =' => $username, 'or email =' => $username);
          $row = self::$db->select($tablename, array(
              'salt',
              'hash',
              'active'), $where, 'LIMIT 1')->result();

          if (!$row)
              return "e";

          $validpass = $this->_validate_login($pass, $row->hash, $row->salt);

          if (!$validpass)
              return "e";

          switch ($row->active) {
              case "b":
                  return "b";
                  break;

              case "n":
                  return "n";
                  break;

              case "t":
                  return "t";
                  break;

              case "y" and $validpass == true:
                  return "y";
                  break;
          }

      }

      /**
       * Auth::getUserInfo()
       * 
       * @param mixed $username
       * @param mixed $table
       * @return
       */
      public function getUserInfo($username, $table)
      {
          $username = Validator::sanitize($username, "string");
          $tablename = ($table == "admin") ? Users::aTable : Users::mTable;

          $row = self::$db->first($tablename, null, array('username =' => $username, 'or email =' => $username));

          return ($row) ? $row : 0;
      }

      /**
       * Auth::getAcl()
       * 
       * @param mixed $username
       * @param mixed $table
       * @return
       */
	  public static function getAcl($role = '')
	  {
		  $sql = "
		  SELECT 
			p.code,
			p.name,
			p.description,
			rp.active 
		  FROM `".Users::rpTable."` rp 
			INNER JOIN `".Users::rTable."` r 
			  ON rp.rid = r.id 
			INNER JOIN `".Users::pTable."` p 
			  ON rp.pid = p.id 
		  WHERE r.code = ? ;";
		  
		  return self::$db->pdoQuery($sql, array($role))->results();
		  
	  }

      /**
       * Auth::hasPrivileges()
       * 
       * @param mixed $code
       * @param mixed $val
       * @return
       */
	  public static function hasPrivileges($code = '', $val = '')
	  {
		  $privileges_info = App::get('Session')->get('acl');
		  if (!empty($val)) {
			  if (isset($privileges_info[$code]) && is_array($privileges_info[$code])) {
				  return in_array($val, $privileges_info[$code]);
			  } else {
				  return ($privileges_info[$code] == $val);
			  }
		  } else {
			  return (isset($privileges_info[$code]) && $privileges_info[$code] == true) ? true : false;
		  }
	  }
	
      /**
       * Auth::logout()
       * 
       * @return
       */
      public function logout()
      {
          App::get('Session')->endSession();
          $this->logged_in = false;
          $this->username = "Visitor";
          $this->userlevel = 0;
      }

      /**
       * Auth::usernameExists()
       * 
       * @param mixed $username
       * @return
       */
      public static function usernameExists($username, $table)
      {
          $username = Validator::sanitize($username, "string");
          if (strlen($username) < 4)
              return 1;

          //Username should contain only alphabets, numbers, or underscores. Should be between 4 to 15 characters long
          $valid_uname = "/^[a-zA-Z0-9_]{4,15}$/";
          if (!preg_match($valid_uname, $username))
              return 2;

		  $tablename = ($table == "admin") ? Users::aTable : Users::mTable;
		  $row = self::$db->select($tablename, array('username'), array('username' => $username), 'LIMIT 1')->result();

          return ($row) ? 3 : false;
      }

      /**
       * Auth::emailExists()
       * 
       * @param mixed $email
       * @param mixed $table
       * @return
       */
      public static function emailExists($email, $table)
      {
		  $tablename = ($table == "admin") ? Users::aTable : Users::mTable;
          $row = self::$db->select($tablename, array('email'), array('email' => $email), ' LIMIT 1')->result();

          if ($row) {
              return true;
          } else
              return false;
      }

      /**
       * Auth::setUserCookies()
       * 
       * @param mixed $username
       * @param mixed $name
	   * @param mixed $avatar
       * @return
       */
      public static  function setUserCookies($username, $name, $avatar)
      {
		  $avatar = empty($avatar) ? "blank.png" : $avatar;
          if(!isset($_COOKIE['CDP_loginData'])) {
			  setcookie( "CDP_loginData[0]", $username, strtotime('+30 days'));
			  setcookie( "CDP_loginData[1]", $name, strtotime('+30 days'));
			  setcookie( "CDP_loginData[2]", $avatar, strtotime('+30 days'));
		  }
          
      }

      /**
       * Auth::getUserCookies()
       * 
       * @return
       */
      public static  function getUserCookies()
      {
          if(isset($_COOKIE['CDP_loginData'])) {
			  $data = array(
				'username' => $_COOKIE['CDP_loginData'][0],
				'name' => $_COOKIE['CDP_loginData'][1],
				'avatar' => $_COOKIE['CDP_loginData'][2]
			  );
			  return $data;
		  } else {
			  return false;
		  }
          
      }
	  
      /**
       * Auth::create_hash()
       * 
       * @param mixed $password
       * @param string $salt
       * @param integer $stretch_cost
       * @return
       */
      public function create_hash($password, &$salt = '', $stretch_cost = 10)
      {
          $salt = strlen($salt) != 21 ? $this->_create_salt() : $salt;
          if (function_exists('crypt') && defined('CRYPT_BLOWFISH')) {
              return crypt($password, '$2a$' . $stretch_cost . '$' . $salt . '$');
          }

          if (!function_exists('hash') || !in_array('sha512', hash_algos())) {
			  Debug::AddMessage("errors", "hash", "You must have the PHP PECL hash module installed");
          }

          return $this->_create_hash($password, $salt);
      }


      /**
       * Auth::validate_hash()
       * 
       * @param mixed $pass
       * @param mixed $hashed_pass
       * @param mixed $salt
       * @return
       */
      public function validate_hash($pass, $hashed_pass, $salt)
      {
          return $hashed_pass === $this->create_hash($pass, $salt);
      }

      /**
       * Auth::_validate_login()
       * 
       * @param mixed $pass
       * @param mixed $hash
       * @param mixed $salt
       * @return
       */
      protected function _validate_login($pass, $hash, $salt)
      {
          if ($this->validate_hash($pass, $hash, $salt)) {
              return true;
          } else
              return false;
      }

      /**
       * Auth::_create_salt()
       * 
       * @return
       */
      protected function _create_salt()
      {
          $salt = $this->_pseudo_rand(128);
          return substr(preg_replace('/[^A-Za-z0-9_]/is', '.', base64_encode($salt)), 0, 21);
      }

      /**
       * Auth::_pseudo_rand()
       * 
       * @param mixed $length
       * @return
       */
      protected function _pseudo_rand($length)
      {
          if (function_exists('openssl_random_pseudo_bytes')) {
              $is_strong = false;
              $rand = openssl_random_pseudo_bytes($length, $is_strong);
              if ($is_strong === true)
                  return $rand;
          }
          $rand = '';
          $sha = '';
          for ($i = 0; $i < $length; $i++) {
              $sha = hash('sha256', $sha . mt_rand());
              $chr = mt_rand(0, 62);
              $rand .= chr(hexdec($sha[$chr] . $sha[$chr + 1]));
          }
          return $rand;
      }

      /**
       * Auth::_create_hash()
       * 
       * @param mixed $password
       * @param mixed $salt
       * @return
       */
      private function _create_hash($password, $salt)
      {
          $hash = '';
          for ($i = 0; $i < 20000; $i++) {
              $hash = hash('sha512', $hash . $salt . $password);
          }
          return $hash;
      }

  }