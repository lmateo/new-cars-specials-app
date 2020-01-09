<?php
  /**
   * Pending
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: pending.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!Auth::hasPrivileges('manage_approval')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php $data = $items->getListings(false, "", false);?>
<div class="wojo secondary icon message"> <i class="hourglass icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LST_TITLE5;?></div>
    <p><?php echo Lang::$word->LST_INFO5;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->LST_SUB5;?></span></div>
  <form method="post" id="wojo_forml" name="wojo_forml">
    <table class="wojo sortable table">
      <thead>
        <tr>
          <th class="disabled"> <label class="fitted small checkbox">
              <input type="checkbox" name="masterCheckbox" data-parent="#listtable" id="masterCheckbox">
              <i></i></label>
          </th>
          <th class="disabled"><?php echo Lang::$word->PHOTO;?></th>
          <th data-sort="string"><?php echo Lang::$word->DESC;?></th>
          <th data-sort="string"><?php echo Lang::$word->LST_CAT;?></th>
          <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
          <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
        </tr>
      </thead>
      <tbody id="listtable">
        <?php if(!$data):?>
        <tr>
          <td colspan="6"><?php Message::msgSingleAlert(Lang::$word->LST_NOLIST);?></td>
        </tr>
        <?php else:?>
        <?php foreach($data as $row):?>
        <tr<?php echo ($row->rejected) ? ' class="active"' : null;?>>
          <td><label class="fitted small checkbox">
              <input name="listid[<?php echo $row->id;?>]" type="checkbox" value="<?php echo $row->id;?>">
              <i></i></label></td>
          <td><a data-lightbox="true" href="<?php echo UPLOADURL . 'listings/' . $row->thumb;?>"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt="" class="wojo medium grid image"></a></td>
          <td><b><?php echo $row->title;?></b> (<?php echo $row->year;?>) <br />
            <small><?php echo Lang::$word->BY;?>:
            <?php if(Auth::hasPrivileges('edit_members')):?>
            <a href="<?php echo Url::adminUrl("members", "edit", false,"?id=" . $row->user_id);?>"><?php echo $row->username;?></a>
            <?php else:?>
            <?php echo $row->username;?>
            <?php endif;?>
            </small><br />
            #: <b><?php echo $row->stock_id;?></b> <br />
            <?php echo Lang::$word->LST_PRICE;?>: (<?php echo Utility::formatMoney($row->price);?>)<br />
            <?php echo Lang::$word->LST_COND;?>: <b><?php echo $row->cdname;?></b><br />
            <?php echo Lang::$word->MODIFIED;?>: <b><?php echo ($row->modified <> 0) ? Utility::dodate("short_date", $row->modified): '- ' . Lang::$word->NEVER . ' -'?></b><br /></td>
          <td><?php echo $row->ctname;?></td>
          <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Utility::dodate("short_date", $row->created);?></td>
          <td><a data-content="<?php echo Lang::$word->APPROVE;?>" class="doStatus" data-set='{"field": "status", "table": "Approve", "toggle": "check check", "togglealt": "inverted outline", "id": <?php echo $row->id;?>, "value": "<?php echo $row->status;?>", "response":1, "remove":1, "parent":"tr"}'><i class="rounded outline check positive icon link"></i></a> <a data-set='{"title": "<?php echo Lang::$word->REJECT . ' ' . Lang::$word->LISTING;?>", "option": "rejectListing", "label": "<?php echo Lang::$word->REJECT;?>", "redirect": "<?php echo Url::adminUrl("pending");?>", "id":<?php echo $row->id;?>}' id="addNew" data-content="<?php echo Lang::$word->REJECT . ' ' . Lang::$word->LISTING;?>"><i class="rounded outline icon negative ban link"></i></a>
            <div class="quarter-top-space"></div>
            <a href="<?php echo Url::adminUrl("items", "print", false,"?id=" . $row->id);?>"><i class="rounded outline purple icon printer link"></i></a> <a href="<?php echo Url::adminUrl("items", "images", false,"?id=" . $row->id);?>"><i class="rounded outline primary icon photo link"></i></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
      <?php if($data):?>
      <tfoot>
        <tr>
          <td colspan="6"><button name="mdelete" type="button" data-form="#wojo_forml" class="wojo negative button"><i class="icon trash alt"></i><?php echo Lang::$word->LST_DELETES;?></button>
            <input name="delete" type="hidden" value="deleteMultiListings"></td>
        </tr>
      </tfoot>
      <?php endif;?>
    </table>
  </form>
  <div class="footer">
    <div class="wojo tabular segment">
      <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
      <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
    </div>
  </div>
</div>
<div id="msgholder"></div>