<?php
  /**
   * News Manager
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: news.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!Auth::hasPrivileges('manage_news')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!$row = $db->select(Content::nwaTable, null, array('id' => Filter::$id), 'LIMIT 1')->result()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->NWA_SUB1;?> <small> / <?php echo $row->title;?></small> </div>
      <p><?php echo Lang::$word->NWA_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="field">
      <label><?php echo Lang::$word->NWA_NAME;?></label>
      <div class="wojo labeled icon input">
        <input type="text" value="<?php echo $row->title;?>" name="title" required>
        <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->NWA_CONTENT;?></label>
      <textarea name="body" class="bodypost"><?php echo $row->body;?></textarea>
    </div>
    <div class="field">
      <label class="checkbox">
        <input name="active" type="checkbox" value="1" <?php Validator::getChecked($row->active, 1); ?>>
        <i></i><?php echo Lang::$word->PUBLISHED;?> </label>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processNews" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->NWA_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("news");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php case"add": ?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->NWA_SUB2;?></div>
      <p><?php echo Lang::$word->NWA_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="field">
      <label><?php echo Lang::$word->NWA_NAME;?></label>
      <div class="wojo labeled icon input">
        <input type="text" placeholder="<?php echo Lang::$word->NWA_NAME;?>" name="title" required>
        <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->NWA_CONTENT;?></label>
      <textarea name="body" class="bodypost" placeholder="<?php echo Lang::$word->NWA_CONTENT;?>"></textarea>
    </div>
    <div class="field">
      <label class="checkbox">
        <input name="active" type="checkbox" value="1" checked="1">
        <i></i><?php echo Lang::$word->PUBLISHED;?> </label>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processNews" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->NWA_ADD;?></button>
      <a href="<?php echo Url::adminUrl("news");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
  </form>
</div>
<?php break;?>
<?php default: ?>
<?php $data = $content->getNews();?>
<div class="wojo secondary icon message"> <i class="file icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->NWA_TITLE;?></div>
    <p><?php echo Lang::$word->NWA_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->NWA_SUB;?></span> <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->NWA_ADD;?>" href="<?php echo Url::adminUrl("news", "add");?>" ><i class="icon plus"></i></a> </div>
</div>
<?php if(!$data):?>
<?php echo Message::msgSingleAlert(Lang::$word->NWA_NONEWS);?>
<?php else:?>
<div class="wojo space divider"></div>
<div class="columns gutters">
  <?php foreach($data as $row):?>
  <div class="row screen-33 tablet-50 phone-100">
    <div class="wojo divided card">
      <div class="header">
        <div class="wojo top right small attached label"><?php echo $row->id;?>.</div>
        <div class="content"> <a href="<?php echo Url::adminUrl("news", "edit", false,"?id=" . $row->id);?>"><?php echo $row->title;?> </a>
          <p><i class="icon calendar"></i> <?php echo Utility::DoDate("short_date", $row->created);?></p>
        </div>
      </div>
      <div class="item eq content"> <?php echo Validator::cleanOut($row->body);?></div>
      <div class="actions">
        <div class="item">
          <div class="intro"><?php echo Lang::$word->ACTIONS;?>:</div>
          <div class="data"><a href="<?php echo Url::adminUrl("news", "edit", false,"?id=" . $row->id);?>"><i class="rounded inverted positive icon pencil link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->NWA_DELNEWS;?>", "parent": ".row", "option": "deleteNews", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><i class="rounded inverted negative icon trash alt link"></i></a> </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<?php break;?>
<?php endswitch;?>
