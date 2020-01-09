<?php
  /**
   * Manual Inventory file upload to mysql
   *
   * @package Quirk Digital Marketing
   * @author Lorenzo Mateo
   * @copyright 2017
   * @version $Id: qinvupload.php, v1.00 2017-17-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!$row = $db->first(Users::aTable, null, array('id' => $auth->uid))) : Message::invalid("ID" . $auth->uid); return; endif;?>

<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="car icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->QI_TITLE;?> <small>/ <?php echo $row->username;?></small> </div>
      <p><?php echo Lang::$word->M_INFO . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label>Mysql Server address (or) Host name</label>
        <label class="input">
          <input type="text" name="mysql" id="mysql" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->USERNAME;?></label>
        <label class="input">
          <input type="text" name="username" id="username">
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </label>
      </div>
     
    </div>
    <div class="two fields">
     <div class="field">
        <label><?php echo Lang::$word->PASSWORD;?></label>
        <div class="wojo labeled icon input">
          <input type="text" name="password" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label>Database Name</label>
        <div class="wojo labeled icon input">
          <input type="text" name="db" id="db" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      </div>
      
     <div class="two fields">
      <div class="field">
        <label>Table Name</label>
        <div class="wojo labeled icon input">
          <input type="text" name="table" id="table" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label>Name of the File</label>
        <div class="wojo labeled icon input">
          <input type="text" name="csv" id="csv" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
<!--     <div class="two fields"> -->
<!--       <div class="field"> 
        <label><?php echo Lang::$word->AVATAR;?></label>
        <input type="file" name="avatar" data-type="image" data-exist="<?php echo ($row->avatar) ? UPLOADURL . 'avatars/' . $row->avatar : UPLOADURL . 'avatars/blank.png';?>" accept="image/png, image/jpeg">-->
<!--       </div> -->
<!--       <div class="field"> </div> -->
<!--     </div> -->
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
    <button type="submit" class="wojo positive button">Upload</button>
    </div>
  </form>
</div>
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['username'])&&isset($_POST['mysql'])&&isset($_POST['db'])&&isset($_POST['username']))
{
$sqlname=$_POST['mysql'];
$username=$_POST['username'];
$table=$_POST['table'];
if(isset($_POST['password']))
{
$password=$_POST['password'];
}
else
{
$password= '';
}
$db=$_POST['db'];
$file=$_POST['csv'];
$cons= mysqli_connect("$sqlname", "$username","$password","$db") or die(mysql_error());

$result1=mysqli_query($cons,"select count(*) count from $table");
$r1=mysqli_fetch_array($result1);
$count1=(int)$r1['count'];
//If the fields in CSV are not seperated by comma(,)  replace comma(,) in the below query with that  delimiting character 
//If each tuple in CSV are not seperated by new line.  replace \n in the below query  the delimiting character which seperates two tuples in csv
// for more information about the query http://dev.mysql.com/doc/refman/5.1/en/load-data.html
mysqli_query($cons, '
    LOAD DATA LOCAL INFILE "'.$file.'"
        INTO TABLE '.$table.'
        FIELDS TERMINATED BY \',\' ENCLOSED BY \'\"\' ESCAPED BY \'\\\\\' 
    	LINES TERMINATED BY \'\\r\\n\' 
		IGNORE 1 LINES')or die(mysqli_error($cons));

$result2=mysqli_query($cons,"select count(*) count from $table");
$r2=mysqli_fetch_array($result2);
$count2=(int)$r2['count'];

$count=$count2-$count1;
if($count>0)
echo "<section id='content'>";
echo "<div class='width-wrapper width-wrapper__inset1'>";
echo "<div class='wrapper1'>";
echo "<div class='container'>";
echo "<div class='row'>";
echo "<div class='container row margin-bottom-1em'>";
echo "<div class=\"alert alert-success alert-dismissable\">Success <b> File: $file with a total of $count records have been added to the table $table </b> </div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</section>";

$delete = "DELETE FROM qinventory_temp";
$result3 = mysqli_query($cons,$delete);
	

}
else{
echo "Mysql Server address/Host name ,Username , Database name ,Table name , File name are the Mandatory Fields";
}

?>