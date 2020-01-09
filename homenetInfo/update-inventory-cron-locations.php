#!/usr/bin/php5 -q

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('/srv/www/lib/inc.cli.php');
require_once('/srv/www/lib/class.inventory_vehicle.php');

ini_set("memory_limit","16M"); /* added 9/01/2009 */

$timer = start_timer();

$vStoreLetterToProcess = strtoupper(trim($argv[1]));

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
	$vFeedFile = "";
	$vFileBase = "/srv/www/files/inventory/";
	$vArchiveFileBase = "/srv/www/files/inventory/archived/";
	$vFilesToRemoveAfter = array();
	$vModelsAddedArr = array();
	$vErrorsArr = array();
	$vFilesArr = array(); 
	$vTotalErrorVehicles = 0;
	$vInnerHtmlBody = "";


	/* 	PROCESS AN INVENTORY FILE - START */
	$sql = "SELECT * from stores WHERE UPPER(letter)='".addslashes($vStoreLetterToProcess)."' LIMIT 1";
	$result = r_q($sql);
	$class="alt-row";
	if( mysql_num_rows($result) == 1 )
	{
		$row = mysql_fetch_array($result);
		$vFeedFile = stripslashes($row['inventory_feed_filename']);
		$vStoreId = stripslashes($row['id']);
		$vStoreLetter = stripslashes($row['letter']);
		$vStoreName = stripslashes($row['storename']);
		$vStoreInventoryName = stripslashes($row['store_inventory_name']);

		cliprint("\r\nOpening ".$vFeedFile);	
		if($fcontents = file ($vFileBase.$vFeedFile)) 
		{
			cliprint("\r\nProcessing ".$vFilesArr[$i]);
			while (list ($lineNum, $line) = each ($fcontents)) 
			{	
				$fixedLine = trim (chop ($line));
				$ln = explode (",", $fixedLine);
				if($lineNum >= 1) 
				{
					$vVinNumber = str_replace("\"", "", $ln[1]);	// VIN	
					$vLocation = str_replace("\"", "", $ln[29]);	// either R for shipyard or it's the sales location
					( strtoupper($vLocation) == 'R' ) ? $vLocation = "shipyard" : $vLocation = $vStoreInventoryName;
					$sql2 = "UPDATE vehicle_inventory SET location='".addslashes($vLocation)."' WHERE vin_number='".addslashes($vVinNumber)."' AND location!='".addslashes($vLocation)."'";
					$result2 = r_q($sql2);					
					cliprint("\r\nVehicle: ".$vVinNumber." Location: ".$vLocation);	
				} 
			}	

			/* 	MARK AS PROCESSED - START */
			$sql = "UPDATE stores SET processed_inventory='1' WHERE store_inventory_name='".addslashes($vStoreInventoryName)."' LIMIT 1";
			$result = r_q($sql);
			/* 	MARK AS PROCESSED - END */
		}
		else
		{
			cliprint("\r\n".$vFeedFile." is Empty");	
		}		
		cliprint("\r\n");	
		UTILITY_update_cron_successful_log("UPDATE_INVENTORY", "Success");	
	}
	else
	{
		cliprint("\r\nNo Inventory File To Process");
		UTILITY_update_cron_successful_log("UPDATE_INVENTORY", "No Files Found");	
	}	
	/* 	PROCESS AN INVENTORY FILE - END */	
	
	
	// check if all files have been updated.  If so reset them all to run again.
	$sql = "SELECT * from stores WHERE processed_inventory='0' AND hasSales='1' LIMIT 1";
	$result = r_q($sql);
	$class="alt-row";
	if( mysql_num_rows($result) == 0 )
	{
		/* 
		all inventory has been processed.
		reset inventory processing
		*/
		$sql = "UPDATE stores SET processed_inventory='0' WHERE hasSales='1'";
		$result = r_q($sql);
	}
}

$vEndTimer = end_timer($timer);

UTILITY_update_cron_log_script_time("UPDATE_INVENTORY", $vEndTimer);	

cliprint("\r\n\r\nScript completed in ".$vEndTimer."\r\n");


?>