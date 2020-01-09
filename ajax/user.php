<?php
  /**
   * User
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: user.php, v1.00 2015-10-05 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once("../init.php");

  if (!$auth->is_User())
      exit;
	  
  /* Proccess Cart */
  if (isset($_POST['addtocart'])):
      if ($row = $db->first(Content::msTable, null, array("id" => Filter::$id))):
          $gaterows = $content->getGetaways(true);

          if ($row->price == 0):
              $data = array(
                  'membership_id' => $row->id,
                  'membership_expire' => $user->calculateDays($row->id),
                  );

              $db->update(Users::mTable, $data, array("id=" . $auth->uid));
			  App::get('Session')->set('membership_id', $row->id);
              $message = str_replace("[NAME]", '<b>' . $row->title . '</b>', LANG::$word->M_ACTIVATE_OK);
              $json['message'] = Message::msgSingleOk($message, false);
              print json_encode($json);

          else:
              $db->delete(Items::xTable, array("uid" => $auth->uid));
              $tax = Core::calculateTax();
              $data = array(
                  'uid' => $auth->uid,
                  'mid' => $row->id,
                  'originalprice' => $row->price,
                  'tax' => Utility::formatNumber($tax),
                  'totaltax' => Utility::formatNumber($row->price * $tax),
                  'total' => $row->price,
                  'totalprice' => Utility::formatNumber($tax * $row->price + $row->price)
                  );
              $db->insert(Items::xTable, $data);
              $cart = Core::getCart();
			  $featured = ($row->featured) ? Lang::$word->YES : Lang::$word->NO;

			  $html ='
			  <div class="wojo divided form card">
				<div class="header">' . Lang::$word->M_P_SUMMARY . '</div>
				<div class="item">
				  <div class="intro max">' . Lang::$word->MSM_NAME . '</div>
				  <div class="data">' . $row->title . '</div>
				</div>
				<div class="item">
				  <div class="intro max">#' . Lang::$word->LISTINGS . '</div>
				  <div class="data">' . $row->listings . '</div>
				</div>
				<div class="item">
				  <div class="intro max">' . Lang::$word->CF_FEATURED . '</div>
				  <div class="data">' . $featured . '</div>
				</div>
				<div class="item">
				  <div class="intro max">' . Lang::$word->MSM_PRICE . '</div>
				  <div class="data">' . Utility::formatMoney($cart->total, true) . '</div>
				</div>
				<div class="item">
				  <div class="intro max">' . Lang::$word->MSM_PERIOD . '</div>
				  <div class="data">' . $row->days . ' ' . Utility::getPeriod($row->period) . '</div>
				</div>
				<div class="item">
				  <div class="intro max">' . Lang::$word->MSM_VALIDTO . '</div>
				  <div class="data">' .  $user->calculateDays($row->id, true) . '</div>
				</div>';
				if ($core->tax) :
				$html .='
				<div class="item">
				  <div class="intro max">' . Lang::$word->VAT . '</div>
				  <div class="data totaltax">' . Utility::formatMoney($cart->total * $cart->tax, true) . '</div>
				</div>';
				endif;
				$html .='
				<div class="item">
				  <div class="intro max">' . Lang::$word->DC_DISC . '</div>
				  <div class="data disc">0.00</div>
				</div>
				<div class="item">
				  <div class="intro max">' . Lang::$word->DC_CODE . '</div>
				  <div class="data">
					<div class="wojo inline action input">
					  <input type="text" placeholder="' . Lang::$word->DC_CODE_I . '" name="coupon">
					  <a id="cinput" data-id="' . $row->id . '" class="wojo primary icon button"><i class="icon long right arrow"></i></a></div>
				  </div>
				</div>
				<div class="item">
				  <div class="intro max">' . Lang::$word->TRX_TOTAMT . '</div>
				  <div class="data totalamt">' . Utility::formatMoney($cart->tax * $cart->total + $cart->total, true) . '</div>
				</div>
				<div class="item">
				  <div class="intro max">' . Lang::$word->MSM_DESC . '</div>
				  <div class="data">' . $row->description . '</div>
				</div>
				<div class="item" id="gatedata">
				  <div class="intro max">' . Lang::$word->M_P_PAYWITH . '</div>
				  <div class="data">
					<div class="inline-group">';
					  foreach ($gaterows as $grows) :
					  $html .= '
					  <label class="radio">
						<input name="gateway" data-gateway= "' . $grows->id . '" type="radio" value="' . $row->id . '">
						<i></i>' . $grows->displayname . '</label>';
					  endforeach;
					  $html .= ' </div>
				  </div>
				</div>
			  </div>
			  <div id="gdata"> </div>';
			  $json['message'] = $html;
			  print json_encode($json);
          endif;
      else :
		  $json['message'] = $json['message'] = Message::msgSingleError(Lang::$word->SYSTEM_ERR1, false);
		  print json_encode($json);
		  exit;
      endif;
  endif;

  /* Load Gateway */
  if (isset($_GET['loadGateway'])):
      if ($row = $db->first(Content::msTable, null, array("id" => Filter::$id))):
          $grows = $db->first(Content::gwTable, null, array("id" => intval($_GET['mid'])));
          $form_url = BASEPATH . "gateways/" . $grows->dir . "/form.tpl.php";

          $html = '';
          ob_start();
          include ($form_url);
          $html .= ob_get_contents();
          ob_end_clean();

          $json['message'] = $html;
          print json_encode($json);
      else:
          $json['message'] = $json['message'] = Message::msgSingleError(Lang::$word->SYSTEM_ERR1, false);
          print json_encode($json);
          exit;
      endif;
  endif;
  
  /* Proccess Coupon */
  if (isset($_GET['doCoupon']) and Filter::$id):
      $code = Validator::sanitize($_GET['code'], "string");
      if ($row = $db->pdoQuery("SELECT * FROM " . Content::dcTable . " WHERE FIND_IN_SET(" . Filter::$id . ", mid) AND code = ? AND active = ?", array($code, 1))->result()) :
          $row2 = $db->first(Content::msTable, null, array("id" => Filter::$id));
		  $db->delete(Items::xTable, array("uid" => $auth->uid));
		  $tax = Core::calculateTax();

          if ($row->type == "p"):
              $disc = Utility::formatNumber($row2->price / 100 * $row->discount);
              $gtotal = Utility::formatNumber($row2->price - $disc);
          else:
              $disc = Utility::formatNumber($row->discount);
              $gtotal = Utility::formatNumber($row2->price - $disc);
          endif;

		  $data = array(
		      'uid' => $auth->uid,
			  'mid' => $row2->id,
			  'cid' => $row->id,
			  'totaltax' => Utility::formatNumber($gtotal * $tax),
			  'coupon' => $disc,
			  'total' => $gtotal,
			  'originalprice' => $row2->price,
			  'totalprice' => Utility::formatNumber($tax * $gtotal + $gtotal)
			  );
			  
		  $db->insert(Items::xTable, $data);
			  
		  $json['type'] = "success";
		  $json['disc'] = "- " . Utility::formatMoney($disc, true);
		  $json['tax'] = Utility::formatMoney($data['totaltax'], true);
		  $json['gtotal'] = Utility::formatMoney($data['totalprice'], true);
		  print json_encode($json);
		  
      else:
	      $json['type'] = "error";
          print json_encode($json);
          exit;
      endif;
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
			  $data['user_id'] = $auth->uid;
	
			  $last_id = $db->insert(Items::gTable, $data)->getLastInsertId();
			  print '
			  <div class="wojo reveal"><img data-lazy="' . UPLOADURL . 'listings/pics' . Filter::$id . '/thumbs/' . $data['photo'] . '" alt="">
				<div class="overlay"></div>
				<div class="corner-overlay-content"><i class="icon long arrow right"></i></div>
				<div class="overlay-content">
				  <p data-editable="true" data-set=\'{"type": "gallery", "id": ' . $last_id . ',"key":"name", "path":""}\'></p>
				  <a class="delslide wojo corner label" data-set=\'{"title": "' . Lang::$word->GAL_DELETE . '>", "parent": ".reveal", "option": ' . Filter::$id . ', "id": ' . $last_id . ', "name": "", "path":"' . $data['photo'] . '"}\'><i class="icon negative delete link"></i></a> </div>
			  </div>';
		  endfor;
	  endif;
  endif;
  
  /* == Quick Edit== */
  if (isset($_POST['quickedit'])):
      $title = Validator::cleanOut($_POST['title']);
      $title = strip_tags($title);
      switch ($_POST['type']) {
          /* == Update Gallery Image == */
          case "gallery":
              if (empty($_POST['title'])):
                  print '--- EMPTY STRING ---';
                  exit;
              endif;
              if ($_POST['key'] == "name"):
                  $data['title'] = Validator::sanitize($_POST['title']);
              endif;
				  $db->update(Items::gTable, $data, array('id' => Filter::$id, 'user_id' => $auth->uid));
          break;
      }

      print $title;
  endif;
  
  /* == Print Invoice == */
  if (isset($_GET['doInvoice'])):
      if ($row = $user->getUserInvoice(Filter::$id, $auth->uid)):
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