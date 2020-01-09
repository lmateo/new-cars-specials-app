<?php
  /**
   * Language Manager
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: lmanager.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner", "admin", "editor")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . Core::$language . "/lang.xml");?>
<?php $sections = Lang::getSections();?>
<div class="wojo secondary icon message"> <i class="flag icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LMG_TITLE;?></div>
    <p><?php echo Lang::$word->LMG_INFO;?></p>
  </div>
</div>
<div id="langsegment">
  <div class="wojo quaternary segment">
    <div class="header"><?php echo Lang::$word->FILTER;?></div>
    <div class="content">
      <div class="wojo form">
        <div class="two fields fitted">
          <div class="field">
            <div class="wojo icon input">
              <input id="filter" type="text" placeholder="<?php echo Lang::$word->SEARCH;?>">
              <i class="find icon"></i> </div>
          </div>
          <div class="field">
            <select id="group" name="group">
              <option value="all"><?php echo Lang::$word->LMG_RESET;?></option>
              <?php asort($sections);?>
              <?php foreach($sections as $row):?>
              <option value="<?php echo $row;?>"><?php echo $row;?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="wojo table segment">
    <table class="wojo table" id="editable">
      <thead>
        <tr>
          <th class="one wide">#</th>
          <th class="four wide"><?php echo Lang::$word->LMG_KEY;?></th>
          <th class="eleven wide"><?php echo Lang::$word->LMG_VALUE;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1;?>
        <?php foreach ($xmlel as $pkey) :?>
        <tr>
          <td><small><?php echo $i++;?>.</small></td>
          <td><?php echo $pkey['data'];?></td>
          <td data-editable="true" data-set='{"type": "phrase", "id": <?php echo $i++;?>,"key":"<?php echo $pkey['data'];?>", "path":"lang"}'><?php echo $pkey;?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    /* == Filter == */
    $("#filter").on("keyup", function () {
        var filter = $(this).val(),
            count = 0;
        $("td[data-editable=true]").each(function () {
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).parent().fadeOut();
            } else {
                $(this).parent().show();
                count++;
            }
        });
    });

    /* == Group Filter == */
    $('#group').change(function () {
        var sel = $(this).val();
		var type = $("#group option:selected").data('type');
        $("#langsegment").addClass('loading');
        $.ajax({
            type: "get",
            url: ADMINURL + "/helper.php",
            dataType: 'json',
            data: {
                'loadLangSection': 1,
				'section': sel
            },
            beforeSend: function () {},
            success: function (json) {
                if (json.status == "success") {
                    $("#editable tbody").html(json.message).fadeIn("slow");
					$('#editable').editableTableWidget();
                } else {
                    $.sticky(decodeURIComponent(json.message), {
                        type: "error",
                        title: json.title
                    });
                }
				$("#langsegment").removeClass('loading');
            }
        })
    });
});
// ]]>
</script> 