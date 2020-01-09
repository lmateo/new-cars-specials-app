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
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!$row = $db->first(Users::aTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->M_SUB1;?> <small> / <?php echo $row->username;?></small> </div>
      <p><?php echo Lang::$word->M_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field disabled">
        <label><?php echo Lang::$word->USERNAME;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->username;?>" disabled="disabled" name="username">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->EMAIL;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->email;?>" name="email" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FNAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->fname;?>" name="fname" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->LNAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->lname;?>" name="lname" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->ACCTYPE;?></label>
        <select name="userlevel" data-cover="true">
          <option value=""><?php echo Lang::$word->SELECT;?></option>
          <?php echo Users::renderAccountTypes($row->userlevel);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PASSWORD;?><i class="icon pin" data-content="<?php echo Lang::$word->M_PASS_T;?>"></i></label>
        <input type="text" name="password">
      </div>
    </div>
    <div class="two fields">
       <div class="field">
        <label>Web Specials Email Alerts</label>
        <div class="inline-group">
          <label class="radio">
            <input name="webspecialsalert" type="radio" value="y" <?php Validator::getChecked($row->webspecialsalert, "y");?>>
            <i></i><?php echo Lang::$word->ACTIVE;?></label>
          <label class="radio">
            <input name="webspecialsalert" type="radio" value="n" <?php Validator::getChecked($row->webspecialsalert, "n");?>>
            <i></i><?php echo Lang::$word->INACTIVE;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->M_USTATUS;?></label>
        <div class="inline-group">
          <label class="radio">
            <input name="active" type="radio" value="y" <?php Validator::getChecked($row->active, "y");?>>
            <i></i><?php echo Lang::$word->ACTIVE;?></label>
          <label class="radio">
            <input name="active" type="radio" value="n" <?php Validator::getChecked($row->active, "n");?>>
            <i></i><?php echo Lang::$word->INACTIVE;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processStaff" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->M_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("staff");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php case"add": ?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->M_SUB2;?></div>
      <p><?php echo Lang::$word->M_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->USERNAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->USERNAME;?>" name="username" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->EMAIL;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->EMAIL;?>" name="email" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->FNAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->FNAME;?>" name="fname" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->LNAME;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->LNAME;?>" name="lname" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->PASSWORD;?></label>
        <div class="wojo icon input">
          <input id="staffPass" name="password" type="text" placeholder="<?php echo Lang::$word->PASSWORD;?>" required>
          <i id="generate" class="icon refresh" data-content="<?php echo Lang::$word->GENERATE;?>"></i> </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ACCTYPE;?></label>
        <select name="userlevel" data-cover="true">
          <option value=""><?php echo Lang::$word->SELECT;?></option>
          <?php echo Users::renderAccountTypes();?>
        </select>
      </div>
    </div>
    <div class="three fields">
    <div class="field">
        <div class="three fields fitted">
        <div class="field">
        <label>Web Specials Email Alerts</label>
        <div class="inline-group">
          <label class="radio">
            <input name="webspecialsalert" type="radio" value="y" checked="checked">
            <i></i><?php echo Lang::$word->ACTIVE;?></label>
          <label class="radio">
            <input name="webspecialsalert" type="radio" value="n">
            <i></i><?php echo Lang::$word->INACTIVE;?></label>
        </div>
      </div>
          <div class="field">
            <label><?php echo Lang::$word->M_USTATUS;?></label>
            <div class="inline-group">
              <label class="radio">
                <input name="active" type="radio" value="y" checked="checked">
                <i></i><?php echo Lang::$word->ACTIVE;?></label>
              <label class="radio">
                <input name="active" type="radio" value="n">
                <i></i><?php echo Lang::$word->INACTIVE;?></label>
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->M_NOTIFY;?> <i class="icon pin" data-content="<?php echo Lang::$word->M_NOTIFY_T;?>"></i></label>
            <div class="inline-group">
              <label class="checkbox">
                <input name="notify" type="checkbox" value="1">
                <i></i><?php echo Lang::$word->YES;?></label>
            </div>
          </div>
        </div>
      </div>
      <div class="field"></div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processStaff" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->M_ADD;?></button>
      <a href="<?php echo Url::adminUrl("staff");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
  </form>
</div>
<script type="text/javascript">
// <![CDATA[
 $(document).ready(function () {
	$('#generate').click(function(e) {
	    $('#staffPass').val($.password(8));
	});
 });
// ]]>
</script>
<?php break;?>
<?php default: ?>
<?php $data = $user->getAllStaff();?>
<div class="wojo secondary icon message"> <i class="user icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->M_TITLE6;?></div>
    <p><?php echo Lang::$word->M_INFO6;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->M_FILTER;?></div>
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
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBits(Url::adminUrl("staff"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->M_TITLE6;?></span> <a class="wojo large top right detached action label" data-tooltip-options='{"direction":"left"}' data-content="<?php echo Lang::$word->M_ADD;?>" href="<?php echo Url::adminUrl("staff", "add");?>" ><i class="icon plus"></i></a> </div>
  <table class="wojo table responsive">
    <thead>
      <tr>
        <th></th>
        <th><?php echo Lang::$word->USERNAME;?></th>
        <th><?php echo Lang::$word->EMAIL;?></th>
        <th><?php echo Lang::$word->NAME;?></th>
        <th><?php echo Lang::$word->LASTLOGIN;?></th>
        <th><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="6"><?php echo Message::msgSingleAlert(Lang::$word->M_NO_MEMBERS);?></td>
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
<?php break;?>
<?php endswitch;?>