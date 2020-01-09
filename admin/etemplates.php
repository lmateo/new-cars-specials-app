<?php
  /**
   * Email Templates
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: etemplates.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner", "admin", "editor")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php $data = File::getMailerTemplates();?>
<div class="wojo secondary icon message"> <i class="content left icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->ET_TITLE2;?> / <small id="tplsub"></small></div>
    <p><?php echo Lang::$word->ET_INFO;?></p>
  </div>
</div>
<div class="wojo form segment">
  <form id="wojo_form" name="wojo_form" method="post">
    <div class="two fields">
      <div class="field">
        <label> <?php echo Lang::$word->ET_SUB;?></label>
        <select name="filename" id="mtemplatelist">
          <option value="">---  ---</option>
          <?php if($data):?>
          <?php foreach($data as $row):?>
          <?php $name = basename($row);?>
          <option value="<?php echo $name;?>"><?php echo substr(str_replace("_", " ",$name), 0, -8);?></option>
          <?php endforeach;?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label> <?php echo Lang::$word->ET_BACKUP;?></label>
        <input class="wojo switch" data-toggler="true" type="checkbox" value="1" name="backup" checked="checked" data-checkbox-options='{"toggle":true, "labels" : {"on": "Yes","off": "No"}}'>
      </div>
    </div>
    <div class="field">
      <textarea class="fullpage" name="body"></textarea>
    </div>
    <div class="wojo error notice"><i class="icon info sign"></i>
      <div class="content"><?php echo Lang::$word->NOTEVAR;?></div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processMtemplate" name="dosubmit" class="wojo positive button"><?php echo Lang::$word->ET_UPDATE;?></button>
    </div>
  </form>
</div>
<script type="text/javascript">
  $(document).ready(function() {
      $('#mtemplatelist').change(function() {
		  $container = $(".wojo.form");
		  $container.addClass('loading');
          var option = $(this).val();
          $.ajax({
			  cache: false,
              type: "get",
              url: ADMINURL + "/helper.php",
			  dataType: "json",
              data: {
                  getMailerTemplate: 1,
                  filename: option
              },
              success: function(json) {
                  if (json.status == "error") {
                      $("#tplsub").html("none");
                      $('.fullpage').redactor('set', json.message);
                  } else {
                      $("#tplsub").html(json.title);
                      $('.fullpage').redactor('set', json.message);
                  }
				  $container.removeClass('loading');
              }
          });
      });
  });
</script> 