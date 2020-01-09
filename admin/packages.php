<?php
  /**
   * Membership Packages
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: packages.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!$row = $db->first(Content::msTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->MSM_SUB1;?> <small> / <?php echo $row->title;?></small> </div>
      <p><?php echo Lang::$word->MSM_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MSM_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->title;?>" name="title" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_PRICE;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_PRICE_T;?>"></i></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->price;?>" name="price" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MSM_PERIOD;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_PERIOD_T;?>"></i></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->days;?>" name="days" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_PERIOD;?></label>
        <select name="period" data-cover="true">
          <?php echo Utility::getMembershipPeriod($row->period);?>
        </select>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->MSM_PRIVATE;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_PRIVATE_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="private" type="radio" value="1" <?php echo Validator::getChecked($row->private, 1);?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="private" type="radio" value="0" <?php echo Validator::getChecked($row->private, 0);?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_FEATURED;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_FEATURED_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="featured" type="radio" value="1" <?php echo Validator::getChecked($row->featured, 1);?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="featured" type="radio" value="0" <?php echo Validator::getChecked($row->featured, 0);?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_ACTIVE;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_ACTIVE_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="active" type="radio" value="1" <?php echo Validator::getChecked($row->active, 1);?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="active" type="radio" value="0" <?php echo Validator::getChecked($row->active, 0);?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_LISTS;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_LISTS_T;?>"></i></label>
        <input class="wojo range slider" type="range" min="1" max="1000" step="1" name="listings" value="<?php echo $row->listings;?>">
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <div class="field">
      <label><?php echo Lang::$word->MSM_DESC;?></label>
      <textarea name="description"><?php echo $row->description;?></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processPackage" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->MSM_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("packages");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php case"add": ?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->MSM_SUB2;?></div>
      <p><?php echo Lang::$word->MSM_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MSM_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->MSM_NAME;?>" name="title" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_PRICE;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_PRICE_T;?>"></i></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->MSM_PRICE;?>" name="price" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MSM_PERIOD;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_PERIOD_T;?>"></i></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->MSM_PERIOD;?>" name="days" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_PERIOD;?></label>
        <select name="period" data-cover="true">
          <?php echo Utility::getMembershipPeriod();?>
        </select>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->MSM_PRIVATE;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_PRIVATE_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="private" type="radio" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="private" type="radio" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_FEATURED;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_FEATURED_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="featured" type="radio" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="featured" type="radio" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_ACTIVE;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_ACTIVE_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="active" type="radio" value="1" checked="checked">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="active" type="radio" value="0">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->MSM_LISTS;?><i class="icon pin" data-content="<?php echo Lang::$word->MSM_LISTS_T;?>"></i></label>
        <input class="wojo range slider" type="range" min="1" max="1000" step="1" name="listings" value="1">
      </div>
    </div>
    <div class="wojo double fitted divider"></div>
    <div class="field">
      <label><?php echo Lang::$word->MSM_DESC;?></label>
      <textarea name="description" placeholder="<?php echo Lang::$word->MSM_DESC;?>"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processPackage" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->MSM_ADD;?></button>
      <a href="<?php echo Url::adminUrl("packages");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
  </form>
</div>
<?php break;?>
<?php default: ?>
<?php $data = $content->getMemberships();?>
<div class="wojo secondary icon message"> <i class="certificate icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->MSM_TITLE;?></div>
    <p><?php echo Lang::$word->MSM_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->MSM_SUB;?></span> <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->MSM_ADD;?>" href="<?php echo Url::adminUrl("packages", "add");?>" ><i class="icon plus"></i></a> </div>
  <table class="wojo sortable table">
    <thead>
      <tr>
        <th class="disabled">#</th>
        <th data-sort="string"><?php echo Lang::$word->MSM_NAME;?></th>
        <th data-sort="int"><?php echo Lang::$word->MSM_PRICE2;?></th>
        <th data-sort="string"><?php echo Lang::$word->MSM_EXPIRY;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="5"><?php echo Message::msgSingleInfo(Lang::$word->MSM_NOMBS);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr>
        <td><small><?php echo $row->id;?>.</small></td>
        <td><?php echo $row->title;?></td>
        <td><?php echo Utility::formatMoney($row->price, true);?></td>
        <td><?php echo $row->days . ' ' . Utility::getPeriod($row->period);?></td>
        <td><a href="<?php echo Url::adminUrl("packages", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->MSM_DELETE;?>", "parent": "tr", "option": "deletePackage", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<?php break;?>
<?php endswitch;?>