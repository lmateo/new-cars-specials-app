<?php
  /**
   * Content Menus
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: menus.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!Auth::hasPrivileges('edit_menus')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php if(!$row = $db->first(Content::muTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $datapages = $content->getPages();?>
<div class="wojo secondary icon message"> <i class="note icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->MENU_SUB1;?> <small> / <?php echo $row->name;?></small> </div>
    <p><?php echo Lang::$word->MENU_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
  </div>
</div>
<div class="columns half-gutters">
  <div class="screen-60 tablet-50 phone-100">
    <div class="wojo form top attached segment">
      <form method="post" id="wojo_form" name="wojo_form">
        <div class="field">
          <label><?php echo Lang::$word->MENU_NAME;?><i class="icon pin" data-content="<?php echo Lang::$word->MENU_NAME_T;?>"></i></label>
          <div class="wojo labeled icon input">
            <input type="text" value="<?php echo $row->name;?>" name="name" required>
            <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </div>
        </div>
        <div class="two fields">
          <div class="field">
            <label><?php echo Lang::$word->MENU_TYPE;?><i class="icon pin" data-content="<?php echo Lang::$word->MENU_TYPE_T;?>"></i></label>
            <select name="content_type" id="contenttype">
              <option value=""><?php echo Lang::$word->MENU_TYPE_SEL;?></option>
              <?php echo Content::getContentType($row->content_type);?>
            </select>
          </div>
          <div class="field" id="contentid" style="display:<?php echo ($row->content_type != "web") ? 'block' : 'none';?>">
            <label><?php echo Lang::$word->MENU_LINK;?></label>
            <select name="page_id" id="page_id">
              <?php echo Utility::loopOptions($datapages, "id", "title", $row->page_id);?>
            </select>
          </div>
        </div>
        <div class="two fields" id="webid" style="display:<?php echo ($row->content_type == "web") ? 'block' : 'none';?>">
          <div class="field">
            <label><?php echo Lang::$word->MENU_LINK;?><i class="icon pin" data-content="<?php echo Lang::$word->MENU_LINK_T;?>"></i></label>
            <label class="input">
              <input type="text" name="web" value="<?php echo $row->link;?>" placeholder="<?php echo Lang::$word->MENU_LINK;?>">
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->MENU_TARGETL;?></label>
            <select name="target">
              <option value=""><?php echo Lang::$word->MENU_TARGET;?></option>
              <option value="_blank"<?php if ($row->target == "_blank") echo ' selected="selected"';?>><?php echo Lang::$word->MENU_TARGET_B;?></option>
              <option value="_self"<?php if ($row->target == "_self") echo ' selected="selected"';?>><?php echo Lang::$word->MENU_TARGET_S;?></option>
            </select>
          </div>
        </div>
        <div class="field">
          <div class="inline-group">
            <label><?php echo Lang::$word->MENU_PUB;?></label>
            <label class="radio">
              <input name="active" type="radio" value="1" <?php Validator::getChecked($row->active, 1); ?>>
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input name="active" type="radio"  value="0" <?php Validator::getChecked($row->active, 0); ?>>
              <i></i> <?php echo Lang::$word->NO;?> </label>
          </div>
        </div>
        <div class="wojo fitted divider"></div>
        <div class="wojo footer">
          <button type="button" name="doMenu" data-action="processMenu" class="wojo secondary button"><?php echo Lang::$word->MENU_UPDATE;?></button>
          <a href="<?php echo Url::adminUrl("menus");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
        <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
      </form>
    </div>
  </div>
  <div class="screen-40 tablet-50 phone-100">
    <div class="wojo tertiary segment">
      <div class="header"><?php echo Lang::$word->MENU_LIST;?></div>
      <div class="content">
        <div id="sortlist" class="dd"> <?php echo $content->getSortMenuList();?></div>
      </div>
    </div>
  </div>
</div>
<?php break;?>
<?php default: ?>
<div class="wojo secondary icon message"> <i class="plus icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->MENU_TITLE;?></div>
    <p><?php echo str_replace("[DEL]", "<i class=\"icon negative middle delete\"></i>", Lang::$word->MENU_INFO) . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
  </div>
</div>
<div class="columns half-gutters">
  <div class="screen-60 tablet-50 phone-100">
    <div class="wojo form top attached segment">
      <form method="post" id="wojo_form" name="wojo_form">
        <div class="field">
          <label><?php echo Lang::$word->MENU_NAME;?><i class="icon pin" data-content="<?php echo Lang::$word->MENU_NAME_T;?>"></i></label>
          <div class="wojo labeled icon input">
            <input type="text" placeholder="<?php echo Lang::$word->MENU_NAME;?>" name="name" required>
            <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </div>
        </div>
        <div class="two fields">
          <div class="field">
            <label><?php echo Lang::$word->MENU_TYPE;?><i class="icon pin" data-content="<?php echo Lang::$word->MENU_TYPE_T;?>"></i></label>
            <select name="content_type" id="contenttype">
              <option value=""><?php echo Lang::$word->MENU_TYPE_SEL;?></option>
              <?php echo Content::getContentType();?>
            </select>
          </div>
          <div class="field" id="contentid">
            <label><?php echo Lang::$word->MENU_LINK;?></label>
            <select name="page_id" id="page_id">
              <option value="0"><?php echo Lang::$word->MENU_NONE;?></option>
            </select>
          </div>
        </div>
        <div class="two fields" id="webid" style="display:none">
          <div class="field">
            <label><?php echo Lang::$word->MENU_LINK;?><i class="icon pin" data-content="<?php echo Lang::$word->MENU_LINK_T;?>"></i></label>
            <label class="input">
              <input type="text" name="web" placeholder="<?php echo Lang::$word->MENU_LINK;?>">
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->MENU_TARGETL;?></label>
            <select name="target">
              <option value=""><?php echo Lang::$word->MENU_TARGET;?></option>
              <option value="_blank"><?php echo Lang::$word->MENU_TARGET_B;?></option>
              <option value="_self"><?php echo Lang::$word->MENU_TARGET_S;?></option>
            </select>
          </div>
        </div>
        <div class="field">
          <div class="inline-group">
            <label><?php echo Lang::$word->MENU_PUB;?></label>
            <label class="radio">
              <input name="active" type="radio" value="1" checked="checked">
              <i></i><?php echo Lang::$word->YES;?></label>
            <label class="radio">
              <input name="active" type="radio" value="0">
              <i></i> <?php echo Lang::$word->NO;?> </label>
          </div>
        </div>
        <?php if(Auth::hasPrivileges('add_menus')):?>
        <div class="wojo fitted divider"></div>
        <div class="wojo footer">
          <button type="button" name="doMenu" data-action="processMenu" class="wojo secondary button"><?php echo Lang::$word->MENU_ADD;?></button>
        </div>
        <?php endif;?>
      </form>
    </div>
  </div>
  <div class="screen-40 tablet-50 phone-100">
    <div class="wojo tertiary segment">
      <div class="header"><?php echo Lang::$word->MENU_LIST;?></div>
      <div class="content">
        <div id="sortlist" class="dd"> <?php echo $content->getSortMenuList();?></div>
      </div>
    </div>
  </div>
</div>
<?php break;?>
<?php endswitch;?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/nestable.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
    function loadList() {
        $.ajax({
            type: 'get',
            url: ADMINURL + "/helper.php",
            data: 'getmenus=1',
            cache: false
        }).done(function(html) {
            $("#sortlist").html(html);
            $('#sortlist').nestable();
        });
    }
    $('body').on('click', 'button[name=doMenu]', function() {
        $('#wojo_form').validate({
            errorPlacement: function(error, element) {}
        });
        var action = $(this).data('action');

        function showResponse(json) {
            $(".wojo.form").removeClass("loading");
            $.sticky(decodeURIComponent(json.message), {
                autoclose: 12000,
                type: json.type,
                title: json.title
            });
            if (json.type == "success") {
                setTimeout(function() {
                    $(loadList()).fadeIn("slow");
                }, 2000);
            }
        }

        function showLoader() {
            $(".wojo.form").addClass("loading");
        }
        var options = {
            target: null,
            beforeSubmit: showLoader,
            success: showResponse,
            type: "post",
            url: ADMINURL + "/controller.php",
            data: {
                action: action
            },
            dataType: 'json'
        };

        $('#wojo_form').ajaxForm(options).submit();
    });

    $('#contenttype').change(function() {
        var option = $(this).val();
        $.ajax({
            type: 'get',
            url: ADMINURL + "/helper.php",
            dataType: 'json',
            data: {
                contenttype: option
            },
            success: function(json) {
                if (json.type == "web") {
                    $("#webid").show();
                    $("#contentid").hide();
                    $(json.message).appendTo('#wojo_form');
                } else {
                    $("#contentid").show();
                    $("#webid").hide();
                    $('#page_id').html(json.message).selecter("update");
                    $(".selecter-options").scroller("destroy")
                    $(".selecter-options").scroller()
                }
            }
        });
    });

    $('#sortlist').nestable({"maxDepth":1,"placeClass":"dd-placeholder large"}).on('change', function() {
        var json_text = $('#sortlist').nestable('serialize');
        $.ajax({
            cache: false,
            type: "post",
            url: ADMINURL + "/helper.php",
            dataType: "json",
            data: {
                sortMenus: 1,
                sortlist: JSON.stringify(json_text)
            },
            success: function(json) {}
        });
    });
});
</script>