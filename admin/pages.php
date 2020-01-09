<?php
  /**
   * Content Pages
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: pages.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!Auth::hasPrivileges('edit_pages')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php if(!$row = $db->first(Content::pgTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->PAG_SUB1;?> <small> / <?php echo $row->title;?></small> </div>
      <p><?php echo Lang::$word->PAG_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->PAG_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->title;?>" name="title" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_SLUG;?><i class="icon pin" data-content="<?php echo Lang::$word->PAG_SLUG_T;?>"></i></label>
        <label class="input">
          <input type="text" value="<?php echo $row->slug;?>" name="slug">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_DATE;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input data-datepicker="true" data-date="<?php echo $row->created;?>" type="text" value="<?php echo Utility::doDate("short_date", $row->created);?>" name="created">
        </label>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->PAG_HOME;?><i class="icon pin" data-content="<?php echo Lang::$word->PAG_HOME_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="home_page" type="radio" value="1" <?php echo Validator::getChecked($row->home_page, 1);?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="home_page" type="radio" value="0" <?php echo Validator::getChecked($row->home_page, 0);?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_FAQPAGE;?><i class="icon pin" data-content="<?php echo Lang::$word->PAG_FAQPAGE_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="faq" type="radio" value="1" <?php echo Validator::getChecked($row->faq, 1);?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="faq" type="radio" value="0" <?php echo Validator::getChecked($row->faq, 0);?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_CNPAGE;?><i class="icon pin" data-content="<?php echo Lang::$word->PAG_CNPAGE_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="contact" type="radio" value="1" <?php echo Validator::getChecked($row->contact, 1);?>>
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="contact" type="radio" value="0" <?php echo Validator::getChecked($row->contact, 0);?>>
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
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
    <div class="field">
      <label><?php echo Lang::$word->PAG_BODY;?></label>
      <textarea name="body" class="bodypost"><?php echo $row->body;?></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processPage" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->PAG_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("pages");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php case"add": ?>
<?php if(!Auth::hasPrivileges('add_pages')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->PAG_SUB2;?></div>
      <p><?php echo Lang::$word->PAG_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->PAG_NAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->PAG_NAME;?>" name="title" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_SLUG;?><i class="icon pin" data-content="<?php echo Lang::$word->PAG_SLUG_T;?>"></i></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->PAG_SLUG;?>" name="slug">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_DATE;?></label>
        <label class="input"><i class="icon-append icon calendar"></i>
          <input data-datepicker="true" type="text" placeholder="<?php echo Lang::$word->PAG_DATE;?>" name="created">
        </label>
      </div>
    </div>
    <div class="wojo divider"></div>
    <div class="four fields">
      <div class="field">
        <label><?php echo Lang::$word->PAG_HOME;?><i class="icon pin" data-content="<?php echo Lang::$word->PAG_HOME_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="home_page" type="radio" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="home_page" type="radio" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_FAQPAGE;?><i class="icon pin" data-content="<?php echo Lang::$word->PAG_FAQPAGE_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="faq" type="radio" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="faq" type="radio" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PAG_CNPAGE;?><i class="icon pin" data-content="<?php echo Lang::$word->PAG_CNPAGE_T;?>"></i></label>
        <div class="inline-group">
          <label class="radio">
            <input name="contact" type="radio" value="1">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="contact" type="radio" value="0" checked="checked">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PUBLISHED;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="active" type="radio" value="1" checked="checked">
            <i></i><?php echo Lang::$word->YES;?></label>
          <label class="radio">
            <input name="contact" type="radio" value="0">
            <i></i><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="field">
      <label><?php echo Lang::$word->PAG_BODY;?></label>
      <textarea name="body" class="bodypost" placeholder="<?php echo Lang::$word->PAG_BODY;?>"></textarea>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processPage" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->PAG_ADD;?></button>
      <a href="<?php echo Url::adminUrl("pages");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
  </form>
</div>
<?php break;?>
<?php default: ?>
<?php $data = $content->getPages();?>
<div class="wojo secondary icon message"> <i class="file icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->PAG_TITLE;?></div>
    <p><?php echo Lang::$word->PAG_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->PAG_SUB;?></span>
    <?php if(Auth::hasPrivileges('add_pages')):?>
    <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->PAG_ADD;?>" href="<?php echo Url::adminUrl("pages", "add");?>"><i class="icon plus"></i></a>
    <?php endif;?>
  </div>
  <table class="wojo sortable table">
    <thead>
      <tr>
        <th class="disabled">#</th>
        <th data-sort="string"><?php echo Lang::$word->PAG_NAME;?></th>
        <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
        <th class="disabled"><?php echo Lang::$word->PAG_TYPE;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="5"><?php echo Message::msgSingleInfo(Lang::$word->PAG_NOPAGE);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr>
        <td><small><?php echo $row->id;?>.</small></td>
        <td><?php echo $row->title;?></td>
        <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Utility::doDate("short_date", $row->created);?></td>
        <td><?php if($row->contact):?>
          <i class="rounded outline icon email"></i>
          <?php elseif($row->faq):?>
          <i class="rounded outline icon question sign"></i>
          <?php elseif($row->home_page):?>
          <i class="rounded outline icon home"></i>
          <?php else:?>
          <i class="rounded outline icon note"></i>
          <?php endif;?></td>
        <td><?php if(Auth::hasPrivileges('edit_pages')):?>
          <a href="<?php echo Url::adminUrl("pages", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a>
          <?php endif;?>
          <?php if(Auth::hasPrivileges('delete_pages')):?>
          <a class="delete" data-set='{"title": "<?php echo Lang::$word->PAG_DELPAGE;?>", "parent": "tr", "option": "deletePage", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><i class="rounded outline icon negative trash link"></i></a>
          <?php endif;?></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<?php break;?>
<?php endswitch;?>