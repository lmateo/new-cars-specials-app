<?php
  /**
   * Gateway Manager
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: gateways.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!$row = $db->select(Content::gwTable, null, array('id' => Filter::$id), 'LIMIT 1')->result()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->GW_SUB1;?> <small> / <?php echo $row->displayname;?></small> </div>
      <p><?php echo Lang::$word->GW_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->GW_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->displayname;?>" name="displayname" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo $row->extra_txt;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->extra;?>" name="extra" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo $row->extra_txt2;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->extra2;?>" name="extra2">
        </label>
      </div>
      <div class="field">
        <label><?php echo $row->extra_txt3;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->extra3;?>" name="extra3">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->GW_LIVE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="live" value="1" <?php Validator::getChecked($row->live, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="live" value="0" <?php Validator::getChecked($row->live, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->GW_ACTIVE;?></label>
        <div class="inline-group">
          <label class="radio">
            <input type="radio" name="active" value="1" <?php Validator::getChecked($row->active, 1); ?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input type="radio" name="active" value="0" <?php Validator::getChecked($row->active, 0); ?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label class="label"><?php echo Lang::$word->GW_IPNURL;?></label>
        <label class="input">
          <input type="text" readonly="readonly" value="<?php echo SITEURL.'/gateways/' . $row->dir . '/ipn.php';?>">
        </label>
      </div>
      <div class="field">
        <label class="label"><?php echo Lang::$word->GW_HELP;?></label>
        <a class="viewtip"><i class="large circular inverted primary success icon pin link"></i></a></div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processGateway" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->GW_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("gateways");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<div id="showhelp" style="display:none"><?php echo Validator::cleanOut($row->info);?></div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
	$('a.viewtip').on('click', function () {
		var text = $("#showhelp").html();
		new Messi(text, {
			title: "<?php echo $row->displayname;?>"
		});
	});
});
// ]]>
</script>
<?php break;?>
<?php default: ?>
<?php $data = $content->getGetaways();?>
<div class="wojo secondary icon message"> <i class="wallet icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->GW_TITLE;?></div>
    <p><?php echo Lang::$word->GW_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->GW_SUB;?></span></div>
  <table class="wojo table">
    <thead>
      <tr>
        <th class="disabled">#</th>
        <th data-sort="string"><?php echo Lang::$word->GW_NAME;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="4"><?php echo Message::msgSingleAlert(Lang::$word->GW_NOGATEWAY);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr>
        <td><small><?php echo $row->id;?></small></td>
        <td><?php echo $row->displayname;?></td>
        <td><a href="<?php echo Url::adminUrl("gateways", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<?php break;?>
<?php endswitch;?>