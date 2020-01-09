<?php
  /**
   * Location Manager
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: locations.php, v1.00 2014-10-08 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  if(!Users::checkAcl("owner", "admin")): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!$row = $db->select(Content::lcTable, null, array('id' => Filter::$id), 'LIMIT 1')->result()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->LOC_SUB1;?> <small> / <?php echo $row->name;?></small> </div>
      <p><?php echo Lang::$word->LOC_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
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
    <div class="three fields">
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
       <div class="field">
        <label>Display Logo</label>
        <div class="wojo icon input">
          <input  name="logo2" type="text"  value="<?php echo $row->logo2;?>" required>     
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
        <label>Letter Code</label>
         <div class="wojo labeled icon input">
        <input type="text" value="<?php echo $row->letter;?>" name="letter" required>
        <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
      </div>
       </div>
      <div class="field">
        <label>Sales <?php echo Lang::$word->CF_PHONE;?></label>
        <input type="text" value="<?php echo $row->salesphone;?>" name="salesphone">
      </div>
      <div class="field">
        <label>Service <?php echo Lang::$word->CF_PHONE;?></label>
        <input type="text" value="<?php echo $row->servicephone;?>" name="servicephone">
      </div>
     </div>
    <div class="four fields">
     <div class="field">
        <label><?php echo Lang::$word->CF_FAX;?></label>
        <input type="text" value="<?php echo $row->fax;?>" name="fax">
      </div>
     <div class="field">
        <label><?php echo Lang::$word->CF_WEBURL;?></label>
        <input type="text" value="<?php echo $row->url;?>" name="url">
      </div>
    <div class="field">
        <label>Dealership Brand Logo</label>
        <label class="input">
          <input type="file" name="logo" id="logo" class="filefield">
        </label>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CF_LOGO;?></label>
        <div><img src="<?php echo UPLOADURL;?>showrooms/<?php echo ($row->logo) ? $row->logo : "blank.png";?>" alt=""height="50" width="200"></div>
      </div>
    </div>
    
    <div class="field">
        <label>Sales</label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="hasSales" <?php Validator::getChecked($row->hasSales, 1); ?>>
              <i></i>Has Sales </label>
          </div>
          </div>
          
          <div class="field">
        <label>Service</label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="hasService" <?php Validator::getChecked($row->hasService, 1); ?>>
              <i></i>Has Service </label>
          </div>
          </div>
          
          <div class="field">
        <label>Parts</label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="hasParts" <?php Validator::getChecked($row->hasParts, 1); ?>>
              <i></i>Has Parts </label>
          </div>
          </div>
    <div class="wojo fitted article divider"></div>
    <div class="field">
      <div style="position:absolute;z-index:500;right:.5em;top:.5em">
        <div class="wojo action input" style="width:400px;">
          <input placeholder="<?php echo Lang::$word->LOC_SEARCH;?>" type="text" name="adrs" id="address" style="padding-left:1em">
          <a id="lookup" class="wojo icon button"><i class="find icon"></i></a> </div>
      </div>
      <div id="map" style="width:100%;height:350px;z-index:300"></div>
      <p class="note"><?php echo Lang::$word->LOC_SEARCH_T;?></p>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processLocation" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->LOC_UPDATE;?></button>
      <a href="<?php echo Url::adminUrl("locations");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
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
<?php break;?>
<?php case"add": ?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="plus icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->LOC_SUB2;?></div>
      <p><?php echo Lang::$word->LOC_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
  </div>
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
    <div class="three fields">
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
      <div class="field">
        <label>Display Logo</label>
        <div class="wojo labeled icon input">
          <input type="text"  placeholder="Display Logo" name="logo2" required>  
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
        <label>Letter Code</label>
        <div class="wojo labeled icon input">
          <input type="text" placeholder="Letter Code" name="letter" required>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label>Sales <?php echo Lang::$word->CF_PHONE;?></label>
        <input type="text" placeholder="Sales <?php echo Lang::$word->CF_PHONE;?>" name="salesphone">
      </div>
      
      <div class="field">
        <label>Service <?php echo Lang::$word->CF_PHONE;?></label>
        <input type="text" placeholder="Service <?php echo Lang::$word->CF_PHONE;?>" name="servicephone">
      </div>
    </div>
    <div class="three fields">
     <div class="field">
        <label><?php echo Lang::$word->CF_FAX;?></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_FAX;?>" name="fax">
      </div>
    <div class="field">
        <label><?php echo Lang::$word->CF_WEBURL;?></label>
        <input type="text" placeholder="<?php echo Lang::$word->CF_WEBURL;?>" name="url">
      </div>
      <div class="field">
        <label>Dealership Brand Logo</label>
        <label class="input">
          <input type="file" name="logo" id="logo" class="filefield">
        </label>
      </div>
      
    </div>
    <div class="three fields">
       <div class="field">
        <label>Sales</label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="hasSales" >
              <i></i>Has Sales </label>
          </div>
          </div>
      
       <div class="field">
        <label>Service</label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="hasService">
              <i></i>Has Service </label>
          </div>
          </div>
          
        <div class="field">
        <label>Parts</label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="hasParts">
              <i></i>Has Parts </label>
          </div>
          </div>
          
           </div>
    <div class="wojo fitted article divider"></div>
    <div class="field">
      <div style="position:absolute;z-index:500;right:.5em;top:.5em">
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
      <a href="<?php echo Url::adminUrl("locations");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
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

	  $('#generate').click(function(e) {
		    $('#dealershipPass').val($.password(8));
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
<?php break;?>
<?php default: ?>
<?php $data = $content->getLocations();?>
<div class="wojo secondary icon message"> <i class="building icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LOC_TITLE;?></div>
    <p><?php echo Lang::$word->LOC_INFO;?></p>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->LOC_SUB;?></span> <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->LOC_ADD;?>" href="<?php echo Url::adminUrl("locations", "add");?>" ><i class="icon plus"></i></a> </div>
</div>
<?php if(!$data):?>
<?php echo Message::msgSingleAlert(Lang::$word->LOC_NOLOC);?>
<?php else:?>
<div class="wojo space divider"></div>
<div class="columns gutters">
  <?php foreach($data as $row):?>
  <div class="row screen-33 tablet-50 phone-100">
    <div class="wojo divided card">
      <div class="header">
        <div class="wojo top right small attached label"><?php echo $row->id;?>.</div>
        <div class="wojo circular primary small image"><img src="<?php echo UPLOADURL;?>showrooms/<?php echo ($row->logo) ? $row->logo : "blank.png";?>" height="30" width="30" alt=""></div>
        <div class="content"> <a href="<?php echo Url::adminUrl("locations", "edit", false,"?id=" . $row->id);?>"><?php echo $row->name;?> </a>
          <p><?php echo $row->email;?></p>
        </div>
      </div>
      <div class="item">
        <div class="intro"><i class="icon building"></i></div>
        <div class="data"><?php echo $row->address;?><br>
          <?php echo $row->city;?>, <?php echo $row->state;?>, <?php echo $row->zip;?><br>
          <?php echo $row->country;?> </div>
      </div>
      <div class="item">
        <div class="intro"><i class="icon globe"></i></div>
        <div class="data"><a href ='<?php echo $row->url;?>' target="_blank"><?php echo $row->url;?></a></div>
      </div>
      <div class="item">
        <div class="intro"><i class="icon phone call"> Sales Phone#:</i></div>
        <div class="data"><?php echo $row->salesphone;?></div>
      </div>
       <div class="item">
        <div class="intro"><i class="icon phone call"> Service Phone#:</i> </div>
        <div class="data"><?php echo $row->servicephone;?></div>
      </div>
      <div class="item">
        <div class="intro">Letter Code:</i></div>
        <div class="data"><?php echo $row->letter;?></div>
      </div>
      <div class="item">
        <div class="intro">View Web Specials:</div>
        <div class="data"><a href="<?php echo Url::adminUrl("webspecials", "dealership", false,"?id=" . $row->store_id);?>"><img src="<?php echo ($row->logo2) ? $row->logo2 : "blank.png";?>" height="90" width="90" alt=""></a></div>
      </div>
      <div class="actions">
        <div class="item">
          <div class="intro"><?php echo Lang::$word->ACTIONS;?>:</div>
          <div class="data"><a href="<?php echo Url::adminUrl("locations", "edit", false,"?id=" . $row->id);?>"><i class="rounded inverted positive icon pencil link"></i></a> <a href="<?php echo ADMINURL;?>/helper.php?exportWebspecials&store_id=<?php echo $row->store_id;?>&storename=<?php echo $row->name;?>"><i class="rounded inverted positive icon table link"></i></a> <a class="delete" data-set='{"title": "<?php echo Lang::$word->LOC_DELOC;?>", "parent": ".row", "option": "deleteLocation", "id": <?php echo $row->id;?>, "name": "<?php echo $row->name;?>"}'><i class="rounded inverted negative icon trash alt link"></i></a></div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<?php break;?>
<?php endswitch;?>