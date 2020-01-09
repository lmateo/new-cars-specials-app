<?php
  /**
   * Seller
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: seller.tpl.php, v1.00 2015-08-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  $dataset = $items->renderBySeller($data->result->id);
?>
<div class="wojo-grid">
  <div class="wojo top attached segment">
    <div class="wojo huge header">
      <?php if($data->result->logo):?>
      <img src="<?php echo UPLOADURL . "showrooms/" . $data->result->logo;?>" alt="" class="wojo small image">
      <?php endif;?>
      <div class="content"> <?php echo $data->result->name;?>
        <p class="subheader"><?php echo Lang::$word->HOME_SUB9P;?></p>
      </div>
    </div>
  </div>
  <?php if(!$dataset):?>
  <?php echo Message::msgSingleInfo(Lang::$word->NOLISTFOUND);?>
  <?php else:?>
  <div class="wojo primary bottom attached segment">
    <div id="map" style="height:200px"></div>
  </div>
  <div class="columns gutters">
    <?php foreach($dataset as $row):?>
    <div class="screen-33 tablet-50 phone-100">
      <div class="wojo tertiary segment">
        <?php if($row->sold):?>
        <span class="wojo negative right ribbon label"><?php echo strtoupper(Lang::$word->SOLD);?></span>
        <?php endif;?>
        <div class="header"><a href="<?php echo Url::doUrl(URL_ITEM, $row->idx . '/' . $row->slug);?>" class="white"><?php echo $row->year . ' ' . $row->nice_title;?></a>
          <p>
            <?php if($row->price_sale <> 0):?>
            <span class="wojo strike negative label"><?php echo Utility::formatMoney($row->price, true);?></span> <span class="wojo positive label"><?php echo Utility::formatMoney($row->price_sale, true);?></span>
            <?php else:?>
            <span class="wojo positive label"><?php echo Utility::formatMoney($row->price, true);?></span>
            <?php endif;?>
          </p>
        </div>
        <div class="content-center"><a href="<?php echo Url::doUrl(URL_ITEM, $row->idx . '/' . $row->slug);?>" class="wojo block shine"><img src="<?php echo UPLOADURL . 'listings/thumbs/' . $row->thumb;?>" alt=""></a></div>
        <div class="footer">
          <div class="content-center">
            <div class="wojo small divided horizontal list">
              <div class="item"><?php echo $row->condition_name;?></div>
              <div class="item"><?php echo $row->trans_name;?></div>
              <div class="item"><?php echo $row->category_name;?></div>
              <div class="item"><?php echo Utility::doDate("short_date", $row->created);?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
    <?php unset($row);?>
  </div>
  <?php endif;?>
  <div class="wojo tabular segment">
    <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
    <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
  </div>
  <div class="wojo double space divider"></div>
  <?php $result = $items->getFooterBits();?>
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
</div>
<script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo $core->mapapi;?>&v=3"></script> 
<script type="text/javascript"> 
// <![CDATA[
var latitude = parseFloat(<?php echo $data->result->lat;?>);
var longitude = parseFloat(<?php echo $data->result->lng;?>);
var center = new google.maps.LatLng(latitude, longitude);
function initialize() {
    var mapOptions = {
        center: center,
		 backgroundColor: 'none',
         mapTypeControl: false,
         streetViewControl: false,
        zoom: <?php echo $data->result->zoom;?>,
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

    var map = new google.maps.Map(document.getElementById("map"), mapOptions);

    // InfoWindow content
    var content = 
	    '<div class="container">' +
		  '<h5><?php echo $data->result->name;?></h5>' +
		  '<div class="content">' +
			'<p><?php echo $data->result->address;?> <?php echo $data->result->city;?>' +
			'<br><?php echo $data->result->state;?> <?php echo $data->result->zip;?>' +
			'<br><?php echo $data->result->phone;?></p>' +
		  '</div>' +
        '</div>';

    // A new Info Window is created and set content
    var infowindow = new google.maps.InfoWindow({
        content: content,
        maxWidth: 350
    });

    // marker options
    var marker = new google.maps.Marker({
        position: center,
		icon: new google.maps.MarkerImage('<?php echo SITEURL;?>/assets/pin.png'),
        map: map,
        title: "<?php echo $data->result->name;?>"
    });
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker);
    });

    // Event that closes the Info Window with a click on the map
    google.maps.event.addListener(map, 'click', function() {
        infowindow.close();
    });
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
