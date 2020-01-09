<?php
  /**
   * Models
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: models.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!Auth::hasPrivileges('manage_models')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php $data = $content->getModelsws();?>
<?php $makedata = $content->getMakesws(false);?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->MODL_SUB;?></div>
    <p><?php echo Lang::$word->MODL_INFO;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->FILTER;?></div>
  <div class="content">
    <div class="wojo form">
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("models");?>" name="wojo_form">
        <div class="three fields">
          <div class="field">
            <div class="wojo action input">
              <input type="text" name="find" placeholder="<?php echo Lang::$word->MODL_SEARCH;?>">
              <a id="doFormSubmit" class="wojo icon button"><?php echo Lang::$word->GO;?></a> </div>
          </div>
          <div class="field">
            <select name="mid" data-links="true">
              <option value="<?php echo Url::adminUrl("models");?>">--- <?php echo Lang::$word->MODL_RESET;?> ---</option>
              <?php if($makedata):?>
              <?php foreach($makedata as $mrow):?>
              <?php $selected = ($mrow->id == Filter::$id) ? ' selected="selected"' : null;?>
              <option value="<?php echo Url::adminUrl("models", false, false,"?id=" . $mrow->id);?>"<?php echo $selected;?>><?php echo $mrow->name;?></option>
              <?php endforeach;?>
              <?php endif;?>
            </select>
          </div>
          <div class="field">
            <div class="columns horizontal-gutters">
              <div class="all-50"><?php echo $pager->items_per_page();?></div>
              <div class="all-50"><?php echo $pager->jump_menu();?> </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="addmore wojo form segment" style="display:none">
<div id="container1" class="clonedInput">
  <div class="four fields">
    <div class="field">
      <label><?php echo Lang::$word->MODL_NAME;?></label>
      <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->MODL_NAME;?>" name="modelname[]" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
         </div>   
      </div>
   </div>
  </div>
  <a class="wojo small primary button" id="dosubmit"><?php echo Lang::$word->ADDALL;?></a> <a id="btnAdd" class="wojo small positive button"><?php echo Lang::$word->ADD;?></a> <a id="btnDel" class="wojo small negative button"><?php echo Lang::$word->REMOVE;?></a> </div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->MODL_TITLE;?></span> <a onclick="$('.addmore').slideToggle();" class="wojo large top right detached action label" data-content="<?php echo Lang::$word->MODL_ADD;?>"><i class="icon plus"></i></a> </div>
  <table class="wojo grid table" id="editable">
    <thead>
      <tr>
        <th class="disabled">#</th>
        <th data-sort="string"><?php echo Lang::$word->MAKE_NAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->MODL_NAME;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="4"><?php echo Message::msgSingleAlert(Lang::$word->MODL_NOMODEL);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($data as $row):?>
      <tr>
        <td><small><?php echo $row->mdid;?>.</small></td>
        <td><?php echo $row->mkname;?></td>
        <td data-editable="true" data-set='{"type": "model", "id": <?php echo $row->mdid;?>,"key":"name", "path":""}'><?php echo $row->mdname;?></td>
        <td><a class="delete" data-set='{"title": "<?php echo Lang::$word->MODL_DEL;?>", "parent": "tr", "option": "deleteModelws", "id": <?php echo $row->mdid;?>, "name": "<?php echo $row->mdname;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
  <div class="footer">
    <div class="wojo tabular segment">
      <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
      <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#btnAdd').click(function () {
        var num = $('.clonedInput').length;
        var newNum = new Number(num + 1);
        var newElem = $('#container' + num).clone().attr('id', 'container' + newNum);
        $('#container' + num).after(newElem);
        $('#btnDel').show();
        if (newNum == 15) $('#btnAdd').hide();
    });
    $('#btnDel').click(function () {
        var num = $('.clonedInput').length;
        $('#container' + num).remove();
        $('#btnAdd').show();
        if (num - 1 == 1) $('#btnDel').hide();
    });
    $('#btnDel').hide();

    $('a#dosubmit').on('click', function() {
        var values = $('.addmore :input').serialize();
        values += "&processModelws=1";
        values += "&id=<?php echo Filter::$id;?>"
		values += "&action=processModelws"
        $.ajax({
            type: 'post',
            url: ADMINURL + "/controller.php",
            dataType: 'json',
            data: values,
            success: function(json) {
                if (json.type == "success") {
                    $(".wojo.info.message").remove();
                    $(json.data).insertBefore('.wojo.table tbody tr:first');
                    $(".addmore").slideUp();
                    $(".addmore :input").val('');
                }
                $.sticky(decodeURIComponent(json.message), {
                    type: json.type,
                    title: json.title
                });
            }
        });
    });
});
</script>