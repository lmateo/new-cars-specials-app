<?php
/**
 * Categories
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2014
 * @version $Id: categories.php, v1.00 2014-10-08 10:12:05 gewa Exp $
 */
if (!defined("_WOJO"))
	die('Direct access to this location is not allowed.');
?>
<?php if(!Auth::hasPrivileges('manage_cats')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!$row = $db->first(Content::ctTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>

<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->CAT_SUB1;?> <small> / <?php echo $row->name;?></small> </div>
      <p><?php echo Lang::$word->CAT_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CAT_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->name;?>" name="name" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CAT_SLUG;?><i class="icon pin" data-content="<?php echo Lang::$word->CAT_SLUG_T;?>"></i></label>
        <label class="input">
          <input type="text" value="<?php echo $row->slug;?>" name="slug">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CAT_IMG;?></label>
        <input type="file" name="image" data-type="image" data-exist="<?php echo ($row->image) ? UPLOADURL . 'catico/' . $row->image : UPLOADURL . 'blank.png';?>" accept="image/png, image/jpeg">
      </div>
      <div class="field"> </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processCategory" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->CAT_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("categories");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php case"add": ?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->CAT_SUB2;?></div>
      <p><?php echo Lang::$word->CAT_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CAT_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->CAT_NAME;?>" name="name" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CAT_SLUG;?><i class="icon pin" data-content="<?php echo Lang::$word->CAT_SLUG_T;?>"></i></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->CAT_SLUG;?>" name="slug">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CAT_IMG;?></label>
        <input type="file" name="image" data-type="image" accept="image/png, image/jpeg">
      </div>
      <div class="field"> </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processCategory" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->CAT_ADD;?></button>
      <a href="<?php echo Url::adminUrl("categories");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
  </form>
</div>
<?php break;?>
<?php default: ?>
<?php $data = $content->getCategories();?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->CAT_TITLE;?></div>
    <p><?php echo Lang::$word->CAT_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->CAT_SUB;?></span> <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->CAT_ADD;?>" href="<?php echo Url::adminUrl("categories", "add");?>" ><i class="icon plus"></i></a> </div>
  <table class="wojo grid table">
    <thead>
      <tr>
        <th class="disabled"><i class="icon photo"></i></th>
        <th data-sort="string"><?php echo Lang::$word->CAT_NAME;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="3"><?php echo Message::msgSingleInfo(Lang::$word->CAT_NOCAT);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr>
        <td><a href="<?php echo $row->image ? UPLOADURL . 'catico/' . $row->image : UPLOADURL . 'blank.png';?>" data-lightbox="true"><img src="<?php echo $row->image ? UPLOADURL . 'catico/' . $row->image : UPLOADURL . 'blank.png';?>" alt="" class="wojo basic grid image small"></a></td>
        <td><?php echo $row->name;?></td>
        <td><a href="<?php echo Url::adminUrl("categories", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->CAT_DEL;?>", "parent": "tr", "option": "deleteCategory", "id": <?php echo $row->id;?>, "name": "<?php echo $row->name;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<?php break;?>
<?php endswitch;?>