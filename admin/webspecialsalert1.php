<?php
  /**
   * Staff Members
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: staff.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  if(!Users::checkAcl("owner", "admin")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php $data = $user->getAllStaff();?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->WSALERT_TITLE;?></div>
    <p><?php echo Lang::$word->WSALERT_INFO;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->WSALERT_FILTER;?></div>
  <div class="content">
    <div class="wojo form">
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("staff");?>" name="wojo_form">
        <div class="three fields">
          <div class="field">
            <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
              <input name="fromdate" type="text" id="fromdate" placeholder="<?php echo Lang::$word->FROM;?>" readonly data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
            </div>
          </div>
          <div class="field">
            <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
              <input name="enddate" type="text" id="enddate" placeholder="<?php echo Lang::$word->TO;?>" readonly data-date-autoclose="true" data-min-view="2" data-start-view="2" data-date-today-btn="true" data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
              <a id="doDates" class="wojo primary button"><?php echo Lang::$word->FIND;?></a> </div>
          </div>
          <div class="field">
            <div class="wojo icon input">
              <input type="text" name="staffsearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->WSALERT_TITLE;?></span></div>
  <table class="wojo table responsive">
    <thead>
      <tr>
        <th></th>
        <th><?php echo Lang::$word->WSALERT_FIELD;?></th>
        <th><?php echo Lang::$word->WSALERT_CH_NEW;?></th>
        <th><?php echo Lang::$word->WSALERT_CH_OLD;?></th>
        <th><?php echo Lang::$word->WSALERT_CH_BY;?></th>
        <th><?php echo Lang::$word->WSALERT_CH_ON;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="6"><?php echo Message::msgSingleAlert(Lang::$word->WSALERT_NO_ALERTS);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($data as $row):?>
      <tr>
        <td><?php echo Users::accountTypeIcon($row->userlevel);?></td>
        <td><?php echo $row->username;?></td>
        <td><a data-content="<?php echo Lang::$word->M_SENDMAIL;?>" href="<?php echo Url::adminUrl("mailer", false,"?mailid=" . urlencode($row->email));?>"><?php echo $row->email;?></a></td>
        <td><?php echo $row->fullname;?></td>
        <td><?php echo (strtotime($row->last_active) === false) ? "-/-" : Utility::dodate("short_date", $row->last_active);?></td>
        <td><a href="<?php echo Url::adminUrl("staff", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->M_DEL_MEMBER;?>", "parent": "tr", "option": "deleteStaff", "id": <?php echo $row->id;?>, "name": "<?php echo $row->fullname;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>