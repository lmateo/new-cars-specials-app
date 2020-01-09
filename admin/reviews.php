<?php
  /**
   * Review Management
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: reviews.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_reviews')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php $data = $content->getReviews();?>
<div class="wojo secondary icon message"> <i class="comment icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->SRW_TITLE;?></div>
    <p><?php echo Lang::$word->SRW_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix relative"><span><?php echo Lang::$word->SRW_SUB;?></span></div>
  <table class="wojo sortable table">
    <thead>
      <tr>
        <th class="disabled"></th>
        <th data-sort="string"><?php echo Lang::$word->NAME;?></th>
        <th data-sort="string">#Twitter</th>
        <th data-sort="string"><?php echo Lang::$word->COMMENT;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="5"><?php echo Message::msgSingleInfo(Lang::$word->SRW_NOREV);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($data as $row):?>
      <tr>
        <td><img src="<?php echo UPLOADURL;?>avatars/<?php echo ($row->avatar) ? $row->avatar : "blank.png";?>" alt="" class="wojo image avatar"></td>
        <td><?php if(Auth::hasPrivileges('edit_members')):?>
          <a href="<?php echo Url::adminUrl("members", "edit", false,"?id=" . $row->uid);?>"><?php echo $row->name;?> </a>
          <?php else:?>
          <?php echo $row->name;?>
          <?php endif;?></td>
        <td><?php echo $row->twitter;?></td>
        <td><?php echo Validator::truncate($row->content, 60);?></td>
        <td><a data-content="<?php echo Lang::$word->STATUS;?>" class="doStatus" data-set='{"field": "status", "table": "Reviews", "toggle": "check ban", "togglealt": "primary negative", "id": <?php echo $row->id;?>, "value": "<?php echo $row->status;?>"}'> <i class="rounded <?php echo ($row->status) ? "check primary" : "ban negative";?> inverted icon link"></i></a> <a data-reviewcomment="<?php echo $row->id;?>" data-name="<?php echo $row->name;?>"><i class="rounded outline positive icon pencil link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->SRW_DEL_REV;?>", "parent": "tr", "option": "deleteReview", "id": <?php echo $row->id;?>, "name": "<?php echo $row->name;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
	$("[data-reviewcomment]").on('click', function () {
		id = $(this).data('reviewcomment');
		Messi.load(ADMINURL + '/helper.php', {
			loadReviewDescription: 1,
			id: id
		}, '',{
			title: "<?php echo Lang::$word->SRW_EDIT;?> / " + $(this).data('name'),
            buttons: [{
                id: 0,
				class: 'positive dosubmit',
                label: "<?php echo Lang::$word->SUBMIT;?>",
                val: 'action'
            }],
			callback: function (val) {}
		});
	});
});
// ]]>
</script>