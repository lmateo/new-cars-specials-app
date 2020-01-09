<?php
/**
 * Quirk Web Specials Class
 *
 * @package Wojo Framework
 * @author Lorenzo Mateo
 * @copyright 2017
 * @version $Id: wspecials.class.php, v1.00 2017-08-25 9:12:05 gewa Exp $
 */



  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class wSpecials
  {

  	const wsTable = "webspecials";
  	const w_sTable = "web_specials";
  	const wspTable = "ws_pricing";
  	const wspTable3 = "ws_pricing3";
  	const wschTable = "webspecials_changes";
  	const acTable = "activity";
  	const gTable = "gallery";
  	const xTable = "cart";

      private static $db;


      /**
       * wSpecials::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          self::$db = Db::run();

      }

      /**
       * wSpecials::getWebspecials()
       *
       * @param string $from
       * @param bool $page
       * @param bool $status
       * @return
       */
      public function getWebspecials($from = false, $page, $status = true)
      {
      	$active = $status ? 'AND ws.active = 1' : 'AND ws.active = 0';
      	
      	
          if (isset($_GET['letter']) and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
              $enddate = date("Y-m-d");
              $letter = Validator::sanitize($_GET['letter'], 'default', 2);
              $fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
              if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                  $enddate = Validator::sanitize($_POST['enddate_submit']);
              }
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::w_sTable . "` AS ws  WHERE created_ws BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND nice_title_ws REGEXP '^" . $letter . "' $active");
              $where = "WHERE ws.created_ws BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND ws.nice_title_ws REGEXP '^" . $letter . "' $active";

          } elseif (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
              if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
                  $enddate = Validator::sanitize($_POST['enddate_submit']);
              }
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::w_sTable . "` AS ws WHERE created_ws BETWEEN '" . trim($fromdate) . "' AND '" . $enddate . " 23:59:59' $active");
              $where = "WHERE ws.created_ws BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' $active";

          } elseif (isset($_GET['letter'])) {
              $letter = Validator::sanitize($_GET['letter'], 'default', 2);
              $where = "WHERE ws.modelname_ws REGEXP '^" . $letter . "' $active";
              $counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::w_sTable . "` AS ws WHERE modelname_ws REGEXP '^" . $letter . "' $active LIMIT 1");
          
          }else {
          	 $active = $status ? 'WHERE ws.active = 1' : 'WHERE ws.active = 0 AND ';
			$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::w_sTable . "`AS ws $active LIMIT 1");
            $where = $active; 
          }

          if (isset($_GET['order'])) {
              list($sort, $order) = explode("/", $_GET['order']);
              $sort = Validator::sanitize($sort, "default", 10);
              $order = Validator::sanitize($order, "default", 4);
              if (in_array($sort, array(
                 "title_ws",
                 "new_used",
                  "specials_type",
                  "body_style",
                  "year"))) {
                  $ord = ($order == 'DESC') ? " DESC" : " ASC";
                  $sorting = $sort . $ord;
              } else {
                  $sorting = "  ws.active DESC, ws.year DESC, ws.id DESC";
              }
          } else {
              $sorting = " ws.active DESC, ws.year DESC, ws.id DESC";
          }

          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = App::get("Core")->perpage;
          $pager->paginate();

          $sql = "
		  SELECT 
			ws.*,
          	CONCAT(u.fname, ' ',u.lname) AS username	
      		
          FROM
			`" . self::w_sTable . "` AS ws
			LEFT JOIN `" . Users::aTable . "` AS u 
			  ON u.id = ws.modified_id
			$where 
			AND
			ws.specials_type IN ('SALES','COMMERCIAL')
		    
		  ORDER BY $sorting{$pager->limit};";
          $row = self::$db->pdoQuery($sql)->results();

          return ($row) ? $row : 0;
          /* */
      }

      /**
       * wSpecials::getWebspecialsPreview()
       * 
       * @return
       */
      public function getWebspecialsPreview()
      {
          
          $sql = "
		  SELECT 
			ws.*,
          	lc.*,
			ws.id AS id
          	
		  FROM
			`" . self::w_sTable . "` AS ws
			LEFT JOIN `" . Content::lcTable . "` AS lc 
			  ON ws.store_id = lc.store_id 
		  WHERE ws.webspecials_id = ?;";

          $row = self::$db->pdoQuery($sql, array(Filter::$id))->result();
          return ($row) ? $row : 0;
      }
      
      /**
       * wSpecials::getWebspecialsDate()
       *
       * @return
       */
      public  static function getWebspecialsDate()
      {
      	
      	$currentdate = date("m-d-Y");
      	$where = "WHERE DATE(modified_ws) = CURDATE()";
      	$sql = "
		  SELECT
			ws.*,
          	lc.*,
      		ws.update_flag AS id	
      			
		  FROM
			`" . self::w_sTable . "` AS ws
			LEFT JOIN `" . Content::lcTable . "` AS lc
			  ON ws.store_id = lc.store_id
		  $where 
		  AND
		  ws.update_flag = 1
		  AND
		  ws.active = 1
		  AND
		  ws.specials_type IN ('SALES','COMMERCIAL')
      	  ";
  
      	$row = self::$db->pdoQuery($sql)->results();
      	return ($row) ? $row : 0;
      }
      
     
      
     /**
       * wSpecials::getWebspecialsbystore()
       *
       * @param string $from
       * @param bool $page
       * @param bool $status
       * @return
       */
      public function getWebspecialsbystore($from = false, $page, $status = true, $id = false )
      {
      	$active = $status ? 'AND ws.active = 1' : 'AND ws.active = 0';
      	$sid = ($id) ? $id : Filter::$id;
      	//var_dump($sid);
      	 
      	if (isset($_GET['letter']) and (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '')) {
      		$enddate = date("Y-m-d");
      		$letter = Validator::sanitize($_GET['letter'], 'default', 2);
      		$fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
      		if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
      			$enddate = Validator::sanitize($_POST['enddate_submit']);
      		}
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::w_sTable . "` AS ws WHERE created_ws BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND nice_title_ws REGEXP '^" . $letter . "'AND store_id = '" . $sid . "' $active");
      		$where = "WHERE ws.created_ws BETWEEN '" . $fromdate . "' AND '" . $enddate . " 23:59:59' AND ws.nice_title_ws REGEXP '^" . $letter . "' AND ws.store_id = '" . $sid . "' $active";
      
      	} elseif (isset($_POST['fromdate_submit']) && $_POST['fromdate_submit'] <> "" || isset($from) && $from != '') {
      		$enddate = date("Y-m-d");
      		$fromdate = (empty($from)) ? Validator::sanitize($_POST['fromdate_submit']) : $from;
      		if (isset($_POST['enddate_submit']) && $_POST['enddate_submit'] <> "") {
      			$enddate = Validator::sanitize($_POST['enddate_submit']);
      		}
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::w_sTable . "` AS ws WHERE created_ws BETWEEN '" . trim($fromdate) . "' AND '" . $enddate . " 23:59:59' AND store_id  = '" . $sid . "' $active");
      		$where = "WHERE ws.created_ws BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND ws.store_id  = '" . $sid . "' $active";
      
      	} elseif (isset($_GET['letter'])) {
      		$letter = Validator::sanitize($_GET['letter'], 'default', 2);
      		$where = "WHERE ws.modelname_ws REGEXP '^" . $letter . "' AND ws.store_id = '" . $sid . "' $active";
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::w_sTable . "` AS ws WHERE modelname_ws REGEXP '^" . $letter . "' AND store_id  = '" . $sid . "' $active LIMIT 1");
      
      	} elseif (isset($_POST['store_letter'])) {
      		$store_letter = Validator::sanitize($_POST['store_letter'], 'default', 2);
      		$where = "WHERE ws.store_letter REGEXP '^" . $store_letter . "' $active";
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::w_sTable . "` AS ws WHERE store_letter REGEXP '^" . $store_letter . "' $active LIMIT 1");
      
      	}else {
      		
      		$active = $status ? ' AND ws.active = 1' : 'ws.active = 0';
      		$where = "WHERE ws.store_id = '" . $sid . "' $active";
      		$counter = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::w_sTable . "` AS ws WHERE store_id = '" . $sid . "' $active LIMIT 1");
      				
      	}
      
      	if (isset($_GET['order'])) {
      		list($sort, $order) = explode("/", $_GET['order']);
      		$sort = Validator::sanitize($sort, "default", 10);
      		$order = Validator::sanitize($order, "default", 4);
      		if (in_array($sort, array(
      				"title_ws",
      				"new_used",
      				"specials_type",
      				"body_style",
      				"year"))) {
      				$ord = ($order == 'DESC') ? " DESC" : " ASC";
      				$sorting = $sort . $ord;
      		} else {
      			$sorting = "  ws.ordering ASC, ws.active DESC, ws.year DESC, ws.id DESC";
      		}
      	} else {
      		$sorting = " ws.ordering ASC, ws.active DESC, ws.year DESC, ws.id DESC";
      	}
      
      	$pager = Paginator::instance();
      	$pager->items_total = $counter;
      	$pager->default_ipp = App::get("Core")->perpage;
      	//$pager->path = Url::adminUrl("webspecials", "dealership", false, "?id=$sid");
      	$pager->paginate();  
      
      	$sql = "
		  SELECT
			ws.*,
      		CONCAT( u.fname, ' ',u.lname) AS username	
          FROM
			`" . self::w_sTable . "` AS ws
      		LEFT JOIN `" . Users::aTable . "` AS u 
			  ON u.id = ws.modified_id	
      			
      			 
      			$where
      			AND
			    ws.specials_type IN ('SALES','COMMERCIAL')
      		
      			
      			ORDER BY $sorting{$pager->limit};";
      	
      			$row = self::$db->pdoQuery($sql)->results();
      			
      			return ($row) ? $row : 0;
      
      }

      /**
       * wSpecials::getWebspecialsById()
       * 
       * @return
       */
      public function getWebspecialsById()
      {

      
      	
      	$sql = "
		  SELECT 
			ws.*,
      		ws.id AS id,
			CONCAT(CASE ws.vcondition WHEN '1' THEN 'NEW' ELSE 'USED' END , ' ', y.name,' ', mk.name,' ', md.name, ' ', ws.trim_level) as title_ws
		  FROM
          	`" . self::wsTable . "` AS ws
          	LEFT JOIN `" . Content::mkwsTable . "` AS mk 
          		ON mk.id = ws.make_id 
			LEFT JOIN `" . Content::mdwsTable . "` AS md 
			  ON md.id = ws.model_id 
			LEFT JOIN `" . Content::yTable . "` AS y
			  ON y.id = ws.year_id
				
			 		
		WHERE ws.id = ?;";

          $row = self::$db->pdoQuery($sql, array(Filter::$id))->result();
          return ($row) ? $row : 0;
      }
      
      /**
       * wSpecials::getWSBYID()
       *
       * @return
       */
      public function getWSBYID()
      {
        
      	$where = "WHERE webspecials_id = " . Filter::$id;
      	$sql = "
		  SELECT
			 w_s.*,
      		 lc.logo	
		  FROM
          	`" . self::w_sTable . "` AS w_s
          	LEFT JOIN `" . Content::lcTable . "` AS lc
			  ON lc.letter = w_s.store_letter
          $where";
      
      	$row = self::$db->pdoQuery($sql)->result();
      	return ($row) ? $row : 0;
      }
      
      
      /**
       * wSpecials::getWSBYSTORE()
       *
       * @return
       */
      public function getWSBYSTORE($storeid)
      {
      
      	$where = "WHERE w_s.store_id = $storeid";
      	$sql = "
		  SELECT
			 w_s.*,
      		 lc.logo
		  FROM
          	`" . self::w_sTable . "` AS w_s
          	LEFT JOIN `" . Content::lcTable . "` AS lc
                	 ON lc.store_id = w_s.store_id
                	$where";
      
                	$row = self::$db->pdoQuery($sql)->results();
                	return ($row) ? $row : 0;
      }
      
     

      /**
       * wSpecials::processWebspecials()
       * 
       * @return
       */
      public function processWebspecials()
      {

	          $validate = Validator::instance();
	          $validate->addSource($_POST);
	          $validate->addRule('vcondition','numeric', true, 1, 11, Lang::$word->WSP_COND);
	          $validate->addRule('year_id','numeric',  true, 1, 4, Lang::$word->WSP_YEAR);
	          $validate->addRule('make_id','numeric', true, 1, 11, Lang::$word->WSP_MAKE);
	          $validate->addRule('model_id','numeric', true, 1, 11, Lang::$word->WSP_MODEL);
	          $validate->addRule('deal_type','numeric', false);
	          $validate->addRule('category','numeric', false);
	          $validate->addRule('trim_level','string', false);
	          $validate->addRule('tagline','string', false);
	          $validate->addRule('stock_id','string', false);
	          $validate->addRule('vin_number','string', false);
	          $validate->addRule('msrp','numeric', false);
	          $validate->addRule('buy_price','numeric', false);
	          $validate->addRule('save_up_to_amount','string', false);
	          $validate->addRule('available_apr','string', false);
	          $validate->addRule('apr_text','string', false);
	          $validate->addRule('finance_for_price','numeric', false);
	          $validate->addRule('finance_for_term','string', false);
	          $validate->addRule('finance_for_payment_calcu_url','string', false);
	          $validate->addRule('finance_for_interest_rate','string', false);
	          $validate->addRule('finance_for_down_payment','numeric', false);
	          $validate->addRule('finance_for_trade_in_value','numeric', false);
	          $validate->addRule('lease_extras','numeric', false);
	          $validate->addRule('lease_price','numeric', false);
	          $validate->addRule('lease_term','string', false);
	          $validate->addRule('single_zero_lease','numeric', false);
	          $validate->addRule('single_lease_price','numeric', false);
	          $validate->addRule('single_lease_term','string', false);
	          $validate->addRule('single_lease_miles','numeric', false);
	          $validate->addRule('zero_down_lease_price','numeric', false);
	          $validate->addRule('zero_down_lease_term','string', false);
	          $validate->addRule('vehicle_image','string', false); 
	          $validate->addRule('alt_link_url','string', false);
	          $validate->addRule('custom_marquee_text','string', true, 0, 110, Lang::$word->WSP_CUSTOM_MARQUEE_TEXT);
	          $validate->addRule('brand_logo_url','string', false);
	          
     		  $validate->run();

          if (empty(Message::$msgs)) {
          	  $mid = self::doTitle($validate->safe->model_id);
              $wsrow = self::$db->select(self::wsTable, "null", array('id' => Filter::$id))->result();
              $single_zero_lease = $validate->safe->single_zero_lease;
              $data = array(
              		//'slug' => (empty($_POST['slug'])) ? $validate->safe->year . '-' . $mid : Url::doSeo($validate->safe->slug),
              		'nice_title' => ucwords(str_replace("-", " ", $mid)),
              		'vcondition' => $validate->safe->vcondition,
              		'year_id' => $validate->safe->year_id,
              		'make_id' => $validate->safe->make_id,
              		'model_id' => $validate->safe->model_id,
              		'deal_type' => $validate->safe->deal_type,
              		'category' => $validate->safe->category,
              		'trim_level' => $validate->safe->trim_level,
              		'tagline' => $validate->safe->tagline,
              		'stock_id' => $validate->safe->stock_id,
              		'vin_number' => $validate->safe->vin_number,
              		'msrp' => $validate->safe->msrp,
              		'buy_price' => $validate->safe->buy_price,
              		'save_up_to_amount' => $validate->safe->save_up_to_amount,
              		'available_apr' => $validate->safe->available_apr,
              		'apr_text' => $validate->safe->apr_text,
              		'finance_for_price' => $single_zero_lease == 6 ? $validate->safe->finance_for_price : 0,
              		'finance_for_term' => $validate->safe->finance_for_term,
              		'finance_for_payment_calcu_url' => $validate->safe->finance_for_payment_calcu_url,
              		'finance_for_interest_rate' => $single_zero_lease == 6 ? $validate->safe->finance_for_interest_rate : 0,
              		'finance_for_down_payment' => $single_zero_lease == 6 ? $validate->safe->finance_for_down_payment : 0,
              		'finance_for_trade_in_value' => $validate->safe->finance_for_trade_in_value,
              		'lease_extras' => $single_zero_lease == 1 ? $validate->safe->lease_extras : 0,
              		'lease_price' => $single_zero_lease == 1 ? $validate->safe->lease_price : 0,
              		'lease_term' => $validate->safe->lease_term,
              		'single_zero_lease' => $validate->safe->single_zero_lease,
              		'single_lease_price' => $single_zero_lease == 2 ? $validate->safe->single_lease_price : 0,
              		'single_lease_term' => $validate->safe->single_lease_term,
              		'single_lease_miles' => $validate->safe->single_lease_miles,
              		'zero_down_lease_price' => $validate->safe->zero_down_lease_price,
              		'zero_down_lease_term' => $validate->safe->zero_down_lease_term,
              		'vehicle_image' => $validate->safe->vehicle_image,
              		'alt_link_url' => $validate->safe->alt_link_url,
              		'disclaimer_text' =>Utility::clean_specialchar($_POST['disclaimer_text']),
              		'custom_marquee_text' => $validate->safe->custom_marquee_text,
              		'brand_logo_url' => $validate->safe->brand_logo_url,
              		'active' => isset($_POST['active']) ? $_POST['active'] : 0
              				
              );
              
                   
              if (!Filter::$id) {
              	
              	$data['location'] = $_POST['location'];
              	$data['specials_type'] = $_POST['specials_type'];
              	$data['store_letter'] = strtoupper(self::$db->getValueById(Content::lcTable, "letter", $_POST['location']));
              	$data['created_ws'] = Db::toDate();
              	$data['created_id'] = App::get('Auth')->uid;
              	$data['idx'] = Utility::randNumbers();
              
              } else {
              	
              	$data['location'] = $wsrow->location;
              	$data['specials_type'] = $wsrow->specials_type;
              	$data['store_letter'] = strtoupper($wsrow->store_letter);
              	$data['modified_ws'] = Db::toDate();
              	$data['modified_id'] = App::get('Auth')->uid;
              
              }
			     
			
    
              (Filter::$id) ? self::$db->update(self::wsTable, $data, array('id' => Filter::$id)) : $last_id = self::$db->insert(self::wsTable, $data)->getLastInsertId();
              $storeidws = self::$db->getValueById(Content::lcTable, "store_id", $data['location']);
              $dealershipAdminUrl = Url::adminUrl("webspecials","dealership", false, "?id=$storeidws");
              $storenamews = strtoupper(self::$db->getValueById(Content::lcTable, "name", $data['location']));
              $webspecialslinkUpdate = "<br/> <a href= $dealershipAdminUrl>BACK TO $storenamews</a>";
              $webspecialslinkADDED = "<br/> <a href= $dealershipAdminUrl>GO TO $storenamews</a>";
              $message = (Filter::$id) ? Lang::$word->WSP_UPDATED .$webspecialslinkUpdate : Lang::$word->WSP_ADDED .$webspecialslinkADDED;
              Message::msgReply(self::$db->affected(), 'success', $message); 

               // Add to web_specials tbl
	          $makename_ws = self::$db->getValueById(Content::mkwsTable, "name", $data['make_id']);
			  $store_letter = self::$db->getValueById(Content::lcTable, "letter", $data['location']);
			  $store_id = self::$db->getValueById(Content::lcTable, "store_id", $data['location']);
			  $storename_ws = strtoupper(self::$db->getValueById(Content::lcTable, "name", $data['location']));
			  $bodystyle_name = self::$db->getValueById(Content::bsTable, "name", $data['category']);
			  $specials_type_name = self::$db->getValueById(Content::stTable, "name", $data['specials_type']);
			  $dealtype = '';
			  $deal_type_name = isset($data['deal_type'])&& empty($data['deal_type']) ? $dealtype : self::$db->getValueById(Content::dtTable, "name", $data['deal_type']);
			  $singleLease = '';
			  $single_zero_lease_name = isset($data['single_zero_lease']) && empty($data['single_zero_lease']) ? $singleLease : self::$db->getValueById(Content::zsTable, "name", $data['single_zero_lease']);
			  $year = self::$db->getValueById(Content::yTable, "name", $data['year_id']);
			  
			   
              $idata = array(
	                'makename_ws' => $makename_ws,
					'modelname_ws' => self::$db->getValueById(Content::mdwsTable, "name", $data['model_id']),
	                'store_letter' => $store_letter,
	              	'store_id' => $store_id,
	              	'storename_ws' => $storename_ws,
					'body_style_code' => $bodystyle_name,
	              	'nice_title_ws' => ucwords(str_replace("-", " ", $mid)),
              		'specials_type' => $specials_type_name,
              		'new_used_ws' => self::$db->getValueById(Content::cdTable, "name", $data['vcondition']),
              		'year' => $year,
              		'make_id' => $validate->safe->make_id,
              		'model_id' => $validate->safe->model_id,
              		'deal_type' => $deal_type_name,
              		'trim_level' => $validate->safe->trim_level,
              		'tagline' => $validate->safe->tagline,
              		'stock_number' => $validate->safe->stock_id,
              		'vin_number' => $validate->safe->vin_number,
              		'msrp' => $validate->safe->msrp,
              		'buy_price' => $validate->safe->buy_price,
              		'save_up_to_amount' => $validate->safe->save_up_to_amount,
              		'available_apr' => $validate->safe->available_apr,
              		'apr_text' => $validate->safe->apr_text,
              		'finance_for_price' => $single_zero_lease_name == "Finance For" ? $validate->safe->finance_for_price : 0,
              		'finance_for_term' => $validate->safe->finance_for_term,
              		'finance_for_payment_calcu_url' => $validate->safe->finance_for_payment_calcu_url,
              		'finance_for_interest_rate' => $single_zero_lease_name == "Finance For" ? $validate->safe->finance_for_interest_rate : 0,
              		'finance_for_down_payment' => $single_zero_lease_name == "Finance For" ? $validate->safe->finance_for_down_payment : 0,
              		'finance_for_trade_in_value' => $single_zero_lease_name == "Finance For" ? $validate->safe->finance_for_trade_in_value : 0,
              		'lease_extras' => $single_zero_lease_name == "Lease" ? $validate->safe->lease_extras : 0,
              		'lease_price' => $single_zero_lease_name == "Lease" ? $validate->safe->lease_price : 0,
              		'lease_term' => $validate->safe->lease_term,
              		'single_zero_lease' => $single_zero_lease_name,
              		'single_lease_price' => $single_zero_lease_name == "Single Pay Lease" ? $validate->safe->single_lease_price : 0,
              		'single_lease_term' => $validate->safe->single_lease_term,
              		'single_lease_miles' => $validate->safe->single_lease_miles,
              		'zero_down_lease_price' => $validate->safe->zero_down_lease_price,
              		'zero_down_lease_term' => $validate->safe->zero_down_lease_term,
              		'vehicle_image' => $validate->safe->vehicle_image,
              		'alt_link_url' => $validate->safe->alt_link_url,
              		'disclaimer_text' => Utility::clean_specialchar($_POST['disclaimer_text']),
              		'custom_marquee_text' => $validate->safe->custom_marquee_text,
              		'brand_logo_url' => $validate->safe->brand_logo_url,
              		'active' => isset($_POST['active']) ? $_POST['active'] : 0,
              		'slug_ws' => $year. '-' . $mid);
              
             
              	$idata['title_ws'] = self::$db->getValueById(Content::cdTable, "name", $data['vcondition']).' '.$year.' '. $makename_ws.' '. self::$db->getValueById(Content::mdwsTable, "name", $data['model_id']).' '. $validate->safe->trim_level.' '. $bodystyle_name;
             
                 if (!Filter::$id) {
                 	$idata['webspecials_id'] = $last_id;
                 	$idata['created_ws'] = Db::toDate();
                 	$idata['created_id'] = App::get('Auth')->uid;
                 	 
                 } else {
                 	$idata['update_flag'] = 1;
                 	$idata['modified_ws'] = Db::toDate();
                 	$idata['modified_id'] = App::get('Auth')->uid;
                 	 
                 }
                 
                (Filter::$id) ? self::$db->update(self::w_sTable, $idata, array('webspecials_id' => Filter::$id ? Filter::$id : $last_id)) : self::$db->insert(self::w_sTable, $idata);
			 
              
          } else {
              Message::msgSingleStatus();
          }

      }
      
      
      
      
      
      /**
       * wSpecials::processWebspecialsDubs()
       *
       * @return
       */
      public function processWebspecialsDubs()
      {
        
      	$wbsp = self::$db->select(self::wsTable, "null", array('id' => $_POST['id']))->result();
      	 
      	if (empty(Message::$msgs)) {
      		$mid = self::doTitle($wbsp->model_id);
      		$html = '';
      		$data = array(
      				//'slug' => (empty($_POST['slug'])) ? $validate->safe->year . '-' . $mid : Url::doSeo($validate->safe->slug),
      				'nice_title' => ucwords(str_replace("-", " ", $mid)),
      				'location' => $wbsp->location,
      				'store_letter' => strtoupper(self::$db->getValueById(Content::lcTable, "letter", $wbsp->location)),
      				'specials_type' => $wbsp->specials_type,
      				'vcondition' => $wbsp->vcondition,
      				'year_id' => $wbsp->year_id,
      				'make_id' => $wbsp->make_id,
      				'model_id' => $wbsp->model_id,
      				'deal_type' => $wbsp->deal_type,
      				'category' => $wbsp->category,
      				'trim_level' => $wbsp->trim_level,
      				'tagline' => $wbsp->tagline,
      				'stock_id' => $wbsp->stock_id,
      				'vin_number' => $wbsp->vin_number,
      				'msrp' => $wbsp->msrp,
      				'buy_price' => $wbsp->buy_price,
      				'save_up_to_amount' => $wbsp->save_up_to_amount,
      				'available_apr' => $wbsp->available_apr,
      				'apr_text' => $wbsp->apr_text,
      				'finance_for_price' => $wbsp->finance_for_price,
      				'finance_for_term' => $wbsp->finance_for_term,
      				'finance_for_payment_calcu_url' => $wbsp->finance_for_payment_calcu_url,
      				'finance_for_interest_rate' => $wbsp->finance_for_interest_rate,
      				'finance_for_down_payment' => $wbsp->finance_for_down_payment,
      				'finance_for_trade_in_value' => $wbsp->finance_for_trade_in_value,
      				'lease_extras' => $wbsp->lease_extras,
      				'lease_price' => $wbsp->lease_price,
      				'lease_term' => $wbsp->lease_term,
      				'single_zero_lease' => $wbsp->single_zero_lease,
      				'single_lease_price' => $wbsp->single_lease_price,
      				'single_lease_term' => $wbsp->single_lease_term,
      				'single_lease_miles' => $wbsp->single_lease_miles,
      				'zero_down_lease_price' => $wbsp->zero_down_lease_price,
      				'zero_down_lease_term' => $wbsp->zero_down_lease_term,
      				'vehicle_image' => $wbsp->vehicle_image,
      				'alt_link_url' => $wbsp->alt_link_url,
      				'disclaimer_text' => $wbsp->disclaimer_text,
      				'custom_marquee_text' => $wbsp->custom_marquee_text,
      				'brand_logo_url' => $wbsp->brand_logo_url,
      				'active' => $wbsp->active
      				
      		);
      		 
      		 
      			$data['created_ws'] = Db::toDate();
      			$data['created_id'] = App::get('Auth')->uid;
      			$data['idx'] = Utility::randNumbers();
      			
      			
      			
      
            $nicetitle = $data['nice_title'];
      		$last_id = self::$db->insert(self::wsTable, $data)->getLastInsertId();
      		$json = array(
      				'type' => 'success',
      				'title' => Lang::$word->SUCCESS,
      				'data' => $html,
      				'message' => "$nicetitle Web Special Dublicated Successfully!!"
      		);
      		
      		print json_encode($json);
      		
      		
      		
      
      		// Add to web_specials tbl
      		$makename_ws = self::$db->getValueById(Content::mkwsTable, "name", $data['make_id']);
      		$store_letter = self::$db->getValueById(Content::lcTable, "letter", $data['location']);
      		$store_id = self::$db->getValueById(Content::lcTable, "store_id", $data['location']);
      		$storename_ws = strtoupper(self::$db->getValueById(Content::lcTable, "name", $data['location']));
      		$bodystyle_name = self::$db->getValueById(Content::bsTable, "name", $data['category']);
      		$dealtype = '';
      		$deal_type_name = isset($data['deal_type']) && empty($data['deal_type'])? $dealtype : self::$db->getValueById(Content::dtTable, "name", $data['deal_type']);
      		$specials_type_name = self::$db->getValueById(Content::stTable, "name", $data['specials_type']);
      		$singleLease = '';
      		$single_zero_lease_name = isset($data['single_zero_lease']) && empty($data['single_zero_lease']) ? $singleLease : self::$db->getValueById(Content::zsTable, "name", $data['single_zero_lease']);
      		$year = self::$db->getValueById(Content::yTable, "name", $data['year_id']);
      		
      
      		$idata = array(
      				'makename_ws' => $makename_ws,
      				'modelname_ws' => self::$db->getValueById(Content::mdwsTable, "name", $data['model_id']),
      				'store_letter' => $store_letter,
      				'store_id' => $store_id,
      				'storename_ws' => $storename_ws,
      				'body_style_code' => $bodystyle_name,
      				'nice_title_ws' => ucwords(str_replace("-", " ", $mid)),
      				'specials_type' => $specials_type_name,
      				'new_used_ws' => self::$db->getValueById(Content::cdTable, "name", $data['vcondition']),
      				'year' => $year,
      				'make_id' => $wbsp->make_id,
      				'model_id' => $wbsp->model_id,
      				'deal_type' => $deal_type_name,
      				'trim_level' => $wbsp->trim_level,
      				'tagline' => $wbsp->tagline,
      				'stock_number' => $wbsp->stock_id,
      				'vin_number' => $wbsp->vin_number,
      				'msrp' => $wbsp->msrp,
      				'buy_price' => $wbsp->buy_price,
      				'save_up_to_amount' => $wbsp->save_up_to_amount,
      				'available_apr' => $wbsp->available_apr,
      				'apr_text' => $wbsp->apr_text,
      				'finance_for_price' => $wbsp->finance_for_price,
      				'finance_for_term' => $wbsp->finance_for_term,
      				'finance_for_payment_calcu_url' => $wbsp->finance_for_payment_calcu_url,
      				'finance_for_interest_rate' => $wbsp->finance_for_interest_rate,
      				'finance_for_down_payment' => $wbsp->finance_for_down_payment,
      				'finance_for_trade_in_value' => $wbsp->finance_for_trade_in_value,
      				'lease_extras' => $wbsp->lease_extras,
      				'lease_price' => $wbsp->lease_price,
      				'lease_term' => $wbsp->lease_term,
      				'single_zero_lease' => $single_zero_lease_name,
      				'single_lease_price' => $wbsp->single_lease_price,
      				'single_lease_term' => $wbsp->single_lease_term,
      				'single_lease_miles' => $wbsp->single_lease_miles,
      				'zero_down_lease_price' => $wbsp->zero_down_lease_price,
      				'zero_down_lease_term' => $wbsp->zero_down_lease_term,
      				'vehicle_image' => $wbsp->vehicle_image,
      				'alt_link_url' => $wbsp->alt_link_url,
      				'disclaimer_text' => $wbsp->disclaimer_text,
      				'custom_marquee_text' => $wbsp->custom_marquee_text,
      				'brand_logo_url' => $wbsp->brand_logo_url,
      				'active' => $wbsp->active,
      				'slug_ws' => $year. '-' . $mid
      				
      		);
      
      
      		$idata['title_ws'] = self::$db->getValueById(Content::cdTable, "name", $data['vcondition']).' '.$year.' '. $makename_ws.' '. self::$db->getValueById(Content::mdwsTable, "name", $data['model_id']).' '. $wbsp->trim_level.' '. $bodystyle_name;
      		 
      		    $idata['webspecials_id'] = $last_id;
      			$idata['created_ws'] = Db::toDate();
      			$idata['created_id'] = App::get('Auth')->uid;
      			 
      	    self::$db->insert(self::w_sTable, $idata);
     	
      	} else {
      		$json['type'] = 'error';
      		$json['title'] = Lang::$word->ERROR;
      		$json['message'] = $err;
      		print json_encode($json); 
      		/*Message::msgSingleStatus();*/
      	}
      
      }
      
      /**
       * wSpecials::processWebspecialsUpdate_autosave()
       *
       * @return
       */
      public function processWebspecialsUpdate_autosave()
      {
      	
      	$validate = Validator::instance();
      	$validate->addSource($_POST);
      	$validate->addRule('vcondition','numeric', true, 1, 11, Lang::$word->WSP_COND);
      	$validate->addRule('year_id','numeric',  true, 1, 4, Lang::$word->WSP_YEAR);
      	$validate->addRule('make_id','numeric', true, 1, 11, Lang::$word->WSP_MAKE);
      	$validate->addRule('model_id','numeric', true, 1, 11, Lang::$word->WSP_MODEL);
      	$validate->addRule('deal_type','numeric', false);
      	$validate->addRule('category','numeric', false);
      	$validate->addRule('trim_level','string', false);
      	$validate->addRule('tagline','string', false);
      	$validate->addRule('stock_id','string', false);
      	$validate->addRule('vin_number','string', false);
      	$validate->addRule('msrp','numeric', false);
      	$validate->addRule('buy_price','numeric', false);
      	$validate->addRule('save_up_to_amount','string', false);
      	$validate->addRule('available_apr','string', false);
      	$validate->addRule('apr_text','string', false);
      	$validate->addRule('finance_for_price','numeric', false);
      	$validate->addRule('finance_for_term','string', false);
      	$validate->addRule('finance_for_payment_calcu_url','string', false);
      	$validate->addRule('finance_for_interest_rate','string', false);
      	$validate->addRule('finance_for_down_payment','numeric', false);
      	$validate->addRule('finance_for_trade_in_value','numeric', false);
      	$validate->addRule('lease_extras','numeric', false);
      	$validate->addRule('lease_price','numeric', false);
      	$validate->addRule('lease_term','string', false);
      	$validate->addRule('single_zero_lease','numeric', false);
      	$validate->addRule('single_lease_price','numeric', false);
      	$validate->addRule('single_lease_term','string', false);
      	$validate->addRule('single_lease_miles','numeric', false);
      	$validate->addRule('zero_down_lease_price','numeric', false);
      	$validate->addRule('zero_down_lease_term','string', false);
      	$validate->addRule('vehicle_image','string', false);
      	$validate->addRule('alt_link_url','string', false);
      	$validate->addRule('custom_marquee_text','string', true, 0, 110, Lang::$word->WSP_CUSTOM_MARQUEE_TEXT);
      	$validate->addRule('brand_logo_url','string', false);
      	 
      	$validate->run();
      	
      	if (empty(Message::$msgs)) {
      		$mid = self::doTitle($validate->safe->model_id);
      		$wsrow = self::$db->select(self::wsTable, "null", array('id' => Filter::$id))->result();
      		$single_zero_lease = $validate->safe->single_zero_lease;
      		$data = array(
      				//'slug' => (empty($_POST['slug'])) ? $validate->safe->year . '-' . $mid : Url::doSeo($validate->safe->slug),
      				'nice_title' => ucwords(str_replace("-", " ", $mid)),
      				'vcondition' => $validate->safe->vcondition,
      				'year_id' => $validate->safe->year_id,
      				'make_id' => $validate->safe->make_id,
      				'model_id' => $validate->safe->model_id,
      				'deal_type' => $validate->safe->deal_type,
      				'category' => $validate->safe->category,
      				'trim_level' => $validate->safe->trim_level,
      				'tagline' => $validate->safe->tagline,
      				'stock_id' => $validate->safe->stock_id,
      				'vin_number' => $validate->safe->vin_number,
      				'msrp' => $validate->safe->msrp,
      				'buy_price' => $validate->safe->buy_price,
      				'save_up_to_amount' => $validate->safe->save_up_to_amount,
      				'available_apr' => $validate->safe->available_apr,
      				'apr_text' => $validate->safe->apr_text,
      				'finance_for_price' => $single_zero_lease == 6 ? $validate->safe->finance_for_price : 0,
      				'finance_for_term' => $validate->safe->finance_for_term,
      				'finance_for_payment_calcu_url' => $validate->safe->finance_for_payment_calcu_url,
      				'finance_for_interest_rate' => $single_zero_lease == 6 ? $validate->safe->finance_for_interest_rate : 0,
      				'finance_for_down_payment' => $single_zero_lease == 6 ? $validate->safe->finance_for_down_payment : 0,
      				'finance_for_trade_in_value' => $validate->safe->finance_for_trade_in_value,
      				'lease_extras' => $single_zero_lease == 1 ? $validate->safe->lease_extras : 0,
      				'lease_price' => $single_zero_lease == 1 ? $validate->safe->lease_price : 0,
      				'lease_term' => $validate->safe->lease_term,
      				'single_zero_lease' => $validate->safe->single_zero_lease,
      				'single_lease_price' => $single_zero_lease == 2 ? $validate->safe->single_lease_price : 0,
      				'single_lease_term' => $validate->safe->single_lease_term,
      				'single_lease_miles' => $validate->safe->single_lease_miles,
      				'zero_down_lease_price' => $validate->safe->zero_down_lease_price,
      				'zero_down_lease_term' => $validate->safe->zero_down_lease_term,
      				'vehicle_image' => $validate->safe->vehicle_image,
      				'alt_link_url' => $validate->safe->alt_link_url,
      				'disclaimer_text' =>Utility::clean_specialchar($_POST['disclaimer_text']),
      				'custom_marquee_text' => $validate->safe->custom_marquee_text,
      				'brand_logo_url' => $validate->safe->brand_logo_url,
      				'active' => isset($_POST['active']) ? $_POST['active'] : 0
      	
      		);
      	
      		 
      		if (!Filter::$id) {
      			 
      			$data['location'] = $_POST['location'];
      			$data['specials_type'] = $_POST['specials_type'];
      			$data['store_letter'] = strtoupper(self::$db->getValueById(Content::lcTable, "letter", $_POST['location']));
      			$data['created_ws'] = Db::toDate();
      			$data['created_id'] = App::get('Auth')->uid;
      			$data['idx'] = Utility::randNumbers();
      		
      		} else {
      			 
      			$data['location'] = $wsrow->location;
      			$data['specials_type'] = $wsrow->specials_type;
      			$data['store_letter'] = strtoupper(self::$db->getValueById(Content::lcTable, "letter", $wsrow->location));
      			$data['modified_ws'] = Db::toDate();
      			$data['modified_id'] = App::get('Auth')->uid;
      		
      		}
      	
      			
      	
      		(Filter::$id) ? self::$db->update(self::wsTable, $data, array('id' => Filter::$id)) : $last_id = self::$db->insert(self::wsTable, $data)->getLastInsertId();
      		$storeidws = self::$db->getValueById(Content::lcTable, "store_id", $data['location']);
      		$dealershipAdminUrl = Url::adminUrl("webspecials","dealership", false, "?id=$storeidws");
      		$storenamews = strtoupper(self::$db->getValueById(Content::lcTable, "name", $data['location']));
      		$webspecialslinkUpdate = "<br/> <a href= $dealershipAdminUrl>BACK TO $storenamews</a>";
      		$webspecialslinkADDED = "<br/> <a href= $dealershipAdminUrl>GO TO $storenamews</a>";
      		
      	
      		// Add to web_specials tbl
      		$makename_ws = self::$db->getValueById(Content::mkwsTable, "name", $data['make_id']);
      		$store_letter = self::$db->getValueById(Content::lcTable, "letter", $data['location']);
      		$store_id = self::$db->getValueById(Content::lcTable, "store_id", $data['location']);
      		$storename_ws = strtoupper(self::$db->getValueById(Content::lcTable, "name", $data['location']));
      		$bodystyle_name = self::$db->getValueById(Content::bsTable, "name", $data['category']);
      		$specials_type_name = self::$db->getValueById(Content::stTable, "name", $data['specials_type']);
      		$dealtype = '';
      		$deal_type_name = isset($data['deal_type'])&& empty($data['deal_type']) ? $dealtype : self::$db->getValueById(Content::dtTable, "name", $data['deal_type']);
      		$singleLease = '';
      		$single_zero_lease_name = isset($data['single_zero_lease']) && empty($data['single_zero_lease']) ? $singleLease : self::$db->getValueById(Content::zsTable, "name", $data['single_zero_lease']);
      		$year = self::$db->getValueById(Content::yTable, "name", $data['year_id']);
      			
      	
      		$idata = array(
      				'makename_ws' => $makename_ws,
      				'modelname_ws' => self::$db->getValueById(Content::mdwsTable, "name", $data['model_id']),
      				'store_letter' => $store_letter,
      				'store_id' => $store_id,
      				'storename_ws' => $storename_ws,
      				'body_style_code' => $bodystyle_name,
      				'nice_title_ws' => ucwords(str_replace("-", " ", $mid)),
      				'specials_type' => $specials_type_name,
      				'new_used_ws' => self::$db->getValueById(Content::cdTable, "name", $data['vcondition']),
      				'year' => $year,
      				'make_id' => $validate->safe->make_id,
      				'model_id' => $validate->safe->model_id,
      				'deal_type' => $deal_type_name,
      				'trim_level' => $validate->safe->trim_level,
      				'tagline' => $validate->safe->tagline,
      				'stock_number' => $validate->safe->stock_id,
      				'vin_number' => $validate->safe->vin_number,
      				'msrp' => $validate->safe->msrp,
      				'buy_price' => $validate->safe->buy_price,
      				'save_up_to_amount' => $validate->safe->save_up_to_amount,
      				'available_apr' => $validate->safe->available_apr,
      				'apr_text' => $validate->safe->apr_text,
      				'finance_for_price' => $single_zero_lease_name == "Finance For" ? $validate->safe->finance_for_price : 0,
      				'finance_for_term' => $validate->safe->finance_for_term,
      				'finance_for_payment_calcu_url' => $validate->safe->finance_for_payment_calcu_url,
      				'finance_for_interest_rate' => $single_zero_lease_name == "Finance For" ? $validate->safe->finance_for_interest_rate : 0,
      				'finance_for_down_payment' => $single_zero_lease_name == "Finance For" ? $validate->safe->finance_for_down_payment : 0,
      				'finance_for_trade_in_value' => $single_zero_lease_name == "Finance For" ? $validate->safe->finance_for_trade_in_value : 0,
      				'lease_extras' => $single_zero_lease_name == "Lease" ? $validate->safe->lease_extras : 0,
      				'lease_price' => $single_zero_lease_name == "Lease" ? $validate->safe->lease_price : 0,
      				'lease_term' => $validate->safe->lease_term,
      				'single_zero_lease' => $single_zero_lease_name,
      				'single_lease_price' => $single_zero_lease_name == "Single Pay Lease" ? $validate->safe->single_lease_price : 0,
      				'single_lease_term' => $validate->safe->single_lease_term,
      				'single_lease_miles' => $validate->safe->single_lease_miles,
      				'zero_down_lease_price' => $validate->safe->zero_down_lease_price,
      				'zero_down_lease_term' => $validate->safe->zero_down_lease_term,
      				'vehicle_image' => $validate->safe->vehicle_image,
      				'alt_link_url' => $validate->safe->alt_link_url,
      				'disclaimer_text' => Utility::clean_specialchar($_POST['disclaimer_text']),
      				'custom_marquee_text' => $validate->safe->custom_marquee_text,
      				'brand_logo_url' => $validate->safe->brand_logo_url,
      				'active' => isset($_POST['active']) ? $_POST['active'] : 0,
      				'slug_ws' => $year. '-' . $mid);
      	
      		 
      		$idata['title_ws'] = self::$db->getValueById(Content::cdTable, "name", $data['vcondition']).' '.$year.' '. $makename_ws.' '. self::$db->getValueById(Content::mdwsTable, "name", $data['model_id']).' '. $validate->safe->trim_level.' '. $bodystyle_name;
      		 
      		if (!Filter::$id) {
      			$idata['webspecials_id'] = $last_id;
      			$idata['created_ws'] = Db::toDate();
      			$idata['created_id'] = App::get('Auth')->uid;
      			 
      		} else {
      			$idata['update_flag'] = 1;
      			$idata['modified_ws'] = Db::toDate();
      			$idata['modified_id'] = App::get('Auth')->uid;
      			 
      		}
      		 
      		(Filter::$id) ? self::$db->update(self::w_sTable, $idata, array('webspecials_id' => Filter::$id ? Filter::$id : $last_id)) : self::$db->insert(self::w_sTable, $idata);
      	
      		
      		$autosaveText = "Web Specials is Auto Saving.....";
      		$message = $autosaveText;
      		Message::msgReply(self::$db->affected(), 'success', $message);
      		 
      		
      		
      		} else {
      			Message::msgSingleStatus();
      		}
      
      }
      
      /**
       * wSpecials::copybulkSpecialsWebspecials()
       *
       * @return
       */
      public function copybulkSpecialsWebspecials()
      {
      
      	$store_letter = 'P';
      	
      	$cwbsp = self::$db->select(self::wsTable, "null", array('store_letter' => $store_letter))->results();
      	$cwbsptble = isset($cwbsp->name)? 'no data':  $cwbsp;
      	$websptable = self::wsTable;
      	$location = '22';
      	
      	//print_r($cwbsptble);
      	//$cw_bsp = self::$db->select(self::w_sTable, "null", array('store_letter' => $store_letter))->results();
      	 
      	if($cwbsptble){
      		foreach($cwbsptble as $wbsp){
      			
      			$data = array(
      					//'slug' => (empty($_POST['slug'])) ? $validate->safe->year . '-' . $mid : Url::doSeo($validate->safe->slug),
      					'nice_title' => $wbsp->nice_title,
      					'location' => $location,
      					'store_letter' => strtoupper(self::$db->getValueById(Content::lcTable, "letter", $location)),
      					'specials_type' => $wbsp->specials_type,
      					'vcondition' => $wbsp->vcondition,
      					'year_id' => $wbsp->year_id,
      					'make_id' => $wbsp->make_id,
      					'model_id' => $wbsp->model_id,
      					'deal_type' => $wbsp->deal_type,
      					'category' => $wbsp->category,
      					'trim_level' => $wbsp->trim_level,
      					'tagline' => $wbsp->tagline,
      					'stock_id' => $wbsp->stock_id,
      					'vin_number' => $wbsp->vin_number,
      					'ordering' => $wbsp->ordering,
      					'msrp' => $wbsp->msrp,
      					'buy_price' => $wbsp->buy_price,
      					'save_up_to_amount' => $wbsp->save_up_to_amount,
      					'available_apr' => $wbsp->available_apr,
      					'apr_text' => $wbsp->apr_text,
      					'finance_for_price' => $wbsp->finance_for_price,
      					'finance_for_term' => $wbsp->finance_for_term,
      					'finance_for_payment_calcu_url' => $wbsp->finance_for_payment_calcu_url,
      					'finance_for_interest_rate' => $wbsp->finance_for_interest_rate,
      					'finance_for_down_payment' => $wbsp->finance_for_down_payment,
      					'finance_for_trade_in_value' => $wbsp->finance_for_trade_in_value,
      					'lease_extras' => $wbsp->lease_extras,
      					'lease_price' => $wbsp->lease_price,
      					'lease_term' => $wbsp->lease_term,
      					'single_zero_lease' => $wbsp->single_zero_lease,
      					'single_lease_price' => $wbsp->single_lease_price,
      					'single_lease_term' => $wbsp->single_lease_term,
      					'single_lease_miles' => $wbsp->single_lease_miles,
      					'zero_down_lease_price' => $wbsp->zero_down_lease_price,
      					'zero_down_lease_term' => $wbsp->zero_down_lease_term,
      					'vehicle_image' => $wbsp->vehicle_image,
      					'alt_link_url' => $wbsp->alt_link_url,
      					'disclaimer_text' => $wbsp->disclaimer_text,
      					'custom_marquee_text' => $wbsp->custom_marquee_text,
      					'brand_logo_url' => $wbsp->brand_logo_url,
      					'active' => $wbsp->active
      			
      			);
      			 
      			 
      			$data['created_ws'] = Db::toDate();
      			$data['created_id'] = App::get('Auth')->uid;
      			$data['idx'] = Utility::randNumbers();
      			 
      			 
      			$nicetitle = $data['nice_title'];
      			$last_id = self::$db->insert(self::wsTable, $data)->getLastInsertId();
      			
      			
      			// Add to web_specials tbl
      			$mid = ucwords(str_replace(" ", "-", $data['nice_title']));
      			$makename_ws = self::$db->getValueById(Content::mkwsTable, "name", $data['make_id']);
      			$modelname_ws = self::$db->getValueById(Content::mdwsTable, "name", $data['model_id']);
      			$new_used_ws = self::$db->getValueById(Content::cdTable, "name", $data['vcondition']);
      			$store_letter = strtoupper(self::$db->getValueById(Content::lcTable, "letter", $data['location']));
      			$store_id = self::$db->getValueById(Content::lcTable, "store_id", $data['location']);
      			$storename_ws = strtoupper(self::$db->getValueById(Content::lcTable, "name", $data['location']));
      			$bodystyle_name = self::$db->getValueById(Content::bsTable, "name", $data['category']);
      			$dealtype = '';
      			$deal_type_name = isset($data['deal_type']) && empty($data['deal_type'])? $dealtype : self::$db->getValueById(Content::dtTable, "name", $data['deal_type']);
      			$specials_type_name = self::$db->getValueById(Content::stTable, "name", $data['specials_type']);
      			$singleLease = '';
      			$single_zero_lease_name = isset($data['single_zero_lease']) && empty($data['single_zero_lease']) ? $singleLease : self::$db->getValueById(Content::zsTable, "name", $data['single_zero_lease']);
      			$year = self::$db->getValueById(Content::yTable, "name", $data['year_id']);
      			
      			
      			$idata = array(
      					'makename_ws' => $makename_ws,
      					'modelname_ws' => $modelname_ws,
      					'store_letter' => $store_letter,
      					'store_id' => $store_id,
      					'storename_ws' => $storename_ws,
      					'body_style_code' => $bodystyle_name,
      					'ordering' => $wbsp->ordering,
      					'nice_title_ws' => $wbsp->nice_title,
      					'specials_type' => $specials_type_name,
      					'new_used_ws' => $new_used_ws,
      					'year' => $year,
      					'make_id' => $wbsp->make_id,
      					'model_id' => $wbsp->model_id,
      					'deal_type' => $deal_type_name,
      					'trim_level' => $wbsp->trim_level,
      					'tagline' => $wbsp->tagline,
      					'stock_number' => $wbsp->stock_id,
      					'vin_number' => $wbsp->vin_number,
      					'msrp' => $wbsp->msrp,
      					'buy_price' => $wbsp->buy_price,
      					'save_up_to_amount' => $wbsp->save_up_to_amount,
      					'available_apr' => $wbsp->available_apr,
      					'apr_text' => $wbsp->apr_text,
      					'finance_for_price' => $wbsp->finance_for_price,
      					'finance_for_term' => $wbsp->finance_for_term,
      					'finance_for_payment_calcu_url' => $wbsp->finance_for_payment_calcu_url,
      					'finance_for_interest_rate' => $wbsp->finance_for_interest_rate,
      					'finance_for_down_payment' => $wbsp->finance_for_down_payment,
      					'finance_for_trade_in_value' => $wbsp->finance_for_trade_in_value,
      					'lease_extras' => $wbsp->lease_extras,
      					'lease_price' => $wbsp->lease_price,
      					'lease_term' => $wbsp->lease_term,
      					'single_zero_lease' => $single_zero_lease_name,
      					'single_lease_price' => $wbsp->single_lease_price,
      					'single_lease_term' => $wbsp->single_lease_term,
      					'single_lease_miles' => $wbsp->single_lease_miles,
      					'zero_down_lease_price' => $wbsp->zero_down_lease_price,
      					'zero_down_lease_term' => $wbsp->zero_down_lease_term,
      					'vehicle_image' => $wbsp->vehicle_image,
      					'alt_link_url' => $wbsp->alt_link_url,
      					'disclaimer_text' => $wbsp->disclaimer_text,
      					'custom_marquee_text' => $wbsp->custom_marquee_text,
      					'brand_logo_url' => $wbsp->brand_logo_url,
      					'active' => $wbsp->active,
      					'slug_ws' => strtolower($year. '-' . $mid)
      			
      			);
      			
      			
      			$idata['title_ws'] = $new_used_ws.' '.$year.' '. $makename_ws.' '.$modelname_ws.' '. $wbsp->trim_level.' '. $bodystyle_name;
      			 
      			$idata['webspecials_id'] = $last_id;
      			$idata['created_ws'] = Db::toDate();
      			$idata['created_id'] = App::get('Auth')->uid;
      			
      			
      			self::$db->insert(self::w_sTable, $idata);
      			
      			//print_r($idata);
      
      		}
      		 
      		echo 'You have just Updated '.$websptable.  ' Web Specials';
      		 
      	} else {
      		echo 'Error Updating '.$storename_ws.  ' Web Specials';
      	}
      	 
      
      }
      
      /**
       * wSpecials::updatewspricing()
       *
       * @return
       */
      public function updatewspricing()
      {
      
      	
      	
      	$cwbspricing = self::$db->select(self::wsTable, "null")->results();
      
      	if($cwbspricing){
      		foreach($cwbspricing as $wspricing){
      			 
      			    $wspricingid = $wspricing->id;
      			    $wspricingletter = $wspricing->store_letter;
      			    $wspricingstockid = $wspricing->stock_id;
      			    $websptable = self::wspTable;
      			    
      			    $data = array('store_letter_ws' => $wspricingletter, 'stock_id_ws' => $wspricingstockid);
      			    
      				
      			self::$db->update(self::wspTable, $data, array('special_id' => $wspricingid));
      			 
      			//print_r($wsprdata);
      
      		}
      		 
      		echo 'You have just Updated '.$websptable.  ' Web Specials';
      		 
      	} else {
      		echo 'Error Updating '.$storename_ws.  ' Web Specials';
      	}
      
      
      }
      
      /**
       * wSpecials::wspricingdb()
       *
       * @return
       */
      public function wspricingdb()
      {
      
      	$store_letter_b = "B";
      	
      	$store_letter_p = "P";
      	
      	$stock_id = "P19762";
      	
      
      	
      	$wstblsql = "
		  SELECT ws.id as id, ws.stock_id as stock_id, ws.store_letter, wsp.special_id, wsp.store_letter_ws, wsp.name, wsp.ordering, wsp.price, wsp.active
		  FROM
          	`" . self::wsTable . "` AS ws
          	LEFT JOIN `" . self::wspTable . "` AS wsp
          		ON wsp.stock_id_ws = ws.stock_id
			
      	WHERE ws.store_letter = '$store_letter_b'";
      	
      	//SELECT * FROM `ws_pricing` WHERE `store_letter_ws` IN('B','P') AND `stock_id_ws` = 'P19762' ORDER BY `special_id` DESC
      	//$queryArray =  'IN("'.$store_letter_b.'", "'.$store_letter_p.'") AND stock_id_ws = "'.$stock_id.'" ';
      	
      	
      	$cwbspricing = self::$db->pdoQuery($wstblsql)->results();
      	$websptable = self::wspTable;
      	$webspcount = count($cwbspricing);
      	
      	//print_r($cwbspricing);
      	
      	if($cwbspricing){
      		foreach($cwbspricing as $wspricing){
      
      			//$wspricingid = $wspricing->id;
      			//$wspricingletter = $wspricing->store_letter;
      				
      			$wsdata = array(
      					
      			'special_id'  => $wspricing->id,
      			'store_letter_ws' => $store_letter_b,
      			'stock_id_ws' => $wspricing->stock_id,
      			'name' => $wspricing->name,
      			'price' => $wspricing->price,
      			'ordering' => $wspricing->ordering,
      			'active' => $wspricing->active
      					
      			);
      			
      			$wsdata['created'] = Db::toDate();
      			$wsdata['created_id'] = App::get('Auth')->uid;
      					 
      			
      			//self::$db->update(self::wspTable3, array('store_letter_ws' => $wspricingletter), array('special_id' => $wspricingid));
                  
      			$result = array_unique($wsdata);
      			
      			print_r($result);
      			
      			//self::$db->insert(self::wspTable, $wsdata);
      			
      			
      
      		} 
      		 
      		echo 'You have just Updated '.$websptable. ' ' .$webspcount. ' Web Specials Pricing Breakdown';
      		 
      	} else {
      		echo 'Error Updating '.$storename_ws.  ' Web Specials';
      	}
      
      
      }
       
     
      
      
      
       /**
       * wSpecials::getPriceDiscounts(()
       *
       * @return
       */
      
      public function getPriceDiscounts()
      {
      
      	$where = "WHERE special_id = " . Filter::$id;
      	$sql = "
		  SELECT id, special_id, name, ordering, price, active 
      			FROM `" . self::wspTable . "`wsp
		 	    $where
		 	    AND active = 1
      			ORDER BY wsp.ordering ASC";
      			$row = self::$db->pdoQuery($sql)->results();
      
      			return ($row) ? $row : 0;
      }
      
      /**
       * wSpecials::getWebSpecialsAlert(()
       *
       * @return
       */
      
      public function getWebSpecialsAlert()
      {
      
      	
       $where = "WHERE webspecials_id = " . Filter::$id;
       $sql = "
		  SELECT 
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
      			CONCAT( u.fname, ' ',u.lname) AS username	
      			FROM `" . self::wschTable . "` AS wsch
      			LEFT JOIN `" . Users::aTable . "` AS u 
			    	 ON u.id = wsch.modified_id
            			$where
            			ORDER BY wsch.id DESC
            			";
            			$row = self::$db->pdoQuery($sql)->results();
      
            			return ($row) ? $row : 0;
      }
      
      
     
      
      /**
       * wSpecials::processPriceDiscounts()
       *
       * @return
       */
      public function processPriceDiscounts()
      {
      	
      	$name = array_filter($_POST['name'], 'strlen');
      		if (empty($name))
      			$err = Message::$msgs['answer'] = "Please enter at least one Pricing Discount.";
      	
      	if (empty(Message::$msgs)) {
      		$active = isset($_POST['active1']) ? $_POST['active1'] : 0;
      		$htmlPD = '';
      		foreach ($_POST['name'] as $key => $val) {
      					$data = array('name' => Validator::sanitize($_POST['name'][$key])
      							,'price' => Validator::sanitize($_POST['price'][$key])
      							,'ordering' => Validator::sanitize($_POST['ordering1'][$key])
      							,'active' => $active[$key]
      							, 'special_id' => Filter::$id
      							, 'stock_id_ws' => Validator::sanitize($_POST['stock_id'])
      							, 'store_letter_ws' => Validator::sanitize($_POST['store_letter'])
      							, 'created' =>Db::toDate()
      							, 'created_id' => App::get('Auth')->uid);
      					$last_id = self::$db->insert(self::wspTable, $data)->getLastInsertId();
      	                $activeIcon =  $data['active']   ? 'check positive' : 'ban purple';
      	                $npd = self::$db->select(self::wspTable, "null", array('id' => $last_id))->result();
      		            $htmlPD .= '
					    <tr>
      		            <tr data-id=" ' . $last_id . '">
					    <td class="sorter"><i class="icon reorder"></i></td>
					    <td><small> ' . $last_id . '.</small></td>		
      		            <td data-editablews="true" data-set=\'{"type": "pricediscount", "id": ' . $last_id . ' ,"key":"name", "path":""}\'>' . $npd->name . '</td>
      		            <td data-editablews="true" data-set=\'{"type": "pricediscount", "id": ' . $last_id . ' ,"key":"price", "path":""}\'>' . $npd->price . '</td>
      		            <td data-editablews="true" data-set=\'{"type": "pricediscount", "id": ' . $last_id . ' ,"key":"ordering", "path":""}\'>' . $npd->ordering . '</td>
                        <td <div class="data"> <a class="doStatus" data-set=\'{"field": "status", "table": "PriceDiscounts", "toggle": "check ban", "togglealt": "positive purple", "id":  ' . $last_id . ', "value": "' .  $npd->active . '"}\' data-content="'.Lang::$word->STATUS.'"><i class="rounded inverted  '.$activeIcon.' icon link"></i></a> </td>
      		            <td><a class="delete" data-set=\'{"title": "Delete Pricing Discount", "parent": "tr", "option": "deletePricingDiscount", "id": ' . $last_id . ', "name": "' .  $npd->name . '"}\'><i class="rounded outline icon negative trash link"></i></a></td> 
						</tr>';
      		            
      		            
      		            
      		            
      				}
      				
      				$json = array(
      						'type' => 'success',
      						'title' => Lang::$word->SUCCESS,
      						'data' => $htmlPD,
      						'message' => "Pricing Discounts ADDED!"
      						
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
       * Items::approveListing()
       * 
       * @return
       */
	  public function approveListing()
	  {
		  if ($item = self::$db->select(self::lTable, "null", array('id' => Filter::$id))->result()) {
			  $row = self::$db->select(Users::mTable, array('email','membership_id','CONCAT(fname," ",lname) as name'), array('id' => $item->user_id))->result();
			  $data = array(
					'status' => 1,
					'rejected' => 0,
					'expire' => Users::calculateDays($row->membership_id)
					);
			  self::$db->update(self::lTable, $data, array('id' => Filter::$id));
			  
			  $count = self::$db->count(self::lTable, "user_id = " . $item->user_id . " AND status = 1");
			  self::$db->update(Users::mTable, array("listings" => $count), array("id" => $item->user_id));
			  self::$db->update(self::liTable, array("lstatus" => 1), array("id" => Filter::$id));
			  
			  //Add to core
			  self::doCalc();
			  
			  $numSent = 0;
			  $mailer = Mailer::sendMail();
	
			  if ($row) {
				  ob_start();
				  require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Listing_Approve.tpl.php');
				  $html_message = ob_get_contents();
				  ob_end_clean();
	
				  $newbody = str_replace(array(
					  '[COMPANY]',
					  '[LOGO]',
					  '[FULLNAME]',
					  '[ID]',
					  '[URL]',
					  '[LURL]',
					  '[TITLE]',
					  '[DATE]'), array(
					  App::get("Core")->company,
					  Utility::getLogo(),
					  $row->name,
					  $item->idx,
					  SITEURL,
					  Url::doUrl(URL_ITEM, $item->idx . '/' . $item->slug),
					  $item->title,
					  date('Y')), $html_message);
	
				  $message = Swift_Message::newInstance()
							->setSubject(Lang::$word->WSP_APPROVED . ' ' . App::get("Core")->company)
							->setTo(array($row->email => $row->name))
							->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
							->setBody($newbody, 'text/html');
	
				  $numSent++;
				  $mailer->send($message);
			  }
			  Message::msgReply($numSent, 'success', Lang::$word->EMN_SENT, Lang::$word->EMN_ALERT);
		  } else {
			  Message::msgReply(true, 'error', Lang::$word->SYSTEM_ERR1);
		  }
	  }

      /**
       * Items::rejectListing()
       * 
       * @return
       */
	  public function rejectListing()
	  {
		  if ($item = self::$db->select(self::lTable, "null", array('id' => Filter::$id))->result()) {
			  $row = self::$db->select(Users::mTable, array('email','membership_id','CONCAT(fname," ",lname) as name'), array('id' => $item->user_id))->result();
			  $data = array('rejected' => 1);
			  self::$db->update(self::lTable, $data, array('id' => Filter::$id));
	
			  $numSent = 0;
			  $mailer = Mailer::sendMail();
	
			  if ($row) {
				  ob_start();
				  require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Listing_Reject.tpl.php');
				  $html_message = ob_get_contents();
				  ob_end_clean();
	
				  $newbody = str_replace(array(
					  '[COMPANY]',
					  '[LOGO]',
					  '[FULLNAME]',
					  '[ID]',
					  '[URL]',
					  '[REASON]',
					  '[TITLE]',
					  '[DATE]'), array(
					  App::get("Core")->company,
					  Utility::getLogo(),
					  $row->name,
					  $item->idx,
					  SITEURL,
					  Validator::sanitize($_POST['notes']),
					  $item->title,
					  date('Y')), $html_message);
	
				  $message = Swift_Message::newInstance()
							->setSubject(Lang::$word->WSP_REJECTED . ' ' . App::get("Core")->company)
							->setTo(array($row->email => $row->name))
							->setFrom(array(App::get("Core")->site_email => App::get("Core")->company))
							->setBody($newbody, 'text/html');
	
				  $numSent++;
				  $mailer->send($message);
			  }
			  Message::msgReply($numSent, 'success', Lang::$word->EMN_SENT, Lang::$word->EMN_ALERT);
		  } else {
			  Message::msgReply(true, 'error', Lang::$word->SYSTEM_ERR1);
		  }
	  }

      /**
       * Items::doCalc()
       * 
       * @return
       */
      public static function doCalc()
      {
		  $sql = array(
			  "MIN(price) AS minprice",
			  "MAX(price) AS maxprice",
			  "MIN(price_sale) AS minsprice",
			  "MAX(price_sale) AS maxsprice",
			  "MIN(YEAR) AS minyear",
			  "MAX(YEAR) AS maxyear",
			  "MIN(mileage) AS minkm",
			  "MAX(mileage) AS maxkm");
		  $val = self::$db->first(self::lTable, $sql, array("status" => 1));

		  $make = self::$db->select(self::liTable, array("make_name", "COUNT(id) as total"), array("lstatus" => 1), 'GROUP BY make_name')->results('json');
		  $category = self::$db->select(self::liTable, array("category_name", "COUNT(id) as total"), array("lstatus" => 1), 'GROUP BY category_name')->results('json');
		  $condition = self::$db->select(self::liTable, array("condition_name", "COUNT(id) as total"), null, 'GROUP BY condition_name')->results('json');
		  $color = self::$db->select(self::lTable, array("color_e", "COUNT(id) as total"), array("status" => 1), 'GROUP BY color_e')->results('json');
		  $year_list = self::$db->select(self::lTable, array("year", "COUNT(id) as total"), array("status" => 1), 'GROUP BY year')->results('json');
		  
		  $ids = self::$db->select(Items::lTable, array("GROUP_CONCAT(make_id) as mkids, GROUP_CONCAT(model_id) as mdids"))->result();
		  $makes = self::$db->pdoQuery("SELECT id, name FROM `" . Content::mkTable . "` WHERE id IN(" . $ids->mkids.") GROUP BY id")->results('json');
		  $models = self::$db->pdoQuery("SELECT id, name FROM `" . Content::mdTable . "` WHERE id IN(" . $ids->mdids.") GROUP BY id")->results('json');
		  
		  // Add to core
		  $odata = array(
			  'minprice' => $val->minprice,
			  'maxprice' => $val->maxprice,
			  'minsprice' => $val->minsprice,
			  'maxsprice' => $val->maxsprice,
			  'minyear' => $val->minyear,
			  'maxyear' => $val->maxyear,
			  'minkm' => $val->minkm,
			  'maxkm' => $val->maxkm,
			  'color' => $color,
			  'makes' => $make,
			  'year_list' => $year_list,
			  'cond_list' => $condition,
			  'category_list' => $category,
			  'make_list' => $makes,
			  'model_list' => $models,
			  );
		  self::$db->update(Core::sTable, $odata, array('id' => 1));
      }	  
      /**
       * Items::getFeatured()
       * 
       * @return
       */
      public function getFeatured()
      {
		  $row = self::$db->select(self::lTable, "*", array("status" => 1), 'ORDER BY created DESC LIMIT ' . App::get('Core')->featured)->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::getBrands()
       * 
       * @return
       */
      public function getBrands()
      {
		  $sql = "
		  SELECT 
			m.name,
			m.id,
			COUNT(m.id) as items
		  FROM
			`" . Content::mkTable . "` AS m 
			INNER JOIN `" . self::lTable . "` AS l 
			  ON l.make_id = m.id 
		  WHERE l.status = ?
		  GROUP BY m.id
		  ORDER BY items DESC
		  LIMIT 10;";
		  
		  $row = self::$db->pdoQuery($sql, array(1))->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::renderBrands()
       * 
       * @return
       */
      public function renderBrands($no = 5)
      {
		  $sql = "
		  SELECT 
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.thumb,
			l.sold, l.created, x.make_name, x.model_name, x.category_name, x.condition_name, x.trans_name
		  FROM
			(SELECT 
			  li.category_name, li.make_name, li.condition_name,
			  li.model_name, li.trans_name, li.listing_id,
			  CASE
				WHEN li.make_name = @brand 
				THEN @rownum := @rownum + 1 
				ELSE @rownum := 1 
			  END AS rank,
			  @brand := li.make_name 
			FROM
			  `" . self::liTable . "` li 
			  JOIN 
				(SELECT 
				  @rownum := 0,
				  @brand := NULL) r 
			ORDER BY li.make_name, li.listing_id) x
			INNER JOIN `" . self::lTable . "` AS l 
			  ON l.id = x.listing_id 
		  WHERE l.status = ?
		  AND x.rank <= $no
		  ORDER BY l.created DESC;";
		  
		  $row = self::$db->pdoQuery($sql, array(1))->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::getBrand()
       * 
       * @return
       */
      public function getBrand()
      {

		  $row = self::$db->first(Content::mkTable, array("name", "name_slug", "id"), array("name_slug" => App::get('Core')->_url[1]));
          return ($row) ? $row : 0;
      }

      /**
       * Items::renderByBrand()
       * 
       * @return
       */
      public function renderByBrand($make_id)
      {
		  $and = null;
		  $parts = parse_url($_SERVER['REQUEST_URI']);
		  isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
		  $required = array(
			  "model_id" => 0,
			  "transmission" => 1,
			  "price" => 2,
			  "miles" => 3,
			  "year" => 4
			  );
		  if (array_intersect_key($qs, $required)) { 
              if (Validator::notEmptyGet('model_id') and $model_id = Validator::sanitize($_GET['model_id'], "digits", 11)) {
				  $and .= " AND model_id = {$model_id}";
			  }  
              if (Validator::notEmptyGet('transmission') and $transmission = Validator::sanitize($_GET['transmission'], "digits", 2)) {
				  $and .= " AND transmission = {$transmission}";
			  }   
              if (Validator::notEmptyGet('year') and $year = Validator::sanitize($_GET['year'], "digits", 4)) {
				  $and .= " AND year = {$year}";
			  }     
              if (Validator::notEmptyGet('price') and $price = Validator::sanitize($_GET['price'], "digits", 12)) {
				  switch($price) {
					  case 10 :
					    $and .= " AND price BETWEEN 0 AND 5000";
					  break;
					  case 20 :
					    $and .= " AND price BETWEEN 5000 AND 10000";
					  break;
					  case 30 :
					    $and .= " AND price BETWEEN 10000 AND 20000";
					  break;
					  case 40 :
					    $and .= " AND price BETWEEN 20000 AND 30000";
					  break;
					  case 50 :
					    $and .= " AND price BETWEEN 30000 AND 50000";
					  break;
					  case 60 :
					    $and .= " AND price BETWEEN 50000 AND 75000";
					  break;
					  case 70 :
					    $and .= " AND price BETWEEN 75000 AND 100000";
					  break;
					  case 80 :
					    $and .= " AND price BETWEEN 100000 AND 9999999";
					  break;
				  }
			  } 
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND make_id = {$make_id}{$and}");
		  } else {
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND make_id = {$make_id}");
		  }
		  
          $pager = Paginator::instance();
          $pager->items_total = $total;
          $pager->default_ipp = App::get("Core")->sperpage;
          $pager->paginate();
		  
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.thumb, l.created, l.featured, l.body, l.mileage
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = 1
		  AND l.make_id = {$make_id}
		  $and
		  ORDER BY l.featured DESC, l.created DESC
		  {$pager->limit};";
		  
		  $row = self::$db->pdoQuery($sql, array(1, $make_id))->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::getBodyType()
       * 
       * @return
       */
      public function getBodyType()
      {

		  $sql = "
		  SELECT 
			name,
			slug,
			id
		  FROM
			`" . Content::ctTable . "`
			WHERE slug = ? 
		  LIMIT 1;";

		  $row = self::$db->pdoQuery($sql, array(App::get('Core')->_url[1]))->result();
          return ($row) ? $row : 0;
      }

      /**
       * Items::renderByCategory()
       * 
       * @return
       */
      public function renderByCategory($cat_id)
      {
		  $and = null;
		  $parts = parse_url($_SERVER['REQUEST_URI']);
		  isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
		  $required = array(
			  "make_id" => 0,
			  "model_id" => 1,
			  "transmission" => 2,
			  "price" => 3,
			  "miles" => 4,
			  "year" => 5
			  );
		  if (array_intersect_key($qs, $required)) {
			  if (Validator::notEmptyGet('make_id') and $make_id = Validator::sanitize($_GET['make_id'], "digits", 11)) {
				  $and .= " AND make_id = {$make_id}";
			  }  
              if (Validator::notEmptyGet('model_id') and $model_id = Validator::sanitize($_GET['model_id'], "digits", 11)) {
				  $and .= " AND model_id = {$model_id}";
			  }  
              if (Validator::notEmptyGet('transmission') and $transmission = Validator::sanitize($_GET['transmission'], "digits", 2)) {
				  $and .= " AND transmission = {$transmission}";
			  }   
              if (Validator::notEmptyGet('year') and $year = Validator::sanitize($_GET['year'], "digits", 4)) {
				  $and .= " AND year = {$year}";
			  }     
              if (Validator::notEmptyGet('price') and $price = Validator::sanitize($_GET['price'], "digits", 12)) {
				  switch($price) {
					  case 10 :
					    $and .= " AND price BETWEEN 0 AND 5000";
					  break;
					  case 20 :
					    $and .= " AND price BETWEEN 5000 AND 10000";
					  break;
					  case 30 :
					    $and .= " AND price BETWEEN 10000 AND 20000";
					  break;
					  case 40 :
					    $and .= " AND price BETWEEN 20000 AND 30000";
					  break;
					  case 50 :
					    $and .= " AND price BETWEEN 30000 AND 50000";
					  break;
					  case 60 :
					    $and .= " AND price BETWEEN 50000 AND 75000";
					  break;
					  case 70 :
					    $and .= " AND price BETWEEN 75000 AND 100000";
					  break;
					  case 80 :
					    $and .= " AND price BETWEEN 100000 AND 9999999";
					  break;
				  }
			  } 
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND category = {$cat_id}{$and}");
		  } else {
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND category = {$cat_id}");
		  }
		  
          $pager = Paginator::instance();
          $pager->items_total = $total;
          $pager->default_ipp = App::get("Core")->sperpage;
          $pager->paginate();
		  
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.thumb, l.featured, l.created, l.featured
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = 1
		  AND l.category = {$cat_id}
		  $and
		  ORDER BY l.featured DESC, l.created DESC
		  {$pager->limit};";
		  
		  $row = self::$db->pdoQuery($sql)->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::getSeller()
       * 
       * @return
       */
      public function getSeller()
      {
		  
		  $row = self::$db->first(Content::lcTable, null, array("name_slug" => App::get('Core')->_url[1]));
          return ($row) ? $row : 0;
      }

      /**
       * Items::renderBySeller()
       * 
       * @return
       */
      public function renderBySeller($location)
      {
		  
		  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1 AND location = {$location}");
          $pager = Paginator::instance();
          $pager->items_total = $total;
          $pager->default_ipp = App::get("Core")->sperpage;
          $pager->paginate();
		  
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.thumb, l.featured, l.created, l.featured
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = ?
		  AND l.location = ?
		  ORDER BY l.featured DESC, l.created DESC
		  {$pager->limit};";
		  
		  $row = self::$db->pdoQuery($sql, array(1, $location))->results();
          return ($row) ? $row : 0;
      }
	  
      /**
       * Items::getFooterBits()
       * 
       * @return
       */
      public function getFooterBits()
      {
		  
		  $row = self::$db->select(self::liTable, array("make_name", "category_name"), null, "ORDER BY make_name")->results();
          return ($row) ? $row : 0;
      }
	  
      /**
       * Items::renderListings()
       * 
       * @return
       */
	  public function renderListings()
	  {
	
		  if (isset($_GET['order'])) {
			  list($sort, $order) = explode("/", $_GET['order']);
			  $sort = Validator::sanitize($sort, "default", 10);
			  $order = Validator::sanitize($order, "default", 4);
			  $array = array(
				  "year",
				  "price",
				  "make",
				  "model",
				  "mileage"
				  );
			  if (in_array($sort, $array)) {
				  $ord = ($order == 'DESC') ? " DESC" : " ASC";
				  switch ($sort) {
					  case "year":
						  $sortorder = "l.year";
						  break;
					  case "price":
						  $sortorder = "l.price";
						  break;
					  case "make":
						  $sortorder = "l.make_id";
						  break;
					  case "model":
						  $sortorder = "l.model_id";
						  break;
					  case "mileage":
						  $sortorder = "l.mileage";
						  break;
					  default:
						  $sortorder = "l.featured DESC, l.created DESC";
						  break;
				  }
				  $sorting = $sortorder . $ord;
			  } else {
				  $sorting = " l.featured DESC, l.created DESC";
			  }
		  } else {
			  $sorting = " l.featured DESC, l.created DESC";
		  }
	
		  $and = null;
		  $parts = parse_url($_SERVER['REQUEST_URI']);
		  isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
		  $required = array(
			  "condition" => 0,
			  "make_name" => 1,
			  "color" => 2,
			  "body" => 3,
			  "sale" => 4
			  );
		  if (array_intersect_key($qs, $required)) {
			  if (!empty($_GET['condition']) and $condition = Validator::sanitize($_GET['condition'], "db", 6)) {
				  $and .= " AND li.condition_name = '{$condition}'";
			  } 
			  if (!empty($_GET['make_name']) and $make_name = Validator::sanitize($_GET['make_name'], "db", 30)) {
				  $and .= " AND li.make_slug = '{$make_name}'";
			  } 
			  if (!empty($_GET['color']) and $color = Validator::sanitize($_GET['color'], "alpha", 20)) {
				  $and .= " AND li.color_name = '{$color}'";
			  } 
			  if (!empty($_GET['body']) and $body = Validator::sanitize($_GET['body'], "db", 30)) {
				  $and .= " AND li.category_slug = '{$body}'";
			  } 
			  if (isset($_GET['sale'])) {
				  $and .= " AND li.special = 1";
			  }
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::liTable . "` as li WHERE lstatus = 1{$and}");
		  } else {
			  if (isset($_GET['range_search'])) {
				  if (!empty($_GET['year_range']) and $year = Validator::sanitize($_GET['year_range'], "string", 9)) {
					  $ydata = explode(";", $year);
					  if (count($ydata) == 2 and intval($ydata[0]) and intval($ydata[1])) {
						  $and .= " AND year BETWEEN " . Validator::sanitize($ydata[0], "digits") . " AND " . Validator::sanitize($ydata[1], "digits");
					  }
				  }
		
				  if (!empty($_GET['price_range']) and $price = Validator::sanitize($_GET['price_range'], "string", 16)) {
					  $pdata = explode(";", $price);
					  if (count($pdata) == 2 and intval($pdata[0]) and intval($pdata[1])) {
						  $and .= " AND price BETWEEN " . Validator::sanitize($pdata[0], "digits") . " AND " . Validator::sanitize($pdata[1], "digits");
					  }
				  }
				  if (!empty($_GET['km_range']) and $kms = Validator::sanitize($_GET['km_range'], "string", 20)) {
					  $kdata = explode(";", $kms);
					  if (count($kdata) == 2 and intval($kdata[0]) and intval($kdata[1])) {
						  $and .= " AND mileage BETWEEN " . Validator::sanitize($kdata[0], "digits") . " AND " . Validator::sanitize($kdata[1], "digits");
					  }
				  }
				
				  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` WHERE status = 1{$and}");
			  } else {
				  $total = self::$db->count(self::lTable, "status = 1");
			  }
		  }
	
		  $pager = Paginator::instance();
		  $pager->items_total = $total;
		  $pager->default_ipp = App::get("Core")->perpage;
		  $pager->paginate();
	
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name, l.thumb,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.created, l.mileage, l.body, l.featured
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = ?
		  $and
		  ORDER BY $sorting{$pager->limit};";
	
		  $row = self::$db->pdoQuery($sql, array(1))->results();
		  return ($row) ? $row : 0;
	  }

      /**
       * Items::getSingleListing()
       * 
       * @return
       */
      public function getSingleListing()
      {
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.location_name, li.fuel_name, li.fuel_name, 
			u.username, u.avatar, l.*
		  FROM
			`" . self::lTable . "` AS l
			LEFT JOIN `" . self::liTable . "` AS li
			  ON li.listing_id = l.id 
			LEFT JOIN `" . Users::mTable . "` AS u
			  ON u.id = l.user_id
		  WHERE l.status = ?
		  AND l.idx = ?
		  AND l.slug = ?;";

		  $row = self::$db->pdoQuery($sql, array(1, App::get('Core')->_url[1], App::get('Core')->_url[2]))->result();
		  return ($row) ? $row : 0;

      }

      /**
       * Items::fullSearch()
       * 
       * @return
       */
      public function fullSearch()
      {
		  if (isset($_GET['order'])) {
			  list($sort, $order) = explode("/", $_GET['order']);
			  $sort = Validator::sanitize($sort, "default", 10);
			  $order = Validator::sanitize($order, "default", 4);
			  $array = array(
				  "year",
				  "price",
				  "make",
				  "model",
				  "mileage"
				  );
			  if (in_array($sort, $array)) {
				  $ord = ($order == 'DESC') ? " DESC" : " ASC";
				  switch ($sort) {
					  case "year":
						  $sortorder = "l.year";
						  break;
					  case "price":
						  $sortorder = "l.price";
						  break;
					  case "make":
						  $sortorder = "l.make_id";
						  break;
					  case "model":
						  $sortorder = "l.model_id";
						  break;
					  case "mileage":
						  $sortorder = "l.mileage";
						  break;
					  default:
						  $sortorder = "l.featured DESC, l.created DESC";
						  break;
				  }
				  $sorting = $sortorder . $ord;
			  } else {
				  $sorting = " l.featured DESC, l.created DESC";
			  }
		  } else {
			  $sorting = " l.featured DESC, l.created DESC";
		  }
	
		  $and = null;
		  $parts = parse_url($_SERVER['REQUEST_URI']);
		  isset($parts['query']) ? parse_str($parts['query'], $qs) : $qs = array();
		  $required = array(
			  "make_id" => 0,
			  "model_id" => 1,
			  "transmission" => 2,
			  "color_e" => 3,
			  "category" => 4,
			  "vcondition" => 5,
			  "doors" => 6,
			  "fuel" => 6
			  );
		  if (array_intersect_key($qs, $required)) {
			  if (!empty($_GET['make_id']) and $make_id = Validator::sanitize($_GET['make_id'], "digits", 11)) {
				  $and .= " AND l.make_id = {$make_id}";
			  }  
              if (!empty($_GET['model_id']) and $model_id = Validator::sanitize($_GET['model_id'], "digits", 11)) {
				  $and .= " AND l.model_id = {$model_id}";
			  }  
              if (!empty($_GET['transmission']) and $transmission = Validator::sanitize($_GET['transmission'], "digits", 2)) {
				  $and .= " AND l.transmission = {$transmission}";
			  }   
              if (!empty($_GET['color']) and $color = Validator::sanitize($_GET['color'], "alpha", 20)) {
				  $and .= " AND l.color_e = '{$color}'";
			  } 
              if (!empty($_GET['category']) and $category = Validator::sanitize($_GET['category'], "digits", 6)) {
				  $and .= " AND l.category = '{$category}'";
			  }  
              if (!empty($_GET['condition']) and $condition = Validator::sanitize($_GET['condition'], "digits", 1)) {
				  $and .= " AND l.vcondition = {$condition}";
			  } 
              if (!empty($_GET['doors']) and $doors = Validator::sanitize($_GET['doors'], "digits", 1)) {
				  $and .= " AND l.doors = {$doors}";
			  }
              if (!empty($_GET['fuel']) and $fuel = Validator::sanitize($_GET['fuel'], "digits", 2)) {
				  $and .= " AND l.fuel = {$fuel}";
			  } 
			  
			  if (!empty($_GET['price']) and $price = Validator::sanitize($_GET['price'], "digits", 12)) {
				  switch($price) {
					  case 10 :
						$and .= " AND price BETWEEN 0 AND 5000";
					  break;
					  case 20 :
						$and .= " AND price BETWEEN 5000 AND 10000";
					  break;
					  case 30 :
						$and .= " AND price BETWEEN 10000 AND 20000";
					  break;
					  case 40 :
						$and .= " AND price BETWEEN 20000 AND 30000";
					  break;
					  case 50 :
						$and .= " AND price BETWEEN 30000 AND 50000";
					  break;
					  case 60 :
						$and .= " AND price BETWEEN 50000 AND 75000";
					  break;
					  case 70 :
						$and .= " AND price BETWEEN 75000 AND 100000";
					  break;
					  case 80 :
						$and .= " AND price BETWEEN 100000 AND 9999999";
					  break;
				  }
			  } 

			  if (!empty($_GET['year_range']) and $year = Validator::sanitize($_GET['year_range'], "string", 9)) {
				  $ydata = explode(";", $year);
				  if (count($ydata) == 2 and intval($ydata[0]) and intval($ydata[1])) {
					  $and .= " AND year BETWEEN " . Validator::sanitize($ydata[0], "digits") . " AND " . Validator::sanitize($ydata[1], "digits");
				  }
			  }
			  if (!empty($_GET['price_range']) and $price = Validator::sanitize($_GET['price_range'], "string", 16)) {
				  $pdata = explode(";", $price);
				  if (count($pdata) == 2 and intval($pdata[0]) and intval($pdata[1])) {
					  $and .= " AND price BETWEEN " . Validator::sanitize($pdata[0], "digits") . " AND " . Validator::sanitize($pdata[1], "digits");
				  }
			  }
			  if (!empty($_GET['km_range']) and $kms = Validator::sanitize($_GET['km_range'], "string", 20)) {
				  $kdata = explode(";", $kms);
				  if (count($kdata) == 2 and intval($kdata[0]) and intval($kdata[1])) {
					  $and .= " AND mileage BETWEEN " . Validator::sanitize($kdata[0], "digits") . " AND " . Validator::sanitize($kdata[1], "digits");
				  }
			  }
				  
			  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` as l WHERE status = 1{$and}");
		  } else {
			  if (!empty($_GET['keyword']) and $keyword = Validator::sanitize($_GET['keyword'], "string", 20)) {
				  $and .= " AND l.nice_title LIKE '%{$keyword}%'";
				  $total = self::$db->count(false, false, "SELECT COUNT(*) FROM `" . self::lTable . "` as l WHERE status = 1{$and}");
			  } else {
				  $total = self::$db->count(self::lTable, "status = 1");
			  }
		  }

		  $pager = Paginator::instance();
		  $pager->items_total = $total;
		  $pager->default_ipp = App::get("Core")->sperpage;
		  $pager->paginate();
	
		  $sql = "
		  SELECT 
			li.model_name, li.condition_name, li.category_name, li.trans_name, li.fuel_name, l.thumb,
			l.idx, l.nice_title, l.title, l.slug, l.price, l.price_sale, l.year, l.sold, l.created, l.mileage, l.body, l.featured
		  FROM
			`" . self::liTable . "` AS li 
			LEFT JOIN `" . self::lTable . "` AS l 
			  ON l.id = li.listing_id 
		  WHERE l.status = ?
		  $and
		  ORDER BY $sorting{$pager->limit};";
	
		  $row = self::$db->pdoQuery($sql, array(1))->results();
		  return ($row) ? $row : 0;

      }
	  
      /**
       * Items::getUserActivity()
       * 
       * @param bool $id
       * @return
       */
      public function getUserActivity($id = false)
      {
          $uid = ($id) ? $id : Filter::$id;
          $row = self::$db->select(self::acTable, null, array("user_id" => $uid), 'ORDER BY created DESC')->results();

          return ($row) ? $row : 0;

      }

      /**
       * Items::getUserItems()
       * 
       * @param bool $id
       * @return
       */
      public function getUserItems($id = false)
      {
          $uid = ($id) ? $id : Filter::$id;
          $row = self::$db->select(self::lTable, null, array("user_id" => $uid), 'ORDER BY created DESC')->results();

          return ($row) ? $row : 0;

      }

      /**
       * Items::getUserListing()
       * 
       * @return
       */
      public function getUserListing()
      {
          $row = self::$db->first(self::lTable, null, array("user_id" => App::get('Auth')->uid, "id" => Filter::$id));

          return ($row) ? $row : 0;

      }
	  
      /**
       * Items::getGalleryImages()
       * 
       * @param bool $lid
       * @return
       */
      public static function getGalleryImages($lid = false)
      {
          $id = ($lid) ? $lid : Filter::$id;

          $row = self::$db->select(self::gTable, "*", array("listing_id" => $id), 'ORDER BY sorting')->results();
          return ($row) ? $row : 0;
      }

      /**
       * Items::processGaleryImage()
       * 
       * @param str $filename
	   * @param int $id
       * @return
       */
      public static function processGaleryImage($filename, $id)
      {
		  $path = UPLOADS . 'listings/pics' . $id . '/';
		  $maxsize = 6291456;
		  
          if (isset($_FILES[$filename]) && $_FILES[$filename]['error'] == 0) {
			  $extension = pathinfo($_FILES[$filename]['name'], PATHINFO_EXTENSION);
			  if (!in_array(strtolower($extension), array("jpg","jpeg","png"))) {
				  $json['type'] = "error";
				  $json['message'] = $json['message'] = str_replace("[EXT]", $extension, Lang::$word->FM_FILE_ERR5);
				  print json_encode($json);
				  exit;
			  }  

			  if (file_exists($path . $_FILES[$filename]['name'])) {
				  $json['type'] = "error";
				  $json['message'] = Lang::$word->FM_FILE_ERR1;
				  print json_encode($json);
				  exit;
			  }
			  
			  if (!is_writeable($path)) {
				  $json['type'] = "error";
				  $json['message'] = Lang::$word->FM_FILE_ERR2;
				  print json_encode($json);
				  exit;
			  }
			  
			  if ($maxsize < $_FILES[$filename]['size']) {
				  $json['type'] = "error";
				  $json['message'] = str_replace("[LIMIT]", File::getSize($maxsize), Lang::$word->FM_FILE_ERR3);
				  print json_encode($json);
				  exit;
			  }
			  
			  $html = '';
			  $newName = "IMG_" . Utility::randName();
			  $fullname = $path . $newName . "." . strtolower(File::getExtension($_FILES[$filename]['name']));
			  $dbname = $newName . "." . strtolower(File::getExtension($_FILES[$filename]['name']));

			  if (move_uploaded_file($_FILES[$filename]['tmp_name'], $fullname)) {
				  $data = array(
					  'listing_id' => $id,
					  'title' => "-/-",
					  'photo' => $dbname);
					  
				  $last_id = self::$db->insert(self::gTable, $data)->getLastInsertId();

                  try {
                      $img = new Image($path . $data['photo']); 
                      $img->thumbnail(400, 300)->save($path . 'thumbs/' . $data['photo']);
                  }
                  catch (exception $e) {
                      echo 'Error: ' . $e->getMessage();
                  }
				  
				  $html = '
					<tr data-id="' . $last_id . '" class="active">
					  <td class="sorter"><i class="icon reorder"></i></td>
					  <td><a href="' . UPLOADURL . 'listings/pics' . $id . '/' . $data['photo'] . '" data-title="Hello" data-lightbox-gallery="true" data-lightbox="true"><img src="' . UPLOADURL . 'listings/pics' . $id . '/thumbs/' . $data['photo'] . '" alt="" class="wojo grid small image"></a></td>
					  <td data-editable="true" data-set=\'{"type": "gallery", "id": ' . $last_id . ',"key":"name", "path":""}\'>' . $data['title'] . '</td>
					  <td><small class="wojo label">0</small></td>
					  <td><a class="delete" data-set=\'{"title": "' . Lang::$word->GAL_DELETE . '", "parent": "tr", "option": "deleteGalleryImage", "extra": "' . $data['photo'] . '", "id": ' . $last_id . ', "name": "' . $data['title'] . '"}\'><i class="rounded outline icon negative trash link"></i></a></td>
					</tr>';
			
				  
				  $json['type'] = "success";
				  $json['html'] = $html;
				  print json_encode($json);
				  exit;
			  }
		  }
		  
		  $json['type'] = "error";
		  print json_encode($json);
		  exit;

      }
	  

      /**
       * Items::quickMessage()
       * 
       * @return
       */
      public function quickMessage()
      {
          $validate = Validator::instance();
          $validate->addSource($_POST);
          $validate->addRule('msg', 'string', true, 3, 300, Lang::$word->CL_QMSG);
          $validate->run();

          if (empty(Message::$msgs)) {
              $numSent = 0;
              $mailer = Mailer::sendMail();
              $row = self::$db->select(Users::mTable, array('email', 'CONCAT(fname," ",lname) as name'), array('id' => Filter::$id))->result();

              if ($row) {
                  ob_start();
                  require_once (BASEPATH . 'mailer/' . App::get('Core')->lang . '/Quick_Email_From_Admin.tpl.php');
                  $html_message = ob_get_contents();
                  ob_end_clean();

                  $newbody = str_replace(array(
                      '[COMPANY]',
                      '[LOGO]',
                      '[FULLNAME]',
                      '[URL]',
                      '[MSG]',
                      '[DATE]'), array(
                      App::get("Core")->company,
                      Utility::getLogo(),
                      $row->name,
                      SITEURL,
                      $validate->safe->msg,
                      date('Y')), $html_message);

                  $message = Swift_Message::newInstance()
							->setSubject(Lang::$word->EMN_NOTEFROM . ' ' . App::get("Core")->company)
							->setTo(array($row->email => $row->name))->setFrom(array(App::get("Core")
							->site_email => App::get("Core")->company))
							->setBody($newbody, 'text/html');

                  $numSent++;
                  $mailer->send($message);
              }

              if ($numSent) {
                  $json['type'] = 'success';
                  $json['title'] = Lang::$word->SUCCESS;
                  $json['message'] = $numSent . ' ' . Lang::$word->EMN_SENT;
              } else {
                  $json['type'] = 'error';
                  $json['title'] = Lang::$word->ERROR;
                  $json['message'] = Lang::$word->EMN_ALERT;
              }
              print json_encode($json);
          } else {
              Message::msgSingleStatus();
          }
      }


      /**
       * Items::updateHits($id)
       * 
       * @param bool $id
       * @return
       */
      public function updateHits($id)
      {
		  $date = date('Y-m-d');
          if ($row = self::$db->pdoQuery("SELECT * FROM `" . Stats::sTable . "` WHERE DATE(created) = ? AND listing_id = ? LIMIT 1", array($date, $id))->result()) {
			  self::$db->pdoQuery("UPDATE `" . Stats::sTable . "` SET visits = visits+1 WHERE listing_id = {$row->listing_id} AND DATE(created) = '{$date}'");
          } else {
              $data = array(
                  'created' => Db::toDate(),
                  'visits' => 1,
                  'listing_id' => $id,
              );
			  self::$db->insert(Stats::sTable, $data);
          }
		  self::$db->pdoQuery("UPDATE " . self::lTable . " SET hits = hits+1 WHERE id = " . $id);

      }
	  
      /**
       * Webspecials::doTitle()
       * 
       * @return
       */
      public static function doTitle($model_id)
      {
          $sql = "
		  SELECT 
			md.name as mdname, mk.name as mkname 
		  FROM
			`" . Content::mdwsTable . "` AS md 
			LEFT JOIN `" . Content::mkwsTable . "` AS mk 
			  ON mk.id = md.make_id 
		  WHERE md.id = ?;";

          $row = self::$db->pdoQuery($sql, array($model_id))->result();
          return ($row) ? Url::doSeo($row->mkname . '-' . $row->mdname) : 0;
      }

      /**
       * Items::doActivity()
       * 
       * @return
       */
      public static function doActivity($array = array())
      {
		  
		  $data = array(
			'user_id' => $array['user_id'], 
			'type' => $array['type'], 
			'lid' => $array['lid'],
			'title' => $array['title'],
			'username' => $array['username'],
			'fname' => $array['fname'],
			'lname' => $array['lname'],
		   );
		  self::$db->insert(self::acTable, $data);
      }
	  
      /**
       * Items::activityIcons()
       * 
       * @param mixed $type
       * @return
       */
      public static function activityIcons($type)
      {

          switch ($type) {
              case "added":
                  return "car";

              case "like":
                  return "thumbs up";

              case "membership":
                  return "medal";

              case "login":
                  return "lock";
          }
      }


      /**
       * Items::activityTitle()
       * 
       * @param mixed $row
       * @return
       */
      public static function activityTitle($row)
      {
          switch ($row->type) {
              case "like":
                  return Lang::$word->LIKE . " &rsaquo; " . $row->title;

              case "added":
                  return Lang::$word->ADDED . " &rsaquo; " . $row->title;

              case "membership":
                  return Lang::$word->MEMBERSHIP . " &rsaquo; " . $row->title;

              case "login":
                  return Lang::$word->LOGIN;
          }
      }


      /**
       * Items::activityDesc()
       * 
       * @param mixed $row
       * @return
       */
      public static function activityDesc($row)
      {
          switch ($row->type) {
              case "like":
                  return Lang::$word->WSP_AC_LIKED . " &rsaquo; " . $row->title;

              case "added":
                  return Lang::$word->WSP_AC_ADD . " &rsaquo; " . $row->title;

              case "membership":
                  return Lang::$word->WSP_AC_MEM . " &rsaquo; " . $row->title;

              case "login":
                  return Lang::$word->WSP_AC_LOGIN;
          }
      }
  }