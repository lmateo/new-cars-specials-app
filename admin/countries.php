<?php
  /**
   * Countries
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: countries.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner", "admin")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!$row = $db->first(Content::cTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->CNT_SUB1;?> <small>/ <?php echo $row->name;?></small> </div>
      <p><?php echo Lang::$word->CNT_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->name;?>" name="name" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CNT_ABBR;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->abbr;?>" name="abbr" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->VAT;?></label>
        <label class="input"><span class="icon-append"><b>%</b></span>
          <input type="text" value="<?php echo $row->vat;?>" name="vat">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->SORTING;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->sorting;?>" name="sorting">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATUS;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="active" type="radio" value="1" <?php Validator::getChecked($row->active, 1);?>>
            <i></i><?php echo Lang::$word->ACTIVE;?></label>
          <label class="radio">
            <input name="active" type="radio" value="0" <?php Validator::getChecked($row->active, 0);?>>
            <i></i><?php echo Lang::$word->INACTIVE;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->DEFAULT;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="home" type="radio" value="1" <?php Validator::getChecked($row->home, 1);?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="home" type="radio" value="0" <?php Validator::getChecked($row->home, 0);?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processCountry" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->CNT_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("countries");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php default: ?>
<?php $data = $content->getCountryList();?>
<div class="wojo secondary icon message"> <i class="earth icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->CNT_TITLE;?></div>
    <p><?php echo Lang::$word->CNT_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <table class="wojo sortable table">
    <thead>
      <tr>
        <th data-sort="string"><?php echo Lang::$word->NAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->CNT_ABBR;?></th>
        <th data-sort="int"><?php echo Lang::$word->DEFAULT;?></th>
        <th data-sort="int"><?php echo Lang::$word->ACTIVE;?></th>
        <th data-sort="int"><?php echo Lang::$word->SORTING;?></th>
        <th data-sort="int"><?php echo Lang::$word->VAT;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="7"><?php echo Message::msgSingleInfo(Lang::$word->CNT_NOCOUNTRY);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($data as $row):?>
      <tr>
        <td><?php echo $row->name;?></td>
        <td><?php echo $row->abbr;?></td>
        <td data-sort-value="<?php echo $row->home;?>"><?php echo Utility::isActive($row->home);?></td>
        <td data-sort-value="<?php echo $row->active;?>"><?php echo Utility::isActive($row->active);?></td>
        <td><?php echo $row->sorting;?></td>
        <td><?php echo $row->vat;?></td>
        <td><a href="<?php echo Url::adminUrl("countries", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->CNT_DEL_COUNTRY;?>", "parent": "tr", "option": "deleteCountry", "id": <?php echo $row->id;?>, "name": "<?php echo $row->name;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<?php break;?>
<?php endswitch;?>
