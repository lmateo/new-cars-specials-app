<?php
  /**
   * Coupons
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: coupons.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_coupons')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!$row = $db->first(Content::dcTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $memdata = $content->getMemberships();?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->DC_SUB1;?> <small> / <?php echo $row->title;?></small> </div>
      <p><?php echo Lang::$word->DC_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->DC_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->title;?>" name="title" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->DC_CODE;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->code;?>" name="code" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->DC_DISC;?><i class="icon pin" data-content="<?php echo Lang::$word->DC_DISC_T;?>"></i></label>
        <div class="wojo labeled icon input">
          <input type="text" name="discount" value="<?php echo $row->discount;?>" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->DC_TYPE;?></label>
        <select name="type" data-cover="true">
          <option value="p"<?php if($row->type == "p") echo ' selected="selected"';?>><?php echo Lang::$word->DC_TYPE_P;?></option>
          <option value="a"<?php if($row->type == "a") echo ' selected="selected"';?>><?php echo Lang::$word->DC_TYPE_A;?></option>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MEMBERSHIP;?><i class="icon pin" data-content="<?php echo Lang::$word->DC_MEMBERSHIP_T;?>"></i></label>
        <select name="mid[]" multiple="multiple">
          <?php if($memdata):?>
          <?php $arr = explode(",", $row->mid);?>
          <?php foreach ($memdata as $mlist):?>
          <?php if(!$mlist->private):?>
          <?php $selected = (in_array($mlist->id, $arr)) ? " selected=\"selected\"" : "";?>
          <option value="<?php echo $mlist->id;?>"<?php echo $selected;?>><?php echo $mlist->title;?></option>
          <?php endif;?>
          <?php endforeach;?>
          <?php unset($mlist);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PUBLISHED;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="active" type="radio" value="1" <?php echo Validator::getChecked($row->active, 1);?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="active" type="radio" value="0" <?php echo Validator::getChecked($row->active, 0);?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processCoupon" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->DC_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("coupons");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php case"add": ?>
<?php $memdata = $content->getMemberships();?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->DC_SUB2;?></div>
      <p><?php echo Lang::$word->DC_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->DC_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->DC_NAME;?>" name="title" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->DC_CODE;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->DC_CODE;?>" name="code" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->DC_DISC;?><i class="icon pin" data-content="<?php echo Lang::$word->DC_DISC_T;?>"></i></label>
        <div class="wojo labeled icon input">
          <input type="text" name="discount" placeholder="<?php echo Lang::$word->DC_DISC;?>" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->DC_TYPE;?></label>
        <select name="type" data-cover="true">
          <option value="p"><?php echo Lang::$word->DC_TYPE_P;?></option>
          <option value="a"><?php echo Lang::$word->DC_TYPE_A;?></option>
        </select>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MEMBERSHIP;?><i class="icon pin" data-content="<?php echo Lang::$word->DC_MEMBERSHIP_T;?>"></i></label>
        <select name="mid[]" multiple="multiple">
          <?php if($memdata):?>
          <?php foreach ($memdata as $mlist):?>
          <?php if(!$mlist->recurring and !$mlist->private):?>
          <option value="<?php echo $mlist->id;?>"><?php echo $mlist->title;?></option>
          <?php endif;?>
          <?php endforeach;?>
          <?php unset($mlist);?>
          <?php endif;?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PUBLISHED;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="active" type="radio" value="1" checked="checked">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="active" type="radio" value="0" >
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processCoupon" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->DC_ADD;?></button>
      <a href="<?php echo Url::adminUrl("coupons");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
  </form>
</div>
<?php break;?>
<?php default: ?>
<?php $data = $content->getCoupons();?>
<div class="wojo secondary icon message"> <i class="tag icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->DC_TITLE;?></div>
    <p><?php echo Lang::$word->DC_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->DC_SUB;?></span> <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->DC_ADD;?>" href="<?php echo Url::adminUrl("coupons", "add");?>"><i class="icon plus"></i></a> </div>
  <table class="wojo table">
    <thead>
      <tr>
        <th class="disabled">#</th>
        <th><?php echo Lang::$word->DC_NAME;?></th>
        <th><?php echo Lang::$word->DC_CODE;?></th>
        <th><?php echo Lang::$word->DC_TYPE;?></th>
        <th><?php echo Lang::$word->CREATED;?></th>
        <th><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="6"><?php echo Message::msgSingleInfo(Lang::$word->DC_NONDISC);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr>
        <td><small><?php echo $row->id;?>.</small></td>
        <td><?php echo $row->title;?></td>
        <td><?php echo $row->code;?></td>
        <td><?php echo $row->type;?></td>
        <td><?php echo Utility::doDate("short_date", $row->created);?></td>
        <td><a href="<?php echo Url::adminUrl("coupons", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->DC_DELETE;?>", "parent": "tr", "option": "deleteCoupon", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<?php break;?>
<?php endswitch;?>