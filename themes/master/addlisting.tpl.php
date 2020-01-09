<?php
  /**
   * Add Listing
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: addlisting.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if (!$auth->is_User())
      Url::redirect(Url::doUrl(URL_LOGIN));
?>
<?php $featuredata = $content->getFeatures();?>
<?php $mrow = $user->getUserPackage();?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->HOME_SUB12;?>
        <p class="subheader"><?php echo Lang::$word->HOME_SUB12P;?></p>
      </div>
    </div>
    <div id="userMenu">
      <div class="wojo labeled right icon fluid dropdown button"> <i class="angle down icon"></i> <span class="text"><?php echo Lang::$word->LST_ADD;?></span>
        <div class="menu"> 
        <a href="<?php echo Url::doUrl(URL_ACCOUNT);?>" class="item"><i class="icon note"></i><?php echo Lang::$word->PACKAGES;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYLISTINGS);?>" class="item"><i class="icon car"></i><?php echo Lang::$word->LISTINGS;?></a> 
        <a class="item"><i class="icon plus"></i><?php echo Lang::$word->LST_ADD;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYSETTINGS);?>" class="item"><i class="icon cog"></i><?php echo Lang::$word->SETTINGS;?></a> 
        <a href="<?php echo Url::doUrl(URL_MYREVIEWS);?>" class="item"><i class="icon badge"></i><?php echo Lang::$word->SRW_ADD;?></a> </div>
      </div>
    </div>
  </div>
  <div class="wojo secondary bg">
    <div class="padding wojo tab item" id="listings">
      <div class="wojo basic message"><?php echo Lang::$word->HOME_SUB17P;?> <?php echo str_replace(array("[A]", "[B]", "[C]"), array($mrow->total, $mrow->listings, ($mrow->total - $mrow->listings)), Lang::$word->HOME_SUB18P);?>
        <?php if(!App::get('Core')->autoapprove):?>
        <p class="wojo negative text"><i class="icon info sign"></i> <?php echo Lang::$word->HOME_APPNOTE;?></p>
        <?php endif;?>
      </div>
      <?php if(!$mrow->membership_id or $mrow->listings >= $mrow->total):?>
      <?php echo Message::msgSingleError(Lang::$word->HOME_MEMEXP);?>
      <?php else:?>
      <div class="wojo form">
        <p class="content-right"><a href="<?php echo Url::doUrl(URL_MYLOCATIONS);?>" class="wojo bold text">+ Manage Locations</a></p>
        <form method="post" id="wojo_form" name="wojo_form">
          <div class="four fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_ROOM;?></label>
              <select name="location">
                <option value="">-- <?php echo Lang::$word->LST_ROOM_S;?> --</option>
                <?php echo Utility::loopOptions($content->getUserLocations(), "id", "name");?>
              </select>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_YEAR;?></label>
              <div class="wojo labeled icon input">
                <input type="text" placeholder="<?php echo Lang::$word->LST_YEAR;?>" name="year" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_STOCK;?></label>
              <input type="text" placeholder="<?php echo Lang::$word->LST_STOCK;?>" name="stock_id">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_VIN;?><i class="icon pin" data-content="<?php echo Lang::$word->LST_VIN_T;?>"></i></label>
              <?php if($core->vinapi):?>
              <div class="wojo action fluid input">
                <input type="text" placeholder="<?php echo Lang::$word->LST_VIN;?>" name="vin">
                <a id="doVin" class="wojo icon button"><i class="icon find"></i></a> </div>
              <?php else:?>
              <input type="text" placeholder="<?php echo Lang::$word->LST_VIN;?>" name="vin">
              <?php endif;?>
            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_MAKE;?></label>
              <select name="umake_id">
                <option value="">-- <?php echo Lang::$word->LST_MAKE_S;?> --</option>
                <?php echo Utility::loopOptions($content->getMakes(false), "id", "name");?>
              </select>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_MODEL;?></label>
              <select name="model_id">
                <option value="">-- <?php echo Lang::$word->LST_MODEL_S;?> --</option>
              </select>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_CAT;?></label>
              <select name="category">
                <option value="">-- <?php echo Lang::$word->LST_CAT_S;?> --</option>
                <?php echo Utility::loopOptions($content->getCategories(), "id", "name");?>
              </select>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_COND;?></label>
              <select name="vcondition">
                <option value="">-- <?php echo Lang::$word->LST_COND_S;?> --</option>
                <?php echo Utility::loopOptions(Utility::unserialToArray($core->cond_list_alt), "id", "name");?>
              </select>
            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_ODM;?></label>
              <label class="input"> <b class="icon-append"><?php echo $core->odometer;?></b>
                <input type="text" placeholder="<?php echo Lang::$word->LST_ODM;?>" name="mileage">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_TORQUE;?></label>
              <input type="text" placeholder="<?php echo Lang::$word->LST_TORQUE;?>" name="torque">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_INTC;?></label>
              <select name="color_i">
                <option value="">-- <?php echo Lang::$word->LST_SELCLR;?> --</option>
                <?php echo Utility::loopOptionsSimple(Content::colorList());?>
              </select>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_EXTC;?></label>
              <select name="color_e">
                <option value="">-- <?php echo Lang::$word->LST_SELCLR;?> --</option>
                <?php echo Utility::loopOptionsSimple(Content::colorList());?>
              </select>
            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_ENGINE;?></label>
              <input type="text" placeholder="<?php echo Lang::$word->LST_ENGINE;?>" name="engine">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_TRAIN;?></label>
              <input type="text" placeholder="<?php echo Lang::$word->LST_TRAIN;?>" name="drive_train">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_DOORS;?></label>
              <input type="text" class="singlerange" name="doors" min="1" max="8" step="1" value="4" >
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_TRANS;?></label>
              <select name="transmission">
                <option value="">-- <?php echo Lang::$word->LST_TRANS_S;?> --</option>
                <?php echo Utility::loopOptions(Utility::jSonToArray($core->trans_list), "id", "name");?>
              </select>
            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_SPEED;?></label>
              <label class="input"> <b class="icon-append"><?php echo ($core->odometer == "km") ? 'km/h' : 'mph';?></b>
                <input type="text" placeholder="<?php echo Lang::$word->LST_SPEED;?>" name="top_speed">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_FUEL;?></label>
              <select name="fuel">
                <option value="">-- <?php echo Lang::$word->LST_FUEL_S;?> --</option>
                <?php echo Utility::loopOptions(Utility::unserialToArray($core->fuel_list), "id", "name");?>
              </select>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_POWER;?></label>
              <input type="text" placeholder="<?php echo Lang::$word->LST_POWER;?>" name="horse_power">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_TOWING;?></label>
              <input type="text" placeholder="<?php echo Lang::$word->LST_TOWING;?>" name="towing_capacity">
            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_PRICE;?></label>
              <div class="wojo labeled icon input">
                <input type="text" placeholder="<?php echo Lang::$word->LST_PRICE;?>" name="price" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_DPRICE_S;?></label>
              <label class="input">
                <input type="text" placeholder="<?php echo Lang::$word->LST_DPRICE_S;?>" name="price_sale">
              </label>
            </div>
            <div class="field">
              <?php if($mrow->featured):?>
              <label><?php echo Lang::$word->ACTIONS;?></label>
              <div class="inline-group"> <i class="icon circle check"></i> <?php echo Lang::$word->FEATURED;?> </div>
              <input type="hidden" name="featured" value="1">
              <?php endif;?>
            </div>
            <div class="field"> </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_IMAGE;?></label>
              <label class="input">
                <input type="file" name="thumb" id="thumb" class="filefield">
              </label>
              <div class="quarter-top-space"> </div>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_IMAGEA;?></label>
              <label class="input">
                <input type="file" name="photo" id="photo" data-fields='{"processGalleryImages":1, "id":-<?php echo $auth->uid;?>}' class="multifile" multiple>
              </label>
              <div class="wojo carousel quarter-top-space" data-slick='{"dots": true,"arrows":false,"mobileFirst":true,"lazyLoad": "ondemand","responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 3,"slidesToScroll": 2}},{ "breakpoint": 769, "settings":{"slidesToShow": 2,"slidesToScroll": 2}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}' id="editable"> </div>
            </div>
          </div>
          <div class="wojo double fitted divider"></div>
          <div class="field">
            <label><?php echo Lang::$word->LST_FEATURES;?></label>
            <div class="inline-group">
              <label class="checkbox">
                <input type="checkbox" name="masterCheckbox" data-parent="#features" id="masterCheckbox">
                <i></i><?php echo Lang::$word->LST_SEL_ALL;?></label>
            </div>
          </div>
          <div class="wojo divider"></div>
          <div class="columns gutters" id="features">
            <?php foreach ($featuredata as $frow):?>
            <div class="screen-25 tablet-50 phone-100">
              <label class="checkbox">
                <input name="features[<?php echo $frow->id;?>]" type="checkbox" value="<?php echo $frow->id;?>">
                <i></i><?php echo $frow->name;?> </label>
            </div>
            <?php endforeach;?>
            <?php unset($frow);?>
          </div>
          <div class="wojo double fitted divider"></div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_NAME;?><i class="icon pin" data-content="<?php echo Lang::$word->LST_NAME_T;?>"></i></label>
              <input type="text" placeholder="<?php echo Lang::$word->LST_NAME;?>" name="title">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_SLUG;?><i class="icon pin" data-content="<?php echo Lang::$word->LST_SLUG_T;?>"></i></label>
              <input type="text" placeholder="<?php echo Lang::$word->LST_SLUG;?>" name="slug">
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_DESC;?></label>
            <textarea name="body" placeholder="<?php echo Lang::$word->LST_DESC;?>" class="bodypost"></textarea>
          </div>
          <div class="wojo double fitted divider"></div>
          <div class="wojo footer">
            <button type="button" data-action="processListing" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->LST_ADD;?></button>
            <a href="<?php echo Url::doUrl(URL_ACCOUNT);?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
        </form>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php if($mrow->membership_id):?>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
    <?php if($core->vinapi):?>
    $('body').on('click', '#doVin', function() {
		$parent = $(this).closest(".wojo.form");
        $.extend($.expr[":"], {
            "containsIN": function(elem, i, match, array) {
                return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });
        var vin = $("input[name=vin]").val()
        if (vin.length != 0) {
			$parent.addClass('loading');
            $.getJSON("https://api.edmunds.com/api/vehicle/v2/vins/" + vin + "?fmt=json&api_key=<?php echo $core->vinapi;?>")
			.done(function(json) {
				$('select[name=umake_id] option:containsIN("' + json.make.name + '")').prop("selected", "selected")
				var make = $('select[name=umake_id]').selecter("update").val();
				updateMakeModel(make, json.model.name, true);
				$('input[name=year]').val(json.years[0].year);
				$('select[name=category] option:containsIN("' + json.categories.vehicleStyle + '")').prop("selected", "selected");
				$('select[name=category]').selecter("update");
				$('input[name=torque]').val(json.engine.rpm.torque);
				$('input[name=horse_power]').val(json.engine.horsepower);
				$('input[name=drive_train]').val(json.drivenWheels);
				$('input[name=engine]').val(json.engine.size + 'L ' + json.engine.size);
				//$('select[name=transmission] option:containsIN("' + json.transmission.transmissionType + '")').prop("selected", "selected");
				//$('select[name=transmission]').selecter("update");
				$('select[name=vcondition] option:containsIN("' + json.engine.availability + '")').prop("selected", "selected");
				$('select[name=vcondition]').selecter("update");
				$parent.removeClass('loading');
			})
			.fail(function(jqxhr, textStatus, error) {
				$.sticky(decodeURIComponent(error), {
					type: "error",
					title: "Request Failed"
				});
				$parent.removeClass('loading');
			});
        }
    });
    <?php endif;?>

    function updateMakeModel(make, model, isvin = false) {
        $.ajax({
            url: SITEURL + "/ajax/controller.php",
            data: {
                getMakelistFull: make
            },
            dataType: "json",
            success: function(json) {
                if (isvin) {
                    $('select[name=model_id]').html(json.message).selecter("update");
                    $('select[name=model_id] option').filter(function() {
                        return $(this).html() == model
                    }).prop("selected", "selected");
                    $('select[name=model_id]').selecter("update");
                    $('.selecter-options').scroller("destroy").scroller();
                } else {
                    $('select[name=model_id]').html(json.message).selecter("update");
                    $('.selecter-options').scroller("destroy").scroller();
                }
            }
        });
    }
    $('select[name=umake_id]').on('change', function() {
        updateMakeModel($(this).val(), 0, false);
    });

    $('.multifile').simpleUpload({
        url: SITEURL + "/ajax/user.php",
        types: ['jpg', 'png', 'JPG', 'PNG'],
        error: function(error) {
            if (error.type == 'fileType') {
                new Messi(config.lang.invImage, {
                    title: 'Error',
                    modal: true
                });
            };
        },
        change: function(files) {},
        success: function(data) {
            var index = 0;
            $('.wojo.carousel').slick('slickAdd', data);
            $('#editable').editableTableWidget();
        }
    });

    $('body').on('click', '.delslide', function() {
        var index = $(this).closest('.slick-slide').data('slick-index');
        var dataset = $(this).data("set");
        $('.wojo.carousel').slick('slickRemove', index, false);
        $.post(SITEURL + "/ajax/controller.php", {
            delete: "deleteSlide",
            id: dataset.id,
            option: dataset.option,
            path: dataset.path
        });
    });
});
// ]]>
</script>
<?php endif;?>