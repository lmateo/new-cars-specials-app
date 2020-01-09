<?php

/*
 * 
 * ncsApi - Communication Tool for 3rd Party to connect to the Web Specials
 * 
 * # How to run script.  use the code below 
 * $arr = $_POST; (post via curl or http)
 * $aNcs = new ncsApi();
 * $aNcs->apiDataArr = $arr;
 * $retXml = $aNcs->handleRequest();
 * print $retXml;
 * 
 * using 1 of the methods below...
 * 
 * # Do an Auth Check
 * public_key (required)
 * task = "AUTHCHECK" (required)
 * wp_sp_domain (required)
 * 
 * # Do a Web Specials Search
 * public_key (required)
 * task = "NCS_SEARCH" (required)
 * wp_sp_domain (required)
 * specials_type (defaults to "SALES")
 * store_letter
 * store_id
 * active  (defaults to "1")
 * body_style 
 * year
 * make_id
 * make_name
 * model_id
 * model_name 
 * order_by 
 * order_direction 
 * limit
 * 
 * # Sync Database of IDs
 * public_key (required)
 * task = "NCS_SYNC" (required)
 * wp_sp_domain (required)
 * wp_sp_ids (required)
 * 
 * 
 */

//modified the below class which was originally an extensions of the quirk class but now is a standalone and includes the run_qury function from quirk
class ncsApi {
	public $dbname = "quirkspe_inventory";
	public $specialsTblName = "web_specials";
	public $syncTblName = "web_specials_wp";
	public $storesTblName = "stores";
	public $specialPricingTblName = "ws_pricing";
	public $makesTblName = "makes_ws";
	public $modelsTblName = "models_ws";
	public $errorsArr = array();
	public $apiDataArr = array();
	public $authorized = FALSE;
	public $privateKey = "fjFB9E!4h_HC012";
	public $publicKey = "";
	public $cD1 = "<![CDATA[";
	public $cD2 = "]]>";
	public $retXml = "";
	public $retSpecialsXml = "";
	public $retErrorXml = "";
	public $apiSqlResult = "";	
	public $apiSqlWhere = "";
	public $apiSqlQuery = ""; 
	public $whereArr = array();
	public $apiTotalSpecialsFound = 0;

	function _run_query($query){ //internal
		$dbuser="quirkspe_ncsuser";
		$dbpass="(OTiJdM%.JUs";
		//$chandle = mysql_connect("localhost", $dbuser, $dbpass)	depreciated	
		$chandle = mysqli_connect("localhost", $dbuser, $dbpass, $dbname)
		or die("Connection Failure to Database");
		//mysql_select_db($this->dbname, $chandle) or die ($dbname . " Database not found. User: " . $dbuser); depreciated	
		mysqli_select_db($chandle,$this->dbname) or die ($dbname . " Database not found. User: " . $dbuser);
		//$result = mysql_query($query,$chandle) or die("Error with Query String($query): ". mysql_error()); depreciated	
		$result = mysqli_query($chandle,$query) or die("Error with Query String($query): ". mysqli_error($chandle));
		//mysql_close($chandle); depreciated	
		mysqli_close($chandle);
		return $result;
	}
	
	function ncsApiMethod()
	{
		$this->publicKey = "";
		$this->authorized = FALSE;
	}

	function authorizeMe()
	{
		if( $this->publicKey == $this->privateKey )
		{
			$this->authorized = TRUE;
		}
		else
		{
			$this->authorized = FALSE;
			$this->errorsArr[] = "Invalid Authorization [".$this->publicKey."]";
		}
		return $this->authorized;
	}

	function getAuthReponse()
	{		
		$this->retXml = "";
		$this->retXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
		$this->retXml .= "<ncs version=\"1.0\">\r\n";
		$this->retXml .= "	<messages>\r\n";
		$this->retXml .= "	  <message>Authorized User!</message>\r\n";
		$this->retXml .= "	</messages>\r\n";
		$this->retXml .= "</ncs>\r\n";
	}

	function handleRequest()
	{
		$this->apiSqlResult = "";
		$this->apiSqlWhere = "";
		$this->whereArr = array();

		if( count($this->apiDataArr) > 0 )
		{
			$this->publicKey = $this->apiDataArr['public_key'];

			if( isset($this->apiDataArr['public_key']) )
			{
				if( isset($this->apiDataArr['task']) )
				{
					switch( strtoupper($this->apiDataArr['task']) )
					{
						case "AUTHCHECK":
							$this->authorizeMe();
							$this->getAuthReponse();		
							break;
								
						case "NCS_SEARCH":
							if( $this->authorizeMe() )
							{
								$this->doNcsSearch();
							}
							break;

						case "NCS_SYNC":
							if( $this->authorizeMe() )
							{
								$this->sync_wp_web_specials();
							}
							break;

						default:
							$this->errorsArr[] = "Error [invalid task]";
							break;
					}
				}
				else
				{
					$this->errorsArr[] = "Error [no task]";
				}
			}
			else
			{
				$this->errorsArr[] = "Invalid Authorization [no key]";
			}
		}
		else
		{
			$this->errorsArr[] = "Error [nothing at all]";
		}
		
		/* 
		successful return code should be built at this point if successful. 
		check for errors and overwrite returning xml code with error message	
		*/
		
		if( count($this->errorsArr) > 0 )
		{
			$this->getApiErrorReponse();
			$this->retXml = $this->retErrorXml; 
		}
	}

	function doNcsSearch()
	{
		$this->doNcsBuildSearchQuery();
		$this->getNcsSearchReponse();
	}

	function doNcsBuildSearchQuery()
	{	
		$this->apiSqlQuery = "SELECT *, 
							(select post_id from ".$this->syncTblName." WHERE 
							web_special_id=".$this->specialsTblName.".webspecials_id AND domain_name='".addslashes($this->apiDataArr['wp_sp_domain'])."' ) as post_id  
							FROM ".$this->specialsTblName." [WHERE] order by [ORDER_FIELD] [ORDER_DIRECTION]";

		if( isset($this->apiDataArr['specials_type']) ) 
		{
			// these values are all uppercase.  just form value to upper
			$this->whereArr[] = "UPPER(specials_type)='".addslashes(strtoupper($this->apiDataArr['specials_type']))."'";
		}
		else
		{
			$this->whereArr[] = "specials_type='SALES'";
		}

		if( isset($this->apiDataArr['store_letter']) ) 
		{
			$this->whereArr[] = "UPPER(store_letter)='".addslashes(strtoupper($this->apiDataArr['store_letter']))."'";
		}

		if( isset($this->apiDataArr['store_id']) ) 
		{
			// these values are all uppercase.  just form value to upper
			$this->whereArr[] = "UPPER(store_letter)=(SELECT UPPER(letter) from ".$this->storesTblName." where id='".addslashes($this->apiDataArr['store_id'])."')";
		}

		if( isset($this->apiDataArr['active']) ) 
		{
			$this->whereArr[] = "active='".addslashes($this->apiDataArr['active'])."'";
		}
		else
		{
			$this->whereArr[] = "active='1'";
		}

		if( isset($this->apiDataArr['body_style']) ) 
		{
			$this->whereArr[] = "body_style='".addslashes($this->apiDataArr['body_style'])."'";
		}

		if( isset($this->apiDataArr['year']) ) 
		{
			$this->whereArr[] = "active='".addslashes($this->apiDataArr['year'])."'";
		}

		if( isset($this->apiDataArr['make_id']) ) 
		{
			$this->whereArr[] = "make_id='".addslashes($this->apiDataArr['make_id'])."'";
		}

		if( isset($this->apiDataArr['make_name']) ) 
		{
			$this->whereArr[] = "make_id=(SELECT id from ".$this->makesTblName." where name='".addslashes($this->apiDataArr['make_name'])."')";
		}

		if( isset($this->apiDataArr['model_id']) ) 
		{
			$this->whereArr[] = "model_id='".addslashes($this->apiDataArr['model_id'])."'";
		}

		if( isset($this->apiDataArr['model_name']) ) 
		{
			$this->whereArr[] = "model_id=(SELECT id from ".$this->modelsTblName." where name='".addslashes($this->apiDataArr['model_name'])."')";
		}

		if( isset($this->apiDataArr['order_by']) ) 
		{
			$this->apiSqlQuery = str_replace("[ORDER_FIELD]","'".addslashes($this->apiDataArr['order_by'])."'",$this->apiSqlQuery);
			if( isset($this->apiDataArr['order_direction']) ) 
			{
				$this->apiSqlQuery = str_replace("[ORDER_DIRECTION]","'".addslashes($this->apiDataArr['order_direction'])."'",$this->apiSqlQuery);
			}
			else 
			{
				$this->apiSqlQuery = str_replace("[ORDER_DIRECTION]","",$this->apiSqlQuery);
			} 
		}	
		else
		{
			$this->apiSqlQuery = str_replace("[ORDER_FIELD]","id",$this->apiSqlQuery);
			$this->apiSqlQuery = str_replace("[ORDER_DIRECTION]","",$this->apiSqlQuery);
		}

		if( isset($this->apiDataArr['limit']) ) {
			$this->whereArr[] = "limit '".addslashes($this->apiDataArr['limit'])."')";
		}
				
		if( count($this->whereArr) > 0 )
		{
			$this->apiSqlWhere = implode(" AND ", $this->whereArr);
		}

		$this->apiSqlQuery = str_replace("[WHERE]","WHERE ".$this->apiSqlWhere,$this->apiSqlQuery);

		$this->apiSqlResult = $this->_run_query($this->apiSqlQuery);

		//$this->apiTotalSpecialsFound = mysql_num_rows($this->apiSqlResult);\
		$this->apiTotalSpecialsFound = mysqli_num_rows($this->apiSqlResult);
	}

	function getNcsSearchReponse()
	{		
		$this->retXml = "";
		$this->retXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
		$this->retXml .= "<ncs version=\"1.0\">\r\n";
		$this->retXml .= "	<specials total=\"" . $this->apiTotalSpecialsFound . "\">\r\n";
		if( $this->apiTotalSpecialsFound > 0 ) 
		{
			$storesArr = array();
			$sql1 = "SELECT * FROM ".$this->storesTblName." WHERE hasSales='1' order by letter";
			$result1 = $this->_run_query($sql1);
			//if( mysql_num_rows($result1) > 0 ) 
			if( mysqli_num_rows($result1) > 0 )
			{
				//while( $row1 = mysql_fetch_array($result1) ) 
				while( $row1 = mysqli_fetch_array($result1) )
				{
					$storeLetter = strtolower(stripslashes($row1['letter']));
					$storesArr[$storeLetter] = $row1;
				}
			}

			//while( $row = mysql_fetch_array($this->apiSqlResult) ) 
			while( $row = mysqli_fetch_array($this->apiSqlResult) )
			{
				$vStoreLetter2 = strtolower(stripslashes($row['store_letter']));
				$this->retXml .= "<special id=\"" . stripslashes($row['webspecials_id']) . "\" post_id=\"" . stripslashes($row['post_id']) . "\" store_letter=\"" . stripslashes($row['store_letter']) . "\" ordering=\"".stripslashes($row['ordering'])."\" leads=\"\">\r\n";
				$this->retXml .= "	<title>" . $this->cD1 . stripslashes($row['title']) . $this->cD2 . "</title>\r\n";
				$this->retXml .= "	<vehicle_image>" . $this->cD1 . stripslashes($row['vehicle_image']) . $this->cD2 . "</vehicle_image>\r\n";
				$this->retXml .= "	<year>" . stripslashes($row['year']) . "</year>\r\n";
				$this->retXml .= "	<make id=\"" . stripslashes($row['make_id']) . "\">".fix(get_make_name($row['make_id']))."</make>\r\n";
				$this->retXml .= "	<model id=\"" . stripslashes($row['model_id']) . "\">" . $this->cD1 . stripslashes(get_model_name($row['model_id'])) . $this->cD2 . "</model>\r\n";
				$this->retXml .= "	<body_style>" . $this->cD1 . stripslashes($row['body_style_code']) . $this->cD2 . "</body_style>\r\n";
				$this->retXml .= "	<trim_level>" . $this->cD1. stripslashes($row['trim_level']) . $this->cD2 . "</trim_level>\r\n";
				$this->retXml .= "	<tagline>" . $this->cD1 . stripslashes($row['tagline']) . $this->cD2 . "</tagline>\r\n";
				$this->retXml .= "	<featured_special>" . $this->cD1 . stripslashes($row['featured_special']) . $this->cD2 . "</featured_special>\r\n";
				$this->retXml .= "	<stock_number>" . stripslashes($row['stock_number']) . "</stock_number>\r\n";
				$this->retXml .= "	<vin_number>" . stripslashes($row['vin_number']) . "</vin_number>\r\n";
				$this->retXml .= "	<p_text>" . $this->cD1 . stripslashes($row['p_text']) . $this->cD2 ."</p_text>\r\n";
				$this->retXml .= "	<description_text>" . $this->cD1 . stripslashes($row['description_text']) . $this->cD2 ."</description_text>\r\n";
				$this->retXml .= "	<disclaimer_text>" . $this->cD1 . stripslashes($row['disclaimer_text']) . $this->cD2 ."</disclaimer_text>\r\n";
				$this->retXml .= "	<zero_down_lease_disclaimer_text>" . $this->cD1 . stripslashes($row['zero_down_lease_disclaimer_text']) . $this->cD2 ."</zero_down_lease_disclaimer_text>\r\n";
				$this->retXml .= "	<alt_link_url>" . $this->cD1 . stripslashes($row['alt_link_url']) . $this->cD2 ."</alt_link_url>\r\n";
				$this->retXml .= "	<lease_price>" . stripslashes($row['lease_price']) . "</lease_price>\r\n";
				$this->retXml .= "	<lease_extras>" . $this->cD1 . stripslashes($row['lease_extras']) . $this->cD2 ."</lease_extras>\r\n";
				$this->retXml .= "	<lease_term>" . stripslashes($row['lease_term']) . "</lease_term>\r\n";
				$this->retXml .= "	<zero_down_lease_price>" . stripslashes($row['zero_down_lease_price']) . "</zero_down_lease_price>\r\n";
				$this->retXml .= "	<zero_down_lease_term>" . stripslashes($row['zero_down_lease_term']) . "</zero_down_lease_term>\r\n";
				$this->retXml .= "	<single_lease_price>" . stripslashes($row['single_lease_price']) . "</single_lease_price>\r\n";
				$this->retXml .= "	<single_lease_term>" . stripslashes($row['single_lease_term']) . "</single_lease_term>\r\n";
				$this->retXml .= "	<single_lease_miles>" . stripslashes($row['single_lease_miles']) . "</single_lease_miles>\r\n";
				$this->retXml .= "	<buy_price>" . stripslashes($row['buy_price']) . "</buy_price>\r\n";
				$this->retXml .= "	<price_with_owner_loyalty>" . stripslashes($row['price_with_owner_loyalty']) . "</price_with_owner_loyalty>\r\n";
				$this->retXml .= "	<price_with_lease_conquest>" . stripslashes($row['price_with_lease_conquest']) . "</price_with_lease_conquest>\r\n";
				$this->retXml .= "	<custom_price_label>" . $this->cD1 . stripslashes($row['custom_price_label']) . $this->cD2 ."</custom_price_label>\r\n";
				$this->retXml .= "	<custom_price_val>" . stripslashes($row['custom_price_val']) . "</custom_price_val>\r\n";
				$this->retXml .= "	<mpg>" . stripslashes($row['mpg']) . "</mpg>\r\n";
				$this->retXml .= "	<msrp>" . stripslashes($row['msrp']) . "</msrp>\r\n";
				$this->retXml .= "	<available_apr>" . stripslashes($row['available_apr']) . "</available_apr>\r\n";
				$this->retXml .= "	<apr_text>" . $this->cD1 . stripslashes($row['apr_text']) . $this->cD2 ."</apr_text>\r\n";
				$this->retXml .= "	<save_up_to_amount>" . stripslashes($row['save_up_to_amount']) . "</save_up_to_amount>\r\n";
				$this->retXml .= "	<brand_logo_url>" . $this->cD1 . stripslashes($row['brand_logo_url']) . $this->cD2 ."</brand_logo_url>\r\n";
				$this->retXml .= "	<ddc_get_special_url>" . $this->cD1 . stripslashes($row['ddc_get_special_url']) . $this->cD2 ."</ddc_get_special_url>\r\n";
				$this->retXml .= "	<landing_page_url>" . $this->cD1 . stripslashes($row['landing_page_url']) . $this->cD2 ."</landing_page_url>\r\n";
				$this->retXml .= "	<ordering>" . stripslashes($row['ordering']) . "</ordering>\r\n";
				$sql2 = "SELECT * FROM ".$this->specialPricingTblName." WHERE special_id='".$row['webspecials_id']."' AND active='1' ORDER BY ordering";
				$result2 = $this->_run_query($sql2);
				//$vTotPricingLines = mysql_num_rows($result2);
				$vTotPricingLines = mysqli_num_rows($result2);
				$this->retXml .= "	<pricing total_lines=\"" . $vTotPricingLines . "\">\r\n";
				if( $vTotPricingLines > 0 ) 
				{
					//while( $row2 = mysql_fetch_array($result2) )
					while( $row2 = mysqli_fetch_array($result2) )
					{
						$vName = stripslashes($row2['name']);
						$vName = str_replace("&", "and", $vName);
						$this->retXml .= "		<item name=\"" . $vName . "\" ordering=\"" . stripslashes($row2['ordering']) . "\" price=\"" . stripslashes($row2['price']) . "\"></item>\r\n";
					}
				}

				$this->retXml .= "	</pricing>\r\n";
				$this->retXml .= "	<store_id>" . stripslashes($storesArr[$vStoreLetter2]['id']) . "</store_id>\r\n";
				$this->retXml .= "	<store_name>" . stripslashes($storesArr[$vStoreLetter2]['storename']) . "</store_name>\r\n";
				$this->retXml .= "	<store_address>" . stripslashes($storesArr[$vStoreLetter2]['address1']) . "</store_address>\r\n";
				$this->retXml .= "	<store_city>" . stripslashes($storesArr[$vStoreLetter2]['city']) . "</store_city>\r\n";
				$this->retXml .= "	<store_state>" . stripslashes($storesArr[$vStoreLetter2]['state']) . "</store_state>\r\n";
				$this->retXml .= "	<store_zip>" . stripslashes($storesArr[$vStoreLetter2]['zip']) . "</store_zip>\r\n";
				$this->retXml .= "	<store_sales_phone>" . stripslashes($storesArr[$vStoreLetter2]['ddc_sales_tracking_phone']) . "</store_sales_phone>\r\n";			
				$this->retXml .= "	<store_service_phone>" . stripslashes($storesArr[$vStoreLetter2]['servicephone']) . "</store_service_phone>\r\n";			
				$this->retXml .= "</special>\r\n";
			}
		}
		$this->retXml .= "	</specials>\r\n";
		$this->retXml .= "	</ncs>\r\n";
	}

	function sync_wp_web_specials()
	{
		if( isset($this->apiDataArr['wp_sp_ids']) && isset($this->apiDataArr['wp_sp_domain']) ) 
		{
			$spArr = array();
			$spArr = explode("|",$this->apiDataArr['wp_sp_ids']);
			if( count($spArr) == 0 )
			{
				$this->errorsArr[] = "Error [no data to process]";
			}
			else
			{
				$this->retXml = "";
				$this->retXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
				$this->retXml .= "<ncs version=\"1.0\">\r\n";
				$this->retXml .= "	<sync>\r\n";
				foreach($spArr AS $spLine)
				{
					$innerArr = array();
					$innerArr = explode("=",$spLine);
					$postId = $innerArr[0];			
					$specialId = $innerArr[1];
					$this->apiSqlQuery = "SELECT * FROM ".$this->syncTblName." 
										  WHERE web_special_id='".addslashes($specialId)."' 
										  AND domain_name='".addslashes($this->apiDataArr['wp_sp_domain'])."' 
										  LIMIT 1";
					$result = $this->_run_query($this->apiSqlQuery);
					//$tot = mysql_num_rows( $result );
					$tot = mysqli_num_rows( $result );
					if( $tot == 0 )
					{
						$this->apiSqlQuery = "INSERT INTO ".$this->syncTblName." (id, web_special_id, post_id, domain_name) VALUES 
												('', '".addslashes($specialId)."', '".addslashes($postId)."', 
												'".addslashes($this->apiDataArr['wp_sp_domain'])."')";
						$result1 = $this->_run_query($this->apiSqlQuery);
						// $this->retXml .= "	<message>Added Special [post=".$postId."] [special=".$specialId."] [".$this->apiDataArr['wp_sp_domain']."]</error>\r\n";
					}
					else
					{
						//$row = mysql_fetch_array($result);
						$row = mysqli_fetch_array($result);
						if( ( $tot == 1 ) && ( $postId == stripslashes($row['post_id']) ) )
						{
							/* no need to update anything */
						}
						else
						{
							$this->apiSqlQuery = "UPDATE ".$this->syncTblName." 
												  SET post_id='".addslashes($postId)."'
												  WHERE web_special_id='".addslashes($specialId)."' 
												  AND domain_name='".addslashes($this->apiDataArr['wp_sp_domain'])."' 
												  LIMIT 1";
							$result2 = $this->_run_query($this->apiSqlQuery);
							// $this->retXml .= "	<message>Updated Special [post=".$postId."] [special=".$specialId."] [".$this->apiDataArr['wp_sp_domain']."]</error>\r\n";
						}
					}
				}
				$this->retXml .= "	<message>Success!</message>\r\n";
				$this->retXml .= "	</sync>\r\n";
				$this->retXml .= "	</ncs>\r\n";
			}
		}
		else
		{
			if( !isset($this->apiDataArr['wp_sp_ids']) )
			{
				$this->errorsArr[] = "Error [no data to process]";
			}

			if ( !isset($this->apiDataArr['wp_sp_domain']) ) 
			{
				$this->errorsArr[] = "Error [no domain]";
			}
		}
	}

	function getApiErrorReponse()
	{		
		$this->retErrorXml = "";
		$this->retErrorXml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
		$this->retErrorXml .= "<ncs version=\"1.0\">\r\n";
		$this->retErrorXml .= "	<errors total=\"" . count($this->errorsArr) . "\">\r\n";
		if( $this->errorsArr > 0 ) 
		{
			foreach($this->errorsArr AS $errorMessage ) 
			{
				$this->retErrorXml .= "	<error>".$errorMessage."</error>\r\n";
			}
		}
		$this->retErrorXml .= "	</errors>\r\n";
		$this->retErrorXml .= "	</ncs>\r\n";
	}

}

?>
