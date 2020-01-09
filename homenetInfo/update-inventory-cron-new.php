#!/usr/bin/php5 -q

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('/srv/www/lib/inc.cli.php');
require_once('/srv/www/lib/class.inventory_vehicle.php');

ini_set("memory_limit","16M"); /* added 9/01/2009 */

$timer = start_timer();

cliprint("INVENTORY Cron Started at ".date("m-d-Y H:i:s a")."\r\n");

if( !UTILITY_cron_log_is_active("UPDATE_INVENTORY") )
{
	cliprint("UPDATE_INVENTORY Cron is NOT Active\r\n");
	UTILITY_update_cron_log("UPDATE_INVENTORY", "Cron is  NOT Active");
}
else
{
	cliprint("UPDATE_INVENTORY Cron is Active\r\n");
	UTILITY_update_cron_log("UPDATE_INVENTORY", "Running");

	$vNumLines = 0;	
	$vNumSaved = 0;	
	$vNumUpdated = 0;	
	
	$vNumLinesInFile = 0;	
	$vNumSavedInFile = 0;	
	$vNumUpdatedInFile = 0;	

	$vNewVehiclesArr = array();
	$vVehiclesArr = array();
	// load the current core vehicle list
	$sql = "SELECT * FROM core_vehicles";
	$result = r_q($sql);	
	if( mysql_num_rows($result) > 0 )
	{
		while( $row = mysql_fetch_array($result) )
		{
			$vYear = stripslashes($row['year']);
			$vMakeId = stripslashes($row['make_id']);
			$vModelId = stripslashes($row['model_id']);
			$vVehicleCode = $vYear."_".$vMakeId."_".$vModelId;
			$vVehiclesArr[$vVehicleCode] = 0;
		}
	}
	
	$vFileBase = "/srv/www/files/inventory/";
	$vArchiveFileBase = "/srv/www/files/inventory/archived/";
	$vFilesToRemoveAfter = array();
	$vModelsAddedArr = array();
	$vErrorsArr = array();
	$vFilesArr = array(); 

	$sql = "SELECT * from inventory_files ORDER BY inventory_file_name";
	$result = r_q($sql);
	$class="alt-row";
	if( mysql_num_rows($result) > 0 )
	{
		while( $row = mysql_fetch_array($result) )
		{
			$vFilesArr[] = stripslashes($row['inventory_file_name']);
		}
	}

	for($i=0; $i < count($vFilesArr); $i++) 
	{
		cliprint("\r\nOpening ".$vFilesArr[$i]);	
		if($fcontents = file ($vFileBase.$vFilesArr[$i])) 
		{
			$vTotalErrorVehicles = 0;
			$vStoreId = "";
			$vStoreLetter = "";
			$vStoreName = "";
			$vStoreRow = array();
			$sql2 = "select * from stores where lower(inventory_feed_filename)='".addslashes($vFilesArr[$i])."' LIMIT 1";
			$result2 = r_q($sql2);
			if( mysql_num_rows($result2) == '1' )
			{
				$vStoreRow = mysql_fetch_array($result2);
				$vStoreId = stripslashes($vStoreRow['id']);
				$vStoreLetter = stripslashes($vStoreRow['letter']);
				$vStoreName = stripslashes($vStoreRow['storename']);
				$vStoreInventoryName = stripslashes($vStoreRow['store_inventory_name']);
	
				$vAllVehicles1 = new inventory_vehicle;
				$vAllVehicles1->set_all_in_feed_zero($vStoreInventoryName);
			}			

			$vInnerHtmlBody = "";
			$vFilesToRemoveAfter[] = $vFileBase.$vFilesArr[$i];
			cliprint("\r\nProcessing ".$vFilesArr[$i]);
			$vNumLinesInFile = 0;	
			$vNumSavedInFile = 0;	
			$vNumUpdatedInFile = 0;	
			while (list ($lineNum, $line) = each ($fcontents)) 
			{	
				$vVehicleErrors = 0;
				$vHasMakeId = TRUE;
				$vHasModelId = TRUE;
				$vHasPrice = TRUE;
				$vHasTransmission = TRUE;
				$vHasExteriorColor = TRUE;
				$vHasInteriorColor = TRUE;
				
				$fixedLine = trim (chop ($line));
				$ln = explode (",", $fixedLine);
				if($lineNum >= 1) 
				{
					$vNumLines++;
					$vNumLinesInFile++;	
					$vExists = FALSE;
					$vVinNumber = str_replace("\"", "", $ln[1]);
					$vVehicle = new inventory_vehicle;
					$vVehicle->vin_number = $vVinNumber;
					if( $vVehicle->exists() ) 
					{
						$vExists = TRUE;
						$vId = $vVehicle->id;
						$vVehicle->get($vId);
					}
					$vMakeName = str_replace("\"", "", $ln[5]);
					$vModelName = str_replace("\"", "", $ln[6]);
					$vNewUsed = str_replace("\"", "", $ln[3]);
					$vVehicle->store_inventory_name 	= str_replace("\"", "", $ln[0]);	// Company	
					$vVehicle->vin_number 						= str_replace("\"", "", $ln[1]);	// VIN	
					$vVehicle->stock_number 					= str_replace("\"", "", $ln[2]);	// Stock #	
					( strtoupper($vNewUsed) == "NEW" ) ? $vVehicle->new_used = 'N' : $vVehicle->new_used = 'U';
					$vVehicle->year 									= str_replace("\"", "", $ln[4]);	// Year	
					$vVehicle->make_id 								= get_make_id($vMakeName);
					$vVehicle->model_id 							= get_model_id($vModelName);
					$vVehicle->model_number 					= str_replace("\"", "", $ln[7]);	// Model-No	
					$vVehicle->model_type 						= str_replace("\"", "", $ln[8]);	// Model-Type	
					$vVehicle->transmission 					= str_replace("\"", "", $ln[9]);	// Transmission	
					$vVehicle->trim_level 						= str_replace("\"", "", $ln[10]);	// Trim-Level	
					$vVehicle->num_of_doors 					= str_replace("\"", "", $ln[11]);	// # of Doors	
					$vVehicle->mileage 								= str_replace("\"", "", $ln[12]);	// Mileage	
					$vVehicle->num_of_cylinders 			= str_replace("\"", "", $ln[13]);	// # of Cyl	
					$vVehicle->engine 								= str_replace("\"", "", $ln[14]);	// Engine	
					$vVehicle->drivetrain 						= str_replace("\"", "", $ln[15]);	// Drivetrain	
					$vVehicle->ext_color 							= str_replace("\"", "", $ln[16]);	// Ext. Color	
					$vVehicle->int_color 							= str_replace("\"", "", $ln[17]);	// Int. Color	
					$vVehicle->invoice_price 					= str_replace("\"", "", $ln[18]);	// Invoice Price	
					$vVehicle->retail_price 					= str_replace("\"", "", $ln[19]);	// Retail Price	
					$vVehicle->book_value 						= str_replace("\"", "", $ln[20]);	// Book Value	
					$vVehicle->internet_price 				= str_replace("\"", "", $ln[21]);	// Selling Price	
					$vVehicle->entry_date 						= str_replace("\"", "", $ln[22]);	// Entry Date	
					$vVehicle->certified 							= str_replace("\"", "", $ln[23]);	// Certified	
					$vVehicle->description 						= str_replace("\"", "", $ln[24]);	// Description	
					$vVehicle->options 								= str_replace("\"", "", $ln[25]);	// Options	
					$vVehicle->wheelbase 							= str_replace("\"", "", $ln[26]);	// Wheelbase	
					$vVehicle->commercial 						= str_replace("\"", "", $ln[27]);	// Commercial
					$vVehicle->source 								= 'INVENTORY';
					$vVehicle->in_feed 								= '1';
					$vVehicle->active 								= '1';

					// check for problems

					if( $vVehicle->new_used == 'N' )
					{
						// is it really new?
						if( ( $vVehicle->mileage > 100 ) && ( $vVehicle->mileage != '' ) )
						{
							$vVehicle->new_used = 'U';
						}
					}
					else
					{
						// is it really used?
						if( ( ( $vVehicle->mileage < 100 ) && ( $vVehicle->mileage != '' ) ) )
						{
							$vVehicle->new_used = 'N';
						}						
					}


					if( $vVehicle->make_id == "" || $vMakeName == "" )
					{
						$vHasMakeId = FALSE;
						$vVehicleErrors++;
					}

					if( $vVehicle->model_id == "" || $vModelName == "" )
					{
						$vHasModelId = FALSE;
						$vVehicleErrors++;
					}
					
					if( $vVehicle->new_used == 'N' )
					{
						if( ( $vVehicle->internet_price == "" ) || ( $vVehicle->internet_price == "0" ) )
						{
							$vHasPrice = FALSE;
							$vVehicleErrors++;
						}
					}
					
					if( $vVehicle->transmission == "" )
					{
						$vHasTransmission = FALSE;
						$vVehicleErrors++;
					}

					if( $vVehicle->ext_color == "" )
					{
						$vHasExteriorColor = FALSE;
						$vVehicleErrors++;
					}					

					if( $vVehicle->int_color == "" )
					{
						$vHasInteriorColor = FALSE;
						$vVehicleErrors++;
					}					

					// update db for vehicles in need of attention
					$vVehicle->attention='0';	
					if( $vVehicle->make_id == "" || $vMakeName == "" ) { $vVehicle->attention='1'; }
					if( $vVehicle->model_id == "" || $vModelName == "" ) { $vVehicle->attention='1'; }
					if( ( ( $vVehicle->retail_price == "" ) || ( $vVehicle->retail_price == "0" ) ) && ( ( $vVehicle->internet_price == "" ) || ( $vVehicle->internet_price == "0" ) ) ) { $vVehicle->attention='1'; }
					if( $vVehicle->transmission == "" ) { $vVehicle->attention='1'; }
					if( $vVehicle->ext_color == "" ) { $vVehicle->attention='1'; }					
					if( $vVehicle->int_color == "" )  { $vVehicle->attention='1'; }	
					
					if( !$vExists ) 
					{
						if( $vVehicle->insert() )
						{
							$vNumSaved++;
							$vNumSavedInFile++;	
							cliprint("\r\nAdded: ".$vVehicle->stock_number);						
						}
						else
						{
							cliprint("\r\nCould Not Add: ".$vVehicle->stock_number);													
						}
					}
					else
					{
						/*
						cliprint("\r\nUpdating is OFF :".$vVehicle->stock_number);	
						if( $vVehicle->update() )
						{
							$vNumUpdated++;	
							$vNumUpdatedInFile++;	
							cliprint("\r\nUpdated: ".$vVehicle->stock_number);	
						}
						else
						{
							cliprint("\r\nCould Not Update: ".$vVehicle->stock_number);	
						}
						*/
					}	


					if( $vVehicle->attention == '1' )
					{
						$vInnerHtmlBody .= "<tr class='default-row'>";
						$vInnerHtmlBody .= "	<td align='left' valign='top'><a href='https://home.quirkcars.com/inventory/view/".$vVehicle->id."' target='_blank'>".$vVehicle->stock_number."</a></td>";
						$vInnerHtmlBody .= "	<td align='left' valign='top'>".$vVehicle->new_used."</td>";
						$vInnerHtmlBody .= "	<td align='left' valign='top'>".$vVehicle->year."</td>";
						$vInnerHtmlBody .= "	<td align='left' valign='top' bgcolor='".( (!$vHasMakeId) ? "red" : "" )."'>".ucwords(strtolower($vMakeName))."(".$vVehicle->make_id.")</td>";
						$vInnerHtmlBody .= "	<td align='left' valign='top' bgcolor='".( (!$vHasModelId) ? "red" : "" )."'>".ucwords(strtolower($vModelName))."(".$vVehicle->model_id.")</td>";
						$vInnerHtmlBody .= "	<td align='left' valign='top' bgcolor='".( (!$vHasPrice) ? "red" : "" )."'>".( (!$vHasPrice) ? "N/A" : $vVehicle->internet_price )."</td>";
						$vInnerHtmlBody .= "	<td align='left' valign='top' bgcolor='".( (!$vHasTransmission) ? "red" : "" )."'>".( (!$vHasTransmission) ? "N/A" : $vVehicle->transmission )."</td>";
						$vInnerHtmlBody .= "	<td align='left' valign='top'>".( (!$vHasExteriorColor) ? "N/A" : $vVehicle->ext_color )."</td>";
						$vInnerHtmlBody .= "	<td align='left' valign='top'>".( (!$vHasInteriorColor) ? "N/A" : $vVehicle->int_color )."</td>";
						$vInnerHtmlBody .= "</tr>";
						$vTotalErrorVehicles++;
					}

					$vCode = $vVehicle->year."_".$vVehicle->make_id."_".$vVehicle->model_id;
					if( in_array($vCode,$vVehiclesArr) )
					{
						$vNewTotal = ($vVehiclesArr[$vCode] + 1 );
						$vVehiclesArr[$vCode] = $vNewTotal;						
					}
					else
					{
						$vVehiclesArr[$vCode] = 0;	
						$vNewVehiclesArr[] = $vCode; 												
					}	
									

				} // end if first line

			}	// end while file has next lin

			if( $vInnerHtmlBody != "" )
			{
				$vHtmlBody = "<html>";
				$vHtmlBody .= "<head><title>Auto Form</title>";
				$vHtmlBody .= "<style type='text/css'>";
				$vHtmlBody .= " * { font-size: 10PX; font-family:Arial, Helvetica, sans-serif; }";
				$vHtmlBody .= ".redtext { font-family: Georgia, 'Times New Roman', Times, serif; color: #FF0000; }";
				$vHtmlBody .= "body { background-color:#FFFFFF; margin:0px 0px 0px 0px; padding:0px 0px 0px 0px; }";
				$vHtmlBody .= ".whitetext { color: #FFFFFF; }";
				$vHtmlBody .= ".main-tbl { border: 2PX SOLID #000000; }";
				$vHtmlBody .= ".tbl-header { color: #FFFFFF; background-color: #222222; }";
				$vHtmlBody .= ".tbl-col-header { color: #FFFFFF; background-color: #999999; }";
				$vHtmlBody .= ".default-row { background-color: #FFFFFF; }";
				$vHtmlBody .= ".alt-row { background-color: #DDDDDD; }";
				$vHtmlBody .= ".bold {font-weight:bold;}	";
				$vHtmlBody .= "</style>";	
				$vHtmlBody .= "</head>";	
				$vHtmlBody .= "<body>";
				$vHtmlBody .= "<p width='100%'>";
				$vHtmlBody .= "Store Name: ".$vStoreName."<br>
											 The Vehicles below are in need of <strong>Attention</strong>. 
											 These issues were found during the nightly inventory update on home.quirkcars.com.
											 <br>
											 Missing data should be entered into the system using ADP.<br>
											 Error Lines are caused by any of the following:<br>
											 <ul>
											 	<li>Missing Make Name or Make ID</li>
											 	<li>Missing Model Name or Model ID</li>
											 	<li>Missing BOTH Internet Price and Retail Price (New Vehicles Only)</li>
											 	<li>Missing Transmission Type</li>
											 	<li>Missing Exterior Color</li>
											 	<li>Missing Exterior Color</li>
											 </ul>
											 <br>";
				$vHtmlBody .= "</p><br>";
				$vHtmlBody .= "<table border='1' cellspacing='0' cellpadding='5' width='600' align='left' valign='top' bgcolor='#FFFFFF' class='main-tbl'>";
				$vHtmlBody .= "	<tbody>";
				$vHtmlBody .= "	<tr class='default-row'>";
				$vHtmlBody .= "		<td align='left' valign='top'><strong>Stock#</strong></td>";
				$vHtmlBody .= "		<td align='left' valign='top'><strong>N/U</strong></td>";
				$vHtmlBody .= "		<td align='left' valign='top'><strong>Year</strong></td>";
				$vHtmlBody .= "		<td align='left' valign='top'><strong>Make</strong></td>";
				$vHtmlBody .= "		<td align='left' valign='top'><strong>Model</strong></td>";				
				$vHtmlBody .= "		<td align='left' valign='top'><strong>Price</strong></td>";
				$vHtmlBody .= "		<td align='left' valign='top'><strong>Trans</strong></td>";
				$vHtmlBody .= "		<td align='left' valign='top'><strong>Ext Color</strong></td>";
				$vHtmlBody .= "		<td align='left' valign='top'><strong>Int Color</strong></td>";
				$vHtmlBody .= "	</tr>";
				if( $vTotalErrorVehicles == 0 )
				{
					$vHtmlBody .= "	<tr class='default-row'>";
					$vHtmlBody .= "		<td align='left' valign='top' colspan='9'><strong>Looking Good... Real Good!  Nice Work.</strong></td>";
					$vHtmlBody .= "	</tr>";
				}
				else
				{
					$vHtmlBody .= $vInnerHtmlBody;							
				}						

				$vHtmlBody .= "	</tbody>";
				$vHtmlBody .= "</table>";
				$vHtmlBody .= "</body></html>";
	                        /*   		
				$vNotifyTypeName = "INVENTORY_ERRORS_" . strtoupper($vStoreLetter);
				$to_emails = generate_notify_email_array( get_notify_type_id_by_name($vNotifyTypeName) );
				if( count($to_emails) > 0 )
				{
					$from_name = "Quirk Web Team";
					$from_email = "support@quirkcars.com";
					$mSubject = "Inventory Errors (".$vStoreName.")";
					if( send_qmail($to_emails, $from_email, $from_name, $mSubject, $vHtmlBody) ) 
					{
						cliprint("\r\n Email Sent");	
					}
					else
					{
						cliprint("\r\n Email Not Sent");	
					}
				}
                                */
			}

			
			cliprint("\r\nTotal Lines: ".$vNumLinesInFile);	
			cliprint("\r\n      Saved: ".$vNumSavedInFile);	
			cliprint("\r\n    Updated: ".$vNumUpdatedInFile);	

			// Go through the inventory in the db again.  Mark vehicles not in current files (in_feed) as inactive
			$vAllVehicles2 = new inventory_vehicle;
			$vNumRemovedInFile = $vAllVehicles2->set_not_in_feed_inactive($vStoreInventoryName);

		}
		else
		{
			cliprint("\r\n".$vFilesArr[$i]." is Empty");	
			
		}
		
		
		cliprint("\r\n");	
	}
	
	cliprint("\r\n----------------------------------------------\r\n");	
	cliprint("\r\nTotal Lines: ".$vNumLines);	
	cliprint("\r\n      Saved: ".$vNumSaved);	
	cliprint("\r\n    Updated: ".$vNumUpdated);	
	cliprint("\r\n    Removed: ".$vNumRemovedInFile);	
	cliprint("\r\n----------------------------------------------\r\n");	
	
	$vVehicleInfo2 = "";
	if( count($vModelsAddedArr) > 0 )
	{
		cliprint("\r\nNew Models that need to be added:");		
		for($i=0; $i < count($vModelsAddedArr); $i++)
		{
			cliprint("\r\n".$vModelsAddedArr[$i]);				
			$vVehicleInfo2 .= "<tr><td>".$vModelsAddedArr."</td></tr>";
		}

		
		$to_emails = generate_notify_email_array( get_notify_type_id_by_name("INVENTORY_ERRORS") );
		if( count($to_emails) > 0 )
		{
			$from_name = "Quirk Web Team";
			$from_email = "support@quirkcars.com";
			$mSubject = "UnMatched Vehicle Models";
			$mBody = "<div align='left'><table border='1' cellpadding='5' cellspacing='0' width='600'>".$vVehicleInfo2."</table></div>";	
			if( send_qmail($to_emails, $from_email, $from_name, $mSubject, $vHtmlBody) ) 
			{
				cliprint("\r\n Email Sent");	
			}
			else
			{
				cliprint("\r\n Email Not Sent");	
			}
		}

	}



	$vNewVehiclesEmail .= "<tr>";
	$vNewVehiclesEmail .= "<td width='100'>Year</td>";
	$vNewVehiclesEmail .= "<td width='200'><td>Make</td>";
	$vNewVehiclesEmail .= "<td width='200'><td>Model</td>";
	$vNewVehiclesEmail .= "<td width='100'><td>Total</td>";
	$vNewVehiclesEmail .= "</tr>";

	$vNewVehiclesEmail .= "<tr><td width='100'>Year</td><td>".$vYear."</td></tr>";
	$vNewVehiclesEmail .= "<tr><td>Make</td><td>".get_make_name($vMakeId)."</td></tr>";
	$vNewVehiclesEmail .= "<tr><td>Model</td><td>".get_model_name($vModelId)."</td></tr>";
	$vNewVehiclesEmail .= "<tr><td>Code</td><td>".$vVehicleCode."</td></tr>";

	if( count($vNewVehiclesArr) > 0 )
	{
		$vNewVehiclesEmail = "<table border='1' cellpadding='5' cellspacing='0' width='600'>";
		foreach($vNewVehiclesArr AS $code)
		{
			$vChunk = explode ("_", $code);
			$vYear = $vChunk[0];
			$vMakeId = $vChunk[1];
			$vModelId = $vChunk[2];
			$vTotalInStock = $vVehiclesArr[$code];
			$sql2 = "INSERT INTO core_vehicles (id, year, make_id, model_id, total_in_stock, created)  
							 VALUES ('', '".addslashes($vYear)."', '".addslashes($vMakeId)."', '".addslashes($vModelId)."', 
							 '".addslashes($vTotalInStock)."', NOW())";
			$result2 = r_q($sql2);				

			$vNewVehiclesEmail .= "<tr>";
			$vNewVehiclesEmail .= "<td width='100'>".$vYear."</td>";
			$vNewVehiclesEmail .= "<td width='200'><td>".get_make_name($vMakeId)."</td>";
			$vNewVehiclesEmail .= "<td width='200'><td>".get_model_name($vModelId)."</td>";
			$vNewVehiclesEmail .= "<td width='100'><td>".$vTotalInStock."</td>";
			$vNewVehiclesEmail .= "</tr>";
		}
		$vNewVehiclesEmail .= "</table>";	 				

		$to_emails = generate_notify_email_array( get_notify_type_id_by_name("NEW_VEHICLES_ADDED") );
		$from_name = "Quirk Web Team";
		$from_email = "pwalker@quirkcars.com";
		$mBody = "<div align='left'>".$vNewVehiclesEmail."</div>";		
		send_qmail($to_emails, $from_email, $from_name, "New Vehicle Type(s) Added to System", $mBody);										
	}

	// update the existing vehicles counts
	foreach($vVehiclesArr AS $code => $vTotalInStock)
	{
		$vChunk = explode ("_", $code);
		$vYear = $vChunk[0];
		$vMakeId = $vChunk[1];
		$vModelId = $vChunk[2];
		$sql2 = "UPDATE core_vehicles 
						 SET 
						 total_in_stock='".addslashes($vTotalInStock)."', 
						 modified=NOW() 
						 WHERE 
						 year='".addslashes($vYear)."' AND 
						 make_id='".addslashes($vMakeId)."' AND 
						 model_id='".addslashes($vModelId)."' 
						 LIMIT 1";
		$result2 = r_q($sql2);				
	}	


	if( count($vFilesToRemoveAfter) > 0 )
	{
		cliprint("\r\nRemoving Files: ");		
		for($i=0; $i < count($vFilesToRemoveAfter); $i++)
		{
			$vJustFileName = str_replace($vFileBase, "", $vFilesToRemoveAfter[$i]);
			$vFileTime = filemtime($vFilesToRemoveAfter[$i]);
			$vLastReceived = date("Y-m-d H:i:s",$vFileTime);

			$vCopyFileNameTemp = str_replace($vFileBase, $vArchiveFileBase, $vFilesToRemoveAfter[$i]);
			$vCopyFileName = str_replace(".csv", "-".time().".csv", $vCopyFileNameTemp); // save daily file for research 2013-12-11

			if( copy($vFilesToRemoveAfter[$i], $vCopyFileName) ) 
			{
		    fwrite($stdout, "\r\nFile has been archived: ".$vCopyFileName);	
			} 
			unlink($vFilesToRemoveAfter[$i]); /* remove file */
			cliprint("\r\nFile has been Removed: ".$vFilesToRemoveAfter[$i]);				

			$sql4 = "UPDATE inventory_files SET 
								last_received='".$vLastReceived."', 
								last_processed=NOW() 
								WHERE inventory_file_name='".addslashes($vJustFileName)."'";
			$result4 = r_q($sql4);
		}
		
		UTILITY_update_cron_successful_log("UPDATE_INVENTORY", "Success");	
	}
	else
	{
		UTILITY_update_cron_successful_log("UPDATE_INVENTORY", "No Files Found");		
	}
}

$vEndTimer = end_timer($timer);

UTILITY_update_cron_log_script_time("UPDATE_INVENTORY", $vEndTimer);	

cliprint("\r\n\r\nScript comleted in ".$vEndTimer."\r\n");


?>
