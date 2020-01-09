<?php
  /**
   * Contact
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2015
   * @version $Id: contact.tpl.php, v1.00 2015-08-05 10:16:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
	  
  $locations = $content->getLocations();
?>
<div class="wojo primary top bottom attached segment">
  <div id="map" style="height:350px"></div>
</div>
<div class="columns">
  <div class="screen-40 tablet-50 phone-100">
    <div class="padding eq wojo tertiary bg">
      <h3 class="wojo primary text"><?php echo Lang::$word->CONTACT;?></h3>
      <div class="wojo double space divider"></div>
      <div class="wojo celled list">
        <div class="item wojo primary text"><i class="icon marker"></i> <?php echo $core->address;?>, <?php echo $core->city;?>, <?php echo $core->state;?> <?php echo $core->zip;?> </div>
        <div class="item wojo primary text"><i class="icon phone"></i> <?php echo $core->phone;?></div>
        <div class="item wojo primary text"><i class="icon phone"></i> <?php echo $core->phone;?></div>
        <?php if($core->fax):?>
        <div class="item wojo primary text"><i class="icon fax"></i> <?php echo $core->fax;?></div>
        <?php endif;?>
        <div class="item wojo primary text"><i class="icon email"></i> <?php echo $core->site_email;?></div>
      </div>
      <div class="wojo double space divider"></div>
      <div class="wojo secondary text"> <?php echo Validator::cleanOut($data->result->body);?> </div>
    </div>
  </div>
  <div class="screen-60 tablet-50 phone-100">
    <div class="padding eq wojo secondary bg">
      <div class="wojo form">
        <form method="post" id="wojo_form" name="wojo_form">
          <div class="field">
            <label><?php echo Lang::$word->EMN_NLN;?></label>
            <label class="input"><i class="icon-prepend icon user"></i><i class="icon-append icon asterisk"></i>
              <input name="name" type="text" placeholder="<?php echo Lang::$word->EMN_NLN;?>" value="<?php echo ($auth->logged_in) ? $auth->name : null;?>">
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->EMN_NLE;?></label>
            <label class="input"><i class="icon-prepend icon email"></i> <i class="icon-append icon asterisk"></i>
              <input name="email" type="text" placeholder="<?php echo Lang::$word->EMN_NLE;?>" value="<?php echo ($auth->logged_in) ? $auth->email : null;?>">
            </label>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->MESSAGE;?></label>
            <label class="textarea">
              <textarea name="message" placeholder="<?php echo Lang::$word->MESSAGE;?>"></textarea>
            </label>
          </div>
          <div class="content-center">
            <button type="button" data-action="contactSite" data-clear="true" name="dosubmit" class="wojo negative rounded button"><?php echo Lang::$word->SUBMIT;?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php if($locations):?>
<div class="padding wojo primary bg">
  <h3 class="wojo header content-center"><?php echo Lang::$word->HOME_SUB10P;?></h3>
</div>
<div class="wojo primary bg">
  <div class="columns gutters">
    <?php foreach($locations as $row):?>
    <div class="screen-33 tablet-50 phone-100">
      <div class="wojo divided card">
        <div class="header">
          <div class="wojo circular small image"><img src="<?php echo UPLOADURL;?>showrooms/<?php echo ($row->logo) ? $row->logo : "blank.png";?>" alt=""></div>
          <div class="content"> <a href="<?php echo Url::doUrl(URL_SELLER, $row->name_slug);?>"><?php echo $row->name;?> </a>
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
          <div class="data"><?php echo $row->url;?></div>
        </div>
        <div class="item">
          <div class="intro"><i class="icon phone call"></i></div>
          <div class="data"><?php echo $row->phone;?></div>
        </div>
        <div class="item">
          <div class="intro"><i class="icon printer"></i></div>
          <div class="data"><?php echo $row->fax;?></div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<?php endif;?>
<script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo $core->mapapi;?>&v=3"></script> 
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    var geocoder;
    geocoder = new google.maps.Geocoder();
    var map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(43.652527, -79.381961),
        zoom: 15,
        mapTypeControl: false,
        streetViewControl: true,
        overviewMapControl: true,
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
    });
    var address = "<?php echo $core->address . ',' . $core->city . ',' . $core->state . ',' . $core->zip;?>";
    geocoder.geocode({
        'address': address
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var image = new google.maps.MarkerImage('<?php echo SITEURL;?>/assets/pin.png');
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                title: "<?php echo $core->address . ',' . $core->city . ',' . $core->state . ',' . $core->zip;?>",
                position: results[0].geometry.location,
                draggable: false,
                raiseOnDrag: false,
                icon: image
            });
        }
    });
});
// ]]>
</script>