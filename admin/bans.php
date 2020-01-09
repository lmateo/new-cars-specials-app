<?php
  /**
   * Ban List
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: bans.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner", "admin")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php $data = $content->getBanList();?>
<div class="wojo secondary icon message"> <i class="user remove icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->BL_TITLE;?></div>
    <p><?php echo Lang::$word->BL_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix relative"><span><?php echo Lang::$word->BL_SUB;?></span>
    <div class="wojo multiple labels">
      <ul>
        <li><a class="action" data-set='{"title": "<?php echo Lang::$word->BL_ADDNEW;?>", "option": "addNewBan", "label": "<?php echo Lang::$word->BL_ADD;?>", "redirect": "<?php echo Url::adminUrl("bans");?>"}' data-tooltip-options='{"direction":"left"}' data-content="<?php echo Lang::$word->BL_ADD;?>" id="addNew"><i class="icon plus"></i></a></li>
        <li>
          <div class="wojo labeled icon top right pointing dropdown action"> <i class="icon ellipsis vertical alt" data-tooltip-options='{"direction":"left"}' data-content="<?php echo Lang::$word->FILTER;?>"></i>
            <div class="menu"> <a href="<?php echo Url::adminUrl("bans");?>" class="item"><i class="filter icon"></i><?php echo Lang::$word->BL_RESET;?></a> <a href="<?php echo Url::adminUrl("bans", false, false,"?sort=ip");?>" class="item<?php echo (Validator::get('sort') == "ip") ? " active" : null;?>"><i class="earth icon"></i><?php echo Lang::$word->IP;?></a> <a href="<?php echo Url::adminUrl("bans", false, false,"?sort=email");?>" class="item<?php echo (Validator::get('sort') == "email") ? " active" : null;?>"><i class="email icon"></i><?php echo Lang::$word->EMAIL;?></a> </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
  <table class="wojo sortable table">
    <thead>
      <tr>
        <th data-sort="int"></th>
        <th data-sort="string"><?php echo Lang::$word->BL_ITEM;?></th>
        <th data-sort="string"><?php echo Lang::$word->BL_TYPE;?></th>
        <th data-sort="string"><?php echo Lang::$word->COMMENT;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="5"><?php echo Message::msgSingleInfo(Lang::$word->BL_NOBAN);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($data as $row):?>
      <tr>
        <td><small><?php echo $row->id;?>.</small></td>
        <td><?php echo $row->item;?></td>
        <td><?php echo $row->type;?></td>
        <td><?php echo $row->comment;?></td>
        <td><a class="delete" data-set='{"title": "<?php echo Lang::$word->BL_DEL_BAN;?>", "parent": "tr", "option": "deleteBanItem", "id": <?php echo $row->id;?>, "name": "<?php echo $row->item;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>