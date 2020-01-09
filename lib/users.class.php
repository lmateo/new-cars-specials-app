<?php
  /**
   * Users Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: users.class.php, v1.00 2014-06-05 10:12:05 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class Users
  {

      const aTable = "admins";
      const mTable = "members";
	  const mxTable = "memberships";
      const rTable = "roles";
      const rpTable = "role_privileges";
      const pTable = "privileges";
	  const acTable = "activity";
	  
      private static $db;


      /**
       * Users::__construct()
       * 
       * @return
       */
      public function __construct()
      {
		  self::$db = Db::run();

      }

      /**
       * Users::register()
       * 
       * @return
       */
      public function register()
      {
		  
		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  
		  $validate->addRule('email', 'email');
		  $validate->addRule('username', 'string', true, 4, 50, Lang::$word->USERNAME);
		  $validate->addRule('fname', 'string', true, 2, 50, Lang::$word->FNAME);
		  $validate->addRule('lname', 'string', true, 2, 50, Lang::$word->LNAME);
		  $validate->addRule('address', 'string', true, 8, 100, Lang::$word->ADDRESS);
		  $validate->addRule('city', 'string', true, 2, 30, Lang::$word->CITY);
		  $validate->addRule('state', 'string', true, 2, 50, Lang::$word->STATE);
		  $validate->addRule('zip', 'string', true, 2, 50, Lang::$word->ZIP);
		  $validate->addRule('country', 'string', true, 2, 2, Lang::$word->COUNTRY);
		  $validate->addRule('password', 'string', true, 6, 20, Lang::$word->PASSWORD);
		  $validate->addRule('password2', 'string', true, 6, 20, Lang::$word->PASSWORD_C);
		  $validate->addRule('captcha', 'numeric', true, 5, 5, Lang::$word->CAPTCHA);
		  
		  $validate->addRule('company', 'string', false);
		  $validate->addRule('url', 'string', false);
		  $validate->addRule('lat', 'float', false);
		  $validate->addRule('lng', 'float', false);
		  
		  $validate->run();

          if (strlen($_POST['password']) < 6)
              Message::$msgs['password'] = Lang::$word->PASSWORD_T2;
          elseif (!preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", ($_POST['password'] = trim($_POST['password']))))
              Message::$msgs['password'] = Lang::$word->PASSWORD_R2;
          elseif ($_POST['password'] != $_POST['password2'])
              Message::$msgs['password'] = Lang::$word->PASSWORD_R3;
			  
          if (Auth::emailExists($_POST['email'], "members"))
              Message::$msgs['email'] = Lang::$word->EMAIL_R2;

		  if (App::get('Session')->get('captchacode') != $_POST['captcha'])
			  Message::$msgs['captcha'] = Lang::$word->CAPTCHA;
			  
          if (empty(Message::$msgs)) {
              $salt = '';
			  $hash = App::get('Auth')->create_hash(Validator::cleanOut($_POST['password']), $salt);
              $data = array(
                  'username' => $validate->safe->username,
                  'email' => $validate->safe->email,
                  'lname' => $validate->safe->lname,
                  'fname' => $validate->safe->fname,
				  'company' => $validate->safe->company,
				  'country' => $validate->safe->country,
				  'address' => $validate->safe->address,
				  'city' => $validate->safe->city,
				  'state' => $validate->safe->state,
				  'zip' => $validate->safe->zip,
				  'url' => $validate->safe->url,
                  'hash' => $hash,
                  'salt' => $salt,
                  'created' => Db::toDate(),
				  'usertype' => "user",
				  'userlevel' => 1,
				  'active' => "y",
				  );
				  
              $last_id = self::$db->insert(self::mTable, $data)->getLastInsertId();
			  
			  App::get('Auth')->loginUser($validate->safe->username, $_POST['password']);
              
			  //Add Location
              $ldata = array(
                  'name' => $validate->safe->company ? $validate->safe->company : $validate->safe->username,
                  'name_slug' => $validate->safe->company ? Url::doSeo(Utility::randNumbers(4) . '-' . $validate->safe->company) : Url::doSeo(Utility::randNumbers(4) . '-' . $validate->safe->username),
                  'user_id' => $last_id,
                  'ltype' => "user",
				  'email' => $validate->safe->email,
				  'address' => $validate->safe->address,
				  'city' => $validate->safe->city,
				  'state' => $validate->safe->state,
				  'zip' => $validate->safe->zip,
				  'country' => $validate->safe->country,
				  'url' => $validate->safe->url,
				  'lat' => $validate->safe->lat,
				  'lng' => $validate->safe->lng
				  );
				
			  self::$db->insert(Content::lcTable, $ldata);  
			  
			  //User Email Notification
			  $mailer = Mailer::sendMail();
			  $subject = Lang::$word->M_ACCSUBJECT . ' ' . $data['fname'] . ' ' . $data['lname'];

			  ob_start();
			  require_once (BASEPATH . 'mailer/' . Core::$language . '/Member_Welcome_Message.tpl.php');
			  $html_message = ob_get_contents();
			  ob_end_clean();

			  $body = str_replace(array(
				  '[LOGO]',
				  '[FULLNAME]',
				  '[USERNAME]',
				  '[PASS]',
				  '[DATE]',
				  '[COMPANY]',
				  '[LOGINURL]',
				  '[SITEURL]'), array(
				  Utility::getLogo(),
				  $data['fname'] . ' ' . $data['lname'],
				  $data['username'],
				  $_POST['password'],
				  date('Y'),
				  App::get("Core")->company,
				  SITEURL . '/',
				  SITEURL), $html_message);
				  
			  $msg = Swift_Message::newInstance()
					->setSubject($subject)
					->setTo(array($data['email'] => $data['fname'] . ' ' . $data['lname']))
					->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
					->setBody($body, 'text/html');
			  $mailer->send($msg);
			  
			  if ($last_id) {
				  $json['type'] = "success";
				  $json['title'] = Lang::$word->SUCCESS;
				  $json['message'] = Lang::$word->M_ADDED;
				  $json['redirect'] = Url::doUrl(URL_ACCOUNT);
			  } else {
				  $json['type'] = "alert";
				  $json['title'] = Lang::$word->ALERT;
				  $json['message'] = Lang::$word->NOPROCCESS;
			  }
			  print json_encode($json);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Users::getAllStaff()
       * 
       * @param bool $from
       * @return
       */
      public function getAllStaff($from = false)
      {

          if (App::get("Auth")->usertype == 'owner'|| App::get("Auth")->usertype == 'admin') {
              $where = 'WHERE (type = \'admin\' || type = \'editor\')';
          } else {
              if (App::get("Auth")->usertype == 'admin') {
                  $where = 'WHERE (type = \'editor\')';
              }
		  }
			  
          if (isset($_GET['letter']) and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
              $enddate = date("Y-m-d");
              $letter = Validator::sanitize($_GET['letter'], 'default', 2);
              $fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
              if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                  $enddate = Validator::sanitize($_POST['enddate_submit']);
              }
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::aTable . "` $where AND created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND username REGEXP '^" . $letter . "'");
              $and = "AND created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND fname REGEXP '^" . $letter . "'";

          } elseif (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
              if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                  $enddate = Validator::sanitize($_POST['enddate_submit']);
              }
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::aTable . "` $where AND created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'");
              $and = "AND created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";

          } elseif (isset($_GET['letter'])) {
              $letter = Validator::sanitize($_GET['letter'], 'default', 2);
              $and = "AND username REGEXP '^" . $letter . "'";
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::aTable . "` $where AND fname REGEXP '^" . $letter . "' LIMIT 1");
          } else {
              $counter = self::$db->count(self::aTable);
              $and = null;
          }

          if (isset($_GET['order'])) {
              list($sort, $order) = explode("/", $_GET['order']);
              $sort = Validator::sanitize($sort, "default", 10);
              $order = Validator::sanitize($order, "default", 4);
              if (in_array($sort, array(
                  "username",
                  "fname",
                  "email",
                  "type"))) {
                  $ord = ($order == 'DESC') ? " DESC" : " ASC";
                  $sorting = $sort . $ord;
              } else {
                  $sorting = " created DESC";
              }
          } else {
              $sorting = " created DESC";
          }

          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = App::get("Core")->perpage;
          $pager->paginate();

          $sql = "
		  SELECT *, CONCAT(fname,' ',lname) as fullname
		  FROM   `" . self::aTable . "`
		  $where
		  $and
		  ORDER  BY $sorting" . $pager->limit;

          $row = self::$db->pdoQuery($sql)->results();

          return ($row) ? $row : 0;

      }

      /**
       * Users::processStaff()
       * 
       * @return
       */
      public function processStaff()
      {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('email', 'email');
		  $validate->addRule('fname', 'string', true, 3, 50, Lang::$word->FNAME);
		  $validate->addRule('lname', 'string', true, 3, 50, Lang::$word->LNAME);
		  $validate->addRule('userlevel', 'numeric', false);
		  $validate->addRule('webspecialsalert', 'string', true, 1, 1);
		  $validate->addRule('active', 'string', true, 1, 1);
		  
          (Filter::$id) ? $this->_updateStaff($validate) : $this->_addStaff($validate);

      }

      /**
       * Users::_addStaff()
       * 
       * @return
       */
      private function _addStaff($validate)
      {
		  $validate->addRule('username', 'alpha', true, 4, 20, Lang::$word->USERNAME_R1);
		  $validate->addRule('password', 'string', true, 6, 20, Lang::$word->PASSWORD_R1);
		  $validate->run();

          if ($value = Auth::usernameExists($validate->safe->username, "admin")) {
              if ($value == 1)
                  Message::$msgs['username'] = Lang::$word->USERNAME_R2;
              if ($value == 2)
                  Message::$msgs['username'] = Lang::$word->USERNAME_R3;
              if ($value == 3)
                  Message::$msgs['username'] = Lang::$word->USERNAME_R4;
          }
		  
          if (Auth::emailExists($validate->safe->email, "admin"))
              Message::$msgs['email'] = Lang::$word->EMAIL_R2;
          
          if (empty(Message::$msgs)) {
              $data = array(
                  'username' => $validate->safe->username,
                  'email' => $validate->safe->email,
                  'lname' => $validate->safe->lname,
                  'fname' => $validate->safe->fname,
                  'type' => self::accountLevelToType($validate->safe->userlevel),
                  'userlevel' => $validate->safe->userlevel,
              	  'webspecialsalert' => $validate->safe->webspecialsalert,
                  'created' => Db::toDate(),
                  'active' => $validate->safe->active
				  );

              if (!empty($_POST['password'])) {
                  $salt = '';
                  $hash = App::get('Auth')->create_hash(Validator::cleanOut($_POST['password']), $salt);
                  $data['hash'] = $hash;
                  $data['salt'] = $salt;
              }

              self::$db->insert(self::aTable, $data);

              if (self::$db->getLastInsertId()) {
                  $json['type'] = 'success';
				  $json['title'] = Lang::$word->SUCCESS;
                  $json['message'] = Lang::$word->M_ADDED;
                  print json_encode($json);

                  if (isset($_POST['notify']) && intval($_POST['notify']) == 1) {
                      $pass = Validator::cleanOut($_POST['password']);
                      $mailer = Mailer::sendMail();
                      $subject = Lang::$word->M_ACCSUBJECT . ' / ' . $data['fname'] . ' ' . $data['lname'];

                      ob_start();
                      require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Member_Welcome_Message.tpl.php');
                      $html_message = ob_get_contents();
                      ob_end_clean();

                      $body = str_replace(array(
                          '[LOGO]',
                          '[FULLNAME]',
                          '[USERNAME]',
                          '[PASS]',
                          '[DATE]',
                          '[COMPANY]',
                          '[LOGINURL]',
                          '[SITEURL]'), array(
                          Utility::getLogo(),
                          $data['fname'] . ' ' . $data['lname'],
                          $data['username'],
                          $pass,
                          date('Y'),
                          App::get("Core")->company,
                          SITEURL . '/admin/',
                          SITEURL . '/admin/'), $html_message);

                      $msg = Swift_Message::newInstance()
							->setSubject($subject)
							->setTo(array($data['email'] => $data['fname'] . ' ' . $data['lname']))
							->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
							->setBody($body, 'text/html');

                      $mailer->send($msg);
                  }
              } else {
                  $json['type'] = 'alert';
				  $json['title'] = Lang::$word->ALERT;
                  $json['message'] = Lang::$word->NOPROCCESS;
                  print json_encode($json);
              }

          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Users::_updateStaff()
       * 
       * @return
       */
      private function _updateStaff($validate)
      {
          $validate->run();
          if (empty(Message::$msgs)) {
              $data = array(
                  'email' => $validate->safe->email,
                  'lname' => $validate->safe->lname,
                  'fname' => $validate->safe->fname,
                  'type' => self::accountLevelToType($validate->safe->userlevel),
                  'userlevel' => $validate->safe->userlevel,
              	  'webspecialsalert' => $validate->safe->webspecialsalert,
                  'active' => $validate->safe->active);

              if (!empty($_POST['password'])) {
                  $salt = '';
                  $hash = App::get('Auth')->create_hash(Validator::cleanOut($_POST['password']), $salt);
                  $data['hash'] = $hash;
                  $data['salt'] = $salt;
              }

              self::$db->update(self::aTable, $data, array('id' => Filter::$id));
              $message = Lang::$word->M_UPDATED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Users::processMember()
       * 
       * @return
       */
      public function processMember()
      {
		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  
		  $validate->addRule('fname', 'string', true, 3, 50, Lang::$word->FNAME);
		  $validate->addRule('lname', 'string', true, 3, 50, Lang::$word->LNAME);
		  $validate->addRule('country', 'string', true, 2, 2, Lang::$word->COUNTRY);
		  $validate->addRule('address', 'string', true, 3, 100, Lang::$word->ADDRESS);
		  $validate->addRule('city', 'string', true, 2, 50, Lang::$word->CITY);
		  $validate->addRule('state', 'string', true, 2, 50, Lang::$word->STATE);
		  $validate->addRule('zip', 'string', true, 2, 20, Lang::$word->ZIP);
		  $validate->addRule('company', 'string', false);
		  //$validate->addRule('type', 'numeric', false);
		  $validate->addRule('membership_id', 'numeric', false);
		  $validate->addRule('url', 'string', false);
		  $validate->addRule('comments', 'string', false);
		  $validate->addRule('about', 'string', false);
		  $validate->addRule('email', 'email');

          (Filter::$id) ? $this->_updateMember($validate) : $this->_addMember($validate);

      }


      /**
       * Users::_addMember()
       * 
       * @return
       */
      private function _addMember($validate)
      {
		  
		  $validate->addRule('username', 'alpha', true, 4, 20, Lang::$word->USERNAME_R1);
		  $validate->addRule('password', 'string', true, 6, 20, Lang::$word->PASSWORD_R1);
          $validate->run();
		  
          if ($value = Auth::usernameExists($validate->safe->username, "front")) {
              if ($value == 1)
                  Message::$msgs['username'] = Lang::$word->USERNAME_R2;
              if ($value == 2)
                  Message::$msgs['username'] = Lang::$word->USERNAME_R3;
              if ($value == 3)
                  Message::$msgs['username'] = Lang::$word->USERNAME_R4;
          }

          if (Auth::emailExists($validate->safe->email, "front"))
              Message::$msgs['email'] = Lang::$word->EMAIL_R2;

          $upl = Upload::instance(512000, "png,jpg");
          if (!empty($_FILES['avatar']['name']) and empty(Message::$msgs)) {
              $upl->process("avatar", UPLOADS . "avatars/", "AVT_");
          }
		  
          if (empty(Message::$msgs)) {
              $salt = '';
			  $hash = App::get('Auth')->create_hash(Validator::cleanOut($_POST['password']), $salt);
              $data = array(
                  'username' => $validate->safe->username,
                  'email' => $validate->safe->email,
                  'lname' => $validate->safe->lname,
                  'fname' => $validate->safe->fname,
                  'url' => $validate->safe->url,
				  'company' => $validate->safe->company,
				  'address' => $validate->safe->address,
				  'city' => $validate->safe->city,
				  'state' => $validate->safe->state,
				  'zip' => $validate->safe->zip,
                  'country' => $validate->safe->country,
                  'hash' => $hash,
                  'salt' => $salt,
                  'created' => Db::toDate(),
				  'membership_id' => ($validate->safe->membership_id > 0) ? $validate->safe->membership_id : 0,
				  'membership_expire' => ($validate->safe->membership_id > 0) ? self::calculateDays($validate->safe->membership_id) : "0000-00-00 00:00:00",
				  'avatar' => isset($upl->fileInfo['fname']) ? $upl->fileInfo['fname'] : "NULL",
                  'comments' => $validate->safe->comments,
				  'about' => $validate->safe->about,
				  'active' => "y",
                  //'type' => $validate->safe->type
				  );
				  
              if(isset($_POST['mem_expire_submit']) and $_POST['mem_expire_submit'] > 0) {
				  $texpire = !empty($_POST['mem_expiret']) ? Validator::sanitize($_POST['mem_expiret']) : date("H:i:s");
				  $data['membership_expire'] = Validator::sanitize($_POST['mem_expire_submit'] . ' ' . $texpire);
			  }
			  
              self::$db->insert(self::mTable, $data);
              $message = Lang::$word->M_ADDED;

              if (self::$db->getLastInsertId()) {
                  $json['type'] = 'success';
				  $json['title'] = Lang::$word->SUCCESS;
                  $json['message'] = Lang::$word->M_ADDED;
                  print json_encode($json);

                  if (isset($_POST['notify']) && intval($_POST['notify']) == 1) {
                      $pass = Validator::cleanOut($_POST['password']);
                      $mailer = Mailer::sendMail();
                      $subject = Lang::$word->M_ACCSUBJECT . $data['fname'] . ' ' . $data['lname'];

                      ob_start();
                      require_once (BASEPATH . 'mailer/' . Core::$language . '/Member_Welcome_Message.tpl.php');
                      $html_message = ob_get_contents();
                      ob_end_clean();

                      $body = str_replace(array(
                          '[LOGO]',
                          '[FULLNAME]',
                          '[USERNAME]',
                          '[PASS]',
                          '[DATE]',
                          '[COMPANY]',
                          '[LOGINURL]',
                          '[SITEURL]'), array(
                          Utility::getLogo(),
                          $data['fname'] . ' ' . $data['lname'],
                          $data['username'],
                          $pass,
                          date('Y'),
                          App::get("Core")->company,
                          SITEURL . '/login/',
                          SITEURL), $html_message);

                      $msg = Swift_Message::newInstance()
							->setSubject($subject)
							->setTo(array($data['email'] => $data['fname'] . ' ' . $data['lname']))
							->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
							->setBody($body, 'text/html');

                      $numSent = $mailer->send($msg);
                  }
              } else {
                  $json['type'] = 'alert';
				  $json['title'] = Lang::$word->ALERT;
				  $json['message'] = Lang::$word->NOPROCCESS;
                  print json_encode($json);
              }

          } else {
              Message::msgSingleStatus();
          }
      }


      /**
       * Users::_updateMember()
       * 
       * @return
       */
      private function _updateMember($validate)
      {
          $validate->run();
          if (empty(Message::$msgs)) {
			  $mrow = self::$db->first(self::mTable, array("membership_id", "membership_expire"), array('id' => Filter::$id));
              $data = array(
                  'email' => $validate->safe->email,
                  'lname' => $validate->safe->lname,
                  'fname' => $validate->safe->fname,
                  'url' => $validate->safe->url,
				  'company' => $validate->safe->company,
				  'address' => $validate->safe->address,
				  'city' => $validate->safe->city,
				  'state' => $validate->safe->state,
				  'zip' => $validate->safe->zip,
                  'country' => $validate->safe->country,
				  'about' => $validate->safe->about,
                  'comments' => $validate->safe->comments,
				  'membership_id' => ($mrow->membership_id != $validate->safe->membership_id) ? $validate->safe->membership_id : $mrow->membership_id,
				  'membership_expire' => ($mrow->membership_id != $validate->safe->membership_id) ? self::calculateDays($validate->safe->membership_id) : $mrow->membership_expire,
				  //'type' => $validate->safe->type
				  );
				  
              if(isset($_POST['mem_expire_submit']) and $_POST['mem_expire_submit'] > 0) {
				  $texpire = !empty($_POST['mem_expiret']) ? Validator::sanitize($_POST['mem_expiret']) : date("H:i:s");
				  $data['membership_expire'] = Validator::sanitize($_POST['mem_expire_submit'] . ' ' . $texpire);
			  }
			  
              if (!empty($_POST['password'])) {
                  $salt = '';
                  $hash = App::get('Auth')->create_hash(Validator::cleanOut($_POST['password']), $salt);
                  $data['hash'] = $hash;
                  $data['salt'] = $salt;
              }

			  self::$db->update(self::mTable, $data, array('id' => Filter::$id));
			  Message::msgReply(self::$db->affected(), 'success', Lang::$word->M_UPDATED);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Users::updateAccount()
       * 
       * @return
       */
      public function updateAccount()
      {
          
		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('email', 'email');
		  $validate->addRule('fname', 'string', true, 3, 50, Lang::$word->FNAME);
		  $validate->addRule('lname', 'string', true, 3, 50, Lang::$word->LNAME);
		  $validate->run();

          $upl = Upload::instance(512000, "png,jpg,jpeg");
          if (!empty($_FILES['avatar']['name']) and empty(Message::$msgs)) {
              $upl->process("avatar", UPLOADS . "avatars/", "AVT_");
          }
		   
          if (empty(Message::$msgs)) {
              $data = array(
                  'email' => $validate->safe->email,
                  'lname' => $validate->safe->lname,
                  'fname' => $validate->safe->fname
				  );

              if (!empty($_POST['password'])) {
                  $salt = '';
                  $hash = App::get('Auth')->create_hash(Validator::cleanOut($_POST['password']), $salt);
                  $data['hash'] = $hash;
                  $data['salt'] = $salt;
              }
              if (isset($upl->fileInfo['fname'])) {
                  $data['avatar'] = $upl->fileInfo['fname'];
				  if(Auth::$udata->avatar !="") {
					  File::deleteFile(UPLOADS . "avatars/" . Auth::$udata->avatar);
				  }
              }
              self::$db->update(self::aTable, $data, array("id" => Auth::$udata->uid));
			  Message::msgReply(self::$db->affected(), 'success', Lang::$word->M_UPDATED);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }

      /**
       * Users::updateProfile()
       * 
       * @return
       */
      public function updateProfile()
      {
		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  
		  $validate->addRule('fname', 'string', true, 3, 50, Lang::$word->FNAME);
		  $validate->addRule('lname', 'string', true, 3, 50, Lang::$word->LNAME);
		  $validate->addRule('country', 'string', true, 2, 2, Lang::$word->COUNTRY);
		  $validate->addRule('address', 'string', true, 3, 100, Lang::$word->ADDRESS);
		  $validate->addRule('city', 'string', true, 2, 50, Lang::$word->CITY);
		  $validate->addRule('state', 'string', true, 2, 50, Lang::$word->STATE);
		  $validate->addRule('zip', 'string', true, 2, 20, Lang::$word->ZIP);
		  $validate->addRule('company', 'string', false);
		  $validate->addRule('url', 'string', false);
		  $validate->addRule('about', 'string', false);
		  $validate->addRule('phone', 'string', false);
		  $validate->addRule('email', 'email');
		  
		  $validate->run();

          $upl = Upload::instance(512000, "png,jpg");
          if (!empty($_FILES['avatar']['name']) and empty(Message::$msgs)) {
              $upl->process("avatar", UPLOADS . "avatars/", "AVT_");
          }
		  
          if (empty(Message::$msgs)) {
              $data = array(
                  'email' => $validate->safe->email,
                  'lname' => $validate->safe->lname,
                  'fname' => $validate->safe->fname,
                  'url' => $validate->safe->url,
				  'company' => $validate->safe->company,
				  'address' => $validate->safe->address,
				  'city' => $validate->safe->city,
				  'state' => $validate->safe->state,
				  'zip' => $validate->safe->zip,
				  'phone' => $validate->safe->phone,
                  'country' => $validate->safe->country,
				  'about' => $validate->safe->about,
				  );
			  
              if (!empty($_POST['password'])) {
                  $salt = '';
                  $hash = App::get('Auth')->create_hash(Validator::cleanOut($_POST['password']), $salt);
                  $data['hash'] = $hash;
                  $data['salt'] = $salt;
              }
			  
              if (isset($upl->fileInfo['fname'])) {
                  $data['avatar'] = $upl->fileInfo['fname'];
				  if(Auth::$udata->avatar !="") {
					  File::deleteFile(UPLOADS . "avatars/" . Auth::$udata->avatar);
				  }
              }
			  
			  self::$db->update(self::mTable, $data, array('id' => Auth::$udata->uid));
			  Message::msgReply(self::$db->affected(), 'success', Lang::$word->M_UPDATED);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Users::passReset()
       * 
       * @return
       */
      public function passReset()
      {
		  $validate = Validator::instance();
		  $validate->addSource($_POST);

		  $validate->addRule('email', 'email', true);
		  $validate->addRule('uname', 'string', true, 6, 20, Lang::$word->USERNAME);
		  $validate->run();

		  if(!empty($validate->safe->email) and !empty($validate->safe->uname)) {
			  if($row = self::$db->first(self::mTable, array("email", "fname", "lname", "username"), array('email' => $validate->safe->email))) {
				  if(Validator::sanitize($row->username) != Validator::sanitize($validate->safe->uname)) {
					  Message::$msgs['uname'] = Lang::$word->LOGIN_R6;
					  $json['type'] = 'error';
				  }
			  } else {
				  Message::$msgs['email'] = Lang::$word->LOGIN_R6;
				  $json['type'] = 'error';
			  }
		  }
		  
          if (empty(Message::$msgs)) {
			  $row = self::$db->first(self::mTable, array("email", "fname", "lname", "id"), array('email' => $validate->safe->email));
			  $salt = ''; 
			  $pass = substr(md5(uniqid(rand(), true)), 0, 10);
              $data = array(
					'hash' => App::get('Auth')->create_hash($pass, $salt), 
					'salt' => $salt,
			  );

			  $mailer = Mailer::sendMail();
			  $subject = Lang::$word->M_ACCSUBJECT2 . ' ' . $row->fname . ' ' . $row->lname;

			  ob_start();
			  require_once (BASEPATH . 'mailer/' . Core::$language . '/Member_Pass_Reset.tpl.php');
			  $html_message = ob_get_contents();
			  ob_end_clean();

			  $body = str_replace(array(
				  '[LOGO]',
				  '[FULLNAME]',
				  '[PASSWORD]',
				  '[DATE]',
				  '[COMPANY]',
				  '[PANEL]',
				  '[SITEURL]'), array(
				  Utility::getLogo(),
				  $row->fname . ' ' . $row->lname,
				  $pass,
				  date('Y'),
				  App::get("Core")->company,
				  Url::doUrl(URL_LOGIN),
				  SITEURL), $html_message);

			  $msg = Swift_Message::newInstance()
					->setSubject($subject)
					->setTo(array($row->email => $row->fname . ' ' . $row->lname))
					->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
					->setBody($body, 'text/html');

			  $numSent = $mailer->send($msg);
					  
              self::$db->update(self::mTable, $data, array('id' => $row->id));
			  Message::msgReply(self::$db->affected(), 'success', Lang::$word->PASSWORD_RES_D);
		  } else {
			  $json['type'] = 'error';
			  print json_encode($json);
		  }
      }
	  
      /**
       * Users::getAllMembers()
       * 
       * @param bool $from
	   * @param str $page
       * @return
       */
      public function getAllMembers($from = false, $page)
      {

          if (isset($_GET['letter']) and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
              $enddate = date("Y-m-d");
              $letter = Validator::sanitize($_GET['letter'], 'default', 2);
              $fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
              if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                  $enddate = Validator::sanitize($_POST['enddate_submit']);
              }
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::mTable . "` WHERE created BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND username REGEXP '^" . $letter . "'");
              $where = "WHERE m.created BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND m.fname REGEXP '^" . $letter . "'";

          } elseif (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
              if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                  $enddate = Validator::sanitize($_POST['enddate_submit']);
              }
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::mTable . "` WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . $enddate . " 23:59:59'");
              $where = "WHERE m.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";

          } elseif (isset($_GET['letter'])) {
              $letter = Validator::sanitize($_GET['letter'], 'default', 2);
              $where = "WHERE username REGEXP '^" . $letter . "'";
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::mTable . "` WHERE fname REGEXP '^" . $letter . "' LIMIT 1");
          } else {
              $counter = self::$db->count(self::mTable);
              $where = null;
          }

          if (isset($_GET['order'])) {
              list($sort, $order) = explode("/", $_GET['order']);
              $sort = Validator::sanitize($sort, "default", 14);
              $order = Validator::sanitize($order, "default", 4);
              if (in_array($sort, array(
                  "username",
                  "fname",
                  "email",
				  "membership_id",
                  "listings"))) {
                  $ord = ($order == 'DESC') ? " DESC" : " ASC";
                  $sorting = $sort . $ord;
              } else {
                  $sorting = " m.type DESC, m.created DESC";
              }
          } else {
              $sorting = " m.type DESC, m.created DESC";
          }
		  
          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = App::get("Core")->perpage;
          $pager->paginate();

          $sql = "
		  SELECT 
			m.*,
			CONCAT(fname, ' ', lname) AS fullname,
			mx.title as mtitle, membership_expire
		  FROM
			`" . self::mTable . "` AS m 
			LEFT JOIN `" . self::mxTable . "` as mx
			ON mx.id = m.membership_id 
		  $where 
		  ORDER BY $sorting" . $pager->limit; 

          $row = self::$db->pdoQuery($sql)->results();

          return ($row) ? $row : 0;

      }

      /**
       * Users::getAllUsers()
       * 
       * @return
       */
      public function getAllUsers()
      {
          $sql = "
		  SELECT 
			id,
			username,
			CONCAT(fname, ' ', lname) AS name 
		  FROM `" . self::mTable . "` 
		  WHERE active = ? 
		  ORDER BY username;";
          $row = self::$db->pdoQuery($sql, array('y'))->results();

          return ($row) ? $row : 0;
      }

      /**
       * Users::getAllDealers()
       * 
       * @return
       */
      public function getAllDealers()
      {
          $sql = "
		  SELECT 
			id,
			username
		  FROM `" . self::mTable . "` 
		  WHERE type = ? 
		  ORDER BY username;";
          $row = self::$db->pdoQuery($sql, array(1))->results();

          return ($row) ? $row : 0;
      }
	  
      /**
       * Users::getRoles()
       * 
       * @return
       */
      public function getRoles()
      {

          $row = self::$db->select(self::rTable)->results();

          return ($row) ? $row : 0;

      }

      /**
       * Users::getPrivileges()
       * 
       * @return
       */
      public function getPrivileges()
      {
          $sql = "
		  SELECT 
			rp.id,
			rp.active,
			p.description AS pdesc,
			p.id as prid,
			p.name,
			p.type,
			p.mode
		  FROM `" . self::rpTable . "` as rp 
			INNER JOIN `" . self::rTable . "` as r 
			  ON rp.rid = r.id 
			INNER JOIN `" . self::pTable . "` as p 
			  ON rp.pid = p.id 
		  WHERE rp.rid = ?
		  ORDER BY p.type;";

          $row = self::$db->pdoQuery($sql, array(Filter::$id))->results();

          return ($row) ? $row : 0;

      }

      /**
       * Users::getUserInvoice()
       * 
	   * @param int $pid
	   * @param int $uid
       * @return
       */
      public function getUserInvoice($pid, $uid)
      {
          $sql = "
		  SELECT 
		    p.*,
			DATE_FORMAT(p.created,'%Y%m%d') as invid,
			m.title,
			m.description,
			CONCAT(u.fname, ' ', u.lname) AS fullname,
			u.company,
			u.email,
			u.address,
			u.city,
			u.state,
			u.zip,
			u.phone,
			c.name AS country 
		  FROM `" . Content::txTable . "` AS p
			LEFT JOIN `" . Content::msTable . "` as m 
			  ON m.id = p.membership_id 
			LEFT JOIN `" . self::mTable . "` as u 
			  ON u.id = p.user_id
			LEFT JOIN `" . Content::cTable . "` as c 
			  ON c.abbr = u.country
		  WHERE p.id = ?
		  AND p.user_id = ?
		  AND p.status = 1;";
          $row = self::$db->pdoQuery($sql, array($pid, $uid))->result();

          return ($row) ? $row : 0;
      }

	  
      /**
       * Users::getInvoices()
       * 
       * @return
       */
      public function getInvoices()
	  {

          $row = self::$db->select(Content::inTable, null, array("user_id" => App::get('Auth')->uid), "ORDER BY created DESC")->results();
          
          return ($row) ? $row : 0;
      }
	  
     /**
       * Users::addListing()
       * 
       * @return
       */
      public function addListing()
      {
		  
          $validate = Validator::instance();
          $validate->addSource($_POST);
          $validate->addRule('location', 'numeric', true, 1, 11, Lang::$word->LST_ROOM);
          $validate->addRule('umake_id', 'numeric', true, 1, 11, Lang::$word->LST_MAKE);
          $validate->addRule('model_id', 'numeric', true, 1, 11, Lang::$word->LST_MODEL);
          $validate->addRule('price', 'float', true, 0, 0, Lang::$word->LST_PRICE);
          $validate->addRule('price_sale', 'float', false, 0, 0, Lang::$word->LST_DPRICE_S);
          $validate->addRule('year', 'numeric', true, 1, 4, Lang::$word->LST_YEAR);
          $validate->addRule('category', 'numeric', true, 1, 11, Lang::$word->LST_CAT);
          $validate->addRule('vcondition', 'numeric', true, 1, 11, Lang::$word->LST_COND);
          $validate->addRule('transmission', 'numeric', true, 1, 11, Lang::$word->LST_TRANS);
          $validate->addRule('fuel', 'numeric', true, 1, 11, Lang::$word->LST_FUEL);
          $validate->addRule('mileage', 'numeric', false);
          $validate->addRule('torque', 'string', false);
          $validate->addRule('color_e', 'string', true, 3, 20, Lang::$word->LST_EXTC);
          $validate->addRule('color_i', 'string', false);
          $validate->addRule('doors', 'numeric', false);
          $validate->addRule('drive_train', 'string', false);
          $validate->addRule('engine', 'string', false);
          $validate->addRule('top_speed', 'numeric', false);
          $validate->addRule('horse_power', 'string', false);
          $validate->addRule('towing_capacity', 'string', false);
          $validate->addRule('vin', 'string', false);
          $validate->addRule('stock_id', 'string', false);
          $validate->addRule('slug', 'string', false);
          $validate->addRule('title', 'string', false);  

		  if (empty($_FILES['thumb']['name'])) {
			  Message::$msgs['thumb'] = Lang::$word->LST_IMAGE;
		  }
		  
		  if (!empty($_FILES['thumb']['name']) and empty(Message::$msgs)) {
			  $upl = Upload::instance(3145728, "png,jpg");
			  $upl->process("thumb", UPLOADS . "listings/", false);
		  }
		  
		  $validate->run();
		  
          if (empty(Message::$msgs)) {
			  $usr = App::get('Users')->getUserPackage();
			  $mid = Items::doTitle($validate->safe->model_id);
              $data = array(
                  'user_id' => App::get('Auth')->uid,
                  'slug' => (empty($_POST['slug'])) ? $validate->safe->year . '-' . $mid : Url::doSeo($validate->safe->slug),
                  'nice_title' => ucwords(str_replace("-", " ", $mid)),
				  'location' => $validate->safe->location,
                  'stock_id' => $validate->safe->stock_id,
                  'vin' => $validate->safe->vin,
                  'make_id' => $validate->safe->umake_id,
                  'model_id' => $validate->safe->model_id,
                  'year' => $validate->safe->year,
                  'vcondition' => $validate->safe->vcondition,
                  'category' => $validate->safe->category,
                  'mileage' => $validate->safe->mileage,
                  'torque' => $validate->safe->torque,
                  'price' => $validate->safe->price,
                  'price_sale' => $validate->safe->price_sale,
                  'color_e' => $validate->safe->color_e,
                  'color_i' => $validate->safe->color_i,
                  'doors' => $validate->safe->doors,
                  'fuel' => $validate->safe->fuel,
                  'drive_train' => $validate->safe->drive_train,
                  'engine' => $validate->safe->engine,
                  'transmission' => $validate->safe->transmission,
                  'top_speed' => $validate->safe->top_speed,
                  'horse_power' => $validate->safe->horse_power,
                  'towing_capacity' => $validate->safe->towing_capacity,
                  'body' => $_POST['body'],
                  'expire' => $usr->membership_expire,
                  'status' => App::get('Core')->autoapprove,
				  'created' => Db::toDate(),
				  'idx' => Utility::randNumbers(),
				  'is_owner' => 0,
                  'featured' => $usr->featured
				  );

              if (isset($_POST['features'])) {
                  if (is_array($_POST['features'])) {
                      $data['features'] = Utility::implodeFields($_POST['features']);
                  }
              } else {
                  $data['features'] = "NULL";
              }
			    
              $data['title'] = (empty($_POST['title'])) ? str_replace("-", " ", $data['slug']) : $validate->safe->title;
			  
              if (empty($_POST['metakey']) or empty($_POST['metadesc'])) {
                  parseMeta::instance($_POST['body']);
                  if (empty($validate->safe->metakey)) {
                      $data['metakey'] = parseMeta::get_keywords();
                  }
                  if (empty($validate->safe->metadesc)) {
                      $data['metadesc'] = parseMeta::metaText($_POST['body']);
                  }
              }

              /* == Procces Image == */
              if (!empty($_FILES['thumb']['name'])) {
                  $maindir = UPLOADS . "listings/";
                  try {
                      $img = new Image($maindir . $upl->fileInfo['fname']);
                      $img->bestFit(400, 300)->save($maindir . 'thumbs/' . $upl->fileInfo['fname']);
                  }
                  catch (exception $e) {
					  Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
                  }
                  $data['thumb'] = $upl->fileInfo['fname'];
              }

              $last_id = self::$db->insert(Items::lTable, $data)->getLastInsertId();
			  
              // Process Gallery Images
			  $gallerytemp = App::get('Items')->getGalleryImages("-" . App::get('Auth')->uid);
			  if ($gallerytemp) {
				  foreach($gallerytemp as $grow) {
					  $pdata['listing_id'] = $last_id;
					  self::$db->update(Items::gTable, $pdata, array('id' => $grow->id));
				  }
				  
				  $gallery = App::get('Items')->getGalleryImages($last_id);
				  $gdata['gallery'] = serialize($gallery);
				  self::$db->update(Items::lTable, $gdata, array('id' => $last_id));
			  }

              // Add to listings info
			  $make_name = self::$db->getValueById(Content::mkTable, "name", $data['make_id']);
			  $category_name = self::$db->getValueById(Content::ctTable, "name", $data['category']);
			  $location_name = self::$db->first(Content::lcTable, null, array("id" => $data['location']));
              $idata = array(
                  'listing_id' => $last_id,
				  'make_name' => $make_name,
				  'make_slug' => Url::doSeo($make_name),
                  'model_name' => self::$db->getValueById(Content::mdTable, "name", $data['model_id']),
                  'location_name' => serialize($location_name),
				  'location_slug' => Url::doSeo($location_name->name_slug),
                  'condition_name' => self::$db->getValueById(Content::cdTable, "name", $data['vcondition']),
                  'category_name' => $category_name,
				  'category_slug' => Url::doSeo($category_name),
                  'fuel_name' => self::$db->getValueById(Content::fuTable, "name", $data['fuel']),
                  'trans_name' => self::$db->getValueById(Content::trTable, "name", $data['transmission']),
				  'color_name' => $validate->safe->color_e,
				  'special' => empty($validate->safe->price_sale) ? 0 : 1,
				  'lstatus' => App::get('Core')->autoapprove
				  );
				  self::$db->insert(Items::liTable, $idata);

              // Create image direcories
			  $picpath = UPLOADS . "listings/pics" . $last_id;
			  
			  File::makeDirectory($picpath . "/thumbs");
			  File::makeDirectory(UPLOADS . 'listings/pics-' . App::get('Auth')->uid . '/thumbs');
			  File::copyRemove(UPLOADS . 'listings/pics-' . App::get('Auth')->uid, $picpath);
			  
			  
			  if(App::get('Core')->autoapprove) {
				  $count = self::$db->count(Items::lTable, "user_id = " . App::get('Auth')->uid . " AND status = 1");
				  self::$db->update(self::mTable, array("listings" => $count), array("id" => App::get('Auth')->uid));
				  // Add to core
				  Items::doCalc();
				  
				  $json['type'] = "success";
				  $json['title'] = Lang::$word->SUCCESS;
				  $json['message'] = str_replace("[URL]", Url::doUrl(URL_ITEM, $data['idx'] . '/' . $data['slug']), Lang::$word->HOME_LISTADD_OK);
				  $json['redirect'] = Url::doUrl(URL_ADDLISTING);
			  } else {
				  $json['type'] = "success";
				  $json['title'] = Lang::$word->ALERT;
				  $json['message'] = Lang::$word->HOME_LISTADD_P;
				  $json['redirect'] = Url::doUrl(URL_ADDLISTING);  
			  }

			  print json_encode($json);
			  
			  //Admin Email Notification
			  if (App::get("Core")->notify_admin and Validator::sanitize(App::get("Core")->notify_email, "email")) {
				  $mailer = Mailer::sendMail();
				  $subject = Lang::$word->M_ACCSUBJECT4 . ' ' . App::get('Auth')->name;
	
				  ob_start();
				  require_once (BASEPATH . 'mailer/' . Core::$language . '/Admin_Notify_Submission.tpl.php');
				  $html_message = ob_get_contents();
				  ob_end_clean(); 
				  
				  $body = str_replace(array(
					  '[LOGO]',
					  '[USERNAME]',
					  '[NAME]',
					  '[EMAIL]',
					  '[LISTING]',
					  '[ID]',
					  '[IP]',
					  '[DATE]',
					  '[COMPANY]',
					  '[LOGINURL]',
					  '[SITEURL]'), array(
					  Utility::getLogo(),
					  App::get('Auth')->username,
					  App::get('Auth')->name,
					  App::get('Auth')->email,
					  $data['nice_title'],
					  $last_id,
					  Url::getIP(),
					  date('Y'),
					  App::get("Core")->company,
					  SITEURL . '/',
					  SITEURL), $html_message);
					  
				  $msg = Swift_Message::newInstance()
						->setSubject($subject)
						->setTo(array(App::get("Core")->notify_email => App::get("Core")->company))
						->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
						->setBody($body, 'text/html');
				  $mailer->send($msg);
			  }
			  
          } else {
              Message::msgSingleStatus();
          }

      }
	    
      /**
       * Users::adminPassReset()
       * 
       * @return
       */
      public function adminPassReset()
      {
		  $validate = Validator::instance();
		  $validate->addSource($_POST);

		  $validate->addRule('email', 'email', true);
		  $validate->addRule('uname', 'alpha', true, 4, 20, Lang::$word->USERNAME);
		  $validate->run();

		  if(isset($validate->safe->email) and isset($validate->safe->uname)) {
			  if(!$row = self::$db->first(self::aTable, array("email", "fname", "lname", "id"), array('username' => $validate->safe->uname, 'email' => $validate->safe->email))) {
				  Message::$msgs['username'] = Lang::$word->LOGIN_R6;
			  }
		  }

          if (empty(Message::$msgs)) {
			  $salt = ''; 
			  $pass = substr(md5(uniqid(rand(), true)), 0, 10);
              $data = array(
					'hash' => App::get('Auth')->create_hash($pass, $salt), 
					'salt' => $salt,
			  );

			  $mailer = Mailer::sendMail();
			  $subject = Lang::$word->M_ACCSUBJECT2 . ' ' . $row->fname . ' ' . $row->lname;

			  ob_start();
			  require_once (BASEPATH . 'mailer/' . Core::$language . '/Admin_Pass_Reset.tpl.php');
			  $html_message = ob_get_contents();
			  ob_end_clean();

			  $body = str_replace(array(
				  '[LOGO]',
				  '[FULLNAME]',
				  '[PASSWORD]',
				  '[DATE]',
				  '[COMPANY]',
				  '[PANEL]',
				  '[SITEURL]'), array(
				  Utility::getLogo(),
				  $row->fname . ' ' . $row->lname,
				  $pass,
				  date('Y'),
				  App::get("Core")->company,
				  ADMINURL,
				  SITEURL), $html_message);

			  $msg = Swift_Message::newInstance()
					->setSubject($subject)
					->setTo(array($row->email => $row->fname . ' ' . $row->lname))
					->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
					->setBody($body, 'text/html');

			  $numSent = $mailer->send($msg);
					  
              self::$db->update(self::aTable, $data, array('id' => $row->id));
			  Message::msgReply(self::$db->affected(), 'success', Lang::$word->PASSWORD_RES_D);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }

      /**
       * Users::getUserPackage()
       * 
       * @return
       */
      public function getUserPackage()
      {

          $sql = "
		  SELECT 
			u.*,
			m.title, m.listings as total, m.featured
		  FROM
			`" . self::mTable . "` AS u
			LEFT JOIN `" . Content::msTable . "` AS m
			  ON m.id = u.membership_id 
		  WHERE u.id = ?;";
          $row = self::$db->pdoQuery($sql, array(App::get("Auth")->uid))->result();

          return ($row) ? $row : 0;
      }
	  
      /**
       * Users::checkAcl()
       * 
       * @return
       */
      public static function checkAcl()
      {

          $acctypes = func_get_args();
          foreach ($acctypes as $type) {
              $args = explode(',', $type);
              foreach ($args as $arg) {
                  if (App::get("Auth")->usertype == $arg)
                      return true;
              }
          }
          return false;
      }


      /**
       * Users::getMemberships()
       * 
       * @return
       */
      public function getMemberships()
      {

		  $row = self::$db->select(self::mxTable, null, null, "ORDER BY price")->results();
          return ($row) ? $row : 0;
      }
	  
      /**
       * Users::calculateDays()
       * 
       * @return
       */
      public static function calculateDays($membership_id, $format = false)
      {

          $now = Db::toDate();
          $row = self::$db->first(self::mxTable, array('days', 'period'), array('id' => $membership_id));
          if ($row) {
              switch ($row->period) {
                  case "D":
                      $diff = $row->days;
                      break;
                  case "W":
                      $diff = $row->days * 7;
                      break;
                  case "M":
                      $diff = $row->days * 30;
                      break;
                  case "Y":
                      $diff = $row->days * 365;
                      break;
              }
              $expire = $format ? Utility::doDate("long_date", date("Y-m-d H:i:s", strtotime($now . + $diff . " days"))) : date("Y-m-d H:i:s", strtotime($now . + $diff . " days"));
          } else {
              $expire = "0000-00-00 00:00:00";
          }
          return $expire;
      }
	  
      /**
       * Users::accountLevelToType()
       * 
       * @param mixed $level
       * @return
       */
      public static function accountLevelToType($level)
      {
          switch ($level) {
              case 9:
                  return "owner";

              case 8:
                  return "admin";

              case 7:
                  return "editor";

              case 5:
                  return "manager";
          }
      }

      /**
       * Users::renderAccountTypes()
       * 
       * @param bool $selected
       * @return
       */
      public static function renderAccountTypes($selected = false)
      {
          $arr = array(
              '8' => Lang::$word->LEV8,
              '7' => Lang::$word->LEV7
              );

          $html = '';
          foreach ($arr as $key => $val) {
              $html .= "<option value=\"$key\"";
              $html .= ($key == $selected) ? ' selected="selected"' : '';
              $html .= ">$val</option>\n";
          }

          unset($val);
          return $html;
      }

      /**
       * Users::accountType()
       * 
       * @param mixed $level
       * @return
       */
      public static function accountType($level)
      {
          switch ($level) {
              case 9:
                  return '<i class="icon badge"></i> ' . Lang::$word->LEV9;

              case 8:
                  return '<i class="icon trophy"></i> ' . Lang::$word->LEV8;

              case 7:
                  return '<i class="icon note"></i> ' . Lang::$word->LEV7;

              case 5:
                  return '<i class="icon user"></i> ' . Lang::$word->LEV5;
          }
      }

      /**
       * Users::accountTypeIcon()
       * 
       * @param mixed $level
       * @return
       */
      public static function accountTypeIcon($level)
      {
          switch ($level) {
              case 9:
                  return '<i class="rounded icon badge" data-tooltip-options=\'{"direction":"right"}\' data-content="' . Lang::$word->LEV9 . '"></i>';

              case 8:
                  return '<i class="rounded icon trophy" data-tooltip-options=\'{"direction":"right"}\' data-content="' . Lang::$word->LEV8 . '"></i>';

              case 7:
                  return '<i class="rounded icon note" data-tooltip-options=\'{"direction":"right"}\' data-content="' . Lang::$word->LEV7 . '"></i>';

              case 5:
                  return '<i class="rounded icon user" data-tooltip-options=\'{"direction":"right"}\' data-content="' . Lang::$word->LEV5 . '"></i>';
          }
      }


  }