<?php
  /**
   * Listing Manager
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: items.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!Auth::hasPrivileges('edit_items')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php if(!$row = $items->getListingById()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $gallerydata = Items::getGalleryImages();?>
<?php $featuredata = $content->getFeatures();?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"><?php echo Lang::$word->LST_SUB1;?> <small> / <?php echo $row->title;?></small> </div>
      <p><?php echo Lang::$word->LST_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
    <ul class="wojo tabs fixed clearfix">
      <li><a data-tab="#general"><?php echo Lang::$word->LST_MGEN;?></a></li>
      <li><a data-tab="#feat"><?php echo Lang::$word->LST_MFET;?></a></li>
      <li><a data-tab="#desc"><?php echo Lang::$word->LST_MDSC;?></a></li>
    </ul>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div id="general" class="wojo tab item">
      <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_ROOM;?></label>
          <select name="location">
            <option value="">-- <?php echo Lang::$word->LST_ROOM_S;?> --</option>
            <?php echo Utility::loopOptions($content->getLocations(), "id", "name", $row->location);?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_YEAR;?></label>
          <div class="wojo labeled icon input">
            <input type="text" value="<?php echo $row->year;?>" name="year" required>
            <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_STOCK;?></label>
          <input type="text" value="<?php echo $row->stock_id;?>" name="stock_id">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_VIN;?></label>
          <input type="text" value="<?php echo $row->vin;?>" name="vin">
        </div>
      </div>
      <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_MAKE;?></label>
          <select name="make_id">
            <option value="">-- <?php echo Lang::$word->LST_MAKE_S;?> --</option>
            <?php echo Utility::loopOptions($content->getMakes(false), "id", "name", $row->make_id);?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_MODEL;?></label>
          <select name="model_id">
            <option value="">-- <?php echo Lang::$word->LST_MODEL_S;?> --</option>
            <?php echo Utility::loopOptions($content->getModelList($row->make_id), "id", "name", $row->model_id);?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_CAT;?></label>
          <select name="category">
            <option value="">-- <?php echo Lang::$word->LST_CAT_S;?> --</option>
            <?php echo Utility::loopOptions($content->getCategories(), "id", "name", $row->category);?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_COND;?></label>
          <select name="vcondition">
            <option value="">-- <?php echo Lang::$word->LST_COND_S;?> --</option>
            <?php echo Utility::loopOptions($content->getConditions(), "id", "name", $row->vcondition);?>
          </select>
        </div>
      </div>
      <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_ODM;?></label>
          <label class="input"> <b class="icon-append"><?php echo $core->odometer;?></b>
            <input type="text" value="<?php echo $row->mileage;?>" name="mileage">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_TORQUE;?></label>
          <input type="text" value="<?php echo $row->torque;?>" name="torque">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_INTC;?></label>
          <select name="color_i">
            <option value="">-- <?php echo Lang::$word->LST_SELCLR;?> --</option>
            <?php echo Utility::loopOptionsSimple(Content::colorList(), $row->color_i);?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_EXTC;?></label>
          <select name="color_e">
            <option value="">-- <?php echo Lang::$word->LST_SELCLR;?> --</option>
            <?php echo Utility::loopOptionsSimple(Content::colorList(), $row->color_e);?>
          </select>
        </div>
      </div>
      <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_ENGINE;?></label>
          <input type="text" value="<?php echo $row->engine;?>" name="engine">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_TRAIN;?></label>
          <input type="text" value="<?php echo $row->drive_train;?>" name="drive_train">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_DOORS;?></label>
          <input class="wojo range slider" type="range" min="1" max="6" step="1" name="doors" value="<?php echo $row->doors;?>">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_TRANS;?></label>
          <select name="transmission">
            <option value="">-- <?php echo Lang::$word->LST_TRANS_S;?> --</option>
            <?php echo Utility::loopOptions($content->getTransmissions(), "id", "name", $row->transmission);?>
          </select>
        </div>
      </div>
      <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_SPEED;?></label>
          <label class="input"> <b class="icon-append"><?php echo ($core->odometer == "km") ? 'km/h' : 'mph';?></b>
            <input type="text" value="<?php echo $row->top_speed;?>" name="top_speed">
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_FUEL;?></label>
          <select name="fuel">
            <option value="">-- <?php echo Lang::$word->LST_FUEL_S;?> --</option>
            <?php echo Utility::loopOptions($content->getFuel(), "id", "name", $row->fuel);?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_POWER;?></label>
          <input type="text" value="<?php echo $row->horse_power;?>" name="horse_power">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_TOWING;?></label>
          <input type="text"  value="<?php echo $row->towing_capacity;?>" name="towing_capacity">
        </div>
      </div>
      <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_PRICE;?></label>
          <div class="wojo labeled icon input">
            <input type="text" value="<?php echo $row->price;?>" name="price" required>
            <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_DPRICE_S;?></label>
          <label class="input">
            <input type="text" value="<?php echo $row->price_sale;?>" name="price_sale">
          </label>
        </div>
        <div class="field disabled">
          <label><?php echo Lang::$word->CREATED;?></label>
          <label class="input">
            <input name="last_active" type="text" disabled value="<?php echo Utility::dodate("long_date", $row->created);?>" readonly>
          </label>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->EXPIRE;?><i class="icon pin" data-content="<?php echo Lang::$word->LST_EXPIRE_T;?>"></i></label>
          <label class="input"><i class="icon-prepend icon calendar"></i>
            <input data-datepicker="true" data-date="<?php echo $row->expire;?>" type="text" value="<?php echo Utility::dodate("short_date", $row->expire);?>" name="expire">
          </label>
        </div>
      </div>
      <div class="two fields">
          <div class="field">
          <label><?php echo Lang::$word->LST_IMAGE;?></label>
          <label class="input">
            <input type="file" name="thumb" id="thumb" class="filefield">
          </label>
          <div class="quarter-top-space"><a data-lightbox="true" data-title="<?php echo $row->title;?>" href="<?php echo UPLOADURL . 'listings/' . $row->thumb;?>"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt="" class="wojo grid image"></a> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_IMAGEA;?></label>
          <label class="input">
            <input type="file" name="photo" id="photo" data-fields='{"processGalleryImages":1, "id":<?php echo $row->id;?>}' class="multifile" multiple>
          </label>
          <div class="wojo carousel quarter-top-space" data-slick='{"dots": true,"arrows":false,"mobileFirst":true,"lazyLoad": "ondemand","responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 3,"slidesToScroll": 2}},{ "breakpoint": 769, "settings":{"slidesToShow": 2,"slidesToScroll": 2}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}' id="editable">
            <?php if($gallerydata):?>
            <?php foreach ($gallerydata as $grow):?>
            <div class="wojo reveal"><img data-lazy="<?php echo UPLOADURL . 'listings/pics' . $row->id . '/thumbs/' . $grow->photo;?>" alt="">
              <div class="overlay"></div>
              <div class="corner-overlay-content"><i class="icon long arrow right"></i></div>
              <div class="overlay-content">
                <p data-editable="true" data-set='{"type": "gallery", "id": <?php echo $grow->id;?>,"key":"name", "path":""}'><?php echo $grow->title;?></p>
                <a class="delslide wojo top right large corner label" data-set='{"title": "<?php echo Lang::$word->GAL_DELETE;?>", "parent": ".reveal", "option": <?php echo $grow->listing_id;?>, "id": <?php echo $grow->id;?>, "name": "<?php echo $grow->title;?>", "path":"<?php echo $grow->photo;?>"}'><i class="icon negative delete link"></i></a> </div>
            </div>
            <?php endforeach;?>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
    <div id="feat" class="wojo tab item">
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
        <?php $key = explode(",", $row->features);?>
        <?php $checked = (in_array($frow->id, $key) ? ' checked="checked"' : '');?>
        <div class="screen-25 tablet-50 phone-100">
          <label class="checkbox">
            <input name="features[<?php echo $frow->id;?>]" type="checkbox" value="<?php echo $frow->id;?>" <?php echo $checked;?>/>
            <i></i><?php echo $frow->name;?> </label>
        </div>
        <?php endforeach;?>
        <?php unset($frow);?>
      </div>
    </div>
    <div id="desc" class="wojo tab item">
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_NAME;?><i class="icon pin" data-content="<?php echo Lang::$word->LST_NAME_T;?>"></i></label>
          <input type="text" value="<?php echo $row->title;?>" name="title">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_SLUG;?><i class="icon pin" data-content="<?php echo Lang::$word->LST_SLUG_T;?>"></i></label>
          <input type="text" value="<?php echo $row->slug;?>" name="slug">
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->LST_DESC;?></label>
        <textarea name="body" class="bodypost"><?php echo $row->body;?></textarea>
      </div>
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_METAKEY;?></label>
          <textarea name="metakey"><?php echo $row->metakey;?></textarea>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_METADESC;?></label>
          <textarea name="metadesc"><?php echo $row->metadesc;?></textarea>
        </div>
      </div>
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_NOTES;?></label>
          <textarea name="notes"><?php echo $row->notes;?></textarea>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->ACTIONS;?></label>
          <div class="inline-group">
            <label class="checkbox">
              <input type="checkbox" value="1" name="featured" <?php Validator::getChecked($row->featured, 1); ?>>
              <i></i><?php echo Lang::$word->FEATURED;?> </label>
          </div>
          <div class="inline-group">
            <label class="checkbox">
              <input type="checkbox" value="1" name="status" <?php Validator::getChecked($row->status, 1); ?>>
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          <div class="inline-group">
            <label class="checkbox">
              <input type="checkbox" value="1" name="sold" <?php Validator::getChecked($row->sold, 1); ?>>
              <i></i><?php echo Lang::$word->SOLD;?> </label>
          </div>
        </div>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processListing" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->LST_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("items");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
    <input name="is_owner" type="hidden" value="<?php echo $row->is_owner;?>">
    <input name="user_id" type="hidden" value="<?php echo $row->user_id;?>">
  </form>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
	$('select[name=make_id]').on('change', function () {
		var option = $(this).val();
		$.ajax({
		  url: ADMINURL + "/helper.php",
		  data: {getMakelist: option},
		  dataType: "json",
		  success: function (json) {
			  $('select[name=model_id]').html(json.message).selecter("update");
			  $('.selecter-options').scroller("destroy").scroller();
		  }
		});
    });
    $('.multifile').simpleUpload({
        url: ADMINURL + "/helper.php",
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
            var index = $('.wojo.carousel').slick('slickCurrentSlide');
            $('.wojo.carousel').slick('slickAdd', data, index);
			$('#editable').editableTableWidget();
        }
    });
	
	$('body').on('click', '.delslide', function () {
		var index = $(this).closest('.slick-slide').data('slick-index');
		var dataset = $(this).data("set");
		$('.wojo.carousel').slick('slickRemove', index, false);
		$.post(ADMINURL + "/controller.php", {
			delete: "deleteSlide",
			id: dataset.id,
			option: dataset.option,
			path: dataset.path
		});
	});
});
// ]]>
</script>
<?php break;?>
<?php case"add": ?>
<?php if(!Auth::hasPrivileges('add_items')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php $featuredata = $content->getFeatures();?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="check icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->LST_SUB2;?></div>
      <p><?php echo Lang::$word->LST_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
    <ul class="wojo tabs fixed clearfix">
      <li><a data-tab="#general"><?php echo Lang::$word->LST_MGEN;?></a></li>
      <li><a data-tab="#feat"><?php echo Lang::$word->LST_MFET;?></a></li>
      <li><a data-tab="#desc"><?php echo Lang::$word->LST_MDSC;?></a></li>
    </ul>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div id="general" class="wojo tab item">
      <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_ROOM;?></label>
          <select name="location">
            <option value="">-- <?php echo Lang::$word->LST_ROOM_S;?> --</option>
            <?php echo Utility::loopOptions($content->getLocations(), "id", "name");?>
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
          <div class="wojo action input">
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
          <select name="make_id">
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
            <?php echo Utility::loopOptions($content->getConditions(), "id", "name");?>
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
          <input class="wojo range slider" type="range" min="1" max="6" step="1" value="4" name="doors">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_TRANS;?></label>
          <select name="transmission">
            <option value="">-- <?php echo Lang::$word->LST_TRANS_S;?> --</option>
            <?php echo Utility::loopOptions($content->getTransmissions(), "id", "name");?>
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
            <?php echo Utility::loopOptions($content->getFuel(), "id", "name");?>
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
          <label><?php echo Lang::$word->LST_USER;?><i class="icon pin" data-content="<?php echo Lang::$word->LST_USER_T;?>"></i></label>
          <select name="user_id">
            <option value="">-- <?php echo Lang::$word->LST_USER_S;?> --</option>
            <?php echo Utility::loopOptions($user->getAllDealers(), "id", "username");?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->EXPIRE;?><i class="icon pin" data-content="<?php echo Lang::$word->LST_EXPIRE_T;?>"></i></label>
          <label class="input"><i class="icon-prepend icon calendar"></i>
            <input data-datepicker="true" type="text" name="expire">
          </label>
        </div>
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
            <input type="file" name="photo" id="photo" data-fields='{"processGalleryImages":1, "id":-100}' class="multifile" multiple>
          </label>
          <div class="wojo carousel quarter-top-space" data-slick='{"dots": true,"arrows":false,"mobileFirst":true,"lazyLoad": "ondemand","responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 3,"slidesToScroll": 2}},{ "breakpoint": 769, "settings":{"slidesToShow": 2,"slidesToScroll": 2}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}' id="editable"> </div>
        </div>
      </div>
    </div>
    <div id="feat" class="wojo tab item">
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
    </div>
    <div id="desc" class="wojo tab item">
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
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_METAKEY;?></label>
          <textarea placeholder="<?php echo Lang::$word->LST_METAKEY;?>" name="metakey"></textarea>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->LST_METADESC;?></label>
          <textarea placeholder="<?php echo Lang::$word->LST_METADESC;?>" name="metadesc"></textarea>
        </div>
      </div>
      <div class="two fields">
        <div class="field">
          <label><?php echo Lang::$word->LST_NOTES;?></label>
          <textarea placeholder="<?php echo Lang::$word->LST_NOTES;?>" name="notes"></textarea>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->ACTIONS;?></label>
          <div class="inline-group">
            <label class="checkbox">
              <input type="checkbox" value="1" name="featured">
              <i></i><?php echo Lang::$word->FEATURED;?> </label>
          </div>
          <div class="inline-group">
            <label class="checkbox">
              <input type="checkbox" value="1" name="status" checked="checked">
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          <div class="inline-group">
            <label class="checkbox">
              <input type="checkbox" value="1" name="sold">
              <i></i><?php echo Lang::$word->SOLD;?> </label>
          </div>
        </div>
      </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processListing" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->LST_ADD;?></button>
      <a href="<?php echo Url::adminUrl("items");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <input name="is_owner" type="hidden" value="1">
  </form>
</div>
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
				$('select[name=make_id] option:containsIN("' + json.make.name + '")').prop("selected", "selected")
				var make = $('select[name=make_id]').selecter("update").val();
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
			})
			.fail(function(jqxhr, textStatus, error) {
				$.sticky(decodeURIComponent(jqxhr.responseJSON.message), {
					type: "error",
					title: "Request Failed"
				});
			});
			$parent.removeClass('loading');
        }
    });
    <?php endif;?>

    function updateMakeModel(make, model, isvin) {
        $.ajax({
            url: ADMINURL + "/helper.php",
            data: {
                getMakelist: make
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
    $('select[name=make_id]').on('change', function() {
        updateMakeModel($(this).val(), 0, false);
    });

    $('.multifile').simpleUpload({
        url: ADMINURL + "/helper.php",
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
        $.post(ADMINURL + "/controller.php", {
            delete: "deleteSlide",
            id: dataset.id,
            option: dataset.option,
            path: dataset.path
        });
    });
});
// ]]>
</script>
<?php break;?>
<?php case"images": ?>
<?php if(!Auth::hasPrivileges('manage_gallery')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php if(!$item = $db->first(Items::lTable, null, array('id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $data = Items::getGalleryImages();?>
<div class="wojo secondary icon message"> <i class="photo icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->GAL_SUB;?> <small> / <?php echo $item->title;?></small> </div>
    <p><?php echo str_replace("[ICON]", "<i class=\"icon middle reorder\"></i>", Lang::$word->GAL_INFO);?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->GAL_TITLE;?></span> <a onclick="$('#uploader').slideToggle();" class="wojo large top right detached action label" data-content="<?php echo Lang::$word->GAL_UPLOAD;?>"><i class="icon plus"></i></a> </div>
  <div id="uploader" class="wojo segment" style="display:none">
    <form action="#">
      <div class="upload" data-upload-options='{"action":"<?php echo ADMINURL;?>/helper.php", "postData":{"uploadGimages":1,"id": <?php echo $item->id;?>}}'></div>
      <h4 class="wojo header"> <?php echo Lang::$word->FM_FILE_C;?></h4>
      <div class="wojo divided list complete"></div>
      <h4 class="wojo header"> <?php echo Lang::$word->FM_FILE_Q;?></h4>
      <div class="wojo divided list queue"></div>
    </form>
  </div>
  <table class="wojo sortable table" id="editable">
    <thead>
      <tr>
        <th class="disabled"><i class="icon arrows vertical"></i></th>
        <th class="disabled"></th>
        <th class="disabled"><?php echo Lang::$word->GAL_CAP;?></th>
        <th class="disabled"><?php echo Lang::$word->POSITION;?></th>
        <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$data):?>
      <tr>
        <td colspan="4"><?php echo Message::msgSingleAlert(Lang::$word->GAL_NOGAL);?></td>
      </tr>
      <?php else:?>
      <?php foreach($data as $row):?>
      <tr data-id="<?php echo $row->id;?>">
        <td class="sorter"><i class="icon reorder"></i></td>
        <td><a href="<?php echo UPLOADURL . 'listings/pics' . $item->id . '/' . $row->photo;?>" data-title="<?php echo $row->title;?>" data-lightbox-gallery="true" data-lightbox="true"><img src="<?php echo UPLOADURL . 'listings/pics' . $item->id . '/thumbs/' . $row->photo;?>" alt="" class="wojo grid small image"></a></td>
        <td data-editable="true" data-set='{"type": "gallery", "id": <?php echo $row->id;?>,"key":"name", "path":""}'><?php echo $row->title;?></td>
        <td><small class="wojo label"><?php echo $row->sorting;?></small></td>
        <td><a class="delete" data-set='{"title": "<?php echo Lang::$word->GAL_DELETE;?>", "parent": "tr", "option": "deleteGalleryImage", "extra": "<?php echo $row->photo;?>", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
      </tr>
      <?php endforeach;?>
      <?php unset($row);?>
      <?php endif;?>
    </tbody>
  </table>
</div>
<script type="text/javascript">
// <![CDATA[
var $filequeue,
    $filelist;

function onStart(e, files) {
    var html = '';
    for (var i = 0; i < files.length; i++) {
        html += '<div class="item" data-index="' + files[i].index + '"><span class="right floated"><?php echo Lang::$word->FM_FILE_Q;?></span><div class="content">' + files[i].name + '</div><div class="wojo progress item_'+ i + '" data-percent="0" data-progress="false"><div class="meter"></div></div></div>';
    }
    $filequeue.append(html);
}

function onComplete(e) {}
function onFileStart(e, file) {
    $filequeue.find(".item[data-index=" + file.index + "]").find("span").text("0%");
}
function onFileProgress(e, file, percent) {
    $filequeue.find(".item[data-index=" + file.index + "]").find("span").text(percent + "%");
	$(".wojo.progress.item_"+ file.index).simpleprogress("update", percent);
}
function onFileComplete(e, file, json) {
    if (json.type == "success") {
        var $target = $filequeue.find(".item[data-index=" + file.index + "]");
        $target.find(".content").text(file.name);
		$target.find("span").addClass("wojo text positive");
        $target.appendTo($filelist);
		$("#editable tbody").prepend(json.html).fadeIn();
		$('#editable').editableTableWidget();
    } else {
       $filequeue.find(".item[data-index=" + file.index + "] span").addClass("wojo text negative").text(json.message);
    }
}
function onFileError(e, file, error) {
	$filequeue.find(".item[data-index=" + file.index + "] span").addClass("wojo text negative").text(error);
}
$(document).ready(function() {
    $filequeue = $(".wojo.list.queue");
    $filelist = $(".wojo.list.complete");
    $(".upload").upload({
            maxSize: 6291456,
			label: "<?php echo Lang::$word->FM_DROP;?>",
        }).on("start.upload", onStart)
        .on("complete.upload", onComplete)
        .on("filestart.upload", onFileStart)
        .on("fileprogress.upload", onFileProgress)
        .on("filecomplete.upload", onFileComplete)
        .on("fileerror.upload", onFileError);

    $(".wojo.table").rowSorter({
        handler: "td.sorter",
        onDrop: function() {
            var data = [];
            $('.wojo.table tbody tr').each(function() {
                data.push($(this).data("id"))
            });
            $.post(ADMINURL + "/helper.php", {
                sortgal: 1,
				id: <?php echo Filter::$id;?>,
                sorting: data
            });
        }
    });
});
// ]]>
</script>
<?php $thumb = $db->first(Items::lTable, array("thumb"), array('id' => Filter::$id));?>
<?php break;?>
<?php case"print": ?>
<?php if(!$row = $items->getListingPreview()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $featuredata = $content->getFeaturesById($row->features);?>
<?php $locdata = unserialize($row->location_name);?>
<div class="wojo secondary icon message"> <i class="printer icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LPR_SUB;?> <small> / <?php echo $row->name;?></small> </div>
    <p><?php echo Lang::$word->LPR_INFO;?></p>
  </div>
</div>
<div class="clearfix bottom-space"> <a class="wojo right labeled icon secondary button push-right" onclick="javascript:void window.open('<?php echo ADMINURL;?>/print.php?id=<?php echo Filter::$id;?>','printer','width=880,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0'); return false;"><i class="icon printer"></i><?php echo Lang::$word->PRINT;?></a> </div>
<div class="columns double-gutters" id="printArea">
  <div class="screen-50 tablet-100 phone-100">
    <div class="content-center"><img src="<?php echo UPLOADURL . 'listings/' . $row->thumb;?>" alt=""></div>
    <?php if($row->gallery):?>
    <div class="wojo divider"></div>
    <?php $gallerydata = unserialize($row->gallery);?>
    <ul class="wojo block grid small-3 divided">
      <?php foreach ($gallerydata as $grow):?>
      <li>
        <div class="content"><img class="wojo image" src="<?php echo UPLOADURL.'/listings/pics' . Filter::$id . '/thumbs/' . $grow->photo;?>" alt=""></div>
      </li>
      <?php endforeach;?>
    </ul>
    <?php endif;?>
  </div>
  <div class="screen-50 tablet-100 phone-100">
    <table class="wojo grid table">
      <thead>
        <tr>
          <th colspan="2"><?php echo $row->year . ' ' . $row->name;?></th>
        </tr>
      </thead>
      <tr>
        <td><?php echo Lang::$word->LST_STOCK;?></td>
        <td><?php echo Validator::has($row->stock_id);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_VIN;?></td>
        <td><?php echo Validator::has($row->vin);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_COND;?></td>
        <td><?php echo $row->condition_name;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_ODM;?></td>
        <td><?php echo Validator::has($row->mileage);?> <?php echo $core->odometer;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_PRICE;?></td>
        <td><?php echo ($row->price_sale) ? '<span class="wojo strike text">' . Utility::formatMoney($row->price) . '</span>' : Utility::formatMoney($row->price);?></td>
      </tr>
      <?php if($row->price_sale):?>
      <tr>
        <td><?php echo Lang::$word->LST_DPRICE_S;?></td>
        <td><?php echo Validator::has(Utility::formatMoney($row->price_sale));?></td>
      </tr>
      <?php endif;?>
      <tr>
        <td><?php echo Lang::$word->LST_ROOM;?></td>
        <td><?php echo $locdata->name;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->EMAIL;?></td>
        <td><?php echo $locdata->email;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->CF_PHONE;?></td>
        <td><?php echo Validator::has($locdata->phone);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_INTC;?></td>
        <td><?php echo Validator::has($row->color_i);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_EXTC;?></td>
        <td><?php echo Validator::has($row->color_e);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_DOORS;?></td>
        <td><?php echo Validator::has($row->doors);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_ENGINE;?></td>
        <td><?php echo Validator::has($row->engine);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_TRANS;?></td>
        <td><?php echo $row->trans_name;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_FUEL;?>:</td>
        <td><?php echo $row->fuel_name;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_TRAIN;?>:</td>
        <td><?php echo Validator::has($row->drive_train);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_SPEED;?>:</td>
        <td><?php echo Validator::has($row->top_speed);?> <?php echo ($core->odometer == "km") ? 'km/h' : 'mph';?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_POWER;?></td>
        <td><?php echo Validator::has($row->horse_power);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_TORQUE;?></td>
        <td><?php echo Validator::has($row->torque);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_TOWING;?></td>
        <td><?php echo Validator::has($row->towing_capacity);?></td>
      <tr>
        <td colspan="2" class="active"><?php echo Validator::cleanOut($row->body);?></td>
      </tr>
      <tr>
        <td colspan="2"><?php if($featuredata):?>
          <div class="columns gutters">
            <?php foreach ($featuredata as $frow):?>
            <div class="all-50"> <?php echo $frow->name;?> </div>
            <?php endforeach;?>
            <?php unset($frow);?>
          </div>
          <?php endif;?></td>
      </tr>
    </table>
  </div>
</div>
<?php break;?>
<?php case"stats": ?>
<?php if(!$row = $db->select(Items::lTable, array("id", "title"), array('id' => Filter::$id), 'LIMIT 1')->result()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo secondary icon message"> <i class="pie chart icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LST_TITLE4;?></div>
    <p><?php echo Lang::$word->LST_INFO4;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->LST_SUB4 . ' / ' . $row->title;?></span> <a id="resetStats" class="wojo large top right detached action label" data-content="<?php echo Lang::$word->LST_DELSTATS;?>"><i class="icon trash"></i></a> </div>
  <div class="content">
    <div id="chart" style="height:400px;"></div>
  </div>
</div>
<script type='text/javascript'>
  function getStats(range) {
      $.ajax({
          type: 'GET',
          url: ADMINURL + "/helper.php?getListingStats=1&id=<?php echo $row->id;?>",
          dataType: 'json'
      }).done(function(json) {
          var option = {
              series: {
                  lines: {
                      show: false
                  },
                  splines: {
                      show: true,
                      tension: 0.5,
                      lineWidth: 2,
                      fill: 0.3
                  },
                  shadowSize: 1
              },
              points: {
                  show: true,
              },
              colors: ["#9ACA40"],
              grid: {
                  hoverable: true,
                  clickable: true,
                  borderColor: "rgba(0,0,0,0.1)",
                  borderWidth: 1,
                  labelMargin: 16,
                  backgroundColor: '#fff'
              },
              yaxis: {
                  color: "rgba(0,0,0,0.1)",
                  font: {
                      color: "#939599"
                  }
              },
              xaxis: {
                  color: "rgba(0,0,0,0.1)",
                  ticks: json.xaxis,
                  font: {
                      color: "#939599"
                  }
              },
              legend: {
                  backgroundColor: "#fff",
                  labelBoxBorderColor: "rgba(0,0,0,0.1)",
                  backgroundOpacity: .75,
                  noColumns: 1,
              }
          }

          plot = $.plot($('#chart'), [json.visits], option);
      });

  }
  getStats('month');

  function showTooltip(x, y, contents) {
      $('<div class="charts_tooltip">' + contents + '</div>').css({
          position: 'absolute',
          display: 'none',
          top: y + 5,
          left: x + 5
      }).appendTo("body").fadeIn(200);
  }
  var previousPoint = null;

  $("#chart").on("plothover", function(event, pos, item) {
      if (item) {
          if (previousPoint != item.dataIndex) {
              previousPoint = item.dataIndex;
              $(".charts_tooltip").fadeOut("fast").promise().done(function() {
                  $(this).remove();
              });
              var x = item.datapoint[0].toFixed(2),
                  y = item.datapoint[1].toFixed(2);
              i = item.series.xaxis.options.ticks[item.dataIndex][1]
              showTooltip(item.pageX, item.pageY, item.series.label + " for " + i + " = " + y);
          }
      } else {
          $(".charts_tooltip").fadeOut("fast").promise().done(function() {
              $(this).remove();
          });
          previousPoint = null;
      }
  });
  $("#resetStats").on("click", function() {
	  $("#chart").addClass('loading');
      $.get(ADMINURL + "/helper.php?resetListingStats=1&id=<?php echo $row->id;?>")
          .done(function() {
              getStats('month');
			  $("#chart").removeClass('loading');
          })
  });  
</script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/flot.resize.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/excanvas.min.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.flot.spline.js"></script>
<?php break;?>
<?php case"view": ?>
<?php $data = $items->getListings(false, "view");?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LST_TITLE;?></div>
    <p><?php echo Lang::$word->LST_INFO;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->FILTER;?></div>
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
            <div class="field"> <a href="<?php echo Url::adminUrl("items", false);?>" class="wojo right labeled icon fluid secondary button"> <i class="reorder icon"></i> <?php echo Lang::$word->LIST;?> </a> </div>
          </div>
        </div>
      </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("items/view");?>" name="wojo_form">
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
              <input type="text" name="listingsearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
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
        <a href="<?php echo Url::adminUrl("items/view");?>" class="item<?php echo Url::setActive("order", false);?>"> <?php echo Lang::$word->DEFAULT;?> </a> <a href="<?php echo Url::adminUrl("items/view", false, false, "?order=title/DESC");?>" class="item<?php echo Url::setActive("order", "title");?>"> <?php echo Lang::$word->LST_NAME;?> </a> <a href="<?php echo Url::adminUrl("items/view", false, false, "?order=username/DESC");?>" class="item<?php echo Url::setActive("order", "username");?>"> <?php echo Lang::$word->USERNAME;?> </a> <a href="<?php echo Url::adminUrl("items/view", false, false, "?order=featured/DESC");?>" class="item<?php echo Url::setActive("order", "featured");?>"> <?php echo Lang::$word->FEATURED;?> </a> <a href="<?php echo Url::adminUrl("items/view", false, false, "?order=price/DESC");?>" class="item<?php echo Url::setActive("order", "price");?>"> <?php echo Lang::$word->PRICE;?> </a> <a href="<?php echo Url::adminUrl("items/view", false, false, "?order=expire/DESC");?>" class="item<?php echo Url::setActive("order", "expire");?>"> <?php echo Lang::$word->EXPIRE;?> </a> <a href="<?php echo Url::adminUrl("items/view", false, false, "?order=hits/DESC");?>" class="item<?php echo Url::setActive("order", "hits");?>"> #<?php echo Lang::$word->VISITS;?> </a>
        <div class="item" data-content="ASC/DESC"><a href="<?php echo Url::sortItems(Url::adminUrl("items/view"), "order");?>"><i class="icon unfold more link"></i></a> </div>
      </div>
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBits(Url::adminUrl("items", "view"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<?php if(Auth::hasPrivileges('add_items')):?>
<div class="clearfix"> <a class="wojo right labeled icon secondary button push-right" href="<?php echo Url::adminUrl("items", "add");?>"><i class="icon plus"></i><?php echo Lang::$word->LST_ADD;?></a> </div>
<?php endif;?>
<?php if(!$data):?>
<?php echo Message::msgSingleAlert(Lang::$word->LST_NOLIST);?>
<?php else:?>
<div class="wojo space divider"></div>
<div class="columns gutters">
  <?php foreach ($data as $row):?>
  <div class="row screen-50 tablet-50 phone-100">
    <div class="wojo divided card">
      <div class="header">
        <div class="wojo top right small attached label"><?php echo $row->id;?>.</div>
        <div class="wojo rounded primary small grid image"><a data-lightbox="true" href="<?php echo UPLOADURL . 'listings/' . $row->thumb;?>"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt=""></a></div>
        <div class="content">
          <?php if(Auth::hasPrivileges('edit_items')):?>
          <a href="<?php echo Url::adminUrl("items", "edit", false,"?id=" . $row->id);?>"><?php echo $row->title;?> </a> (<?php echo $row->year;?>)
          <?php else:?>
          <?php echo $row->title;?> (<?php echo $row->year;?>)
          <?php endif;?>
          <p><?php echo Lang::$word->CREATED;?>: <?php echo Utility::dodate("long_date", $row->created);?></p>
        </div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->USERNAME;?>:</div>
        <div class="data">
          <?php if(Auth::hasPrivileges('edit_members')):?>
          <a href="<?php echo Url::adminUrl("members", "edit", false,"?id=" . $row->user_id);?>"><?php echo $row->username;?></a>
          <?php else:?>
          <?php echo $row->username;?>
          <?php endif;?>
        </div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->LST_PRICE;?>:</div>
        <div class="data"><?php echo Utility::formatMoney($row->price);?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->LST_DPRICE_S;?>:</div>
        <div class="data"><?php echo Utility::formatMoney($row->price_sale);?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->LST_STOCK;?>:</div>
        <div class="data"><?php echo $row->stock_id;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->LST_COND;?>:</div>
        <div class="data"><?php echo $row->cdname;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->MODIFIED;?>:</div>
        <div class="data"><?php echo ($row->modified <> 0) ? Utility::dodate("short_date", $row->modified): '- ' . Lang::$word->NEVER . ' -'?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->EXPIRE;?>:</div>
        <div class="data">
          <?php if(Utility::compareDates($row->expire, date('y-m-d H:i:s'))):?>
          <div class="wojo positive label"><?php echo Lang::$word->EXPIRE;?>:
            <div class="detail"><?php echo Utility::dodate("long_date", $row->expire);?></div>
          </div>
          <?php else:?>
          <div class="wojo negative label"><?php echo Lang::$word->EXPIRED;?>: <span class="detail"><?php echo Utility::dodate("long_date", $row->expire);?></span></div>
          <?php endif;?>
        </div>
      </div>
      <div class="actions">
        <div class="item">
          <div class="intro"><?php echo Lang::$word->ACTIONS;?>:</div>
          <div class="data"> <a class="doStatus" data-set='{"field": "status", "table": "Listing", "toggle": "check ban", "togglealt": "positive purple", "id": <?php echo $row->id;?>, "value": "<?php echo $row->status;?>"}' data-content="<?php echo Lang::$word->STATUS;?>"><i class="rounded inverted <?php echo ($row->status) ? "check positive" : "ban purple";?> icon link"></i></a> <a class="doStatus" data-set='{"field": "sold", "table": "Listing", "toggle": "money bag money bag", "togglealt": "positive negative", "id": <?php echo $row->id;?>, "value": "<?php echo $row->sold;?>"}' data-content="<?php echo Lang::$word->SOLD;?>"><i class="rounded inverted <?php echo ($row->sold) ? "money bag positive" : "money bag negative";?> icon link"></i></a> <a class="doStatus" data-set='{"field": "featured", "table": "Listing", "toggle": "badge ban", "togglealt": "primary negative", "id": <?php echo $row->id;?>, "value": "<?php echo $row->featured;?>"}' data-content="<?php echo Lang::$word->FEATURED;?>"><i class="rounded inverted <?php echo ($row->featured) ? "badge primary" : "ban negative";?> icon link"></i></a> <a href="<?php echo Url::adminUrl("items", "print", false,"?id=" . $row->id);?>"><i class="rounded outline purple icon printer link"></i></a> <a href="<?php echo Url::adminUrl("items", "images", false,"?id=" . $row->id);?>"><i class="rounded outline primary icon photo link"></i></a>
            <?php if(Auth::hasPrivileges('edit_items')):?>
            <a href="<?php echo Url::adminUrl("items", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a>
            <?php endif;?>
            <?php if(Auth::hasPrivileges('delete_items')):?>
            <a class="delete" data-set='{"title": "<?php echo Lang::$word->LST_DELETE;?>", "parent": ".row", "option": "deleteListing", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><i class="rounded outline icon negative trash link"></i></a>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
  <?php unset($row);?>
</div>
<div class="wojo tabular segment">
  <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
  <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
</div>
<?php endif;?>
<?php break;?>
<?php default: ?>
<?php $data = $items->getListings(false, "");?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LST_TITLE;?></div>
    <p><?php echo Lang::$word->LST_INFO;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->FILTER;?></div>
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
            <div class="field"> <a href="<?php echo Url::adminUrl("items", "view");?>" class="wojo labeled fluid secondary icon button"> <i class="grid icon"></i> <?php echo Lang::$word->GRID;?> </a> </div>
            <div class="field">
              <div class="wojo right labeled icon fluid button disabled"> <i class="reorder icon"></i> <?php echo Lang::$word->LIST;?> </div>
            </div>
          </div>
        </div>
      </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("items");?>" name="wojo_form">
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
              <input type="text" name="listingsearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBits(Url::adminUrl("items"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->LST_SUB;?></span>
    <?php if(Auth::hasPrivileges('add_items')):?>
    <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->LST_ADD;?>" href="<?php echo Url::adminUrl("items", "add");?>" ><i class="icon plus"></i></a>
    <?php endif;?>
  </div>
  <form method="post" id="wojo_forml" name="wojo_forml">
    <table class="wojo sortable table">
      <thead>
        <tr>
          <th class="disabled"> <label class="fitted small checkbox">
              <input type="checkbox" name="masterCheckbox" data-parent="#listtable" id="masterCheckbox">
              <i></i></label>
          </th>
          <th class="disabled"><?php echo Lang::$word->PHOTO;?></th>
          <th data-sort="string"><?php echo Lang::$word->DESC;?></th>
          <th data-sort="string"><?php echo Lang::$word->LST_CAT;?></th>
          <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
          <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
        </tr>
      </thead>
      <tbody id="listtable">
        <?php if(!$data):?>
        <tr>
          <td colspan="6"><?php Message::msgSingleAlert(Lang::$word->LST_NOLIST);?></td>
        </tr>
        <?php else:?>
        <?php foreach($data as $row):?>
        <tr>
          <td><label class="fitted small checkbox">
              <input name="listid[<?php echo $row->id;?>]" type="checkbox" value="<?php echo $row->id;?>">
              <i></i></label></td>
          <td><a data-lightbox="true" href="<?php echo UPLOADURL . 'listings/' . $row->thumb;?>"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt="" class="wojo medium grid image"></a></td>
          <td><b><?php echo $row->title;?></b> (<?php echo $row->year;?>) <br />
            <small><?php echo Lang::$word->BY;?>:
            <?php if(Auth::hasPrivileges('edit_members')):?>
            <a href="<?php echo Url::adminUrl("members", "edit", false,"?id=" . $row->user_id);?>"><?php echo $row->username;?></a>
            <?php else:?>
            <?php echo $row->username;?>
            <?php endif;?>
            </small><br />
            #: <b><?php echo $row->stock_id;?></b> <br />
            <?php echo Lang::$word->LST_PRICE;?>: (<?php echo Utility::formatMoney($row->price);?>) <small class="wojo text negative"><?php echo Utility::formatMoney($row->price_sale);?></small><br />
            <?php echo Lang::$word->LST_COND;?>: <b><?php echo $row->cdname;?></b><br />
            <?php echo Lang::$word->MODIFIED;?>: <b><?php echo ($row->modified <> 0) ? Utility::dodate("short_date", $row->modified): '- ' . Lang::$word->NEVER . ' -'?></b><br />
            <?php if(Utility::compareDates($row->expire, date('y-m-d H:i:s'))):?>
            <div class="wojo positive label"><?php echo Lang::$word->EXPIRE;?>:
              <div class="detail"><?php echo Utility::dodate("long_date", $row->expire);?></div>
            </div>
            <?php else:?>
            <div class="wojo negative label"><?php echo Lang::$word->EXPIRED;?>: <span class="detail"><?php echo Utility::dodate("long_date", $row->expire);?></span></div>
            <?php endif;?></td>
          <td><?php echo $row->ctname;?></td>
          <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Utility::dodate("short_date", $row->created);?></td>
          <td><div class="wojo icon top right pointing primary small dropdown button"> <i class="ellipsis vertical icon"></i>
              <div class="menu"> <a class="doStatus item" data-set='{"field": "status", "table": "Listing", "toggle": "check ban", "togglealt": "primary negative", "id": <?php echo $row->id;?>, "value": "<?php echo $row->status;?>"}'><i class="<?php echo ($row->status) ? "check primary" : "ban negative";?> icon link"></i><?php echo Lang::$word->STATUS;?></a> <a class="doStatus item" data-set='{"field": "featured", "table": "Listing", "toggle": "check ban", "togglealt": "primary negative", "id": <?php echo $row->id;?>, "value": "<?php echo $row->featured;?>"}'><i class="<?php echo ($row->featured) ? "check primary" : "ban negative";?> icon link"></i><?php echo Lang::$word->FEATURED;?></a> <a class="doStatus item" data-set='{"field": "sold", "table": "Listing", "toggle": "check ban", "togglealt": "primary negative", "id": <?php echo $row->id;?>, "value": "<?php echo $row->sold;?>"}'><i class="<?php echo ($row->sold) ? "check primary" : "ban negative";?> icon link"></i><?php echo Lang::$word->SOLD;?></a> </div>
            </div>
            <a href="<?php echo Url::adminUrl("items", "stats", false,"?id=" . $row->id);?>" data-content="<?php echo Lang::$word->STATS;?>"><i class="rounded outline teal icon pie chart link"></i></a>
            <div class="quarter-top-space"></div>
            <a href="<?php echo Url::adminUrl("items", "print", false,"?id=" . $row->id);?>"><i class="rounded outline purple icon printer link"></i></a> <a href="<?php echo Url::adminUrl("items", "images", false,"?id=" . $row->id);?>"><i class="rounded outline primary icon photo link"></i></a>
            <div class="quarter-top-space"></div>
            <?php if(Auth::hasPrivileges('edit_items')):?>
            <a href="<?php echo Url::adminUrl("items", "edit", false,"?id=" . $row->id);?>"><i class="rounded outline positive icon pencil link"></i></a>
            <?php endif;?>
            <?php if(Auth::hasPrivileges('delete_items')):?>
            <a class="delete" data-set='{"title": "<?php echo Lang::$word->LST_DELETE;?>", "parent": "tr", "option": "deleteListing", "id": <?php echo $row->id;?>, "name": "<?php echo $row->title;?>"}'><i class="rounded outline icon negative trash link"></i></a>
            <?php endif;?></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
      <?php if($data):?>
      <tfoot>
        <tr>
          <td colspan="6"><button name="mdelete" type="button" data-form="#wojo_forml" class="wojo negative button"><i class="icon trash alt"></i><?php echo Lang::$word->LST_DELETES;?></button>
            <input name="delete" type="hidden" value="deleteMultiListings"></td>
        </tr>
      </tfoot>
      <?php endif;?>
    </table>
  </form>
  <div class="footer">
    <div class="wojo tabular segment">
      <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
      <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
    </div>
  </div>
</div>
<div id="msgholder"></div>
<?php break;?>
<?php endswitch;?>