<?php

class inventory_vehicle extends quirk 
{
	var $file_base = "/srv/www/files/inventory/";
	var $dbname = "quirk";
	var $tblname = "vehicle_inventory";
	var $id;
	var $new_used;
	var $store_inventory_name;						
	var $stock_number;						
	var $year;							
	var $plate;							
	var $make_id;								
	var $model_id;									
	var $vin_number;										
	var $mileage;									
	var $ext_color;										
	var $transmission;											
	var $engine;								
	var $internet_price;										
	var $model_code;						
	var $active;	
	var $in_feed;					
	var $source;					
	var $created;						
	var $created_id;						
	var $modified;						
	var $modified_id;						
	var $model_number;
	var $int_color;
	var $model_type;							
	var $trim_level;			
	var $num_of_doors;							
	var $num_of_cylinders;										
	var $drivetrain;							
	var $invoice_price;							
	var $retail_price;								
	var $book_value;						
	var $entry_date;						
	var $certified;					
	var $description;				
	var $options;			
	var $wheelbase;
  var $commercial;
  var $attention;
  var $error_check_off;
  var $locked;
  var $locked_modified;
  var $locked_modified_id;
  var $has_recall;
  var $has_recall_modified;
  var $has_recall_modified_id;
  var $checked_recall;
  var $recall_checked_modified;
  var $recall_checked_modified_id;
  var $recall_completed;
  var $recall_completed_modified;
  var $recall_completed_modified_id;
  var $location;
  var $shipyard_lot;
  var $shipyard_row;
  var $shipyard_spot;    
  var $rental_status;
  var $rental_isd;
  var $rental_plate_exp;
  var $rental_inspection_date;
  var $rental_inspection_state;
  var $rental_notes;

	function inventory_vehicle() 
	{
		$this->id = '';						 
		$this->new_used = '';						 		
		$this->store_inventory_name = '';						
		$this->stock_number = '';						
		$this->year = '';						
		$this->plate = '';						
		$this->make_id = '';						
		$this->model_id = '';						
		$this->vin_number = '';						
		$this->mileage = '';						
		$this->ext_color = '';						
		$this->transmission = '';						
		$this->engine = '';						
		$this->internet_price = '';						
		$this->model_code = '';						
		$this->active = '1';
		$this->in_feed = '1';
		$this->source = 'INVENTORY';
		$this->created = '';
		$this->created_id = '';
		$this->modified = '';						
		$this->modified_id = '';						
		$this->int_color; 
		$this->model_number; 
		$this->model_type; 			
		$this->trim_level;			
		$this->num_of_doors;									
		$this->num_of_cylinders;								
		$this->drivetrain;					
		$this->invoice_price;						
		$this->retail_price;									
		$this->book_value;						
		$this->entry_date;							
		$this->certified;					
		$this->description;						
		$this->options;		
		$this->wheelbase; 
	 	$this->commercial; 
	 	$this->attention='0'; 
	 	$this->error_check_off='0';
	 	$this->locked='0';
	 	$this->locked_modified;
	 	$this->locked_modified_id;
	 	$this->has_recall='0';
	 	$this->has_recall_modified='';
	 	$this->has_recall_modified_id='';
	 	$this->checked_recall='0';
	 	$this->recall_checked_modified='';
	 	$this->recall_checked_modified_id='';
	 	$this->recall_completed='0';
	 	$this->recall_completed_modified='';
	 	$this->recall_completed_modified_id='';
	 	$this->location='';
	 	$this->shipyard_lot='';
	 	$this->shipyard_row='';
	 	$this->shipyard_spot='';  
	 	$this->rental_status=''; 
	 	$this->rental_isd=''; 
	 	$this->rental_plate_exp=''; 
	 	$this->rental_inspection_date=''; 
	 	$this->rental_inspection_state=''; 
	 	$this->rental_notes=''; 
	}
	
	function insert() 
	{
		$sql = "INSERT INTO ".$this->tblname." (id, store_inventory_name, new_used, stock_number, year, plate, make_id, model_id, 
						vin_number, mileage, ext_color, transmission, engine, internet_price, model_code, active, in_feed, int_color,  
						model_number,	model_type,	trim_level,	num_of_doors,	num_of_cylinders,	drivetrain,	invoice_price, retail_price, book_value, entry_date, 
						certified, description,	options, wheelbase,	commercial,	error_check_off, source, location, created) VALUES (
						'', '".addslashes($this->store_inventory_name)."', '".addslashes($this->new_used)."', '".addslashes($this->stock_number)."', 
						'".addslashes($this->year)."', '".$this->plate."', '".$this->make_id."', '".$this->model_id."', 
						'".addslashes($this->vin_number)."', '".addslashes($this->mileage)."', '".addslashes($this->ext_color)."', 
						'".addslashes($this->transmission)."', '".addslashes($this->engine)."', '".addslashes($this->internet_price)."', 
						'".addslashes($this->model_code)."', '".$this->active."', '".$this->in_feed."', 
						'".addslashes($this->int_color)."', '".addslashes($this->model_number)."', 
						'".addslashes($this->model_type)."', '".addslashes($this->trim_level)."', 
						'".addslashes($this->num_of_doors)."', '".addslashes($this->num_of_cylinders)."', '".addslashes($this->drivetrain)."', 
						'".addslashes($this->invoice_price)."', '".addslashes($this->retail_price)."', '".addslashes($this->book_value)."', 
						'".addslashes($this->entry_date)."', '".addslashes($this->certified)."', '".addslashes($this->description)."', 	
						'".addslashes($this->options)."', '".addslashes($this->wheelbase)."', '".addslashes($this->commercial)."', 
						'".$this->error_check_off."', '".$this->source."', '".$this->location."', NOW())";		
		if( $result = $this->_run_query($sql) )
		{
			return true;
		}
		return false;		
	}
	
	function insert_on_the_fly()
	{
		$sql = "INSERT INTO ".$this->tblname." (id, store_inventory_name, new_used, stock_number, 
						year, make_id, model_id, vin_number, 
						mileage, ext_color, active, in_feed, 
						entry_date, source, location, created) VALUES (
						'', '".addslashes($this->store_inventory_name)."', '".addslashes($this->new_used)."', '".addslashes($this->stock_number)."', 
						'".addslashes($this->year)."', '".$this->make_id."', '".$this->model_id."', '".addslashes($this->vin_number)."', 
						'".addslashes($this->mileage)."', '".addslashes($this->ext_color)."', '".$this->active."', '".$this->in_feed."', 
						'".addslashes($this->entry_date)."', '".$this->source."', '".$this->location."', NOW())";		
		if( $result = $this->_run_query($sql) )
		{
			return true;
		}
		return false;				
	}
	
	function update() 
	{
		switch( $this->source )
		{
			case "INVENTORY":
				$sql = "UPDATE ".$this->tblname." SET 
								new_used='".addslashes($this->new_used)."', 
								store_inventory_name='".addslashes($this->store_inventory_name)."', 
								stock_number='".addslashes($this->stock_number)."', 
								year='".addslashes($this->year)."', 
								make_id='".$this->make_id."', 
								model_id='".$this->model_id."', ";
								if( $this->make_id == '' || $this->make_id == '0' )
								{
									// $sql .= "make_id='".$this->make_id."', ";
								}
								else
								{
									$sql .= "make_id='".$this->make_id."', ";					
								}
						
								if( $this->model_id == '' || $this->model_id == '0' )
								{
									// $sql .= "model_id='".$this->model_id."', ";
								}
								else
								{
									$sql .= "model_id='".$this->model_id."', ";					
								}

				$sql .= "mileage='".addslashes($this->mileage)."', 
								ext_color='".addslashes($this->ext_color)."', 
								transmission='".addslashes($this->transmission)."', 
								engine='".addslashes($this->engine)."', 
								internet_price='".addslashes($this->internet_price)."', 
								model_code='".addslashes($this->model_code)."', 
								active='".$this->active."',
								source='".$this->source."',
								in_feed='".$this->in_feed."',			
								int_color='".addslashes($this->int_color)."',				 
								model_number='".addslashes($this->model_number)."',	
								model_type='".addslashes($this->model_type)."',							
								trim_level='".addslashes($this->trim_level)."',			
								num_of_doors='".addslashes($this->num_of_doors)."',								
								num_of_cylinders='".addslashes($this->num_of_cylinders)."',								
								drivetrain='".addslashes($this->drivetrain)."',							
								invoice_price='".addslashes($this->invoice_price)."',							
								retail_price='".addslashes($this->retail_price)."',									
								book_value='".addslashes($this->book_value)."',						
								certified='".addslashes($this->certified)."',				
								description='".addslashes($this->description)."',				
								options='".addslashes($this->options)."',		
								wheelbase='".addslashes($this->wheelbase)."',	
								attention='".addslashes($this->attention)."',	
							 	commercial='".addslashes($this->commercial)."',	
							 	error_check_off='".addslashes($this->error_check_off)."',	
								attention='".addslashes($this->attention)."',	
								location='".addslashes($this->location)."',	
								modified=NOW()
								WHERE vin_number='".$this->vin_number."'";
								break;

			case "RENTALS":					
				// don't change stock number.  
				$sql = "UPDATE ".$this->tblname." SET 
							new_used='".addslashes($this->new_used)."', 
							store_inventory_name='".addslashes($this->store_inventory_name)."', 
							year='".addslashes($this->year)."', 
							plate='".addslashes($this->plate)."', 
							make_id='".$this->make_id."', 
							model_id='".$this->model_id."', 
							mileage='".addslashes($this->mileage)."', 
							ext_color='".addslashes($this->ext_color)."', 
							transmission='".addslashes($this->transmission)."', 
							engine='".addslashes($this->engine)."', 
							internet_price='".addslashes($this->internet_price)."', 
							model_code='".addslashes($this->model_code)."', 
							active='".$this->active."',
							source='".$this->source."',
							in_feed='".$this->in_feed."',			
							int_color='".addslashes($this->int_color)."',				 
							model_number='".addslashes($this->model_number)."',	
							model_type='".addslashes($this->model_type)."',							
							trim_level='".addslashes($this->trim_level)."',			
							num_of_doors='".addslashes($this->num_of_doors)."',								
							num_of_cylinders='".addslashes($this->num_of_cylinders)."',								
							drivetrain='".addslashes($this->drivetrain)."',							
							invoice_price='".addslashes($this->invoice_price)."',							
							retail_price='".addslashes($this->retail_price)."',									
							book_value='".addslashes($this->book_value)."',						
							certified='".addslashes($this->certified)."',				
							description='".addslashes($this->description)."',				
							options='".addslashes($this->options)."',		
							wheelbase='".addslashes($this->wheelbase)."', 
							attention='".addslashes($this->attention)."',		
						 	commercial='".addslashes($this->commercial)."',	
							location='".addslashes($this->location)."',	
						 	error_check_off='".addslashes($this->error_check_off)."',	
							modified=NOW()
							WHERE vin_number='".$this->vin_number."'";	
							break;
		}

		if( $result = $this->_run_query($sql) )
		{
			return true;
		}
		return false;			
	}
	

	function get($vId) 
	{
		$sql = "SELECT * FROM ".$this->tblname." WHERE id='".addslashes($vId)."' LIMIT 1";		
		$result = $this->_run_query($sql);
		$row = mysql_fetch_array($result);
		if($row != '') 
		{
			$this->id = stripslashes($row['id']);
			$this->new_used = stripslashes($row['new_used']);			
			$this->store_inventory_name = stripslashes($row['store_inventory_name']);
			$this->stock_number = stripslashes($row['stock_number']);
			$this->year = stripslashes($row['year']);
			$this->plate = stripslashes($row['plate']);
			$this->make_id = stripslashes($row['make_id']);
			$this->model_id = stripslashes($row['model_id']);
			$this->vin_number = stripslashes($row['vin_number']);
			$this->mileage = stripslashes($row['mileage']);
			$this->ext_color = stripslashes($row['ext_color']);
			$this->transmission = stripslashes($row['transmission']);
			$this->engine = stripslashes($row['engine']);
			$this->internet_price = stripslashes($row['internet_price']);
			$this->model_code = stripslashes($row['model_code']);
			$this->active = stripslashes($row['active']);
			$this->in_feed = stripslashes($row['in_feed']);			
			$this->source = stripslashes($row['source']);			
			$this->created = stripslashes($row['created']);
			$this->modified = stripslashes($row['modified']);
			$this->int_color = stripslashes($row['int_color']);
			$this->model_number = stripslashes($row['model_number']);
			$this->model_type = stripslashes($row['model_type']);
			$this->trim_level = stripslashes($row['trim_level']);
			$this->num_of_doors = stripslashes($row['num_of_doors']);
			$this->num_of_cylinders = stripslashes($row['num_of_cylinders']);
			$this->drivetrain = stripslashes($row['drivetrain']);
			$this->invoice_price = stripslashes($row['invoice_price']);
			$this->retail_price = stripslashes($row['retail_price']);
			$this->book_value = stripslashes($row['book_value']);
			$this->entry_date = stripslashes($row['entry_date']);
			$this->certified = stripslashes($row['certified']);
			$this->description = stripslashes($row['description']);
			$this->options = stripslashes($row['options']);
			$this->wheelbase = stripslashes($row['wheelbase']);
			$this->commercial = stripslashes($row['commercial']);
			$this->attention = stripslashes($row['attention']);			
			$this->error_check_off = stripslashes($row['error_check_off']);
		 	$this->locked = stripslashes($row['locked']);
		 	$this->location = stripslashes($row['location']);
		 	$this->locked_modified = stripslashes($row['locked_modified']);
		 	$this->locked_modified_id = stripslashes($row['locked_modified_id']);
		 	$this->has_recall = stripslashes($row['has_recall']);
		 	$this->has_recall_modified = stripslashes($row['has_recall_modified']);
		 	$this->has_recall_modified_id = stripslashes($row['has_recall_modified_id']);
		 	$this->checked_recall = stripslashes($row['checked_recall']);
		 	$this->recall_checked_modified = stripslashes($row['recall_checked_modified']);
		 	$this->recall_checked_modified_id = stripslashes($row['recall_checked_modified_id']);
		 	$this->recall_completed = stripslashes($row['recall_completed']);
		 	$this->recall_completed_modified = stripslashes($row['recall_completed_modified']);
		 	$this->recall_completed_modified_id = stripslashes($row['recall_completed_modified_id']);
		 	$this->shipyard_lot = stripslashes($row['shipyard_lot']);
		 	$this->shipyard_row = stripslashes($row['shipyard_row']);
		 	$this->shipyard_spot = stripslashes($row['shipyard_spot']);
		 	$this->rental_status = stripslashes($row['rental_status']);
		 	$this->rental_isd = stripslashes($row['rental_isd']);
		 	$this->rental_plate_exp = stripslashes($row['rental_plate_exp']);
		 	$this->rental_inspection_date = stripslashes($row['rental_inspection_date']);
		 	$this->rental_inspection_state = stripslashes($row['rental_inspection_state']);
		 	$this->rental_notes = stripslashes($row['rental_notes']);
			return true;		
		}
		return false;		
	}

	function exists() 
	{
		$vOr = '';	
		$vSqlWhere= '';	
		$sql = "SELECT id FROM ".$this->tblname." WHERE UPPER(vin_number)='".addslashes(strtoupper($this->vin_number))."' LIMIT 1";		
		$result = $this->_run_query($sql);
		if(mysql_num_rows($result) == 1) 
		{
			$row = mysql_fetch_array($result);
			$this->id = $row['id'];
			return true;		
		}
		return false;		
	}

	function get_by_stocknumber($vStocknum) 
	{
		$ret = false;
		$sql = "SELECT id FROM ".$this->tblname." WHERE stock_number='".addslashes($vStocknum)."' LIMIT 1";		
		$result = $this->_run_query($sql);
		$row = mysql_fetch_array($result);
		if($row['id'] != '') {
			$ret = $this->get(stripslashes($row['id']));
		}
		return $ret;		
	}

	function get_by_vin($vVin) 
	{
		$ret = false;
		$sql = "SELECT id FROM ".$this->tblname." WHERE vin_number='".addslashes($vVin)."' LIMIT 1";		
		$result = $this->_run_query($sql);
		$row = mysql_fetch_array($result);
		if($row['id'] != '') {
			$ret = $this->get(stripslashes($row['id']));
		}
		return $ret;
	}
	
	function get_all($vWhere) 
	{
		$sql = "SELECT * FROM ".$this->tblname.$vWhere;		
		$result = $this->_run_query($sql);
		return $result;
	}
	
	function set_all_in_feed_zero($vStoreInventoryName='')
	{
		if( $vStoreInventoryName == '' )
		{
			/*
			$sql = "UPDATE ".$this->tblname." SET in_feed='0' WHERE in_feed='1'";		
			$result = $this->_run_query($sql);
			*/
		}
		else
		{
			$sql = "UPDATE ".$this->tblname." SET in_feed='0' WHERE store_inventory_name='".addslashes($vStoreInventoryName)."' AND in_feed='1'";		
			$result = $this->_run_query($sql);			
		}
		return $result;	
	}
	
	function set_not_in_feed_inactive($vStoreInventoryName='')
	{
		$vTotSetInActive = 0;
		if($vStoreInventoryName == '')
		{
			/*
			$sql = "UPDATE ".$this->tblname." SET active='0' WHERE in_feed='0'";		
			$result = $this->_run_query($sql);
			$vTotSetInActive = mysql_affected_rows();
			*/
		}
		else
		{
			$sql = "UPDATE ".$this->tblname." SET active='0' WHERE store_inventory_name='".addslashes($vStoreInventoryName)."' AND in_feed='0'";		
			$result = $this->_run_query($sql);
			$vTotSetInActive = mysql_affected_rows();			
		}
		return $vTotSetInActive;	
	}

	function set_in_feed() 
	{
		$sql = "UPDATE ".$this->tblname." SET 
						active='".$this->active."',
						source='".$this->source."',
						in_feed='".$this->in_feed."' 			
						WHERE vin_number='".$this->vin_number."'";
		if( $result = $this->_run_query($sql) )
		{
			return true;
		}
		return false;			
	}



	function web_update() 
	{
		$vTempVehicle = new inventory_vehicle;
		$vTempVehicle->get( $this->id );

		$sql = "UPDATE ".$this->tblname." SET 
						new_used='".addslashes($this->new_used)."', 
						store_inventory_name='".addslashes($this->store_inventory_name)."', 
						stock_number='".addslashes($this->stock_number)."', 
						year='".addslashes($this->year)."', 
						plate='".addslashes($this->plate)."', 
						make_id='".$this->make_id."', 
						model_id='".$this->model_id."', 
						mileage='".addslashes($this->mileage)."', 
						ext_color='".addslashes($this->ext_color)."', 
						transmission='".addslashes($this->transmission)."', 
						engine='".addslashes($this->engine)."', 
						internet_price='".addslashes($this->internet_price)."', 
						model_code='".addslashes($this->model_code)."', 
						active='".$this->active."',
						source='".$this->source."',
						int_color='".addslashes($this->int_color)."',				 
						model_number='".addslashes($this->model_number)."',	
						model_type='".addslashes($this->model_type)."',							
						trim_level='".addslashes($this->trim_level)."',			
						num_of_doors='".addslashes($this->num_of_doors)."',								
						num_of_cylinders='".addslashes($this->num_of_cylinders)."',								
						drivetrain='".addslashes($this->drivetrain)."',							
						invoice_price='".addslashes($this->invoice_price)."',							
						retail_price='".addslashes($this->retail_price)."',									
						book_value='".addslashes($this->book_value)."',						
						certified='".addslashes($this->certified)."',				
						description='".addslashes($this->description)."',				
						options='".addslashes($this->options)."',		
						wheelbase='".addslashes($this->wheelbase)."',	
					 	commercial='".addslashes($this->commercial)."',	
					 	error_check_off='".addslashes($this->error_check_off)."',	
					 	has_recall='".addslashes($this->has_recall)."',	
					 	checked_recall='".addslashes($this->checked_recall)."',
					 	shipyard_lot='".addslashes($this->shipyard_lot)."',
					 	shipyard_row='".addslashes($this->shipyard_row)."',
					 	shipyard_spot='".addslashes($this->shipyard_spot)."',
					 	location='".addslashes($this->location)."',
						rental_isd='".addslashes($this->rental_isd)."',
						rental_plate_exp='".addslashes($this->rental_plate_exp)."',												
						rental_inspection_date='".addslashes($this->rental_inspection_date)."',	
						rental_status='".addslashes($this->rental_status)."',
						rental_notes='".addslashes($this->rental_notes)."',
						entry_date='".addslashes($this->entry_date)."',		";

						if( $vTempVehicle->locked != $this->locked )
						{
							$sql .= "locked_modified=NOW(), locked_modified_id='".addslashes($_SESSION['Q_USER']['id'])."', ";	
						}
			
						if( $vTempVehicle->checked_recall != $this->checked_recall )
						{
							$sql .= "recall_checked_modified=NOW(), recall_checked_modified_id='".addslashes($_SESSION['Q_USER']['id'])."', ";	
						}
			
						if( $vTempVehicle->has_recall != $this->has_recall )
						{
							$sql .= "has_recall_modified=NOW(), has_recall_modified_id='".addslashes($_SESSION['Q_USER']['id'])."', ";	
						}
			
						if( $vTempVehicle->recall_completed != $this->recall_completed )
						{
							$sql .= "recall_completed_modified=NOW(), recall_completed_modified_id='".addslashes($_SESSION['Q_USER']['id'])."', ";	
						}
					 	
						$sql .= "modified=NOW(), 
										 modified_id='".addslashes($_SESSION['Q_USER']['id'])."' 
										 WHERE id='".$this->id."'";				

		if( $result = $this->_run_query($sql) )
		{
			return true;
		}
		return false;			
	}

	function web_update_shipyard() 
	{
		$sql = "UPDATE ".$this->tblname." SET 
						store_inventory_name='".addslashes($this->store_inventory_name)."', 
					 	shipyard_lot='".addslashes($this->shipyard_lot)."',
					 	shipyard_row='".addslashes($this->shipyard_row)."',
					 	shipyard_spot='".addslashes($this->shipyard_spot)."',
					 	description='".addslashes($this->description)."',	
					 	location='".addslashes($this->location)."', 
						modified=NOW(), 
						modified_id='".addslashes($_SESSION['Q_USER']['id'])."' 
						WHERE id='".$this->id."'";				

		if( $result = $this->_run_query($sql) )
		{
			return true;
		}
		return false;			
	}
	
	function web_add()
	{
		$sql = "INSERT INTO ".$this->tblname." (id, store_inventory_name, new_used, stock_number, year, plate, make_id, model_id, 
						vin_number, mileage, ext_color, transmission, engine, internet_price, model_code, active, in_feed, int_color,  
						model_number,	model_type,	trim_level,	num_of_doors,	num_of_cylinders,	drivetrain,	invoice_price, retail_price, book_value, entry_date, 
						certified, description,	options, wheelbase,	commercial,	error_check_off, source, location, shipyard_lot, shipyard_row, shipyard_spot, created) VALUES (
						'', '".addslashes($this->store_inventory_name)."', '".addslashes($this->new_used)."', '".addslashes($this->stock_number)."', 
						'".addslashes($this->year)."', '".$this->plate."', '".$this->make_id."', '".$this->model_id."', 
						'".addslashes($this->vin_number)."', '".addslashes($this->mileage)."', '".addslashes($this->ext_color)."', 
						'".addslashes($this->transmission)."', '".addslashes($this->engine)."', '".addslashes($this->internet_price)."', 
						'".addslashes($this->model_code)."', '".$this->active."', '".$this->in_feed."', 
						'".addslashes($this->int_color)."', '".addslashes($this->model_number)."', 
						'".addslashes($this->model_type)."', '".addslashes($this->trim_level)."', 
						'".addslashes($this->num_of_doors)."', '".addslashes($this->num_of_cylinders)."', '".addslashes($this->drivetrain)."', 
						'".addslashes($this->invoice_price)."', '".addslashes($this->retail_price)."', '".addslashes($this->book_value)."', 
						'".addslashes($this->entry_date)."', '".addslashes($this->certified)."', '".addslashes($this->description)."', 	
						'".addslashes($this->options)."', '".addslashes($this->wheelbase)."', '".addslashes($this->commercial)."', 
						'".$this->error_check_off."', '".$this->source."', '".$this->location."', 
						'".addslashes($this->shipyard_lot)."', '".addslashes($this->shipyard_row)."', '".addslashes($this->shipyard_spot)."',						
						NOW())";		
		if( $result = $this->_run_query($sql) )
		{
			$this->id = mysql_insert_id();
			return true;
		}
		return false;				
	}

	function web_add_shipyard()
	{
		$sql = "INSERT INTO ".$this->tblname." (id, store_inventory_name, new_used, stock_number, year, make_id, model_id, 
						vin_number, mileage, ext_color, transmission, active, int_color, entry_date, 
						description,	source, location, shipyard_lot, shipyard_row, shipyard_spot, created) VALUES (
						'', '".addslashes($this->store_inventory_name)."', '".addslashes($this->new_used)."', '".addslashes($this->stock_number)."', 
						'".addslashes($this->year)."', '".$this->make_id."', '".$this->model_id."', 
						'".addslashes($this->vin_number)."', '".addslashes($this->mileage)."', '".addslashes($this->ext_color)."', 
						'".addslashes($this->transmission)."', '".$this->active."', '".addslashes($this->int_color)."', '".addslashes(date("Y-m-d"))."', 
						'".addslashes($this->description)."', '".$this->source."', '".$this->location."', 
						'".addslashes($this->shipyard_lot)."', '".addslashes($this->shipyard_row)."', '".addslashes($this->shipyard_spot)."', NOW())";		
		if( $result = $this->_run_query($sql) )
		{
			$this->id = mysql_insert_id();
			return true;
		}
		return false;				
	}


	function rentals_update() 
	{
		$vTempVehicle = new inventory_vehicle;
		$vTempVehicle->get( $this->id );

		$sql = "UPDATE ".$this->tblname." SET 
						store_inventory_name='".addslashes($this->store_inventory_name)."', 
						stock_number='".addslashes($this->stock_number)."', 
						year='".addslashes($this->year)."', 
						plate='".addslashes($this->plate)."', 
						make_id='".addslashes($this->make_id)."', 
						model_id='".addslashes($this->model_id)."', 
						mileage='".addslashes($this->mileage)."', 
						int_color='".addslashes($this->int_color)."',				 
						ext_color='".addslashes($this->ext_color)."', 
						transmission='".addslashes($this->transmission)."', 
						source='".addslashes($this->source)."',
						trim_level='".addslashes($this->trim_level)."',			
					 	location='".addslashes($this->location)."',
						rental_isd='".addslashes($this->rental_isd)."',
						rental_plate_exp='".addslashes($this->rental_plate_exp)."',												
						rental_inspection_date='".addslashes($this->rental_inspection_date)."',	
						rental_status='".addslashes($this->rental_status)."',
						rental_notes='".addslashes($this->rental_notes)."',
						entry_date='".addslashes($this->entry_date)."',
					  modified_id='".addslashes($_SESSION['Q_USER']['id'])."' 
					  WHERE id='".addslashes($this->id)."'";				

		if( $result = $this->_run_query($sql) )
		{
			return true;
		}
		return false;			
	}

	
}

?>
