<?php
/**
 * Web Specials Manager
*
* @package Wojo Framework
* @author Lorenzo Mateo
* @copyright 2017
* @version $Id: webspecials.php, v1.00 2017-09-08 9:38:05 gewa Exp $
*/
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
  
      
    
?>
<?php switch(Url::getAction()): case "edit": ?>
<?php if(!Auth::hasPrivileges('edit_items')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<?php if(!$row = $wSpecials->getWebspecialsById()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $data1 = $wSpecials->getPriceDiscounts();?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="note icon"></i>
    <div class="content">
      <div class="header"><?php echo Lang::$word->WSP_SUB1;?> <small> / <?php echo $row->title_ws;?></small> </div>
      <p><?php echo Lang::$word->WSP_INFO1 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
    <ul class="wojo tabs fixed clearfix">
      <li><a data-tab="#general" id="wssectionds" data-related="footer">New Car Web Special Section</a></li>
      <li><a data-tab="#disclaimer" id="disclaimerds" data-related="footer">Disclaimer</a></li>
      <li><a data-tab="#pricing" id="pricingds" data-related="footer">Pricing Discount Section</a></li> 
     </ul>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div id="general" class="wojo tab item">
      <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->WSP_ROOM;?></label>
          <select name="location">
            <option value="">-- <?php echo Lang::$word->WSP_ROOM_S;?> --</option>
            <?php echo Utility::loopOptions($content->getLocations(), "id", "name", $row->location);?>
          </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_SPECIALS_TYPE;?></label>
          <select name="specials_type">
            <option value="">-- Select Specials Type --</option>
            <?php echo Utility::loopOptions($content->getSpecialstype(), "id", "name", $row->specials_type);?>
          </select>
         <div class="wojo corner label"> <i class="icon asterisk"></i> </div> 
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_COND;?></label>
          <select name="vcondition">
            <option value="">-- <?php echo Lang::$word->WSP_COND_S;?> --</option>
            <?php echo Utility::loopOptions($content->getConditions(), "id", "name", $row->vcondition);?>
          </select>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_YEAR;?></label>
          <select name="year_id">
            <option value="">-- Select Year --</option>
            <?php echo Utility::loopOptions($content->getYear(), "id", "name", $row->year_id);?>
          </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        </div>
        <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->WSP_MAKE;?></label>
          <select name="make_id">
            <option value="">-- <?php echo Lang::$word->WSP_MAKE_S;?> --</option>
            <?php echo Utility::loopOptions($content->getMakesws(false), "id", "name", $row->make_id);?>
          </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_MODEL;?></label>
          <select name="model_id">
            <option value="">-- <?php echo Lang::$word->WSP_MODEL_S;?> --</option>
            <?php echo Utility::loopOptions($content->getModelListws($row->make_id), "id", "name", $row->model_id);?>
          </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_DEALTYPE;?></label>
          <select name="deal_type">
            <option value="">-- Select Deal Type --</option>
            <?php echo Utility::loopOptions($content->getDealtype(), "id", "name", $row->deal_type);?>
          </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_CAT;?></label>
          <select name="category">
            <option value="">-- <?php echo Lang::$word->WSP_CAT_S;?> --</option>
            <?php echo Utility::loopOptions($content->getBodyStyle(), "id", "name", $row->category);?>
             <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </select>
        </div>
        </div>
        <div class="four fields">
         <div class="field">
          <label>Title</label>
          <input type="text" value="<?php echo $row->title;?>" name="title">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_TRIM_LEVEL;?></label>
          <input type="text" value="<?php echo $row->trim_level;?>" name="trim_level">
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_TAGLINE;?></label>
          <input type="text" value="<?php echo $row->tagline;?>" name="tagline">
        </div>
         <div class="field">
          <label><?php echo Lang::$word->WSP_STOCK;?></label>
          <input type="text" value="<?php echo $row->stock_id;?>" name="stock_id">
        </div>
        </div>
        
         <div class="four fields">
         <div class="field">
          <label><?php echo Lang::$word->WSP_VIN;?></label>
          <input type="text" value="<?php echo $row->vin;?>" name="vin">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_MSRP_MSRP;?></label>
          <input type="number" value="<?php echo $row->msrp;?>" name="msrp">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_BUYPRICE;?></label>
          <input type="number" value="<?php echo $row->buy_price;?>" name="buy_price">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_SAVE_UP_TO_AMOUNT;?></label>
          <input type="number" value="<?php echo $row->save_up_to_amount;?>" name="save_up_to_amount">
        </div>
        
        </div>
        
        <div class="four fields">
         <div class="field">
          <label><?php echo Lang::$word->WSP_LEASE_OR_SINGLEPAYLEASE;?></label>
          <select name="single_zero_lease" id='single_zero_lease'>
            <option value="">-- Select Lease or Single Pay Lease... --</option>
            <?php echo Utility::loopOptions($content->getLease(), "id", "name", $row->single_zero_lease);?>
          </select>
        </div>
        
         <div id="leasePrice" class="field">
          <label><?php echo Lang::$word->WSP_LEASE_PRICE;?>/Month</label>
          <input type="number" value="<?php echo $row->lease_price;?>" name="lease_price">
        </div>
        
        <div id="leaseDownPayment" class="field">
          <label><?php echo Lang::$word->WSP_LEASE_DOWN_PAYMENT;?></label>
           <input type="number" value="<?php echo $row->lease_extras;?>" name="lease_extras">
        </div>
          <div id="leaseTerm" class="field">
          <label><?php echo Lang::$word->WSP_LEASE_TERM;?></label>
          <input type="number" value="<?php echo $row->lease_term;?>" name="lease_term">
        </div>
        
        <div id="singlePayLeasePrice" class="field">
          <label>Single Pay Lease Price</label>
           <input type="number" value="<?php echo $row->single_lease_price;?>" name="single_lease_price">
        </div>
          <div id="singlePayLeaseTerm" class="field">
          <label>Single Pay Lease Lease Term/Months</label>
          <input type="number" value="<?php echo $row->single_lease_term;?>" name="single_lease_term">
        </div>
          <div id="singlePayLeaseMiles" class="field">
          <label>Single Pay Lease Lease Miles</label>
          <input type="number" value="<?php echo $row->single_lease_miles;?>" name="single_lease_miles">
        </div>
        
         </div>
        
        <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->WSP_AVAILABLE_APR;?></label>
          <input type="number" step="0.01" value="<?php echo $row->available_apr;?>" name="available_apr">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_APR_TEXT;?></label>
          <input type="text" value="<?php echo $row->apr_text;?>" name="apr_text">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_ZERO_DOWN_LEASE_PRICE;?></label>
          <input type="number" step="0.01" value="<?php echo $row->zero_down_lease_price;?>" name="zero_down_lease_price">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_ZERO_DOWN_LEASE_TERM;?></label>
          <input type="number" step="0.01" value="<?php echo $row->zero_down_lease_term;?>" name="zero_down_lease_term">
        </div>
      </div>
      
      <div class="four fields">
      <div class="field">
         <label><?php echo Lang::$word->WSP_PRICE_W_LEASE_CONQUEST;?></label>
          <input type="number" step="0.01" value="<?php echo $row->price_with_lease_conquest;?>" name="price_with_lease_conquest">
       </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_PRICE_W_OWNER_LOYALTY;?></label>
          <input type="text" value="<?php echo $row->price_with_owner_loyalty;?>" name="price_with_owner_loyalty">
        </div>
        <div class="field">
          <label>Custom Label</label>
          <input type="text"  value="<?php echo $row->custom_price_label;?>" name="custom_price_label">
         </div>
        <div class="field">
        <label>Custom Price</label>
           <input type="number" step="0.01" value="<?php echo $row->custom_price_val;?>" name="custom_price_val">    
        </div>
      </div>
        
      <div class="four fields">
      <div class="field">
         <label><?php echo Lang::$word->WSP_VEHICLE_IMAGE_Url;?></label>
          <input type="text" value="<?php echo $row->vehicle_image;?>" name="vehicle_image">
       </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_MPG_MPG;?></label>
          <input type="number"  step="0.01" value="<?php echo $row->mpg;?>" name="mpg">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_INVENTORY_URL;?></label>
          <input type="text"  value="<?php echo $row->alt_link_url;?>" name="alt_link_url">
         </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_SALE_END_DATE;?></label>
          <label class="input"><i class="icon-prepend icon calendar"></i>
            <input data-datepicker="true" data-date="<?php echo $row->sale_end_date;?>" type="text" value="<?php echo Utility::dodate("short_date", $row->sale_end_date);?>" name="sale_end_date">
          </label>
        </div>
      </div>
      <div class="four fields">
      <div class="field">
         <label><?php echo Lang::$word->WSP_BRAND_LOGO_URL;?><i class="icon pin" data-content="<?php echo Lang::$word->WSP_IMAGE_MUST_BE_XSIZE;?>"></i></label>
          
          <input type="text" value="<?php echo $row->brand_logo_url;?>" name="brand_logo_url">
       </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_MOBILE_IMAGE_URL;?></label>
          <input type="text" value="<?php echo $row->new_fader_image_url;?>" name="new_fader_image_url">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_LANDING_PAGE_Url;?></label>
          <input type="text"  value="<?php echo $row->landing_page_url;?>" name="landing_page_url">
         </div>
       <div class="field">
          <label><?php echo Lang::$word->WSP_VDP_URL;?></label>
          <input type="text"  value="<?php echo $row->vdp_url;?>" name="vdp_url">
         </div>
      </div>
      <div class="four fields">
        <div class="field">
         <label><?php echo Lang::$word->WSP_YOUTUBE_VIDEO_ID;?></label>
          <input type="text" value="<?php echo $row->youtube_video_id;?>" name="youtube_video_id">
       </div>
        
           <div class="field">
          <label><?php echo Lang::$word->WSP_NCS_ORDERING;?></label>
          <input class="wojo range slider" type="range" min="1" max="20" step="1" name="ordering" value="<?php echo $row->ordering;?>">
        </div>
      
          <div class="field">
          <label><?php echo Lang::$word->WSP_LEASE_ORDERING;?></label>
          <input class="wojo range slider" type="range" min="1" max="20" step="1" name="lease_special_ordering" value="<?php echo $row->lease_special_ordering;?>">
        </div>
      
          <div class="field">
          <label><?php echo Lang::$word->WSP_ZERO_DOWN_LEASE_ORDERING;?></label>
          <input class="wojo range slider" type="range" min="1" max="20" step="1" name="zero_down_lease_special_ordering" value="<?php echo $row->zero_down_lease_special_ordering;?>">
        </div>
        
      </div>
      <div class="two fields">
       <div class="field">
         <div class="quarter-top-space"><a data-lightbox="true" data-title="<?php echo $row->title_ws;?>" href="<?php echo $row->vehicle_image;?>"><img src="<?php echo  $row->vehicle_image;?>" alt="" class="wojo grid image"></a> </div>
        </div>
      <div class="field">
        <label><?php echo Lang::$word->WSP_ON_NCS_PAGE;?><i class="icon pin" data-content="<?php echo Lang::$word->WSP_VIEW_NEW_CAR_SPECIAL;?>"></i></label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="active" <?php Validator::getChecked($row->active, 1); ?>>
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          </div>
      
       <div class="field">
        <label><?php echo Lang::$word->WSP_ON_LEASE_PAGE;?></label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="lease_special_active" <?php Validator::getChecked($row->lease_special_active, 1); ?>>
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          </div>
          
       
      <div class="field">
        <label><?php echo Lang::$word->WSP_ON_ZERO_DOWN_LEASE_PAGE;?></label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="zero_down_lease_special_active" <?php Validator::getChecked($row->zero_down_lease_special_active, 1); ?>>
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          </div>
      
      <div class="field">
        <label><?php echo Lang::$word->WSP_FEATURED_SPECIAL;?></label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="featured_special" <?php Validator::getChecked($row->featured_special, 1); ?>>
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          </div>
          </div>
    
    </div>
    <div id="disclaimer" class="wojo tab item">
      <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->WSP_DISCLAIMER_TEXT;?><i class="icon pin" data-content="<?php echo Lang::$word->WSP_DO_NOT_USE_DOUBLE_QUOTES;?>"></i></label>
        <textarea name="disclaimer_text"><?php echo htmlspecialchars($row->disclaimer_text, ENT_QUOTES, 'UTF-8');?></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->WSP_ZERO_DOWN_DISCLAIMER;?><i class="icon pin" data-content="<?php echo Lang::$word->WSP_DO_NOT_USE_DOUBLE_QUOTES;?>"></i></label>
       <textarea name="zero_down_lease_disclaimer_text"><?php echo htmlspecialchars($row->zero_down_lease_disclaimer_text, ENT_QUOTES, 'UTF-8');?></textarea>
      </div>
      </div>
      </div>
      
    <div class="wojo fitted divider"></div>
    <div id="footer" class="wojo footer">
   <button type="button" data-action="<?php echo ($row->location == 24 ? 'processWebspecialsTEST' : 'processWebspecials');?>" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->WSP_UPDATE;?></button>
   <a href="<?php echo Url::adminUrl("webspecials");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> 
      <input name="id" type="hidden" value="<?php echo Filter::$id;?>">
    </div>
    <div id="pricing" class="wojo tab item">
      <div class="addmore wojo form segment" style="display: none">
				<div id="container1" class="clonedInput">
					<div class="four fields">
						<div class="field">
							<label>Name</label>
							<div class="wojo labeled icon inputname">
								<input type="text" placeholder="Name" name="name[]" required>
								<div class="wojo corner label">
									<i class="icon asterisk"></i>
								</div>
							</div>
						</div>

						<div class="field">
							<label>Price</label>
							<div>
								<div class="wojo labeled icon inputpr">
									<input type="text" placeholder="Price" name="price[]" required>
									<div class="wojo corner label">
										<i class="icon asterisk"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="field">
							<label>Price Discount Ordering</label>
							<div>
								<div class="wojo labeled icon inputord">
									<input type="text" placeholder="Ordering" name="ordering1[]" required>
									<div class="wojo corner label">
										<i class="icon asterisk"></i>
									</div>
								</div>
							</div>
						</div>
					    <div class="field">
							<label>Active</label>
							<div>
								<div class="wojo labeled icon inputact">
									<div class="inline-group">
										<label class="checkbox"> <input type="checkbox" value="1"
											name="active1[]"> <i></i></label>
									</div>
									<div class="wojo corner label">
										<i class="icon asterisk"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<a class="wojo small primary button" id="dosubmit"><?php echo Lang::$word->ADDALL;?></a>
				<a id="btnAdd" class="wojo small positive button"><?php echo Lang::$word->ADD;?></a>
				<a id="btnDel" class="wojo small negative button"><?php echo Lang::$word->REMOVE;?></a>
                </div>
               <div id ="refreshDiv" class="wojo tertiary segment">
				 <div class="header clearfix"><span>(<?php echo $row->title_ws;?>) Pricing Discount</span> <a onclick="$('.addmore').slideToggle();" class="wojo large top right detached action label" data-content="Add New Pricing Discount"><i class="icon plus"></i></a> </div>
				<table  class="wojo table" id="editable">
			    <thead>
			      <tr>
			       <th class="disabled"></th>
			        <th class="disabled">#</th>
			        <th data-sort="string">Name</th>
			        <th data-sort="string">Price</th>
			        <th class="disabled">Ordering</th>
			        <th class="disabled">Active</th>
			        <th class="disabled">Delete</th>
			      </tr>
			    </thead>
			    <tbody>
			      <?php if(!$data1):?>
			      <tr>
			        <td colspan="7"><?php echo Message::msgSingleAlert("You don't have any pricing discounts yet. Please add...");?></td>
			      </tr>
			      <?php else:?>
			      <?php foreach ($data1 as $row1):?>
			      <tr data-id="<?php echo $row1->id;?>">
			      <td class="sorter"><i class="icon reorder"></i></td>
			      <td><small><?php echo $row1->id;?>.</small></td>
			       <td data-editable="true" data-set='{"type": "pricediscount", "id": <?php echo $row1->id;?>,"key":"name", "path":""}'><?php echo $row1->name;?></td>
			       <td data-editable="true" data-set='{"type": "pricediscount", "id": <?php echo $row1->id;?>,"key":"price", "path":""}'><?php echo $row1->price;?></td>
			       <td data-editable="true" data-set='{"type": "pricediscount", "id": <?php echo $row1->id;?>,"key":"ordering", "path":""}'><?php echo $row1->ordering;?></td>
			       <td <div class="data"> <a class="doStatus" data-set='{"field": "status", "table": "PriceDiscounts", "toggle": "check ban", "togglealt": "positive purple", "id": <?php echo $row1->id;?>, "value": "<?php echo $row1->active;?>"}' data-content="<?php echo Lang::$word->STATUS;?>"><i class="rounded inverted <?php echo ($row1->active) ? "check positive" : "ban purple";?> icon link"></i></a> </td>
			     <td><a class="delete" data-set='{"title": "Delete Pricing Discount", "parent": "tr", "option": "deletePricingDiscount", "id": <?php echo $row1->id;?>, "name": "<?php echo $row1->name;?>"}'><i class="rounded outline icon negative trash link"></i></a></td>
			      </tr>
			      <?php endforeach;?>
			      <?php unset($row1);?>
			      <?php endif;?>
			    </tbody>
			  </table>
			 
			  <div class="footer">
			    <div class="wojo tabular segment">
			      <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
			      <div class="wojo cell left"> <a id="reorderpd" <a href="<?php echo Url::adminUrl("webspecials", "edit", false,"?id=" . Filter::$id);?>" class="wojo secondary button"> <i class="reorder icon"></i> Re-Order Price Discount </a></div>
			      <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
			    </div>
			  </div>
			</div>
			
			</div>
		</div>
    
    </form>
  
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
	$('select[name=make_id]').on('change', function () {
		var option = $(this).val();
		$.ajax({
		  url: ADMINURL + "/helper.php",
		  data: {getMakewslist: option},
		  dataType: "json",
		  success: function (json) {
			  $('select[name=model_id]').html(json.message).selecter("update");
			  $('.selecter-options').scroller("destroy").scroller();
		  }
		});
});

$('#btnAdd').click(function () {
        var num = $('.clonedInput').length;
        var newNum = new Number(num + 1);
        var newElem = $('#container' + num).clone().attr('id', 'container' + newNum);
        $('#container' + num).after(newElem);
        $('#btnDel').show();
        if (newNum == 15) $('#btnAdd').hide();
    });
    $('#btnDel').click(function () {
        var num = $('.clonedInput').length;
        $('#container' + num).remove();
        $('#btnAdd').show();
        if (num - 1 == 1) $('#btnDel').hide();
    });
    $('#btnDel').hide();

    $('a#dosubmit').on('click', function() {
        var values = $('.inputname :input').serialize()+"&"+$('.inputpr :input').serialize()+"&"+$('.inputord :input').serialize()+"&"+$('.inputact :input').serialize();
        values+= "&processPriceDiscounts=1";
        values += "&id=<?php echo Filter::$id;?>"
        values += "&action=processPriceDiscounts"
        $.ajax({
            type: 'post',
            url: ADMINURL + "/controller.php",
            dataType: 'json',
            data: values,
            success: function(json) {
                if (json.type == "success") {
                    //alert(data);
                    $(".wojo.info.message").remove();
                    $(json.data).insertBefore('.wojo.table tbody tr:first');
                    $(".addmore").slideUp();
                    $(".inputname  :input").val(''); 
                    $(".inputpr  :input").val('');
                    $(".inputord  :input").val('');
                    $(".inputact  :input").val('');
                    
                    
                }
                $.sticky(decodeURIComponent(json.message), {
                    type: json.type,
                    title: json.title
                    
                });
            }
        });
        
    });

    $(".wojo.table").rowSorter({
            handler: "td.sorter",
            onDrop: function() {
                var data = [];
                $('.wojo.table tbody tr').each(function() {
                    data.push($(this).data("id"))
                   
                });
                $.ajax({
                    type: "post",
                    url: ADMINURL + "/helper.php",
                    data: {
                        ordering: data,
                        sortpricediscount: 1
                   
                    }    
                });
            }
        });

    
     toggleFields(); 

    $('#single_zero_lease').change(function () {
   	   
   	   var val = $(this).val();
   	   if (val == 1) {
   	        
   	        $('#singlePayLeasePrice').hide();
   	        $('#singlePayLeaseTerm').hide();
   	        $('#singlePayLeaseMiles').hide();
   	        $('#single_lease_price').val('');
   	        $('#single_lease_term').val('');
   	        $('#single_lease_miles').val('');
   	        $('#leasePrice').show();
   	        $('#leaseDownPayment').show();
   	        $('#leaseTerm').show();
   	        
   	       
   	        
   	   } else if (val == 2) {
   		   $('#leasePrice').hide();
   	       $('#leaseDownPayment').hide();
   	       $('#leaseTerm').hide();
   	       $('#lease_price').val('');
   	       $('#lease_extras').val('');
   	       $('#lease_term').val('');
   	        
   	       $('#singlePayLeasePrice').show();
   	        $('#singlePayLeaseTerm').show();
   	        $('#singlePayLeaseMiles').show(); 
   	        
   	   }
      else {

   	   $('#leasePrice').hide();
          $('#leaseDownPayment').hide();
          $('#leaseTerm').hide();
          $('#singlePayLeasePrice').hide();
          $('#singlePayLeaseTerm').hide();
          $('#singlePayLeaseMiles').hide();
          
          }
      
      });  

    
    $("a#pricingds").on("click", function() {
  	  $("div[id=" + $(this).attr("data-related") + "]").hide();
  	}); 

    $("a#wssectionds").on("click", function() {
    	  $("div[id=" + $(this).attr("data-related") + "]").show();
    	}); 

    $("a#disclaimerds").on("click", function() {
  	  $("div[id=" + $(this).attr("data-related") + "]").show();
  	}); 

   
  	
 });    
function toggleFields() {

	   if ($("#single_zero_lease").val() == 2){
		   $('#leasePrice').hide();
	       $('#leaseDownPayment').hide();
	       $('#leaseTerm').hide();
	   
	   $('#singlePayLeasePrice').show();
	   $('#singlePayLeaseTerm').show();
	   $('#singlePayLeaseMiles').show();

	   } else if ($("#single_zero_lease").val() == 1){
		   $('#leasePrice').show();
	       $('#leaseDownPayment').show();
	       $('#leaseTerm').show();
	       $('#singlePayLeasePrice').hide();
	       $('#singlePayLeaseTerm').hide();
	       $('#singlePayLeaseMiles').hide();

	   }
	   else {

		   $('#leasePrice').hide();
	       $('#leaseDownPayment').hide();
	       $('#leaseTerm').hide();
	       $('#singlePayLeasePrice').hide();
	       $('#singlePayLeaseTerm').hide();
	       $('#singlePayLeaseMiles').hide();
	       
	       }
	}

  	

// ]]>
</script>
<?php break;?>
<?php case"add": ?>
<?php if(!Auth::hasPrivileges('add_items')): print Message::msgError(Lang::$word->NOACCESS); return; endif;?>
<div class="wojo form segment">
  <div class="wojo secondary icon message"> <i class="check icon"></i>
    <div class="content">
      <div class="header"> <?php echo Lang::$word->WSP_SUB2;?></div>
      <p><?php echo Lang::$word->WSP_INFO2 . Lang::$word->REQFIELD1 . '<i class="icon small middle asterisk"></i>' . Lang::$word->REQFIELD2;?></p>
    </div>
    <ul class="wojo tabs fixed clearfix">
      <li><a data-tab="#general">New Car Web Special Section</a></li>
      <li><a data-tab="#disclaimer">Disclaimer</a></li>
     <!--  <li><a data-tab="#pricing">Pricing Discount Section</a></li> -->
   </ul>
  </div>
  <form method="post" id="wojo_form" name="wojo_form">
    <div id="general" class="wojo tab item">
      <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->WSP_ROOM;?></label>
          <select name="location">
            <option value="">-- <?php echo Lang::$word->WSP_ROOM_S;?> --</option>
            <?php echo Utility::loopOptions($content->getLocations(), "id", "name");?>
          </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_SPECIALS_TYPE;?></label>
          <select name="specials_type">
            <option value="">-- Select Specials Type --</option>
            <?php echo Utility::loopOptions($content->getSpecialstype(), "id", "name");?>
          </select>
         <div class="wojo corner label"> <i class="icon asterisk"></i> </div> 
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_COND;?></label>
          <select name="vcondition">
            <option value="">-- <?php echo Lang::$word->WSP_COND_S;?> --</option>
            <?php echo Utility::loopOptions($content->getConditions(), "id", "name");?>
          </select>
          <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_YEAR;?></label>
          <select name="year_id">
            <option value="">-- Select Year --</option>
            <?php echo Utility::loopOptions($content->getYear(), "id", "name");?>
          </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        </div>
        <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->WSP_MAKE;?></label>
          <select name="make_id">
            <option value="">-- <?php echo Lang::$word->WSP_MAKE_S;?> --</option>
            <?php echo Utility::loopOptions($content->getMakesws(false), "id", "name");?>
          </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_MODEL;?></label>
          <select name="model_id">
            <option value="">-- <?php echo Lang::$word->WSP_MODEL_S;?> --</option>
            </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_DEALTYPE;?></label>
          <select name="deal_type">
            <option value="">-- Select Deal Type --</option>
            <?php echo Utility::loopOptions($content->getDealtype(), "id", "name");?>
          </select>
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_CAT;?></label>
          <select name="category">
            <option value="">-- <?php echo Lang::$word->WSP_CAT_S;?> --</option>
            <?php echo Utility::loopOptions($content->getBodyStyle(), "id", "name");?>
             <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
          </select>
        </div>
        </div>
        <div class="four fields">
         <div class="field">
          <label>Title</label>
          <input type="text" placeholder="Title" name="title">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_TRIM_LEVEL;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_TRIM_LEVEL;?>" name="trim_level">
           <div class="wojo corner label"> <i class="icon asterisk"></i> </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_TAGLINE;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_TAGLINE;?>" name="tagline">
        </div>
         <div class="field">
          <label><?php echo Lang::$word->WSP_STOCK;?></label>
          <input type="text"  placeholder="<?php echo Lang::$word->WSP_STOCK;?>" name="stock_id">
        </div>
        </div>
        
         <div class="four fields">
         <div class="field">
          <label><?php echo Lang::$word->WSP_VIN;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_VIN;?>" name="vin">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_MSRP_MSRP;?></label>
          <input type="number" placeholder="<?php echo Lang::$word->WSP_MSRP_MSRP;?>" name="msrp">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_BUYPRICE;?></label>
          <input type="number" placeholder="<?php echo Lang::$word->WSP_BUYPRICE;?>" name="buy_price">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_SAVE_UP_TO_AMOUNT;?></label>
          <input type="number" placeholder="<?php echo Lang::$word->WSP_SAVE_UP_TO_AMOUNT;?>" name="save_up_to_amount">
        </div>
        
        </div>
        
        <div class="four fields">
         <div class="field">
          <label><?php echo Lang::$word->WSP_LEASE_OR_SINGLEPAYLEASE;?></label>
          <select name="single_zero_lease" id='single_zero_lease'>
            <option value="">-- Select Lease or Single Pay Lease... --</option>
            <?php echo Utility::loopOptions($content->getLease(), "id", "name");?>
          </select>
        </div>
        
         <div id="leasePrice" class="field">
          <label><?php echo Lang::$word->WSP_LEASE_PRICE;?>/Month</label>
          <input type="number" placeholder="<?php echo Lang::$word->WSP_LEASE_PRICE;?>/Month" name="lease_price">
        </div>
        
        <div id="leaseDownPayment" class="field">
          <label><?php echo Lang::$word->WSP_LEASE_DOWN_PAYMENT;?></label>
           <input type="number" placeholder="<?php echo Lang::$word->WSP_LEASE_DOWN_PAYMENT;?>" name="lease_extras">
        </div>
          <div id="leaseTerm" class="field">
          <label><?php echo Lang::$word->WSP_LEASE_TERM;?></label>
          <input type="number" placeholder="<?php echo Lang::$word->WSP_LEASE_TERM;?>" name="lease_term">
        </div>
        
        <div id="singlePayLeasePrice" class="field">
          <label>Single Pay Lease Price</label>
           <input type="number" placeholder="Single Pay Lease Price" name="single_lease_price">
        </div>
          <div id="singlePayLeaseTerm" class="field">
          <label>Single Pay Lease Term/Months</label>
          <input type="number" placeholder="Single Pay Lease Term/Months" name="single_lease_term">
        </div>
          <div id="singlePayLeaseMiles" class="field">
          <label>Single Pay Lease Miles</label>
          <input type="number" placeholder="Single Pay Lease Lease Miles" name="single_lease_miles">
        </div>
        
         </div>
        
        <div class="four fields">
        <div class="field">
          <label><?php echo Lang::$word->WSP_AVAILABLE_APR;?></label>
          <input type="number" step="0.01" placeholder="<?php echo Lang::$word->WSP_AVAILABLE_APR;?>" name="available_apr">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_APR_TEXT;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_APR_TEXT;?>" name="apr_text">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_ZERO_DOWN_LEASE_PRICE;?></label>
          <input type="number" step="0.01" placeholder="<?php echo Lang::$word->WSP_ZERO_DOWN_LEASE_PRICE;?>" name="zero_down_lease_price">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_ZERO_DOWN_LEASE_TERM;?></label>
          <input type="number" step="0.01" placeholder="<?php echo Lang::$word->WSP_ZERO_DOWN_LEASE_TERM;?>" name="zero_down_lease_term">
        </div>
      </div>
      
      <div class="four fields">
      <div class="field">
         <label><?php echo Lang::$word->WSP_PRICE_W_LEASE_CONQUEST;?></label>
          <input type="number" step="0.01" placeholder="<?php echo Lang::$word->WSP_PRICE_W_LEASE_CONQUEST;?>" name="price_with_lease_conquest">
       </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_PRICE_W_OWNER_LOYALTY;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_PRICE_W_OWNER_LOYALTY;?>" name="price_with_owner_loyalty">
        </div>
        <div class="field">
          <label>Custom Label</label>
          <input type="text"  placeholder="Custom Label" name="custom_price_label">
         </div>
        <div class="field">
        <label>Custom Price</label>
           <input type="number" step="0.01" placeholder="Custom Price" name="custom_price_val">    
        </div>
      </div>
        
      <div class="four fields">
      <div class="field">
         <label><?php echo Lang::$word->WSP_VEHICLE_IMAGE_Url;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_VEHICLE_IMAGE_Url;?>" name="vehicle_image">
       </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_MPG_MPG;?></label>
          <input type="number"  step="0.01" placeholder="<?php echo Lang::$word->WSP_MPG_MPG;?>" name="mpg">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_INVENTORY_URL;?></label>
          <input type="text"  placeholder="<?php echo Lang::$word->WSP_INVENTORY_URL;?>" name="alt_link_url">
         </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_SALE_END_DATE;?></i></label>
          <label class="input"><i class="icon-prepend icon calendar"></i>
            <input data-datepicker="true" type="text" name="sale_end_date">
          </label>
        </div>
      </div>
      <div class="four fields">
      <div class="field">
         <label><?php echo Lang::$word->WSP_BRAND_LOGO_URL;?><i class="icon pin" data-content="<?php echo Lang::$word->WSP_IMAGE_MUST_BE_XSIZE;?>"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_BRAND_LOGO_URL;?>" name="brand_logo_url">
       </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_MOBILE_IMAGE_URL;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_MOBILE_IMAGE_URL;?>" name="new_fader_image_url">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->WSP_LANDING_PAGE_Url;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_LANDING_PAGE_Url;?>" name="landing_page_url">
         </div>
       <div class="field">
          <label><?php echo Lang::$word->WSP_VDP_URL;?></label>
          <input type="text"  placeholder="<?php echo Lang::$word->WSP_VDP_URL;?>" name="vdp_url">
         </div>
      </div>
      <div class="four fields">
        <div class="field">
         <label><?php echo Lang::$word->WSP_YOUTUBE_VIDEO_ID;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->WSP_YOUTUBE_VIDEO_ID;?>" name="youtube_video_id">
       </div>
        
           <div class="field">
          <label><?php echo Lang::$word->WSP_NCS_ORDERING;?></label>
          <input class="wojo range slider" type="range" min="1" max="20" step="1" name="ordering"">
        </div>
      
          <div class="field">
          <label><?php echo Lang::$word->WSP_LEASE_ORDERING;?></label>
          <input class="wojo range slider" type="range" min="1" max="20" step="1" name="lease_special_ordering">
        </div>
      
          <div class="field">
          <label><?php echo Lang::$word->WSP_ZERO_DOWN_LEASE_ORDERING;?></label>
          <input class="wojo range slider" type="range" min="1" max="20" step="1" name="zero_down_lease_special_ordering">
        </div>
        
      </div>
      <div class="two fields">
       <div class="field">
        <label><?php echo Lang::$word->WSP_ON_NCS_PAGE;?><i class="icon pin" data-content="<?php echo Lang::$word->WSP_VIEW_NEW_CAR_SPECIAL;?>"></i></label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="active" >
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          </div>
      
       <div class="field">
        <label><?php echo Lang::$word->WSP_ON_LEASE_PAGE;?></label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="lease_special_active">
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          </div>
           </div>
      <div class="two fields">     
      <div class="field">
        <label><?php echo Lang::$word->WSP_ON_ZERO_DOWN_LEASE_PAGE;?></label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="zero_down_lease_special_active">
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          </div>
      
      <div class="field">
        <label><?php echo Lang::$word->WSP_FEATURED_SPECIAL;?></label>
         <div class="inline-group">
           <label class="checkbox">
              <input type="checkbox" value="1" name="featured_special">
              <i></i><?php echo Lang::$word->ACTIVE;?> </label>
          </div>
          </div>
         </div>
     
    </div>
    <div id="disclaimer" class="wojo tab item">
      <div class="two fields">
      <div class="field">
        <label><?php echo Lang::$word->WSP_DISCLAIMER_TEXT;?><i class="icon pin" data-content="<?php echo Lang::$word->WSP_DO_NOT_USE_DOUBLE_QUOTES;?>"></i></label>
        <textarea name="disclaimer_text"  placeholder="<?php echo Lang::$word->WSP_DISCLAIMER_TEXT;?>" class="bodypost"></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->WSP_ZERO_DOWN_DISCLAIMER;?><i class="icon pin" data-content="<?php echo Lang::$word->WSP_DO_NOT_USE_DOUBLE_QUOTES;?>"></i></label>
       <textarea name="zero_down_lease_disclaimer_text" placeholder="<?php echo Lang::$word->WSP_ZERO_DOWN_DISCLAIMER;?>" class="bodypost"></textarea>
      </div>
      </div>
    </div>
    </div>
    <div class="wojo fitted divider"></div>
    <div class="wojo footer">
      <button type="button" data-action="processWebspecials" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->WSP_ADD;?></button>
      <a href="<?php echo Url::adminUrl("webspecials");?>" class="wojo button"><?php echo Lang::$word->CANCEL;?></a> </div>
    <!--  <input name="is_owner" type="hidden" value="1">-->
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
                getMakewslist: make
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


toggleFields(); 

$('#single_zero_lease').change(function () {
	   
	   var val = $(this).val();
	   if (val == 1) {
	        
	        $('#singlePayLeasePrice').hide();
	        $('#singlePayLeaseTerm').hide();
	        $('#singlePayLeaseMiles').hide();
	        $('#single_lease_price').val('');
	        $('#single_lease_term').val('');
	        $('#single_lease_miles').val('');
	        $('#leasePrice').show();
	        $('#leaseDownPayment').show();
	        $('#leaseTerm').show();
	        
	       
	        
	   } else if (val == 2) {
		   $('#leasePrice').hide();
	       $('#leaseDownPayment').hide();
	       $('#leaseTerm').hide();
	       $('#lease_price').val('');
	       $('#lease_extras').val('');
	       $('#lease_term').val('');
	        
	       $('#singlePayLeasePrice').show();
	        $('#singlePayLeaseTerm').show();
	        $('#singlePayLeaseMiles').show(); 
	        
	   }
  else {

	   $('#leasePrice').hide();
      $('#leaseDownPayment').hide();
      $('#leaseTerm').hide();
      $('#singlePayLeasePrice').hide();
      $('#singlePayLeaseTerm').hide();
      $('#singlePayLeaseMiles').hide();
      
      }
  
  });  
});  
 
function toggleFields() {

   if ($("#single_zero_lease").val() == 2){
	   $('#leasePrice').hide();
       $('#leaseDownPayment').hide();
       $('#leaseTerm').hide();
   
   $('#singlePayLeasePrice').show();
   $('#singlePayLeaseTerm').show();
   $('#singlePayLeaseMiles').show();

   } else if ($("#single_zero_lease").val() == 1){
	   $('#leasePrice').show();
       $('#leaseDownPayment').show();
       $('#leaseTerm').show();
       $('#singlePayLeasePrice').hide();
       $('#singlePayLeaseTerm').hide();
       $('#singlePayLeaseMiles').hide();

   }
   else {

	   $('#leasePrice').hide();
       $('#leaseDownPayment').hide();
       $('#leaseTerm').hide();
       $('#singlePayLeasePrice').hide();
       $('#singlePayLeaseTerm').hide();
       $('#singlePayLeaseMiles').hide();
       
       }
} 

// ]]>
</script>
<?php break;?>
<?php case"webspecialsalert": ?>
<?php if(!$row = $wSpecials->getWebspecialsPreview()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $data = $wSpecials->getWebSpecialsAlert();?>
<div class="wojo secondary icon message"><i class="car icon"></i></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->WSALERT_TITLE;?> <small> / <?php echo $row->title_ws;?></small> </div>
    <p><?php echo Lang::$word->WSALERT_INFO;?></p>
  </div>
</div>
<div class="clearfix bottom-space"> <a class="wojo right labeled icon secondary button push-right" onclick="javascript:void window.open('<?php echo ADMINURL;?>/printwsch.php?id=<?php echo Filter::$id;?>','printer','width=880,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0'); return false;"><i class="icon printer"></i><?php echo Lang::$word->PRINT;?></a> </div>
<!--  <div class="clearfix bottom-space"> <a class="wojo right labeled icon secondary button push-right" onclick="javascript:void window.open('<?php echo ADMINURL;?>/printTest.php','printer','width=880,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0'); return false;"><i class="icon printer"></i><?php echo Lang::$word->PRINT;?></a> </div>-->
<div class="columns double-gutters" id="printArea">
  <div class="screen-50 tablet-100 phone-100">
  <div class="content-center"><img src="<?php echo $row->vehicle_image;?>" alt=""></div>
    <br>
   <div class="content-center"><?php echo $row->new_used_ws . ' ' . '(' .$row->year .') '. $row->nice_title_ws .' '. $row->trim_level;?></div>
   <div class="wojo divider"></div>
    <div class="content-center"><img src="<?php echo UPLOADURL;?>showrooms/<?php echo ($row->logo) ? $row->logo : "blank.png";?>" alt=""></div>
  </div>
  <div class="screen-50 tablet-100 phone-100">
    <table class="wojo grid table">
      <thead>
        <tr>
	        <th align="left"></th>
	        <th><?php echo Lang::$word->WSALERT_FIELD;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_NEW;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_OLD;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_BY;?></th>
	        <th><?php echo Lang::$word->WSALERT_CH_ON;?></th>
        </tr>
      </thead>
      </tr>
       <?php if(!$data):?>
		<tr>
	    <td colspan="7"><?php echo Message::msgSingleAlert("No Web Specials Alerts at this Time.");?></td>
	    </tr>
	    <?php else:?>
	    <?php foreach ($data as $row2):?>
	    <?php if (Utility::dodate("short_date", $row2->modified) == Utility::dodate("short_date",Utility::today())):?>
			      <tr>
			        <td  align="left" style="background-color:yellow">(New Update)</td>
			        <td style="background-color:yellow"><?php echo $row2->col;?></td>
			        <td style="background-color:yellow"><?php echo $row2->changeFrom;?></td>
			        <td style="background-color:yellow"><?php echo $row2->changeTo;?></td>
			        <td style="background-color:yellow"><?php echo $row2->username;?></td>
			        <td style="background-color:yellow"><?php echo Utility::dodate("short_date", $row2->modified);?></td>
			      </tr>
			       <?php else:?>
			       <tr>
			       <td align="left"></td>
			        <td><?php echo $row2->col;?></td>
			        <td><?php echo $row2->changeFrom;?></td>
			        <td><?php echo $row2->changeTo;?></td>
			        <td><?php echo $row2->username;?></td>
			        <td><?php echo Utility::dodate("short_date", $row2->modified);?></td>
			      </tr>
			      <?php endif;?>
			      <?php endforeach;?>
			      <?php unset($row);?>
			      <?php unset($row2);?>
			      <?php endif;?>
     			 <tr> 
     			   
    </table>
  </div>
</div>
<?php break;?>
<?php case"printws": ?>
<?php if(!$row = $wSpecials->getWebspecialsPreview()) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $locdata = $row->storename_ws;?>
<div class="wojo secondary icon message"> <i class="printer icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->LPR_SUB;?> <small> / <?php echo $row->title_ws;?></small> </div>
    <p><?php echo Lang::$word->LPR_INFO;?></p>
  </div>
</div>
<div class="clearfix bottom-space"> <a class="wojo right labeled icon secondary button push-right" onclick="javascript:void window.open('<?php echo ADMINURL;?>/printws.php?id=<?php echo Filter::$id;?>','printer','width=880,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0'); return false;"><i class="icon printer"></i><?php echo Lang::$word->PRINT;?></a> </div>
<div class="columns double-gutters" id="printArea">
  <div class="screen-50 tablet-100 phone-100">
   <div class="content-center"><img src="<?php echo $row->vehicle_image;?>" alt=""></div>
   <div class="wojo divider"></div>
    <div class="content-center"><img src="<?php echo UPLOADURL;?>showrooms/<?php echo ($row->logo) ? $row->logo : "blank.png";?>" alt=""></div>
  </div>
  <div class="screen-50 tablet-100 phone-100">
    <table class="wojo grid table">
      <thead>
        <tr>
          <th colspan="2"><?php echo $row->new_used_ws . ' ' . '(' .$row->year .') '. $row->nice_title_ws .' '. $row->trim_level;?></th>
        </tr>
      </thead>
       <tr>
        <td><?php echo Lang::$word->WSP_STOCK;?></td>
        <td><?php echo Validator::has($row->stock_number);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_COND;?></td>
        <td><?php echo $row->new_used_ws;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_PRICE;?></td>
        <td><?php echo Validator::has(Utility::formatMoney($row->buy_price));?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_ROOM;?></td>
        <td><?php echo $row->storename_ws;?></td>
      </tr>
       <tr>
        <td><?php echo Lang::$word->EMAIL;?></td>
        <td><?php echo $row->email;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->CF_PHONE;?></td>
        <td><?php echo Validator::has($row->phone);?></td>
      </tr>
     <!-- <tr>
        <td><?php echo Lang::$word->WSP_INTC;?></td>
        <td><?php echo Validator::has($row->color_i);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_EXTC;?></td>
        <td><?php echo Validator::has($row->color_e);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_DOORS;?></td>
        <td><?php echo Validator::has($row->doors);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_ENGINE;?></td>
        <td><?php echo Validator::has($row->engine);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_TRANS;?></td>
        <td><?php echo $row->trans_name;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_FUEL;?>:</td>
        <td><?php echo $row->fuel_name;?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_TRAIN;?>:</td>
        <td><?php echo Validator::has($row->drive_train);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_SPEED;?>:</td>
        <td><?php echo Validator::has($row->top_speed);?> <?php echo ($core->odometer == "km") ? 'km/h' : 'mph';?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_POWER;?></td>
        <td><?php echo Validator::has($row->horse_power);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_TORQUE;?></td>
        <td><?php echo Validator::has($row->torque);?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->WSP_TOWING;?></td>
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
      </tr> -->
    </table>
  </div>
</div>
<?php break;?>
<?php case"view": ?>
<?php $data = $wSpecials->getWebspecials(false, "view");?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->WSP_TITLE;?></div>
    <p><?php echo Lang::$word->WSP_INFO;?></p>
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
            <div class="field"> <a href="<?php echo Url::adminUrl("webspecials", false);?>" class="wojo right labeled icon fluid secondary button"> <i class="reorder icon"></i> <?php echo Lang::$word->LIST;?> </a> </div>
          </div>
        </div>
      </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("webspecials/view");?>" name="wojo_form">
        <div class="four fields">
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
           <select name="store_letter_view" id="store_letter_view" >
            <option value="">-- <?php echo Lang::$word->WSP_ROOM_S;?> --</option>
            <?php echo Utility::loopOptions($content->getLocations(),"store_id","name");?>
          </select>
          <!--  <div class="wojo corner label"> <i class="icon asterisk"></i> </div> -->
        </div>
          <div class="field">
            <div class="wojo icon input">
              <input type="text" name="webspecialssearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
    </div>
   </form>
    <script type='text/javascript'>
       $(document).ready(function(e){
    	    $("#store_letter_view").change(function(e){
        	    e.preventDefault(); // avoid to execute the actual submit of the form.
    	        var sletter = $('#store_letter_view').val();
    	        $.post("<?php echo Url::adminUrl("webspecials", "dealership-view");?>" ,$('#wojo_form').serialize(),function(data){
    	            //alert(data);
    	            window.location.href = "<?php echo Url::adminUrl("webspecials", "dealership-view", false,"?id=");?>" +sletter;
                    
    	        });
    	    });
    	});
      
       
       </script>
    </div>
    <div class="content-center">
      <div class="wojo divided horizontal link list">
        <div class="disabled item"> <?php echo Lang::$word->SORTING_O;?> </div>
        <a href="<?php echo Url::adminUrl("webspecials/view");?>" class="item<?php echo Url::setActive("order", false);?>"> <?php echo Lang::$word->DEFAULT;?> </a> <a href="<?php echo Url::adminUrl("webspecials/view", false, false, "?order=title_ws/DESC");?>" class="item<?php echo Url::setActive("order", "title_ws");?>"> <?php echo Lang::$word->WSP_NAME;?> </a> <a href="<?php echo Url::adminUrl("webspecials/view", false, false, "?order=new_used/DESC");?>" class="item<?php echo Url::setActive("order", "new_used");?>"> <?php echo Lang::$word->WSP_NEW_USED;?> </a> <a href="<?php echo Url::adminUrl("webspecials/view", false, false, "?order=specials_type/DESC");?>" class="item<?php echo Url::setActive("order", "specials_type");?>"> <?php echo Lang::$word->WSP_SPECIALS_TYPE;?> </a> <a href="<?php echo Url::adminUrl("webspecials/view", false, false, "?order=body_style/DESC");?>" class="item<?php echo Url::setActive("order", "body_style");?>"> <?php echo Lang::$word->WSP_BODYSTYLE;?> </a> <a href="<?php echo Url::adminUrl("webspecials/view", false, false, "?order=year/DESC");?>" class="item<?php echo Url::setActive("order", "year");?>"> #<?php echo Lang::$word->WSP_YEAR;?> </a>
        <div class="item" data-content="ASC/DESC"><a href="<?php echo Url::sortItems(Url::adminUrl("webspecials/view"), "order");?>"><i class="icon unfold more link"></i></a> </div>
      </div>
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBits(Url::adminUrl("webspecials", "view"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<?php if(Auth::hasPrivileges('add_items')):?>
<div class="clearfix"> <a class="wojo right labeled icon secondary button push-right" href="<?php echo Url::adminUrl("webspecials", "add");?>"><i class="icon plus"></i><?php echo Lang::$word->WSP_ADD;?></a> </div>
<?php endif;?>
<?php if(!$data):?>
<?php echo Message::msgSingleAlert(Lang::$word->WSP_NOLIST);?>
<?php else:?>
<div class="wojo space divider"></div>
<div class="columns gutters">
  <?php foreach ($data as $row):?>
  <div class="row screen-50 tablet-50 phone-100">
    <div class="wojo divided card">
      <div class="header">
        <div class="wojo top right small attached label"><?php echo $row->webspecials_id;?>.</div>
        <div class="wojo rounded primary small grid image"><a data-lightbox="true" data-title="<?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?>" href="<?php echo $row->vehicle_image;?>"><img src="<?php echo $row->vehicle_image;?>" alt=""></a></div>
        <div class="content">
          <?php if(Auth::hasPrivileges('edit_items')):?>
          <a href="<?php echo Url::adminUrl("webspecials", "edit", false,"?id=" . $row->webspecials_id);?>"><?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?> </a> 
          <?php else:?>
          <?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?>
          <?php endif;?>
          <p><?php echo Lang::$word->CREATED;?>: <?php echo Utility::dodate("long_date", $row->created_ws);?></p>
        </div>
      </div>
       <div class="item">
        <div class="intro"><?php echo Lang::$word->WSP_ROOM;?>:</div>
        <div class="data"><?php echo $row->storename_ws;?></div>
      </div>
     <div class="item">
        <div class="intro"><?php echo Lang::$word->WSP_PRICE;?>:</div>
        <div class="data"><?php echo Utility::formatMoney($row->buy_price);?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->WSP_STOCK;?>:</div>
        <div class="data"><?php echo $row->stock_number;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->LST_CAT;?>:</div>
        <div class="data"><?php echo $row->body_style_code;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->WSP_YEAR;?>:</div>
        <div class="data"><?php echo $row->year;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->MODIFIED;?>:</div>
        <div class="data"><?php echo ($row->modified_ws <> 0) ? Utility::dodate("short_date", $row->modified_ws): '- ' . Lang::$word->NEVER . ' -'?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->MODIFIED;?> <?php echo Lang::$word->BY;?>:</div>
        <div class="data"> <?php echo $row->username;?></div>
      </div>
      <div class="actions">
        <div class="item">
          <div class="intro"><?php echo Lang::$word->ACTIONS;?>:</div>
          <div class="data"> <a class="doStatus" data-set='{"field": "status", "table": "Webspecials", "toggle": "check ban", "togglealt": "positive purple", "id": <?php echo $row->webspecials_id;?>, "value": "<?php echo $row->active;?>"}' data-content="<?php echo Lang::$word->ACTIVE;?>"><i class="rounded inverted <?php echo ($row->active) ? "check positive" : "ban purple";?> icon link"></i></a> <a class="doStatus" data-set='{"field": "featured", "table": "Webspecials", "toggle": "badge ban", "togglealt": "primary negative", "id": <?php echo $row->webspecials_id;?>, "value": "<?php echo $row->featured_special;?>"}' data-content="<?php echo Lang::$word->WSP_FEATURED_SPECIAL;?>"><i class="rounded inverted <?php echo ($row->featured_special) ? "badge primary" : "ban negative";?> icon link"></i></a> 
            <a id="dosubmit" data-id="<?php echo $row->webspecials_id;?>" data-content="Copy Web Special"><i class="rounded outline purple icon copy link"></i></a> 
             <!-- <a href="<?php echo Url::adminUrl("webspecials", "printws", false,"?id=" . $row->webspecials_id);?>"><i class="rounded outline purple icon printer link"></i></a> -->
            <?php if(Auth::hasPrivileges('edit_items')):?>
            <a href="<?php echo Url::adminUrl("webspecials", "edit", false,"?id=" . $row->webspecials_id);?>" data-content="Edit Web Special"><i class="rounded outline positive icon pencil link"></i></a>
            <?php endif;?>
            <?php if(Utility::dodate("short_date", $row->modified_ws) == Utility::dodate("short_date",Utility::today())&& $row->update_flag == 1)  :?>
       		<a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="30" width="30"> </a>
      		<?php else:?>
        	<a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>" style="display: none"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="30" width="30"> </a>
      		<?php endif;?>
            <?php if(Auth::hasPrivileges('delete_items')):?>
            <a class="delete" data-set='{"title": "<?php echo Lang::$word->WSP_DELETE;?>", "parent": "tr", "option": "deleteWebspecials", "id": <?php echo $row->webspecials_id;?>, "name": "<?php echo $row->nice_title_ws;?>"}'data-content="Delete Web Special"><i class="rounded outline icon negative trash link"></i></a>
            <?php endif;?>
          </div>
        </div>
        
      </div>
      
      
      </div>
  </div>
  <?php endforeach;?>
   <script type="text/javascript"> 
			// <![CDATA[
			$(document).ready(function() {
				$('a#dosubmit').on('click', function() {
                    var id = $(this).data('id');
			        var values = "id="+id;
			        values+= "&processWebspecialsDubs=1";
			        values += "&id="+id;
			        values += "&action=processWebspecialsDubs";
			        $.ajax({
			            type: 'post',
			            url: ADMINURL + "/controller.php",
			            dataType: 'json',
			            data: values,
			           success: function(json) {
			                if (json.type == "success") {
			                    //alert(data);
			                    $(".wojo.info.message").remove();
			                    
			                   setTimeout("window.location.href=window.location.href;",5000);
			                }
			                $.sticky(decodeURIComponent(json.message), {
			                    type: json.type,
			                    title: json.title
			                    
			                });
			            } 
			        });
			        
			    });
				 
			});    

// ]]>
</script>
  <?php unset($row);?>
</div>
<div class="wojo tabular segment">
  <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
  <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
</div>
<?php endif;?>
<?php break;?>
<?php case"dealership-view": ?>
<?php if(!$usrview = $db->first(Content::lcTable, null, array('store_id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $store_name = $wSpecials->getWebspecialsbystore(false, "dealership-view");?>
<?php $data = $wSpecials->getWebspecialsbystore(false, "dealership-view");?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->WSP_TITLE;?></div>
    <p><?php echo Lang::$word->WSP_INFO;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->FILTER;?></div>
  <div class="content">
    <div class="wojo form">
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->CURPAGE;?></label>
          <?php echo $pager->jump_menubyid($usrview->store_id);?></div>
        <div class="field">
          <label><?php echo Lang::$word->IPP;?></label>
          <?php echo $pager->items_per_pagebyid($usrview->store_id);?></div>
          
        <div class="field">
          <label><?php echo Lang::$word->LAYOUT;?></label>
          <div class="two fields fitted">
            <div class="field">
              <div class="wojo labeled fluid disabled icon button"> <i class="grid icon"></i> <?php echo Lang::$word->GRID;?> </div>
            </div>
            <div class="field"> <a href="<?php echo Url::adminUrl("webspecials","dealership", false, "?id=$usrview->store_id");?>" class="wojo right labeled icon fluid secondary button"> <i class="reorder icon"></i> <?php echo Lang::$word->LIST;?> </a> </div>
          </div>
        </div>
      </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("webspecials/dealership-view");?>" name="wojo_form">
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
              <input type="text" name="webspecialssearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
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
        <a href="<?php echo Url::adminUrl("webspecials/dealership-view", false,"?id=$usrview->store_id");?>" class="item<?php echo Url::setActive("order", false);?>"> <?php echo Lang::$word->DEFAULT;?> </a> <a href="<?php echo Url::adminUrl("webspecials/dealership-view", false, "?id=$usrview->store_id&", "order=title_ws/DESC");?>" class="item<?php echo Url::setActive("order", "title_ws");?>"> <?php echo Lang::$word->WSP_NAME;?> </a> <a href="<?php echo Url::adminUrl("webspecials/dealership-view", false,"?id=$usrview->store_id&", "order=new_used/DESC");?>" class="item<?php echo Url::setActive("order", "new_used");?>"> <?php echo Lang::$word->WSP_NEW_USED;?> </a> <a href="<?php echo Url::adminUrl("webspecials/dealership-view", false,"?id=$usrview->store_id&", "order=specials_type/DESC");?>" class="item<?php echo Url::setActive("order", "specials_type");?>"> <?php echo Lang::$word->WSP_SPECIALS_TYPE;?> </a> <a href="<?php echo Url::adminUrl("webspecials/dealership-view", false,"?id=$usrview->store_id&", "order=body_style/DESC");?>" class="item<?php echo Url::setActive("order", "body_style");?>"> <?php echo Lang::$word->WSP_BODYSTYLE;?> </a> <a href="<?php echo Url::adminUrl("webspecials/dealership-view", false,"?id=$usrview->store_id&", "order=year/DESC");?>" class="item<?php echo Url::setActive("order", "year");?>"> #<?php echo Lang::$word->WSP_YEAR;?> </a>
        <div class="item" data-content="ASC/DESC"><a href="<?php echo Url::sortItemsView(Url::adminUrl("webspecials/dealership-view", false, "?id=$usrview->store_id"), "order");?>"><i class="icon unfold more link"></i></a> </div>
      </div>
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBitsLetter(Url::adminUrl("webspecials", "dealership-view",false,"?id=$usrview->store_id&"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<?php if(Auth::hasPrivileges('add_items')):?>
<div class="clearfix"> <a class="wojo right labeled icon secondary button push-right" href="<?php echo Url::adminUrl("webspecials", "add");?>"><i class="icon plus"></i><?php echo Lang::$word->WSP_ADD;?></a> </div>
<?php endif;?>
<?php if(!$data):?>
<?php echo Message::msgSingleAlert(Lang::$word->WSP_NOLIST);?>
<?php else:?>
<div class="wojo space divider"></div>
<div class="columns gutters">
  <?php foreach ($data as $row):?>
  <div class="row screen-50 tablet-50 phone-100">
    <div class="wojo divided card">
      <div class="header">
        <div class="wojo top right small attached label"><?php echo $row->webspecials_id;?>.</div>
        <div class="wojo rounded primary small grid image"><a data-lightbox="true" data-title="<?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?>" href="<?php echo $row->vehicle_image;?>"><img src="<?php echo $row->vehicle_image;?>" alt=""></a></div>
        <div class="content">
          <?php if(Auth::hasPrivileges('edit_items')):?>
          <a href="<?php echo Url::adminUrl("webspecials", "edit", false,"?id=" . $row->webspecials_id);?>"><?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?> </a> 
          <?php else:?>
          <?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?>
          <?php endif;?>
          <p><?php echo Lang::$word->CREATED;?>: <?php echo Utility::dodate("long_date", $row->created_ws);?></p>
        </div>
      </div>
       <div class="item">
        <div class="intro"><?php echo Lang::$word->WSP_ROOM;?>:</div>
        <div class="data"><?php echo $row->storename_ws;?></div>
      </div>
     <div class="item">
        <div class="intro"><?php echo Lang::$word->WSP_PRICE;?>:</div>
        <div class="data"><?php echo Utility::formatMoney($row->buy_price);?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->WSP_STOCK;?>:</div>
        <div class="data"><?php echo $row->stock_number;?></div>
      </div>
       <div class="item">
        <div class="intro"><?php echo Lang::$word->LST_CAT;?>:</div>
        <div class="data"><?php echo $row->body_style_code;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->WSP_YEAR;?>:</div>
        <div class="data"><?php echo $row->year;?></div>
      </div>
      <div class="item">
        <div class="intro"><?php echo Lang::$word->MODIFIED;?>:</div>
        <div class="data"><?php echo ($row->modified_ws <> 0) ? Utility::dodate("short_date", $row->modified_ws): '- ' . Lang::$word->NEVER . ' -'?></div>
       </div>
       <div class="item">
        <div class="intro"><?php echo Lang::$word->MODIFIED;?> <?php echo Lang::$word->BY;?>:</div>
        <div class="data"> <?php echo $row->username;?></div>
      </div>
      <!--  <div class="item">
      <div class="intro"><?php echo Lang::$word->MODIFIED;?> <?php echo Lang::$word->BY;?>:</div>
      <?php if(Utility::dodate("short_date", $row->modified_ws) == Utility::dodate("short_date",Utility::today())&& $row->update_flag == 1)  :?>
       <div class="data"><a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt="" class="wojo grid image" height="42" width="42"> </a></div>
      	<?php else:?>
        <div class="data"><a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>" style="display: none"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt="" class="wojo grid image" height="42" width="42"> </a></div>
      <?php endif;?>
      </div>-->
      <div class="actions">
        <div class="item">
          <div class="intro"><?php echo Lang::$word->ACTIONS;?>:</div>
          <div class="data"> <a class="doStatus" data-set='{"field": "status", "table": "Webspecials", "toggle": "check ban", "togglealt": "positive purple", "id": <?php echo $row->webspecials_id;?>, "value": "<?php echo $row->active;?>"}' data-content="<?php echo Lang::$word->ACTIVE;?>"><i class="rounded inverted <?php echo ($row->active) ? "check positive" : "ban purple";?> icon link"></i></a> <a class="doStatus" data-set='{"field": "featured", "table": "Webspecials", "toggle": "badge ban", "togglealt": "primary negative", "id": <?php echo $row->webspecials_id;?>, "value": "<?php echo $row->featured_special;?>"}' data-content="<?php echo Lang::$word->WSP_FEATURED_SPECIAL;?>"><i class="rounded inverted <?php echo ($row->featured_special) ? "badge primary" : "ban negative";?> icon link"></i></a> 
            <a id="dosubmit" data-id="<?php echo $row->webspecials_id;?>" data-content="Copy Web Special"><i class="rounded outline purple icon copy link"></i></a> 
             <!-- <a href="<?php echo Url::adminUrl("webspecials", "printws", false,"?id=" . $row->webspecials_id);?>"><i class="rounded outline purple icon printer link"></i></a> -->
            <?php if(Auth::hasPrivileges('edit_items')):?>
            <a href="<?php echo Url::adminUrl("webspecials", "edit", false,"?id=" . $row->webspecials_id);?>" data-content="Edit Web Special"><i class="rounded outline positive icon pencil link"></i></a>
            <?php endif;?>
            <?php if(Utility::dodate("short_date", $row->modified_ws) == Utility::dodate("short_date",Utility::today())&& $row->update_flag == 1)  :?>
       		<a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="30" width="30"> </a>
      		<?php else:?>
        	<a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>" style="display: none"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="30" width="30"> </a>
      		<?php endif;?>
            <?php if(Auth::hasPrivileges('delete_items')):?>
            <a class="delete" data-set='{"title": "<?php echo Lang::$word->WSP_DELETE;?>", "parent": "tr", "option": "deleteWebspecials", "id": <?php echo $row->webspecials_id;?>, "name": "<?php echo $row->nice_title_ws;?>"}'data-content="Delete Web Special"><i class="rounded outline icon negative trash link"></i></a>
            <?php endif;?>
          </div>
        </div>
        
      </div>
      </div>
  </div>
  <?php endforeach;?>
   <script type="text/javascript"> 
			// <![CDATA[
			$(document).ready(function() {
				$('a#dosubmit').on('click', function() {
                    var id = $(this).data('id');
			        var values = "id="+id;
			        values+= "&processWebspecialsDubs=1";
			        values += "&id="+id;
			        values += "&action=processWebspecialsDubs";
			        $.ajax({
			            type: 'post',
			            url: ADMINURL + "/controller.php",
			            dataType: 'json',
			            data: values,
			           success: function(json) {
			                if (json.type == "success") {
			                    //alert(data);
			                    $(".wojo.info.message").remove();
			                    
			                   setTimeout("window.location.href=window.location.href;",5000);
			                }
			                $.sticky(decodeURIComponent(json.message), {
			                    type: json.type,
			                    title: json.title
			                    
			                });
			            } 
			        });
			        
			    });
				 
			});    

// ]]>
</script>
  <?php unset($row);?>
</div>
<div class="wojo tabular segment">
  <div class="wojo cell"> <?php echo $pager->display_pages();?></div>
  <div class="wojo cell right"> <?php echo Lang::$word->TOTAL.': '.$pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$pager->current_page.' '.Lang::$word->OF.' '.$pager->num_pages;?> </div>
</div>
<?php endif;?>
<?php break;?>
<?php case"dealership": ?>
<?php if(!$usr = $db->first(Content::lcTable, null, array('store_id' => Filter::$id))) : Message::invalid("ID" . Filter::$id); return; endif;?>
<?php $store_name = $db->getValueById(Content::lcTable, "name", $usr->id);?>
<?php $data = $wSpecials->getWebspecialsbystore(false, "dealership");?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->WSP_TITLE;?></div>
    <p><?php echo Lang::$word->WSP_INFO;?></p>
  </div>
</div>
<div class="wojo quaternary segment">
  <div class="header"><?php echo Lang::$word->FILTER;?></div>
  <div class="content">
    <div class="wojo form">
      <div class="three fields">
        <div class="field">
          <label><?php echo Lang::$word->CURPAGE;?></label>
          <?php echo $pager->jump_menubyid($usr->store_id);?></div>
        <div class="field">
          <label><?php echo Lang::$word->IPP;?></label>
          <?php echo $pager->items_per_pagebyid($usr->store_id);?></div>
        <div class="field">
          <label><?php echo Lang::$word->LAYOUT;?></label>
          <div class="two fields fitted">
            <div class="field"> <a href="<?php echo Url::adminUrl("webspecials", "dealership-view",false,"?id=$usr->store_id");?>" class="wojo labeled fluid secondary icon button"> <i class="grid icon"></i> <?php echo Lang::$word->GRID;?> </a> </div>
            <div class="field">
              <div class="wojo right labeled icon fluid button disabled"> <i class="reorder icon"></i> <?php echo Lang::$word->LIST;?> </div>
            </div>
          </div>
        </div>
      </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("webspecials", "dealership",false,"?id=$usr->store_id");?>" name="wojo_form">
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
              <input type="text" name="webspecialssearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
        </div>
        
      </form>
     
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBitsLetter(Url::adminUrl("webspecials", "dealership", false,"?id=$usr->store_id&" ), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<div class="wojo tertiary segment">

  <div class="header clearfix"><span>Viewing (<?php echo $store_name?>) Web Specials</span>
    <?php if(Auth::hasPrivileges('add_items')):?>
    <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->WSP_ADD;?>" href="<?php echo Url::adminUrl("webspecials", "add");?>" ><i class="icon plus"></i></a>
    <?php endif;?>
  </div>
  <form method="post" id="wojo_forml" name="wojo_forml">
    <table class="wojo table">
      <thead>
        <tr>
        
          <th class="disabled"> <label class="fitted small checkbox">
              <input type="checkbox" name="masterCheckbox" data-parent="#listtable" id="masterCheckbox">
              <i></i></label>
          </th>
          <th class="disabled"></th>
          <th class="disabled"><?php echo Lang::$word->PHOTO;?></th>
          <th data-sort="string"><?php echo Lang::$word->DESC;?></th>
          <th class="disabled"><?php echo Lang::$word->WSP_ROOM;?></th>
          <th data-sort="string"><?php echo Lang::$word->LST_CAT;?></th>
          <th data-sort="int">NCS Ordering</th>
           <th class="disabled">Alerts</th>
          <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
        </tr>
      </thead>
      <tbody id="listtable">
        <?php if(!$data):?>
        <tr>
          <td colspan="9"><?php Message::msgSingleAlert(Lang::$word->WSP_NOLIST);?></td>
        </tr>
        <?php else:?>
        <?php foreach($data as $row):?>
        <tr data-id="<?php echo $row->webspecials_id;?>">
        <td class="sorter"><i class="icon reorder"></i></td>
         <td><label class="fitted small checkbox">
              <input name="listid[<?php echo $row->webspecials_id;?>]" type="checkbox" value="<?php echo $row->webspecials_id;?>">
              <i></i></label></td>
          <td><a data-lightbox="true" data-title="<?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?>" href="<?php echo $row->vehicle_image;?>" href="<?php echo $row->vehicle_image;?>"><img src="<?php echo $row->vehicle_image;?>" alt="" class="wojo medium grid image"></a></td>
          <td><b><?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?></b>  <br />
           Stock#: <b><?php echo $row->stock_number;?></b> <br />
            <?php echo Lang::$word->WSP_PRICE;?>: (<?php echo Utility::formatMoney($row->buy_price);?>) </small><br />
            <?php if($row->created_ws > 0 && $row->modified_ws == 0):?>
           <?php echo Lang::$word->CREATED;?>: <b><?php echo Utility::dodate("short_date", $row->created_ws);?></b><br />
          <!-- <?php echo Lang::$word->CREATED;?> <?php echo Lang::$word->BY;?>: <?php echo $row->username;?> -->
          <?php else:?>
          <?php echo Lang::$word->MODIFIED;?>: <b><?php echo ($row->modified_ws <> 0) ? Utility::dodate("short_date", $row->modified_ws): ''?></b><br />
          <?php echo Lang::$word->MODIFIED;?> <?php echo Lang::$word->BY;?>: <?php echo $row->username;?>
          <?php endif;?>
         <td><?php echo $row->storename_ws;?></td>
          <td><?php echo $row->body_style_code;?></td>
         <td><?php echo $row->ordering;?></td>
          <?php if(Utility::dodate("short_date", $row->modified_ws) == Utility::dodate("short_date",Utility::today())&& $row->update_flag == 1)  :?>
          <td>
          <a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="42" width="42"> </a></td>
           <?php else:?>
           <td>
            <a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>" style="display: none"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="42" width="42"> </a></td>
           <?php endif;?>
          <td>
          <div class="wojo icon top right pointing primary small dropdown button"> <i class="ellipsis vertical icon"></i>
              <div class="menu"> <a class="doStatus item" data-set='{"field": "status", "table": "Webspecials", "toggle": "check ban", "togglealt": "primary negative", "id": <?php echo $row->webspecials_id;?>, "value": "<?php echo $row->active;?>"}'><i class="<?php echo ($row->active) ? "check primary" : "ban negative";?> icon link"></i><?php echo Lang::$word->ACTIVE;?></a> <a class="doStatus item" data-set='{"field": "featured", "table": "Webspecials", "toggle": "check ban", "togglealt": "primary negative", "id": <?php echo $row->webspecials_id;?>, "value": "<?php echo $row->featured_special;?>"}'><i class="<?php echo ($row->featured_special) ? "check primary" : "ban negative";?> icon link"></i><?php echo Lang::$word->FEATURED;?></a></div>
            </div>
            <a id="dosubmit" data-id="<?php echo $row->webspecials_id;?>" data-content="Copy Web Special"><i class="rounded outline purple icon copy link"></i></a> 
           <!-- <a href="<?php echo Url::adminUrl("webspecials", "printws", false,"?id=" . $row->webspecials_id);?>"><i class="rounded outline purple icon printer link"></i></a> -->
           <div class="quarter-top-space"></div>
            <?php if(Auth::hasPrivileges('edit_items')):?>
            <a href="<?php echo Url::adminUrl("webspecials", "edit", false,"?id=" . $row->webspecials_id);?>" data-content="Edit Web Special"><i class="rounded outline positive icon pencil link"></i></a>
            <?php endif;?>
          
            <?php if(Auth::hasPrivileges('delete_items')):?>
            <a class="delete" data-set='{"title": "<?php echo Lang::$word->WSP_DELETE;?>", "parent": "tr", "option": "deleteWebspecials", "id": <?php echo $row->webspecials_id;?>, "name": "<?php echo $row->nice_title_ws;?>"}'data-content="Delete Web Special"><i class="rounded outline icon negative trash link"></i></a>
            <?php endif;?></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
      <script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
	 $(".wojo.table").rowSorter({
        handler: "td.sorter",
        onDrop: function() {
            var data = [];
            var sletter = <?php echo Filter::$id;?>;    
            $('.wojo.table tbody tr').each(function() {
                data.push($(this).data("id"))
               
            });
            $.ajax({
                type: "post",
                url: ADMINURL + "/helper.php",
                data: {
                    ordering: data,
                    sortwebspecials: 1
                  
                }    
            });
        }
    });

	 $('a#dosubmit').on('click', function() {
         var id = $(this).data('id');
	        var values = "id="+id;
	        values+= "&processWebspecialsDubs=1";
	        values += "&id="+id;
	        values += "&action=processWebspecialsDubs";
	        $.ajax({
	            type: 'post',
	            url: ADMINURL + "/controller.php",
	            dataType: 'json',
	            data: values,
	           success: function(json) {
	                if (json.type == "success") {
	                    //alert(data);
	                    $(".wojo.info.message").remove();
	                    setTimeout("window.location.href=window.location.href;",5000);    
	                }
	                $.sticky(decodeURIComponent(json.message), {
	                    type: json.type,
	                    title: json.title
	                    
	                });
	            } 
	        });
	        
	    });

	 /* function setIdle(cb, seconds) {
		    var timer; 
		    var interval = seconds * 1000;
		    function refresh() {
		            clearInterval(timer);
		            timer = setTimeout(cb, interval);
		    };
		    $(document).on('keypress click', refresh);
		    refresh();
		}

		setIdle(function() {
		    
		}, 30
);   */
});    

// ]]>
</script>
      <?php if($data):?>
      <tfoot>
        <tr>
          <td colspan="2"><button name="mdelete" type="button" data-form="#wojo_forml" class="wojo negative button"><i class="icon trash alt"></i><?php echo Lang::$word->WSP_DELETES;?></button>
            <input name="delete" type="hidden" value="deleteMultiWebspecials"></td>
             <td><a href="<?php echo Url::adminUrl("webspecials","dealership", false, "?id=$usr->store_id");?>" class="wojo secondary button"> <i class="reorder icon"></i> Re-Order Web Specials </a></td>
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
<?php default: ?>
<?php $data = $wSpecials->getWebspecials(false, "");?>
<div class="wojo secondary icon message"> <i class="car icon"></i>
  <div class="content">
    <div class="header"> <?php echo Lang::$word->WSP_TITLE;?></div>
    <p><?php echo Lang::$word->WSP_INFO;?></p>
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
            <div class="field"> <a href="<?php echo Url::adminUrl("webspecials", "view");?>" class="wojo labeled fluid secondary icon button"> <i class="grid icon"></i> <?php echo Lang::$word->GRID;?> </a> </div>
            <div class="field">
              <div class="wojo right labeled icon fluid button disabled"> <i class="reorder icon"></i> <?php echo Lang::$word->LIST;?> </div>
            </div>
          </div>
        </div>
      </div>
      <form method="post" id="wojo_form" action="<?php echo Url::adminUrl("webspecials");?>" name="wojo_form">
        <div class="four fields">
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
          <select name="store_letter" id="store_letter" class="select-store_letter"><!--  onchange="this.form.submit()"-->
            <option value="">-- <?php echo Lang::$word->WSP_ROOM_S;?> --</option>
            <?php echo Utility::loopOptions($content->getLocations(),"store_id","name");?>
          </select>
          <!--  <div class="wojo corner label"> <i class="icon asterisk"></i> </div> -->
        </div>
          <div class="field">
            <div class="wojo icon input">
              <input type="text" name="webspecialssearch" placeholder="<?php echo Lang::$word->SEARCH;?>" id="searchfield">
              <i class="find icon"></i>
              <div id="suggestions"> </div>
            </div>
          </div>
        </div>
       </form>
       <script type='text/javascript'>
       $(document).ready(function(e){
          $("#store_letter").change(function(e){
        	   e.preventDefault(); // avoid to execute the actual submit of the form.
    	        var sletter = $('#store_letter').val();
    	        $.post("<?php echo Url::adminUrl("webspecials", "dealership");?>" ,$('#wojo_form').serialize(),function(data){
    	            //alert(data);
    	            window.location.href = "<?php echo Url::adminUrl("webspecials", "dealership", false,"?id=");?>" +sletter;                  
    	        });
    	    });
    	});
      
       // window.location.href = "<?php echo Url::adminUrl("webspecials", "dealership", false,"?id=");?>" +sletter;
               // e.preventDefault(); // avoid to execute the actual submit of the form.
       </script>
       
        
    </div>
  </div>
  <div class="footer">
    <div class="content-center"> <?php echo Validator::alphaBits(Url::adminUrl("webspecials"), "letter", "basic pagination menu");?> </div>
  </div>
</div>
<div class="wojo tertiary segment">
  <div class="header clearfix"><span><?php echo Lang::$word->WSP_SUB;?></span>
    <?php if(Auth::hasPrivileges('add_items')):?>
    <a class="wojo large top right detached action label" data-content="<?php echo Lang::$word->WSP_ADD;?>" href="<?php echo Url::adminUrl("webspecials", "add");?>" ><i class="icon plus"></i></a>
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
          <th class="disabled"><?php echo Lang::$word->WSP_ROOM;?></th>
          <th data-sort="string"><?php echo Lang::$word->LST_CAT;?></th>
          <th class="disabled">Alerts</th>
          <th class="disabled"><?php echo Lang::$word->ACTIONS;?></th>
        </tr>
      </thead>
      <tbody id="listtable">
        <?php if(!$data):?>
        <tr>
          <td colspan="9"><?php Message::msgSingleAlert(Lang::$word->WSP_NOLIST);?></td>
        </tr>
        <?php else:?>
        <?php foreach($data as $row):?>
        <tr data-id="<?php echo $row->webspecials_id;?>">
          <td><label class="fitted small checkbox">
              <input name="listid[<?php echo $row->webspecials_id;?>]" type="checkbox" value="<?php echo $row->webspecials_id;?>">
              <i></i></label></td>
          <td><a data-lightbox="true" data-title="<?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?>" href="<?php echo $row->vehicle_image;?>" href="<?php echo $row->vehicle_image;?>"><img src="<?php echo $row->vehicle_image;?>" alt="" class="wojo medium grid image"></a></td>
          <td><b><?php echo $row->new_used_ws;?> (<?php echo $row->year;?>)  <?php echo $row->nice_title_ws;?> <?php echo $row->trim_level;?></b>  <br />
           Stock#: <b><?php echo $row->stock_number;?></b> <br />
           <?php echo Lang::$word->WSP_PRICE;?>: (<?php echo Utility::formatMoney($row->buy_price);?>) </small><br />
          <?php if($row->created_ws > 0 && $row->modified_ws == 0):?>
          <?php echo Lang::$word->CREATED;?>: <b><?php echo Utility::dodate("short_date", $row->created_ws);?></b><br />
          <!-- <?php echo Lang::$word->CREATED;?> <?php echo Lang::$word->BY;?>: <?php echo $row->username;?> -->
          <?php else:?>
          <?php echo Lang::$word->MODIFIED;?>: <b><?php echo ($row->modified_ws <> 0) ? Utility::dodate("short_date", $row->modified_ws): ''?></b><br />
          <?php echo Lang::$word->MODIFIED;?> <?php echo Lang::$word->BY;?>: <?php echo $row->username;?>
          <?php endif;?>
         <td><?php echo $row->storename_ws;?></td>
          <td><?php echo $row->body_style_code;?></td>
          <?php if(Utility::dodate("short_date", $row->modified_ws) == Utility::dodate("short_date",Utility::today())&& $row->update_flag == 1)  :?>
          <td>
          <a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="42" width="42"> </a></td>
           <?php else:?>
           <td>
            <a href="<?php echo Url::adminUrl("webspecials", "webspecialsalert", false,"?id=" . $row->webspecials_id);?>" style="display: none"><img src="<?php echo Url::adminUrl("assets", "images", false,"highAlertIcon.gif");?> " alt=""  height="42" width="42"> </a></td>
           <?php endif;?>
          <td>
          <div class="wojo icon top right pointing primary small dropdown button"> <i class="ellipsis vertical icon"></i>
              <div class="menu"> <a class="doStatus item" data-set='{"field": "status", "table": "Webspecials", "toggle": "check ban", "togglealt": "primary negative", "id": <?php echo $row->webspecials_id;?>, "value": "<?php echo $row->active;?>"}'><i class="<?php echo ($row->active) ? "check primary" : "ban negative";?> icon link"></i><?php echo Lang::$word->ACTIVE;?></a> <a class="doStatus item" data-set='{"field": "featured", "table": "Webspecials", "toggle": "check ban", "togglealt": "primary negative", "id": <?php echo $row->webspecials_id;?>, "value": "<?php echo $row->featured_special;?>"}'><i class="<?php echo ($row->featured_special) ? "check primary" : "ban negative";?> icon link"></i><?php echo Lang::$word->FEATURED;?></a></div>
            </div>
            <a id="dosubmit" data-id="<?php echo $row->webspecials_id;?>" data-content="Copy Web Special"><i class="rounded outline purple icon copy link"></i></a> 
           <!-- <a href="<?php echo Url::adminUrl("webspecials", "printws", false,"?id=" . $row->webspecials_id);?>"><i class="rounded outline purple icon printer link"></i></a> -->
           <div class="quarter-top-space"></div>
            <?php if(Auth::hasPrivileges('edit_items')):?>
            <a href="<?php echo Url::adminUrl("webspecials", "edit", false,"?id=" . $row->webspecials_id);?>" data-content="Edit Web Special"><i class="rounded outline positive icon pencil link"></i></a>
            <?php endif;?>
          
            <?php if(Auth::hasPrivileges('delete_items')):?>
            <a class="delete" data-set='{"title": "<?php echo Lang::$word->WSP_DELETE;?>", "parent": "tr", "option": "deleteWebspecials", "id": <?php echo $row->webspecials_id;?>, "name": "<?php echo $row->nice_title_ws;?>"}'data-content="Delete Web Special"><i class="rounded outline icon negative trash link"></i></a>
            <?php endif;?></td>
        </tr>
        <?php endforeach;?>
         <script type="text/javascript"> 
			// <![CDATA[
			$(document).ready(function() {
				$('a#dosubmit').on('click', function() {
                    var id = $(this).data('id');
			        var values = "processWebspecialsDubs=1";
			        values += "&id="+id;
			        values += "&action=processWebspecialsDubs";
			        $.ajax({
			            type: 'post',
			            url: ADMINURL + "/controller.php",
			            dataType: 'json',
			            data: values,
			           success: function(json) {
			                if (json.type == "success") {
			                    //alert(data);
			                    $(".wojo.info.message").remove();
			                    
			                   setTimeout("window.location.href=window.location.href;",5000);
			                }
			                $.sticky(decodeURIComponent(json.message), {
			                    type: json.type,
			                    title: json.title
			                    
			                });
			            } 
			        });
			        
			    });
				 
			});    

// ]]>
</script>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
      <?php if($data):?>
      <tfoot>
        <tr>
          <td colspan="6"><button name="mdelete" type="button" data-form="#wojo_forml" class="wojo negative button"><i class="icon trash alt"></i><?php echo Lang::$word->WSP_DELETES;?></button>
            <input name="delete" type="hidden" value="deleteMultiWebspecials"></td>
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
<?php endswitch;?>