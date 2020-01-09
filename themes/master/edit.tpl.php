<?php
  /**
   * Edit
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: edit.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  $gallerydata = Items::getGalleryImages();
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->LST_SUB1;?> <small> / <?php echo $data->result->title;?></small>
        <p class="subheader"><?php echo Lang::$word->LST_INFO1;?></p>
      </div>
    </div>
  </div>
  <div class="wojo form secondary bg">
    <form method="post" id="wojo_form" name="wojo_form">
      <div class="padding">
        <div class="four fields">
          <div class="field">
            <label><?php echo Lang::$word->LST_ROOM;?></label>
            <select name="location">
              <option value="">-- <?php echo Lang::$word->LST_ROOM_S;?> --</option>
              <?php echo Utility::loopOptions($content->getUserLocations(), "id", "name", $data->result->location);?>
            </select>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_YEAR;?></label>
            <div class="wojo labeled icon input">
              <input type="text" value="<?php echo $data->result->year;?>" name="year" required>
              <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_STOCK;?></label>
            <input type="text" value="<?php echo $data->result->stock_id;?>" name="stock_id">
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_VIN;?></label>
            <input type="text" value="<?php echo $data->result->vin;?>" name="vin">
          </div>
        </div>
        <div class="four fields">
          <div class="field">
            <label><?php echo Lang::$word->LST_MAKE;?></label>
            <select name="make_id">
              <option value="">-- <?php echo Lang::$word->LST_MAKE_S;?> --</option>
              <?php echo Utility::loopOptions($content->getMakes(false), "id", "name", $data->result->make_id);?>
            </select>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_MODEL;?></label>
            <select name="model_id">
              <option value="">-- <?php echo Lang::$word->LST_MODEL_S;?> --</option>
              <?php echo Utility::loopOptions($content->getModelList($data->result->make_id), "id", "name", $data->result->model_id);?>
            </select>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_CAT;?></label>
            <select name="category">
              <option value="">-- <?php echo Lang::$word->LST_CAT_S;?> --</option>
              <?php echo Utility::loopOptions($content->getCategories(), "id", "name", $data->result->category);?>
            </select>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_COND;?></label>
            <select name="vcondition">
              <option value="">-- <?php echo Lang::$word->LST_COND_S;?> --</option>
              <option value="1"<?php if($data->result->vcondition == 1) echo ' selected="selected"';?>><?php echo Lang::$word->NEW;?></option>
              <option value="2"<?php if($data->result->vcondition == 2) echo ' selected="selected"';?>><?php echo Lang::$word->USED;?></option>
            </select>
          </div>
        </div>
        <div class="four fields">
          <div class="field">
            <label><?php echo Lang::$word->LST_ODM;?></label>
            <label class="input"> <b class="icon-append"><?php echo $core->odometer;?></b>
              <input type="text" value="<?php echo $data->result->mileage;?>" name="mileage">
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_TORQUE;?></label>
            <input type="text" value="<?php echo $data->result->torque;?>" name="torque">
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_INTC;?></label>
            <select name="color_i">
              <option value="">-- <?php echo Lang::$word->LST_SELCLR;?> --</option>
              <?php echo Utility::loopOptionsSimple(Content::colorList(), $data->result->color_i);?>
            </select>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_EXTC;?></label>
            <select name="color_e">
              <option value="">-- <?php echo Lang::$word->LST_SELCLR;?> --</option>
              <?php echo Utility::loopOptionsSimple(Content::colorList(), $data->result->color_e);?>
            </select>
          </div>
        </div>
        <div class="four fields">
          <div class="field">
            <label><?php echo Lang::$word->LST_ENGINE;?></label>
            <input type="text" value="<?php echo $data->result->engine;?>" name="engine">
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_TRAIN;?></label>
            <input type="text" value="<?php echo $data->result->drive_train;?>" name="drive_train">
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_DOORS;?></label>
            <input class="wojo range slider" type="range" min="1" max="6" step="1" name="doors" value="<?php echo $data->result->doors;?>">
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_TRANS;?></label>
            <select name="transmission">
              <option value="">-- <?php echo Lang::$word->LST_TRANS_S;?> --</option>
              <?php echo Utility::loopOptions(Utility::jSonToArray($core->trans_list), "id", "name", $data->result->transmission);?>
            </select>
          </div>
        </div>
        <div class="four fields">
          <div class="field">
            <label><?php echo Lang::$word->LST_SPEED;?></label>
            <label class="input"> <b class="icon-append"><?php echo ($core->odometer == "km") ? 'km/h' : 'mph';?></b>
              <input type="text" value="<?php echo $data->result->top_speed;?>" name="top_speed">
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_FUEL;?></label>
            <select name="fuel">
              <option value="">-- <?php echo Lang::$word->LST_FUEL_S;?> --</option>
              <?php echo Utility::loopOptions(Utility::jSonToArray($core->fuel_list), "id", "name", $data->result->fuel);?>
            </select>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_POWER;?></label>
            <input type="text" value="<?php echo $data->result->horse_power;?>" name="horse_power">
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_TOWING;?></label>
            <input type="text"  value="<?php echo $data->result->towing_capacity;?>" name="towing_capacity">
          </div>
        </div>
        <div class="four fields">
          <div class="field">
            <label><?php echo Lang::$word->LST_PRICE;?></label>
            <div class="wojo labeled icon input">
              <input type="text" value="<?php echo $data->result->price;?>" name="price" required>
              <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
            </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_DPRICE_S;?></label>
            <label class="input">
              <input type="text" value="<?php echo $data->result->price_sale;?>" name="price_sale">
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->CREATED;?></label>
            <label class="input">
              <input name="last_active" type="text" disabled value="<?php echo Utility::dodate("long_date", $data->result->created);?>" readonly>
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->EXPIRE;?></label>
            <label class="input">
              <input type="text" disabled readonly value="<?php echo Utility::dodate("long_date", $data->result->expire);?>" name="expire">
            </label>
          </div>
        </div>
        <div class="two fields">
          <div class="field">
            <label><?php echo Lang::$word->LST_IMAGE;?></label>
            <label class="input">
              <input type="file" name="thumb" id="thumb" class="filefield">
            </label>
            <div class="quarter-top-space"><a data-lightbox="true" data-title="<?php echo $data->result->title;?>" href="<?php echo UPLOADURL . 'listings/' . $data->result->thumb;?>"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $data->result->thumb;?>" alt="" class="wojo grid image"></a> </div>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->LST_IMAGEA;?></label>
            <label class="input">
              <input type="file" name="photo" id="photo" data-fields='{"processGalleryImages":1, "id":<?php echo $data->result->id;?>}' class="multifile" multiple>
            </label>
            <div class="wojo carousel quarter-top-space" data-slick='{"dots": true,"arrows":false,"mobileFirst":true,"lazyLoad": "ondemand","responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 2,"slidesToScroll": 2}},{ "breakpoint": 769, "settings":{"slidesToShow": 2,"slidesToScroll": 2}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}' id="editable">
              <?php if($gallerydata):?>
              <?php foreach ($gallerydata as $grow):?>
              <div class="wojo reveal"><img data-lazy="<?php echo UPLOADURL . 'listings/pics' . $data->result->id . '/thumbs/' . $grow->photo;?>" alt="">
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
    </form>
  </div>
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
	
		/* == Inline Edit == */
		$('#editable').editableTableWidget();
		$('#editable')
		.on('validate', '[data-editable]', function(e, val) {
			if (val === "") {
				return false;
			}
		})
		.on('change', '[data-editable]', function(e, val) {
			var data = $(this).data('set');
			var $this = $(this);
			$.ajax({
				type: "POST",
				url: SITEURL + "/ajax/user.php",
				data: ({
					'title': val,
					'type': data.type,
					'key': data.key,
					'path': data.path,
					'id': data.id,
					'quickedit': 1
				}),
				beforeSend: function() {
					$this.text("<?php echo Lang::$word->WORKING;?>").animate({
						opacity: 0.2
					}, 800);
				},
				success: function(res) {
					$this.animate({
						opacity: 1
					}, 800);
					setTimeout(function() {
						$this.html(res).fadeIn("slow");
					}, 1000);
				}
			});
		});
});
// ]]>
</script>
<?php
/*	
      App::get('Session')->remove('debug-queries');
	  App::get('Session')->remove('debug-warnings');
	  App::get('Session')->remove('debug-errors');
	  App::get('Session')->remove('debug-params');
*/
//Debug::pre($mrow);
?>
