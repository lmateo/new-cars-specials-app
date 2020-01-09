<?php
  /**
   * Backup
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: backup.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  if (!Users::checkAcl("owner", "admin")) : print Message::msgError(Lang::$word->NOACCESS); return; endif;

  $dir = BASEPATH . 'admin/backups';
  $data = File::findFiles($dir, array('fileTypes' => array('sql'), 'returnType' => 'fileOnly'));
?>
<div class="wojo secondary icon message"> <i class="database icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->DBM_TITLE;?></div>
    <p><?php echo Lang::$word->DBM_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->DBM_SUB;?></span> <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->DBM_ADD;?>" id="doBackup"><i class="icon plus"></i></a> </div>
  <div id="backupList" class="wojo divided fitted list">
    <?php if ($data):?>
    <?php foreach ($data as $row):?>
    <?php $latest =  ($row == $core->sbackup) ? " active" : null;?>
    <div class="item<?php echo $latest;?>"><i class="big icon hdd"></i>
      <div class="header"><?php echo File::getFileSize($dir . '/' . $row, "kb", true);?></div>
      <div class="push-right"> <a class="delete" data-set='{"title": "<?php echo Lang::$word->DBM_DEL;?>", "parent": ".item", "option": "deleteBackup", "id": 1, "name": "<?php echo $row;?>"}'><i class="rounded inverted negative trash icon link"></i></a> <a href="<?php echo ADMINURL . '/backups/' . $row;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>"><i class="rounded inverted positive cloud download icon link"></i></a> <a class="restore" data-content="<?php echo Lang::$word->RESTORE;?>" data-file="<?php echo $row;?>"><i class="rounded inverted primary refresh icon link"></i></a> </div>
      <div class="content"><?php echo str_replace(".sql", "", $row);?></div>
    </div>
    <?php endforeach;?>
    <?php unset($row);?>
    <?php endif;?>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
	  $('a#doBackup').on('click', function() {
	      $.ajax({
	          dataType: "json",
	          url: ADMINURL + "/helper.php",
	          data: {
	              doBackup: 1
	          },
	          success: function(json) {
	              if (json.type == "success") {
	                  $("#backupList .item").removeClass("active")
	                  $("#backupList").prepend(json.html)
	              }
	              $.sticky(decodeURIComponent(json.message), {
	                  type: json.type,
	                  title: json.title
	              });
	          }
	      });
	  });
		  
	  $('a.restore').on('click', function () {
		  var parent = $(this).closest('div.item');
		  var id = $(this).data('file')
		  var title = id;
		  var text = "<div class=\"messi-warning\"><i class=\"huge icon warn sign\"></i></p><p><?php echo Lang::$word->DBM_RES_T;?><br><strong><?php echo Lang::$word->DELCONFIRM2;?></strong></p></div>";
		  new Messi(text, {
			  title: "<?php echo Lang::$word->DBM_RES;?>",
			  modal: true,
			  closeButton: true,
			  buttons: [{
				  id: 0,
				  label: "<?php echo Lang::$word->DBM_RES;?>",
				  val: 'Y',
				  class: 'negative'
			  }],
			  callback: function (val) {
				  if (val === "Y") {
					  $.ajax({
						  type: 'post',
						  dataType: 'json',
						  url: ADMINURL + "/helper.php",
						  data: 'restoreBackup=' + id,
						  success: function (json) {
							  parent.addClass('highlite');
							  $.sticky(decodeURIComponent(json.message), {
								  type: json.type,
								  title: json.title
							  });
						  }
					  });
				  }
			  }
		  })
	  });
  });
</script> 