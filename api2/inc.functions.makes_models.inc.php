<?php
/* GENERAL MAKE-MODEL FUNCTIONS */
//below variables and first two functions, fix and get_data taken from inc.functions.php
$dbuser="quirkspe_ncsuser";
$dbpass="(OTiJdM%.JUs";
//$chandle = mysql_connect("localhost", $dbuser, $dbpass) or die("Connection Failure to Database" . mysql_error()); depreciated	
$chandle = mysqli_connect("localhost", $dbuser, $dbpass, "quirkspe_ncs") or die("Connection Failure to Database" . mysqli_connect_error());
//mysql_select_db("quirkspe_ncs", $chandle) or die ($dbname . " Database not found. User: " . $dbuser); depreciated	
mysqli_select_db($chandle, "quirkspe_ncs") or die ($dbname . " Database not found. User: " . $dbuser);

function fix($input){	
	$output = ucwords(strtolower(stripslashes(trim($input))));
	return $output;
}

function get_data($dbname,$query){ //for returning one row only.
	global $chandle;
	$row = '';
	if( $query != '' )
	{
		//$result = mysql_query($query,$chandle);
		$result = mysqli_query($chandle,$query);
		if ($result) {
			//$row = mysql_fetch_array($result);
			$row = mysqli_fetch_array($result);
		}
	}
	return $row;
}

function get_make_model_name($model_id) {
	$sql = "SELECT make.name AS make_name, model.name AS model_name FROM makes AS make
					INNER JOIN models as model ON make.id=model.make_id 
					WHERE model.id='".$model_id."'";
	$row = get_data("quirk", $sql);
	$make_model = stripslashes($row['make_name'])." ".stripslashes($row['model_name']);
	return $make_model; 
}

function get_make_model_array($model_id) {
	$vArr = array();
	$sql = "SELECT make.id AS make_id, make.name AS make_name, model.id AS model_id, model.name AS model_name FROM makes AS make
					INNER JOIN models as model ON make.id=model.make_id 
					WHERE model.id='".$model_id."'";
	$row = get_data("quirk", $sql);
	$vArr['make_name'] = stripslashes($row['make_name']);
	$vArr['model_name'] = stripslashes($row['model_name']);
	$vArr['make_id'] = stripslashes($row['make_id']);
	$vArr['model_id'] = stripslashes($row['model_id']);
	return $vArr; 
}

/* AJAX MAKE-MODEL FUNCTIONS */

function get_models_for_make($make_id) {
	// used with ajax_get_make_models.inc.php  
	$sql = "SELECT make.id AS make_id, make.name AS make_name, make.active AS make_active, 
					model.id AS model_id, model.name AS model_name, model.active AS model_active 
					FROM makes AS make INNER JOIN models as model ON make.id=model.make_id 
					WHERE make_id='".$make_id."' AND make.active='1' AND model.active='1' ORDER BY model_name";
	$result = run_query("quirk", $sql);
	return $result; // RETURN RESULT TO AJAX
}







/* MAKE FUNCTIONS */

function get_makes_dropdown($selected) {
	$str = '';
	$sql = "SELECT * FROM makes WHERE active='1' ORDER BY name";
	$result = run_query("quirk", $sql);
	//while($row = mysql_fetch_array($result))
	while($row = mysqli_fetch_array($result))
	{
		$str .= "<option value='".stripslashes($row['id'])."'".( ($row['id'] == $selected) ? " selected='selected'" : "" ).">".stripslashes($row['name'])."</option>";
	}
	echo $str;
}






function get_make_id($make_name) {
	$sql = "SELECT * FROM makes WHERE LOWER(name)='".addslashes(strtolower($make_name))."'";
	$row = get_data("quirk", $sql);

	if( $row['id'] == '' )
	{
		// check alternate name
		$sql = "SELECT * FROM makes WHERE LOWER(mfr_cd)='".addslashes(strtolower($make_name))."'";
		$row = get_data("quirk", $sql);		
	}

	return stripslashes($row['id']); 
}

function add_make() {
	$vErrorsArr = array();
	
	if( trim(['txt_name']) == '' ) {
		$vErrorsArr[] = "Please enter a Make Name";
	}
	else
	{
		$vNewMakeId = get_make_id( trim(strtolower(['txt_name'])) );
		if($vNewMakeId != '') {
			$vErrorsArr[] = "Make Name Already Exists";				
		}
	}
	
	( trim(['chk_active']) == '1') ? $vActive='1' : $vActive='0';
	
	if( count($vErrorsArr) == 0) {
		$sql = "INSERT INTO makes (id, mfr_cd, name, active) 
						VALUES ('', '".trim(addslashes(['txt_manufacturers_code']))."', 
						'".trim(addslashes(['txt_name']))."', '".$vActive."')";
		$result = r_q($sql);
	}
	return $vErrorsArr;
}

function get_all_makes() {
	$sql = "SELECT * FROM makes ORDER BY name";
	$result = run_query("quirk", $sql);	
	return $result;
}

function get_make($make_id) {
	$sql = "SELECT * FROM makes WHERE id='".addslashes($make_id)."'";
	$row = get_data("quirk", $sql);	
	return $row;	
}

function get_make_name($make_id) {
	$sql = "SELECT name FROM makes WHERE id='".$make_id."'";
	$row = get_data("quirk", $sql);
	return stripslashes($row['name']); 
}

function update_make() {
	$vErrorsArr = array();
	$vOrigRow = get_make( trim(['hid_make_id']) );	
	
	if( trim(['txt_name']) == '' ) {
		$vErrorsArr[] = "Please enter a Make Name";
	}
	else
	{
		if( stripslashes(strtolower($vOrigRow['name'])) != trim(strtolower(['txt_name'])) ) {
			// make name has changed.  Check if already exists
			$vNewMakeId = get_make_id( trim(strtolower(['txt_name'])) );
			if($vNewMakeId != '') {
				$vErrorsArr[] = "Make Name Already Exists";				
			}
		}
	}
	
	( trim(['chk_active']) == '1') ? $vActive='1' : $vActive='0';
	
	if( count($vErrorsArr) == 0) {
		$sql = "UPDATE makes SET
		        name='".trim(addslashes(['txt_name']))."', 
		        mfr_cd='".trim(addslashes(['txt_manufacturers_code']))."', 
		        active='".$vActive."' 
		        WHERE id='".trim(addslashes(['hid_make_id']))."'";
		$result = r_q($sql);
	}
	return $vErrorsArr;
}

function delete_make($make_id) {
	$sql = "DELETE FROM makes WHERE id='".addslashes($make_id)."'";
	$result = r_q($sql);
}










/* MODEL FUNCTIONS */

function get_model($model_id) {
	$sql = "SELECT * FROM models WHERE id='".addslashes($model_id)."'";
	$row = get_data("quirk", $sql);	
	return $row;	
}

function add_model() {
	$vErrorsArr = array();
	
	if( trim(['txt_name']) == '' ) {
		$vErrorsArr[] = "Please enter a Model Name";
	}
	else
	{
		$vNewModelId = get_model_id4Make( trim(strtolower(['txt_name'])), trim(['sel_make_id']) );
		if($vNewModelId != '') {
			$vErrorsArr[] = "Model Name Already Exists";				
		}
	}

	( trim(['chk_active']) == '1') ? $vActive='1' : $vActive='0';		

	if( count($vErrorsArr) == 0 ) {
		$sql = "INSERT INTO models (id, make_id, mfr_cd, name, active) 
						VALUES ('', '".trim(addslashes(['sel_make_id']))."', 
						'".trim(addslashes(['txt_manufacturers_code']))."', 
						'".trim(addslashes(['txt_name'])) ."', '".$vActive."')";
		$result = r_q($sql);	
	}	
	return $vErrorsArr;
}

function get_model_name($model_id) {
	$sql = "SELECT * FROM models WHERE id='".$model_id."'";
	$row = get_data("quirk", $sql);
	return stripslashes($row['name']); 
}

function get_models_dropdown($selected_make, $selected_model) {
	$str = '';
	$sql = "SELECT make.id AS make_id, make.name AS make_name, model.id AS model_id, model.name AS model_name FROM makes AS make
					INNER JOIN models as model ON make.id=model.make_id WHERE make_id='".$selected_make."' AND make.active='1' AND model.active='1' ORDER BY make.name, model.name";
	$result = run_query("quirk", $sql);
	//while($row = mysql_fetch_array($result))
	while($row = mysqli_fetch_array($result))
	{
		$str .= "<option value='".stripslashes($row['model_id'])."'".( ($row['model_id'] == $selected_model) ? " selected='selected'" : "" ).">".stripslashes($row['model_name'])."</option>";
	}
	echo $str;
}

function get_model_id($model_name) {
	$sql = "SELECT * FROM models WHERE LOWER(name)='".addslashes(strtolower($model_name))."'";
	$row = get_data("quirk", $sql);
	
	if( $row['id'] == '' )
	{
		// check for alternate name
		$sql = "SELECT * FROM models WHERE LOWER(mfr_cd)='".addslashes(strtolower($model_name))."'";
		$row = get_data("quirk", $sql);
	}
	
	return stripslashes($row['id']); 
}

function get_model_id4Make($model_name, $make_id) {
	$sql = "SELECT * FROM models WHERE LOWER(name)='".addslashes(strtolower($model_name))."' AND make_id='".$make_id."'";
	$row = get_data("quirk", $sql);
	return stripslashes($row['id']); 	
}

function get_all_models($make_id) {
	$sql = "SELECT * FROM models WHERE make_id='".addslashes($make_id)."' ORDER BY name";
	$result = run_query("quirk", $sql);
	return $result;
}

function get_number_models($make_id) {
	$sql = "SELECT count(id) AS tot FROM models WHERE make_id='".$make_id."'";
	$row = get_data("quirk", $sql);
	return $row['tot'];	
}

function update_model() {
	$vErrorsArr = array();
	$vOrigRow = get_model( trim(['hid_model_id']) );	
	
	if( trim(['txt_name']) == '' ) {
		$vErrorsArr[] = "Please enter a Model Name";
	}
	else
	{
		if( stripslashes(strtolower($vOrigRow['name'])) != trim(strtolower(['txt_name'])) ) {
			// Model name has changed.  Check if already exists.  Include Make Id.
			$vNewModelId = get_model_id4Make( trim(strtolower(['txt_name'])), trim(['sel_make_id']) );
			if($vNewModelId != '') {
				$vErrorsArr[] = "Model Name Already Exists";				
			}
		}
	}
	
	( trim(['chk_active']) == '1') ? $vActive='1' : $vActive='0';
	
	if( count($vErrorsArr) == 0) {
		$sql = "UPDATE models SET
		        name='".trim(addslashes(['txt_name']))."', 
		        make_id='".trim(addslashes(['sel_make_id']))."', 
		        mfr_cd='".trim(addslashes(['txt_manufacturers_code']))."', 
		        active='".$vActive."' 
		        WHERE id='".trim(addslashes(['hid_model_id']))."'";
		$result = r_q($sql);
	}
	return $vErrorsArr;
}

function delete_model($model_id) {
	$sql = "DELETE FROM models WHERE id='".addslashes($model_id)."'";
	$result = r_q($sql);
}






/* Resource Automotive */

function ra_get_makes_dropdown($selected) 
{
	$str = '';
	$sql = "SELECT * FROM makes WHERE active='1' ORDER BY name";
	$result = r_q($sql);
	//while($row = mysql_fetch_array($result))
	while($row = mysqli_fetch_array($result))
	{
		$str .= "<option value='".stripslashes($row['id'])."'".( ($row['id'] == $selected) ? " selected='selected'" : "" ).">".stripslashes($row['name'])."</option>";
	}
	echo $str;
}

function ra_get_models_dropdown($selected_make, $selected_model) 
{
	$str = '';
	$sql = "SELECT make.id AS make_id, make.name AS make_name, model.id AS model_id, model.name AS model_name FROM makes AS make
					INNER JOIN models as model ON make.id=model.make_id 
					WHERE make_id='".$selected_make."' AND make.active='1' 
					ORDER BY make.name, model.name";
	$result = r_q($sql);
	//while($row = mysql_fetch_array($result))
	while($row = mysqli_fetch_array($result))
	{
		$str .= "<option value='".stripslashes($row['model_id'])."'".( ($row['model_id'] == $selected_model) ? " selected='selected'" : "" ).">".stripslashes($row['model_name'])."</option>";
	}
	echo $str;
}

function ra_get_models_for_make($make_id) 
{
	// used with ajax_get_make_models.inc.php  
	/*
	$sql = "SELECT make.id AS make_id, make.name AS make_name, make.active AS make_active, 
					model.id AS model_id, model.name AS model_name, model.active AS model_active, model.ra_new 
					FROM makes AS make 
					INNER JOIN models as model ON make.id=model.make_id 
					WHERE make_id='".$make_id."' AND make.active='1'
					AND ( model.active = '1' OR model.ra_new='1' )
					ORDER BY model_name";
	$result = r_q($sql);
	*/

	$sql = "SELECT DISTINCT make.id AS make_id, make.name AS make_name, make.active AS make_active, 
					model.id AS model_id, model.name AS model_name, model.active AS model_active, model.ra_new, 
					vc.vehicle_class, vc.warranty_term, vc.warranty_mileage FROM makes AS make 
					INNER JOIN models as model ON make.id=model.make_id 
					LEFT JOIN vehicle_classes AS vc ON vc.model_id=model.id 
					WHERE make.id='".$make_id."' AND make.active='1'
					AND model.ra_new='1'
					ORDER BY model_name";
	$result = r_q($sql);




	return $result; // RETURN RESULT TO AJAX
}



?>