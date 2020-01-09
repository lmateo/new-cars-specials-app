#!/usr/bin/php5 -q

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('/srv/www/lib/inc.cli.php');
require_once('/srv/www/lib/class.inventory_vehicle.php');

ini_set("memory_limit","16M"); /* added 9/01/2009 */

$timer = start_timer();

$vStoreLetterToProcess = strtoupper(trim($argv[1]));

$vCronName = "UPDATE_OTHER_INVENTORY";

cliprint($vCronName." Cron Started at ".date("m-d-Y H:i:s a")."\r\n");

if( !UTILITY_cron_log_is_active($vCronName) )
{
	cliprint($vCronName." Cron is NOT Active\r\n");
	UTILITY_update_cron_log($vCronName, "Cron is  NOT Active");
}
else
{
	cliprint($vCronName." Cron is Active\r\n");
	UTILITY_update_cron_log($vCronName, "Running");

	$vNumLines = 0;	
	$vNumSaved = 0;	
	$vNumUpdated = 0;	
	$vNumLinesInFile = 0;	
	$vNumSavedInFile = 0;	
	$vNumUpdatedInFile = 0;	
	$vNewVehiclesArr = array();
	$vVehiclesArr = array();
	$vFeedFile = "";
	$vFileBase = "/srv/www/files/inventory/";
	$vArchiveFileBase = "/srv/www/files/inventory/archived/";
	$vFilesToRemoveAfter = array();
	$vModelsAddedArr = array();
	$vErrorsArr = array();
	$vFilesArr = array(); 
	$vTotalErrorVehicles = 0;
	$vInnerHtmlBody = "";

	$vFilesArr = array(); 
	$vFilesArr[0] = "Volkswagen_Inventory.csv";
	$vFilesArr[1] = "Kia_Inventory.csv";	
	$vFilesArr[2] = "Mazda_Inventory.csv";
	$vFilesArr[3] = "Nissan_Inventory.csv";	
	$vFilesArr[4] = "Chevrolet_Inventory.csv";	
	$vFilesArr[5] = "Preowned_Inventory.csv";	
	$vFilesArr[6] = "Ford_Inventory.csv";	
	$vFilesArr[7] = "Subaru_Inventory.csv";	
	$vFilesArr[8] = "Jeep_Inventory.csv";	
	$vFilesArr[9] = "ChevyBuick_Inventory.csv";	
	$vFilesArr[10] = "VWNH_Inventory.csv";
	$vFilesArr[11] = "BuickGMC_Inventory.csv";	
	$vFilesArr[12] = "Dodge_Inventory.csv";
	$vFilesArr[13] = "JeepDorchester_Inventory.csv";
	$vFilesArr[14] = "KiaNH_inventory.csv";

	$vStoreNamesArr = array();
	$vStoreNamesArr[0] = "quirkvolkswagen";
	$vStoreNamesArr[1] = "quirkkia";	
	$vStoreNamesArr[2] = "quirkmazda";
	$vStoreNamesArr[3] = "quirknissan";	
	$vStoreNamesArr[4] = "quirkchevrolet";	
	$vStoreNamesArr[5] = "quirkpreowned";	
	$vStoreNamesArr[6] = "quirkford";	
	$vStoreNamesArr[7] = "quirksubaru";	
	$vStoreNamesArr[8] = "quirkjeep";	
	$vStoreNamesArr[9] = "quirkchevroletnh";	
	$vStoreNamesArr[10] = "quirkvolkswagennh";
	$vStoreNamesArr[11] = "quirkbuickgmc";	
	$vStoreNamesArr[12] = "quirkdodge";
	$vStoreNamesArr[13] = "quirkjeepdorch";
	$vStoreNamesArr[14] = "quirkkianh";	

	$vStoreIdsArr = array();
	$vStoreIdsArr[0] = "7";
	$vStoreIdsArr[1] = "3";	
	$vStoreIdsArr[2] = "4";
	$vStoreIdsArr[3] = "5";	
	$vStoreIdsArr[4] = "1";	
	$vStoreIdsArr[5] = "8";	
	$vStoreIdsArr[6] = "2";	
	$vStoreIdsArr[7] = "6";	
	$vStoreIdsArr[8] = "11";	
	$vStoreIdsArr[9] = "9";	
	$vStoreIdsArr[10] = "19";
	$vStoreIdsArr[11] = "20";	
	$vStoreIdsArr[12] = "26";
	$vStoreIdsArr[13] = "30";
	$vStoreIdsArr[14] = "29";	

	$vStoreLettersArr = array();
	$vStoreLettersArr[0] = "v";
	$vStoreLettersArr[1] = "k";	
	$vStoreLettersArr[2] = "m";
	$vStoreLettersArr[3] = "n";	
	$vStoreLettersArr[4] = "c";	
	$vStoreLettersArr[5] = "p";	
	$vStoreLettersArr[6] = "f";	
	$vStoreLettersArr[7] = "s";	
	$vStoreLettersArr[8] = "j";	
	$vStoreLettersArr[9] = "d";	
	$vStoreLettersArr[10] = "w";
	$vStoreLettersArr[11] = "g";	
	$vStoreLettersArr[12] = "e";
	$vStoreLettersArr[13] = "y";
	$vStoreLettersArr[14] = "h";	



	/* 	PROCESS AN INVENTORY FILE - START */
	$sql = "SELECT * from stores WHERE UPPER(letter)='".addslashes($vStoreLetterToProcess)."' LIMIT 1";
	$result = r_q($sql);
	$class="alt-row";
	
	foreach($vFilesArr AS $key => $value)
	{
		$vFeedFile = $value;
		$vStoreId = $vStoreIdsArr[$key];
		$vStoreLetter = $vStoreLettersArr[$key];
		$vStoreName = $vStoreNamesArr[$key];
		$vStoreInventoryName = $vStoreNamesArr[$key];

		cliprint("\r\nOpening ".$vFeedFile);	
		if($fcontents = file ($vFileBase.$vFeedFile)) 
		{
			$vAllVehicles1 = new inventory_vehicle;
			$vAllVehicles1->set_all_in_feed_zero($vStoreInventoryName);
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
					$vVehicleCheck = new inventory_vehicle;

					if( $vVehicle->exists() ) 
					{
						$vExists = TRUE;
						$vId = $vVehicle->id;
						$vVehicle->get($vId);
						$vVehicleCheck->get($vId);
					}
					$vMakeName = str_replace("\"", "", $ln[2]);
					$vModelName = str_replace("\"", "", $ln[3]);
					$vNewUsed = str_replace("\"", "", $ln[7]);
					$vVehicle->store_inventory_name 	= $vStoreInventoryName;
					$vVehicle->vin_number 						= str_replace("\"", "", $ln[1]);
					$vVehicle->stock_number 					= str_replace("\"", "", $ln[0]);
					( strtoupper($vNewUsed) == "NEW" ) ? $vVehicle->new_used = 'N' : $vVehicle->new_used = 'U';
					$vVehicle->year 									= str_replace("\"", "", $ln[4]);
					$vVehicle->make_id 								= get_make_id($vMakeName);
					$vVehicle->model_id 							= get_model_id($vModelName);
					$vVehicle->mileage 								= str_replace("\"", "", $ln[5]);
					$vVehicle->ext_color 							= str_replace("\"", "", $ln[6]);
					$vVehicle->entry_date 						= str_replace("\"", "", $ln[9]);
					$vVehicle->source 								= 'INVENTORY';
					$vVehicle->in_feed 								= '0';
					$vVehicle->active 								= '0';
					$vVehicle->location 							= "shipyard";
	
					if( $vVehicle->entry_date != '' )
					{
						$vTempEntryDate = date("Y-m-d", strtotime($vVehicle->entry_date));
						$vVehicle->entry_date = $vTempEntryDate;
					}

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
						if( ( ( $vVehicle->mileage < 50 ) && ( $vVehicle->mileage != '' ) ) )
						{
							$vVehicle->new_used = 'N';
						}						
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
						if( $vVehicle->insert_on_the_fly() )
						{
							$vNumSaved++;
							$vNumSavedInFile++;	
							cliprint("\r\nAdded: ".$vVehicle->stock_number);			
							$vHistoryDataArr = array();
							$vHistoryDataArr['vin_number'] = $vVehicle->vin_number;
							$vHistoryDataArr['notes'] = "Added to System (Inventory Feed)";
							$vHistoryDataArr['created_id'] = '0';
							UTIL_vehicle_history_append($vHistoryDataArr);
						}
						else
						{
							cliprint("\r\nCould Not Add: ".$vVehicle->stock_number);													
						}
					}
					else
					{
						/* don't update entry - that is not the purpose of this feed ... as in... these should all be add ins */
					}
				} // end if first line
			}	// end while file has next lin

			cliprint("\r\nTotal Lines: ".$vNumLinesInFile);	
			cliprint("\r\n      Saved: ".$vNumSavedInFile);	

			/* 	REMOVE FILE - START */
			cliprint("\r\nRemoving File: ");		
			$vFileTime = filemtime($vFileBase.$vFeedFile);
			$vLastReceived = date("Y-m-d H:i:s",$vFileTime);
			$vCopyFileNameTemp = str_replace($vFileBase, $vArchiveFileBase, $vFileBase.$vFeedFile);
			$vCopyFileName = str_replace(".csv", "-".time().".csv", $vCopyFileNameTemp); // save daily file for research 2013-12-11
	
			if( copy($vFileBase.$vFeedFile, $vCopyFileName) ) 
			{
		    fwrite($stdout, "\r\nFile has been archived: ".$vCopyFileName);	
			} 
			unlink($vFileBase.$vFeedFile); /* remove file */
			cliprint("\r\nFile has been Removed: ".$vFeedFile);				
	
			/* 	REPORTING - START */	
			cliprint("\r\n----------------------------------------------\r\n");	
			cliprint("\r\nTotal Lines: ".$vNumLines);	
			cliprint("\r\n      Saved: ".$vNumSaved);	
			cliprint("\r\n----------------------------------------------\r\n");	
		}
		else
		{
			cliprint("\r\n".$vFeedFile." is Empty");	
		}		
		cliprint("\r\n");	
		UTILITY_update_cron_successful_log($vCronName, "Success");	
	}
}

$vEndTimer = end_timer($timer);

UTILITY_update_cron_log_script_time($vCronName, $vEndTimer);	

cliprint("\r\n\r\nScript completed in ".$vEndTimer."\r\n");


?>