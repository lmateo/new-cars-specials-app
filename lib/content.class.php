<?php
  /**
   * Content Class
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: content.class.php, v1.10 2017-08-28 8:33:05 gewa Exp $
   * @sub-author Lorenzo Mateo
   * @copyright 2017
   * Add functions assoiciated with web-specials dropdowns
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');


  class Content
  {

      const cTable = "countries";
	  const blTable = "banlist";
	  const pgTable = "pages";
	  const muTable = "menus";
	  const faqTable = "faq";
	  const ctTable = "categories";
	  const cdTable = "conditions";
	  const fTable = "features";
	  const fuTable = "fuel";
	  const trTable = "transmissions";
	  const mkTable = "makes";
	  const mdTable = "models";
	  const mkwsTable = "makes_ws";
	  const mdwsTable = "models_ws";
	  const lcTable = "locations";
	  const slTable = "slider";
	  const dcTable = "coupons";
	  const rwTable = "reviews";
	  const nwaTable = "news";
	  const gwTable = "gateways";
	  const msTable = "memberships";
	  const nwTable = "newsletter";
	  const txTable = "payments";
	  const inTable = "invoices";
	  const dtTable = "dealtype";
	  const stTable = "specialstype";
	  const bsTable = "bodystyle";
	  const zsTable = "zerosingle";
	  const yTable = "year";
	  const sTable = "stores";
	 

	  public $cattree = array();
	  public $catlist = array();
	  
	  private static $db;


      /**
       * Content::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          self::$db = Db::run();

      }
	  
      /**
       * Content::getCountryList()
       * 
       * @return
       */
      public function getCountryList()
      {

		  $row = self::$db->select(self::cTable, null, null, "ORDER BY sorting DESC")->results();

          return ($row) ? $row : 0; 

      }
	  
      /**
       * Content::processCountry()
       * 
       * @return
       */
      public function processCountry()
      {
		  $validate = Validator::instance();
		  $validate->addSource($_POST);

		  $validate->addRule('name', 'string', true, 2, 20, Lang::$word->NAME);
		  $validate->addRule('abbr', 'string', true, 2, 2, Lang::$word->CNT_ABBR);
		  $validate->addRule('sorting', 'numeric', false, 1, 1000);
		  $validate->addRule('active', 'numeric', false, 1, 1);
		  $validate->run();
		  
          if (empty(Message::$msgs)) {
              $data = array(
					'name' => $validate->safe->name, 
					'abbr' => $validate->safe->abbr, 
					'active' => $validate->safe->active,
					'home' => $_POST['home'],
					'vat' => $_POST['vat'],
					'sorting' => $validate->safe->sorting,
			  );

			  if ($data['home'] == 1) {
				  self::$db->pdoQuery("UPDATE `" . self::cTable . "` SET `home`= DEFAULT(home);");
			  }	
  
              self::$db->update(self::cTable, $data, array('id' => Filter::$id));
			  Message::msgReply(self::$db->affected(), 'success', Lang::$word->CNT_UPDATED);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
	  
      /**
       * Content::getBanList()
       * 
       * @return
       */
	  public function getBanList()
	  {
		  if (isset($_GET['sort'])) {
			  $sort = Validator::sanitize($_GET['sort'], "alpha", 5);
			  if ($sort == "ip" or $sort == "email") {
				  if ($sort == "ip") {
					  $where = "WHERE type = 'IP'";
				  }
				  if ($sort == "email") {
					  $where = "WHERE type = 'Email'";
				  }
			  } else {
				  $where = null;
			  }
			  Debug::addMessage('params', 'sort', print_r($_GET['sort'], true));
		  } else {
			  $where = null;
		  }
	
		  $sql = "
			  SELECT id, item,
				CASE
				  WHEN type = 'IP' 
				  THEN '" . Lang::$word->IP . "' 
				  WHEN type = 'Email' 
				  THEN '" . Lang::$word->EMAIL . "' 
				  ELSE 'Unknown' 
				END type, comment
			  FROM `" . self::blTable . "` 
			  $where";
		  $row = self::$db->pdoQuery($sql)->results();
	
		  return ($row) ? $row : 0;
	  }
	  
      /**
       * Content::processBan()
       * 
       * @return
       */
      public function processBan()
      {
		  $validate = Validator::instance();
		  $validate->addSource($_POST);

		  $validate->addRule('type', 'string', true, 2, 20, Lang::$word->BL_ITEM);
		  $validate->addRule('item', 'string', true, 4, 100, Lang::$word->BL_TYPE);
		  $validate->addRule('comment', 'string', false);

		  if($_POST['type'] == "IP") {
			  $validate->addRule('item', 'ipv4', true);
		  } else {
			  $validate->addRule('item', 'email', true);
		  }
		  $validate->run();
          if (empty(Message::$msgs)) {
              $data = array(
					'type' => $validate->safe->type, 
					'item' => $validate->safe->item, 
					'comment' => $validate->safe->comment
					
			  );
              self::$db->insert(self::blTable, $data);
			  Message::msgReply(self::$db->getLastInsertId(), 'success', Lang::$word->BL_ADDED);

		  } else {
			  Message::msgSingleStatus();
		  }

	  }

      /**
       * Content::processEmail()
       * 
       * @return
       */
	  public function processEmail()
	  {
	
		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('subject', 'string', true, 3, 150, Lang::$word->EMN_REC_SUBJECT);
		  $validate->addRule('recipient', 'string', true, 3, 150, Lang::$word->EMN_REC_SEL);
		  $validate->addRule('from', 'email');
		  $validate->run();
	
		  if (empty(Message::$msgs)) {
			  $to = Validator::sanitize($_POST['recipient']);
			  $subject = Validator::cleanOut($_POST['subject']);
			  $body = Validator::cleanOut($_POST['body']);
			  $numSent = 0;
			  $failedRecipients = array();
	
			  switch ($to) {
				  case "members":
					  $mailer = Mailer::sendMail();
					  $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));
	
					  $userrow = self::$db->select(Users::mTable, array('email', 'CONCAT(fname," ",lname) as name'), array('active'=>'y'))->results();
	
					  $replacements = array();
					  if ($userrow) {
						  foreach ($userrow as $cols) {
							  $replacements[$cols->email] = array(
								  '[COMPANY]' => App::get("Core")->company,
								  '[LOGO]' => Utility::getLogo(),
								  '[NAME]' => $cols->name,
								  '[SITEURL]' => SITEURL,
								  '[DATE]' => date('Y'));
						  }
	
						  $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
						  $mailer->registerPlugin($decorator);
	
						  $message = Swift_Message::newInstance()
									->setSubject($subject)
									->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
									->setBody($body, 'text/html');
	
						  foreach ($userrow as $row) {
							  $message->setTo(array($row->email => $row->name));
							  $numSent++;
							  $mailer->send($message, $failedRecipients);
						  }
						  unset($row);
					  }
					  break;
	
				  case "staff":
					  $mailer = Mailer::sendMail();
					  $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));
	
					  $userrow = self::$db->select(Users::aTable, array('email', 'CONCAT(fname," ",lname) as name'), array('userlevel <>'=>9))->results();
	
					  $replacements = array();
					  if ($userrow) {
						  foreach ($userrow as $cols) {
							  $replacements[$cols->email] = array(
								  '[COMPANY]' => App::get("Core")->company,
								  '[LOGO]' => Utility::getLogo(),
								  '[NAME]' => $cols->name,
								  '[URL]' => SITEURL,
								  '[DATE]' => date('Y'));
						  }
	
						  $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
						  $mailer->registerPlugin($decorator);
	
						  $message = Swift_Message::newInstance()
									->setSubject($subject)
									->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
									->setBody($body, 'text/html');
	
						  foreach ($userrow as $row) {
							  $message->setTo(array($row->email => $row->name));
							  $numSent++;
							  $mailer->send($message, $failedRecipients);
						  }
						  unset($row);
	
					  }
					  break;
	
				  case "sellers":
					  $mailer = Mailer::sendMail();
					  $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));

					  $userrow = self::$db->select(Users::mTable, array('email', 'CONCAT(fname," ",lname) as name'), array('listings >'=>0))->results();
	
					  $replacements = array();
					  if ($userrow) {
						  foreach ($userrow as $cols) {
							  $replacements[$cols->email] = array(
								  '[COMPANY]' => App::get("Core")->company,
								  '[LOGO]' => Utility::getLogo(),
								  '[NAME]' => $cols->name,
								  '[URL]' => SITEURL,
								  '[DATE]' => date('Y'));
						  }
	
						  $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
						  $mailer->registerPlugin($decorator);
	
						  $message = Swift_Message::newInstance()
									->setSubject($subject)
									->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
									->setBody($body, 'text/html');
	
						  foreach ($userrow as $row) {
							  $message->setTo(array($row->email => $row->name));
							  $numSent++;
							  $mailer->send($message, $failedRecipients);
						  }
						  unset($row);
					  }
					  break;

				  case "newsletter":
					  $mailer = Mailer::sendMail();
					  $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));

					  $userrow = self::$db->select(self::nwTable, array('email', 'name'))->results();
	
					  $replacements = array();
					  if ($userrow) {
						  foreach ($userrow as $cols) {
							  $replacements[$cols->email] = array(
								  '[COMPANY]' => App::get("Core")->company,
								  '[LOGO]' => Utility::getLogo(),
								  '[NAME]' => $cols->name,
								  '[URL]' => SITEURL,
								  '[DATE]' => date('Y'));
						  }
	
						  $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
						  $mailer->registerPlugin($decorator);
	
						  $message = Swift_Message::newInstance()
									->setSubject($subject)
									->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
									->setBody($body, 'text/html');
	
						  foreach ($userrow as $row) {
							  $message->setTo(array($row->email => $row->name));
							  $numSent++;
							  $mailer->send($message, $failedRecipients);
						  }
						  unset($row);
					  }
					  break;
					  
					case "webspecials":
						$mailer = Mailer::sendMail();
						$mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));
						$wstable = wSpecials::w_sTable;
						$wschtable = wSpecials::wschTable;
						
						$dataws = self::$db->pdoQuery("SELECT *, DATE_FORMAT(modified_ws, '%Y-%m-%d') FROM `" . $wstable . "` WHERE DATE(modified_ws) = CURDATE() AND update_flag = 1")->results();
						$datawsch = self::$db->pdoQuery("SELECT 
				      			ws.*,
								wsch.*,
				       		    CASE WHEN wsch.col ='Active on New Car Specials Page' AND wsch.OldValue = 1
				                                       THEN 'Active'
				                                       WHEN wsch.col ='Active on New Car Specials Page' AND wsch.OldValue = 0
				                                       THEN 'Not Active'
				                                        WHEN wsch.col ='Active on Lease Specials Page' AND wsch.OldValue = 1
				                                       THEN 'Active'
				                                       WHEN wsch.col ='Active on Lease Specials Page' AND wsch.OldValue = 0
				                                       THEN 'Not Active'
				                                       WHEN wsch.col ='Active on Zero Down Lease Specials Page' AND wsch.OldValue = 1
				                                       THEN 'Active'
				                                       WHEN wsch.col ='Active on Zero Down Lease Specials Page' AND wsch.OldValue = 0
				                                       THEN 'Not Active'
													   ELSE wsch.OldValue
				                                       END AS changeFrom,
				       		                           CASE 
				                                       WHEN wsch.col ='Active on New Car Specials Page' AND wsch.NewValue = 1
				                                       THEN 'Active'
				                                       WHEN wsch.col ='Active on New Car Specials Page' AND wsch.NewValue = 0
				                                       THEN 'Not Active'
				                                        WHEN wsch.col ='Active on Lease Specials Page' AND wsch.NewValue = 1
				                                       THEN 'Active'
				                                       WHEN wsch.col ='Active on Lease Specials Page' AND wsch.NewValue = 0
				                                       THEN 'Not Active'
				                                       WHEN wsch.col ='Active on Zero Down Lease Specials Page' AND wsch.NewValue = 1
				                                       THEN 'Active'
				                                       WHEN wsch.col ='Active on Zero Down Lease Specials Page' AND wsch.NewValue = 0
				                                       THEN 'Not Active'
													   ELSE wsch.NewValue
				                                       END AS changeTo, 
				      			CONCAT( u.fname, ' ',u.lname) AS username,
								DATE_FORMAT(wsch.modified, '%b-%d-%Y') AS modifiedDATE,
								DATE_FORMAT(wsch.modified, '%r') AS modifiedTIME,
								DATE_FORMAT(wsch.modified, '%W') AS modifiedWKDAY
				      			FROM `" . $wschtable . "` AS wsch
				      			LEFT JOIN `" . Users::aTable . "` AS u 
							    	 ON u.id = wsch.modified_id
								LEFT JOIN `" . $wstable . "` AS ws
			 				         ON ws.webspecials_id = wsch.webspecials_id 
				            			WHERE DATE(wsch.modified) = CURDATE()
				            			ORDER BY wsch.id DESC")->results();
						
						
						$webspecialAlertimage ='<img src="'.SITEURL.'/uploads/COLORADO.png" alt="" width="200" height="100">';
						$webspecialAlertimagegif = '<img src="'.SITEURL.'/admin/assets/images/highAlertIcon.gif " alt="" height="40" width="40">';
						$webspecialAlerthtml = '';
						if ($datawsch) {
						foreach ($datawsch as $row) {
							$webspecialAlerthtml .= '
								<tr>
								<td><b>' .  $row->title_ws . '</b> with Stock Number: <b>' .  $row->stock_number . '</b> was changed on <br/> <b>' . $row->modifiedWKDAY . ' </b> <b>' . $row->modifiedDATE . ' </b>
								
								at <b>' . $row->modifiedTIME . '</b> by <b>'.$row->username.' </b>
										
								<br/>
								<br/>
										
								The field <b>'.$row->col.'</b>	was changed <br/>
								
								Old Value: <b>'.$row->changeFrom.'</b> <br/>
								
								<span style="font-weight:bold">*New Value*:</span> <span style="color:red;font-weight:bold">'.$row->changeTo.'</span>
								<hr>
								</td>
								</tr>		';
							
									
						}
						}else {
			  
							$webspecialAlerthtml .= '
								<tr>
								<td> <b> NO Web Specials Alerts at this Time.</b>
								</td>
								</tr>';
		             }
						
						
						
						$userrow = self::$db->select(Users::aTable, array('email', 'CONCAT(fname," ",lname) as name'), array('webspecialsalert'=>'y'))->results();
						
						$replacements = array();
						if ($userrow) {
							foreach ($userrow as $cols) {
								$replacements[$cols->email] = array(
										'[COMPANY]' => App::get("Core")->company,
										'[LOGO]' => Utility::getLogo(),
										'[NAME]' => $cols->name,
										'[URL]' => SITEURL . '/admin/',
										'[DATE]' => date('Y'),
										'[WEBSPECIALSALERTDATA]' => $webspecialAlerthtml,
										'[WEBSPECIALSALERTIMAGE]' => $webspecialAlertimage,
										'[WEBSPECIALSALERTIMAGEGIF]' => $webspecialAlertimagegif,
										'[SITEURL]' => SITEURL . '/admin/'
										
										
								);
							}
						
							$decorator = new Swift_Plugins_DecoratorPlugin($replacements);
							$mailer->registerPlugin($decorator);
						
							$message = Swift_Message::newInstance()
							->setSubject($subject)
							->setFrom(array(App::get("Core")->webspecials_email => App::get("Core")->company))
							->setBody($body, 'text/html');
						
							foreach ($userrow as $row) {
								$message->setTo(array($row->email => $row->name));
								$numSent++;
								$mailer->send($message, $failedRecipients);
							}
							unset($row);
						
						}
						break;
					  	
					  
				  default:
					  $mailer = Mailer::sendMail();
					  $table = isset($_POST['clients']) ? Users::mTable : Users::aTable;
					  $row = self::$db->pdoQuery("SELECT email, CONCAT(fname,' ',lname) as name FROM `" . $table . "` WHERE email LIKE '%" . Validator::sanitize($to) . "%'")->result();
					  if ($row) {
						  $newbody = str_replace(array(
							  '[COMPANY]',
							  '[LOGO]',
							  '[NAME]',
							  '[URL]',
							  '[DATE]'), array(
							  App::get("Core")->company,
							  Utility::getLogo(),
							  $row->name,
							  SITEURL,
							  date('Y')), $body);
	
						  $message = Swift_Message::newInstance()
									->setSubject($subject)->setTo(array($to => $row->name))
									->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
									->setBody($newbody, 'text/html');
	
						  $numSent++;
						  $mailer->send($message, $failedRecipients);
					  }
					  break;
			  }
	
			  if ($numSent) {
				  $json['type'] = 'success';
				  $json['title'] = Lang::$word->SUCCESS;
				  $json['message'] = $numSent . ' ' . Lang::$word->EMN_SENT;
			  } else {
				  $json['type'] = 'error';
				  $json['title'] = Lang::$word->ERROR;
				  $res = '';
				  $res .= '<ul>';
				  foreach ($failedRecipients as $failed) {
					  $res .= '<li>' . $failed . '</li>';
				  }
				  $res .= '</ul>';
				  $json['message'] = Lang::$word->EMN_ALERT . $res;
	
				  unset($failed);
			  }
			  print json_encode($json);
	
		  } else {
			  Message::msgSingleStatus();
		  }
	
	  }
	  
      /**
       * Content::getPages()
       * 
       * @return
       */
      public function getPages()
      {
		  
		  $row = self::$db->select(self::pgTable)->results();

          return ($row) ? $row : 0;

      }

      /**
       * Content::renderPage()
       * 
       * @return
       */
      public function renderPage()
      {
		  
		  $row = self::$db->select(self::pgTable, "*", array("slug" => App::get('Core')->_url[1], "active" => 1, "home_page" => 0))->result();

          return ($row) ? $row : 0;

      }
	  
      /**
       * Content::processPage()
       * 
       * @return
       */
      public function processPage()
      {
		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('title', 'string', true, 3, 150, Lang::$word->PAG_NAME);
		  $validate->addRule('slug', 'string', false);
		  $validate->addRule('created_submit', 'date', false);
		  $validate->addRule('active', 'numeric', false, 1, 1);
		  $validate->run();
		  
          if (empty(Message::$msgs)) {
              $data = array(
					'title' => $validate->safe->title, 
					'slug' => (empty($_POST['slug'])) ? Url::doSeo($validate->safe->title) : Url::doSeo($validate->safe->slug),
					'body' => $_POST['body'],
					'created' => empty($validate->safe->created_submit) ? Db::toDate() : $validate->safe->created_submit, 
					'contact' => $_POST['contact'], 
					'faq' => $_POST['faq'], 
					'home_page' => $_POST['home_page'], 
					'active' => $validate->safe->active
			  );
              
			  
			  if ($data['home_page'] == 1) {
				  self::$db->pdoQuery("UPDATE `" . self::pgTable . "` SET `home_page`= DEFAULT(home_page);");
				  $sdata['home_content'] = $data['body'];
				  self::$db->update(Core::sTable, $sdata, array('id' => 1));
			  }	
			  
			  if ($data['contact'] == 1) {
				  self::$db->pdoQuery("UPDATE `" . self::pgTable . "` SET `contact`= DEFAULT(contact);");
			  }	
			  
			  if ($data['faq'] == 1) {
				  self::$db->pdoQuery("UPDATE `" . self::pgTable . "` SET `faq`= DEFAULT(faq);");
			  }	
			  
			  (Filter::$id) ? self::$db->update(self::pgTable, $data, array('id' => Filter::$id)) : self::$db->insert(self::pgTable, $data);
			  $message = (Filter::$id) ? Lang::$word->PAG_UPDATED : Lang::$word->PAG_ADDED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
		  } else {
			  Message::msgSingleStatus();
		  }
      }

      /**
       * Content::getMenus()
       * 
       * @return
       */
      public function getMenus()
      {
		  
		  $row = self::$db->select(self::muTable, "*", null, "ORDER BY position")->results();

          return ($row) ? $row : 0;

      }

      /**
       * Content::renderMenus()
       * 
       * @return
       */
      public function renderMenus()
      {

		  $sql = "
		  SELECT 
			m.*,
			p.slug AS pslug,
			p.home_page 
		  FROM
			`" . self::muTable . "` AS m 
			LEFT JOIN `" . self::pgTable . "` AS p 
			  ON m.page_id = p.id 
		  WHERE m.active = ? 
		  ORDER BY m.position;";
		  
		  $row = self::$db->pdoQuery($sql, array(1))->results();

          return ($row) ? $row : 0;

      }
	  
	  /**
	   * Content::processMenu()
	   * 
	   * @return
	   */
	  public function processMenu()
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('name', 'string', true, 3, 150, Lang::$word->MENU_NAME);
		  $validate->addRule('content_type', 'string', true, 3, 20, Lang::$word->MENU_TYPE);
		  $validate->addRule('active', 'numeric', false, 1, 1);
		  $validate->run();
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'name' => $validate->safe->name, 
				  'slug' => Url::doSeo($validate->safe->name),
				  'page_id' => ($_POST['content_type'] == "web") ? 0 : intval($_POST['page_id']),
				  'content_type' => $validate->safe->content_type,
				  'link' => (!empty($_POST['web'])) ? Validator::sanitize($_POST['web']) : "NULL",
				  'target' => (!empty($_POST['target'])) ? Validator::sanitize($_POST['target']) : "_self",
				  'active' => $validate->safe->active
			  );

			  (Filter::$id) ? self::$db->update(self::muTable, $data, array('id' => Filter::$id)) : self::$db->insert(self::muTable, $data);
			  $message = (Filter::$id) ? Lang::$word->MENU_UPDATED : Lang::$word->MENU_ADDED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
		  } else {
			  Message::msgSingleStatus();
		  }
      }
	  
      /**
       * Content::getSortMenuList()
       * 
       * @return
       */
	  public function getSortMenuList()
	  {
		  
		  if ($menurow = self::$db->select(self::muTable, null, null, 'ORDER BY position')->results()) {
			  print "<ol class=\"dd-list lagre\">\n";
			  foreach ($menurow as $row) {
				  print '
				    <li data-id="' . $row->id . '" class="dd-item dd3-item">'
				  .'<div class="dd-handle dd3-handle"></div>' 
				  .'<div class="dd3-content"><a href="' . Url::adminUrl("menus", "edit", false,"?id=" . $row->id) . '">' . $row->name . '</a>' 
				  .'<span><a class="delete" data-set=\'{"title": "' . Lang::$word->MENU_DELETE . '", "parent": "li", "option": "deleteMenu", "id": ' . $row->id . ', "name": "' . $row->name . '"}\'><i class="icon negative delete"></i></a></span></div>';
				  print "</li>\n";
			  }
		  }
		  unset($row);
		  print "</ol>\n";
	  }
	  
      /**
       * Content::getContentType()
       * 
	   * @param bool $selected
       * @return
       */ 	  
      public static function getContentType($selected = false)
	  {
		  $arr = array(
				'page' => Lang::$word->MENU_CPAGE,
				'web' => Lang::$word->MENU_ELINK
		  );
		  
		  $contenttype = '';
		  foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $contenttype .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $contenttype .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $contenttype;
      } 
	  
      /**
       * Content::getFaq()
       * 
       * @return
       */
      public function getFaq()
      {
		  
		  $row = self::$db->select(self::faqTable, "*", null, 'ORDER BY sorting')->results();
          return ($row) ? $row : 0;

      }
	  
	  /**
	   * Content::processFaq()
	   * 
	   * @return
	   */
	  public function processFaq()
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('question', 'string', true, 5, 200, Lang::$word->FAQ_NAME);
		  $validate->run();
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'question' => $validate->safe->question, 
				  'answer' => $_POST['answer']
			  );

			  (Filter::$id) ? self::$db->update(self::faqTable, $data, array('id' => Filter::$id)) : self::$db->insert(self::faqTable, $data);
			  $message = (Filter::$id) ? Lang::$word->FAQ_UPDATED : Lang::$word->FAQ_ADDED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
		  } else {
			  Message::msgSingleStatus();
		  }
      }

      /**
       * Content::getNews()
       * 
       * @return
       */
      public function getNews()
      {
		  
		  $row = self::$db->select(self::nwaTable, "*", null, 'ORDER BY created DESC')->results();
          return ($row) ? $row : 0;

      }

      /**
       * Content::renderNews()
       * 
       * @return
       */
      public function renderNews()
      {
		  
		  $row = self::$db->first(self::nwaTable, "*", array("active" => 1));
          return ($row) ? $row : 0;

      }
	  
      /**
       * Content::processNews()
       * 
       * @return
       */
      public function processNews()
      {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('title', 'string', true, 3, 100, Lang::$word->NWA_NAME);
		  $validate->run();
		  
          if (empty(Message::$msgs)) {
              $data = array(
					'title' => $validate->safe->title, 
					'body' => $_POST['body'], 
					'created' => Db::toDate(), 
					'author' => App::get('Auth')->username,
					'active' => isset($_POST['active']) ? 1 : 0,
			  );

			  if ($data['active'] == 1) {
				  self::$db->pdoQuery("UPDATE `" . self::nwaTable . "` SET `active`= DEFAULT(active);");
			  }	
			  
			  (Filter::$id) ? self::$db->update(self::nwaTable, $data, array('id' => Filter::$id)) : self::$db->insert(self::nwaTable, $data);
			  $message = (Filter::$id) ? Lang::$word->NWA_UPDATED : Lang::$word->NWA_ADDED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
		  } else {
			  Message::msgSingleStatus();
		  }
      }
			  
      /**
       * Content::getFeatures()
       * 
       * @return
       */
      public function getFeatures()
      {
		  $row = self::$db->select(self::fTable, "*", null, 'ORDER BY sorting')->results();
          return ($row) ? $row : 0;
      }

      /**
       * Content::getFeaturesById()
       * 
       * @return
       */
      public function getFeaturesById($id)
      {
          $ids = ($id) ? $id : 0;
          $sql = "SELECT * FROM `" . self::fTable . "` WHERE id IN(" . $ids . ") ORDER BY name";
          $row = self::$db->pdoQuery($sql)->results();

          return ($row) ? $row : 0;
      }
	  
	  /**
	   * Content::processFeature()
	   * 
	   * @return
	   */
	  public function processFeature()
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('name', 'string', true, 3, 200, Lang::$word->FEAT_NAME);
		  $validate->run();
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'name' => $validate->safe->name, 
			  );

			  self::$db->insert(self::fTable, $data);
			  Message::msgReply(self::$db->affected(), 'success', str_replace("[NAME]", $data['name'], Lang::$word->FEAT_ADDED));
		  } else {
			  Message::msgSingleStatus();
		  }
      }
      
      /**
       * Content::getModelsws()
       *
       * @return
       */
      public function getModelsws()
      {
      
      	if (Filter::$id) {
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::mdwsTable . "` WHERE make_id = " . Filter::$id . " LIMIT 1");
      		$where = "WHERE md.make_id = " . Filter::$id;
      	} elseif(isset($_POST['find'])) {
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::mdwsTable . "` WHERE name LIKE '%" . Validator::sanitize($_POST['find']) . "%' LIMIT 1");
      		$where = "WHERE md.name LIKE '%" . Validator::sanitize($_POST['find']) . "%'";
      	} else {
      		$counter = self::$db->count(self::mdTable);
      		$where = null;
      	}
      
      	$pager = Paginator::instance();
      	$pager->items_total = $counter;
      	$pager->default_ipp = App::get("Core")->perpage;
      	$pager->path = Url::adminUrl("models", false, false, "?");
      	$pager->paginate();
      
      	$sql = "
		  SELECT
			md.id AS mdid,
			md.name AS mdname,
			mk.name AS mkname
		  FROM
			`" . self::mdwsTable . "` AS md
			LEFT JOIN `" . self::mkwsTable . "` AS mk
      			ON mk.id = md.make_id
      			$where
      			ORDER BY md.name
      			" . $pager->limit;
      			$row = self::$db->pdoQuery($sql)->results();
      
      			return ($row) ? $row : 0;
      }
      
      /**
       * Content::getModelList()
       *
       * @return
       */
      public function getModelListws($make_id)
      {
      	$row = self::$db->select(self::mdwsTable, "*", array("make_id" => $make_id), 'ORDER BY name')->results();
      	return ($row) ? $row : 0;
      }
       
      /**
       * Content::processModel()
       *
       * @return
       */
      public function processModelws()
      {
      	if(empty($_POST['id']))
      		$err = Message::$msgs['id'] = Lang::$word->MAKE_NAME_R;
      
      		$name = array_filter($_POST['modelname'], 'strlen');
      		if (empty($name))
      			$err = Message::$msgs['answer'] = Lang::$word->MODL_NAME_R;
      
      			if (empty(Message::$msgs)) {
      				$makename = self::$db->first(self::mkwsTable, array("name"), array('id' => Filter::$id));
      				$html = '';
      				foreach ($_POST['modelname'] as $key => $val) {
      					$data = array('name' => Validator::sanitize($_POST['modelname'][$key]), 'make_id' => Filter::$id);
      					$last_id = self::$db->insert(self::mdwsTable, $data)->getLastInsertId();
      
      					$html .= '
				  <tr>
					<td class="warning"><small>' . $last_id . '.</small></td>
					<td>' . $makename->name . '</td>
					<td data-editable="true" data-set=\'{"type": "model", "id": ' . $last_id . ',"key":"name", "path":""}\'>' . $data['name'] . '</td>
					<td><a class="delete" data-set=\'{"title": "' . Lang::$word->MODL_DEL . '", "parent": "tr", "option": "deleteModelws", "id": ' . $last_id . ', "name": "' . $data['name'] . '"}\'><i class="rounded outline icon negative trash link"></i></a></td>
				  </tr>';
      				}
      				$json = array(
      				  'type' => 'success',
      				  'title' => Lang::$word->SUCCESS,
      				  'data' => $html,
      				  'message' => Lang::$word->MODL_ADDED
      				);
      				print json_encode($json);
      
      			} else {
      				$json['type'] = 'error';
      				$json['title'] = Lang::$word->ERROR;
      				$json['message'] = $err;
      				print json_encode($json);
      			}
      
      }

      /**
       * Content::getModels()
       * 
       * @return
       */
      public function getModels()
      {

          if (Filter::$id) {
			  $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::mdTable . "` WHERE make_id = " . Filter::$id . " LIMIT 1");
              $where = "WHERE md.make_id = " . Filter::$id;
          } elseif(isset($_POST['find'])) {
			  $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::mdTable . "` WHERE name LIKE '%" . Validator::sanitize($_POST['find']) . "%' LIMIT 1");
              $where = "WHERE md.name LIKE '%" . Validator::sanitize($_POST['find']) . "%'";
          } else {
			  $counter = self::$db->count(self::mdTable);
              $where = null;
          }

          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = App::get("Core")->perpage;
          $pager->path = Url::adminUrl("models", false, false, "?");
          $pager->paginate();
		  
          $sql = "
		  SELECT 
			md.id AS mdid,
			md.name AS mdname,
			mk.name AS mkname 
		  FROM
			`" . self::mdTable . "` AS md 
			LEFT JOIN `" . self::mkTable . "` AS mk 
			  ON mk.id = md.make_id 
		  $where
		  ORDER BY md.name 
		  " . $pager->limit;
          $row = self::$db->pdoQuery($sql)->results();

          return ($row) ? $row : 0;
      }

      /**
       * Content::getModelList()
       * 
       * @return
       */
      public function getModelList($make_id)
      {
		  $row = self::$db->select(self::mdTable, "*", array("make_id" => $make_id), 'ORDER BY name')->results();
          return ($row) ? $row : 0;
      }
	  
      /**
       * Content::processModel()
       * 
       * @return
       */
      public function processModel()
      {
          if(empty($_POST['id']))
			  $err = Message::$msgs['id'] = Lang::$word->MAKE_NAME_R;

          $name = array_filter($_POST['modelname'], 'strlen');
          if (empty($name))
              $err = Message::$msgs['answer'] = Lang::$word->MODL_NAME_R;

          if (empty(Message::$msgs)) {
			  $makename = self::$db->first(self::mkTable, array("name"), array('id' => Filter::$id));
              $html = '';
              foreach ($_POST['modelname'] as $key => $val) {
                  $data = array('name' => Validator::sanitize($_POST['modelname'][$key]), 'make_id' => Filter::$id);
                  $last_id = self::$db->insert(self::mdTable, $data)->getLastInsertId();

                  $html .= '
				  <tr>
					<td class="warning"><small>' . $last_id . '.</small></td>
					<td>' . $makename->name . '</td>
					<td data-editable="true" data-set=\'{"type": "model", "id": ' . $last_id . ',"key":"name", "path":""}\'>' . $data['name'] . '</td>
					<td><a class="delete" data-set=\'{"title": "' . Lang::$word->MODL_DEL . '", "parent": "tr", "option": "deleteModel", "id": ' . $last_id . ', "name": "' . $data['name'] . '"}\'><i class="rounded outline icon negative trash link"></i></a></td>
				  </tr>';
              }
			  $json = array(
				  'type' => 'success',
				  'title' => Lang::$word->SUCCESS,
				  'data' => $html,
				  'message' => Lang::$word->MODL_ADDED
				  );
              print json_encode($json);

		  } else {
			  $json['type'] = 'error';
			  $json['title'] = Lang::$word->ERROR;
			  $json['message'] = $err;
			  print json_encode($json);
		  }

      }
	  
      /**
       * Content::getMakes()
       * 
       * @return
       */
      public function getMakes($paginate = true)
      {
          if ($paginate) {
              $pager = Paginator::instance();
              $pager->items_total = self::$db->count(self::mkTable);
              $pager->default_ipp = App::get("Core")->perpage;
			  $pager->path = Url::adminUrl("makes", false, false, "?");
              $pager->paginate();
              $limit = $pager->limit;
          } else {
              $limit = null;
          }
		  
		  $row = self::$db->select(self::mkTable, "*", null, 'ORDER BY name' . $limit)->results();
          return ($row) ? $row : 0;
      }
	  
	  /**
	   * Content::processMake()
	   * 
	   * @return
	   */
	  public function processMake()
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('name', 'string', true, 3, 200, Lang::$word->MAKE_NAME);
		  $validate->run();
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'name' => $validate->safe->name,
				  'name_slug' => Url::doSeo($validate->safe->name)
			  );

			  self::$db->insert(self::mkTable, $data);
			  Message::msgReply(self::$db->affected(), 'success', str_replace("[NAME]", $data['name'], Lang::$word->MAKE_ADDED));
		  } else {
			  Message::msgSingleStatus();
		  }
      }
      
      /**
       * Content::getMakesws()
       *
       * @return
       */
      public function getMakesws($paginate = true)
      {
      	if ($paginate) {
      		$pager = Paginator::instance();
      		$pager->items_total = self::$db->count(self::mkTable);
      		$pager->default_ipp = App::get("Core")->perpage;
      		$pager->path = Url::adminUrl("makes", false, false, "?");
      		$pager->paginate();
      		$limit = $pager->limit;
      	} else {
      		$limit = null;
      	}
      
      	$row = self::$db->select(self::mkwsTable, "*", null, 'ORDER BY name' . $limit)->results();
      	return ($row) ? $row : 0;
      }
       
      /**
       * Content::processMake()
       *
       * @return
       */
      public function processMakews()
      {
      
      	$validate = Validator::instance();
      	$validate->addSource($_POST);
      	$validate->addRule('name', 'string', true, 3, 200, Lang::$word->MAKE_NAME);
      	$validate->run();
      
      	if (empty(Message::$msgs)) {
      		$data = array(
      				'name' => $validate->safe->name,
      				'name_slug' => Url::doSeo($validate->safe->name)
      		);
      
      		self::$db->insert(self::mkwsTable, $data);
      		Message::msgReply(self::$db->affected(), 'success', str_replace("[NAME]", $data['name'], Lang::$word->MAKE_ADDED));
      	} else {
      		Message::msgSingleStatus();
      	}
      }
	  
      /**
       * Content::getConditions()
       * 
       * @return
       */
      public function getConditions()
      {
		  $row = self::$db->select(self::cdTable, "*", null, 'ORDER BY name')->results();
          return ($row) ? $row : 0;
      }
	  
	  /**
	   * Content::processCondition()
	   * 
	   * @return
	   */
	  public function processCondition()
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('name', 'string', true, 3, 200, Lang::$word->COND_NAME);
		  $validate->run();
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'name' => $validate->safe->name, 
			  );

			  self::$db->insert(self::cdTable, $data);
			  Message::msgReply(self::$db->affected(), 'success', str_replace("[NAME]", $data['name'], Lang::$word->COND_ADDED));
			  $sdata['cond_list_alt'] = serialize($this->getConditions());
			  self::$db->update(Core::sTable, $sdata, array("id" => 1));
		  } else {
			  Message::msgSingleStatus();
		  }
      }
	  
      /**
       * Content::getFuel()
       * 
       * @return
       */
      public function getFuel()
      {
		  $row = self::$db->select(self::fuTable, "*", null, 'ORDER BY name')->results();
          return ($row) ? $row : 0;
      }
	  
	  /**
	   * Content::processFuel()
	   * 
	   * @return
	   */
	  public function processFuel()
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('name', 'string', true, 3, 200, Lang::$word->FUEL_NAME);
		  $validate->run();
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'name' => $validate->safe->name, 
			  );

			  self::$db->insert(self::fuTable, $data);
			  Message::msgReply(self::$db->affected(), 'success', str_replace("[NAME]", $data['name'], Lang::$word->FUEL_ADDED));
			  $sdata['fuel_list'] = serialize($this->getFuel());
			  self::$db->update(Core::sTable, $sdata, array("id" => 1));
		  } else {
			  Message::msgSingleStatus();
		  }
      }

      /**
       * Content::getTransmissions()
       * 
       * @return
       */
      public function getTransmissions()
      {
		  $row = self::$db->select(self::trTable, "*", null, 'ORDER BY name')->results();
          return ($row) ? $row : 0;
      }

	  /**
	   * Content::processTransmission()
	   * 
	   * @return
	   */
	  public function processTransmission()
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('name', 'string', true, 3, 200, Lang::$word->TRNS_NAME);
		  $validate->run();
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'name' => $validate->safe->name, 
			  );

			  self::$db->insert(self::trTable, $data);
			  Message::msgReply(self::$db->affected(), 'success', str_replace("[NAME]", $data['name'], Lang::$word->TRNS_ADDED));
			  $sdata['trans_list'] = json_encode($this->getTransmissions());
			  self::$db->update(Core::sTable, $sdata, array("id" => 1));
		  } else {
			  Message::msgSingleStatus();
		  }
      }
      
      /**
       * Content::getYear()
       *
       * @return
       */
      public function getYear()
      {
      	$row = self::$db->select(self::yTable, "*", null, 'ORDER BY name DESC')->results();
      	return ($row) ? $row : 0;
      }
      
      /**
       * Content::processYear()
       *
       * @return
       */
      public function processYear()
      {
      
      	$validate = Validator::instance();
      	$validate->addSource($_POST);
      	$validate->addRule('name', 'numeric', true, 1, 4, Lang::$word->YEAR_NAME);
      	$validate->run();
      
      	if (empty(Message::$msgs)) {
      		$data = array(
      				'name' => $validate->safe->name,
      		);
      
      		self::$db->insert(self::yTable, $data);
      		Message::msgReply(self::$db->affected(), 'success', str_replace("[Name]", $data['name'], Lang::$word->YEAR_ADDED));
      		$sdata['year_ws_list'] = serialize($this->getYear());
      		self::$db->update(Core::sTable, $sdata, array("id" => 1));
      	} else {
      		Message::msgSingleStatus();
      	}
      }
      
      /**
       * Content::getDealtype()
       *
       * @return
       */
      public function getDealtype()
      {
      	$row = self::$db->select(self::dtTable, "*", null, 'ORDER BY name')->results();
      	return ($row) ? $row : 0;
      }
      
      /**
       * Content::processDealype()
       *
       * @return
       */
      public function processDealtype()
      {
      
      	$validate = Validator::instance();
      	$validate->addSource($_POST);
      	$validate->addRule('name', 'string', true, 3, 200, Lang::$word->DEAL_NAME);
      	$validate->run();
      
      	if (empty(Message::$msgs)) {
      		$data = array(
      				'name' => $validate->safe->name,
      		);
      
      		self::$db->insert(self::dtTable, $data);
      		Message::msgReply(self::$db->affected(), 'success', str_replace("[Name]", $data['name'], Lang::$word->DEAL_ADDED));
      		$sdata['dealtype_list'] = serialize($this->getDealtype());
      		self::$db->update(Core::sTable, $sdata, array("id" => 1));
      	} else {
      		Message::msgSingleStatus();
      	}
      } 
      
      /**
       * Content::getSpecialstype()
       *
       * @return
       */
      public function getSpecialstype()
      {
      	$row = self::$db->select(self::stTable, "*", null, 'ORDER BY name')->results();
      	return ($row) ? $row : 0;
      }
      
      /**
       * Content::processSpecialstype()
       *
       * @return
       */
      public function processSpecialstype()
      {
      
      	$validate = Validator::instance();
      	$validate->addSource($_POST);
      	$validate->addRule('name', 'string', true, 3, 200, Lang::$word->SPECIAL_NAME);
      	$validate->run();
      
      	if (empty(Message::$msgs)) {
      		$data = array(
      				'name' => $validate->safe->name,
      		);
      
      		self::$db->insert(self::stTable, $data);
      		Message::msgReply(self::$db->affected(), 'success', str_replace("[Name]", $data['name'], Lang::$word->SPECIAL_ADDED));
      		$sdata['specialstype_list'] = serialize($this->getSpeicalstype());
      		self::$db->update(Core::sTable, $sdata, array("id" => 1));
      	} else {
      		Message::msgSingleStatus();
      	}
      }
      
      
      
      
      
      
      /**
       * Content::getLease()
       *
       * @return
       */
      public function getLease()
      {
      	$row = self::$db->select(self::zsTable, "*", null, 'ORDER BY name')->results();
      	return ($row) ? $row : 0;
      }
      
      /**
       * Content::processLease()
       *
       * @return
       */
      public function processLease()
      {
      
      	$validate = Validator::instance();
      	$validate->addSource($_POST);
      	$validate->addRule('name', 'string', true, 3, 200, Lang::$word->LEASE_NAME);
      	$validate->run();
      
      	if (empty(Message::$msgs)) {
      		$data = array(
      				'name' => $validate->safe->name,
      		);
      
      		self::$db->insert(self::zsTable, $data);
      		Message::msgReply(self::$db->affected(), 'success', str_replace("[Name]", $data['name'], Lang::$word->LEASE_ADDED));
      		$sdata['zerosingle_list'] = serialize($this->getLease());
      		self::$db->update(Core::sTable, $sdata, array("id" => 1));
      	} else {
      		Message::msgSingleStatus();
      	}
      }
      
      /**
       * Content::getCoupons()
       * 
       * @return
       */
      public function getCoupons()
      {
		  $sql = "
		  SELECT *,
			CASE
			  WHEN type = 'a' 
			  THEN '" . Lang::$word->DC_TYPE_A . "' 
			  ELSE '" . Lang::$word->DC_TYPE_P . "' 
			END type
		  FROM `" . self::dcTable . "` 
		  ORDER BY created DESC;";
			  
		  $row = self::$db->pdoQuery($sql)->results();
          return ($row) ? $row : 0;
      }

      /**
       * Content::processCoupon()
       * 
       * @return
       */
      public function processCoupon()
      {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('title', 'string', true, 3, 100, Lang::$word->DC_NAME);
		  $validate->addRule('code', 'string', true, 3, 20, Lang::$word->DC_CODE);
		  $validate->addRule('discount', 'numeric', true, 1, 2, Lang::$word->DC_DISC);
		  $validate->addRule('type', 'string', false);
		  $validate->addRule('active', 'numeric', false);
		  $validate->run();
		  
          if (empty(Message::$msgs)) {
              $data = array(
					'title' => $validate->safe->title, 
					'code' => $validate->safe->code, 
					'discount' => $validate->safe->discount, 
					'type' => $validate->safe->type, 
					'mid' => isset($_POST['mid']) ? Utility::implodeFields($_POST['mid']) : 0, 
					'active' => $validate->safe->active
			  );

			  (Filter::$id) ? self::$db->update(self::dcTable, $data, array('id' => Filter::$id)) : self::$db->insert(self::dcTable, $data);
			  $message = (Filter::$id) ? Lang::$word->DC_UPDATED : Lang::$word->DC_ADDED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
		  } else {
			  Message::msgSingleStatus();
		  }
      }

      /**
       * Content::getSlider()
       * 
       * @return
       */
      public function getSlider()
      {

		  $row = self::$db->select(self::slTable, null, null, "ORDER BY sorting")->results();

          return ($row) ? $row : 0; 

      }

	  /**
	   * Content::processSlide()
	   * 
	   * @return
	   */
	  public function processSlide()
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('caption', 'string', true, 3, 200, Lang::$word->SLD_NAME);
		  $validate->run();
		  
		  if(!Filter::$id and empty($_FILES['thumb']['name'])){
			  Message::$msgs['thumb'] = Lang::$word->SLD_IMAGE;
		  }
		  
          if (!empty($_FILES['thumb']['name']) and empty(Message::$msgs)) {
			  $upl = Upload::instance(2097152, "png,jpg");
              $upl->process("thumb", UPLOADS . "slider/", "SLIDE_");
          }
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'caption' => $validate->safe->caption, 
				  'body' => $_POST['body'],
			  );

			  /* == Procces Image == */
			  if (!empty($_FILES['thumb']['name'])) {
				  $thumbdir = UPLOADS . "slider/";
				  if (Filter::$id && $row = self::$db->first(self::slTable, array("thumb"), array('id' => Filter::$id))) {
					  File::deleteFile($thumbdir . $row->thumb);
				  }
				  $data['thumb'] = $upl->fileInfo['fname'];
			  }
			  
			  (Filter::$id) ? self::$db->update(self::slTable, $data, array('id' => Filter::$id)) : self::$db->insert(self::slTable, $data);
			  $message = (Filter::$id) ? Lang::$word->SLD_UPDATED : Lang::$word->SLD_ADDED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
		  } else {
			  Message::msgSingleStatus();
		  }
      }

      /**
       * Content::getReviews()
       * 
       * @return
       */
      public function getReviews($status = false)
      {
		  $active = $status ? 'WHERE r.status = 1' : null;
		  $sql ="
		  SELECT r.*,
			CONCAT(m.fname,' ',m.lname) as name,
			m.avatar,
			m.id AS uid
		  FROM
			`" . self::rwTable . "` AS r 
			LEFT JOIN `" . Users::mTable . "` AS m 
			  ON m.id = r.user_id 
		  $active
		  ORDER BY r.created DESC;";
		  
		  $row = self::$db->pdoQuery($sql)->results();
          return ($row) ? $row : 0;
      }

      /**
       * Content::addReview()
       * 
       * @return
       */
      public function addReview()
      {
		  $validate = Validator::instance();
		  $validate->addSource($_POST);

		  $validate->addRule('content', 'string', true, 20, 300, Lang::$word->SRW_DESC);
		  $validate->addRule('twitter', 'string', false);

		  $validate->run();
          if (empty(Message::$msgs)) {
              $data = array(
					'content' => $validate->safe->content, 
					'twitter' => $validate->safe->twitter, 
					'user_id' => App::get('Auth')->uid
					
			  );
              $last_id = self::$db->insert(self::rwTable, $data)->getLastInsertId();
			  
			  if ($last_id) {
				  $json['type'] = "success";
				  $json['title'] = Lang::$word->SRW_ADDDED;
				  $json['message'] = Lang::$word->M_ADDED;
				  $json['redirect'] = Url::doUrl(URL_ACCOUNT);
			  } else {
				  $json['type'] = "alert";
				  $json['title'] = Lang::$word->ALERT;
				  $json['message'] = Lang::$word->NOPROCCESS;
			  }
			  
			  print json_encode($json);
			  
			  if ($last_id) {
				  $mailer = Mailer::sendMail();
	
				  ob_start();
				  require_once (BASEPATH . 'mailer/' . Core::$language . '/Admin_Notify_Review.tpl.php');
				  $html_message = ob_get_contents();
				  ob_end_clean();
	
				  $body = str_replace(array(
					  '[LOGO]',
					  '[USERNAME]',
					  '[NAME]',
					  '[CONTENT]',
					  '[IP]',
					  '[DATE]',
					  '[COMPANY]',
					  '[SITEURL]'), array(
					  Utility::getLogo(),
					  App::get('Auth')->username,
					  App::get('Auth')->name,
					  $validate->safe->content,
					  Url::getIP(),
					  date('Y'),
					  App::get("Core")->company,
					  SITEURL), $html_message);
	
				  $msg = Swift_Message::newInstance()
						->setSubject(Lang::$word->SRW_SUBJECT . ' ' . App::get('Auth')->name)
						->setTo(array(App::get("Core")->site_email => App::get("Core")->company))
						->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
						->setBody($body, 'text/html');
	
				  $mailer->send($msg);
			  }
		  } else {
			  Message::msgSingleStatus();
		  }

	  }
	  
      /**
       * Content::getCategories()
       * 
       * @return
       */
      public function getCategories()
      {
		  $row = self::$db->select(self::ctTable, "*", null, 'ORDER BY name')->results();
          return ($row) ? $row : 0;
      }

      /**
       * Content::getCategoryCounters()
       * 
       * @return
       */
      public function getCategoryCounters()
      {

		  $sql = "
		  SELECT 
			c.id,
			c.name,
			c.slug,
			c.image,
			COUNT(l.id) AS listings 
		  FROM
			`" . self::ctTable . "` c 
			LEFT JOIN `" . Items::lTable . "` l 
			  ON l.category = c.id 
		  WHERE l.status = 1
		  AND l.featured = 1
		  GROUP BY c.id LIMIT " . App::get('Core')->featured; 
		  
		  $row = self::$db->pdoQuery($sql)->results();
          return ($row) ? $row : 0;
      }
	  
	  /**
	   * Content::processCategory()
	   * 
	   * @return
	   */
	  public function processCategory()
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('name', 'string', true, 3, 200, Lang::$word->CAT_NAME);
		  $validate->addRule('slug', 'string', false);
		  
          if (!empty($_FILES['image']['name']) and empty(Message::$msgs)) {
			  $upl = Upload::instance(1048576, "png,jpg");
              $upl->process("image", UPLOADS . "catico/", false);
          }
		  
		  $validate->run();
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'name' => $validate->safe->name, 
				  'slug' => (empty($_POST['slug'])) ? Url::doSeo($validate->safe->name) : Url::doSeo($validate->safe->slug),
			  );

			  /* == Procces Icon == */
			  if (!empty($_FILES['image']['name'])) {
				  $thumbdir = UPLOADS . "catico/";
				  if (Filter::$id && $row = self::$db->first(self::ctTable, array("image"), array('id' => Filter::$id))) {
					  File::deleteFile($thumbdir . $row->image);
				  }
				  $data['image'] = $upl->fileInfo['fname'];
			  }
			  
			  (Filter::$id) ? self::$db->update(self::ctTable, $data, array('id' => Filter::$id)) : self::$db->insert(self::ctTable, $data);
			  $message = (Filter::$id) ? Lang::$word->CAT_UPDATED : Lang::$word->CAT_ADDED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
		  } else {
			  Message::msgSingleStatus();
		  }
      }
      
      /**
       * Content::getBodyStyle()
       *
       * @return
       */
      public function getBodyStyle()
      {
      	$row = self::$db->select(self::bsTable, "*", null, 'ORDER BY name')->results();
      	return ($row) ? $row : 0;
      }
      
      /**
       * Content::getBodyStyleCounters()
       *
       * @return
       */
      public function getBodyStyleCounters()
      {
      
      	$sql = "
		  SELECT
			bs.id,
			bs.name,
			bs.slug,
			bs.image,
			COUNT(ws.id) AS webspecials
		  FROM
			`" . self::bsTable . "` bs
			LEFT JOIN `" . wSpecials::wsTable . "` ws
			  ON ws.body_style_code = bs.name
		  WHERE bs.active = 1
		  GROUP BY c.id LIMIT " . App::get('Core')->featured;
      
      	$row = self::$db->pdoQuery($sql)->results();
      	return ($row) ? $row : 0;
      }
       
      /**
       * Content::processBodyStyle()
       *
       * @return
       */
      public function processBodyStyle()
      {
      
      	$validate = Validator::instance();
      	$validate->addSource($_POST);
      	$validate->addRule('name', 'string', true, 3, 200, Lang::$word->BS_NAME);
      	$validate->addRule('slug', 'string', false);
      
      	if (!empty($_FILES['image']['name']) and empty(Message::$msgs)) {
      		$upl = Upload::instance(1048576, "png,jpg");
      		$upl->process("image", UPLOADS . "bodystyleico/", false);
      	}
      
      	$validate->run();
      	if (empty(Message::$msgs)) {
      		$data = array(
      				'name' => $validate->safe->name,
      				'slug' => (empty($_POST['slug'])) ? Url::doSeo($validate->safe->name) : Url::doSeo($validate->safe->slug),
      		);
      
      		/* == Procces Icon == */
      		if (!empty($_FILES['image']['name'])) {
      			$thumbdir = UPLOADS . "bodystyleico/";
      			if (Filter::$id && $row = self::$db->first(self::bsTable, array("image"), array('id' => Filter::$id))) {
      				File::deleteFile($thumbdir . $row->image);
      			}
      			$data['image'] = $upl->fileInfo['fname'];
      		}
      			
      		(Filter::$id) ? self::$db->update(self::bsTable, $data, array('id' => Filter::$id)) : self::$db->insert(self::bsTable, $data);
      		$message = (Filter::$id) ? Lang::$word->BS_UPDATED : Lang::$word->BS_ADDED;
      		Message::msgReply(self::$db->affected(), 'success', $message);
      	} else {
      		Message::msgSingleStatus();
      	}
      }

      /**
       * Content::getLocations()
       * 
       * @return
       */
      public function getLocations($owner = true)
      {
		  $is_owner = $owner ? array("ltype" => "owner") : null;
		  
		  $row = self::$db->select(self::lcTable, "*", $is_owner, 'ORDER BY name')->results();
          return ($row) ? $row : 0;

      }

      /**
       * Content::getUserLocations()
       * 
       * @return
       */
      public function getUserLocations()
      {
		  
		  $row = self::$db->select(self::lcTable, "*", array("user_id" => App::get('Auth')->uid), 'ORDER BY name')->results();
          return ($row) ? $row : 0;

      }
	  
	  /**
	   * Content::processLocation()
	   * 
	   * @return
	   */
	  public function processLocation($front = false)
	  {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('name', 'string', true, 3, 200, Lang::$word->LOC_NAME);
		  $validate->addRule('email', 'email');
		  $validate->addRule('address', 'string', true, 3, 200, Lang::$word->ADDRESS);
		  $validate->addRule('city', 'string', true, 2, 100, Lang::$word->CITY);
		  $validate->addRule('state', 'string', true, 2, 50, Lang::$word->STATE);
		  $validate->addRule('zip', 'string', true, 3, 30, Lang::$word->ZIP);
		  $validate->addRule('country', 'string', true, 3, 30, Lang::$word->COUNTRY);
		  $validate->addRule('letter', 'string', true, 1, 30, Lang::$word->LETTER);
		  $validate->addRule('salesphone', 'string', false);
		  $validate->addRule('servicephone', 'string', false);
		  $validate->addRule('fax', 'string', false);
		  $validate->addRule('url', 'string', false);
		  $validate->addRule('lat', 'float', false);
		  $validate->addRule('lng', 'float', false);
		  $validate->addRule('zoom', 'numeric', false);
		  $validate->addRule('logo2', 'string', false);
		  $validate->addRule('leadsemail', 'email');
		  
		  
          if (!empty($_FILES['logo']['name']) and empty(Message::$msgs)) {
			  $upl = Upload::instance(1048576, "png,jpg");
              $upl->process("logo", UPLOADS . "showrooms/", "logo_");
          }
		  
		  $validate->run();
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'name' => $validate->safe->name,
				  'name_slug' => Url::doSeo(Utility::randNumbers(4) . '-' . $validate->safe->name), 
				  'ltype' => $front ? "user" : "owner",
				  'user_id' => $front ? App::get('Auth')->uid : 0,
				  'email' => $validate->safe->email,
				  'address' => $validate->safe->address,
				  'city' => $validate->safe->city,
				  'state' => $validate->safe->state,
				  'zip' => $validate->safe->zip,
				  'country' => $validate->safe->country,
			  	  'letter' => $validate->safe->letter,
				  'salesphone' => $validate->safe->salesphone,
			  	  'servicephone' => $validate->safe->servicephone,
				  'fax' => $validate->safe->fax,
				  'url' => $validate->safe->url,
				  'lat' => $validate->safe->lat,
				  'lng' => $validate->safe->lng,
			  	  'zoom' => $validate->safe->zoom,
				  'logo2' => $validate->safe->logo2,
			  	  'leadsemail' => $validate->safe->leadsemail,
			  	  'hasSales' => isset($_POST['hasSales']) ? $_POST['hasSales'] : 0,
			  	  'hasService' => isset($_POST['hasService']) ? $_POST['hasService'] : 0,
			  	  'hasParts' => isset($_POST['hasParts']) ? $_POST['hasParts'] : 0
			  		
			  );

			  /* == Procces Icon == */
			  if (!empty($_FILES['logo']['name'])) {
				  $thumbdir = UPLOADS . "showrooms/";
				  if (Filter::$id && $row = self::$db->first(self::lcTable, array("logo"), array('id' => Filter::$id))) {
					  if($row->logo) {
						  File::deleteFile($thumbdir . $row->logo);
					  }
				  }
				  
				  $data['logo'] = $upl->fileInfo['fname'];
			  }
			  
			  (Filter::$id) ? self::$db->update(self::lcTable, $data, array('id' => Filter::$id)) : $last_id = self::$db->insert(self::lcTable, $data)->getLastInsertId();
			  $message = (Filter::$id) ? Lang::$word->LOC_UPDATED : Lang::$word->LOC_ADDED;
			  if($front) {
				  $json['type'] = "success";
				  $json['title'] = Lang::$word->SUCCESS;
				  $json['message'] = $message;
				  $json['redirect'] = Url::doUrl(URL_MYLOCATIONS);
				  print json_encode($json);
			  } else {
				  Message::msgReply(self::$db->affected(), 'success', $message);
			  }
			  
			  $idata = array(
			  		'storename' => $validate->safe->name,
			  		'salesemail' => $validate->safe->email,
			  		'address1' => $validate->safe->address,
			  		'city' => $validate->safe->city,
			  		'state' => $validate->safe->state,
			  		'zip' => $validate->safe->zip,
			  		'letter' => $validate->safe->letter,
			  		'salesphone' => $validate->safe->salesphone,
			  		'servicephone' => $validate->safe->servicephone,
			  		'fax' => $validate->safe->fax,
			  		'url' => $validate->safe->url,
			  		'storeLogo' => $validate->safe->logo2,
			  		'leadsemail' => $validate->safe->leadsemail,
			  		'hasSales' => isset($_POST['hasSales']) ? $_POST['hasSales'] : 0,
			  		'hasService' => isset($_POST['hasService']) ? $_POST['hasService'] : 0,
			  		'hasParts' => isset($_POST['hasParts']) ? $_POST['hasParts'] : 0
			  		);
			  
			   
			  
			  if (!Filter::$id) {
			  	$idata['store_id_loc'] = $last_id;
			  	$idata['created'] = Db::toDate();
			  	$idata['created_id'] = App::get('Auth')->uid;
			  	 
			  } else {
			  	$idata['modified'] = Db::toDate();
			  	$idata['modified_id'] = App::get('Auth')->uid;
			  }
			 
			   
			  (Filter::$id) ? self::$db->update(self::sTable, $idata, array('store_id_loc' => Filter::$id ? Filter::$id : $last_id)) : self::$db->insert(self::sTable, $idata);
		 
		  } else {
			  Message::msgSingleStatus();
		  }
      }
	  
      /**
       * Content::getGetaways()
       * 
       * @return
       */
      public function getGetaways($active = false)
      {
          $is_active = $active ? array("active" =>1) : null;
		  $row = self::$db->select(self::gwTable, "*", $is_active, 'ORDER BY name')->results();
          return ($row) ? $row : 0;
      }

      /**
       * Content::processGateway()
       * 
       * @return
       */
      public function processGateway()
      {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('displayname', 'string', true, 3, 200, Lang::$word->GW_NAME);
		  $validate->addRule('extra', 'string', false);
		  $validate->addRule('extra2', 'string', false);
		  $validate->addRule('extra3', 'string', false);
		  $validate->addRule('active', 'numeric', false);
		  $validate->addRule('live', 'numeric', false);
		  $validate->run();
		  
          if (empty(Message::$msgs)) {
              $data = array(
					'displayname' => $validate->safe->displayname, 
					'extra' => $validate->safe->extra, 
					'extra2' => $validate->safe->extra2, 
					'extra3' => $validate->safe->extra3, 
					'live' => $validate->safe->live, 
					'active' => $validate->safe->active
			  );

              self::$db->update(self::gwTable, $data, array('id' => Filter::$id));
              $message = Lang::$word->GW_UPDATED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Content::getMemberships()
       * 
       * @return
       */
	  public function getMemberships($private = false)
	  {
		  $is_private = $private ? array("private" => 0, "active" => 1) : null;
		  $row = self::$db->select(self::msTable, "*", $is_private, 'ORDER BY price')->results();
          return ($row) ? $row : 0;
	  }
	  
      /**
       * Content::processPackage()
       * 
       * @return
       */
      public function processPackage()
      {

		  $validate = Validator::instance();
		  $validate->addSource($_POST);
		  $validate->addRule('title', 'string', true, 3, 200, Lang::$word->MSM_NAME);
		  $validate->addRule('price', 'float', true, 0, 0, Lang::$word->MSM_PRICE);
		  $validate->addRule('days', 'numeric', true, 1, 3, Lang::$word->MSM_PERIOD);
		  $validate->addRule('period', 'string', false);
		  $validate->addRule('private', 'numeric', false);
		  $validate->addRule('featured', 'numeric', false);
		  $validate->addRule('active', 'numeric', false);
		  $validate->addRule('listings', 'numeric', false);
		  $validate->addRule('description', 'string', false);
		  $validate->run();
		  
          if (empty(Message::$msgs)) {
              $data = array(
					'title' => $validate->safe->title, 
					'price' => $validate->safe->price, 
					'days' => $validate->safe->days, 
					'period' => $validate->safe->period, 
					'private' => $validate->safe->private, 
					'featured' => $validate->safe->featured,
					'active' => $validate->safe->active,
					'listings' => $validate->safe->listings,
					'description' => $validate->safe->description,
			  );

			  (Filter::$id) ? self::$db->update(self::msTable, $data, array('id' => Filter::$id)) : self::$db->insert(self::msTable, $data);
			  $message = (Filter::$id) ? Lang::$word->MSM_UPDATED : Lang::$word->MSM_ADDED;
			  Message::msgReply(self::$db->affected(), 'success', $message);
		  } else {
			  Message::msgSingleStatus();
		  }
      }
	  
      /**
       * Content::getPayments()
       * 
       * @param bool $from
       * @return
       */
	  public function getPayments($from = false)
	  {

		  if (Filter::$id and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
              if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                  $enddate = Validator::sanitize($_POST['enddate_submit']);
              }
			  $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::txTable . "` WHERE p.membership_id = " . Filter::$id . " AND created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'");
			  $where = " WHERE p.membership_id = " . Filter::$id . " AND p.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'"; 
		  } elseif (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
              if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                  $enddate = Validator::sanitize($_POST['enddate_submit']);
              }
			  $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::txTable . "` WHERE created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'");
			  $where = " WHERE p.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
          } elseif (Filter::$id) {
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::txTable . "` WHERE membership_id = " . Filter::$id . " LIMIT 1");
			  $where = " WHERE p.membership_id = " . Filter::$id;
		  } else {
			  $counter = self::$db->count(self::txTable);
			  $where = null;
		  }
	
          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = App::get("Core")->perpage;
          $pager->path = Url::adminUrl("transactions", false, false, "?");
          $pager->paginate();
	
		  $sql = "
		  SELECT 
			p.*,
			p.id AS id,
			u.username,
			u.id AS uid,
			m.id AS mid,
			m.title 
		  FROM
			`" . self::txTable . "` AS p 
			LEFT JOIN `" . Users::mTable . "` AS u 
			  ON u.id = p.user_id 
			LEFT JOIN `" . self::msTable . "` AS m 
			  ON m.id = p.membership_id 
		  $where
		  ORDER BY p.created DESC " . $pager->limit; 
	
		  $row = self::$db->pdoQuery($sql)->results();
		  return ($row) ? $row : 0;
	  }

      /**
       * Content::getUserTransactions()
       * 
	   * @param bool $uid
       * @return
       */
	  public function getUserTransactions($uid = false)
	  {
		  $id = $uid ? $uid : Filter::$id;
		  
		  $sql = "
		  SELECT 
			p.*,
			p.id AS id,
			m.id AS mid,
			m.title 
		  FROM
			`" . self::txTable . "` AS p 
			LEFT JOIN `" . self::msTable . "` AS m 
			  ON m.id = p.membership_id 
		  WHERE user_id = ?
		  ORDER BY p.created DESC;"; 
		  
          $row = self::$db->pdoQuery($sql, array($id))->results();
          return ($row) ? $row : 0;
	  }
	  
      /**
       * Content::colorList()
       * 
       * @return
       */
      public static function colorList()
      {
          $data = array(
              Lang::$word->WHITE => Lang::$word->WHITE,
              Lang::$word->BLACK => Lang::$word->BLACK,
              Lang::$word->SILVER => Lang::$word->SILVER,
              Lang::$word->GRAY => Lang::$word->GRAY,
              Lang::$word->RED => Lang::$word->RED,
              Lang::$word->BLUE => Lang::$word->BLUE,
			  Lang::$word->BEIGE => Lang::$word->BEIGE,
			  Lang::$word->YELLOW => Lang::$word->YELLOW,
			  Lang::$word->GREEN => Lang::$word->GREEN,
			  Lang::$word->BROWN => Lang::$word->BROWN,
			  Lang::$word->BURGUNDY => Lang::$word->BURGUNDY,
			  Lang::$word->CHARCOAL => Lang::$word->CHARCOAL,
			  Lang::$word->GOLD => Lang::$word->GOLD,
			  Lang::$word->PINK => Lang::$word->PINK,
			  Lang::$word->PURPLE => Lang::$word->PURPLE,
			  Lang::$word->TAN => Lang::$word->TAN,
			  Lang::$word->TURQUOISE => Lang::$word->TURQUOISE,
			  );
          return $data;
      }
      
      /**
       * Content::numberList()
       *
       * @return
       */
      public static function numberList()
      {
      	$data = array(
      			1 => 1,
      			2 => 2,
      			3 => 3,
      			4 => 4,
      			5 => 5,
      			6 => 6,
      			7 => 7,
      			8 => 8,
      			9 => 9,
      			10 => 10,
      			11 => 11,
      			12 => 12,
      			13 => 13,
      			14 => 14,
      			15 => 15,
      			16 => 16,
      			17 => 17,
      			18 => 18,
      			19 => 19,
      			20 => 20,	
      	);
      	return $data;
      }   
      
      /**
       * Content::yesnoList()
       *
       * @return
       */
      public static function yesnoList()
      {
      	$data = array(
      			Yes => 1,
      			No => 0, 			
      	);
      	return $data;
      }
      
      /**
       * Content::getncscolorswap()
       *
       * @return
       */
      public static function getncscolorswap($sletter) {
            $colorstyleblock = '<style>';
            $storeLetter = $sletter;
            $ncs_pcolor;
            $ncs_vtcolor;
            $ncs_hcolor;
            $ncs_vbcolor;
            $ncs_bcolor;
            $ncs_bhcolor;
            $ncs_spancolor;
            $ncs_ty_clr;
            $ncs_ty_bgclr;
            $ncs_ty_bgclrhvr;
            
            switch ($storeLetter) {
              case 'aa':
              case 'f':
                  $ncs_pcolor = '#2d96cd';
                  $ncs_vtcolor = '#2d96cd';
                  $ncs_hcolor = '#075c9a';
                  $ncs_vbcolor = '#075c9a';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(7,92,154, .6)';
                  $ncs_ty_clr = '#075c9a';
                  $ncs_ty_bgclr = '#075c9a';
                  $ncs_ty_bgclrhvr = '#2d96cd';
                  break;
              case 'c':
              case 'd':
                  $ncs_pcolor = '#f2bf24';
                  $ncs_vtcolor = '#f7da20';
                  $ncs_hcolor = '#a68319';
                  $ncs_vbcolor = '#e68c00';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(238,170,4,.6)';
                  $ncs_ty_clr = '#ff9c00';
                  $ncs_ty_bgclr = '#ff9c00';
                  $ncs_ty_bgclrhvr = '#e68c00';
                  break;
              case 'e':
                  $ncs_pcolor = '#D50000';
                  $ncs_vtcolor = '#BA3B00';
                  $ncs_hcolor = '#8a0000';
                  $ncs_vbcolor = '#D50000';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(213,0,0,.6)';
                  $ncs_ty_clr = '#D50000';
                  $ncs_ty_bgclr = '#D50000';
                  $ncs_ty_bgclrhvr = '#BA3B00';
                  break;
              case 'j':
              case 'y':
                  $ncs_pcolor = '#edad01';
                  $ncs_vtcolor = '#7f6a37';
                  $ncs_hcolor = '#a17602';
                  $ncs_vbcolor = '#0c3e00';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(12,62,0,.6)';
                  $ncs_ty_clr = '#0c3e00';
                  $ncs_ty_bgclr = '#0c3e00';
                  $ncs_ty_bgclrhvr = '#7f6a37';
                  break;
              case 'm':
                  $ncs_pcolor = '#d00000';
                  $ncs_vtcolor = '#e92020';
                  $ncs_hcolor = '#850000';
                  $ncs_vbcolor = '#d00000';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(7,92,154, .6)';
                  $ncs_ty_clr = '#d00000';
                  $ncs_ty_bgclr = '#d00000';
                  $ncs_ty_bgclrhvr = '#e92020';
                  break;
              case 'v':
              case 'w':
                  $ncs_pcolor = '#00b1eb';
                  $ncs_vtcolor = '#00b1eb';
                  $ncs_hcolor = '#00779e';
                  $ncs_vbcolor = '#2680B4';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(38,128,180,.6)';
                  $ncs_ty_clr = '#2680B4';
                  $ncs_ty_bgclr = '#2680B4';
                  $ncs_ty_bgclrhvr = '#00b1eb';
                  break;
              case 'k':
              case 'h':
                  $ncs_pcolor = '#c4172c';
                  $ncs_vtcolor = '#ee243d';
                  $ncs_hcolor = '#780e1b';
                  $ncs_vbcolor = '#c5293c';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(238,36,61, .6)';
                  $ncs_ty_clr = '#c4172c';
                  $ncs_ty_bgclr = '#c4172c';
                  $ncs_ty_bgclrhvr = '#780e1b';
                  break;
              case 'g':
                  $ncs_pcolor = '#cc0000';
                  $ncs_vtcolor = '#CA0000';
                  $ncs_hcolor = '#800000';
                  $ncs_vbcolor = '#A40000';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(164,0,0,.6)';
                  $ncs_ty_clr = '#A40000';
                  $ncs_ty_bgclr = '#A40000';
                  $ncs_ty_bgclrhvr = '#CA0000';
                  break;
              case 'n':
                  $ncs_pcolor = '#c3002f';
                  $ncs_vtcolor = '#c3002f';
                  $ncs_hcolor = '#75001b';
                  $ncs_vbcolor = '#75001b';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(198,25,53,.6)';
                  $ncs_ty_clr = '#c3002f';
                  $ncs_ty_bgclr = '#c3002f';
                  $ncs_ty_bgclrhvr = '#75001b';
                  break;
              case 'p':
                  $ncs_pcolor = '#0c9d1a';
                  $ncs_vtcolor = '#0c9d1a';
                  $ncs_hcolor = '#1d7117';
                  $ncs_vbcolor = '#1d7117';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(23, 157, 27,.6)';
                  $ncs_ty_clr = '#1d7117';
                  $ncs_ty_bgclr = '#1d7117';
                  $ncs_ty_bgclrhvr = '#0c9d1a';
                  break;
              case 's':
                  $ncs_pcolor = '#20609f';
                  $ncs_vtcolor = '#0189e4';
                  $ncs_hcolor = '#103152';
                  $ncs_vbcolor = '#004890';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(0, 72, 144, .6)';
                  $ncs_ty_clr = '#004890';
                  $ncs_ty_bgclr = '#004890';
                  $ncs_ty_bgclrhvr = '#0189e4';
                  break;
              case 't':
                  $ncs_pcolor = '#48c3b8';
                  $ncs_vtcolor = '#48c3b8';
                  $ncs_hcolor = '#308aa1';
                  $ncs_vbcolor = '#308aa1';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(72,195,184, .6)';
                  $ncs_ty_clr = '#308aa1';
                  $ncs_ty_bgclr = '#308aa1';
                  $ncs_ty_bgclrhvr = '#48c3b8';
                  break;
              default:
                  $ncs_pcolor = '#2d96cd';
                  $ncs_vtcolor = '#2d96cd';
                  $ncs_hcolor = '#075c9a';
                  $ncs_vbcolor = '#075c9a';
                  $ncs_bcolor = '#0c9d1a';
                  $ncs_bhcolor = '#13cc25';
                  $ncs_spancolor = 'rgba(7,92,154, .6)';
                  $ncs_ty_clr = '#075c9a';
                  $ncs_ty_bgclr = '#075c9a';
                  $ncs_ty_bgclrhvr = '#2d96cd';
                  break;
            }
            $color_swap =
            '.ncs_primary_color,
            .q_breadcrumbs,
            .q_breadcrumbs a,
            a.NCSMainPhone,
            .stickyPriceBarTitle {
            	color: '.$ncs_pcolor.';
            }
            .vlp_uppercase {
                color: '.$ncs_vtcolor.';
            }
            .ncs_ty_color {
                color: '.$ncs_ty_clr.';
            }
            .ncs_ty_bg_color,
            .ncs_ty_bg_color a {
                background-color: '.$ncs_ty_bgclr.';
            }
            .ncs_ty_bg_color:hover,
            .ncs_ty_bg_color a:hover {
                background-color: '.$ncs_ty_bgclrhvr.';
            }
            .ncs_secondary_color,
            a.NCSMainPhone:hover {
            	color: '.$ncs_hcolor.';
            }
            .ncs_secondary_bg_color {
            	background-color: '.$ncs_hcolor.';
            }
            .vlp_specials_links_container .getInfo,
            .vlp_title_lines hr {
                background-color: '.$ncs_vbcolor.';
            }
            .vlp_specials_links_container .getInfo:hover {
                background-color: '.$ncs_vtcolor.';
            }
            .ncs_secondary_bg_color:hover {
            	background-color: '.$ncs_pcolor.';
            }
            .ncs_form_img_bg {
            	background-color: '.$ncs_bcolor.';
            }
            .ncs_form_img_outline_color {
            	border-color: '.$ncs_bcolor.';
            	color: '.$ncs_bcolor.';
            }
            .ncs_form_img_outline_color a,
            .ncs_form_img_outline_color a:hover {
            	color: '.$ncs_bcolor.';
            }
            .ncs_primary_bgcolor {
                background-color: '.$ncs_pcolor.';
            }
            .ncs_primary_bgcolor:hover {
            	background-color: '.$ncs_hcolor.';
            }
            .ncs_primary_link_color a,
            .ncs_primary_link_color a:visited,
            .ncs_primary_color a,
            .ncs_primary_color a:visited {
            	color: '.$ncs_pcolor.';
            }
            .ncs_primary_link_color a:hover,
            .ncs_primary_color a:hover {
            	color: '.$ncs_hcolor.';
            }
            .ncs_btn_color {
            	background-color: '.$ncs_bcolor.';
            }
            .ncs_btn_color:hover {
            	background-color: '.$ncs_bhcolor.';
            	outline: none;
            }
            .ncs_checker_color {
            	outline-color: '.$ncs_pcolor.' !important;
            }
            .NCSOptionChecker input:hover + .ncs_checker_color,
            .NCSOptionChecker input:focus + .ncs_checker_color {
            	outline-color: '.$ncs_hcolor.' !important;
            }
            label.ncs_checker_color:after,
            .specialCTAs_singlePost_linkWrap {
            	border-color: '.$ncs_pcolor.' !important;
            }
            .ncs_span_color span {
            	background-color: '.$ncs_spancolor.';
            }
            @media(max-width: 768px) {
            	.vlp_title_h2 h2 {
            		color: '.$ncs_vbcolor.' !important;
            	}
            }';
          $colorstyleblock .= $color_swap;
          $colorstyleblock .='</style>';
          print $colorstyleblock;
      }
      /**
       * Content::getwebspecialspreview()
       *
       * @return
       */
      public  static function getwebspecialspreview($id,$letter) {        
          $where = "WHERE webspecials_id = $id " ;
          $where2 = "WHERE special_id = $id" ;
          $sql = "
			SELECT
			  ws.*,
	  		 lc.logo
			 FROM
			  `" . wSpecials::w_sTable . "` AS ws
  			  LEFT JOIN `" . self::lcTable . "` AS lc
        			  ON lc.letter = ws.store_letter
        			  
        			  $where
        			  
        			  ";
        			  
          $sql2 = "
			SELECT
			  wsp.*
			  FROM `" . wSpecials::wspTable . "` AS wsp
			  
      			  $where2
      			  AND wsp.active = 1
      			  ORDER BY wsp.ordering ASC";
      			  
      	  $sqlPhone = "SELECT salesphone FROM stores WHERE letter = '".$letter."'";
      	  $html = '';
		  if ($data2 = self::$db->pdoQuery ( $sql )->result ()) :
		  
  			  $data1 = self::$db->pdoQuery ( $sql2 )->results(); 
		  
  			  $data3 = self::$db->pdoQuery ( $sqlPhone )->results();
  			  
  			  $dataws = $data2;
  			  
  			  $cfArr = array();
  			  
  			  $phoneArr = array();
  			  
  			  foreach ( $dataws  as $key => $val ) {  			      
  			      $cfArr[$key] = $val;
  			  }
  			  foreach ($data3 as $key => $val){
  			      $phoneArr[$key] = $val;
  			  }  			
  			  foreach($phoneArr[0] as $key => $val){
  			      $cfArr['sales_phone'] = $val;
  			  }
  			  
  			  $uniqid = uniqid();      			  
  			  $pricing_counter = 0;
  			  foreach($data1 as $prow){      			   
  			      $pricing_counter++;
  			      $cfArr['pricing_'.$pricing_counter.'_name'] = $prow->name;
  			      $cfArr['pricing_'.$pricing_counter.'_value'] = $prow->price;
  			  }
  			  $cfArr['total_pricing_lines'] = $pricing_counter;
  			  
  			  $html .= "<div id='ncsMain' data-ncsid='".$uniqid."' data-ncsarray='".json_encode($cfArr, JSON_HEX_APOS)."' data-formimg='".Url::adminUrl ( "assets", false, "images/click_to_call_cta.png" )."' class='qsPage'></div>";
  			  //$html .= '<script id="specialsFunctions" type="text/babel" src=" ' . Url::adminUrl ( "assets", false, "/js/ncs_dev.js" ) . '"></script>';
  			 
  			  print $html;
		  
		  endif;
      }
      /**
       * Content::getwebspecialspreview() - old version
       *
       * @return
       */
      /*public  static function getwebspecialspreview($id) {
      	 
      	$where = "WHERE webspecials_id = $id " ;
      	$where2 = "WHERE special_id = $id" ;
      	$sql = "
			SELECT
			  ws.*,
	  		 lc.logo
			 FROM
			  `" . wSpecials::w_sTable . "` AS ws
  			  LEFT JOIN `" . self::lcTable . "` AS lc
        			  ON lc.letter = ws.store_letter
        			   
        			  $where
            	 
        			  ";
        			   
        			  $sql2 = "
			SELECT
			  wsp.*
			  FROM `" . wSpecials::wspTable . "` AS wsp
      			  	
      			  $where2
      			  AND wsp.active = 1
      			  ORDER BY wsp.ordering ASC";
      			   
      			  $html = '';
      			  if ($data2 = self::$db->pdoQuery ( $sql )->result ()) :
      			   
      			  $data1 = self::$db->pdoQuery ( $sql2 )->results();
      			  // $html .= '<div class="field">';
      			  	
      			  	
      			  $vTotalPricingLines = self::$db->count(false,false, "SELECT COUNT(*) FROM `" . wSpecials::wspTable . "` $where2 AND active = 1  LIMIT 1");
      			  $dataws = $data2;
      			  $dealtype = '';
      			   
      			  switch( $dealtype ) {
      			  	case "lease":
      			  		$show['lease'] = TRUE;
      			  		$show['single_pay'] = TRUE;
      			  		$show['zero_down'] = TRUE;
      			  		$show['apr'] = FALSE;
      			  		$show['buy_price'] = FALSE;
      			  		$show['cust_price'] = FALSE;
      			  		$show['savings'] = FALSE;
      			  		break;
      			  	case "zero down":
      			  		$show['lease'] = TRUE;
      			  		$show['single_pay'] = TRUE;
      			  		$show['zero_down'] = TRUE;
      			  		$show['apr'] = FALSE;
      			  		$show['buy_price'] = FALSE;
      			  		$show['cust_price'] = FALSE;
      			  		$show['savings'] = FALSE;
      			  		break;
      			  	case "purchase":
      			  		$show['lease'] = FALSE;
      			  		$show['single_pay'] = FALSE;
      			  		$show['zero_down'] = FALSE;
      			  		$show['apr'] = TRUE;
      			  		$show['buy_price'] = TRUE;
      			  		$show['cust_price'] = TRUE;
      			  		$show['savings'] = TRUE;
      			  		break;
      			  	default:
      			  		$show['lease'] = TRUE;
      			  		$show['single_pay'] = TRUE;
      			  		$show['zero_down'] = TRUE;
      			  		$show['apr'] = TRUE;
      			  		$show['buy_price'] = TRUE;
      			  		$show['cust_price'] = TRUE;
      			  		$show['savings'] = TRUE;
      			  }
      			   
      			  //$html .= '<div class="qsPage">'; //page container
      			   
      			  $cfArr = array();
      			   
      			  foreach ( $dataws  as $key => $val ) {
      
      			  	$cfArr[$key] = $val;
      			  } 		   
      			  
      			   
      			  $modelmod = $cfArr['modelname_ws'];
      			   
      			  switch($modelmod) {
      			  	case "F150":
      			  		$modelmod="F-150";
      			  		break;
      			  	case "F250":
      			  		$modelmod="F-250";
      			  		break;
      			  	case "F350":
      			  		$modelmod="F-350";
      			  		break;
      			  	case "E350":
      			  		$modelmod="E-350";
      			  		break;
      			  	case "CMax":
      			  		$modelmod="C-Max";
      			  		break;
      			  	case "F350 DRW":
      			  		$modelmod="F-350 DRW";
      			  		break;
      			  	case "F650 DRW":
      			  		$modelmod="F-650 DRW";
      			  		break;
      			  }
      			   
      			  $cssLetter = $cfArr['store_letter'];
      			   
      			  switch($cssLetter) {
      			  	case "a":
      			  		$cssLetter="y";
      			  		break;
      			  	case "A":
      			  		$cssLetter="y";
      			  		break;
      			  	case "b":
      			  		$cssLetter="p";
      			  		break;
      			  	case "B":
      			  		$cssLetter="p";
      			  		break;
      			  	default:
      			  		$cssLetter = strtolower($cfArr['store_letter']);
      			  }
      			         			   
      			  
      			  //
      			  //  Set special class if only one side of vertical pricing is available
      			  //
      			  $onlyMonthly = "";
      			   
      			  if( ( !isset($cfArr['save_up_to_amount']) || $cfArr['save_up_to_amount'] == '' || $cfArr['save_up_to_amount'] == '0' ) && ( !isset($cfArr['buy_price']) || $cfArr['buy_price'] == '' || $cfArr['buy_price'] == '0' ) && ( !isset($cfArr['price_with_lease_conquest']) || $cfArr['price_with_lease_conquest'] == '' || $cfArr['price_with_lease_conquest'] == '0' ) && ( !isset($cfArr['price_with_owner_loyalty']) || $cfArr['price_with_owner_loyalty'] == '' || $cfArr['price_with_owner_loyalty'] == '0' ) ){
      			  	$onlyMonthly = "single";
      			  }
      			   
      			  $onlyPurchase = "";
      			   
      			  if( ( !isset($cfArr['lease_price']) || $cfArr['lease_price'] == 0 || $cfArr['lease_price'] == '' ) && (!isset($cfArr['single_lease_price']) || $cfArr['single_lease_price'] == 0 || $cfArr['single_lease_price'] == '' ) && ( !isset($cfArr['zero_down_lease_price']) || $cfArr['zero_down_lease_price'] == 0 || $cfArr['zero_down_lease_price'] == '' ) && (!isset($cfArr['available_apr']) || $cfArr['available_apr'] == '') ){
      			  	$onlyPurchase = "single";
      			  }
      			   
      			  $featuredSpecial = (isset($cfArr['featured_special']) && $cfArr['featured_special'] == '1') ? 'featured' : 'regular' ;
      			   
      			  $display_lease = '';
      			  $display_zero_down = '';
      			   
      			   
      			  if(isset($cfArr['lease_price']) && $cfArr['lease_price'] != '' && $cfArr['lease_price'] != 0 && $show['lease'] && isset($cfArr['zero_down_lease_price']) && $cfArr['zero_down_lease_price'] != '' && $cfArr['zero_down_lease_price'] != 0 && $show['zero_down'] && (!isset($cfArr['single_lease_price']) || $cfArr['single_lease_price'] == '' || $cfArr['single_lease_price'] == 0)) {		//show lease and zero down
      			  	$display_lease = '<div class="specialLease">';
      			  	$display_lease .= '<div class="price1 NCSrightborder"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['lease_price'].'.00">$</span>'.$cfArr['lease_price'].'<span>/mo</span></div>';
      			  	$display_lease .= '<div class="label2">w/ $'.$cfArr['lease_extras'].' down for '.$cfArr['lease_term'].' mos.*</div>';
      			  	$display_lease .= '</div>';
      			  	$display_zero_down = '<div class="specialZeroDown">';
      			  	$display_zero_down .= '<div class="price1"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['zero_down_lease_price'].'.00">$</span>'.$cfArr['zero_down_lease_price'].'<span>/mo</span></div>';
      			  	$display_zero_down .= '<div class="label2">w/ $0 down for '.$cfArr['zero_down_lease_term'].' mos.*</div>';
      			  	$display_zero_down .= '</div>';
      			  }else if(isset($cfArr['single_lease_price']) && $cfArr['single_lease_price'] != '' && $cfArr['single_lease_price'] != 0 && $show['single_pay'] && ((isset($cfArr['lease_price']) && $cfArr['lease_price'] != '' && $cfArr['lease_price'] != 0) || (!isset($cfArr['lease_price']) || $cfArr['lease_price'] == '' || $cfArr['lease_price'] == 0)) && isset($cfArr['zero_down_lease_price']) && $cfArr['zero_down_lease_price'] != '' && $cfArr['zero_down_lease_price'] != 0 && $show['zero_down']) {  //show single pay lease and zero down
      			  	$display_lease = '<div class="specialLease">';
      			  	$display_lease .= '<div class="price1 NCSrightborder singleLease"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['single_lease_price'].'.00">$</span>'.number_format($cfArr['single_lease_price']).'</div>';
      			  	$display_lease .= '<div class="label2">for '.$cfArr['single_lease_term'].' months, '.$cfArr['single_lease_miles'].',000 miles</div>';
      			  	$display_lease .= '</div>';
      			  	$display_zero_down = '<div class="specialZeroDown">';
      			  	$display_zero_down .= '<div class="price1"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['zero_down_lease_price'].'.00">$</span>'.$cfArr['zero_down_lease_price'].'<span>/mo</span></div>';
      			  	$display_zero_down .= '<div class="label2">w/ $0 down for '.$cfArr['zero_down_lease_term'].' mos.*</div>';
      			  	$display_zero_down .= '</div>';
      			  }else if (isset($cfArr['single_lease_price']) && $cfArr['single_lease_price'] != '' && $cfArr['single_lease_price'] != 0 && $show['single_pay'] && ((isset($cfArr['lease_price']) && $cfArr['lease_price'] != '' && $cfArr['lease_price'] != 0) || (!isset($cfArr['lease_price']) || $cfArr['lease_price'] == '' || $cfArr['lease_price'] == 0)) ) {   //show single pay lease only
      			  	$display_lease = '<div class="specialLease">';
      			  	$display_lease .= '<div class="price1 singleLease"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['single_lease_price'].'.00">$</span>'.number_format($cfArr['single_lease_price']).'</div>';
      			  	$display_lease .= '<div class="label2">for '.$cfArr['single_lease_term'].' months, '.$cfArr['single_lease_miles'].',000 miles</div>';
      			  	$display_lease .= '</div>';
      			  }else if ( isset($cfArr['lease_price']) && $cfArr['lease_price'] != '' && $cfArr['lease_price'] != 0 && (!isset($cfArr['single_lease_price']) || $cfArr['single_lease_price'] == '' || $cfArr['single_lease_price'] == 0) && $show['lease'] ) {  //show lease only
      			  	$display_lease = '<div class="specialLease">';
      			  	$display_lease .= '<div class="price1"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['lease_price'].'.00">$</span>'.$cfArr['lease_price'].'<span>/mo</span></div>';
      			  	$display_lease .= '<div class="label2">w/ $'.$cfArr['lease_extras'].' down for '.$cfArr['lease_term'].' mos.*</div>';
      			  	$display_lease .= '</div>';
      			  }else if( isset($cfArr['zero_down_lease_price']) && $cfArr['zero_down_lease_price'] != '' && $cfArr['zero_down_lease_price'] != 0 && $show['zero_down'] ) { //show zero down only
      			  	$display_zero_down = '<div class="specialZeroDown">';
      			  	$display_zero_down .= '<div class="price1"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['zero_down_lease_price'].'.00">$</span>'.$cfArr['zero_down_lease_price'].'<span>/mo</span></div>';
      			  	$display_zero_down .= '<div class="label2">w/ $0 down for '.$cfArr['zero_down_lease_term'].' mos.*</div>';
      			  	$display_zero_down .= '</div>';
      			  }
      			   
      			  //
      			  //  Start the special HTML and content
      			  $logo = $cfArr['logo'];
      			  $linklocation = 'showrooms/';
      			  $link =  UPLOADURL .$linklocation .$logo;
      			  //$html .= '<link rel="stylesheet" type="text/css" href="http://ncs.quirkspecials.com/newplugincss/' . $cssLetter . '/specials-style.css" />';
      			 /* $html .= '<script id="specialsFunctions" type="text/javascript" src=" ' . Url::adminUrl ( "assets", false, "/js/specials-functions.js" ) . '"></script>';
      			  $html .= '<div class="header">';
      			  $html .= '<h3 class="ncs_model_title">**Web Special Preview**</h3>';
      			  $html .= '<img src="' . $link . '? ' . $link . ': "blank.png" " height="30" width="30" alt="">';
      			  $html .= '<div class="content">';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="qsContent"  data-featured=" ' . $featuredSpecial . ' " itemtype="http://schema.org/Product" itemscope>';
      			  $html .= '<div class="qsInnerContent">';
      			  $html .= '<div class="vehicleMedia">';
      			  $html .= '<div class="vehicleMedia_inner_wrap">';
      			  $html .= '<div class="ncsImgWrapper">';
      			  $html .= '<a>';
      			  $html .= '<link itemprop="additionalType" href="http://productontology.org/id/automobile">';
      			  if (isset ( $cfArr ['tagline'] ) && $cfArr ['tagline'] != '') {
      			  	$html .= '<span>' . $cfArr ['tagline'] . '</span>';
      			  }
      			  $html .= '<img src="' . $cfArr ['vehicle_image'] . '" alt="' . $cfArr ['year'] . '" "' . $cfArr ['makename_ws'] . '" "' . $cfArr ['modelname_ws'] . '" "' . $cfArr ['trim_level'] . '">';
      			  $html .= '</a>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_title_save_wrap_mobile">';
      			  $html .= '<div class="ncs_title_wrap">';
      			  $html .= '<a>';
      			  $html .= '<h3 class="ncs_model_title">';
      			   
      			  $html .= '<div class="ncs_top_title" ncs-data-name="New  ' . $cfArr ['year'] . ' ' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . ' ' . $modelmod . '">';
      			  $html .= '<span itemprop="itemCondition">New</span>';
      			  $html .= '<span itemprop="modelYear"> ' . $cfArr ['year'] . '</span>';
      			  $html .= '<span itemprop="manufacturer"> ' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . '</span> ';
      			  $html .= '<span itemprop="model"> ' . $modelmod . '</span>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_top_trim">';
      			  $html .= '<span itemprop="modelTrim"> ' . $cfArr ['trim_level'] . ' </span>';
      			  $html .= '</div>';
      			  $html .= '</h3>';
      			  $html .= '</a>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_mobile_top_content_wrap">';
      			  $html .= '<div class="MonthlyPricingTop">';
      			   
      			  if ($onlyPurchase != "single") {
      			  	$html .= '<div class="monthlyPricingTopContainer  ' . $onlyMonthly . ' ' . $dealtype . ' " itemprop="offers" itemtype="http://schema.org/Offer">';
      
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseWrapperTop">';
      			  	}
      			  	if ((isset ( $cfArr ['zero_down_lease_price'] ) && $cfArr ['zero_down_lease_price'] != '' && $cfArr ['zero_down_lease_price'] != 0 && (! isset ( $cfArr ['lease_price'] ) || $cfArr ['lease_price'] == '' || $cfArr ['lease_price'] == 0) && (! isset ( $cfArr ['single_lease_price'] ) || $cfArr ['single_lease_price'] == '' || $cfArr ['single_lease_price'] == 0))) {
      			  		 
      			  		$html .= '<div class="label1 l1zDown">ZERO DOWN LEASE:</div>';
      			  	} else if (isset ( $cfArr ['single_lease_price'] ) && $cfArr ['single_lease_price'] != '' && $cfArr ['single_lease_price'] != 0) {
      			  		$html .= '<div class="label1">SINGLE PAY LEASE:</div>';
      			  	} else if (isset ( $cfArr ['lease_price'] ) && $cfArr ['lease_price'] != '' && $cfArr ['lease_price'] != 0 && ! isset ( $cfArr ['single_lease_price'] )) {
      			  		$html .= '<div class="label1">LEASE FOR:</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseInnerWrapper">';
      			  		 
      			  		if ($dealtype == "zero down") {
      			  			$html .= $display_zero_down;
      			  			$html .= $display_lease;
      			  		} else {
      			  			$html .= $display_lease;
      			  			$html .= $display_zero_down;
      			  		}
      			  		$html .= '</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '</div>';
      			  	}
      			  	$html .= '</div>';
      			  }
      			   
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_mobile_top_content_expanded">';
      			  $html .= '<div class="ncs_mobile_top_content_flex">';
      			   
      			  if (isset ( $cfArr ['available_apr'] ) && $cfArr ['available_apr'] != '' && $show ['apr']) {
      			  	$html .= '<div class="specialAPRMobile">';
      			  	$html .= '<div class="aprmobileLabel">available APR: <span> ' . doubleval ( $cfArr ['available_apr'] ) . ' %<span class="aprmobileasterisk">*</span></span></div>';
      			  	$html .= '</div>';
      			  }
      			  if ($dealtype == '') {
      			  	if (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings']) {
      			  		$html .= '<div class="ncs_saving_up_to_mobile">';
      			  		$html .= '<div class="ncs_save_content_mobile">save up to: <span>$ ' . number_format ( $cfArr ['save_up_to_amount'] ) . ' </span></div>';
      			  		$html .= '</div>';
      			  	}
      			  }
      			   
      			  $html .= '<div class="quirkPriceTopMobile">';
      			   
      			  if (isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) {
      			  	$html .= '<div class="qPriceMobileTop">';
      			  	$html .= ' Quirk Price: <span itemprop="price" content=" ' . $cfArr ['buy_price'] . ' .00">$ ' . number_format ( $cfArr ['buy_price'] ) . ' </span>';
      			  	$html .= '</div>';
      			  }
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_pricing_wrap">';
      			  $html .= '<div class="ncs_title_save_wrap">';
      			  $html .= '<div class="ncs_title_save_wrap_innernew">';
      			  $html .= '<div class="ncs_title_wrap">';
      			  $html .= '<a>';
      			  $html .= '<h3 class="ncs_model_title">';
      			  $html .= '<div class="ncs_top_title" ncs-data-name="New  ' . $cfArr ['year'] . ' ' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . ' ' . $modelmod . '">';
      			  $html .= '<span itemprop="itemCondition">New</span>';
      			  $html .= ' <span itemprop="modelYear"> ' . $cfArr ['year'] . ' </span>';
      			  $html .= '<span itemprop="manufacturer"> ' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . '</span> ';
      			  $html .= '<span itemprop="model"> ' . $modelmod . ' </span>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_top_trim">';
      			  $html .= '<span itemprop="modelTrim"> ' . $cfArr ['trim_level'] . '</span>';
      			  $html .= '</div>';
      			  $html .= '</h3>';
      			  $html .= '</a>';
      			  $html .= '</div>';
      			  $html .= '<div class="quirkPriceTop">';
      			   
      			  if (isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) {
      			  	$html .= '<div class="qPriceTop">';
      			  	$html .= '<span class="qPriceTextTop">Quirk Price: </span>';
      			  	$html .= '<span class="ncsDsign qDsignTop" itemprop="priceCurrency" content="USD">$</span><span class="ncsBuyPriceTop" itemprop="price" content=" ' . $cfArr ['buy_price'] . ' .00"> ' . number_format ( $cfArr ['buy_price'] ) . ' </span>';
      			  	$html .= '</div>';
      			  }
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="MonthlyPricingTop">';
      			   
      			  if ($onlyPurchase != "single") {
      			  	$html .= '<div class="monthlyPricingTopContainer  ' . $onlyMonthly . '' . $dealtype . ' " itemprop="offers" itemtype="http://schema.org/Offer">';
      
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseWrapperTop">';
      			  	}
      			  	if ($dealtype == "zero down" || (isset ( $cfArr ['zero_down_lease_price'] ) && $cfArr ['zero_down_lease_price'] != '' && $cfArr ['zero_down_lease_price'] != 0 && (! isset ( $cfArr ['lease_price'] ) || $cfArr ['lease_price'] == '' || $cfArr ['lease_price'] == 0) && (! isset ( $cfArr ['single_lease_price'] ) || $cfArr ['single_lease_price'] == '' || $cfArr ['single_lease_price'] == 0))) {
      			  		 
      			  		$html .= '<div class="label1 l1zDown">ZERO DOWN LEASE:</div>';
      			  	} else if (isset ( $cfArr ['single_lease_price'] ) && $cfArr ['single_lease_price'] != '' && $cfArr ['single_lease_price'] != 0) {
      			  		$html .= '<div class="label1">SINGLE PAY LEASE:</div>';
      			  	} else if (isset ( $cfArr ['lease_price'] ) && $cfArr ['lease_price'] != '' && $cfArr ['lease_price'] != 0 && ! isset ( $cfArr ['single_lease_price'] )) {
      			  		$html .= '<div class="label1">LEASE FOR:</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseInnerWrapper">';
      			  		 
      			  		if ($dealtype == "zero down") {
      			  			$html .= $display_zero_down;
      			  			$html .= $display_lease;
      			  		} else {
      			  			$html .= $display_lease;
      			  			$html .= $display_zero_down;
      			  		}
      			  		$html .= '</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '</div>';
      			  	}
      			  	$html .= '</div>';
      			  }
      			   
      			  $html .= '<div class="ncs_saving_up_to">';
      			   
      			  if ($dealtype == '') {
      			  	if (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings']) {
      			  		$html .= '<div class="ncs_save_text">save up to:</div>';
      			  		$html .= '<div class="ncs_save_currency">$ ' . number_format ( $cfArr ['save_up_to_amount'] ) . ' </div>';
      			  	}
      			  }
      			   
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="NCS_deatail_CTA_Expand">';
      			  $html .= '<div class="ncs_detail_expand">';
      			  $html .= '<a>';
      			  $html .= '<div class="specialsInfoWrap">';
      			  $html .= '<div class="allPricing">';
      			   
      			  if ($onlyPurchase != "single" || ((isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) || ($show ['savings'] && $vTotalPricingLines > 0))) {
      			  	if (! isset ( $cfArr ['zero_down_lease_price'] ) && ! isset ( $cfArr ['single_lease_price'] ) && ! isset ( $cfArr ['lease_price'] ) && $show ['savings'] && $vTotalPricingLines > 0) {
      			  		$html .= '<div class="monthlyPricing  ' . $onlyMonthly . '' . $dealtype . '  mpDTNone" itemprop="offers" itemtype="http://schema.org/Offer">';
      			  	} else {
      			  		$html .= '<div class="monthlyPricing  ' . $onlyMonthly . '' . $dealtype . ' " itemprop="offers" itemtype="http://schema.org/Offer">';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseWrapper">';
      			  	}
      			  	if ($dealtype == "zero down" || (isset ( $cfArr ['zero_down_lease_price'] ) && $cfArr ['zero_down_lease_price'] != '' && $cfArr ['zero_down_lease_price'] != 0 && (! isset ( $cfArr ['lease_price'] ) || $cfArr ['lease_price'] == '' || $cfArr ['lease_price'] == 0) && (! isset ( $cfArr ['single_lease_price'] ) || $cfArr ['single_lease_price'] == '' || $cfArr ['single_lease_price'] == 0))) {
      			  		 
      			  		$html .= '<div class="label1 l1zDown">ZERO DOWN LEASE:</div>';
      			  	} else if (isset ( $cfArr ['single_lease_price'] ) && $cfArr ['single_lease_price'] != '' && $cfArr ['single_lease_price'] != 0) {
      			  		$html .= '<div class="label1">SINGLE PAY LEASE:</div>';
      			  	} else if (isset ( $cfArr ['lease_price'] ) && $cfArr ['lease_price'] != '' && $cfArr ['lease_price'] != 0 && ! isset ( $cfArr ['single_lease_price'] )) {
      			  		$html .= '<div class="label1">LEASE FOR:</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseInnerWrapper">';
      			  		 
      			  		if ($dealtype == "zero down") {
      			  			$html .= $display_zero_down;
      			  			$html .= $display_lease;
      			  		} else {
      			  			$html .= $display_lease;
      			  			$html .= $display_zero_down;
      			  			// var_dump($display_zero_down);
      			  		}
      			  		$html .= '</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '</div>';
      			  	}
      			  	if ($dealtype == '' && isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0) {
      			  		$html .= '<div class="qSavings ncs_tableWrap ncs_tablewrap_mobile">';
      			  		$html .= '<table>';
      			  		foreach ( $data1 as $row2 ) :
      			  		$html .= '<tr>';
      			  		$html .= '<td>' . $row2->name . '</td>';
      			  		$html .= '<td>$' . number_format ( $row2->price ) . '</td>';
      			  		$html .= '</tr>';
      			  		endforeach
      			  		;
      			  		unset ( $row2 );
      			  		$html .= '</table>';
      			  		$html .= '</div>';
      			  	}
      			  	$html .= '</div>';
      			  }
      			  $html .= '</div>';
      			  $html .= '</div>';
      			   
      			  if ($dealtype == '' && ((isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) || (isset ( $cfArr ['available_apr'] ) && $cfArr ['available_apr'] != '' && $show ['apr']) || (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0))) {
      			  	$html .= '<div class="ncs_quirk_price_wrap">';
      			  }
      			  if ($dealtype == '' && ((isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) || (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0))) {
      
      			  	if ($onlyMonthly != "single" && $dealtype != "lease" && $dealtype != "zero down" && $dealtype != "single pay" && $dealtype == '' && isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price'] && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0) {
      			  		$html .= '<div class="ncs_pricing_savings_halfWrap">';
      			  		$html .= '<div class="salePricing ncs_fiftyPercent  ' . $onlyPurchase . ' " itemprop="offers" itemtype="http://schema.org/Offer">';
      			  		$html .= '<div class="salePricingBuyPrices">';
      			  		$html .= '<div class="quirkPrice">';
      			  		$html .= '<div class="qPriceText">Quirk Price</div>';
      			  		$html .= '<div class="qPriceCurrency">';
      			  		$html .= '<span class="ncsDsign qDsign" itemprop="priceCurrency" content="USD">$</span><span itemprop="price" content=" ' . $cfArr ['buy_price'] . ' .00"> ' . number_format ( $cfArr ['buy_price'] ) . ' </span>';
      			  		$html .= ' </div>';
      			  		$html .= '</div>';
      			  		$html .= ' </div>';
      			  		$html .= '</div>';
      			  		$html .= '<div id="ncs_fiftyPercent" class="qSavings ncs_fiftyPercent">';
      			  		$html .= '<table>';
      			  		foreach ( $data1 as $row3 ) :
      			  		$html .= '<tr>';
      			  		$html .= '<td>' . $row3->name . '</td>';
      			  		$html .= '<td>$' . number_format ( $row3->price ) . '</td>';
      			  		$html .= '</tr>';
      			  		endforeach
      			  		;
      			  		unset ( $row3 );
      			  		$html .= '</table>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  	} else if ($onlyMonthly != "single" && $dealtype != "lease" && $dealtype != "zero down" && $dealtype != "single pay" && isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) {
      			  		$html .= '<div class="salePricing  ' . $onlyPurchase . '" itemprop="offers" itemtype="http://schema.org/Offer">';
      			  		$html .= '<div class="salePricingBuyPrices">';
      			  		$html .= '<div class="quirkPrice">';
      			  		$html .= '<div class="qPriceText">Quirk Price</div>';
      			  		$html .= '<div class="qPriceCurrency">';
      			  		$html .= '<span class="ncsDsign qDsign" itemprop="priceCurrency" content="USD">$</span><span itemprop="price" content=" ' . $cfArr ['buy_price'] . ' .00"> ' . number_format ( $cfArr ['buy_price'] ) . ' </span>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  	} else {
      			  		if ($dealtype == '' && isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0) {
      			  			$html .= '<div id="ncs_tableWrap" class="qSavings ncs_tableWrap">';
      			  			$html .= '<table>';
      			  			foreach ( $data1 as $row4 ) :
      			  			$html .= '<tr>';
      			  			$html .= '<td>' . $row4->name . '</td>';
      			  			$html .= '<td>$ ' . number_format ( $row4->price ) . ' </td>';
      			  			$html .= '</tr>';
      			  			endforeach
      			  			;
      			  			unset ( $row4 );
      			  			$html .= '</table>';
      			  			$html .= '</div>';
      			  		}
      			  	}
      			  }
      			   
      			  if (isset ( $cfArr ['available_apr'] ) && $cfArr ['available_apr'] != '' && $show ['apr']) {
      			  	$html .= '<div class="salePricingQuirk" itemprop="offers" itemtype="http://schema.org/Offer">';
      			  	$html .= '<div class="salePricingBuyPrices">';
      			  	$html .= '<div class="specialAPR">';
      			  	$html .= '<div class="label1">AVAILABLE APR:</div>';
      			  	$html .= '<div class="price1"> ' . doubleval ( $cfArr ['available_apr'] ) . ' %</div>';
      			  	$html .= '<div class="label2"> ' . (($cfArr ['apr_text'] == '') ? "APR available*" : $cfArr ['apr_text'] . "*") . ' </div>';
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      			  }
      			  if ($dealtype == '' && ((isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) || (isset ( $cfArr ['available_apr'] ) && $cfArr ['available_apr'] != '' && $show ['apr']) || (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0))) {
      			  	$html .= '</div>';
      			  }
      			  $html .= '</a>';
      			   
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  {
      
      			  	$valsArr = array ();
      
      			  	foreach ( $dataws as $key => $val ) {
      			  		 
      			  		$valsArr [$key] = $val;
      			  	}
      
      			  	$storeLetter = $valsArr ['store_letter'];
      			  	$interests = $show;
      
      			  	$html .= '<div class="specialsShortCodeFormContainer">';
      
      			  	$html .= '<div class="NCSShortCodeFormOptionWrapper">';
      			  	$html .= '<div class="NCSShortCodeFormLeft">';
      			  	$html .= '<div class="NCSSCFormInput">';
      			  	$html .= '<div class="NCSSCFormPrimaryInput">';
      			  	$html .= '<div class="NCSFormIPIndividual">';
      			  	$html .= '<label>First Name:</label>';
      			  	$html .= '<input type="text" class="customer_first" name="customer_first" tabindex="1"/>';
      			  	$html .= '<p class="NCSRequiredFormParagraph"></p>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSFormIPIndividual">';
      			  	$html .= '<label>Last Name:</label>';
      			  	$html .= '<input type="text" class="customer_last" name="customer_last" tabindex="2"/>';
      			  	$html .= '<p class="NCSRequiredFormParagraph"></p>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSFormIPIndividual">';
      			  	$html .= '<label>Email:</label>';
      			  	$html .= '<input type="email" class="customer_email" name="customer_email" tabindex="3"/>';
      			  	$html .= '<p class="NCSRequiredFormParagraph"></p>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSFormIPIndividual">';
      			  	$html .= '<label>Phone Number:</label>';
      			  	$html .= '<input type="text" class="customer_phone" name="customer_phone" tabindex="4"/>';
      			  	$html .= '<p class="NCSRequiredFormParagraph"></p>';
      
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      
      			  	$hasLease = FALSE;
      			  	$hasSingle = FALSE;
      			  	$hasZD = FALSE;
      			  	$hasAPR = FALSE;
      			  	$hasSUT = FALSE;
      			  	$hasCustomPrice = FALSE;
      
      			  	if (isset ( $valsArr ['lease_price'] ) && $valsArr ['lease_price'] != '' && $valsArr ['lease_price'] != 0 && (! isset ( $valsArr ['single_lease_price'] ) || $valsArr ['single_lease_price'] == 0 || $valsArr ['single_lease_price'] == '') && isset ( $interests ['lease'] )) {
      			  		$hasLease = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['single_lease_price'] ) && $valsArr ['single_lease_price'] != '' && $valsArr ['single_lease_price'] != 0 && isset ( $interests ['single_pay'] )) {
      			  		$hasSingle = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['zero_down_lease_price'] ) && $valsArr ['zero_down_lease_price'] != '' && $valsArr ['zero_down_lease_price'] != 0 && isset ( $interests ['zero_down'] )) {
      			  		$hasZD = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['available_apr'] ) && $valsArr ['available_apr'] != '' && $interests ['apr']) {
      			  		$hasAPR = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['save_up_to_amount'] ) && $valsArr ['save_up_to_amount'] != '' && $valsArr ['save_up_to_amount'] != '0' && isset ( $interests ['savings'] )) {
      			  		$hasSUT = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['custom_price_val'] ) && $valsArr ['custom_price_val'] != '' && $valsArr ['custom_price_val'] != '0' && isset ( $interests ['cust_price'] )) {
      			  		$hasCustomPrice = TRUE;
      			  	}
      			  	if ($hasLease == TRUE || $hasSingle == TRUE || $hasZD == TRUE || $hasAPR == TRUE || $hasSUT == TRUE || $hasCustomPrice == TRUE) {
      			  		 
      			  		$html .= '<div class="NCSSCFormOptions">';
      			  		$html .= '<div class="NCSSCFormOptionsCenterDiv">';
      			  		$html .= '<h5>I am interested in&nbsp;<span>(optional):</span></h5>';
      			  		 
      			  		if ($hasLease) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_lease" tabindex="6" value="Interested in lease w/money down." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span>$ ' . number_format ( $valsArr ['lease_price'] ) . ' /mo with $ ' . number_format ( $valsArr ['lease_extras'] ) . '  down</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		if ($hasSingle) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_single" tabindex="6" value="Interested in single pay lease." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span>$ ' . number_format ( $valsArr ['single_lease_price'] ) . '  single pay lease</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		if ($hasZD) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_zero_down" tabindex="7" value="Interestested in zero down lease." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span>$ ' . number_format ( $valsArr ['zero_down_lease_price'] ) . ' /mo with $0 down</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		if ($hasAPR) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_apr" tabindex="8" value="Interested in promotional APR." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span> ' . doubleval ( $valsArr ['available_apr'] ) . ' % APR financing</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		if ($hasSUT) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_savings" tabindex="9" value="Interested in purchase savings." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span>$ ' . number_format ( $valsArr ['buy_price'] ) . '  quirk price</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		// if( $hasCustomPrice ){
      			  		/*
      			  		 * div class="NCSIndividualOptions">
      			  		 * $html .= '<div class="NCSOptionChecker">
      			  		 * <input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_custom" tabindex="10" value="Interested in price with . $valsArr['custom_price_label']; ." />
      			  		 * <label class="NCSCheckboxLabel"></label>
      			  		 * </div>
      			  		 * $html .= '<div class="NCSOptionText">
      			  		 * <span>&nbsp;&nbsp;This price with . strtolower( $valsArr['custom_price_label'] ); </span>
      			  		 * </div>
      			  		 * </div
      			  		 */
      			  		 
      			  		// }
      			  		/*$html .= '</div>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  		$html .= '<div class="NCSFormSCDisc">';
      			  		$html .= '<p class="formDisclaimer">' . $valsArr ['disclaimer_text'] . ' </p>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  	}
      			  	$html .= '<div class="NCSShortCodeFormRight">';
      			  	$html .= '<div class="ncsShortCodeComments">';
      			  	$html .= '<label>Comments:</label>';
      			  	$html .= '<textarea id="customer_comments" name="customer_comments" tabindex="5"></textarea>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="specialCTAs">';
      			  	$html .= '<a class="valueTrade" <span>Value Trade</span></a>';
      			  	$html .= '<a class="viewInventory" href="' . $valsArr ['alt_link_url'] . '"><span>Inventory</span></a>';
      			  	$html .= '<button type="button" class="ncsFormSubmit" name="special_submit" rel=" ' . $valsArr ['webspecials_id'] . ' ">Submit</button>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSSCFormdiscMobileBtn">';
      			  	$html .= '<span>full disclaimer</span>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSFormSCDiscMobile">';
      			  	$html .= '<p class="formDisclaimerMobile"> ' . $valsArr ['disclaimer_text'] . ' </p>';
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      
      			  	$html .= '</div>';
      			  }
      			   
      			  $html .= '<div class="qsTab">';
      			  $html .= '<div class="qsTabTitle">';
      			  $html .= '<span itemprop="itemCondition">New</span>';
      			  $html .= '<span itemprop="modelYear">' . $cfArr ['year'] . ' </span>';
      			  $html .= '<span itemprop="manufacturer">' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . ' </span>';
      			  $html .= '<span itemprop="model">' . $modelmod . ' </span>';
      			  $html .= '<span itemprop="modelTrim">' . $cfArr ['trim_level'] . ' </span>';
      			  $html .= '</div>';
      			   
      			  $html .= '<div class="qsTabOpenClose">';
      			  $html .= '<div class="ncsClose">';
      			  $html .= '<span>CLICK TO CLOSE</span>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncsExpand">';
      			  $html .= '<span>GET THIS PRICE</span>';
      			  $html .= '</div>';
      			  $html .= '<img class="ncsCloseArrow" src="' . Url::adminUrl ( "assets", false, "/images/Up-Arrow.png" ) . '"/><img class="ncsExpandArrow" src="' . Url::adminUrl ( "assets", false, "/images/Down-Arrow.png" ) . '"/>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			   
      			  $findResults = true;
      			   
      			  if (! $findResults) {
      			  	$html .= '<span class="noNCS">No specials found at this time.  Please check back later.</span>';
      			  }
      			  $html .= '</div>'; // end page container*/
      			   
      			  //$html .= '</div>';
      			  //$uniqid = uniqid();
      			  //$pricing_array = array();
      			  /*$pricing_counter = 0;
      			  foreach($data1 as $prow){      			      
      			      //$pricing_array[$prow->name] = $prow->price;
      			      $pricing_counter++;
      			      $cfArr['pricing_'.$pricing_counter.'_name'] = $prow->name;
      			      $cfArr['pricing_'.$pricing_counter.'_value'] = $prow->price;
      			  }   
      			  $cfArr['total_pricing_lines'] = $pricing_counter; 
      			  
      			  $html .= "<div id='ncsMain' data-ncsid='".$uniqid."' data-ncsarray='".json_encode($cfArr)."' data-formimg='".Url::adminUrl ( "assets", false, "images/click_to_call_cta.png" )."' class='qsPage'></div>";      			  
      			  //$html .= '<script id="specialsFunctions" type="text/babel" src=" ' . Url::adminUrl ( "assets", false, "/js/ncs_dev.js" ) . '"></script>';
      			  
      			  print $html;
      			   
      			  endif;
      			   
      }*/
     
      
      /**
       * Content::getwebspecialspreviewbystore()
       *
       * @return
       */
      public  static function getwebspecialspreviewbystore($storeid,$id) {
      
      	$where = "WHERE w_s.store_id = $storeid" ;
      	$where2 = "WHERE special_id = $id" ;
      	$sql = "
			SELECT
			  w_s.*,
	  		 lc.logo
			 FROM
			  `" . wSpecials::w_sTable . "` AS w_s
  			  LEFT JOIN `" . Content::lcTable . "` AS lc
        			  ON lc.store_id = w_s.store_id
      
        			  $where
      
        			  ";
      
        			  $sql2 = "
			SELECT
			  wsp.*
			  FROM `" . wSpecials::wspTable . "` AS wsp
      
      			  $where2
      			  AND wsp.active = 1
      			  ORDER BY wsp.ordering ASC";
      
      			  $html = '';
      			  if ($data3 = self::$db->pdoQuery ($sql)->result()) :
      
      
      			  $data1 = self::$db->pdoQuery ( $sql2 )->results();
      			  // $html .= '<div class="field">';
      
      
      			  $vTotalPricingLines = self::$db->count(false,false, "SELECT COUNT(*) FROM `" . wSpecials::wspTable . "` $where2 AND active = 1  LIMIT 1");
      			  $dataws = $data3;
      			  var_dump($dataws);
      			  $dealtype = '';
      
      			  switch( $dealtype ) {
      			  	case "lease":
      			  		$show['lease'] = TRUE;
      			  		$show['single_pay'] = TRUE;
      			  		$show['zero_down'] = TRUE;
      			  		$show['apr'] = FALSE;
      			  		$show['buy_price'] = FALSE;
      			  		$show['cust_price'] = FALSE;
      			  		$show['savings'] = FALSE;
      			  		break;
      			  	case "zero down":
      			  		$show['lease'] = TRUE;
      			  		$show['single_pay'] = TRUE;
      			  		$show['zero_down'] = TRUE;
      			  		$show['apr'] = FALSE;
      			  		$show['buy_price'] = FALSE;
      			  		$show['cust_price'] = FALSE;
      			  		$show['savings'] = FALSE;
      			  		break;
      			  	case "purchase":
      			  		$show['lease'] = FALSE;
      			  		$show['single_pay'] = FALSE;
      			  		$show['zero_down'] = FALSE;
      			  		$show['apr'] = TRUE;
      			  		$show['buy_price'] = TRUE;
      			  		$show['cust_price'] = TRUE;
      			  		$show['savings'] = TRUE;
      			  		break;
      			  	default:
      			  		$show['lease'] = TRUE;
      			  		$show['single_pay'] = TRUE;
      			  		$show['zero_down'] = TRUE;
      			  		$show['apr'] = TRUE;
      			  		$show['buy_price'] = TRUE;
      			  		$show['cust_price'] = TRUE;
      			  		$show['savings'] = TRUE;
      			  }
      
      			  $html .= '<div class="qsPage">'; //page container
      
      			  $cfArr = array();
      
      			  foreach ( $dataws  as $key => $val ) {
      
      			  	$cfArr[$key] = $val;
      			  }
      
      
      
      
      			  $modelmod = $cfArr['modelname_ws'];
      
      			  switch($modelmod) {
      			  	case "F150":
      			  		$modelmod="F-150";
      			  		break;
      			  	case "F250":
      			  		$modelmod="F-250";
      			  		break;
      			  	case "F350":
      			  		$modelmod="F-350";
      			  		break;
      			  	case "E350":
      			  		$modelmod="E-350";
      			  		break;
      			  	case "CMax":
      			  		$modelmod="C-Max";
      			  		break;
      			  	case "F350 DRW":
      			  		$modelmod="F-350 DRW";
      			  		break;
      			  	case "F650 DRW":
      			  		$modelmod="F-650 DRW";
      			  		break;
      			  }
      
      			  $cssLetter = $cfArr['store_letter'];
      
      			  switch($cssLetter) {
      			  	case "a":
      			  		$cssLetter="y";
      			  		break;
      			  	case "A":
      			  		$cssLetter="y";
      			  		break;
      			  	case "b":
      			  		$cssLetter="p";
      			  		break;
      			  	case "B":
      			  		$cssLetter="p";
      			  		break;
      			  	default:
      			  		$cssLetter = strtolower($cfArr['store_letter']);
      			  }
      
      
      
      			  //
      			  //  Set special class if only one side of vertical pricing is available
      			  //
      			  $onlyMonthly = "";
      
      			  if( ( !isset($cfArr['save_up_to_amount']) || $cfArr['save_up_to_amount'] == '' || $cfArr['save_up_to_amount'] == '0' ) && ( !isset($cfArr['buy_price']) || $cfArr['buy_price'] == '' || $cfArr['buy_price'] == '0' ) && ( !isset($cfArr['price_with_lease_conquest']) || $cfArr['price_with_lease_conquest'] == '' || $cfArr['price_with_lease_conquest'] == '0' ) && ( !isset($cfArr['price_with_owner_loyalty']) || $cfArr['price_with_owner_loyalty'] == '' || $cfArr['price_with_owner_loyalty'] == '0' ) ){
      			  	$onlyMonthly = "single";
      			  }
      
      			  $onlyPurchase = "";
      
      			  if( ( !isset($cfArr['lease_price']) || $cfArr['lease_price'] == 0 || $cfArr['lease_price'] == '' ) && (!isset($cfArr['single_lease_price']) || $cfArr['single_lease_price'] == 0 || $cfArr['single_lease_price'] == '' ) && ( !isset($cfArr['zero_down_lease_price']) || $cfArr['zero_down_lease_price'] == 0 || $cfArr['zero_down_lease_price'] == '' ) && (!isset($cfArr['available_apr']) || $cfArr['available_apr'] == '') ){
      			  	$onlyPurchase = "single";
      			  }
      
      			  $featuredSpecial = (isset($cfArr['featured_special']) && $cfArr['featured_special'] == '1') ? 'featured' : 'regular' ;
      
      			  $display_lease = '';
      			  $display_zero_down = '';
      
      
      			  if(isset($cfArr['lease_price']) && $cfArr['lease_price'] != '' && $cfArr['lease_price'] != 0 && $show['lease'] && isset($cfArr['zero_down_lease_price']) && $cfArr['zero_down_lease_price'] != '' && $cfArr['zero_down_lease_price'] != 0 && $show['zero_down'] && (!isset($cfArr['single_lease_price']) || $cfArr['single_lease_price'] == '' || $cfArr['single_lease_price'] == 0)) {		//show lease and zero down
      			  	$display_lease = '<div class="specialLease">';
      			  	$display_lease .= '<div class="price1 NCSrightborder"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['lease_price'].'.00">$</span>'.$cfArr['lease_price'].'<span>/mo</span></div>';
      			  	$display_lease .= '<div class="label2">w/ $'.$cfArr['lease_extras'].' down for '.$cfArr['lease_term'].' mos.*</div>';
      			  	$display_lease .= '</div>';
      			  	$display_zero_down = '<div class="specialZeroDown">';
      			  	$display_zero_down .= '<div class="price1"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['zero_down_lease_price'].'.00">$</span>'.$cfArr['zero_down_lease_price'].'<span>/mo</span></div>';
      			  	$display_zero_down .= '<div class="label2">w/ $0 down for '.$cfArr['zero_down_lease_term'].' mos.*</div>';
      			  	$display_zero_down .= '</div>';
      			  }else if(isset($cfArr['single_lease_price']) && $cfArr['single_lease_price'] != '' && $cfArr['single_lease_price'] != 0 && $show['single_pay'] && ((isset($cfArr['lease_price']) && $cfArr['lease_price'] != '' && $cfArr['lease_price'] != 0) || (!isset($cfArr['lease_price']) || $cfArr['lease_price'] == '' || $cfArr['lease_price'] == 0)) && isset($cfArr['zero_down_lease_price']) && $cfArr['zero_down_lease_price'] != '' && $cfArr['zero_down_lease_price'] != 0 && $show['zero_down']) {  //show single pay lease and zero down
      			  	$display_lease = '<div class="specialLease">';
      			  	$display_lease .= '<div class="price1 NCSrightborder singleLease"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['single_lease_price'].'.00">$</span>'.number_format($cfArr['single_lease_price']).'</div>';
      			  	$display_lease .= '<div class="label2">for '.$cfArr['single_lease_term'].' months, '.$cfArr['single_lease_miles'].',000 miles</div>';
      			  	$display_lease .= '</div>';
      			  	$display_zero_down = '<div class="specialZeroDown">';
      			  	$display_zero_down .= '<div class="price1"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['zero_down_lease_price'].'.00">$</span>'.$cfArr['zero_down_lease_price'].'<span>/mo</span></div>';
      			  	$display_zero_down .= '<div class="label2">w/ $0 down for '.$cfArr['zero_down_lease_term'].' mos.*</div>';
      			  	$display_zero_down .= '</div>';
      			  }else if (isset($cfArr['single_lease_price']) && $cfArr['single_lease_price'] != '' && $cfArr['single_lease_price'] != 0 && $show['single_pay'] && ((isset($cfArr['lease_price']) && $cfArr['lease_price'] != '' && $cfArr['lease_price'] != 0) || (!isset($cfArr['lease_price']) || $cfArr['lease_price'] == '' || $cfArr['lease_price'] == 0)) ) {   //show single pay lease only
      			  	$display_lease = '<div class="specialLease">';
      			  	$display_lease .= '<div class="price1 singleLease"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['single_lease_price'].'.00">$</span>'.number_format($cfArr['single_lease_price']).'</div>';
      			  	$display_lease .= '<div class="label2">for '.$cfArr['single_lease_term'].' months, '.$cfArr['single_lease_miles'].',000 miles</div>';
      			  	$display_lease .= '</div>';
      			  }else if ( isset($cfArr['lease_price']) && $cfArr['lease_price'] != '' && $cfArr['lease_price'] != 0 && (!isset($cfArr['single_lease_price']) || $cfArr['single_lease_price'] == '' || $cfArr['single_lease_price'] == 0) && $show['lease'] ) {  //show lease only
      			  	$display_lease = '<div class="specialLease">';
      			  	$display_lease .= '<div class="price1"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['lease_price'].'.00">$</span>'.$cfArr['lease_price'].'<span>/mo</span></div>';
      			  	$display_lease .= '<div class="label2">w/ $'.$cfArr['lease_extras'].' down for '.$cfArr['lease_term'].' mos.*</div>';
      			  	$display_lease .= '</div>';
      			  }else if( isset($cfArr['zero_down_lease_price']) && $cfArr['zero_down_lease_price'] != '' && $cfArr['zero_down_lease_price'] != 0 && $show['zero_down'] ) { //show zero down only
      			  	$display_zero_down = '<div class="specialZeroDown">';
      			  	$display_zero_down .= '<div class="price1"><span class="ncsDsign" itemprop="price" content="USD '.$cfArr['zero_down_lease_price'].'.00">$</span>'.$cfArr['zero_down_lease_price'].'<span>/mo</span></div>';
      			  	$display_zero_down .= '<div class="label2">w/ $0 down for '.$cfArr['zero_down_lease_term'].' mos.*</div>';
      			  	$display_zero_down .= '</div>';
      			  }
      
      			  //
      			  //  Start the special HTML and content
      			  $logo = $cfArr['logo'];
      			  $linklocation = 'showrooms/';
      			  $link =  UPLOADURL .$linklocation .$logo;
      			  //$html .= '<link rel="stylesheet" type="text/css" href="http://ncs.quirkspecials.com/newplugincss/' . $cssLetter . '/specials-style.css" />';
      			  $html .= '<script id="specialsFunctions" type="text/javascript" src=" ' . Url::adminUrl ( "assets", false, "/js/specials-functions.js" ) . '"></script>';
      			  $html .= '<div class="header">';
      			  $html .= '<h3 class="ncs_model_title">**Web Special Preview**</h3>';
      			  $html .= '<img src="' . $link . '? ' . $link . ': "blank.png" " height="30" width="30" alt="">';
      			  $html .= '<div class="content">';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="qsContent"  data-featured=" ' . $featuredSpecial . ' " itemtype="http://schema.org/Product" itemscope>';
      			  $html .= '<div class="qsInnerContent">';
      			  $html .= '<div class="vehicleMedia">';
      			  $html .= '<div class="vehicleMedia_inner_wrap">';
      			  $html .= '<div class="ncsImgWrapper">';
      			  $html .= '<a>';
      			  $html .= '<link itemprop="additionalType" href="http://productontology.org/id/automobile">';
      			  if (isset ( $cfArr ['tagline'] ) && $cfArr ['tagline'] != '') {
      			  	$html .= '<span>' . $cfArr ['tagline'] . '</span>';
      			  }
      			  $html .= '<img src="' . $cfArr ['vehicle_image'] . '" alt="' . $cfArr ['year'] . '" "' . $cfArr ['makename_ws'] . '" "' . $cfArr ['modelname_ws'] . '" "' . $cfArr ['trim_level'] . '">';
      			  $html .= '</a>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_title_save_wrap_mobile">';
      			  $html .= '<div class="ncs_title_wrap">';
      			  $html .= '<a>';
      			  $html .= '<h3 class="ncs_model_title">';
      
      			  $html .= '<div class="ncs_top_title" ncs-data-name="New  ' . $cfArr ['year'] . ' ' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . ' ' . $modelmod . '">';
      			  $html .= '<span itemprop="itemCondition">New</span>';
      			  $html .= '<span itemprop="modelYear"> ' . $cfArr ['year'] . '</span>';
      			  $html .= '<span itemprop="manufacturer"> ' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . '</span> ';
      			  $html .= '<span itemprop="model"> ' . $modelmod . '</span>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_top_trim">';
      			  $html .= '<span itemprop="modelTrim"> ' . $cfArr ['trim_level'] . ' </span>';
      			  $html .= '</div>';
      			  $html .= '</h3>';
      			  $html .= '</a>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_mobile_top_content_wrap">';
      			  $html .= '<div class="MonthlyPricingTop">';
      
      			  if ($onlyPurchase != "single") {
      			  	$html .= '<div class="monthlyPricingTopContainer  ' . $onlyMonthly . ' ' . $dealtype . ' " itemprop="offers" itemtype="http://schema.org/Offer">';
      
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseWrapperTop">';
      			  	}
      			  	if ((isset ( $cfArr ['zero_down_lease_price'] ) && $cfArr ['zero_down_lease_price'] != '' && $cfArr ['zero_down_lease_price'] != 0 && (! isset ( $cfArr ['lease_price'] ) || $cfArr ['lease_price'] == '' || $cfArr ['lease_price'] == 0) && (! isset ( $cfArr ['single_lease_price'] ) || $cfArr ['single_lease_price'] == '' || $cfArr ['single_lease_price'] == 0))) {
      
      			  		$html .= '<div class="label1 l1zDown">ZERO DOWN LEASE:</div>';
      			  	} else if (isset ( $cfArr ['single_lease_price'] ) && $cfArr ['single_lease_price'] != '' && $cfArr ['single_lease_price'] != 0) {
      			  		$html .= '<div class="label1">SINGLE PAY LEASE:</div>';
      			  	} else if (isset ( $cfArr ['lease_price'] ) && $cfArr ['lease_price'] != '' && $cfArr ['lease_price'] != 0 && ! isset ( $cfArr ['single_lease_price'] )) {
      			  		$html .= '<div class="label1">LEASE FOR:</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseInnerWrapper">';
      
      			  		if ($dealtype == "zero down") {
      			  			$html .= $display_zero_down;
      			  			$html .= $display_lease;
      			  		} else {
      			  			$html .= $display_lease;
      			  			$html .= $display_zero_down;
      			  		}
      			  		$html .= '</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '</div>';
      			  	}
      			  	$html .= '</div>';
      			  }
      
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_mobile_top_content_expanded">';
      			  $html .= '<div class="ncs_mobile_top_content_flex">';
      
      			  if (isset ( $cfArr ['available_apr'] ) && $cfArr ['available_apr'] != '' && $show ['apr']) {
      			  	$html .= '<div class="specialAPRMobile">';
      			  	$html .= '<div class="aprmobileLabel">available APR: <span> ' . doubleval ( $cfArr ['available_apr'] ) . ' %<span class="aprmobileasterisk">*</span></span></div>';
      			  	$html .= '</div>';
      			  }
      			  if ($dealtype == '') {
      			  	if (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings']) {
      			  		$html .= '<div class="ncs_saving_up_to_mobile">';
      			  		$html .= '<div class="ncs_save_content_mobile">save up to: <span>$ ' . number_format ( $cfArr ['save_up_to_amount'] ) . ' </span></div>';
      			  		$html .= '</div>';
      			  	}
      			  }
      
      			  $html .= '<div class="quirkPriceTopMobile">';
      
      			  if (isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) {
      			  	$html .= '<div class="qPriceMobileTop">';
      			  	$html .= ' Quirk Price: <span itemprop="price" content=" ' . $cfArr ['buy_price'] . ' .00">$ ' . number_format ( $cfArr ['buy_price'] ) . ' </span>';
      			  	$html .= '</div>';
      			  }
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_pricing_wrap">';
      			  $html .= '<div class="ncs_title_save_wrap">';
      			  $html .= '<div class="ncs_title_save_wrap_innernew">';
      			  $html .= '<div class="ncs_title_wrap">';
      			  $html .= '<a>';
      			  $html .= '<h3 class="ncs_model_title">';
      			  $html .= '<div class="ncs_top_title" ncs-data-name="New  ' . $cfArr ['year'] . ' ' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . ' ' . $modelmod . '">';
      			  $html .= '<span itemprop="itemCondition">New</span>';
      			  $html .= ' <span itemprop="modelYear"> ' . $cfArr ['year'] . ' </span>';
      			  $html .= '<span itemprop="manufacturer"> ' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . '</span> ';
      			  $html .= '<span itemprop="model"> ' . $modelmod . ' </span>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncs_top_trim">';
      			  $html .= '<span itemprop="modelTrim"> ' . $cfArr ['trim_level'] . '</span>';
      			  $html .= '</div>';
      			  $html .= '</h3>';
      			  $html .= '</a>';
      			  $html .= '</div>';
      			  $html .= '<div class="quirkPriceTop">';
      
      			  if (isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) {
      			  	$html .= '<div class="qPriceTop">';
      			  	$html .= '<span class="qPriceTextTop">Quirk Price: </span>';
      			  	$html .= '<span class="ncsDsign qDsignTop" itemprop="priceCurrency" content="USD">$</span><span class="ncsBuyPriceTop" itemprop="price" content=" ' . $cfArr ['buy_price'] . ' .00"> ' . number_format ( $cfArr ['buy_price'] ) . ' </span>';
      			  	$html .= '</div>';
      			  }
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="MonthlyPricingTop">';
      
      			  if ($onlyPurchase != "single") {
      			  	$html .= '<div class="monthlyPricingTopContainer  ' . $onlyMonthly . '' . $dealtype . ' " itemprop="offers" itemtype="http://schema.org/Offer">';
      
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseWrapperTop">';
      			  	}
      			  	if ($dealtype == "zero down" || (isset ( $cfArr ['zero_down_lease_price'] ) && $cfArr ['zero_down_lease_price'] != '' && $cfArr ['zero_down_lease_price'] != 0 && (! isset ( $cfArr ['lease_price'] ) || $cfArr ['lease_price'] == '' || $cfArr ['lease_price'] == 0) && (! isset ( $cfArr ['single_lease_price'] ) || $cfArr ['single_lease_price'] == '' || $cfArr ['single_lease_price'] == 0))) {
      
      			  		$html .= '<div class="label1 l1zDown">ZERO DOWN LEASE:</div>';
      			  	} else if (isset ( $cfArr ['single_lease_price'] ) && $cfArr ['single_lease_price'] != '' && $cfArr ['single_lease_price'] != 0) {
      			  		$html .= '<div class="label1">SINGLE PAY LEASE:</div>';
      			  	} else if (isset ( $cfArr ['lease_price'] ) && $cfArr ['lease_price'] != '' && $cfArr ['lease_price'] != 0 && ! isset ( $cfArr ['single_lease_price'] )) {
      			  		$html .= '<div class="label1">LEASE FOR:</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseInnerWrapper">';
      
      			  		if ($dealtype == "zero down") {
      			  			$html .= $display_zero_down;
      			  			$html .= $display_lease;
      			  		} else {
      			  			$html .= $display_lease;
      			  			$html .= $display_zero_down;
      			  		}
      			  		$html .= '</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '</div>';
      			  	}
      			  	$html .= '</div>';
      			  }
      
      			  $html .= '<div class="ncs_saving_up_to">';
      
      			  if ($dealtype == '') {
      			  	if (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings']) {
      			  		$html .= '<div class="ncs_save_text">save up to:</div>';
      			  		$html .= '<div class="ncs_save_currency">$ ' . number_format ( $cfArr ['save_up_to_amount'] ) . ' </div>';
      			  	}
      			  }
      
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '<div class="NCS_deatail_CTA_Expand">';
      			  $html .= '<div class="ncs_detail_expand">';
      			  $html .= '<a>';
      			  $html .= '<div class="specialsInfoWrap">';
      			  $html .= '<div class="allPricing">';
      
      			  if ($onlyPurchase != "single" || ((isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) || ($show ['savings'] && $vTotalPricingLines > 0))) {
      			  	if (! isset ( $cfArr ['zero_down_lease_price'] ) && ! isset ( $cfArr ['single_lease_price'] ) && ! isset ( $cfArr ['lease_price'] ) && $show ['savings'] && $vTotalPricingLines > 0) {
      			  		$html .= '<div class="monthlyPricing  ' . $onlyMonthly . '' . $dealtype . '  mpDTNone" itemprop="offers" itemtype="http://schema.org/Offer">';
      			  	} else {
      			  		$html .= '<div class="monthlyPricing  ' . $onlyMonthly . '' . $dealtype . ' " itemprop="offers" itemtype="http://schema.org/Offer">';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseWrapper">';
      			  	}
      			  	if ($dealtype == "zero down" || (isset ( $cfArr ['zero_down_lease_price'] ) && $cfArr ['zero_down_lease_price'] != '' && $cfArr ['zero_down_lease_price'] != 0 && (! isset ( $cfArr ['lease_price'] ) || $cfArr ['lease_price'] == '' || $cfArr ['lease_price'] == 0) && (! isset ( $cfArr ['single_lease_price'] ) || $cfArr ['single_lease_price'] == '' || $cfArr ['single_lease_price'] == 0))) {
      
      			  		$html .= '<div class="label1 l1zDown">ZERO DOWN LEASE:</div>';
      			  	} else if (isset ( $cfArr ['single_lease_price'] ) && $cfArr ['single_lease_price'] != '' && $cfArr ['single_lease_price'] != 0) {
      			  		$html .= '<div class="label1">SINGLE PAY LEASE:</div>';
      			  	} else if (isset ( $cfArr ['lease_price'] ) && $cfArr ['lease_price'] != '' && $cfArr ['lease_price'] != 0 && ! isset ( $cfArr ['single_lease_price'] )) {
      			  		$html .= '<div class="label1">LEASE FOR:</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '<div class="ncsleaseInnerWrapper">';
      
      			  		if ($dealtype == "zero down") {
      			  			$html .= $display_zero_down;
      			  			$html .= $display_lease;
      			  		} else {
      			  			$html .= $display_lease;
      			  			$html .= $display_zero_down;
      			  			// var_dump($display_zero_down);
      			  		}
      			  		$html .= '</div>';
      			  	}
      			  	if (isset ( $cfArr ['zero_down_lease_price'] ) || isset ( $cfArr ['single_lease_price'] ) || isset ( $cfArr ['lease_price'] )) {
      			  		$html .= '</div>';
      			  	}
      			  	if ($dealtype == '' && isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0) {
      			  		$html .= '<div class="qSavings ncs_tableWrap ncs_tablewrap_mobile">';
      			  		$html .= '<table>';
      			  		foreach ( $data1 as $row2 ) :
      			  		$html .= '<tr>';
      			  		$html .= '<td>' . $row2->name . '</td>';
      			  		$html .= '<td>$' . number_format ( $row2->price ) . '</td>';
      			  		$html .= '</tr>';
      			  		endforeach
      			  		;
      			  		unset ( $row2 );
      			  		$html .= '</table>';
      			  		$html .= '</div>';
      			  	}
      			  	$html .= '</div>';
      			  }
      			  $html .= '</div>';
      			  $html .= '</div>';
      
      			  if ($dealtype == '' && ((isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) || (isset ( $cfArr ['available_apr'] ) && $cfArr ['available_apr'] != '' && $show ['apr']) || (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0))) {
      			  	$html .= '<div class="ncs_quirk_price_wrap">';
      			  }
      			  if ($dealtype == '' && ((isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) || (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0))) {
      
      			  	if ($onlyMonthly != "single" && $dealtype != "lease" && $dealtype != "zero down" && $dealtype != "single pay" && $dealtype == '' && isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price'] && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0) {
      			  		$html .= '<div class="ncs_pricing_savings_halfWrap">';
      			  		$html .= '<div class="salePricing ncs_fiftyPercent  ' . $onlyPurchase . ' " itemprop="offers" itemtype="http://schema.org/Offer">';
      			  		$html .= '<div class="salePricingBuyPrices">';
      			  		$html .= '<div class="quirkPrice">';
      			  		$html .= '<div class="qPriceText">Quirk Price</div>';
      			  		$html .= '<div class="qPriceCurrency">';
      			  		$html .= '<span class="ncsDsign qDsign" itemprop="priceCurrency" content="USD">$</span><span itemprop="price" content=" ' . $cfArr ['buy_price'] . ' .00"> ' . number_format ( $cfArr ['buy_price'] ) . ' </span>';
      			  		$html .= ' </div>';
      			  		$html .= '</div>';
      			  		$html .= ' </div>';
      			  		$html .= '</div>';
      			  		$html .= '<div id="ncs_fiftyPercent" class="qSavings ncs_fiftyPercent">';
      			  		$html .= '<table>';
      			  		foreach ( $data1 as $row3 ) :
      			  		$html .= '<tr>';
      			  		$html .= '<td>' . $row3->name . '</td>';
      			  		$html .= '<td>$' . number_format ( $row3->price ) . '</td>';
      			  		$html .= '</tr>';
      			  		endforeach
      			  		;
      			  		unset ( $row3 );
      			  		$html .= '</table>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  	} else if ($onlyMonthly != "single" && $dealtype != "lease" && $dealtype != "zero down" && $dealtype != "single pay" && isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) {
      			  		$html .= '<div class="salePricing  ' . $onlyPurchase . '" itemprop="offers" itemtype="http://schema.org/Offer">';
      			  		$html .= '<div class="salePricingBuyPrices">';
      			  		$html .= '<div class="quirkPrice">';
      			  		$html .= '<div class="qPriceText">Quirk Price</div>';
      			  		$html .= '<div class="qPriceCurrency">';
      			  		$html .= '<span class="ncsDsign qDsign" itemprop="priceCurrency" content="USD">$</span><span itemprop="price" content=" ' . $cfArr ['buy_price'] . ' .00"> ' . number_format ( $cfArr ['buy_price'] ) . ' </span>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  	} else {
      			  		if ($dealtype == '' && isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0) {
      			  			$html .= '<div id="ncs_tableWrap" class="qSavings ncs_tableWrap">';
      			  			$html .= '<table>';
      			  			foreach ( $data1 as $row4 ) :
      			  			$html .= '<tr>';
      			  			$html .= '<td>' . $row4->name . '</td>';
      			  			$html .= '<td>$ ' . number_format ( $row4->price ) . ' </td>';
      			  			$html .= '</tr>';
      			  			endforeach
      			  			;
      			  			unset ( $row4 );
      			  			$html .= '</table>';
      			  			$html .= '</div>';
      			  		}
      			  	}
      			  }
      
      			  if (isset ( $cfArr ['available_apr'] ) && $cfArr ['available_apr'] != '' && $show ['apr']) {
      			  	$html .= '<div class="salePricingQuirk" itemprop="offers" itemtype="http://schema.org/Offer">';
      			  	$html .= '<div class="salePricingBuyPrices">';
      			  	$html .= '<div class="specialAPR">';
      			  	$html .= '<div class="label1">AVAILABLE APR:</div>';
      			  	$html .= '<div class="price1"> ' . doubleval ( $cfArr ['available_apr'] ) . ' %</div>';
      			  	$html .= '<div class="label2"> ' . (($cfArr ['apr_text'] == '') ? "APR available*" : $cfArr ['apr_text'] . "*") . ' </div>';
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      			  }
      			  if ($dealtype == '' && ((isset ( $cfArr ['buy_price'] ) && $cfArr ['buy_price'] != '' && $cfArr ['buy_price'] != '0' && $show ['buy_price']) || (isset ( $cfArr ['available_apr'] ) && $cfArr ['available_apr'] != '' && $show ['apr']) || (isset ( $cfArr ['save_up_to_amount'] ) && $cfArr ['save_up_to_amount'] != '' && $cfArr ['save_up_to_amount'] != '0' && $show ['savings'] && $vTotalPricingLines > 0))) {
      			  	$html .= '</div>';
      			  }
      			  $html .= '</a>';
      
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  {
      
      			  	$valsArr = array ();
      
      			  	foreach ( $dataws as $key => $val ) {
      
      			  		$valsArr [$key] = $val;
      			  	}
      
      			  	$storeLetter = $valsArr ['store_letter'];
      			  	$interests = $show;
      
      			  	$html .= '<div class="specialsShortCodeFormContainer">';
      
      			  	$html .= '<div class="NCSShortCodeFormOptionWrapper">';
      			  	$html .= '<div class="NCSShortCodeFormLeft">';
      			  	$html .= '<div class="NCSSCFormInput">';
      			  	$html .= '<div class="NCSSCFormPrimaryInput">';
      			  	$html .= '<div class="NCSFormIPIndividual">';
      			  	$html .= '<label>First Name:</label>';
      			  	$html .= '<input type="text" class="customer_first" name="customer_first" tabindex="1"/>';
      			  	$html .= '<p class="NCSRequiredFormParagraph"></p>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSFormIPIndividual">';
      			  	$html .= '<label>Last Name:</label>';
      			  	$html .= '<input type="text" class="customer_last" name="customer_last" tabindex="2"/>';
      			  	$html .= '<p class="NCSRequiredFormParagraph"></p>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSFormIPIndividual">';
      			  	$html .= '<label>Email:</label>';
      			  	$html .= '<input type="email" class="customer_email" name="customer_email" tabindex="3"/>';
      			  	$html .= '<p class="NCSRequiredFormParagraph"></p>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSFormIPIndividual">';
      			  	$html .= '<label>Phone Number:</label>';
      			  	$html .= '<input type="text" class="customer_phone" name="customer_phone" tabindex="4"/>';
      			  	$html .= '<p class="NCSRequiredFormParagraph"></p>';
      
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      
      			  	$hasLease = FALSE;
      			  	$hasSingle = FALSE;
      			  	$hasZD = FALSE;
      			  	$hasAPR = FALSE;
      			  	$hasSUT = FALSE;
      			  	$hasCustomPrice = FALSE;
      
      			  	if (isset ( $valsArr ['lease_price'] ) && $valsArr ['lease_price'] != '' && $valsArr ['lease_price'] != 0 && (! isset ( $valsArr ['single_lease_price'] ) || $valsArr ['single_lease_price'] == 0 || $valsArr ['single_lease_price'] == '') && isset ( $interests ['lease'] )) {
      			  		$hasLease = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['single_lease_price'] ) && $valsArr ['single_lease_price'] != '' && $valsArr ['single_lease_price'] != 0 && isset ( $interests ['single_pay'] )) {
      			  		$hasSingle = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['zero_down_lease_price'] ) && $valsArr ['zero_down_lease_price'] != '' && $valsArr ['zero_down_lease_price'] != 0 && isset ( $interests ['zero_down'] )) {
      			  		$hasZD = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['available_apr'] ) && $valsArr ['available_apr'] != '' && $interests ['apr']) {
      			  		$hasAPR = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['save_up_to_amount'] ) && $valsArr ['save_up_to_amount'] != '' && $valsArr ['save_up_to_amount'] != '0' && isset ( $interests ['savings'] )) {
      			  		$hasSUT = TRUE;
      			  	}
      			  	if (isset ( $valsArr ['custom_price_val'] ) && $valsArr ['custom_price_val'] != '' && $valsArr ['custom_price_val'] != '0' && isset ( $interests ['cust_price'] )) {
      			  		$hasCustomPrice = TRUE;
      			  	}
      			  	if ($hasLease == TRUE || $hasSingle == TRUE || $hasZD == TRUE || $hasAPR == TRUE || $hasSUT == TRUE || $hasCustomPrice == TRUE) {
      
      			  		$html .= '<div class="NCSSCFormOptions">';
      			  		$html .= '<div class="NCSSCFormOptionsCenterDiv">';
      			  		$html .= '<h5>I am interested in&nbsp;<span>(optional):</span></h5>';
      
      			  		if ($hasLease) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_lease" tabindex="6" value="Interested in lease w/money down." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span>$ ' . number_format ( $valsArr ['lease_price'] ) . ' /mo with $ ' . number_format ( $valsArr ['lease_extras'] ) . '  down</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		if ($hasSingle) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_single" tabindex="6" value="Interested in single pay lease." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span>$ ' . number_format ( $valsArr ['single_lease_price'] ) . '  single pay lease</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		if ($hasZD) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_zero_down" tabindex="7" value="Interestested in zero down lease." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span>$ ' . number_format ( $valsArr ['zero_down_lease_price'] ) . ' /mo with $0 down</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		if ($hasAPR) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_apr" tabindex="8" value="Interested in promotional APR." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span> ' . doubleval ( $valsArr ['available_apr'] ) . ' % APR financing</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		if ($hasSUT) {
      			  			$html .= '<div class="NCSIndividualOptions">';
      			  			$html .= '<div class="NCSOptionChecker">';
      			  			$html .= '<input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_savings" tabindex="9" value="Interested in purchase savings." />';
      			  			$html .= '<label class="NCSCheckboxLabel"></label>';
      			  			$html .= '</div>';
      			  			$html .= '<div class="NCSOptionText">';
      			  			$html .= '<span>$ ' . number_format ( $valsArr ['buy_price'] ) . '  quirk price</span>';
      			  			$html .= '</div>';
      			  			$html .= '</div>';
      			  		}
      			  		// if( $hasCustomPrice ){
      			  		/*
      			  		 * div class="NCSIndividualOptions">
      			  		 * $html .= '<div class="NCSOptionChecker">
      			  		 * <input type="checkbox" class="checkInterest NCSFormCheckbox" name="wants_custom" tabindex="10" value="Interested in price with . $valsArr['custom_price_label']; ." />
      			  		 * <label class="NCSCheckboxLabel"></label>
      			  		 * </div>
      			  		 * $html .= '<div class="NCSOptionText">
      			  		 * <span>&nbsp;&nbsp;This price with . strtolower( $valsArr['custom_price_label'] ); </span>
      			  		 * </div>
      			  		 * </div
      			  		 */
      
      			  		// }
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  		$html .= '<div class="NCSFormSCDisc">';
      			  		$html .= '<p class="formDisclaimer">' . $valsArr ['disclaimer_text'] . ' </p>';
      			  		$html .= '</div>';
      			  		$html .= '</div>';
      			  	}
      			  	$html .= '<div class="NCSShortCodeFormRight">';
      			  	$html .= '<div class="ncsShortCodeComments">';
      			  	$html .= '<label>Comments:</label>';
      			  	$html .= '<textarea id="customer_comments" name="customer_comments" tabindex="5"></textarea>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="specialCTAs">';
      			  	$html .= '<a class="valueTrade" <span>Value Trade</span></a>';
      			  	$html .= '<a class="viewInventory" href="' . $valsArr ['alt_link_url'] . '"><span>Inventory</span></a>';
      			  	$html .= '<button type="button" class="ncsFormSubmit" name="special_submit" rel=" ' . $valsArr ['webspecials_id'] . ' ">Submit</button>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSSCFormdiscMobileBtn">';
      			  	$html .= '<span>full disclaimer</span>';
      			  	$html .= '</div>';
      			  	$html .= '<div class="NCSFormSCDiscMobile">';
      			  	$html .= '<p class="formDisclaimerMobile"> ' . $valsArr ['disclaimer_text'] . ' </p>';
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      			  	$html .= '</div>';
      
      			  	$html .= '</div>';
      			  }
      
      			  $html .= '<div class="qsTab">';
      			  $html .= '<div class="qsTabTitle">';
      			  $html .= '<span itemprop="itemCondition">New</span>';
      			  $html .= '<span itemprop="modelYear">' . $cfArr ['year'] . ' </span>';
      			  $html .= '<span itemprop="manufacturer">' . ($modelmod == 'Mazda3' || $modelmod == 'Mazda6' ? '' : $cfArr ['makename_ws']) . ' </span>';
      			  $html .= '<span itemprop="model">' . $modelmod . ' </span>';
      			  $html .= '<span itemprop="modelTrim">' . $cfArr ['trim_level'] . ' </span>';
      			  $html .= '</div>';
      
      			  $html .= '<div class="qsTabOpenClose">';
      			  $html .= '<div class="ncsClose">';
      			  $html .= '<span>CLICK TO CLOSE</span>';
      			  $html .= '</div>';
      			  $html .= '<div class="ncsExpand">';
      			  $html .= '<span>GET THIS PRICE</span>';
      			  $html .= '</div>';
      			  $html .= '<img class="ncsCloseArrow" src="' . Url::adminUrl ( "assets", false, "/images/Up-Arrow.png" ) . '"/><img class="ncsExpandArrow" src="' . Url::adminUrl ( "assets", false, "/images/Down-Arrow.png" ) . '"/>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      			  $html .= '</div>';
      
      			  $findResults = true;
      
      			  if (! $findResults) {
      			  	$html .= '<span class="noNCS">No specials found at this time.  Please check back later.</span>';
      			  }
      			  $html .= '</div>'; // end page container
      
      			  //$html .= '</div>';
      			  print $html;
      
      			  endif;
      
      }
      
      
  }