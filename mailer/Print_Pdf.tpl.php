<?php
  /**
   * Pdf Template
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: print_pdf.tpl.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if($row):?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo LANG::$word->TRX_INVOICE;?></title>
<style type="text/css">
body {
  background-color: #fff;
  color: #333;
  font-family: DejaVu Sans, Helvetica, Times-Roman;
  font-size: 1.2em;
  margin: 0;
  padding: 0
}
table {
  font-size: 75%;
  width: 100%;
  border-collapse: separate;
  border-spacing: 2px
}
th,
td {
  position: relative;
  text-align: left;
  border-radius: .25em;
  border-style: solid;
  border-width: 1px;
  padding: .5em
}
th {
  background: #EEE;
  border-color: #BBB
}
td {
  border-color: #DDD
}
td.none {
  border: 0
}
h1 {
  font: bold 100% sans-serif;
  letter-spacing: .5em;
  text-align: center;
  text-transform: uppercase
}
table.inventory {
  clear: both;
  width: 100%
}
table.inventory th,
table.payments th {
  font-weight: 700;
  text-align: center
}
table.inventory td:nth-child(1) {
  width: 52%
}
table.payments {
  padding-top: 20px
}
table.balance th,
table.balance td {
  width: 50%
}
.green {
  background-color: #D5EEBE;
  color: #689340
}
.blue {
  background-color: #D0EBFB;
  color: #4995B1
}
.red {
  background-color: #FAD0D0;
  color: #AF4C4C
}
.yellow {
  background-color: #FFC;
  color: #BBB840
}
#aside {
  padding-top: 30px;
  font-size: 65%
}
small {
  font-size: 75%;
  line-height: 1.5em
}
table.inventory td.right {
  text-align: right;
  width: 12%
}
table.payments td.right,
table.balance td {
  text-align: right
}
#footer {
  position: fixed;
  bottom: 0px;
  left: 0px;
  right: 0px;
  height: 100px;
  text-align: center;
  border-top: 2px solid #eee;
  font-size: 85%;
  padding-top: 5px
}
.divider {
  border-bottom: 2px solid #ddd;
}
@page {
  margin: 30px;
  margin-footer: 5mm;
  footer: html_footer;
}
</style>
</head>
<body>
<table border="0" class="divider">
  <tr>
    <td style="width: 60%;" valign="top" class="none"><?php if (file_exists(UPLOADS . 'print_logo.png')):?>
      <img alt="" src="<?php echo UPLOADS . 'print_logo.png';?>">
      <?php else:?>
      <?php echo App::get("Core")->company;?>
      <?php endif;?></td>
    <td valign="top"  class="none" style="width:40%;text-align: right"><h4 style="margin:0px;padding:0px;font-size: 12px;">Invoice: # <?php echo $row->invid . '-' . $row->id;?> </h4>
      <h4 style="margin:0px;padding:0px;font-size: 12px;"><?php echo Utility::dodate("short_date", $row->created);?></h4></td>
  </tr>
</table>
<table style="padding-top:25px">
  <tr>
    <td valign="top" style="width:60%">Payment To</td>
    <td colspan="2" valign="top" style="width:40%">Bill To</td>
  </tr>
  <tr>
    <td valign="top" style="width:60%"><p><b> <?php echo App::get("Core")->company;?></b><br />
        <?php echo App::get("Core")->site_email;?><br />
        <?php echo App::get("Core")->address;?><br />
        <?php echo App::get("Core")->city . ' ' . App::get("Core")->state . ' ' . App::get("Core")->zip;?><br />
        <?php echo App::get("Core")->phone;?><br />
        <?php echo Validator::cleanOut(App::get("Core")->inv_info);?></p></td>
    <td colspan="2" valign="top" style="width:40%"><p> <?php echo $row->fullname;?>
        <?php  echo $row->company ? '/' . $row->company . '/' : null;?>
        <br />
        <?php echo $row->email;?><br />
        <?php echo $row->address;?><br />
        <?php echo $row->city.', '.$row->state.' '.$row->zip;?><br />
        <?php echo $row->country;?></p></td>
  </tr>
  <tr>
    <td valign="top" style="width:60%"><br /></td>
    <td valign="top" style="width:20%">Amount Due:<br />
      Due Date:</td>
    <td valign="top" style="width:20%"><?php echo Utility::formatMoney($row->total, true);?><br />
      <?php echo Utility::dodate("short_date", $row->created);?></td>
  </tr>
</table>
<div style="height:20px"></div>
<table class="inventory">
  <thead>
    <tr>
      <th><span>Invoice Items</span></th>
      <th><span>Total</span></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><span><?php echo $row->title;?> <small>(<?php echo $row->description;?>)</small></span></td>
      <td class="right"><span><?php echo $row->rate_amount;?></span></td>
    </tr>
    <tr>
      <td align="right">Discount/Coupon:</td>
      <td align="right"><?php echo $row->coupon;?></td>
    </tr>
  </tbody>
</table>
<table class="balance">
  <tr>
    <th><span>Subtotal</span></th>
    <td><span><?php echo Utility::formatMoney($row->total - $row->coupon - $row->tax, true);?></span></td>
  </tr>
  <tr>
    <th><span>Taxes</span></th>
    <td><span><?php echo $row->tax;?></span></td>
  </tr>
  <tr>
    <th><span>Grand Total</span></th>
    <td><span><?php echo $row->total;?> <?php echo $row->currency;?></span></td>
  </tr>
  <tr>
    <th>Status</th>
    <td class="green">Paid</td>
  </tr>
</table>
<htmlpagefooter name="footer">
  <table width="100%" style="vertical-align: bottom;font-size: 8pt; border-top:1px solid #BBB; font-weight: bold; font-style: italic;">
    <tr>
      <td colspan="3" style="border:0"><?php if(App::get("Core")->inv_note):?>
        <?php echo Validator::cleanOut(App::get("Core")->inv_note);?>
        <?php endif;?></td>
    </tr>
    <tr>
      <td width="33%" style="border:0"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
      <td width="33%" align="center" style="font-weight: bold; font-style: italic;border:0">{PAGENO}/{nbpg}</td>
      <td width="33%" style="text-align: right;border:0 "><?php echo App::get("Core")->company;?></td>
    </tr>
  </table>
</htmlpagefooter>
</body>
</html>
<?php else:?>
<?php die('<h1 style="text-align:center">You have selected invalid invoice</h1>');?>
<?php endif;?>