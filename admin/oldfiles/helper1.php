<?php
  /**
   * Helper
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: helper.php, v1.00 2014-10-05 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once("init.php");
  
  if (!$auth->is_Admin())
      exit;
?>
<?php
  /* == Staff Search == */
  if (isset($_GET['doLiveSearch'])):
      $string = Validator::sanitize($_GET['value'], 'string', 15);
	  
	  switch($_GET['type']) {
		  case "staffsearch" :
				if (strlen($string) > 3):
					$sql = "
					SELECT 
					  id,
					  username,
					  email,
					  created,
					  CONCAT(fname, ' ', lname) AS name 
					FROM ". Users ::aTable ." 
					WHERE MATCH (fname) AGAINST ('" . $string . "*' IN BOOLEAN MODE) 
					  OR MATCH (lname) AGAINST ('" . $string . "*' IN BOOLEAN MODE) 
					  OR MATCH (username) AGAINST ('" . $string . "*' IN BOOLEAN MODE) 
					ORDER BY username 
					LIMIT 10 ";
		  
					$html = '';
					if ($result = $db->pdoQuery($sql)->results()):
						$html .= '<div class="wojo small feed results segment">';
						foreach ($result as $row):
							$link = Url::adminUrl("staff", "edit", false,"?id=" . $row->id);
							$html .= '<div class="event">';
							$html .= '<div class="label">';
							$html .= '<i class="circular user icon"></i>';
							$html .= '</div>';
							$html .= '<div class="content">';
							$html .= '<div class="date">';
							$html .= Utility::dodate('long_date', $row->created);
							$html .= '</div>';
							$html .= '<div class="summary">';
							$html .= '<a href="' . $link . '">' . $row->name . '</a> (' . $row->username . ')';
							$html .= '</div>';
							$html .= '<div class="extra text">';
							$html .= '<p><a href="' . Url::adminUrl("mailer", false,"?mailid=" . urlencode($row->email)) . '">' . $row->email . '</a></p>';
							$html .= '</div>';
							$html .= '</div>';
							$html .= '</div>';
						endforeach;
						$html .= '</div>';
						print $html;
					endif;
				endif;
		  break;
		  
		  case "membersearch" :
				if (strlen($string) > 3):
					$sql = "
					SELECT 
					  id,
					  username,
					  email,
					  created,
					  CONCAT(fname, ' ', lname) AS name 
					FROM
					  ".Users ::mTable." 
					WHERE MATCH (fname) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					  OR MATCH (lname) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					  OR MATCH (username) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					ORDER BY username 
					LIMIT 10 ";
		  
					$html = '';
					if ($result = $db->pdoQuery($sql)->results()) :
						$html .= '<div class="wojo small feed results segment">';
						foreach ($result as $row):
							$link = Url::adminUrl("members", "edit", false,"?id=" . $row->id);
							$html .= '<div class="event">';
							$html .= '<div class="label">';
							$html .= '<i class="circular user icon"></i>';
							$html .= '</div>';
							$html .= '<div class="content">';
							$html .= '<div class="date">';
							$html .= Utility::dodate('long_date', $row->created);
							$html .= '</div>';
							$html .= '<div class="summary">';
							$html .= '<a href="' . $link . '">' . $row->name . '</a> (' . $row->username . ')';
							$html .= '</div>';
							$html .= '<div class="extra text">';
							$html .= '<p><a href="' . Url::adminUrl("mailer", false,"?mailid=" . urlencode($row->email) . "&amp;clients=true"). '">' . $row->email . '</a></p>';
							$html .= '</div>';
							$html .= '</div>';
							$html .= '</div>';
						endforeach;
						$html .= '</div>';
						print $html;
					endif;
				endif;
		  break;

		  case "transsearch" :
				if (strlen($string) > 3):
					$sql = "
					SELECT 
					  p.*,
					  p.id AS id,
					  p.created AS transdate,
					  u.username,
					  u.email,
					  u.id AS uid,
					  m.id AS mid,
					  m.title AS title 
					FROM
					  `" . Content::txTable . "` AS p 
					  LEFT JOIN `" . Users::mTable . "` AS u 
						ON u.id = p.user_id 
					  LEFT JOIN `" . Content::msTable . "` AS m 
						ON m.id = p.membership_id 
					WHERE MATCH (m.title) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					  OR MATCH (txn_id) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					  OR MATCH (username) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					ORDER BY p.created DESC 
					LIMIT 10;";
		  
					$html = '';
					if ($result = $db->pdoQuery($sql)->results()) :
						$html .= '<div class="wojo small feed results segment">';
						foreach ($result as $row):
							$link = Url::adminUrl("members", "edit", false,"?id=" . $row->uid);
							$html .= '<div class="event">';
							$html .= '<div class="label">';
							$html .= '<i class="circular user icon"></i>';
							$html .= '</div>';
							$html .= '<div class="content">';
							$html .= '<div class="date">';
							$html .= Utility::dodate('long_date', $row->transdate);
							$html .= '</div>';
							$html .= '<div class="summary">';
							$html .= '<a href="' . $link . '">' . $row->username . '</a> (' . $row->txn_id . ')';
							$html .= '</div>';
							$html .= '<div class="extra text">';
							$html .= '<p><a href="' . Url::adminUrl("mailer", false,"?mailid=" . urlencode($row->email) . "&amp;clients=true"). '">' . $row->email . '</a></p>';
							$html .= '<p><a href="' . Url::adminUrl("packages", false,"?id=" . $row->mid). '">' . $row->title . '</a> <span>' . Utility::formatMoney($row->rate_amount, true) . '</p>';
							$html .= '</div>';
							$html .= '</div>';
							$html .= '</div>';
						endforeach;
						$html .= '</div>';
						print $html;
					endif;
				endif;
		  break;
		  
		  case "listingsearch" :
				if (strlen($string) > 3):
					$sql = "
					SELECT 
					  l.id,
					  l.year,
					  l.price,
					  l.user_id,
					  l.thumb,
					  l.created,
					  l.expire,
					  CONCAT(mk.name, ' ', md.name) AS title,
					  u.username 
					FROM
					  `" . Items::lTable . "` AS l 
					  LEFT JOIN `" . Content::mkTable . "` AS mk 
						ON mk.id = l.make_id 
					  LEFT JOIN `" . Content::mdTable . "` AS md 
						ON md.id = l.model_id 
					  LEFT JOIN `" . Users::mTable . "` AS u 
						ON u.id = l.user_id 
					WHERE MATCH (md.name) AGAINST ('" . $string . "*' IN BOOLEAN MODE) 
					  OR MATCH (mk.name) AGAINST ('" . $string . "*' IN BOOLEAN MODE) 
					  OR MATCH (username) AGAINST ('" . $string . "*' IN BOOLEAN MODE) 
					ORDER BY title 
					LIMIT 10;";

					$html = '';
					if ($result = $db->pdoQuery($sql)->results()) :
						$html .= '<div class="wojo small feed results segment">';
						foreach ($result as $row):
							$link = Url::adminUrl("items", "edit", false,"?id=" . $row->id);
							$html .= '<div class="event">';
							$html .= '<div class="label">';
							$html .= '<img src="' . UPLOADURL . 'listings/' . $row->thumb . '" alt="">';
							$html .= '</div>';
							$html .= '<div class="content">';
							$html .= '<div class="date">';
							$html .= Utility::dodate('long_date', $row->created) . '<br>';
							$html .= Utility::dodate('long_date', $row->expire);
							$html .= '</div>';
							$html .= '<div class="summary">';
							$html .= '<a href="' . $link . '">' . $row->title . '</a> <small>(' . $row->year . ')</small>';
							$html .= '</div>';
							$html .= '<div class="extra text">';
							$html .= '<p>' . Lang::$word->LST_PRICE . ': ' . Utility::formatMoney($row->price) . '</p>';
							$html .= '<p>' . Lang::$word->BY . ': <a href="' . Url::adminUrl("members", "edit", false,"?id=" . $row->user_id) . '">' . $row->username . '</a></p>';
							$html .= '</div>';
							$html .= '</div>';
							$html .= '</div>';
						endforeach;
						$html .= '</div>';
						print $html;
					endif;
				endif;
		  break;
		  
		  
		  case "webspecialssearch" :
			if (strlen ( $string ) > 3) :
				$sql = "
					SELECT
					  ws.webspecials_id,
					  ws.year,
					  ws.stock_number,
					  ws.buy_price,
					  ws.vehicle_image,
					  ws.created_ws,
					  ws.modified_ws,
					  ws.nice_title_ws,
					  ws.makename_ws,
					  ws.modelname_ws			
					  
					FROM
					  `" . wSpecials::w_sTable . "` AS ws
					WHERE MATCH (nice_title_ws) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					  OR MATCH (makename_ws) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					  OR MATCH (modelname_ws) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					ORDER BY ws.active DESC, ws.year DESC, ws.webspecials_id DESC
					LIMIT 10;";
				
				$html = '';
				if ($result = $db->pdoQuery ( $sql )->results ()) :
					$html .= '<div class="wojo small feed results segment">';
					foreach ( $result as $row ) :
						$link = Url::adminUrl ( "webspecials", "edit", false, "?id=" . $row->webspecials_id );
						$html .= '<div class="event">';
						$html .= '<div class="label">';
						$html .= '<img src="'. $row->vehicle_image . '" alt="">';
						$html .= '</div>';
						$html .= '<div class="content">';
						$html .= '<div class="date">';
						$html .= Utility::dodate ( 'long_date', $row->created_ws ) . '<br>';
						$html .= Utility::dodate ( 'long_date', $row->modified_ws );
						$html .= '</div>';
						$html .= '<div class="summary">';
						$html .= '<a href="' . $link . '">' . $row->nice_title_ws . '</a> <small>(' . $row->year . ')</small>';
						$html .= '</div>';
						$html .= '<div class="extra text">';
						$html .= '<p>' . Lang::$word->WSP_PRICE . ': ' . Utility::formatMoney ( $row->buy_price ) . '</p>';
						$html .= '</div>';
						$html .= '</div>';
						$html .= '</div>';
					endforeach;
					$html .= '</div>';
					print $html;
					
				
		  	endif;
		endif;
    break;
	  }

  endif;

  /* == Load Role Description == */
  if (isset($_GET['loadRoleDescription'])):
	  $row = $db->first(Users::rTable, null, array('id' => Filter::$id));
      if ($row):
          print '
		   <div class="wojo small form">
			 <form method="post" id="wojo_form" name="wojo_form">
			 <div class="field">
				<label>' . Lang::$word->NAME . '</label>
				<label class="input"><i class="icon-append icon asterisk"></i>
				  <input type="text" value="' . $row->name . '" name="name" required>
				</label>
			 </div>
			 <div class="field">
				<label>' . Lang::$word->DESCRIPTION . '</label>
				  <textarea  name="description" required>' . $row->description . '</textarea>
			 </div>
			 <input name="updateRoleDescription" type="hidden" value="1">
			 <input name="id" type="hidden" value="' . $row->id . '">
			 </form>
		   </div>
		 ';
      endif;
  endif;

  /* == Load Review Description == */
  if (isset($_GET['loadReviewDescription'])):
	  $row = $db->first(Content::rwTable, null, array('id' => Filter::$id));
      if ($row):
          print '
		   <div class="wojo small form">
			 <form method="post" id="wojo_form" name="wojo_form">
			 <div class="field">
				<label>' . Lang::$word->DESCRIPTION . '</label>
				  <textarea  name="content" required>' . $row->content . '</textarea>
			 </div>
			 <input name="updateReviewDescription" type="hidden" value="1">
			 <input name="id" type="hidden" value="' . $row->id . '">
			 </form>
		   </div>
		 ';
      endif;
  endif;
  
  /* == Quick Edit== */
  if (isset($_POST['quickedit'])):
      $title = Validator::cleanOut($_POST['title']);
      $title = strip_tags($title);
      switch ($_POST['type']) {
          /* == Update Member Group == */
          case "mgroups":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['name'] = Validator::sanitize($_POST['title']);
              else:
                  $data['description'] = Validator::sanitize($_POST['title']);
              endif;
                  $db->update(Users::mgTable, $data, array('id' => Filter::$id));
          break;

          /* == Update Privileges Group == */
          case "mprtype":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['name'] = Validator::sanitize($_POST['title']);
              else:
                  $data['description'] = Validator::sanitize($_POST['title']);
              endif;
                  $db->update(Users::pTable, $data, array('id' => Filter::$id));
          break;
		  
          /* == Update Country Vat == */
          case "cntvat":
              if (empty($_POST['title'])):
                  print '0.000';
                  exit;
              endif;
              if ($_POST['key'] == "vat"):
                  $data['vat'] = Validator::sanitize($_POST['title']);
              endif;
				  $db->update(Content::cTable, $data, array('id' => Filter::$id));
          break;

          /* == Update Feature == */
          case "feature":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['name'] = Validator::sanitize($_POST['title']);
              endif;
				  $db->update(Content::fTable, $data, array('id' => Filter::$id));
          break;

          /* == Update Make == */
          case "make":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['name'] = Validator::sanitize($_POST['title']);
              endif;
				  $db->update(Content::mkTable, $data, array('id' => Filter::$id));
          break;

          /* == Update Model == */
          case "model":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['name'] = Validator::sanitize($_POST['title']);
              endif;
				  $db->update(Content::mdTable, $data, array('id' => Filter::$id));
          break;
          
          /* == Update Pricing Discount Name == */
          case "pricediscount":
          	if (empty($_POST['title'])):
	          	print '--- EMPTY STRING ---';
	          	exit;
          	endif;
          	if ($_POST['key'] == "name"):
          		$data['name'] = Validator::sanitize($_POST['title']);
          	elseif ($_POST['key'] == "price"):
          		$data['price'] = Validator::sanitize($_POST['title']);
          	elseif ($_POST['key'] == "ordering"):
          		$data['ordering'] = Validator::sanitize($_POST['title']);
          	endif;
          		$db->update(wSpecials::wspTable, $data, array('id' => Filter::$id));
          	break;
		  
          /* == Update Condition == */
          case "conditions":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['name'] = Validator::sanitize($_POST['title']);
              endif;
				  $db->update(Content::cdTable, $data, array('id' => Filter::$id));
				  $sdata['cond_list'] = serialize($content->getConditions());
				  $db->update(Core::sTable, $sdata, array("id" => 1));
          break;

          /* == Update Fuel == */
          case "fuel":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['name'] = Validator::sanitize($_POST['title']);
              endif;
				  $db->update(Content::fuTable, $data, array('id' => Filter::$id));
				  $sdata['fuel_list'] = serialize($content->getFuel());
				  $db->update(Core::sTable, $sdata, array("id" => 1));
          break;
          

          /* == Update Year == */
          case "year":
          	if (empty($_POST['title'])):
          	print '--- EMPTY STRING ---';
          	exit;
          	endif;
          	if ($_POST['key'] == "name"):
          	$data['name'] = Validator::sanitize($_POST['title']);
          	endif;
          	$db->update(Content::yTable, $data, array('id' => Filter::$id));
          	$sdata['year_ws_list'] = serialize($content->getYear());
          	$db->update(Core::sTable, $sdata, array("id" => 1));
          	break;
          	
          	/* == Update Dealtype == */
          	case "dealtype":
          	 	if (empty($_POST['title'])):
          	  	print '--- EMPTY STRING ---';
          		exit;
          		endif;
          		if ($_POST['key'] == "name"):
          		$data['name'] = Validator::sanitize($_POST['title']);
          		endif;
          		$db->update(Content::dtTable, $data, array('id' => Filter::$id));
          		$sdata['dealtype_list'] = serialize($content->getDealtype());
          		$db->update(Core::sTable, $sdata, array("id" => 1));
          		break;
          		
          		/* == Update Lease == */
          	case "lease":
          		if (empty($_POST['title'])):
          		print '--- EMPTY STRING ---';
          		exit;
          		endif;
          		if ($_POST['key'] == "name"):
          		$data['name'] = Validator::sanitize($_POST['title']);
          		endif;
          		$db->update(Content::zsTable, $data, array('id' => Filter::$id));
          		$sdata['zerosingle_list'] = serialize($content->getLease());
          		$db->update(Core::sTable, $sdata, array("id" => 1));
          		break;
          	
          

          /* == Update Transmission == */
          case "transmissions":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['name'] = Validator::sanitize($_POST['title']);
              endif;
				  $db->update(Content::trTable, $data, array('id' => Filter::$id));
				  $sdata['trans_list'] = serialize($content->getTransmissions());
				  $db->update(Core::sTable, $sdata, array("id" => 1));
          break;

          /* == Update Gallery Image == */
          case "gallery":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['title'] = Validator::sanitize($_POST['title']);
              endif;
				  $db->update(Items::gTable, $data, array('id' => Filter::$id));
          break;
		    
          /* == Update Language Phrase == */
          case "phrase":
			  if (file_exists(BASEPATH . Lang::langdir . Core::$language . "/lang.xml")):
				  $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . "/lang.xml");
				  $node = $xmlel->xpath("/language/phrase[@data = '" . $_POST['key'] . "']");
				  $node[0][0] = $title;
				  $xmlel->asXML(BASEPATH . Lang::langdir . Core::$language . "/lang.xml");
			  endif;
              break;
      }

      print $title;
  endif;

  /* == Print Invoice == */
  if (isset($_GET['doInvoice'])):
      if ($row = $user->getUserInvoice(Filter::$id, intval($_GET['uid']))):
          $title = Validator::cleanOut(preg_replace("/[^a-zA-Z0-9\s]/", "", $row->title));

          ob_start();
          require_once (BASEPATH . 'mailer/Print_Pdf.tpl.php');
          $pdf_html = ob_get_contents();
          ob_end_clean();

          require_once (BASEPATH . 'lib/mPdf/mpdf.php');
          $mpdf = new mPDF('utf-8', $core->pagesize);
          $mpdf->SetTitle($title);
          $mpdf->WriteHTML($pdf_html);
          $mpdf->Output($title . ".pdf", "D");
          exit;
      else:
          exit;
      endif;
  endif;
  
  /* == Quick Status == */
  if (isset($_GET['quickStatus'])):
      if (Filter::$id):
          switch ($_GET['table']) {
              /* == Update Listing Status == */
              case "Listing":
			      $staus = intval($_GET['value']);
                  if ($_GET['field'] == "status") :
                      $data['status'] = $staus;
                  elseif($_GET['field'] == "featured") :
                      $data['featured'] = $staus;
                  else :
                      $data['sold'] = $staus;
                      $data['soldexpire'] = Db::toDate();
                  endif;
                  $db->update(Items::lTable, $data, array('id' => Filter::$id));
              break;
			  
              /* == Approve Listing == */
              case "Approve":
				  if(intval($_GET['value']) == 1):
                      $items->approveListing();
				  endif;
              break;
			  
              /* == Update Review Status == */
              case "Reviews":
			      $staus = intval($_GET['value']);
                  if ($_GET['field'] == "status") :
                      $data['status'] = $staus;
                  endif;
                  $db->update(Content::rwTable, $data, array('id' => Filter::$id));
              break;
          }
      endif;
  endif;
  
  /* == Quick Status == */
  if (isset ( $_GET ['quickStatus'] )) :
	if (Filter::$id) :
		switch ($_GET ['table']) {
			/* == Update Webspecials Status == */
			case "Webspecials" :
				$staus = intval ( $_GET ['value'] );
				if ($_GET ['field'] == "status") :
					$data ['active'] = $staus;
				 elseif ($_GET ['field'] == "featured") :
					$data ['featured_special'] = $staus;
				 else :
					$data ['sold'] = $staus;
					$data ['soldexpire'] = Db::toDate ();
				endif;
				$db->update ( wSpecials::wsTable, $data, array ('id' => Filter::$id ));
				$db->update ( wSpecials::w_sTable, $data, array ('webspecials_id' => Filter::$id ));
				break;
				
				case "PriceDiscounts" :
					$staus = intval ( $_GET ['value'] );
					if ($_GET ['field'] == "status") :
						$data ['active'] = $staus;
					endif;
						$db->update ( wSpecials::wspTable, $data, array ('id' => Filter::$id ));
					break;
				
			
		}
	endif;
endif;


  /* == Sort F.A.Q.s == */
  if (isset($_POST['sortfaq'])):
      $i = 0;
      foreach ($_POST['sorting'] as $v):
	      $i++;
          $data['sorting'] = $i;
          $db->update(Content::faqTable, $data,  array('id' => $v));
      endforeach;
  endif;

  /* == Sort Features == */
  if (isset($_POST['sortfeatures'])):
      $i = 0;
      foreach ($_POST['sorting'] as $v):
	      $i++;
          $data['sorting'] = $i;
          $db->update(Content::fTable, $data,  array('id' => $v));
      endforeach;
  endif;
  
  /* == Sort Webspecials == */
  if (isset($_POST['sortwebspecials'])):
	  $i = 0;
	  foreach ($_POST['ordering'] as $v):
		  $i++;
		  $data['ordering'] = $i;
		  $db->update(wSpecials::w_sTable, $data,  array('webspecials_id' => $v));
		  $db->update(wSpecials::wsTable, $data,  array('id' => $v));
  	endforeach;	
  endif;
  
  /* == Sort Pricediscount == */
  if (isset($_POST['sortpricediscount'])):
  	$i = 0;
  	foreach ($_POST['ordering'] as $v):
  		$i++;
		$data['ordering'] = $i;
		$db->update(wSpecials::wspTable, $data,  array('id' => $v));		
  	endforeach;
  endif;

  /* == Sort Slides == */
  if (isset($_POST['sortslides'])):
      $i = 0;
      foreach ($_POST['sorting'] as $v):
	      $i++;
          $data['sorting'] = $i;
          $db->update(Content::slTable, $data,  array('id' => $v));
      endforeach;         
  endif;
  
  /* == Sort Gallery == */
  if (isset($_POST['sortgal'])):
      $i = 0;
      foreach ($_POST['sorting'] as $v):
	      $i++;
          $data['sorting'] = $i;
          $db->update(Items::gTable, $data,  array('id' => $v));
      endforeach;
	  if ($gallery = Items::getGalleryImages(Filter::$id)) :
		  $db->update(Items::lTable, array('gallery' => serialize($gallery)), array('id' => Filter::$id));
	  endif;
  endif;

  /* == Upload Gallery Images == */
  if (isset($_POST['uploadGimages'])):
      Items::processGaleryImage("file", Filter::$id);
  endif;
  
  /* == Get Content Type == */
  if (isset($_GET['contenttype'])):
      $type = Validator::sanitize($_GET['contenttype']);
      $html = "";
      switch ($type):
          case "page":
              if ($result = $db->select(Content::pgTable, array("id", "title"), array("active" => 1), 'ORDER BY title ASC')->results()):
                  foreach ($result as $row):
                      $html .= "<option value=\"" . $row->id . "\">" . $row->title . "</option>\n";
                  endforeach;
                  $json['type'] = 'page';
                  $json['message'] = $html;
              endif;
              break;

          default:
              $html .= "<input name=\"page_id\" type=\"hidden\" value=\"0\" />";
              $json['type'] = 'web';
              $json['message'] = $html;
      endswitch;

      print json_encode($json);
  endif;
  
  /* == Load Menus == */
  if (isset($_GET['getmenus'])):
      $content->getSortMenuList();
  endif;

  /* == Sort Menus == */
  if (isset($_POST['sortMenus'])):
      $jsonstring = $_POST['sortlist'];
      $jsonDecoded = json_decode($jsonstring, true, 64);

      function parseJsonArray($jsonArray, $parent_id = 0)
      {
          $return = array();
          foreach ($jsonArray as $subArray) {
              $returnSubSubArray = array();
              if (isset($subArray['children'])) {
                  $returnSubSubArray = parseJsonArray($subArray['children'], $subArray['id']);
              }
              $return[] = array('id' => $subArray['id'], 'parent_id' => $parent_id);
              $return = array_merge($return, $returnSubSubArray);
          }

          return $return;
      }

      $readbleArray = parseJsonArray($jsonDecoded);
	  $i = 0;
      foreach ($readbleArray as $value):
          if (is_array($value)):
		      $i++;
              $data = array('position' => $i);
              $db->update(Content::muTable, $data, array('id' => $value['id']));

          endif;
      endforeach;
  endif;
  
  /* == Load Language Section == */
  if (isset($_GET['loadLangSection'])):
	  $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . "/lang.xml");
	  $i = 1;
	  $html = '';
	  if ($_GET['section'] == "all"):
		  foreach ($xmlel as $pkey):
			  $html .= '
			  <tr>
			    <td><small>' . $i++ . '.</small></td>
				<td>' . $pkey['data'] . '</td>
				<td data-editable="true" data-set=\'{"type": "phrase", "id": ' . $i++ . ',"key":"' . $pkey['data'] . '", "path":"lang"}\'>' . $pkey . '</td>
			  </tr>';
		  endforeach;
	  else:
		  $section = $xmlel->xpath('/language/phrase[@section="' . Validator::sanitize($_GET['section']) . '"]');
		  foreach ($section as $pkey):
			  $html .= '
			  <tr>
			    <td><small>' . $i++ . '.</small></td>
				<td>' . $pkey['data'] . '</td>
				<td data-editable="true" data-set=\'{"type": "phrase", "id": ' . $i++ . ',"key":"' . $pkey['data'] . '", "path":"lang"}\'>' . $pkey . '</td>
			  </tr>';
		  endforeach;
	  endif;
	  
	  $json['status']  = 'success';
	  $json['title']   = Lang::$word->SUCCESS;
	  $json['message'] = $html;
	  print json_encode($json);
  endif;


  if (isset($_GET['addNew'])):

      switch ($_GET['addNew']) {
          /* == Add New Make == */
          case "addNewMake":
              print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->MAKE_NAME . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->MAKE_NAME . '" name="name" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="processMake">
				</form>
			  </div>';
              break;
              
              /* == Add New Makews == */
              case "addNewMakews":
              	print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->MAKE_NAME . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->MAKE_NAME . '" name="name" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="processMakews">
				</form>
			  </div>';
              	break;

          /* == Add New Feature == */
          case "addNewFeature":
              print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->FEAT_NAME . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->FEAT_NAME . '" name="name" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="processFeature">
				</form>
			  </div>';
              break;

          /* == Add New Condition == */
          case "addNewCondition":
              print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->COND_NAME . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->COND_NAME . '" name="name" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="processCondition">
				</form>
			  </div>';
              break;

          /* == Add New Fuel Type == */
          case "addNewFuel":
              print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->FUEL_NAME . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->FUEL_NAME . '" name="name" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="processFuel">
				</form>
			  </div>';
              break;
              
              /* == Add New Year == */
              case "addNewYear":
              	print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->YEAR_NAME . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->YEAR_NAME . '" name="name" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="processYear">
				</form>
			  </div>';
              	break;
              	
              	/* == Add New Deal == */
              case "addNewDealtype":
              		print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->DEAL_NAME . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->DEAL_NAME . '" name="name" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="processDealtype">
				</form>
			  </div>';
              	break;
              	
              	/* == Add New Lease == */
              	case "addNewLease":
              		print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->LEASE_NAME . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->LEASE_NAME . '" name="name" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="processLease">
				</form>
			  </div>';
              break;
              

          /* == Add New Transmission Type == */
          case "addNewTransmission":
              print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->TRNS_NAME . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->TRNS_NAME . '" name="name" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="processTransmission">
				</form>
			  </div>';
              break;
			     	  
          /* == Add New Ban == */
          case "addNewBan";
              $ip = isset($_GET['hasIP']) ? Validator::sanitize($_GET['hasIP']) : null;
              print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->BL_ITEM . '</label>
					<div class="wojo labeled icon input">
					  <input type="text" placeholder="' . Lang::$word->BL_ITEM . '" value="' . $ip . '" name="item" required>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <div class="field">
					<label>' . Lang::$word->BL_TYPE . '</label>
					<div class="inline-group">
					  <label class="radio">
						<input name="type" type="radio" value="IP" checked="checked">
						<i></i>' . Lang::$word->IP . '</label>
					  <label class="radio">
						<input name="type" type="radio" value="Email">
						<i></i>' . Lang::$word->EMAIL . '</label>
					</div>
				  </div>
				  <div class="field">
					<label>' . Lang::$word->COMMENT . '</label>
					<textarea  name="comment" placeholder="' . Lang::$word->COMMENT . '"></textarea>
				  </div>
				  <input name="action" type="hidden" value="processBan">
				</form>
			  </div>';
          break;
		  
          /* == Reject Listing == */
          case "rejectListing":
              print '
			  <div class="wojo small form">
				<form method="post" id="wojo_form" name="wojo_form">
				  <div class="field">
					<label>' . Lang::$word->LST_REASON . '</label>
					<div class="wojo labeled icon input">
					  <textarea name="notes" required></textarea>
					  <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
					</div>
				  </div>
				  <input name="action" type="hidden" value="rejectListing">
				  <input name="id" type="hidden" value="' . Filter::$id . '">
				</form>
			  </div>';
              break;

      }
  endif;

  /* == Export Transactions == */
  if (isset($_GET['exportTransactions'])) :
	  $sql = "
	  SELECT 
		p.*,
		p.id AS id,
		u.username,
		m.title AS title
	  FROM
		`" . Content::txTable . "` AS p 
		LEFT JOIN `" . Users::mTable . "` AS u 
		  ON u.id = p.user_id 
		LEFT JOIN `" . Content::msTable . "` AS m 
		  ON m.id = p.membership_id;";
      $data = $db->pdoQuery($sql)->results();
      
      $type = "vnd.ms-excel";
	  $date = date('m-d-Y H:i');
	  $title = "Exported from the " . $core->company . " on $date";

      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
	  header("Content-Type: application/$type");
      header("Content-Disposition: attachment;filename=temp_" . time() .".xls");
      header("Content-Transfer-Encoding: binary ");
      
	  print '
	  <table width="100%" cellpadding="1" cellspacing="2" border="1">
	  <caption>' . $title . '</caption>
		<tr>
		  <td>TXN_ID</td>
		  <td>' .Lang::$word-> TRX_MEMNAME . '</td>
		  <td>' . Lang::$word->USERNAME . '</td>
		  <td>' . Lang::$word->AMOUNT . '</td>
		  <td>' . Lang::$word->TRX_TAX . '</td>
		  <td>' . Lang::$word->TRX_COUPON . '</td>
		  <td>' . Lang::$word->TRX_PAYDATE . '</td>
		  <td>' . Lang::$word->TRX_PROCESSOR . '</td>
		  <td>IP</td>
		</tr>';
		foreach ($data as $row) :
			print '<tr>
			  <td>' . $row->txn_id . '</td>
			  <td>' . $row->title. '</td>
			  <td>' . $row->username . '</td>
			  <td>' . $row->rate_amount . '</td>
			  <td>' . $row->tax . '</td>
			  <td>' . $row->coupon . '</td>
			  <td>' . Utility::dodate("long_date", $row->created) . '</td>
			  <td>' . $row->pp . '</td>
			  <td>' . $row->ip . '</td>
			</tr>';
		endforeach;				

	  print '</table>';
	  exit();
  endif;
  
  /* == Export exportWebspecials == */
  if (isset($_GET['exportWebspecials'])) :
  $id = $_GET['store_id'];
  $storename = $_GET['storename'];
  $sql = "
	  SELECT
		ws.title_ws,  		
		ws.storename_ws,
		ws.id, 
		ws.specials_type, 
		ws.buy_price, 
		ws.lease_price, 
		ws.stock_number, 
		ws.msrp, 
		ws.save_up_to_amount, 
		ws.lease_extras, 
		ws.lease_term, 
		ws.alt_link_url, 
		ws.zero_down_lease_price, 
		ws.zero_down_lease_term,
		ws.created_ws,	  		
		ws.modified_ws 	  		
	  FROM
		`" . wSpecials::w_sTable . "` AS ws
		 LEFT JOIN `" . Content::lcTable . "` AS s
			ON ws.store_id = s.store_id
		    WHERE s.store_id= '" . $id . "'
			And ws.specials_type IN ('SALES','COMMERCIAL')
			AND  ws.active IN (1)
			ORDER BY ws.year DESC
		";
  
  $data = $db->pdoQuery($sql)->results();
  
  $type = "vnd.ms-excel";
  $date = date('m-d-Y H:i');
  $title = "Exported from the " . $core->company . " on $date";
  
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Type: application/download");
  header("Content-Type: application/$type");
  header("Content-Disposition: attachment;filename='".$storename."_WebSpecialsInfo_" . time() .".xls");
  header("Content-Transfer-Encoding: binary ");
  
  print '
	  <table width="100%" cellpadding="1" cellspacing="2" border="1">
	  <caption>' . $title . '</caption>
		<tr>
		  <td>DealershipName</td>
		  <td>WebSpecialTitle</td>
		  <td>WebSpecialID</td>
		  <td>Specialstype</td>
		  <td>BuyPrice</td>
		  <td>LeasePrice</td>
		  <td>StockNumber</td>
		  <td>MSRP</td>
		  <td>SaveUpToAmount</td>
		  <td>LeaseDownPayment</td>
		  <td>LeaseTerm</td>
		  <td>ZeroDownLeasePrice</td>
		  <td>ZeroDownLeaseTerm</td>
		  <td>InventoryUrl</td>
		  <td>Created</td>
		  <td>Modified</td>
		</tr>';
  foreach ($data as $row) :
  print '<tr>
			  <td>' . $row->storename_ws . '</td>
			  <td>' . $row->title_ws. '</td>
			  <td>' . $row->id . '</td>
			  <td>' . $row->specials_type . '</td>
			  <td>' . $row->buy_price . '</td>
			  <td>' . $row->lease_price . '</td>
		  	  <td>' . $row->stock_number . '</td>
			  <td>' . $row->msrp . '</td>
			  <td>' . $row->save_up_to_amount . '</td>
			  <td>' . $row->lease_extras . '</td>
			  <td>' . $row->lease_term. '</td>
			  <td>' . $row->zero_down_lease_price . '</td>
			  <td>' . $row->zero_down_lease_term . '</td>
			  <td>' . $row->alt_link_url . '</td>
			  <td>' . Utility::dodate("long_date", $row->created_ws) . '</td>
			  <td>' . Utility::dodate("long_date", $row->modified_ws) . '</td>		
			</tr>';
  endforeach;
  
  print '</table>';
  exit();
  endif;
  
  /* == Load Mailer File == */
  if (isset($_GET['getMailerTemplate'])):
      $file = Validator::sanitize($_GET['filename']);
	  $path = BASEPATH . "/mailer/" . Core::$language . "/" . $file;
      if (file_exists($path)):
		  $html = file_get_contents($path);
          $json['status'] = 'success';
		  $json['title'] = substr(str_replace("_", " ",$file), 0, -8);
          $json['message'] = $html;
      else:
          $json['status'] = 'error';
          $json['message'] = Lang::$word->ET_ERROR;
      endif;
          print json_encode($json);
  endif;
  
  /* == Account Map Stats == */
  if (isset($_GET['getAccCountries'])):
	 $data = $stats->getAccountMapStats();
	 $json['country_name']= array();
	 $json['hits']= array();
      
	  if($data):
		  foreach($data as $row):
			  $json['country_name'][]= (string)$row->country_name;
			  $json['hits'][]= (int)$row->hits;
		  endforeach; 
	  endif;
      echo json_encode($json);
  endif;

  /* == Load Makelist  == */
  if (isset($_GET['getMakelist'])):
      $id = Validator::sanitize($_GET['getMakelist'], "int");

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
	
		/* == Load Makelist-ws == */
	if (isset ( $_GET ['getMakewslist'] )) :
		$id = Validator::sanitize ( $_GET ['getMakewslist'], "int" );
		
		$html = "";
		if ($result = $db->select ( Content::mdwsTable, array (
				"id",
				"name" 
		), array (
				'make_id' => $id 
		), 'ORDER BY name ASC' )->results ()) :
			foreach ( $result as $row ) :
				$html .= "<option value=\"" . $row->id . "\">" . $row->name . "</option>\n";
			endforeach
			;
			unset ( $row );
		 else :
			$html .= "<option value=\"\">--- " . Lang::$word->MAKE_NAME_R . " ---</option>\n";
		endif;
		$json ['type'] = "success";
		$json ['message'] = $html;
		echo json_encode ( $json );
	endif;
  
  /* == Create Database Backup == */
  if (isset($_GET['doBackup'])):
      if ($filename = dbTools::doBackup()):
          $html = '
		  <div class="item active"><i class="big icon hdd"></i>
			<div class="header">' . File::getFileSize(BASEPATH . 'admin/backups/' . $filename, "kb", true) . '</div>
			<div class="push-right"> <a class="delete" data-set=\'{"title": "' . Lang::$word->DBM_DEL . '", "parent": ".item", "option": "deleteBackup", "id": 1, "name": "' . $filename . '"}\'><i class="rounded inverted negative trash icon link"></i></a> <a href="' . ADMINURL . '/backups/' . $filename . '" data-content="' . Lang::$word->DOWNLOAD . '"><i class="rounded inverted positive cloud download icon link"></i></a> <a class="restore" data-content="' . Lang::$word->RESTORE . '" data-file="' . $filename . '"><i class="rounded inverted primary refresh icon link"></i></a> </div>
			<div class="content">' . str_replace(".sql", "", $filename) . '</div>
		  </div>';
          $json['type'] = 'success';
          $json['title'] = Lang::$word->SUCCESS;
		  $json['html'] = $html;
          $json['message'] = Lang::$word->DBM_BKP_OK;
      else:
          $json['type'] = 'error';
		  $json['title'] = Lang::$word->ERROR;
		  $json['html'] = '';
          $json['message'] = Lang::$word->DBM_BKP_ERR;
      endif;
      print json_encode($json);
  endif;
  
  /* == Restore SQL Backup == */
  if (isset($_POST['restoreBackup'])):
	  if(dbTools::doRestore($_POST['restoreBackup'])) :
		  $json['type'] = 'success';
		  $json['title'] = Lang::$word->SUCCESS;
		  $json['message'] = str_replace("[NAME]", $_POST['restoreBackup'], Lang::$word->DBM_RES_OK);
	  else :
		  $json['type'] = 'warning';
		  $json['title'] = Lang::$word->ALERT;
		  $json['message'] = Lang::$word->NOPROCCESS;
	  endif;
	  print json_encode($json);
  endif;

  /* == Upload Gallery Images == */
  if (isset($_POST['processGalleryImages'])):
	  if (isset($_FILES['photo']['tmp_name'])):
		  $num_files = count($_FILES['photo']['tmp_name']);
		  $filedir = UPLOADS . 'listings/pics' . Filter::$id . '/';
		  File::makeDirectory($filedir . '/thumbs');
		  for ($x = 0; $x < $num_files; $x++):
			  $image = $_FILES['photo']['name'][$x];
	
			  $newName = "IMG_" . Utility::randName();
			  $ext = substr($image, strrpos($image, '.') + 1);
			  $fullname = $filedir . $newName . "." . strtolower($ext);
			  if (!move_uploaded_file($_FILES['photo']['tmp_name'][$x], $fullname)) {
				  die(Message::msgSingleError(Lang::$word->_FILE_ERR, false));
			  }

			  try {
				  $img = new Image(UPLOADS . 'listings/pics' . Filter::$id . '/' . $newName . "." . strtolower($ext));
				  $img->thumbnail(400, 300)->save(UPLOADS . 'listings/pics' . Filter::$id . '/thumbs/' . $newName . "." . strtolower($ext));
			  } catch(Exception $e) {
				  echo 'Error: ' . $e->getMessage();
			  }
			  
			  $data['photo'] = $newName . "." . strtolower($ext);
			  $data['listing_id'] = Filter::$id;
	
			  $last_id = $db->insert(Items::gTable, $data)->getLastInsertId();
			  print '
			  <div class="wojo reveal"><img data-lazy="' . UPLOADURL . 'listings/pics' . Filter::$id . '/thumbs/' . $data['photo'] . '" alt="">
				<div class="overlay"></div>
				<div class="corner-overlay-content"><i class="icon long arrow right"></i></div>
				<div class="overlay-content">
				  <p data-editable="true" data-set=\'{"type": "gallery", "id": ' . $last_id . ',"key":"name", "path":""}\'></p>
				  <a class="delslide wojo top right large corner label" data-set=\'{"title": "' . Lang::$word->GAL_DELETE . '>", "parent": ".reveal", "option": ' . Filter::$id . ', "id": ' . $last_id . ', "name": "", "path":"' . $data['photo'] . '"}\'><i class="icon negative delete link"></i></a> </div>
			  </div>';
		  endfor;
	  endif;
  endif;

  /* == Reset Listing Stats == */
  if (isset($_GET['resetListingStats'])):
	  $db->delete(Stats::sTable, array("listing_id" => Filter::$id));
  endif;
  
  /* == Listing Stats == */
  if (isset($_GET['getListingStats'])):
      $data = array();
      $data['xaxis'] = array();
      $data['visits']['label'] = Lang::$word->VISITS;
	  
	  for ($i = 1; $i <= 12; $i++) {
		  $data['xaxis'][] = array($i, Utility::dodate("MMM", date('M', mktime(0, 0, 0, $i))));
		  $reg_data[$i] = array('month' => date('M', mktime(0, 0, 0, $i)), 'visits' => 0);
	  }

	  $sql = ("
		SELECT 
		  SUM(visits) AS visits,
		  created 
		FROM
		  `" . Stats::sTable . "` 
		WHERE  listing_id = " . Filter::$id . "
		GROUP BY MONTH(created);
	  ");
	  $query = $db->pdoQuery($sql);
	  foreach ($query->results() as $result) {
		  $reg_data[date('n', strtotime($result->created))] = array('month' => Utility::dodate("MMM", date('M', strtotime($result->created))), 'visits' => $result->visits);
	  }

	  foreach ($reg_data as $key => $value) {
		  $data['visits']['data'][] = array($key, $value['visits']);
	  }

      print json_encode($data);
  endif;

  /* == Visits Stats == */
  if (isset($_GET['getMainVisitStats'])):
      Stats::getAllVisits();
  endif;
  
  /* == Transaction Stats == */
  if (isset($_GET['getTransStats'])):
      $range = (isset($_GET['timerange'])) ? Validator::sanitize($_GET['timerange'], "string", 6) : 'month';
      $data = array();
      $data['xaxis'] = array();
      $data['sales']['label'] = Lang::$word->TRX_TOTSALES;
      $data['amount']['label'] = Lang::$word->TRX_TOTAMT;
	  
      switch ($range) {
          case 'day':
              for ($i = 0; $i < 24; $i++) {
                  $data['xaxis'][] = array($i, $i);
                  $reg_data[$i] = array('hour' => $i, 'sales' => 0, 'amount' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(*) AS sales,
				  HOUR(created) AS hour ,
				  SUM(rate_amount) AS amount
				FROM
				  `" . Content::txTable . "` 
				WHERE DATE(created) = DATE(NOW())
				GROUP BY HOUR(created)
				ORDER BY created ASC;
			  ");
			  $query = $db->pdoQuery($sql);
			  foreach ($query->results() as $result) {
				  $reg_data[$result->hour] = array('hour' => $result->hour, 'sales' => $result->sales, 'amount' => $result->amount);
			  }

              foreach ($reg_data as $key => $value) {
                  $data['sales']['data'][] = array($key, $value['sales']);
				  $data['amount']['data'][] = array($key, $value['amount']);
              }

              break;

          case 'week':
              $date_start = strtotime('-' . date('w') . ' days');

              for ($i = 0; $i < 7; $i++) {
                  $date = date('Y-m-d', $date_start + ($i * 86400));
                  $data['xaxis'][] = array(date('w', strtotime($date)), Utility::dodate("EE", date('D', strtotime($date))));
                  $reg_data[date('w', strtotime($date))] = array('day' => date('D', strtotime($date)), 'sales' => 0, 'amount' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(*) AS sales,
				  SUM(rate_amount) AS amount,
				  created
				FROM
				  `" . Content::txTable . "` 
				WHERE DATE(created) >= DATE('" . Validator::sanitize(date('Y-m-d', $date_start), "string", 10) . "')
				GROUP BY DAYNAME(created)
			  ");
			  $query = $db->pdoQuery($sql);
              foreach ($query->results() as $result) {
                  $reg_data[date('w', strtotime($result->created))] = array('day' => date('D', strtotime($result->created)), 'sales' => $result->sales, 'amount' => $result->amount);
              }

              foreach ($reg_data as $key => $value) {
                  $data['sales']['data'][] = array($key, $value['sales']);
				  $data['amount']['data'][] = array($key, $value['amount']);
              }

              break;

          case 'month':
              for ($i = 1; $i <= date('t'); $i++) {
                  $date = date('Y') . '-' . date('m') . '-' . $i;
                  $data['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));

                  $reg_data[date('j', strtotime($date))] = array('day' => date('d', strtotime($date)), 'sales' => 0, 'amount' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(*) AS sales,
				  SUM(rate_amount) AS amount,
				  created
				FROM
				  `" . Content::txTable . "` 
				WHERE DATE(created) >= '" . Validator::sanitize(date('Y') . '-' . date('m') . '-1', "string", 10) . "'
				GROUP BY DAY(created);
			  ");
			  $query = $db->pdoQuery($sql);
              foreach ($query->results() as $result) {
                  $reg_data[date('j', strtotime($result->created))] = array('day' => date('d', strtotime($result->created)), 'sales' => $result->sales, 'amount' => $result->amount);
              }

              foreach ($reg_data as $key => $value) {
                  $data['sales']['data'][] = array($key, $value['sales']);
				  $data['amount']['data'][] = array($key, $value['amount']);
              }

              break;

          case 'year':
              for ($i = 1; $i <= 12; $i++) {
                  $data['xaxis'][] = array($i, Utility::dodate("MMM", date('M', mktime(0, 0, 0, $i))));
                  $reg_data[$i] = array('month' => date('M', mktime(0, 0, 0, $i)), 'sales' => 0, 'amount' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(id) AS sales,
				  SUM(rate_amount) AS amount,
				  created 
				FROM
				  `" . Content::txTable . "` 
				WHERE YEAR(created) = YEAR(NOW())
				GROUP BY MONTH(created);
			  ");
			  $query = $db->pdoQuery($sql);
              foreach ($query->results() as $result) {
                  $reg_data[date('n', strtotime($result->created))] = array('month' => Utility::dodate("MMM", date('M', strtotime($result->created))), 'sales' => $result->sales, 'amount' => $result->amount);
              }

              foreach ($reg_data as $key => $value) {
                  $data['sales']['data'][] = array($key, $value['sales']);
				  $data['amount']['data'][] = array($key, $value['amount']);
              }

              break;
			      
          case 'all':
              for ($i = 1; $i <= 12; $i++) {
                  $data['xaxis'][] = array($i, Utility::dodate("MMM", date("Y-m-d", mktime(0, 0, 0, $i, 10))));
                  $reg_data[$i] = array('month' => date('M', mktime(0, 0, 0, $i)), 'sales' => 0, 'amount' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(id) AS sales,
				  SUM(rate_amount) AS amount,
				  created 
				FROM
				  `" . Content::txTable . "` 
				GROUP BY MONTH(created);
			  ");
			  $query = $db->pdoQuery($sql);
              foreach ($query->results() as $result) {
                  $reg_data[date('n', strtotime($result->created))] = array('month' => Utility::dodate("MMM", date('M', strtotime($result->created))), 'sales' => $result->sales, 'amount' => $result->amount);
              }

              foreach ($reg_data as $key => $value) {
                  $data['sales']['data'][] = array($key, $value['sales']);
				  $data['amount']['data'][] = array($key, $value['amount']);
              }

              break;
      }

      print json_encode($data);
  endif;
 
  /* == Account Stats == */
  if (isset($_GET['getAccStats'])):
      $range = (isset($_GET['timerange'])) ? Validator::sanitize($_GET['timerange'], "string", 6) : 'month';
      $data = array();
      $data['xaxis'] = array();
      $data['regs']['label'] = Lang::$word->REGISTRATIONS;
      $data['regs']['color'] = "#1CA8DD";

      $reg_data = array();

      switch ($range) {
          case 'day':
              for ($i = 0; $i < 24; $i++) {
                  $data['xaxis'][] = array($i, $i);
                  $reg_data[$i] = array('hour' => $i, 'total' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(*) AS total,
				  HOUR(created) AS hour 
				FROM
				  `" . Users::mTable . "` 
				WHERE DATE(created) = DATE(NOW()) 
				GROUP BY HOUR(created) 
				ORDER BY created ASC;
			  ");
			  $query = $db->pdoQuery($sql);
			  foreach ($query->results() as $result) {
				  $reg_data[$result->hour] = array('hour' => $result->hour, 'total' => $result->total);
			  }

              foreach ($reg_data as $key => $value) {
                  $data['regs']['data'][] = array($key, $value['total']);
              }

              break;

          case 'week':
              $date_start = strtotime('-' . date('w') . ' days');

              for ($i = 0; $i < 7; $i++) {
                  $date = date('Y-m-d', $date_start + ($i * 86400));
                  $data['xaxis'][] = array(date('w', strtotime($date)), Utility::dodate("EE", date('D', strtotime($date))));
                  $reg_data[date('w', strtotime($date))] = array('day' => date('D', strtotime($date)), 'total' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(*) AS total,
				  created
				FROM
				  `" . Users::mTable . "` 
				WHERE DATE(created) >= DATE('" . Validator::sanitize(date('Y-m-d', $date_start), "string", 10) . "') 
				GROUP BY DAYNAME(created)
			  ");
			  $query = $db->pdoQuery($sql);
              foreach ($query->results() as $result) {
                  $reg_data[date('w', strtotime($result->created))] = array('day' => date('D', strtotime($result->created)), 'total' => $result->total);
              }

              foreach ($reg_data as $key => $value) {
                  $data['regs']['data'][] = array($key, $value['total']);
              }

              break;

          case 'month':
              for ($i = 1; $i <= date('t'); $i++) {
                  $date = date('Y') . '-' . date('m') . '-' . $i;
                  $data['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));

                  $reg_data[date('j', strtotime($date))] = array('day' => date('d', strtotime($date)), 'total' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(*) AS total,
				  created
				FROM
				  `" . Users::mTable . "` 
				WHERE DATE(created) >= '" . Validator::sanitize(date('Y') . '-' . date('m') . '-1', "string", 10) . "'
				GROUP BY DAY(created);
			  ");
			  $query = $db->pdoQuery($sql);
              foreach ($query->results() as $result) {
                  $reg_data[date('j', strtotime($result->created))] = array('day' => date('d', strtotime($result->created)), 'total' => $result->total);
              }

              foreach ($reg_data as $key => $value) {
                  $data['regs']['data'][] = array($key, $value['total']);
              }

              break;

          case 'year':
              for ($i = 1; $i <= 12; $i++) {
                  $data['xaxis'][] = array($i, Utility::dodate("MMM", date("Y-m-d", mktime(0, 0, 0, $i, 10))));
                  $reg_data[$i] = array('month' => date('M', mktime(0, 0, 0, $i)), 'total' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(id) AS total,
				  created 
				FROM
				  `" . Users::mTable . "` 
				WHERE YEAR(created) = YEAR(NOW()) 
				GROUP BY MONTH(created);
			  ");
			  $query = $db->pdoQuery($sql);
              foreach ($query->results() as $result) {
                  $reg_data[date('n', strtotime($result->created))] = array('month' => Utility::dodate("MMM", date('M', strtotime($result->created))), 'total' => $result->total);
              }

              foreach ($reg_data as $key => $value) {
                  $data['regs']['data'][] = array($key, $value['total']);
              }

              break;

          case 'all':
              for ($i = 1; $i <= 12; $i++) {
                  $data['xaxis'][] = array($i, Utility::dodate("MMM", date("Y-m-d", mktime(0, 0, 0, $i, 10))));
                  $reg_data[$i] = array('month' => date('M', mktime(0, 0, 0, $i)), 'total' => 0);
              }

			  $sql = ("
				SELECT 
				  COUNT(id) AS total,
				  created 
				FROM
				  `" . Users::mTable . "` 
				GROUP BY MONTH(created);
			  ");
			  $query = $db->pdoQuery($sql);
              foreach ($query->results() as $result) {
                  $reg_data[date('n', strtotime($result->created))] = array('month' => Utility::dodate("MMM", date('M', strtotime($result->created))), 'total' => $result->total);
              }

              foreach ($reg_data as $key => $value) {
                  $data['regs']['data'][] = array($key, $value['total']);
              }

              break;
      }

      print json_encode($data);
  endif;
  
  
  /* == Main Stats == */
  if (isset($_GET['getMainStats'])):
      Stats::getMainStats();
  endif;

  /* == Top Five  Stats == */
  if (isset($_GET['getTopFive'])):
      Stats::topFiveVisits();
  endif;
  
  /* == Clear Session Temp Queries == */
  if (isset($_GET['ClearSessionQueries'])):
      App::get('Session')->remove('debug-queries');
	  App::get('Session')->remove('debug-warnings');
	  App::get('Session')->remove('debug-errors');
	  App::get('Session')->remove('debug-params');
	  print 1;
  endif;