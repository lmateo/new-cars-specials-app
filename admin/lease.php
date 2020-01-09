<?php
  /**
   * Lease
   *
   * @package Wojo Framework
   * @author Lorenzo Mateo
   * @copyright 2017
   * @version $Id: lease.php, v1.00 2017-08-30 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!Auth::hasPrivileges('manage_zerosingle')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php $data = $content->getLease();?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LEASE_SUB;?></div>
    <p><?php echo Lang::$word->LEASE_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->LEASE_TITLE;?></span> <a data-set='{"title": "<?php echo Lang::$word->LEASE_ADDNEW;?>", "option": "addNewLease", "label": "<?php echo Lang::$word->LEASE_ADD;?>", "redirect": "<?php echo Url::adminUrl("lease");?>"}' id="addNew" class="wojo large top right detached action label" data-content="<?php echo Lang::$word->LEASE_ADD;?>"><i class="icon plus"></i></a> </div>
  <?php if(!$data):?>
  <?php echo Message::msgSingleAlert(Lang::$word->LEASE_NOLeasetype);?>
  <?php else:?>
  <ul class="wojo block grid divided large-3 medium-2 small-1" id="editable">
    <?php foreach($data as $row):?>
    <li class="item"> <a class="delete wojo top right corner label" data-set='{"title": "<?php echo Lang::$word->LEASE_DEL;?>", "parent": "li", "option": "deleteLease", "id": <?php echo $row->id;?>, "name": "<?php echo $row->name;?>"}'><i class="icon negative delete link"></i></a>
      <div class="content" data-editable="true" data-set='{"type": "lease", "id": <?php echo $row->id;?>,"key":"name", "path":""}'><?php echo $row->name;?></div>
    </li>
    <?php endforeach;?>
  </ul>
  <?php endif;?>
</div>