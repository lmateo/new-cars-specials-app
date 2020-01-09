<?php
  /**
   * Features
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: features.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!Auth::hasPrivileges('manage_features')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php $data = $content->getFeatures();?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->FEAT_SUB;?></div>
    <p><?php echo str_replace("[ICON]", "<i class=\"icon middle reorder\"></i>", Lang::$word->FEAT_INFO);?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->FEAT_TITLE;?></span> <a data-set='{"title": "<?php echo Lang::$word->FEAT_ADDNEW;?>", "option": "addNewFeature", "label": "<?php echo Lang::$word->FEAT_ADD;?>", "redirect": "<?php echo Url::adminUrl("features");?>"}' id="addNew" class="wojo large top right detached action label" data-content="<?php echo Lang::$word->FEAT_ADD;?>"><i class="icon plus"></i></a> </div>
  <table class="wojo table" id="editable">
    <thead>
      <tr>
        <th class="disabled"></th>
        <th data-sort="string"><?php echo Lang::$word->FEAT_NAME;?></th>
        <th data-sort="int"><?php echo Lang::$word->POSITION;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="4"><?php echo Message::msgSingleAlert(Lang::$word->FEAT_NOFEATURE);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr data-id="<?php echo $row->id;?>">
        <td class="sorter"><i class="icon reorder"></i></td>
        <td data-editable="true" data-set='{"type": "feature", "id": <?php echo $row->id;?>,"key":"name", "path":""}'><?php echo $row->name;?></td>
        <td><small class="wojo label"><?php echo $row->sorting;?></small></td>
        <td><a class="delete" data-set='{"title": "<?php echo Lang::$word->FEAT_DEL;?>", "parent": "tr", "option": "deleteFeature", "id": <?php echo $row->id;?>, "name": "<?php echo $row->name;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
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
                    sortfeatures: 1
                }
            });
        }
    });
});
// ]]>
</script> 