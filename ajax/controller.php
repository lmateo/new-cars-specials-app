<?php
  /**
   * Controller
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: controller.php, v1.00 2014-10-05 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once("../init.php");

 $action = (isset($_POST['action']))  ? $_POST['action'] : null;
 $delete = (isset($_POST['delete']))  ? $_POST['delete'] : null;
 $title = (isset($_POST['delete']) and isset($_POST['title'])) ? Validator::sanitize($_POST['title']) : null;

 /* == Home Carousel == */
 if (isset($_GET['getFeaturedCategory'])):
 	if ($data = $db->select(Items::lTable, "*", array(
 		"category" => Filter::$id,
 		"status" => 1,
 		"featured" => 1), 'ORDER BY created DESC')->results()):
 		$total = count($data);

 		$html = '';
 		foreach ($data as $row):
 			$html .= '<div class="wojo segment divided content-center"> <a href="' . Url::doUrl(URL_ITEM, $row->idx . '/' . $row->slug) . '"><img data-lazy="' . UPLOADURL . 'listings/' . $row->thumb . '" alt=""></a>';
			$html .= '<h4 class="wojo header"><a href="' . Url::doUrl(URL_ITEM, $row->idx . '/' . $row->slug) . '" class="inverted">' . $row->nice_title . '</a></h4>';
			$html .= '<p class="wojo negative bold text">' . Utility::FormatMoney($row->price) . '</p>';
			$html .= '</div>';
 		endforeach;
		unset($row);
		$json['status'] = "success";
		$json['html'] = $html;

 	else:
 		$json['status'] = "error";
 	endif;
 		print json_encode($json);
 endif;

 /* == Process Newsletter == */
 if (isset($_POST['subscribe'])):
 	$validate = Validator::instance();
 	$validate->addSource($_POST);
 	$validate->addRule('name', 'string', true, 2, 80, Lang::$word->EMN_NLN);
 	$validate->addRule('email', 'email');
 	$validate->run();

 	if (empty(Message::$msgs)):
 		if ($row = $db->select(Content::nwTable, "*", array("name" => $validate->safe->name, "email" => $validate->safe->email))->
 			result()):
 			$db->delete(Content::nwTable, array("email" => $validate->safe->email));
 			Message::msgReply($db->affected(), 'success', Lang::$word->EMN_MSG2);
 		else:
 			$data = array('name' => $validate->safe->name, 'email' => $validate->safe->email);
 			$db->insert(Content::nwTable, $data);
 			Message::msgReply($db->affected(), 'success', Lang::$word->EMN_MSG1);
 		endif;
 	else:
 		Message::msgSingleStatus();
 	endif;
 endif;
 
  /* == Load Makelist  == */
  if (isset($_GET['getMakelist'])):
      $id = Validator::sanitize($_GET['getMakelist'], "int");
      $html = "";
	  $mids = $db->select(Items::lTable, array("GROUP_CONCAT(model_id) as ids"), array('make_id' => $id))->result();
	  if ($mids->ids):
	      $result = $db->pdoQuery("SELECT id, name FROM " . Content::mdTable . " WHERE id IN(" . $mids->ids . ")")->results();
	      $html .= "<option value=\"\">-- " . Lang::$word->LST_MODEL . " --</option>\n";
          foreach ($result as $row):
              $html .= "<option value=\"" . $row->id . "\">" . $row->name . "</option>\n";
          endforeach;
          unset($row);

      else:
          $html .= "<option value=\"\">-- " . Lang::$word->MAKE_NAME_R . " --</option>\n";
      endif;
      $json['type'] = "success";
	  $json['message'] = $html;
      echo json_encode($json);
  endif;

  if (isset($_GET['getMakelistFull'])):
      $id = Validator::sanitize($_GET['getMakelistFull'], "int");
	  
      $html = "";
	  if ($result = $db->select(Content::mdTable, array("id", "name"), array('make_id' => $id), 'ORDER BY name ASC')->results()):
          foreach ($result as $row):
              $html .= "<option value=\"" . $row->id . "\">" . $row->name . "</option>\n";
          endforeach;
          unset($row);
      else:
          $html .= "<option value=\"\">--- " . Lang::$word->MAKE_NAME_R . " ---</option>\n";
      endif;
      $json['type'] = "success";
	  $json['message'] = $html;
      echo json_encode($json);
  endif;
  
 switch ($action):
    /* == Main Contact  == */
	case "contactSite":
		$validate = Validator::instance();
		$validate->addSource($_POST);
		$validate->addRule('name', 'string', true, 2, 80, Lang::$word->EMN_NLN);
		$validate->addRule('message', 'string', true, 20, 200, Lang::$word->MESSAGE);
		$validate->addRule('email', 'email');
		
		$validate->run();
		if (empty(Message::$msgs)):
			$mailer = Mailer::sendMail();
			$subject = str_replace("[COMPANY]", $validate->safe->name, Lang::$word->HOME_MSG_CREQ);
  
			ob_start();
			require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Contact_Request.tpl.php');
			$html_message = ob_get_contents();
			ob_end_clean();
  
			$body = str_replace(
			array(
				'[LOGO]',
				'[NAME]',
				'[IP]',
				'[MESSAGE]',
				'[COMPANY]',
				'[SITEURL]',
				'[DATE]'), 
			array(
				Utility::getLogo(),
				$validate->safe->name,
				$_SERVER['REMOTE_ADDR'],
				Validator::cleanOut($validate->safe->message),
				App::get("Core")->company,
				SITEURL,
				date('Y')
				), $html_message);
  
			$msg = Swift_Message::newInstance()
					->setSubject($subject)
					->setTo(array(App::get("Core")->site_email => App::get("Core")->company))
					->setFrom(array($validate->safe->email => $validate->safe->name))
					->setBody($body, 'text/html');
  
			if ($mailer->send($msg)) {
				$json['type'] = 'success';
				$json['title'] = Lang::$word->SUCCESS;
				$json['message'] = Lang::$word->HOME_MSG_SENTOK;
			} else {
				$json['type'] = 'error';
				$json['title'] = Lang::$word->ERROR;
				$json['message'] = Lang::$word->EMN_ALERT . $res;
			}
			print json_encode($json);
		else:
			Message::msgSingleStatus();
		endif;
	break;
	
    /* == Process Contact Seller  == */
	case "contactSeller":
		$validate = Validator::instance();
		$validate->addSource($_POST);
		$validate->addRule('name', 'string', true, 2, 80, Lang::$word->EMN_NLN);
		$validate->addRule('message', 'string', true, 20, 200, Lang::$word->MESSAGE);
		$validate->addRule('email', 'email');
		$validate->addRule('location', 'numeric', false);
		$validate->addRule('item_id', 'string', false);
		$validate->addRule('stock_id', 'string', false);
  
		$validate->run();
		if (empty(Message::$msgs)):
			if ($row = $db->first(Content::lcTable, null, array("id" => $validate->safe->location))):
				$mailer = Mailer::sendMail();
				$subject = str_replace("[COMPANY]", App::get("Core")->company, Lang::$word->HOME_MSG_CREQ);
  
				ob_start();
				require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Seller_Contact_Request.tpl.php');
				$html_message = ob_get_contents();
				ob_end_clean();
  
				$body = str_replace(
				array(
					'[LOGO]',
					'[NAME]',
					'[SENDER]',
					'[ITEM_URL]',
					'[STOCK]',
					'[IP]',
					'[MESSAGE]',
					'[COMPANY]',
					'[SITEURL]',
					'[DATE]'), 
				array(
					Utility::getLogo(),
					$row->name,
					$validate->safe->name,
					Url::doUrl(URL_ITEM, $validate->safe->item_id),
					$validate->safe->stock_id,
					$_SERVER['REMOTE_ADDR'],
					App::get("Core")->company,
					Validator::cleanOut($validate->safe->message),
					SITEURL,
					date('Y')
					), $html_message);
  
				$msg = Swift_Message::newInstance()
						->setSubject($subject)
						->setTo(array($row->email => $row->name))
						->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
						->setBody($body, 'text/html');
  
				if ($mailer->send($msg)) {
					$json['type'] = 'success';
					$json['title'] = Lang::$word->SUCCESS;
					$json['message'] = Lang::$word->HOME_MSG_SENTOK;
				} else {
					$json['type'] = 'error';
					$json['title'] = Lang::$word->ERROR;
					$json['message'] = Lang::$word->EMN_ALERT . $res;
				}
				print json_encode($json);
			else:
				Message::msgReply(false, 'error', Lang::$word->EMN_ALERT);
			endif;
		else:
			Message::msgSingleStatus();
		endif;
	break;
	
    /* == Update Profile  == */
	case "updateProfile":
		if (!$auth->is_User())
			exit;
		$user->updateProfile();
	break;

    /* == Register Account  == */
	case "register":
		$user->register();
	break;

    /* == Register Account  == */
	case "processLocation":
		if (!$auth->is_User())
			exit;
		$content->processLocation(true);
	break;

    /* == Add Review  == */
	case "addReview":
		if (!$auth->is_User())
			exit;
		$content->addReview();
	break;
	
    /* == Add Listing  == */
	case "processListing":
		if (!$auth->is_User() and $auth->membership_id)
			exit;
		$user->addListing();
	break;
	
    /* == Mark Sold  == */
	case "markSold":
		if (!$auth->is_User())
			exit;
		if($row = $db->first(Items::lTable, array("id"), array("id" => Filter::$id, "user_id" => $auth->uid))):	
			$data['sold'] = 1;
			$data['soldexpire'] = Db::toDate();
			$db->update(Items::lTable, $data, array("id" => $row->id));
			$json['type'] = 'success';
			print json_encode($json);
		else:
			$json['type'] = 'error';
			print json_encode($json);
			endif;
	break;
	
    /* == Password Reset  == */
	case "passReset":
		$user->passReset();
	break;
	
 endswitch;
 
  switch ($delete):
  /* == Delete Item == */
  case "deleteItem":
	if (!$auth->is_User())
		exit;
    
	$res = $db->delete(Items::lTable, array('id' => Filter::$id, 'user_id' => $auth->uid));
	if($res):
		$db->delete(Items::liTable, array('listing_id' => Filter::$id));
		$db->delete(Items::gTable, array('listing_id' => Filter::$id));
		
		$pics = UPLOADS . "listings/pics" . Filter::$id;
		File::deleteRecrusive($pics, true);
		
		$thumb = $db->getValueById(Items::lTable, "thumb", Filter::$id);
		File::deleteFile(UPLOADS . "listings/" . $thumb);
		$count = $db->count(Items::lTable, "user_id = " . $auth->uid . " AND status = 1");
		
		$db->update(Users::mTable, array("listings" => $count), array("id" => $auth->uid));
		Items::doCalc();
	endif;

	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->LST_DEL_OK));
  break;

  /* == Delete Image Slide == */
  case "deleteSlide":
	if (!$auth->is_User())
		exit;
	$res = $db->delete(Items::gTable, array('id' => Filter::$id));
	File::deleteFile(UPLOADS . 'listings/pics' . Validator::sanitize($_POST['option'], "int") . '/' . Validator::sanitize($_POST['path'], "string"));
	File::deleteFile(UPLOADS . 'listings/pics' . Validator::sanitize($_POST['option'], "int") . '/thumbs/' . Validator::sanitize($_POST['path'], "string"));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->GAL_DELOK));
  break;
  
  endswitch;
  
 /* == Get News == */
 if (isset($_GET['getNews'])):
 	if($news = $content->renderNews()):
		$json['status'] = "success";
		$json['title'] = $news->title;
		$json['html'] = Validator::cleanOut($news->body);
 	else:
 		$json['status'] = "error";
 	endif;
 		print json_encode($json);
 endif;