<?php
  /**
   * Members
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: members.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!Auth::hasPrivileges('edit_members')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php if(!$row = $db->first(Users::mTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $datacountry = $content->getCountryList();?>
<?php $datamembership = $user->getMemberships();?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->CL_SUB4;?> <small>/ <?php echo $row->username;?></small> </div>
      <p><?php echo Lang::$word->CL_INFO4 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
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
        <label><?php echo Lang::$word->COMPANY;?></label>
        <input type="text" value="<?php echo $row->company;?>" name="company" required>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ADDRESS;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->address;?>" name="address" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CITY;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->city;?>" name="city" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATE;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->state;?>" name="state" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ZIP;?></label>
        <div class="wojo labeled icon input">
          <input type="text" value="<?php echo $row->zip;?>" name="zip" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->WEBSITE;?></label>
        <label class="input">
          <input type="text" value="<?php echo $row->url;?>" name="url">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PASSWORD;?><i class="icon pin" data-content="<?php echo Lang::$word->M_PASS_T;?>"></i></label>
        <input type="text" name="password">
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->COUNTRY;?></label>
        <select name="country">
          <option value="">-- <?php echo Lang::$word->CNT_SELECT;?> --</option>
          <?php echo Utility::loopOptions($datacountry, "abbr", "name", $row->country);?>
        </select>
      </div>
      <div class="field disabled">
        <label><?php echo Lang::$word->LISTINGS;?></label>
        <label class="input">
          <input name="sites" type="text" disabled value="<?php echo $row->listings;?>" readonly>
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MEMBERSHIP;?><i class="icon pin" data-content="<?php echo Lang::$word->CL_NOMEMBERSHIP_T;?>"></i></label>
        <select name="membership_id">
          <option value="0">--- <?php echo Lang::$word->CL_NOMEMBERSHIP;?> ---</option>
          <?php echo Utility::loopOptions($datamembership, "id", "title", $row->membership_id);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CL_DATEEX;?><i class="icon pin" data-content="<?php echo Lang::$word->CL_DATEEX_T;?>"></i></label>
        <div class="two fields">
          <div class="field">
            <label class="input"><i class="icon-prepend icon calendar"></i>
              <input data-datepicker="true" data-min-view="2" data-start-view="2" type="text" name="mem_expire">
            </label>
          </div>
          <div class="field">
            <label class="input"><i class="icon-prepend icon clock"></i>
              <input data-timepicker="true" type="text" name="mem_expiret">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field disabled">
        <label><?php echo Lang::$word->LASTLOGIN;?></label>
        <label class="input">
          <input name="last_active" type="text" disabled value="<?php echo (strtotime($row->last_active) === false) ? "-/-" : Utility::dodate("long_date", $row->last_active);?>" readonly>
        </label>
      </div>
      <div class="field disabled">
        <label><?php echo Lang::$word->LASTIP;?></label>
        <label class="input">
          <input name="lastip" type="text" disabled value="<?php echo $row->lastip;?>" readonly>
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field disabled">
        <label><?php echo Lang::$word->CREATED;?></label>
        <label class="input">
          <input name="created" type="text" disabled value="<?php echo Utility::dodate("long_date", $row->created);?>" readonly>
        </label>
      </div>
      <div class="field">

      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CL_COMMENTS;?> <i class="icon pin" data-content="<?php echo Lang::$word->CL_COMMENTS_T;?>"></i></label>
        <textarea name="comments"><?php echo $row->comments;?></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->M_ABOUT;?> <i class="icon pin" data-content="<?php echo Lang::$word->M_ABOUT_T;?>"></i></label>
        <textarea name="about"><?php echo $row->about;?></textarea>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processMember" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->M_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("members");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
  </form>
</div>
<?php break;?>
<?php case"add": ?>
<?php $datacountry = $content->getCountryList();?>
<?php $datamembership = $user->getMemberships();?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->CL_SUB2;?></div>
      <p><?php echo Lang::$word->CL_INFO . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
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
        <label><?php echo Lang::$word->WEBSITE;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->WEBSITE;?>" name="url">
        </label>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->COMPANY;?></label>
        <input name="company" type="text" placeholder="<?php echo Lang::$word->COMPANY;?>">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ADDRESS;?></label>
        <label class="input">
          <input type="text" placeholder="<?php echo Lang::$word->ADDRESS;?>" name="address">
        </label>
      </div>
    </div>
    <div class="three fields">
      <div class="field">
        <label><?php echo Lang::$word->CITY;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->CITY;?>" name="city" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATE;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->STATE;?>" name="state" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ZIP;?></label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="<?php echo Lang::$word->ZIP;?>" name="zip" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->MEMBERSHIP;?><i class="icon pin" data-content="<?php echo Lang::$word->CL_NOMEMBERSHIP_T;?>"></i></label>
        <select name="membership_id">
          <option value="0">--- <?php echo Lang::$word->CL_NOMEMBERSHIP;?> ---</option>
          <?php echo Utility::loopOptions($datamembership, "id", "title");?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CL_DATEEX;?><i class="icon pin" data-content="<?php echo Lang::$word->CL_DATEEX_T;?>"></i></label>
        <div class="two fields">
          <div class="field">
            <label class="input"><i class="icon-prepend icon calendar"></i>
              <input data-datepicker="true" data-min-view="2" data-start-view="2" type="text" name="mem_expire">
            </label>
          </div>
          <div class="field">
            <label class="input"><i class="icon-prepend icon clock"></i>
              <input data-timepicker="true" type="text" name="mem_expiret">
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CF_LOGO;?>/<?php echo Lang::$word->AVATAR;?></label>
        <input type="file" name="avatar" data-type="image" accept="image/png, image/jpeg">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->COUNTRY;?></label>
        <select name="country">
          <option value="">-- <?php echo Lang::$word->CNT_SELECT;?> --</option>
          <?php echo Utility::loopOptions($datacountry, "abbr", "name");?>
        </select>
        <div class="half-top-space">
          <div class="two fields fitted">
            <div class="field">
              <label><?php echo Lang::$word->M_NOTIFY;?> <i class="icon pin" data-content="<?php echo Lang::$word->M_NOTIFY_T;?>"></i></label>
              <div class="inline-group">
                <label class="checkbox">
                  <input name="notify" type="checkbox" value="1">
                  <i></i><?php echo Lang::$word->YES;?></label>
              </div>
            </div>
            <div class="field"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->CL_COMMENTS;?> <i class="icon pin" data-content="<?php echo Lang::$word->CL_COMMENTS_T;?>"></i></label>
        <textarea name="comments" placeholder="<?php echo Lang::$word->CL_COMMENTS;?>"></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->M_ABOUT;?> <i class="icon pin" data-content="<?php echo Lang::$word->M_ABOUT_T;?>"></i></label>
        <textarea name="about" placeholder="<?php echo Lang::$word->M_ABOUT;?>"></textarea>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processMember" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->CL_ADD;?></button>
      <a href="<?php echo Url::adminUrl("members");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
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
<?php case"payments": ?>
<?php if(!$usr = $db->first(Users::mTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $data = $content->getUserTransactions();?>
<div class="wojo secondary icon message"> <i class="money bag icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->TRX_TITLE3;?> <small>/ <?php echo $usr->username;?></small> </div>
    <p><?php echo Lang::$word->TRX_INFO2;?></p>
  </div>
</div>
<?php if(!$data):?>
<?php echo Message::msgSingleAlert(Lang::$word->TRX_NOTRANS);?>
<?php else:?>
<div class="wojo space divider"></div>
<div class="columns gutters">
  <?php foreach ($data as $row):?>
  <div class="row screen-50 tablet-50 phone-100">
    <div class="wojo card">
      <div class="header">
        <div class="wojo top right small attached label"><?php echo $row->id;?>.</div>
        <div class="wojo circular primary small image"><img src="<?php echo UPLOADURL;?>avatars/<?php echo ($usr->avatar) ? $usr->avatar : "blank.png";?>" alt=""></div>
        <div class="content">
          <?php if(Auth::hasPrivileges('edit_members')):?>
          <a href="<?php echo Url::adminUrl("members", "edit", false,"?id=" . $usr->id);?>"><?php echo $usr->fname . ' ' . $usr->lname;?> </a>
          <?php else:?>
          <?php echo $usr->fname . ' ' . $usr->lname;?>
          <?php endif;?>
          <p><?php echo Lang::$word->LASTLOGIN;?>: <?php echo (strtotime($usr->last_active) === false) ? "never" : Utility::dodate("long_date", $usr->last_active);?></p>
        </div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->MSM_NAME;?>:</div>
        <div class="data"><?php echo $row->title;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->AMOUNT;?>:</div>
        <div class="data"><?php echo Utility::formatMoney($row->rate_amount, true);?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->TRX_COUPON;?>:</div>
        <div class="data"><?php echo Utility::formatMoney($row->coupon, true);?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->VAT;?>:</div>
        <div class="data"><?php echo Utility::formatMoney($row->coupon, true);?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->TRX_PAYDATE;?>:</div>
        <div class="data"><?php echo Utility::dodate("long_date", $row->created);?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->TRX_PROCESSOR;?>:</div>
        <div class="data"><small class="wojo primary label"><?php echo $row->pp;?></small></div>
      </div>
      <div class="item">
        <div class="intro">IP:</div>
        <div class="data"><?php echo $row->ip;?></div>
      </div>
      <div class="item">
        <div class="intro">#:</div>
        <div class="data"><?php echo $row->txn_id;?></div>
      </div>
      <div class="actions">
        <div class="item">
          <div class="intro"><?php echo Lang::$word->ACTIONS;?>:</div>
          <div class="data"><a href="<?php echo ADMINURL;?>/helper.php?doInvoice=1&amp;id=<?php echo $row->id;?>&amp;uid=<?php echo $row->user_id;?>"><i class="rounded inverted positive icon printer link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->TRX_DELETE;?>", "parent": ".row", "option": "deleteTransaction", "id": <?php echo $row->id;?>, "name": "<?php echo $row->txn_id;?>"}'><i class="rounded inverted negative icon trash alt link"></i></a> </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<?php break;?>
<?php case "cars": ?>
<?php if(!$usr = $db->first(Users::mTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $data = $items->getUserItems();?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->CL_TITLE6;?> <small>/ <?php echo $usr->username;?></small> </div>
    <p><?php echo Lang::$word->CL_INFO6;?></p>
  </div>
</div>
<?php if(!$data):?>
<?php echo Message::msgSingleInfo(Lang::$word->CL_NO_LIST);?>
<?php else:?>
<div id="listwrap">
  <?php foreach($data as $row):?>
  <div class="row">
    <div class="wojo quaternary segment"> <a href="<?php echo UPLOADURL . 'listings/' . $row->thumb?>" data-title="<?php echo $row->title;?>" data-lightbox-gallery="true" data-lightbox="true"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt="" class="wojo grid image"></a>
      <div class="content"><?php echo Validator::truncate($row->title, 50);?>
        <div class="wojo fitted divider"></div>
        <div class="content-center">
          <div class="wojo label"><?php echo Lang::$word->_YEAR;?> <span class="detail"><?php echo $row->year;?></span></div>
          <div class="wojo label"><?php echo Lang::$word->PRICE;?> <span class="detail"><?php echo Utility::formatMoney($row->price);?></span></div>
          <div class="wojo label"><?php echo Lang::$word->VISITS;?> <span class="detail"><?php echo $row->hits;?></span></div>
        </div>
      </div>
      <?php if(Auth::hasPrivileges('edit_items') and Auth::hasPrivileges('delete_items')):?>
      <div class="footer actions content-center clearfix">
        <?php if(Auth::hasPrivileges('edit_items')):?>
        <a href="<?php echo Url::adminUrl("items", "edit", false,"?id=" . $row->id);?>"><i class="icon positive long arrow left"></i> <?php echo Lang::$word->LST_EDIT;?></a>
        <?php endif;?>
        <?php if(Auth::hasPrivileges('delete_items')):?>
        <a class="delete" data-set='{"title": "<?php echo Lang::$word->LST_DELIST;?>", "parent": ".row", "option": "deleteItem", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><?php echo Lang::$word->LST_DELIST;?> <i class="icon negative long arrow right"></i></a>
        <?php endif;?>
      </div>
      <?php endif;?>
    </div>
  </div>
  <?php endforeach;?>
  <?php unset($row);?>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
    var $container = $("#listwrap");
    $container.imagesLoaded(function() {
        $container.masonry({
            columnWidth: 400,
            itemSelector: ".row",
            isFitWidth: true,
            gutter: 36,
        });
    });
});
// ]]>
</script>
<?php endif;?>
<?php break;?>
<?php case"activity": ?>
<?php if(!$usr = $db->first(Users::mTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $data = $items->getUserActivity();?>
<div class="wojo secondary icon message"> <i class="user profile icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->CL_TITLE5;?></div>
    <p><?php echo Lang::$word->CL_INFO5;?></p>
  </div>
</div>
<div class="columns gutters">
  <div class="screen-30 tablet-100 phone-100">
    <div class="wojo white card">
      <div class="heading"><img class="" src="<?php echo UPLOADURL;?>avatars/<?php echo ($usr->avatar) ? $usr->avatar : "blank.png";?>" alt="">
        <p><?php echo $usr->username;?></p>
      </div>
      <div class="wojo form">
        <form method="post" id="wojo_form" name="wojo_form">
          <div class="half-padding">
            <div class="field">
              <label><?php echo Lang::$word->CL_QMSG;?></label>
              <textarea name="msg" required></textarea>
              <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
            </div>
          </div>
          <div class="footer content-center"><a data-action="quickMessage"  id="sendMessage" class="dosubmit bform"><?php echo Lang::$word->CL_SENDMSG;?> <i class="icon long arrow right"></i></a></div>
        </form>
      </div>
    </div>
  </div>
  <div class="screen-70 tablet-100 phone-100">
    <div class="wojo tertiary segment">
      <div class="header"><?php echo $usr->fname . ' ' . $usr->lname;?> // <?php echo $usr->email;?></div>
      <div class="content">
        <?php if($usr->about):?>
        <p><?php echo $usr->about;?></p>
        <div class="wojo divider"></div>
        <?php endif;?>
        <div class="content-center">
          <div class="wojo divided horizontal list">
            <div class="item"><i class="icon earth"></i> <?php echo $usr->lastip;?></div>
            <div class="item"><i class="icon clock"></i> <?php echo Utility::timesince($usr->created);?></div>
            <a class="item" href="<?php echo Url::adminUrl("members", "cars", false,"?id=" . $usr->id);?>"><i class="icon car"></i> <?php echo Lang::$word->LISTINGS;?> <?php echo $usr->listings;?></a> </div>
        </div>
        <div class="wojo divider"></div>
        <?php if(!$data):?>
        <?php echo Message::msgSingleInfo(Lang::$word->CL_NO_ACC);?>
        <?php else:?>
        <div class="wojo feed scrollbox">
          <?php foreach ($data as $row):?>
          <div class="event">
            <div class="label"> <i class="icon circular <?php echo Items::activityIcons($row->type)?>"></i> </div>
            <div class="content">
              <div class="summary"> <?php echo Items::activityTitle($row);?>
                <div class="date"> <?php echo Utility::timesince($row->created);?> </div>
              </div>
              <div class="extra text"> <?php echo Items::activityDesc($row);?> </div>
            </div>
          </div>
          <?php endforeach;?>
          <?php unset($row);?>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<?php break;?>
<?php case"view": ?>
<?php $data = $user->getAllMembers(false, "view");?>
<div class="wojo secondary icon message"> <i class="users icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->CL_TITLE;?></div>
    <p><?php echo Lang::$word->CL_INFO2;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->M_FILTER;?></div>
  <div class="content">
    <div class="wojo form">
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->CURPAGE;?></label>
          <?php echo $pager->jump_menu();?></div>
        <div class="field">
          <label><?php echo Lang::$word->IPP;?></label>
          <?php echo $pager->items_per_page();?></div>
        <div class="field">
          <label><?php echo Lang::$word->LAYOUT;?></label>
          <div class="two fields fitted">
            <div class="field">
              <div class="wojo labeled fluid disabled icon button"> <i class="grid icon"></i> <?php echo Lang::$word->GRID;?> </div>
            </div>
            <div class="field"> <a href="<?php echo Url::adminUrl("members", false);?>" class="wojo right labeled icon fluid secondary button"> <i class="reorder icon"></i> <?php echo Lang::$word->LIST;?> </a> </div>
          </div>
        </div>
      </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("members/view");?>" name="wojo_form">
        <div class="three fields">
          <div class="field">
            <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
              <input name="fromdate" type="text" id="fromdate" placeholder="<?php echo Lang::$word->FROM;?>" readonly data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
            </div>
          </div>
          <div class="field">
            <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
              <input name="enddate" type="text" id="enddate" placeholder="<?php echo Lang::$word->TO;?>" readonly data-date-autoclose="true" data-min-view="2" data-start-view="2" data-date-today-btn="true" data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
              <a id="doDates" class="wojo primary icon button"><?php echo Lang::$word->FIND;?></a> </div>
          </div>
          <div class="field">
            <div class="wojo icon input">
              <input type="text" name="membersearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="content-center">
      <div class="wojo divided horizontal link list">
        <div class="disabled item"> <?php echo Lang::$word->SORTING_O;?> </div>
        <a href="<?php echo Url::adminUrl("members/view");?>" class="item<?php echo Url::setActive("order", false);?>"> <?php echo Lang::$word->DEFAULT;?> </a> <a href="<?php echo Url::adminUrl("members/view", false, false, "?order=listings/DESC");?>" class="item<?php echo Url::setActive("order", "listings");?>"> #<?php echo Lang::$word->LISTINGS;?> </a> <a href="<?php echo Url::adminUrl("members/view", false, false, "?order=membership_id/DESC");?>" class="item<?php echo Url::setActive("order", "membership_id");?>"> <?php echo Lang::$word->MEMBERSHIP;?> </a> <a href="<?php echo Url::adminUrl("members/view", false, false, "?order=username/DESC");?>" class="item<?php echo Url::setActive("order", "username");?>"> <?php echo Lang::$word->USERNAME;?> </a> <a href="<?php echo Url::adminUrl("members/view", false, false, "?order=fname/DESC");?>" class="item<?php echo Url::setActive("order", "fname");?>"> <?php echo Lang::$word->FULLNAME;?> </a>
        <div class="item" data-content="ASC/DESC"><a href="<?php echo Url::sortItems(Url::adminUrl("members/view"), "order");?>"><i class="icon unfold more link"></i></a> </div>
      </div>
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBits(Url::adminUrl("members", "view"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<?php if(Auth::hasPrivileges('add_members')):?>
<div class="clearfix"> <a class="wojo right labeled icon secondary button push-right" href="<?php echo Url::adminUrl("members", "add");?>"><i class="icon plus"></i><?php echo Lang::$word->CL_ADD;?></a> </div>
<?php endif;?>
<?php if(!$data):?>
<?php echo Message::msgSingleAlert(Lang::$word->CL_NO_CLIENTS);?>
<?php else:?>
<div class="wojo space divider"></div>
<div class="columns gutters">
  <?php foreach ($data as $row):?>
  <div class="row screen-50 tablet-50 phone-100">
    <div class="wojo divided card">
      <div class="header">
        <div class="wojo top right small attached label"><?php echo $row->id;?>.</div>
        <div class="wojo circular primary small image"><img src="<?php echo UPLOADURL;?>avatars/<?php echo ($row->avatar) ? $row->avatar : "blank.png";?>" alt=""></div>
        <div class="content">
          <?php if(Auth::hasPrivileges('edit_members')):?>
          <a href="<?php echo Url::adminUrl("members", "edit", false,"?id=" . $row->id);?>"><?php echo $row->fullname;?> </a>
          <?php else:?>
          <?php echo $row->fullname;?>
          <?php endif;?>
          <p><?php echo Lang::$word->LASTLOGIN;?>: <?php echo (strtotime($row->last_active) === false) ? "never" : Utility::dodate("long_date", $row->last_active);?></p>
        </div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->USERNAME;?>:</div>
        <div class="data"><?php echo $row->username;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->EMAIL;?>:</div>
        <div class="data"><a data-content="<?php echo Lang::$word->M_SENDMAIL;?>" href="<?php echo Url::adminUrl("mailer", "emailid", urlencode($row->email));?>/"><?php echo $row->email;?></a></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->COMPANY;?>:</div>
        <div class="data"><?php echo $row->company;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->WEBSITE;?>:</div>
        <div class="data"><?php echo $row->url;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->ADDRESS;?>:</div>
        <div class="data"><?php echo $row->address;?>
          <p><?php echo $row->city;?>, <?php echo $row->state;?>, <?php echo $row->zip;?></p>
        </div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->MEMBERSHIP;?>:</div>
        <div class="data"><?php echo $row->mtitle ? $row->mtitle : "N/A" ;?> <small>(<?php echo (strtotime($row->membership_expire) === false) ? "-/-" : Utility::dodate("long_date", $row->membership_expire);?>)</small></div>
      </div>
      <div class="item">
        <div class="intro"># <?php echo Lang::$word->LISTINGS;?>:</div>
        <div class="data"><a href="<?php echo Url::adminUrl("members", "cars", false,"?id=" . $row->id);?>"><i class="icon car"></i> <?php echo $row->listings;?></a></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->STATUS;?>:</div>
        <div class="data"><?php echo Utility::status($row->active, $row->id);?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->CREATED;?>:</div>
        <div class="data"><?php echo Utility::dodate("long_date", $row->created);?></div>
      </div>
      <div class="actions">
        <div class="item">
          <div class="intro"><?php echo Lang::$word->ACTIONS;?>:</div>
          <div class="data"> <a href="<?php echo Url::adminUrl("members", "activity", false,"?id=" . $row->id);?>"><i class="rounded inverted primary icon user profile link"></i></a>
            <?php if(Auth::hasPrivileges('edit_members')):?>
            <a href="<?php echo Url::adminUrl("members", "edit", false,"?id=" . $row->id);?>"><i class="rounded positive inverted icon pencil link"></i></a>
            <?php endif;?>
            <?php if(Auth::hasPrivileges('delete_members')):?>
            <a class="delete" data-set='{"title": "<?php echo Lang::$word->M_DEL_MEMBER;?>", "parent": ".row", "option": "deleteMember", "id": <?php echo $row->id;?>, "name": "<?php echo $row->fullname;?>"}'><i class="rounded inverted negative icon trash alt link"></i></a>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<div class="wojo tabular segment">
  <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
  <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
</div>
<?php break;?>
<?php default: ?>
<?php $data = $user->getAllMembers(false, "");?>
<div class="wojo secondary icon message"> <i class="users icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->CL_TITLE;?></div>
    <p><?php echo Lang::$word->CL_INFO2;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->M_FILTER;?></div>
  <div class="content">
    <div class="wojo form">
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->CURPAGE;?></label>
          <?php echo $pager->jump_menu();?></div>
        <div class="field">
          <label><?php echo Lang::$word->IPP;?></label>
          <?php echo $pager->items_per_page();?></div>
        <div class="field">
          <label><?php echo Lang::$word->LAYOUT;?></label>
          <div class="two fields fitted">
            <div class="field"> <a href="<?php echo Url::adminUrl("members", "view");?>" class="wojo labeled fluid secondary icon button"> <i class="grid icon"></i> <?php echo Lang::$word->GRID;?> </a> </div>
            <div class="field">
              <div class="wojo right labeled icon fluid button disabled"> <i class="reorder icon"></i> <?php echo Lang::$word->LIST;?> </div>
            </div>
          </div>
        </div>
      </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("members");?>" name="wojo_form">
        <div class="three fields">
          <div class="field">
            <div class="wojo input"> <i class="icon-prepend icon calendar"></i>
              <input name="fromdate" type="text" id="fromdate" placeholder="<?php echo Lang::$word->FROM;?>" readonly data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
            </div>
          </div>
          <div class="field">
            <div class="wojo action input"> <i class="icon-prepend icon calendar"></i>
              <input name="enddate" type="text" id="enddate" placeholder="<?php echo Lang::$word->TO;?>" readonly data-date-autoclose="true" data-min-view="2" data-start-view="2" data-date-today-btn="true" data-link-field="true" data-date-format="dd, MM yyyy" data-link-format="yyyy-mm-dd">
              <a id="doDates" class="wojo primary icon button"><?php echo Lang::$word->FIND;?></a> </div>
          </div>
          <div class="field">
            <div class="wojo icon input">
              <input type="text" name="membersearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBits(Url::adminUrl("members"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->CL_TITLE;?></span>
    <?php if(Auth::hasPrivileges('add_members')):?>
    <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->CL_ADD;?>" href="<?php echo Url::adminUrl("members", "add");?>"><i class="icon plus"></i></a>
    <?php endif;?>
  </div>
  <table class="wojo sortable table">
    <thead>
      <tr>
        <th class="disabled">#</th>
        <th data-sort="string"><?php echo Lang::$word->USERNAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->EMAIL;?></th>
        <th data-sort="string"><?php echo Lang::$word->NAME;?></th>
        <th data-sort="string">#<?php echo Lang::$word->LISTINGS;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="7"><?php echo Message::msgSingleAlert(Lang::$word->CL_NO_CLIENTS);?></td>
      </tr>
      <?php else:?>
      <?php foreach ($data as $row):?>
      <tr>
        <td><small><?php echo $row->id;?>.</small></td>
        <td><?php echo $row->username;?></td>
        <td><a data-content="<?php echo Lang::$word->M_SENDMAIL;?>" href="<?php echo Url::adminUrl("mailer", false,"?mailid=" . urlencode($row->email) . "&amp;clients=true");?>"><?php echo $row->email;?></a></td>
        <td><?php echo $row->fullname;?></td>
        <td><a href="<?php echo Url::adminUrl("members", "cars", false,"?id=" . $row->id);?>"><i class="icon car"></i> <?php echo $row->listings;?></a></td>
        <td><?php if(Auth::hasPrivileges('manage_upay')):?>
          <a href="<?php echo Url::adminUrl("members", "payments", false,"?id=" . $row->id);?>" data-content="<?php echo Lang::$word->TRX_PAYHIS;?>"><i class="rounded outline <?php echo $row->listings ? "positive" : "negative";?> icon money bag link"></i></a>
          <?php endif;?>
          <a href="<?php echo Url::adminUrl("members", "activity", false,"?id=" . $row->id);?>"><i class="rounded outline primary icon user profile link"></i></a>
          <?php if(Auth::hasPrivileges('edit_members')):?>
          <a href="<?php echo Url::adminUrl("members", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a>
          <?php endif;?>
          <?php if(Auth::hasPrivileges('delete_members')):?>
          <a class="delete" data-set='{"title": "<?php echo Lang::$word->M_DEL_MEMBER;?>", "parent": "tr", "option": "deleteMember", "id": <?php echo $row->id;?>, "name": "<?php echo $row->fullname;?>"}'><i class="rounded outline icon negative trash link"></i></a>
          <?php endif;?></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
  <div class="footer">
    <div class="wojo tabular segment">
      <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
      <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
    </div>
  </div>
</div>
<?php break;?>
<?php endswitch;?>