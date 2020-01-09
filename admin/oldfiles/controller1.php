<?php
  /**
   * Index
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: index.php, v1.00 2014-10-05 10:12:05 gewa Exp $
   */
  define("_WOJO", true);
  require_once("init.php");
  
  if (!$auth->is_Admin())
      exit;
	  
  $delete = (isset($_POST['delete']))  ? $_POST['delete'] : null;
  $action = (isset($_POST['action']))  ? $_POST['action'] : null;
  $title = (isset($_POST['delete']) and isset($_POST['title'])) ? Validator::sanitize($_POST['title']) : null;
?>
<?php
  switch ($delete):
  /* == Delete Staff Member == */
  case "deleteStaff":
	if (Filter::$id == 1):
		$json['type'] = 'error';
		$json['title'] = Lang::$word->ERROR;
		$json['message'] = Lang::$word->M_DEL_MEMBER_ERR;
		print json_encode($json);
	else:
		$res = $db->delete(Users::aTable, array('id' => Filter::$id));
		Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->M_DEL_OK));
	endif;
  break;

  /* == Delete Member == */
  case "deleteMember":
    $thumb = $db->getValueById(Users::mTable, "avatar", Filter::$id);
	File::deleteFile(UPLOADS . "avatars/" . $thumb);
	$res = $db->delete(Users::mTable, array('id' => Filter::$id));
	$db->delete(Items::acTable, array('user_id' => Filter::$id));
	
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->M_DEL_OK));
  break;

  /* == Delete Ban Item == */
  case "deleteBanItem":
	$res = $db->delete(Content::blTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->BL_DELOK));
  break;

  /* == Delete F.A.Q. == */
  case "deleteFaq":
	$res = $db->delete(Content::faqTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->FAQ_DELFAQ_OK));
  break;

  /* == Delete Content Page == */
  case "deletePage":
	if ($db->select(Content::pgTable, array("id"), array('home_page' => 1, 'id' => Filter::$id), 'LIMIT 1')->result()):
		$json['type'] = 'error';
		$json['title'] = Lang::$word->ERROR;
		$json['message'] = Lang::$word->PAG_DELPAGE_H;
	else:
		$res = $db->delete(Content::pgTable, array('id' => Filter::$id));
		Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->PAG_DEL_OK));
	endif;
	print json_encode($json);
  break;

  /* == Delete News == */
  case "deleteNews":
	$res = $db->delete(Content::nwaTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->NWA_DEL_OK));
  break;
  
  /* == Delete Menu == */
  case "deleteMenu":
	$res = $db->delete(Content::muTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->MENU_DELMENU_OK));
  break;

  /* == Delete Category == */
  case "deleteCategory":
	$res = $db->delete(Content::ctTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->CAT_DEL_OK));
  break;
  
  /* == Delete Bodystyle == */
  case "deleteBodystyle":
  	$res = $db->delete(Content::bsTable, array('id' => Filter::$id));
  	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->BS_DEL_OK));
  	break;

  /* == Delete Coupon == */
  case "deleteCoupon":
	$res = $db->delete(Content::dcTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->DC_DEL_OK));
  break;
  
  /* == Delete Feature == */
  case "deleteFeature":
	$res = $db->delete(Content::fTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->FEAT_DEL_OK));
  break;

  /* == Delete Make == */
  case "deleteMake":
	$res = $db->delete(Content::mkTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->MAKE_DEL_OK));
  break;
  
  /* == Delete Makews == */
  case "deleteMakews":
  	$res = $db->delete(Content::mkwsTable, array('id' => Filter::$id));
  	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->MAKE_DEL_OK));
  	break;

  /* == Delete Model == */
  case "deleteModel":
	$res = $db->delete(Content::mdTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->MODL_DEL_OK));
  break;
  
  /* == Delete Modelws == */
  case "deleteModelws":
  	$res = $db->delete(Content::mdwsTable, array('id' => Filter::$id));
  	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->MODL_DEL_OK));
  	break;
  
  /* == Delete Price Discount == */
  case "deletePricingDiscount":
  	$res = $db->delete(wSpecials::wspTable, array('id' => Filter::$id));
  	Message::msgReply($res, 'success', str_replace("[NAME]", $title, "Pricing Discount [NAME] deleted successfully!"));
  	break;
  
  /* == Delete Condition == */
  case "deleteCondition":
	$res = $db->delete(Content::cdTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->COND_DEL_OK));
	$sdata['cond_list_alt'] = serialize($content->getConditions());
	$db->update(Core::sTable, $sdata, array("id" => 1));
  break;

  /* == Delete Fuel == */
  case "deleteFuel":
	$res = $db->delete(Content::fuTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->FUEL_DEL_OK));
	$sdata['fuel_list'] = serialize($content->getFuel());
	$db->update(Core::sTable, $sdata, array("id" => 1));
  break;
  
  /* == Delete Year == */
  case "deleteYear":
  	$res = $db->delete(Content::yTable, array('id' => Filter::$id));
  	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->YEAR_DEL_OK));
  	$sdata['year_list'] = serialize($content->getYear());
  	$db->update(Core::sTable, $sdata, array("id" => 1));
  	break;
  	

  /* == Delete Dealtype == */
  case "deleteDealtype":
  	$res = $db->delete(Content::dtTable, array('id' => Filter::$id));
  	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->DEAL_DEL_OK));
  	$sdata['dealtype_list'] = serialize($content->getDealtype());
  	$db->update(Core::sTable, $sdata, array("id" => 1));
  	break;
  		
  /* == Delete ZeroSingle == */
  	case "deleteLease":
  	  $res = $db->delete(Content::zsTable, array('id' => Filter::$id));
  	  Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->LEASE_DEL_OK));
  	  $sdata['zerosingle_list'] = serialize($content->getLease());
  	  $db->update(Core::sTable, $sdata, array("id" => 1));
  	  break;

  /* == Delete Transmission == */
  case "deleteTransmission":
	$res = $db->delete(Content::trTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->TRNS_DEL_OK));
	$sdata['trans_list'] = json_encode($content->getTransmissions());
	$db->update(Core::sTable, $sdata, array("id" => 1));
  break;

  /* == Delete Listing Package == */
  case "deletePackage":
	$res = $db->delete(Content::msTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->MSM_DEL_OK));
  break;

  /* == Delete Transaction == */
  case "deleteTransaction":
	$res = $db->delete(Content::txTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->TRX_DEL_OK));
  break;

  /* == Delete Location == */
  case "deleteLocation":
    $thumb = $db->getValueById(Content::lcTable, "logo", Filter::$id);
	File::deleteFile(UPLOADS . "showrooms/" . $thumb);
	$res = $db->delete(Content::lcTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->LOC_DEL_OK));
  break;

  /* == Delete Home Slide == */
  case "deleteHomeSlide":
    $thumb = $db->getValueById(Content::slTable, "thumb", Filter::$id);
	File::deleteFile(UPLOADS . "slider/" . $thumb);
	$res = $db->delete(Content::slTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->SLD_DEL_OK));
  break;

  /* == Delete Review == */
  case "deleteReview":
	$res = $db->delete(Content::rwTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->DC_DEL_OK));
  break;

  /* == Delete Country == */
  case "deleteCountry":
	$res = $db->delete(Content::cTable, array('id' => Filter::$id));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->CNT_DELOK));
  break;
  
  /* == Delete Database Backup == */
  case "deleteBackup":
	$res = File::deleteFile(BASEPATH . 'admin/backups/' . $title);
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->DBM_DEL_OK));
  break;

  /* == Delete Listing == */
  case "deleteListing":
    $thumb = $db->getValueById(Items::lTable, "thumb", Filter::$id);
	File::deleteFile(UPLOADS . "listings/" . $thumb);
	
	$res = $db->delete(Items::lTable, array('id' => Filter::$id));
	$db->delete(Items::liTable, array('listing_id' => Filter::$id));
	$db->delete(Items::gTable, array('listing_id' => Filter::$id));
	
	$pics = UPLOADS . "listings/pics" . Filter::$id;
	File::deleteRecrusive($pics, true);
	
	Items::doCalc();
				  
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->LST_DEL_OK));
  break;
  
  /* == Delete Webspecials == */
  case "deleteWebspecials":
  	$res = $db->delete(wSpecials::wsTable, array('id' => Filter::$id));
  	$db->delete(wSpecials::w_sTable, array('webspecials_id' => Filter::$id));
  	$db->delete(wSpecials::wspTable, array('special_id' => Filter::$id)); 
    Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->LST_DEL_OK));
  	break;
  
  /* == Delete Multiple Listing == */
  case "deleteMultiListings":
      if (empty($_POST['listid'])) :
		  $json['type'] = 'warning';
		  $json['title'] = Lang::$word->ALERT;
		  $json['message'] = Message::msgSingleAlert(Lang::$word->LST_DEL_ERR1, false);
       endif;
	   
      if (isset($_POST['listid'])):
          if (!empty($_POST['listid'])):
              foreach ($_POST['listid'] as $val):
                  $id = intval($val);
                  $thumb = $db->getValueById(Items::lTable, "thumb", $id);

				  File::deleteFile(UPLOADS . "listings/" . $thumb);
				  $db->delete(Items::lTable, array('id' => $id));
				  $db->delete(Items::liTable, array('listing_id' => $id));
				  $db->delete(Items::gTable, array('listing_id' => $id));

				  $pics = UPLOADS . "listings/pics$id";
				  File::deleteRecrusive($pics, true);

              endforeach;
          endif;
		  $json['type'] = 'success';
		  $json['title'] = Lang::$word->SUCCESS;
		  $json['message'] = Message::msgSingleOk(Lang::$word->LST_DEL_OK1, false);
      endif;
	  print json_encode($json);
  break;
  
  /* == Delete Multiple Webspeicals == */
  case "deleteMultiWebspecials" :
		if (empty ( $_POST ['listid'] )) :
			$json ['type'] = 'warning';
			$json ['title'] = Lang::$word->ALERT;
			$json ['message'] = Message::msgSingleAlert ( Lang::$word->LST_DEL_ERR1, false );
		
  	endif;
		
		if (isset ( $_POST ['listid'] )) :
			if (! empty ( $_POST ['listid'] )) :
				foreach ( $_POST ['listid'] as $val ) :
					$id = intval ( $val );
					$db->delete ( wSpecials::wsTable, array ('id' => $id) );
					$db->delete ( wSpecials::w_sTable, array ('webspecials_id' => $id) );
					$db->delete ( wSpecials::wspTable, array ('special_id' => $id) );
				endforeach;
			endif;
			$json ['type'] = 'success';
			$json ['title'] = Lang::$word->SUCCESS;
			$json ['message'] = Message::msgSingleOk ( Lang::$word->LST_DEL_OK1, false );
		endif;
		print json_encode ( $json );
		break;

  /* == Delete Image Slide == */
  case "deleteSlide":
	$res = $db->delete(Items::gTable, array('id' => Filter::$id));
	File::deleteFile(UPLOADS . 'listings/pics' . Validator::sanitize($_POST['option'], "int") . '/' . Validator::sanitize($_POST['path'], "string"));
	File::deleteFile(UPLOADS . 'listings/pics' . Validator::sanitize($_POST['option'], "int") . '/thumbs/' . Validator::sanitize($_POST['path'], "string"));
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->GAL_DELOK));
  break;

  /* == Delete Gallery Image == */
  case "deleteGalleryImage":
	$row = $db->first(Items::gTable, array("photo", "listing_id"), array("id" => Filter::$id));
	$res = $db->delete(Items::gTable, array('id' => Filter::$id));
	
	File::deleteFile(UPLOADS . 'listings/pics' . $row->listing_id . '/' . $row->photo);
	File::deleteFile(UPLOADS . 'listings/pics' . $row->listing_id . '/thumbs/' . $row->photo);
	Message::msgReply($res, 'success', str_replace("[NAME]", $title, Lang::$word->GAL_DELOK));
  break;
  
  endswitch;


  switch ($action):
	/* == Update Account == */
	case "updateAccount":
	  $user->updateAccount();
	break;

	/* == Process Staff Member == */
	case "processStaff":
	  $user->processStaff();
	break;

	/* == Process Member == */
	case "processMember":
	  $user->processMember();
	break;
	
	/* == Process Email == */
	case "processEmail":
	  $content->processEmail();
	break;

	/* == Process Country == */
	case "processCountry":
	  $content->processCountry();
	break;

	/* == Process Ban == */
	case "processBan":
	  $content->processBan();
	break;

	/* == Process F.A.Q. == */
	case "processFaq":
	  $content->processFaq();
	break;

	/* == Process Page == */
	case "processPage":
	  $content->processPage();
	break;

	/* == Process News == */
	case "processNews":
	  $content->processNews();
	break;
	
	/* == Process Slide == */
	case "processSlide":
	  $content->processSlide();
	break;
	
	/* == Process Coupon == */
	case "processCoupon":
	  $content->processCoupon();
	break;
	
	/* == Process Menu == */
	case "processMenu":
	  $content->processMenu();
	break;

	/* == Process Category == */
	case "processCategory":
	  $content->processCategory();
	break;
	
	/* == Process BodyStyle == */
	case "processBodyStyle":
		$content->processBodyStyle();
		break;

	/* == Process Feature == */
	case "processFeature":
	  $content->processFeature();
	break;

	/* == Process Make == */
	case "processMake":
	  $content->processMake();
	break;

	/* == Process Model == */
	case "processModel":
		$content->processModel();
	break;
	
	/* == Process Modelws == */
	case "processModelws":
		$content->processModelws();
		break;
  
	/* == Process Condition == */
	case "processCondition":
	  $content->processCondition();
	break;

	/* == Process Fuel == */
	case "processFuel":
	  $content->processFuel();
	break;
	
	/* == Process Year == */
	case "processYear":
		$content->processYear();
		break;
		
	/* == Process Dealtype == */
	case "processDealtype":
			$content->processDealtype();
			break;
			
	/* == Process Lease == */
	case "processLease":
		 $content->processLease();
		 break;

	/* == Process Transmission == */
	case "processTransmission":
	  $content->processTransmission();
	break;

	/* == Process Location == */
	case "processLocation":
	  $content->processLocation();
	break;
	
	/* == Process Gateway == */
	case "processGateway":
	  $content->processGateway();
	break;

	/* == Process Listing Package == */
	case "processPackage":
	  $content->processPackage();
	break;

	/* == Process Listing == */
	case "processListing":
	  $items->processListing();
	break;
	
	/* == Reject Listing == */
	case "rejectListing":
	  $items->rejectListing();
	break;
	
	/* == Process Country == */
	case "processConfig":
	  $core->processConfig();
	break;
	
	/* == Quick Message == */
	case "quickMessage":
	  $items->quickMessage();
	break;
	
   
	/* == Process Web Specials Price Discount == */
	case "processPriceDiscounts":
		$wSpecials->processPriceDiscounts();
		break;
	
	
  /* == Process Web Specials == */
	case "processWebspecials":
		$wSpecials->processWebspecials();
		break;
		
/* == Process Dublicate Web Specials == */
	case "processWebspecialsDubs":
		$wSpecials->processWebspecialsDubs();
		break;
		
		
 /* == Process Web Specials == */
	case "processWebspecialsTEST":
		$wSpecials->processWebspecialsTEST();
		break;
		
/* == Process Dublicate Web Specials == */
		case "processWebspecialsDubsTEST":
		$wSpecials->processWebspecialsDubsTEST();
		break;		
   	
	
	/* == Process Mailer File== */
	case "processMtemplate":
      $file = Validator::sanitize($_POST['filename']);
      $path = BASEPATH . "/mailer/" . Core::$language . "/" . $file;
      if (is_file($path)):
          if (isset($_POST['backup']) and $_POST['backup'] == 1):
              if ($data = file_get_contents($path)):
                  file_put_contents($path . '.bak', $data);
              endif;
          endif;
          if (!file_put_contents($path,  Validator::cleanOut($_POST['body']))):
              $json['type'] = 'error';
			  $json['title'] = Lang::$word->ERROR;
              $json['message'] = Lang::$word->ET_ERROR2;
          else:
              $json['type'] = 'success';
			  $json['title'] = Lang::$word->SUCCESS;
              $json['message'] = Lang::$word->ET_UPDATED;
          endif;

      else:
		  $json['type'] = 'error';
		  $json['title'] = Lang::$word->ERROR;
		  $json['message'] = Lang::$word->ET_ERROR2;
	  
      endif;
      print json_encode($json);
	break;
	
  endswitch;

  /* == Update Role Description == */
  if (isset($_POST['updateRoleDescription'])):
      if (Filter::$id and !empty($_POST['name']) and !empty($_POST['description'])):
		  $data = array(
				'name' => Validator::sanitize($_POST['name']), 
				'description' => Validator::sanitize($_POST['description'])
		  );
		  $db->update(Users::rTable, $data, array('id' => Filter::$id));
		  $json['title'] = Lang::$word->SUCCESS;
		  $json['type'] = 'success';
		  $json['message'] = Lang::$word->M_ROLEDESC_UPDT;
      else:
	      $json['title'] = Lang::$word->ERROR;
		  $json['type'] = 'error';
		  $json['message'] = Lang::$word->PROCCESS_ERR1;
	  endif;
	  print json_encode($json);
  endif;

  /* == Update Role Status == */
  if (isset($_GET['updateRoleStatus'])):
      if (Filter::$id):
		  $data = array(
				'active' => intval($_GET['active']), 
		  );
		  $db->update(Users::rpTable, $data, array('id' => Filter::$id));
	  endif;
  endif;
  
  /* == Update Review Description == */
  if (isset($_POST['updateReviewDescription'])):
      if (Filter::$id and !empty($_POST['content'])):
		  $data = array(
				'content' => Validator::sanitize($_POST['content'])
		  );
		  $db->update(Content::rwTable, $data, array('id' => Filter::$id));
		  $json['title'] = Lang::$word->SUCCESS;
		  $json['type'] = 'success';
		  $json['message'] = Lang::$word->SRW_UPDATED;
      else:
	      $json['title'] = Lang::$word->ERROR;
		  $json['type'] = 'error';
		  $json['message'] = Lang::$word->PROCCESS_ERR1;
	  endif;
	  print json_encode($json);
  endif;
?>