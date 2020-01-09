<?php
  /**
   * Slider
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: slider.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!Auth::hasPrivileges('manage_slider')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!$row = $db->first(Content::slTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->SLD_SUB1;?> <small> / <?php echo $row->caption;?></small> </div>
      <p><?php echo Lang::$word->SLD_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="field">
      <label><?php echo Lang::$word->SLD_NAME;?></label>
      <div class="wojo labeled icon input">
        <input type="text" value="<?php echo $row->caption;?>" name="caption" required>
        <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SLD_IMAGE;?></label>
        <label class="input">
          <input type="file" name="thumb" id="thumb" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLD_IMAGE;?></label>
        <a class="wojo grid normal image" href="<?php echo UPLOADURL;?>slider/<?php echo $row->thumb;?>" data-title="<?php echo $row->caption;?>" data-lightbox="true"><img src="<?php echo UPLOADURL;?>slider/<?php echo $row->thumb;?>" alt=""></a> </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->SLD_BODY;?></label>
      <textarea name="body" class="altpost"><?php echo $row->body;?></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processSlide" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->SLD_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("slider");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php case"add": ?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->SLD_SUB2;?></div>
      <p><?php echo Lang::$word->SLD_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->SLD_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->SLD_NAME;?>" name="caption" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SLD_IMAGE;?></label>
        <label class="input">
          <input type="file" name="thumb" id="thumb" class="filefield">
        </label>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->SLD_BODY;?></label>
      <textarea name="body" class="altpost" placeholder="<?php echo Lang::$word->SLD_BODY;?>"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processSlide" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->SLD_ADD;?></button>
      <a href="<?php echo Url::adminUrl("slider");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
  </form>
</div>
<?php break;?>
<?php default: ?>
<?php $data = $content->getSlider();?>
<div class="wojo secondary icon message"> <i class="photo icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->SLD_SUB;?></div>
    <p><?php echo str_replace("[ICON]", "<i class=\"icon middle reorder\"></i>", Lang::$word->SLD_INFO);?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->SLD_TITLE;?></span> <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->SLD_ADD;?>" href="<?php echo Url::adminUrl("slider", "add");?>" ><i class="icon plus"></i></a> </div>
  <table class="wojo table">
    <thead>
      <tr>
        <th class="disabled"></th>
        <th class="disabled"></th>
        <th data-sort="string"><?php echo Lang::$word->SLD_NAME;?></th>
        <th data-sort="int"><?php echo Lang::$word->POSITION;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="5"><?php echo Message::msgSingleAlert(Lang::$word->SLD_NOSLIDE);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr data-id="<?php echo $row->id;?>">
        <td class="sorter"><i class="icon reorder"></i></td>
        <td><a class="wojo grid small image" href="<?php echo UPLOADURL;?>slider/<?php echo $row->thumb;?>" data-title="<?php echo $row->caption;?>" data-lightbox="true" data-lightbox-gallery="true"><img src="<?php echo UPLOADURL;?>slider/<?php echo $row->thumb;?>"alt=""></a></td>
        <td><?php echo $row->caption;?></td>
        <td><?php echo $row->sorting;?></td>
        <td><a href="<?php echo Url::adminUrl("slider", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->SLD_DELSLIDE;?>", "parent": "tr", "option": "deleteHomeSlide", "id": <?php echo $row->id;?>, "name": "<?php echo $row->caption;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
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
                    sortslides: 1
                }
            });
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>