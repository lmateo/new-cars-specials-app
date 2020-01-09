<?php



require_once NCS_PLUGIN_INC_DIR . '/store-data.php';



$storeLetter = get_option('quirk_store');

global $storeData;

/*===============================================================

				Dealership Info

  ===============================================================*/

	$storeName = $storeData[$storeLetter]['store_name'];

	$storeAddr = $storeData[$storeLetter]['store_address'];

	$storeCity = $storeData[$storeLetter]['store_city'];

	$storeSt = $storeData[$storeLetter]['store_state_abr'];

	$storeState = $storeData[$storeLetter]['store_state'];

	$storeZip = $storeData[$storeLetter]['store_zip'];

	$storePhone = $storeData[$storeLetter]['store_phone'];

	$leaseLPString = $storeData[$storeLetter]['lease_lp_string'];

	$geoString = $storeData[$storeLetter]['geo_string'];

	$quirkStoreLetter = $storeLetter;

	$taxonomySlug = $storeData[$storeLetter]['tax_slug'];

/*===============================================================

				Fix Phone Number

  ===============================================================*/

function strip_phone( $pnum ) {

	$pattern = "/[-()\.\s]+/";

	$replace = "";

	$new_phone = preg_replace($pattern, $replace, $pnum);

	return $new_phone;

}

function fix_phone( $num ) {

	$phone = strip_phone($num);

	$num1 = substr($phone, 0, 3);

	$num2 = substr($phone, 3, 3);

	$num3 = substr($phone, 6, 4);

	$returnNum = "(".$num1.") ".$num2."-".$num3;

	return $returnNum;

}



/*===============================================================

				Post exists by slug?

  ===============================================================*/

function ncs_post_exists($title) {

	global $wpdb;



	$post_name = wp_unslash( sanitize_post_field( 'post_name', $title, 0, 'db' ) );



	$query = "SELECT ID FROM $wpdb->posts WHERE 1=1";

	$args = array();



	if ( !empty ( $title ) ) {

		$query .= ' AND post_name = %s';

		$args[] = $post_name;

	}



	if ( !empty ( $args ) )

		return (int) $wpdb->get_var( $wpdb->prepare($query, $args) );



		return 0;

}

/*===============================================================

				NCS create/modify settings file

  ===============================================================*/

function ncs_save_settings( $arr ){

	global $wpdb;

	$ret = '';

	update_option('ncs_private_key', $arr['ncs_private_key']);

	/*update_option('specials_page_name', $arr['specials_page_name']);

	update_option('ty_page_name', $arr['ty_page_name']);*/
	
	update_option('thank_you_video', $arr['thank_you_video']);
	
	update_option('contact_directions_link', $arr['contact_directions_link']);
	
	update_option('ncs_about_link', $arr['ncs_about_link']);
	
	update_option('value_trade_link', $arr['value_trade_link']);
	
	update_option('dance_video_text', $arr['dance_video_text']);
	
	update_option('dance_video_link', $arr['dance_video_link']);
	
	update_option('ncs_optional_store_phone', $arr['ncs_optional_store_phone']);

	update_option('crm_lead_email', $arr['crm_lead_email']);

	update_option('quirk_store', $arr['quirk_store']);

	$ret = ncs_validate_key();

	return $ret;

}



function ncs_validate_key() {

    $ret = '0';
    
    $vCommands = '';

	$vPrivateKey = get_option('ncs_private_key');

	if( $vPrivateKey != '' ) {

		$vCommands .= "public_key=".$vPrivateKey;

		$vCommands .= "&task=AUTHCHECK";

		$vCommands .= "&wp_sp_domain=".$_SERVER['SERVER_NAME'];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://qinventory.quirkspecials.com/quirk-inventory-site-dev/api2/v2");

		curl_setopt($ch, CURLOPT_POST, 1);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $vCommands);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

		$ret = curl_exec($ch);

		curl_close($ch);

	}



	  // what's wp's date function?

	  // save last auth datetime and response

	  // update_option('ncs_auth_resp', $result);  	

      // update_option('ncs_last_auth', date('Y-m-d H:i:s'));	



	return $ret;

}	

/*===============================================================

				NCS create specials as posts after AJAX call

  ===============================================================*/

function ncs_process_update() {

	global $storeLetter;

	$upload_dir = wp_upload_dir();

	$totAddedPosts = 0; 	

	$totUpdatedPosts = 0; 	

	$specialsPostsIdsArr[] = array();	

	// carry a string of the script output to return to ajax using print/echo 

	$ncsResponse = "Checking NCS ...<br><br>";

	if( !ncs_validate_key() )

	{

		$ncsResponse .= " Not Authorized<br>";

	}

	else

	{

		//

		//Get all post ids with 'ncs_special' post type

		//

		

		$post_ids = get_posts(array(

			'numberposts'   => -1, // get all posts.

			'meta_key'		=> 'special_id',

			'post_type'		=> 'ncs_special',  //query by custom post type 'ncs_special'

			'fields'        => 'ids', // Only get post IDs

		));

	

		//

		//  Extract and process NCS spreadsheet file  

		//
		if ($storeLetter === 't') {			
			$Letterarray = array('c', 'd', 'e', 'j', 'm', 'v', 'w', 'k', 'f', 'h', 'g', 'n', 'p', 's', 'y');
			foreach ($Letterarray as $storeLetterbulk) {
				$vPrivateKey = get_option('ncs_private_key');
		
				$vCommands = "public_key=".$vPrivateKey;
		
				$vCommands .= "&task=NCS_SEARCH";
		
				$vCommands .= "&wp_sp_domain=".$_SERVER['SERVER_NAME'];
		
				$vCommands .= "&active=1";
		
				$vCommands .= "&specials_type=SALES";
		
				$vCommands .= "&store_letter=".$storeLetterbulk;
		
				
		
				$ch = curl_init();
		
				curl_setopt($ch, CURLOPT_URL, "http://qinventory.quirkspecials.com/quirk-inventory-site-dev/api2/v2");
		
				curl_setopt($ch, CURLOPT_POST, 1);
		
				curl_setopt($ch, CURLOPT_POSTFIELDS, $vCommands);
		
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
				$result = curl_exec($ch);
		
				curl_close($ch);
		
				if( trim($result) == "" ) {
		
					$ncsResponse .= " Error Loading File<br>";
		
				}
		
				else {
		
					$ncsPublicPath = $upload_dir['basedir'] . "/ncs.xml";
		
					$ncsResponse .= "Started Import (".date("m-d-Y g:i a").")<br><br>";
		
					if( $fp = fopen($ncsPublicPath, "w") or die("Couldn't open ".$ncsPublicPath) ) 
		
					{
		
						fwrite($fp, trim($result));
		
						fclose($fp);
		
					}
		
		
		
					if( file_exists($ncsPublicPath) )
		
					{	
		
						$feed = simplexml_load_file($ncsPublicPath);
		
						$total = $feed->specials->attributes()->total;
		
						if( $total > 0 && $total != '' ) 
		
						{
		
							$number_of_posts = array();
		
							foreach($feed->specials->special as $special) {
		
								$postTags = array();
		
								if( !$special->{'year'} == '' ) { $postTags[] = $special->{'year'}; }
		
								if( !$special->{'make'} == '' ) { $postTags[] = $special->{'make'}; }
		
								if( !$special->{'model'} == '' ) { $postTags[] = $special->{'model'}; }
		
								if( !$special->{'body_style'} == '' ) { $postTags[] = $special->{'body_style'}; }
		
								/*   Tags Removed
		
								if( !$special->{'trim_level'} == '' ) { $postTags[] = $special->{'trim_level'}; }
		
								if( !$special->{'mpg'} == '' ) { $postTags[] = "mpg ".$special->{'mpg'}; }
		
								if( !$special->{'available_apr'} == '' ) { $postTags[] = "apr ".doubleval($special->{'available_apr'}); }
		
								*/
		
								$postName = $special->{'year'} . "-". $special->{'make'} . "-" . $special->{'model'} . "-" . $special->{'trim_level'} . "-(".$special->attributes()->id.")";
		
								$postTitle = $special->{'year'} . " ". $special->{'make'} . " " . $special->{'model'} . " " . $special->{'trim_level'};
		
		
		
								/* Check to see if the post already exists */
		
								$pId = '0';
		
								$tempPostId = (string)$special->attributes()->post_id;
		
								if( in_array($tempPostId,$post_ids) ) {
		
									$pId = $tempPostId; /* looks like the post is still here */
		
								}
		
								
		
								// check to make sure post is an ncs post type here.  !!
		
								// $pId = ncs_post_exists( $postName );
		
								
		
								if( $pId == '0' )
		
								{
		
									//
		
									//  The post was not found, create new post args
		
									//
		
									$post = array(
		
										'post_content'   => '',
		
										'post_name'      => $postName,
		
										'post_title'     => $postTitle,
		
										'post_status'    => 'publish',
		
										'post_type'      => 'ncs_special',
		
										'menu_order'     => $special->attributes()->ordering,
		
										'post_date'      => date("Y-m-d H:i:s"),
		
										'post_date_gmt'  => date("Y-m-d H:i:s"),
		
										//  'tags_input'     => implode(",", $postTags),
		
										'post_category'  => array($special->{'make'})
		
									);
		
								}
		
								else {
		
									//
		
									//The post was found, update the existing post args by ID
		
									//
		
									$post = array(
		
										'ID'             => $pId,
		
										'post_content'   => '',
		
										'post_name'      => $postName,
		
										'post_title'     => $postTitle,
		
										'post_status'    => 'publish',
		
										'post_type'      => 'ncs_special',
		
										'menu_order'     => $special->attributes()->ordering,
		
										// 'tags_input'     => implode(",", $postTags),
		
										'post_category'  => array($special->{'make'})
		
									);
		
		
		
									/*
		
										'post_date'      => date("Y-m-d H:i:s"),
		
										'post_date_gmt'  => date("Y-m-d H:i:s"),
		
									*/
		
		
		
								}
		
								/*
		
								 *  Create/Update the post with given args
		
								 *  - if ID field is set in post array then wp_insert_post will attempt to update ( or will insert new post )
		
								 *  - this could potentially change other posts in the system.  a better check to make sure post is ncs is needed!
		
								 */
		
								
		
								$post_id = wp_insert_post( $post, $wp_error );
		
								if( $pId == '0' ) {
		
									$pId = $post_id;
		
									$ncsResponse .= "<br>Added ".$special->{'year'}." ".$special->{'make'}." ".$special->{'model'}." [". (string)$special->attributes()->id ."]";
		
									$totAddedPosts++; 	
		
								}
		
								else
		
								{
		
									$ncsResponse .= "<br>Updated ".$special->{'year'}." ".$special->{'make'}." ".$special->{'model'}." [". (string)$special->attributes()->id ."]";
		
									$totUpdatedPosts++;
		
								}
		
								//  update_post_meta($pId, $meta_key, $meta_value, $isUniqueTrueFalse);
		
								
		
								/*======================================================================
		
								
		
								needs: store info: name, address, letter code, city, state, zip, phone, email, etc.
		
								additional: total of this model at store, total of this model for group
		
								
		
								  ======================================================================*/
		
								update_post_meta($pId, "model", (string)$special->{'model'}); 
		
								update_post_meta($pId, "year", (string)$special->{'year'}); 
		
								update_post_meta($pId, "vehicle_image", (string)$special->{'vehicle_image'}); 
		
								update_post_meta($pId, "special_id", (string)$special->attributes()->id); 
		
								update_post_meta($pId, "title", (string)$special->{'title'}); 
		
								update_post_meta($pId, "make_name", (string)$special->{'make'}); // rename
		
								update_post_meta($pId, "make_id", (string)$special->make->attributes()->id); 
		
								update_post_meta($pId, "model_name", (string)$special->{'model'}); // rename
		
								update_post_meta($pId, "model_id", (string)$special->model->attributes()->id); 
		
								update_post_meta($pId, "body_style", (string)$special->{'body_style'}); 
		
								update_post_meta($pId, "trim_level", (string)$special->{'trim_level'}); 
		
								update_post_meta($pId, "tagline", (string)$special->{'tagline'}); 
		
								update_post_meta($pId, "stock_number", (string)$special->{'stock_number'}); 
		
								update_post_meta($pId, "vin_number", (string)$special->{'vin_number'}); 
		
								update_post_meta($pId, "p_text", (string)$special->{'p_text'}); // not being used ... i think
		
								update_post_meta($pId, "description_text", (string)$special->{'description_text'}); // not be used ( was for used special comments )
		
								update_post_meta($pId, "default_disclaimer_text", (string)$special->{'disclaimer_text'}); // rename
		
								update_post_meta($pId, "zero_down_lease_disclaimer_text", (string)$special->{'zero_down_lease_disclaimer_text'}); 
		
								update_post_meta($pId, "inventory_url", (string)$special->{'alt_link_url'}); // rename
		
								update_post_meta($pId, "lease_down_payment", (string)$special->{'lease_extras'}); // rename
		
								update_post_meta($pId, "lease_term", (string)$special->{'lease_term'}); 								
								
								update_post_meta($pId, "single_lease_term", (string)$special->{'single_lease_term'});
								
								update_post_meta($pId, "single_lease_miles", (string)$special->{'single_lease_miles'});
		
								update_post_meta($pId, "zero_down_lease_term", (string)$special->{'zero_down_lease_term'}); 
		
								update_post_meta($pId, "apr_text", (string)$special->{'apr_text'});	
		
								update_post_meta($pId, "mpg", (string)$special->{'mpg'}); 
								
								update_post_meta($pId, "brand_logo_url", (string)$special->{'brand_logo_url'});
		
								update_post_meta($pId, "ddc_get_special_url", (string)$special->{'ddc_get_special_url'}); 
		
								update_post_meta($pId, "landing_page_url", (string)$special->{'landing_page_url'}); 
								
								update_post_meta($pId, "ordering", (string)$special->{'ordering'});
		
								update_post_meta($pId, "store_id", (string)$special->{'store_id'});
		
								update_post_meta($pId, "service_phone", (string)$special->{'store_service_phone'});
		
								update_post_meta($pId, "custom_price_label", (string)$special->{'custom_price_label'});
		
								update_post_meta($pId, "featured_special", (string)$special->{'featured_special'});
		
								
		
								$dealTypeTax = array();
		
		
		
								if( $special->{'lease_price'} != '' && $special->{'lease_price'} != '0'){
		
									update_post_meta($pId, "lease_price", doubleval($special->{'lease_price'}));
		
									$dealTypeTax[] = "lease";
		
								}
		
								else{
		
									delete_post_meta($pId, "lease_price", "");
		
								}
								
								if( $special->{'single_lease_price'} != '' && $special->{'single_lease_price'} != '0'){
										
									update_post_meta($pId, "single_lease_price", doubleval($special->{'single_lease_price'}));
										
									$dealTypeTax[] = "single pay";
										
								}
								
								else{
										
									delete_post_meta($pId, "single_lease_price", "");
										
								}
		
								if( $special->{'zero_down_lease_price'} != '' && $special->{'zero_down_lease_price'} != '0'){
		
									update_post_meta($pId, "zero_down_lease_price", doubleval($special->{'zero_down_lease_price'}));
		
									$dealTypeTax[] = "zero down";
		
								}
		
								else{
		
									delete_post_meta($pId, "zero_down_lease_price", "");
		
								}
		
								if( $special->{'buy_price'} != '' && $special->{'buy_price'} != '0'){
		
									update_post_meta($pId, "buy_price", doubleval($special->{'buy_price'}));
		
									$dealTypeTax[] = "purchase";
		
								}
		
								else{
		
									delete_post_meta($pId, "buy_price", "");
		
								}
		
								if( $special->{'available_apr'} != '' ){
		
									update_post_meta($pId, "available_apr", doubleval($special->{'available_apr'}));
		
								}
		
								else{
		
									delete_post_meta($pId, "available_apr", "");
		
								}
		
								if( $special->{'msrp'} != '' ){
		
									update_post_meta($pId, "msrp", doubleval($special->{'msrp'}));
		
								}
		
								else{
		
									delete_post_meta($pId, "msrp", "");
		
								}
		
								if( $special->{'save_up_to_amount'} != '' ){
		
									update_post_meta($pId, "save_up_to_amount", doubleval($special->{'save_up_to_amount'}));
		
								}
		
								else{
		
									delete_post_meta($pId, "save_up_to_amount", "");
		
								}
		
		                        if( $special->{'custom_price_val'} != '' ){
		
		                            update_post_meta($pId, "custom_price_val", doubleval($special->{'custom_price_val'}));
		
		                        }
		
		                        else{
		
		                            delete_post_meta($pId, "custom_price_val", "");
		
		                        }
		
										
		
								$vTotalPricingLines = $special->pricing->attributes()->total_lines;
		
								update_post_meta($pId, "total_pricing_lines", (string)$vTotalPricingLines); 
		
								$pricingCounter = 0;
		
								if( $vTotalPricingLines > 0 )
		
								{
		
									foreach($special->pricing->item as $item) 
		
									{
		
										if( $item->attributes()->price != '' )
		
										{
		
											update_post_meta($pId, "pricing_".$pricingCounter."_name", (string)$item->attributes()->name);
		
											update_post_meta($pId, "pricing_".$pricingCounter."_value", doubleval($item->attributes()->price));
		
										}
		
										else
		
										{
		
											delete_post_meta($pId, "pricing_".$pricingCounter."_name", (string)$item->attributes()->name);
		
											delete_post_meta($pId, "pricing_".$pricingCounter."_value", ""); 
		
										}
		
										$pricingCounter++;
		
									}
		
								}
		
								wp_set_post_terms( $pId, $special->{'year'}, 'model_year' );
		
								wp_set_post_terms( $pId, $special->{'make'}, 'make' );
		
								wp_set_post_terms( $pId, $special->{'model'}, 'model' );
		
								wp_set_post_terms( $pId, ucwords(strtolower($special->{'body_style'})), 'body_style' );
		
								wp_set_post_terms( $pId, implode(",", $dealTypeTax), 'deal_type', false);	 			
		
								
		
								$specialsPostsIdsArr[$pId] = (string)$special->attributes()->id;
		
								$number_of_posts[] = $pId;
		
							}
		
		
		
							$ncsResponse .= "<br>Success.<br>";
		
							$ncsResponse .= "Added ".$totAddedPosts." new specials with the category \"Special\".<br>";
		
							$ncsResponse .= "Updated ". $totUpdatedPosts." new specials with the category \"Special\".<br>";
		
		
		
							$ncsResponse .= "<br><br>Syncing Some info back to NCS Server...<br>";
		
		
		
							/* sync back post ids */
		
							$vPrivateKey = get_option('ncs_private_key');
		
							$vCommands = "public_key=".$vPrivateKey;
		
							$vCommands .= "&task=NCS_SYNC";
		
							$vCommands .= "&wp_sp_domain=".$_SERVER['SERVER_NAME'];
		
							$vCommands .= "&wp_sp_ids=".http_build_query($specialsPostsIdsArr, '', '|');
		
							$ch = curl_init(); ;
		
							curl_setopt($ch, CURLOPT_URL, "http://qinventory.quirkspecials.com/quirk-inventory-site-dev/api2/v2");
		
							curl_setopt($ch, CURLOPT_POST, 1);
		
							curl_setopt($ch, CURLOPT_POSTFIELDS, $vCommands);
		
							curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
							$result = curl_exec($ch);
		
							curl_close($ch);
		
							if( trim($result) == "" ) {
		
								$ncsResponse .= " Error Retrieving Sync Response<br>";
		
							}
		
							else
		
							{
		
								$ncsResponse .= "<br><br>".trim($result)."<br>";
		
							}
		
							$ncsResponse .= "<br>...done with NCS Server.<br>";
		
		
		
		
		
							//$ncsResponse .= "<br><br>Cleaning Up....<br>";
		
							/* set specials that weren't in the feed to inactive */
		
							// or should we only do that if selected in options?
		
							/*foreach( (array) $post_ids as $post_id ) {
		
								if( !array_key_exists($post_id,$specialsPostsIdsArr) )
		
								{
		
									// update_post_meta($post_id, "active", "0", true);
		
									$post_del = get_post($post_id);
		
									if ( !wp_delete_post($post_id, true) )
		
									{
		
										$ncsResponse .= "Error deleting Special Post " . $post_id."<br>";
		
									}
		
									else
		
									{
		
										$ncsResponse .= "Special Post " . $post_id." was set deleted<br>";
		
									}
		
								}
		
							}		
		
							$ncsResponse .= "<br>Done Cleaning Up.<br>";*/				
		
						}
		
						else 
		
						{
		
							$ncsResponse .= "<br>".$result."<br>";
		
						}
		
		
		
						$ncsResponse .= "<br>Finished Import (".date("m-d-Y g:i a").")";
		
						unlink($ncsPublicPath);
		
					}
		
					else 
		
					{
		
						$ncsResponse .= "<br>XML File Not Found<br>";
		
						$ncsResponse .= "<br>".$result."<br>";
		
					}
		
				}
			}
			$ncsResponse .= "<br><br>Cleaning Up....<br>";
			
			/* set specials that weren't in the feed to inactive */
			
			// or should we only do that if selected in options?
			
			foreach( (array) $post_ids as $post_id ) {
					
				if( !array_key_exists($post_id,$specialsPostsIdsArr) )
					
				{
			
					// update_post_meta($post_id, "active", "0", true);
			
					$post_del = get_post($post_id);
			
					if ( !wp_delete_post($post_id, true) )
			
					{
							
						$ncsResponse .= "Error deleting Special Post " . $post_id."<br>";
							
					}
			
					else
			
					{
							
						$ncsResponse .= "Special Post " . $post_id." was set deleted<br>";
							
					}
			
				}
					
			}
			
			$ncsResponse .= "<br>Done Cleaning Up.<br>";
		} else {
			$vPrivateKey = get_option('ncs_private_key');
			
			$vCommands = "public_key=".$vPrivateKey;
			
			$vCommands .= "&task=NCS_SEARCH";
			
			$vCommands .= "&wp_sp_domain=".$_SERVER['SERVER_NAME'];
			
			$vCommands .= "&active=1";
			
			$vCommands .= "&specials_type=SALES";
			
			$vCommands .= "&store_letter=".$storeLetter;
			
			
			
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, "http://qinventory.quirkspecials.com/quirk-inventory-site-dev/api2/v2");
			
			curl_setopt($ch, CURLOPT_POST, 1);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $vCommands);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			
			$result = curl_exec($ch);
			
			curl_close($ch);
			
			if( trim($result) == "" ) {
			
				$ncsResponse .= " Error Loading File<br>";
			
			}
			
			else {
			
				$ncsPublicPath = $upload_dir['basedir'] . "/ncs.xml";
			
				$ncsResponse .= "Started Import (".date("m-d-Y g:i a").")<br><br>";
			
				if( $fp = fopen($ncsPublicPath, "w") or die("Couldn't open ".$ncsPublicPath) )
			
				{
			
					fwrite($fp, trim($result));
			
					fclose($fp);
			
				}
			
			
			
				if( file_exists($ncsPublicPath) )
			
				{
			
					$feed = simplexml_load_file($ncsPublicPath);
			
					$total = $feed->specials->attributes()->total;
			
					if( $total > 0 && $total != '' )
			
					{
			
						$number_of_posts = array();
			
						foreach($feed->specials->special as $special) {
			
							$postTags = array();
			
							if( !$special->{'year'} == '' ) { $postTags[] = $special->{'year'}; }
			
							if( !$special->{'make'} == '' ) { $postTags[] = $special->{'make'}; }
			
							if( !$special->{'model'} == '' ) { $postTags[] = $special->{'model'}; }
			
							if( !$special->{'body_style'} == '' ) { $postTags[] = $special->{'body_style'}; }
			
							/*   Tags Removed
			
							if( !$special->{'trim_level'} == '' ) { $postTags[] = $special->{'trim_level'}; }
			
							if( !$special->{'mpg'} == '' ) { $postTags[] = "mpg ".$special->{'mpg'}; }
			
							if( !$special->{'available_apr'} == '' ) { $postTags[] = "apr ".doubleval($special->{'available_apr'}); }
			
							*/
			
							$postName = $special->{'year'} . "-". $special->{'make'} . "-" . $special->{'model'} . "-" . $special->{'trim_level'} . "-(".$special->attributes()->id.")";
			
							$postTitle = $special->{'year'} . " ". $special->{'make'} . " " . $special->{'model'} . " " . $special->{'trim_level'};
			
			
			
							/* Check to see if the post already exists */
			
							$pId = '0';
			
							$tempPostId = (string)$special->attributes()->post_id;
			
							if( in_array($tempPostId,$post_ids) ) {
			
								$pId = $tempPostId; /* looks like the post is still here */
			
							}
			
			
			
							// check to make sure post is an ncs post type here.  !!
			
							// $pId = ncs_post_exists( $postName );
			
			
			
							if( $pId == '0' )
			
							{
			
								//
			
								//  The post was not found, create new post args
			
								//
			
								$post = array(
			
										'post_content'   => '',
			
										'post_name'      => $postName,
			
										'post_title'     => $postTitle,
			
										'post_status'    => 'publish',
			
										'post_type'      => 'ncs_special',
			
										'menu_order'     => $special->attributes()->ordering,
			
										'post_date'      => date("Y-m-d H:i:s"),
			
										'post_date_gmt'  => date("Y-m-d H:i:s"),
			
										//  'tags_input'     => implode(",", $postTags),
			
										'post_category'  => array($special->{'make'})
			
								);
			
							}
			
							else {
			
								//
			
								//The post was found, update the existing post args by ID
			
								//
			
								$post = array(
			
										'ID'             => $pId,
			
										'post_content'   => '',
			
										'post_name'      => $postName,
			
										'post_title'     => $postTitle,
			
										'post_status'    => 'publish',
			
										'post_type'      => 'ncs_special',
			
										'menu_order'     => $special->attributes()->ordering,
			
										// 'tags_input'     => implode(",", $postTags),
			
										'post_category'  => array($special->{'make'})
			
								);
			
			
			
								/*
			
								'post_date'      => date("Y-m-d H:i:s"),
			
								'post_date_gmt'  => date("Y-m-d H:i:s"),
			
								*/
			
			
			
							}
			
							/*
			
							*  Create/Update the post with given args
			
							*  - if ID field is set in post array then wp_insert_post will attempt to update ( or will insert new post )
			
							*  - this could potentially change other posts in the system.  a better check to make sure post is ncs is needed!
			
							*/
			
			
			
							$post_id = wp_insert_post( $post, $wp_error=false );
			
							if( $pId == '0' ) {
			
								$pId = $post_id;
			
								$ncsResponse .= "<br>Added ".$special->{'year'}." ".$special->{'make'}." ".$special->{'model'}." [". (string)$special->attributes()->id ."]";
			
								$totAddedPosts++;
			
							}
			
							else
			
							{
			
								$ncsResponse .= "<br>Updated ".$special->{'year'}." ".$special->{'make'}." ".$special->{'model'}." [". (string)$special->attributes()->id ."]";
			
								$totUpdatedPosts++;
			
							}
			
							//  update_post_meta($pId, $meta_key, $meta_value, $isUniqueTrueFalse);
			
			
			
							/*======================================================================
			
			
			
							needs: store info: name, address, letter code, city, state, zip, phone, email, etc.
			
							additional: total of this model at store, total of this model for group
			
			
			
							======================================================================*/
			
							update_post_meta($pId, "model", (string)$special->{'model'});
			
							update_post_meta($pId, "year", (string)$special->{'year'});
			
							update_post_meta($pId, "vehicle_image", (string)$special->{'vehicle_image'});
			
							update_post_meta($pId, "special_id", (string)$special->attributes()->id);
			
							update_post_meta($pId, "title", (string)$special->{'title'});
			
							update_post_meta($pId, "make_name", (string)$special->{'make'}); // rename
			
							update_post_meta($pId, "make_id", (string)$special->make->attributes()->id);
			
							update_post_meta($pId, "model_name", (string)$special->{'model'}); // rename
			
							update_post_meta($pId, "model_id", (string)$special->model->attributes()->id);
			
							update_post_meta($pId, "body_style", (string)$special->{'body_style'});
			
							update_post_meta($pId, "trim_level", (string)$special->{'trim_level'});
			
							update_post_meta($pId, "tagline", (string)$special->{'tagline'});
			
							update_post_meta($pId, "stock_number", (string)$special->{'stock_number'});
			
							update_post_meta($pId, "vin_number", (string)$special->{'vin_number'});
			
							update_post_meta($pId, "p_text", (string)$special->{'p_text'}); // not being used ... i think
			
							update_post_meta($pId, "description_text", (string)$special->{'description_text'}); // not be used ( was for used special comments )
			
							update_post_meta($pId, "default_disclaimer_text", (string)$special->{'disclaimer_text'}); // rename
			
							update_post_meta($pId, "zero_down_lease_disclaimer_text", (string)$special->{'zero_down_lease_disclaimer_text'});
			
							update_post_meta($pId, "inventory_url", (string)$special->{'alt_link_url'}); // rename
			
							update_post_meta($pId, "lease_down_payment", (string)$special->{'lease_extras'}); // rename
			
							update_post_meta($pId, "lease_term", (string)$special->{'lease_term'});						
							
							update_post_meta($pId, "single_lease_term", (string)$special->{'single_lease_term'});
							
							update_post_meta($pId, "single_lease_miles", (string)$special->{'single_lease_miles'});
			
							update_post_meta($pId, "zero_down_lease_term", (string)$special->{'zero_down_lease_term'});
			
							update_post_meta($pId, "apr_text", (string)$special->{'apr_text'});
			
							update_post_meta($pId, "mpg", (string)$special->{'mpg'});
							
							update_post_meta($pId, "brand_logo_url", (string)$special->{'brand_logo_url'});
			
							update_post_meta($pId, "ddc_get_special_url", (string)$special->{'ddc_get_special_url'});
			
							update_post_meta($pId, "landing_page_url", (string)$special->{'landing_page_url'});
							
							update_post_meta($pId, "ordering", (string)$special->{'ordering'});
			
							update_post_meta($pId, "store_id", (string)$special->{'store_id'});
			
							update_post_meta($pId, "service_phone", (string)$special->{'store_service_phone'});
			
							update_post_meta($pId, "custom_price_label", (string)$special->{'custom_price_label'});
			
							update_post_meta($pId, "featured_special", (string)$special->{'featured_special'});
			
			
			
							$dealTypeTax = array();
			
			
			
							if( $special->{'lease_price'} != '' && $special->{'lease_price'} != '0'){
			
								update_post_meta($pId, "lease_price", doubleval($special->{'lease_price'}));
			
								$dealTypeTax[] = "lease";
			
							}
			
							else{
			
								delete_post_meta($pId, "lease_price", "");
			
							}
							
							if( $special->{'single_lease_price'} != '' && $special->{'single_lease_price'} != '0'){
									
								update_post_meta($pId, "single_lease_price", doubleval($special->{'single_lease_price'}));
									
								$dealTypeTax[] = "single pay";
									
							}
								
							else{
									
								delete_post_meta($pId, "single_lease_price", "");
									
							}
			
							if( $special->{'zero_down_lease_price'} != '' && $special->{'zero_down_lease_price'} != '0'){
			
								update_post_meta($pId, "zero_down_lease_price", doubleval($special->{'zero_down_lease_price'}));
			
								$dealTypeTax[] = "zero down";
			
							}
			
							else{
			
								delete_post_meta($pId, "zero_down_lease_price", "");
			
							}
			
							if( $special->{'buy_price'} != '' && $special->{'buy_price'} != '0'){
			
								update_post_meta($pId, "buy_price", doubleval($special->{'buy_price'}));
			
								$dealTypeTax[] = "purchase";
			
							}
			
							else{
			
								delete_post_meta($pId, "buy_price", "");
			
							}
			
							if( $special->{'available_apr'} != '' ){
			
								update_post_meta($pId, "available_apr", doubleval($special->{'available_apr'}));
			
							}
			
							else{
			
								delete_post_meta($pId, "available_apr", "");
			
							}
			
							if( $special->{'msrp'} != '' ){
			
								update_post_meta($pId, "msrp", doubleval($special->{'msrp'}));
			
							}
			
							else{
			
								delete_post_meta($pId, "msrp", "");
			
							}
			
							if( $special->{'save_up_to_amount'} != '' ){
			
								update_post_meta($pId, "save_up_to_amount", doubleval($special->{'save_up_to_amount'}));
			
							}
			
							else{
			
								delete_post_meta($pId, "save_up_to_amount", "");
			
							}
			
							if( $special->{'custom_price_val'} != '' ){
			
								update_post_meta($pId, "custom_price_val", doubleval($special->{'custom_price_val'}));
			
							}
			
							else{
			
								delete_post_meta($pId, "custom_price_val", "");
			
							}
			
			
			
							$vTotalPricingLines = $special->pricing->attributes()->total_lines;
			
							update_post_meta($pId, "total_pricing_lines", (string)$vTotalPricingLines);
			
							$pricingCounter = 0;
			
							if( $vTotalPricingLines > 0 )
			
							{
			
								foreach($special->pricing->item as $item)
			
								{
			
									if( $item->attributes()->price != '' )
			
									{
			
										update_post_meta($pId, "pricing_".$pricingCounter."_name", (string)$item->attributes()->name);
			
										update_post_meta($pId, "pricing_".$pricingCounter."_value", doubleval($item->attributes()->price));
			
									}
			
									else
			
									{
			
										delete_post_meta($pId, "pricing_".$pricingCounter."_name", (string)$item->attributes()->name);
			
										delete_post_meta($pId, "pricing_".$pricingCounter."_value", "");
			
									}
			
									$pricingCounter++;
			
								}
			
							}
			
							wp_set_post_terms( $pId, $special->{'year'}, 'model_year' );
			
							wp_set_post_terms( $pId, $special->{'make'}, 'make' );
			
							wp_set_post_terms( $pId, $special->{'model'}, 'model' );
			
							wp_set_post_terms( $pId, ucwords(strtolower($special->{'body_style'})), 'body_style' );
			
							wp_set_post_terms( $pId, implode(",", $dealTypeTax), 'deal_type', false);
			
			
			
							$specialsPostsIdsArr[$pId] = (string)$special->attributes()->id;
			
							$number_of_posts[] = $pId;
			
						}
			
			
			
						$ncsResponse .= "<br>Success.<br>";
			
						$ncsResponse .= "Added ".$totAddedPosts." new specials with the category \"Special\".<br>";
			
						$ncsResponse .= "Updated ". $totUpdatedPosts." new specials with the category \"Special\".<br>";
			
			
			
						$ncsResponse .= "<br><br>Syncing Some info back to NCS Server...<br>";
			
			
			
						/* sync back post ids */
			
						$vPrivateKey = get_option('ncs_private_key');
			
						$vCommands = "public_key=".$vPrivateKey;
			
						$vCommands .= "&task=NCS_SYNC";
			
						$vCommands .= "&wp_sp_domain=".$_SERVER['SERVER_NAME'];
			
						$vCommands .= "&wp_sp_ids=".http_build_query($specialsPostsIdsArr, '', '|');
			
						$ch = curl_init(); ;
			
						curl_setopt($ch, CURLOPT_URL, "http://qinventory.quirkspecials.com/quirk-inventory-site-dev/api2/v2");
			
						curl_setopt($ch, CURLOPT_POST, 1);
			
						curl_setopt($ch, CURLOPT_POSTFIELDS, $vCommands);
			
						curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			
						$result = curl_exec($ch);
			
						curl_close($ch);
			
						if( trim($result) == "" ) {
			
							$ncsResponse .= " Error Retrieving Sync Response<br>";
			
						}
			
						else
			
						{
			
							$ncsResponse .= "<br><br>".trim($result)."<br>";
			
						}
			
						$ncsResponse .= "<br>...done with NCS Server.<br>";
			
			
			
			
			
						$ncsResponse .= "<br><br>Cleaning Up....<br>";
			
						/* set specials that weren't in the feed to inactive */
			
						// or should we only do that if selected in options?
			
						foreach( (array) $post_ids as $post_id ) {
			
							if( !array_key_exists($post_id,$specialsPostsIdsArr) )
				
							{
				
								// update_post_meta($post_id, "active", "0", true);
				
								$post_del = get_post($post_id);
				
								if ( !wp_delete_post($post_id, true) )
					
								{
					
								$ncsResponse .= "Error deleting Special Post " . $post_id."<br>";
					
								}
					
								else
					
								{
					
								$ncsResponse .= "Special Post " . $post_id." was set deleted<br>";
					
								}
				
							}
				
						}
			
						$ncsResponse .= "<br>Done Cleaning Up.<br>";
			
					}
			
					else
			
					{
			
						$ncsResponse .= "<br>".$result."<br>";
			
					}
			
			
			
					$ncsResponse .= "<br>Finished Import (".date("m-d-Y g:i a").")";
			
					unlink($ncsPublicPath);
			
				}
			
				else
			
				{
			
					$ncsResponse .= "<br>XML File Not Found<br>";
			
					$ncsResponse .= "<br>".$result."<br>";
			
				}
			
			}			
		}

	}

	$ncsResponse .= "<br><br>... done Checking NCS<br><br>";	

	flush_rewrite_rules();

	//

	//  this function is called with ajax so use print or echo, instead of return

	//

	print $ncsResponse;

}
?>