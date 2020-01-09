<?php
  /**
   * My Account
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: myaccount.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php if(!$row = $db->first(Users::aTable, null, array('id' => $auth->uid))) : Message::invalid("ID" . $auth->uid); return; endif;?>

<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="lock icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->M_TITLE;?> <small>/ <?php echo $row->username;?></small> </div>
      <p><?php echo Lang::$word->M_INFO . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
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
        <label><?php echo Lang::$word->PASSWORD;?><i class="icon pin" data-content="<?php echo Lang::$word->M_PASS_T;?>"></i></label>
        <input type="text" name="password">
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->EMAIL;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->email;?>" name="email" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ACCTYPE;?></label>
        <span class="wojo secondary icon button"><?php echo Users::accountType($row->userlevel);?></span> </div>
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
        <label><?php echo Lang::$word->AVATAR;?></label>
        <input type="file" name="avatar" data-type="image" data-exist="<?php echo ($row->avatar) ? UPLOADURL . 'avatars/' . $row->avatar : UPLOADURL . 'avatars/blank.png';?>" accept="image/png, image/jpeg">
      </div>
      <div class="field"> </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="updateAccount" name="dosubmit" class="wojo positive button"><?php echo Lang::$word->M_UPDATE;?></button>
    </div>
  </form>
</div>
