<?php
  /**
   * Item
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: item.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
  <a id="print" onclick="javascript:void window.open('<?php echo SITEURL;?>/print.php?id=<?php echo $data->result->id;?>','printer','width=880,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0'); return false;"><i class="icon large printer"></i></a>
    <div class="wojo bottom right floated div">
      <?php if($data->result->price_sale <> 0):?>
      <span class="wojo strike negative button"><?php echo Utility::formatMoney($data->result->price, true);?></span> <span class="wojo positive button"><?php echo Utility::formatMoney($data->result->price_sale, true);?></span>
      <?php else:?>
      <span class="wojo positive button"><?php echo Utility::formatMoney($data->result->price, true);?></span>
      <?php endif;?>
    </div>
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo $data->result->year;?> <?php echo $data->result->nice_title;?>
        <p class="subheader"><span class="wojo small black label"># <?php echo $data->result->stock_id;?></span></p>
      </div>
    </div>
  </div>
  <div class="wojo primary top bottom attached segment">
    <div class="columns gutters">
      <div class="screen-40 tablet-50 phone-100">
        <?php if($gallery = Utility::unserialToArray($data->result->gallery)):?>
        <div class="wojo tertiary segment">
          <div id="mcarousel" class="wojo carousel" data-slick='{"dots": false,"arrows":false,"asNavFor": "#scarousel", "lazyLoad": "ondemand", "slidesToShow":1, "slidesToScroll": 1}'>
            <?php foreach($gallery as $rows):?>
            <div class="inner"><a data-lightbox="true" data-title="<?php echo $rows->title;?>" data-lightbox-gallery="photos" href="<?php echo UPLOADURL . 'listings/pics' . $data->result->id . '/' . $rows->photo;?>"><img src="<?php echo UPLOADURL . 'listings/pics' . $data->result->id . '/' . $rows->photo;?>" alt=""></a>
              <p><?php echo $rows->title;?></p>
            </div>
            <?php endforeach;?>
            <?php unset($rows);?>
          </div>
        </div>
        <?php else:?>
        <img src="<?php echo UPLOADURL . 'listings/' . $data->result->thumb;?>" alt="">
        <?php endif;?>
        <?php if($gallery = Utility::unserialToArray($data->result->gallery)):?>
        <div id="scarousel" class="wojo carousel" data-slick='{"dots": true,"arrows":false,"asNavFor": "#mcarousel", "focusOnSelect": true,"centerMode": true, "mobileFirst":true,"responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 4,"slidesToScroll": 1}},{ "breakpoint": 769, "settings":{"slidesToShow": 2,"slidesToScroll": 1}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}'>
          <?php foreach($gallery as $rows):?>
          <img src="<?php echo UPLOADURL . 'listings/pics' . $data->result->id . '/thumbs/' . $rows->photo;?>" alt="">
          <?php endforeach;?>
          <?php unset($rows);?>
        </div>
        <?php endif;?>
      </div>
      <div class="screen-60 tablet-50 phone-100">
        <div class="wojo segment">
          <?php if($data->result->sold):?>
          <span class="wojo negative right ribbon label"><?php echo strtoupper(Lang::$word->SOLD);?></span>
          <?php endif;?>
          <h4><i class="icon map marker"></i> <?php echo Lang::$word->LOCATION;?></h4>
          <?php $location = $db->first(Content::lcTable, "*", array("id" => $data->result->location));// Utility::unserialToArray($data->result->location_name);?>
          <div><?php echo $location->address;?> <?php echo $location->city;?></div>
          <div><?php echo $location->state;?> <?php echo $location->zip;?></div>
          <div class="wojo space divider"></div>
          <div class="columns horizontal-gutters">
            <div class="screen-50 tablet-100 phone-100">
              <div class="wojo divided list">
                <div class="item ">
                  <div class="right floated content"> <?php echo $location->name;?> </div>
                  <div class="content"> <?php echo Lang::$word->SELDLR;?></div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->vin;?> </div>
                  <div class="content"> VIN</div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->sold ? Lang::$word->SOLD : Lang::$word->AVAIL;?> </div>
                  <div class="content"> <?php echo Lang::$word->AVAILB;?></div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->condition_name;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_COND;?> </div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->color_e;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_EXTC;?> </div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->horse_power;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_POWER;?> </div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->top_speed;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_SPEED;?> </div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo Utility::formatNumber($data->result->mileage);?> <?php echo $core->odometer;?> </div>
                  <div class="content"> <?php echo $core->odometer == "km" ? Lang::$word->KM : Lang::$word->MI;?> </div>
                </div>
              </div>
            </div>
            <div class="screen-50 tablet-100 phone-100">
              <div class="wojo divided list">
                <div class="item ">
                  <div class="right floated content"> <?php echo $data->result->category_name;?> </div>
                  <div class="content"> <?php echo Lang::$word->CAT_BODYS;?></div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->trans_name;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_TRANS;?></div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->fuel_name;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_FUEL;?></div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->drive_train;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_TRAIN;?> </div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->color_i;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_INTC;?> </div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->doors;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_DOORS;?> </div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->torque;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_TORQUE;?> </div>
                </div>
                <div class="item">
                  <div class="right floated content"> <?php echo $data->result->model_name;?> </div>
                  <div class="content"> <?php echo Lang::$word->LST_MODEL;?> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="padding"> <a href="<?php echo Url::doUrl(URL_SELLER, $location->name_slug);?>" class="wojo fluid primary button"> <?php echo Lang::$word->HOME_MORES;?></a> </div>
      </div>
    </div>
    <div class="wojo space divider"></div>
  </div>
  
  <!--  <div class="wojo double space divider"></div>-->
  <ul class="wojo tabs fluid clearfix">
    <li><a data-tab="#general" class="active"><i class="icon note"></i><?php echo Lang::$word->DESC;?></a></li>
    <li><a data-tab="#feat"><i class="icon car"></i><?php echo Lang::$word->LST_MFET;?></a></li>
    <li><a id="ghack" data-tab="#location"><i class="icon map marker"></i><?php echo Lang::$word->LOCATION;?></a></li>
    <li><a data-tab="#contact"><i class="icon email"></i><?php echo Lang::$word->CONTACT;?></a></li>
  </ul>
  <div class="wojo top bottom attached tertiary segment padded">
    <div id="general" class="wojo tab item"> <?php echo Validator::cleanOut($data->result->body);?> </div>
    <div id="feat" class="wojo tab item">
      <?php $featurerow = $content->getFeaturesById($data->result->features);?>
      <?php if($featurerow):?>
      <div class="wojo relaxed list">
        <div class="columns half-horizontal-gutters">
          <?php foreach ($featurerow as $frow):?>
          <div class="screen-33 tablet-50 phone-100">
            <div class="item"><i class="icon check"></i> <?php echo $frow->name;?> </div>
          </div>
          <?php endforeach;?>
          <?php unset($frow);?>
        </div>
      </div>
      <?php endif;?>
    </div>
    <div id="location" class="wojo tab item">
      <div id="map" style="height:400px"></div>
    </div>
    <div id="contact" class="wojo tab item">
      <div class="columns gutters">
        <div class="screen-10 tablet-15 phone-100"><img src="<?php echo UPLOADURL . ($location->logo ? "showrooms/" . $location->logo : ($data->result->avatar ? "avatars/" . $data->result->avatar : "avatars/blank.png"));?>" alt="" class="wojo normal image"></div>
        <div class="screen-30 tablet-35 phone-100">
          <h4><?php echo $location->name;?></h4>
          <div><?php echo $location->address;?> <?php echo $location->city;?></div>
          <div><?php echo $location->state;?> <?php echo $location->zip;?>, <?php echo $location->country;?></div>
          <div class="wojo space divider"></div>
          <div class="wojo celled list">
            <div class="item"><i class="icon user"></i> <?php echo $data->result->username;?></div>
            <div class="item"><i class="icon earth"></i> <?php echo $location->url;?></div>
            <div class="item"><i class="icon phone"></i> <?php echo $location->phone;?></div>
            <a href="<?php echo Url::doUrl(URL_SELLER, $location->name_slug);?>" class="item"><i class="icon car"></i> <?php echo Lang::$word->HOME_MORES;?></a>
          </div>
        </div>
        <div class="screen-60 tablet-50 phone-100"> 
          <!--/* Contact Form Start */-->
          <div class="wojo form">
            <form method="post" id="wojo_form" name="wojo_form">
              <div class="two fields">
                <div class="field">
                  <label class="input"><i class="icon-append icon asterisk"></i>
                    <input name="name" type="text" placeholder="<?php echo Lang::$word->EMN_NLN;?>" value="<?php echo ($auth->logged_in) ? $auth->name : null;?>">
                  </label>
                </div>
                <div class="field">
                  <label class="input"><i class="icon-append icon asterisk"></i>
                    <input name="email" type="text" placeholder="<?php echo Lang::$word->EMN_NLE;?>" value="<?php echo ($auth->logged_in) ? $auth->email : null;?>">
                  </label>
                </div>
              </div>
              <div class="field">
                <label class="small textarea">
                  <textarea name="message" placeholder="<?php echo Lang::$word->MESSAGE;?>"></textarea>
                </label>
              </div>
              <div class="content-center">
                <button type="button" data-action="contactSeller" data-clear="true" name="dosubmit" class="wojo negative rounded button"><?php echo Lang::$word->SUBMIT;?></button>
              </div>
              <input name="item_id" type="hidden" value="<?php echo $data->result->idx . '/' . $data->result->slug;?>">
              <input name="location" type="hidden" value="<?php echo $location->id;?>">
              <input name="stock_id" type="hidden" value="<?php echo $data->result->stock_id;?>">
            </form>
          </div>
          <!--/* Contact Form Ends */--> 
        </div>
      </div>
    </div>
  </div>
  <?php $result = $items->getFooterBits();?>
  <?php if($result):?>
  <div class="wojo top bottom attached segment">
  <div class="double-padding">
    <h4><?php echo Lang::$word->HOME_SUB2P;?></h4>
    <div class="wojo double space divider"></div>
    <div class="columns half-gutters">
      <?php $makes = Utility::groupToLoop($result, "make_name");?>
      <?php foreach($makes as $make => $i):?>
      <div class="screen-25 tablet-33 phone-50"><a href="<?php echo Url::doUrl(URL_BRAND, Url::doSeo($make));?>"><img src="<?php echo UPLOADURL . 'brandico/' . str_replace(" ", "-", strtolower($make));?>.png" class="wojo avatar image" alt=""><?php echo $make;?> <span class="wojo bold negative text"><?php echo count($i);?></span></a></div>
      <?php endforeach;?>
    </div>
    <?php unset($i);?>
    </div>
  </div>
  <?php endif;?>
  
  <?php if($result):?>
  <div class="wojo secondary segment">
    <h4 class="wojo inverted header"><?php echo Lang::$word->HOME_SUB7P;?></h4>
    <div class="wojo double space divider"></div>
    <div class="columns half-gutters">
      <?php $categories = Utility::groupToLoop($result, "category_name");?>
      <?php foreach($categories as $category => $i):?>
      <div class="screen-25 tablet-33 phone-50"><a href="<?php echo Url::doUrl(URL_BODY, Url::doSeo($category));?>" class="wojo medium text caps"><img src="<?php echo UPLOADURL . 'catico/' . str_replace(" ", "-", strtolower($category));?>.png" class="wojo overflown image" alt=""><?php echo $category;?> <span class="wojo secondary text"><?php echo count($i);?></span></a></div>
      <?php endforeach;?>
    </div>
    <?php unset($i);?>
  </div>
  <?php endif;?>
</div>
<script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo $core->mapapi;?>&v=3"></script> 
<script type="text/javascript"> 
// <![CDATA[
 var map = null;
 $(document).ready(function() {
     $('#ghack').one('click', function() {
         var geocoder;
         geocoder = new google.maps.Geocoder();
         var latitude = parseFloat(<?php echo $location->lat;?>);
         var longitude = parseFloat(<?php echo $location->lng;?>);
         loadMap(latitude, longitude);
         setupMarker(latitude, longitude);

     });
 });
 // Loads the maps
 loadMap = function(latitude, longitude) {
     var latlng = new google.maps.LatLng(latitude, longitude);
     var myOptions = {
         zoom: <?php echo $location->zoom;?>,
         center: latlng,
		 backgroundColor: 'none',
         mapTypeControl: false,
         streetViewControl: false,
         mapTypeId: google.maps.MapTypeId.ROADMAP,
		  styles: [{
			  "featureType": "landscape",
			  "stylers": [{
				  "hue": "#F1FF00"
			  }, {
				  "saturation": -27.4
			  }, {
				  "lightness": 9.4
			  }, {
				  "gamma": 1
			  }]
		  }, {
			  "featureType": "road.highway",
			  "stylers": [{
				  "hue": "#0099FF"
			  }, {
				  "saturation": -20
			  }, {
				  "lightness": 36.4
			  }, {
				  "gamma": 1
			  }]
		  }, {
			  "featureType": "road.arterial",
			  "stylers": [{
				  "hue": "#00FF4F"
			  }, {
				  "saturation": 0
			  }, {
				  "lightness": 0
			  }, {
				  "gamma": 1
			  }]
		  }, {
			  "featureType": "road.local",
			  "stylers": [{
				  "hue": "#FFB300"
			  }, {
				  "saturation": -38
			  }, {
				  "lightness": 11.2
			  }, {
				  "gamma": 1
			  }]
		  }, {
			  "featureType": "water",
			  "stylers": [{
				  "hue": "#00B6FF"
			  }, {
				  "saturation": 4.2
			  }, {
				  "lightness": -63.4
			  }, {
				  "gamma": 1
			  }]
		  }, {
			  "featureType": "poi",
			  "stylers": [{
				  "hue": "#9FFF00"
			  }, {
				  "saturation": 0
			  }, {
				  "lightness": 0
			  }, {
				  "gamma": 1
			  }]
		  }]
     };
     map = new google.maps.Map(document.getElementById("map"), myOptions);
 }

 setupMarker = function(latitude, longitude) {
     var pos = new google.maps.LatLng(latitude, longitude);
     var image = new google.maps.MarkerImage('<?php echo SITEURL;?>/assets/pin.png');
     var marker = new google.maps.Marker({
         position: pos,
         map: map,
         draggable: false,
         raiseOnDrag: false,
         icon: image,
         title: name
     });
 }
</script>