<?php
  /**
   * WebSpecialsMailer
   *
   * @package Wojo Framework
   * @author Lorenzo Mateo
   * @copyright 2017
   * @version $Id: webspecialsmailer.php, v1.00 2017-11-02 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner", "admin")): print Message::msgError(Lang::$word->NOACCESS); return; endif; 
?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="icon paper plane"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->EMN_SUB;?> <?php echo isset(Filter::$get['mailid']) ? '<small>/ ' . Validator::sanitize($_GET['mailid'], "email") . '</small>' : null;?> </div>
      <p><?php echo Lang::$word->EMN_INFO . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->EMN_FROM;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $core->webspecials_email;?>" name="from" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->EMN_SUJECT;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="Web Specials Alerts" name="subject" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->EMN_REC;?></label>
        <?php if(isset(Filter::$get['mailid'])):?>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->EMN_REC;?>" name="recipient" value="<?php echo Validator::sanitize(Filter::$get['mailid'], "email");?>" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <?php if(isset(Filter::$get['clients'])):?>
        <input name="clients" type="hidden" value="1">
        <?php endif;?>
        <?php else:?>
        <select name="recipient" data-cover="true">
          <option value="">--- <?php echo Lang::$word->EMN_REC_SEL;?> ---</option>
          <option value="webspecials">Web Specials Alert Staff Only</option>
          <!-- <option value="members"><?php echo Lang::$word->EMN_REC_C;?></option>
          <option value="staff"><?php echo Lang::$word->EMN_REC_S;?></option>
          <option value="sellers"><?php echo Lang::$word->EMN_REC_M;?></option>
          <option value="newsletter"><?php echo Lang::$word->EMN_REC_N;?></option> -->
        </select>
        <?php endif;?>
      </div>
      <div class="field"> </div>
    </div>
    <div class="field">
      <textarea class="bodypost" name="body"><?php echo file_get_contents(BASEPATH . "/mailer/" . $core->lang . "/WebSpecials_Changes_Notify.tpl.php");?></textarea>
    </div>
    <div class="wojo error notice"><i class="icon info sign"></i>
      <div class="content"><?php echo Lang::$word->NOTEVAR;?></div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processEmail" name="dosubmit" class="wojo positive button"><?php echo Lang::$word->EMN_SEND;?></button>
    </div>
  </form>
</div>