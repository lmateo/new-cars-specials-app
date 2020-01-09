#!/usr/bin/php5 -q

<?php

$ftp_server = "iol.homenetinc.com";
$ftp_user_name = "hndatafeed";
$ftp_user_pass = "gx8m6";
$vSource = "/srv/www/files/inventory/homenet/quirk-auto-dealers-homenet.csv";
$vDestination = "/Quirk Cars/quirk-auto-dealers-homenet.csv";

/*
$ftp_server = "71.126.249.75";
$ftp_user_name = "pwalker";
$ftp_user_pass = "sp00ler.*1@1";
$vSource = "/srv/www/files/inventory/homenet/quirk-auto-dealers-homenet.csv";
$vDestination = "quirk-auto-dealers-homenet.csv";
*/

$vFtpConnected = FALSE;

$conn_id = ftp_connect($ftp_server); 

if( !$conn_id )
{
	  $vFtpConnected = FALSE;
	  echo " ... Failed Connecting to Server!\r\n\r\n";
}
else
{	
  echo " ... Connected to Server!\r\n\r\n";
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
	if( !$login_result ) 
	{ 
	  $vFtpConnected = FALSE;
	  echo " ... Failed Logging into Server!\r\n\r\n";
	} 
	else 
	{
	  $vFtpConnected = TRUE;
	  echo " ... Logged into Server!\r\n\r\n";
	}
}


if( $vFtpConnected )
{
	ftp_pasv ($conn_id, true);
	$upload = ftp_put($conn_id, $vDestination, $vSource, FTP_BINARY); 
	if(!$upload) 
	{ 
		echo " ... Failed Uploading!\r\n\r\n";
	}
	else
	{	
		echo " ... Uploaded File\r\n\r\n";
	}

	ftp_close($conn_id); 
}
?>

