<?php
  /**
   * F.A.Q. Manager
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: faq.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!Auth::hasPrivileges('edit_faq')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php if(!$row = $db->select(Content::faqTable, null, array('id' => Filter::$id), 'LIMIT 1')->result()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->FAQ_SUB1;?> <small> / <?php echo $row->question;?></small> </div>
      <p><?php echo Lang::$word->FAQ_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="field">
      <label><?php echo Lang::$word->FAQ_NAME;?></label>
      <div class="wojo labeled icon input">
        <input type="text" value="<?php echo $row->question;?>" name="question" required>
        <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FAQ_BODY;?></label>
      <textarea name="answer" class="bodypost"><?php echo $row->answer;?></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processFaq" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->FAQ_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("faq");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php case"add": ?>
<?php if(!Auth::hasPrivileges('add_faq')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->FAQ_SUB2;?></div>
      <p><?php echo Lang::$word->FAQ_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="field">
      <label><?php echo Lang::$word->FAQ_NAME;?></label>
      <div class="wojo labeled icon input">
        <input type="text" placeholder="<?php echo Lang::$word->FAQ_NAME;?>" name="question" required>
        <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->FAQ_BODY;?></label>
      <textarea name="answer" class="bodypost" placeholder="<?php echo Lang::$word->FAQ_BODY;?>"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processFaq" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->FAQ_ADD;?></button>
      <a href="<?php echo Url::adminUrl("faq");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
  </form>
</div>
<?php break;?>
<?php default: ?>
<?php $data = $content->getFaq();?>
<div class="wojo secondary icon message"> <i class="help icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->FAQ_TITLE;?></div>
    <p><?php echo str_replace("[ICON]", "<i class=\"icon middle reorder\"></i>", Lang::$word->FAQ_INFO);?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->FAQ_SUB;?></span>
    <?php if(Auth::hasPrivileges('add_faq')):?>
    <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->FAQ_ADD;?>" href="<?php echo Url::adminUrl("faq", "add");?>" ><i class="icon plus"></i></a>
    <?php endif;?>
  </div>
  <table class="wojo sortable table">
    <thead>
      <tr>
        <th class="disabled">#</th>
        <th data-sort="string"><?php echo Lang::$word->FAQ_NAME;?></th>
        <th data-sort="int"><?php echo Lang::$word->POSITION;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="4"><?php echo Message::msgSingleAlert(Lang::$word->FAQ_NOFAQ);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr data-id="<?php echo $row->id;?>">
        <td class="sorter"><i class="icon reorder"></i></td>
        <td><?php echo $row->question;?></td>
        <td><small class="wojo label"><?php echo $row->sorting;?></small></td>
        <td><a href="<?php echo Url::adminUrl("faq", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a>
          <?php if(Auth::hasPrivileges('delete_faq')):?>
          <a class="delete" data-set='{"title": "<?php echo Lang::$word->FAQ_DELFAQ;?>", "parent": "tr", "option": "deleteFaq", "id": <?php echo $row->id;?>, "name": "<?php echo $row->question;?>"}'><i class="rounded outline icon negative trash link"></i></a>
          <?php endif;?></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
    $(".wojo.table").rowSorter({
        handler: "td.sorter",
        onDrop: function() {
            var data = [];
            $('.wojo.table tbody tr').each(function() {
                data.push($(this).data("id"))
            });
            $.ajax({
                type: "post",
                url: ADMINURL + "/helper.php",
                data: {
                    sorting: data,
                    sortfaq: 1
                }
            });
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>