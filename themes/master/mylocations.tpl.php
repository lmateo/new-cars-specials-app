<?php
  /**
   * My Locations
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: mylocations.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if (!$auth->is_User())
      Url::redirect(Url::doUrl(URL_LOGIN));
	  
  $locations = $content->getUserLocations();
?>
<div class="wojo-grid">
  <div class="wojo secondary segment">
    <div class="wojo huge fitted inverted header">
      <div class="content"> <?php echo Lang::$word->HOME_SUB12;?>
        <p class="subheader"><?php echo Lang::$word->HOME_SUB12P;?></p>
      </div>
    </div>
    <ul class="wojo tabs fixed bottom clearfix">
      <li><a href="<?php echo Url::doUrl(URL_ACCOUNT);?>" class="notab"><i class="icon note"></i><?php echo Lang::$word->PACKAGES;?></a></li>
      <li><a href="<?php echo Url::doUrl(URL_MYLISTINGS);?>" class="notab"><i class="icon car"></i><?php echo Lang::$word->LISTINGS;?></a></li>
      <li><a href="<?php echo Url::doUrl(URL_MYSETTINGS);?>" class="notab"><i class="icon cog"></i><?php echo Lang::$word->SETTINGS;?></a></li>
      <li><a href="<?php echo Url::doUrl(URL_ADD);?>"class="active notab"><i class="icon plus"></i><?php echo Lang::$word->LST_ADD;?></a></li>
    </ul>
  </div>
  <div class="wojo secondary bg">
    <div class="padding">
      <?php if(isset($_GET['add'])):?>
      <div class="wojo basic message"><a href="<?php echo Url::doUrl(URL_MYLOCATIONS);?>" class="wojo small button push-right"><i class="small chevron left icon"></i> <?php echo Lang::$word->BACK;?></a><?php echo Lang::$word->LOC_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
      <div class="wojo form">
        <form method="post" id="wojo_form" name="wojo_form">
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->LOC_NAME;?></label>
              <div class="wojo labeled icon input">
                <input type="text" placeholder="<?php echo Lang::$word->LOC_NAME;?>" name="name" required>
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
              <label><?php echo Lang::$word->ADDRESS;?></label>
              <div class="wojo labeled icon input">
                <input type="text" placeholder="<?php echo Lang::$word->ADDRESS;?>" name="address" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->CITY;?></label>
              <div class="wojo labeled icon input">
                <input type="text" placeholder="<?php echo Lang::$word->CITY;?>" name="city" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
          </div>
          <div class="three fields">
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
            <div class="field">
              <label><?php echo Lang::$word->COUNTRY;?></label>
              <div class="wojo labeled icon input">
                <input type="text" placeholder="<?php echo Lang::$word->COUNTRY;?>" name="country" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
          </div>
          <div class="three fields">
            <div class="field">
              <label><?php echo Lang::$word->CF_PHONE;?></label>
              <input type="text" placeholder="<?php echo Lang::$word->CF_PHONE;?>" name="phone">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->CF_FAX;?></label>
              <input type="text" placeholder="<?php echo Lang::$word->CF_FAX;?>" name="fax">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->CF_WEBURL;?></label>
              <input type="text" placeholder="<?php echo Lang::$word->CF_WEBURL;?>" name="url">
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->CF_LOGO;?></label>
              <label class="input">
                <input type="file" name="logo" id="logo" class="filefield">
              </label>
            </div>
            <div class="field"> </div>
          </div>
          <div class="wojo fitted double divider"></div>
          <div class="field">
            <div style="position:absolute;z-index:500;right:4em;top:1em">
              <div class="wojo action input" style="width:400px;">
                <input placeholder="<?php echo Lang::$word->LOC_SEARCH;?>" type="text" name="adrs" id="address" style="padding-left:1em">
                <a id="lookup" class="wojo icon button"><i class="find icon"></i></a> </div>
            </div>
            <div id="map" style="width:100%;height:350px;z-index:300"></div>
            <p class="note"><?php echo Lang::$word->LOC_SEARCH_T;?></p>
          </div>
          <div class="wojo fitted divider"></div>
          <div class="wojo footer">
            <button type="button" data-action="processLocation" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->LOC_ADD;?></button>
            <a href="<?php echo Url::doUrl(URL_MYLOCATIONS);?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
          <input name="lat" id="lat" type="hidden" value="0">
          <input name="lng" id="lng" type="hidden" value="0">
          <input name="zoom" id="zoomlevel" type="hidden" value="14">
        </form>
      </div>
      <script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo $core->mapapi;?>&v=3"></script> 
      <script type="text/javascript"> 
// <![CDATA[
 var map = null;
  $(document).ready(function () {
	  var geocoder;
	  geocoder = new google.maps.Geocoder();
	  var latitude = 43.652527;
	  var longitude = -79.381961;
	  loadMap(latitude, longitude);

	  $('#lookup').click(function () {
		  var address = document.getElementById('address').value;
		  geocoder.geocode({
			  'address': address
		  }, function (results, status) {
			  if (status == google.maps.GeocoderStatus.OK) {
				  map.setCenter(results[0].geometry.location);
				  var image = new google.maps.MarkerImage('<?php echo SITEURL;?>/assets/pin.png');
				  var marker = new google.maps.Marker({
					  map: map,
					  draggable: true,
					  raiseOnDrag: false,
					  icon: image,
					  position: results[0].geometry.location
				  });
				  $("#lat").val(results[0].geometry.location.lat());
				  $("#lng").val(results[0].geometry.location.lng());
			  
				  google.maps.event.addListener(marker, 'dragend', function (event) {
					  $("#lat").val(this.getPosition().lat());
					  $("#lng").val(this.getPosition().lng());
				  });			  
			  } else {
				  $.sticky('Geocode was not successful for the following reason: ' + status,{type: 'error'});
			  }

		  });
	  });

	  google.maps.event.addListener(map, 'zoom_changed', function () {
		  document.getElementById('zoomlevel').value = map.getZoom();
	  });
  });
   // Loads the maps
  loadMap = function (latitude, longitude) {
	  var latlng = new google.maps.LatLng(latitude, longitude);
	  var myOptions = {
		  zoom: 13,
		  center: latlng,
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
// ]]>
</script>
      <?php elseif(isset($_GET['edit'])):?>
      <?php if(!$row = $db->first(Content::lcTable, null, array('id' => Filter::$id, "user_id" => $auth->uid))) : Message::invalid("ID" . Filter::$id); return; endif;?>
      <div class="wojo basic message"><a href="<?php echo Url::doUrl(URL_MYLOCATIONS);?>" class="wojo small button push-right"><i class="small chevron left icon"></i> <?php echo Lang::$word->BACK;?></a><?php echo Lang::$word->LOC_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></div>
      <div class="wojo form">
        <form method="post" id="wojo_form" name="wojo_form">
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->LOC_NAME;?></label>
              <div class="wojo labeled icon input">
                <input type="text" value="<?php echo $row->name;?>" name="name" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
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
              <label><?php echo Lang::$word->ADDRESS;?></label>
              <div class="wojo labeled icon input">
                <input type="text" value="<?php echo $row->address;?>" name="address" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->CITY;?></label>
              <div class="wojo labeled icon input">
                <input type="text" value="<?php echo $row->city;?>" name="city" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
          </div>
          <div class="three fields">
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
            <div class="field">
              <label><?php echo Lang::$word->COUNTRY;?></label>
              <div class="wojo labeled icon input">
                <input type="text" value="<?php echo $row->country;?>" name="country" required>
                <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
              </div>
            </div>
          </div>
          <div class="three fields">
            <div class="field">
              <label><?php echo Lang::$word->CF_PHONE;?></label>
              <input type="text" value="<?php echo $row->phone;?>" name="phone">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->CF_FAX;?></label>
              <input type="text" value="<?php echo $row->fax;?>" name="fax">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->CF_WEBURL;?></label>
              <input type="text" value="<?php echo $row->url;?>" name="url">
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <label><?php echo Lang::$word->CF_LOGO;?></label>
              <label class="input">
                <input type="file" name="logo" id="logo" class="filefield">
              </label>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->CF_LOGO;?></label>
              <div class="wojo rounded small image"><img src="<?php echo UPLOADURL;?>showrooms/<?php echo ($row->logo) ? $row->logo : "blank.png";?>" alt=""></div>
            </div>
          </div>
          <div class="wojo fitted double divider"></div>
          <div class="field">
            <div id="map" style="width:100%;height:350px;z-index:300"></div>
            <p class="note"><?php echo Lang::$word->LOC_SEARCH_T;?></p>
          </div>
          <div class="wojo footer">
            <button type="button" data-action="processLocation" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->LOC_UPDATE;?></button>
            <a href="<?php echo Url::doUrl(URL_MYLOCATIONS);?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
          <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
          <input name="lat" id="lat" type="hidden" value="<?php echo $row->lat;?>">
          <input name="lng" id="lng" type="hidden" value="<?php echo $row->lng;?>">
          <input name="zoom" id="zoomlevel" type="hidden" value="<?php echo $row->zoom;?>">
        </form>
      </div>
<script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo $core->mapapi;?>&v=3"></script> 
<script type="text/javascript"> 
// <![CDATA[
 var map = null;
  $(document).ready(function () {
	  var geocoder;
	  geocoder = new google.maps.Geocoder();
	  var latitude = parseFloat("<?php echo $row->lat;?>");
	  var longitude = parseFloat("<?php echo $row->lng;?>");
	  loadMap(latitude, longitude);
	  setupMarker(latitude, longitude);

	  $('#lookup').click(function () {
		  var address = document.getElementById('address').value;
		  geocoder.geocode({
			  'address': address
		  }, function (results, status) {
			  if (status == google.maps.GeocoderStatus.OK) {
				  map.setCenter(results[0].geometry.location);
				  var image = new google.maps.MarkerImage('<?php echo SITEURL;?>/assets/pin.png');
				  var marker = new google.maps.Marker({
					  map: map,
					  draggable: true,
					  raiseOnDrag: false,
					  icon: image,
					  position: results[0].geometry.location
				  });

				  $("#lat").val(results[0].geometry.location.lat());
				  $("#lng").val(results[0].geometry.location.lng());
			  
				  google.maps.event.addListener(marker, 'dragend', function (event) {
					  $("#lat").val(this.getPosition().lat());
					  $("#lng").val(this.getPosition().lng());
				  });			  
			  } else {
                  $.sticky('Geocode was not successful for the following reason: ' + status,{type: 'error'});
			  }

		  });
	  });

	  google.maps.event.addListener(map, 'zoom_changed', function () {
		  document.getElementById('zoomlevel').value = map.getZoom();
	  });
  });
   // Loads the maps
  loadMap = function (latitude, longitude) {
	  var latlng = new google.maps.LatLng(latitude, longitude);
	  var myOptions = {
		  zoom: <?php echo $row->zoom;?>,
		  center: latlng,
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
  
  setupMarker = function (latitude, longitude) {
	  var pos = new google.maps.LatLng(latitude, longitude);
	  var image = new google.maps.MarkerImage('<?php echo SITEURL;?>/assets/pin.png');
	  var marker = new google.maps.Marker({
		  position: pos,
		  map: map,
          draggable: true,
          raiseOnDrag: false,
		  icon: image,
		  title: name
	  });
	  google.maps.event.addListener(marker, 'dragend', function (event) {
		  $("#lat").val(this.getPosition().lat());
		  $("#lng").val(this.getPosition().lng());
	  });
  }
</script>
      <?php else:?>
      <div class="wojo basic message"><a href="<?php echo Url::doUrl(URL_MYLOCATIONS, false, "?add=true");?>" class="wojo small button push-right"><i class="plus small icon"></i> <?php echo Lang::$word->ADD;?></a><?php echo Lang::$word->HOME_SUB19P;?></div>
      <?php if(!$locations):?>
      <?php echo Message::msgSingleAlert(Lang::$word->LOC_NOLOC);?>
      <?php else:?>
      <div class="columns gutters">
        <?php foreach($locations as $lcrow):?>
        <div class="row screen-33 tablet-50 phone-100">
          <div class="wojo divided card">
            <div class="header">
              <div class="wojo rounded small image"><img src="<?php echo UPLOADURL;?>showrooms/<?php echo ($lcrow->logo) ? $lcrow->logo : "blank.png";?>" alt=""></div>
              <div class="content"> <a href="<?php echo Url::doUrl(URL_MYLOCATIONS, false, "?edit=true&amp;id=" . $lcrow->id);?>"><?php echo $lcrow->name;?> </a>
                <p><?php echo $lcrow->email;?></p>
              </div>
            </div>
            <div class="item">
              <div class="intro"><i class="icon building"></i></div>
              <div class="data"><?php echo $lcrow->address;?><br>
                <?php echo $lcrow->city;?>, <?php echo $lcrow->state;?>, <?php echo $lcrow->zip;?><br>
                <?php echo $lcrow->country;?> </div>
            </div>
            <div class="item">
              <div class="intro"><i class="icon globe"></i></div>
              <div class="data"><?php echo $lcrow->url;?></div>
            </div>
            <div class="item">
              <div class="intro"><i class="icon phone call"></i></div>
              <div class="data"><?php echo $lcrow->phone;?></div>
            </div>
            <div class="item">
              <div class="intro"><i class="icon printer"></i></div>
              <div class="data"><?php echo $lcrow->fax;?></div>
            </div>
            <div class="actions">
              <div class="item">
                <div class="intro"><i class="icon long right arrow"></i></div>
                <div class="data"><a href="<?php echo Url::doUrl(URL_MYLOCATIONS, false, "?edit=true&amp;id=" . $lcrow->id);?>"><i class="rounded inverted positive icon pencil link"></i></a> </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <?php endif;?>
      <?php endif;?>
    </div>
  </div>
</div>