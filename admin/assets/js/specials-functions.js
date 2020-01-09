jQuery(document).ready(function() { //NCS document ready js	
	var formInc = 0,//initial value for variables used to proc adjustments to top for form when switching from absolute to fixed positioning
	//NCSdisClaim = '.qDisclaimer', //disclaimer class text turned into a variable
	NCSspecForm = '.specialsFormContainer';	//form class text turned into a variable	
	
	function NCSHeaderHeight(){// set height for form and disclaimer
		//if (jQuery(NCSdisClaim).length || jQuery(NCSspecForm).length) {
		if (jQuery(NCSspecForm).length) {
			var discHeight,
			ncsScrollTop = jQuery(window).scrollTop();//mainly to effect initial positioning in mobile when scrolled down on the specials page
			
			if (jQuery("#tablet-basic-header").length > 0 || jQuery(".mobile-menu-toggle").length > 0 || jQuery("#header").length > 0){//check for type of header used as this is specific to di's theme, then get the header's outer height as a variable			
				if (jQuery('#tablet-basic-header').is(':visible')) {	        	
					discHeight = jQuery('#tablet-basic-header').outerHeight();
		        }else if (jQuery('.mobile-menu-toggle').is(':visible')){	             
		        	discHeight = jQuery('.mobile-menu-toggle').outerHeight();
		        }else if (jQuery('#header').is(':visible')){
		        	discHeight = jQuery('#header').outerHeight();
		        }
			}else{
				discHeight = jQuery('.site-header').outerHeight(); //get regular wordpress header by class if none of the previous options exists
			}
			if (jQuery('#wpadminbar').length){ //check for admin bar 
				discHeight = discHeight+jQuery('#wpadminbar').outerHeight(); //add height from admin bar to the variable if it exists		
			}
			discHeight = discHeight+15; //add 15px to the height of this variable
		}			
		if (jQuery(NCSspecForm).length){//check for specials form existence on the page			
			if (jQuery(NCSspecForm).css('position') == "absolute"){
				jQuery(NCSspecForm).not(':visible').css({top: discHeight+ncsScrollTop+'px'}); //absolute positioning fix			
				if (formInc == 0) {
					jQuery(NCSspecForm).css({top: discHeight+ncsScrollTop+'px'});//proc top adjustment on positioning swap
					formInc = 1;					
				}				
			}else{
				jQuery(NCSspecForm).css({top: discHeight+'px'}); //calculate initial top value for form when in fixed position 				
				formInc = 0;				
			}
		}		
		/*if (jQuery(NCSdisClaim).length){//check for disclaimer existence on the page	
			if (jQuery(NCSspecForm).is(':visible') || jQuery(NCSdisClaim).css('width') != '290px'){//always align disclaimer top value with specials form top value if specials form is visible
				var formTop = jQuery(".specialsFormContainer:visible").css('top');
				jQuery(NCSdisClaim).css({position: ''});
				jQuery('.qDisclaimer:visible').css({top: formTop});				
			}else{//if specials form isn't visible but the disclaimer is
				if (jQuery(NCSdisClaim).css('position') == "absolute"){
					jQuery(NCSdisClaim).each(function () {
						this.style.setProperty('position', 'fixed', 'important');
					});					
					jQuery(NCSdisClaim).css({top: discHeight+'px'}); //calculate top value for disclaimer when absolutely positioned							
				}
			}
		}*/
	}
	NCSHeaderHeight();
	jQuery(window).resize(NCSHeaderHeight);	
	jQuery(window).scroll(NCSHeaderHeight);	
	
	jQuery('.getInfo, .close-dialog').click(function() { //show or hide specials form
		var NCSdialog = jQuery(this).parents('.qsContent').next('.specialsFormContainer');
		
		if (!jQuery(NCSspecForm).is(':visible') && !jQuery('#q_overlay').is(':visible')){
			jQuery('#q_overlay').fadeIn();			
			jQuery(NCSdialog).fadeIn();			
		}/*else if (jQuery(NCSspecForm).is(':visible') && jQuery(NCSdisClaim).is(':visible') && (jQuery(NCSdisClaim).css("position") == "fixed" || jQuery(NCSdisClaim).css("position") == "absolute")){			
			jQuery(NCSspecForm).fadeOut();
		}*/else{
			jQuery('#q_overlay').fadeOut();
			jQuery(NCSspecForm).fadeOut();
		}			
	});
	
	jQuery('#q_overlay').click(function() {//form overlay click function
		/*if (jQuery(NCSspecForm).is(':visible') && jQuery(NCSdisClaim).is(':visible')){
			jQuery('.specialsFormContainer:visible').fadeOut();
		}else if(jQuery(NCSspecForm).is(':visible') && !jQuery(NCSdisClaim).is(':visible')){*/
			jQuery('.specialsFormContainer:visible').fadeOut();
			jQuery('#q_overlay').fadeOut();
		/*}else{
			jQuery('.qDisclaimer:visible').fadeOut();			
			jQuery('#q_overlay').fadeOut();
		}*/
	});
	
/*=================================================================
Single & Thank You Page Height Specific Code
===================================================================*/
	if (jQuery('.qsContent.singlePost').length || jQuery('.NCSThankYouPage').length){//check if we're on the single page specials or on the thank you pages and only run the following code if we are
		function NCSSinglePageHeight() {//always keeps the single page specials content centered on the page when the content is smaller than the window height
			var ncshHeight,//header height initial variable
			NCSpageWindowHeight = jQuery(window).height(),//window height
			NCSFooterHeight = jQuery('footer').outerHeight(),//footer height			
			NCSsinglePageHeightFinal;			
			
			if (jQuery("#tablet-basic-header").length > 0 || jQuery(".mobile-menu-toggle").length > 0 || jQuery("#header").length > 0){//check for type of header used as this is specific to di's theme, then get the header's outer height as a variable			
				if (jQuery('#tablet-basic-header').is(':visible')) {	        	
					ncshHeight = jQuery('#tablet-basic-header').outerHeight();
		        }else if (jQuery('.mobile-menu-toggle').is(':visible')){	             
		        	ncshHeight = jQuery('.mobile-menu-toggle').outerHeight();
		        }else if (jQuery('#header').is(':visible')){
		        	ncshHeight = jQuery('#header').outerHeight();
		        }
			}else{
				ncshHeight = jQuery('.site-header').outerHeight(); //get regular wordpress header by class if none of the previous options exists
			}
			if (jQuery('#wpadminbar').length){ //check for admin bar 
				ncshHeight = ncshHeight+jQuery('#wpadminbar').outerHeight(); //add height from admin bar to the variable if it exists		
			}
			if (jQuery(".wideLists").length){//DI includes widelists right above their footers, this is to account for that
				var NCSWideLists = jQuery(".wideLists").outerHeight;
				NCSsinglePageHeightFinal = NCSpageWindowHeight - ncshHeight - NCSWideLists - NCSFooterHeight;//create height value based on window, header, widelists and footer height
			}else{
				NCSsinglePageHeightFinal = NCSpageWindowHeight - ncshHeight - NCSFooterHeight;//create height value based on window, header and footer height
			}
			if (jQuery('.qsContent.singlePost').length){//run for single page specials
				jQuery('.ncs_singlePost_page').css({'min-height' : NCSsinglePageHeightFinal-(parseInt(jQuery('.ncs_singlePost_page').css('margin-top'))*2)+'px'});//adjust single page height					
			}else{//run for thank you pages
				jQuery('.NCSThankYouPage').css({'min-height' : NCSsinglePageHeightFinal-(parseInt(jQuery('.NCSThankYouPage').css('margin-top'))*2)+'px'}); //adjust thank you page height
				if (jQuery('.NCSThanksImg').length && jQuery('.NCSThanksImg').css('display') != 'none'){//check to see if page is under 600px wide using the image display css setting
					jQuery('.NCSThanksVid,.NCSThanksVid iframe').css({'height' : jQuery('.NCSThanksImg img').height()+'px'}); //adjust iframe height to thank you page image height				
				}else{
					//jQuery('.NCSThanksVid').css({'width' : '100%'});
					jQuery('.NCSThanksVid,.NCSThanksVid iframe').css({'height' : (jQuery('.NCSThanksVid').width()/1.78)+'px'}); //adjust iframe height to be 1/2 of the iframe's width					
				}
			}
		}
		NCSSinglePageHeight();
		jQuery(window).resize(NCSSinglePageHeight);		
	}
/*=================================================================
Specials Specific Code
===================================================================*/
	if (jQuery(".qsPage").length){//check for specials page	
		var qbtype = document.documentElement;
		qbtype.setAttribute('data-btype', navigator.userAgent);
		jQuery('.qsTabOpenClose').click(function() {//open and close special click function
			var NCSopen = jQuery(this).parents('.qsContent').find('.ncsExpand'),//set local special variables
			NCSopenArrow = jQuery(this).parents('.qsContent').find('.ncsExpandArrow'),
			NCSclose = jQuery(this).parents('.qsContent').find('.ncsClose'),
			NCScloseArrow = jQuery(this).parents('.qsContent').find('.ncsCloseArrow'),
			NCSpriceTop = jQuery(this).parents('.qsContent').find('.quirkPriceTop'),
			NCSmonthlyTopContain = jQuery(this).parents('.qsContent').find('.monthlyPricingTopContainer'),
			NCSmonthlyTop = jQuery(this).parents('.qsContent').find('.MonthlyPricingTop'),
			NCSsaveUp = jQuery(this).parents('.qsContent').find('.ncs_saving_up_to'),
			NCStabColor = jQuery(this).parents('.qsTab').css('background-color'),
			NCSocTab = jQuery(this),
			NCStitleSave = jQuery(this).parents('.qsContent').find('.ncs_title_save_wrap'),
			NCStitleSaveNew = jQuery(this).parents('.qsContent').find('.ncs_title_save_wrap_innernew'),
			NCSpricingWrap = jQuery(this).parents('.qsContent').find('.ncs_pricing_wrap'),
			NCSImgWrapper = jQuery(this).parents('.qsContent').find('.vehicleMedia'),
			NCSImg = jQuery(this).parents('.qsContent').find('.vehicleMedia img'),
			NCSqsInner = jQuery(this).parents('.qsContent').find('.qsInnerContent'),
			//NCSSpecialName = jQuery(this).parents('.qsContent').find('.ncs_title_wrap h3'),
			NCSshortcodeForm = jQuery(this).parents('.qsContent').find('.specialsShortCodeFormContainer'),
			NCSExpandMobile = jQuery(this).parents('.qsContent').find('.ncs_mobile_top_content_expanded'),
			NCSExpand = jQuery(this).parents('.qsContent').find('.NCS_deatail_CTA_Expand');
			
			if(NCSopen.is(':visible')){//if the special is collapsed				
				NCSopen.hide();
				NCSopenArrow.hide();
				NCSclose.show();
				NCScloseArrow.show();
				NCSocTab.css({'background-color' : NCStabColor});
				NCSImg.css({width: "100%"});
				
				if(jQuery(NCSExpand).css("border-top") != "1px solid rgb(0, 0, 0)"){//checking page size through css applied in a media query at 850px wide					
					NCSExpandMobile.show();					
					NCSpriceTop.fadeOut(200);
					NCSmonthlyTopContain.fadeOut(200);	
					NCSsaveUp.delay(400).fadeIn(200);								
					NCStitleSave.delay(400).queue(function() { 
						jQuery(this).css({height: "auto"});
						jQuery(this).dequeue();
					});
					NCStitleSave.delay(400).animate({padding : "1% 0 1.8% 2%"}, 400);
					NCSImgWrapper.delay(400).queue(function() {
						jQuery(this).css({width : "50%"});
						jQuery(this).dequeue();
					});			
					NCSpricingWrap.delay(400).animate({width : "50%", "min-width" : "50%"}, 400);				
					NCStitleSaveNew.delay(400).animate({width : "75%"}, 400);
					NCSmonthlyTop.delay(400).animate({width : "25%"}, 400);					
					NCSpricingWrap.delay(600).queue(function() {
						jQuery(this).css({"justify-content" : "flex-start", "-webkit-justify-content" : "flex-start"});
						jQuery(this).dequeue();
					});
					NCSshortcodeForm.delay(1000).slideDown(400);
					NCSExpand.delay(1000).slideDown(400);
				}else{					
					NCSpriceTop.hide();						
					NCSsaveUp.show();								
					NCStitleSave.css({height: "auto", padding: "1% 0 1.8% 2%"});									
					NCSImgWrapper.css({width : "50%"});								
					NCStitleSaveNew.css({width : "75%"});
					NCSmonthlyTop.css({width : "25%"});
					NCSpricingWrap.css({width : "50%", "min-width" : "50%", "justify-content" : "flex-start", "-webkit-justify-content" : "flex-start"});
					NCSmonthlyTopContain.fadeOut(200);					
					NCSshortcodeForm.delay(300).slideDown(400);
					NCSExpand.delay(300).slideDown(400);
					NCSExpandMobile.delay(800).fadeIn(200);
				}
			}else if (NCSclose.is(':visible')){//if the special is open
				NCSclose.hide();
				NCScloseArrow.hide();
				NCSopen.show();
				NCSopenArrow.show();
				NCSocTab.removeAttr('style');
				NCSImg.delay(1000).queue(function() {
					jQuery(this).removeAttr('style');
					jQuery(this).dequeue();
				});
				
				if(NCSExpand.css("border-top") != "1px solid rgb(0, 0, 0)"){//checking page size through css applied in a media query at 850px wide					
					NCSExpandMobile.hide();
					NCSqsInner.css({height : NCSqsInner.outerHeight()+"px"});								
					NCStitleSave.removeAttr('style');
					NCSshortcodeForm.slideUp(400, function () { 
						jQuery(this).removeAttr('style');
					});	
					NCSExpand.slideUp(400, function () { 
						jQuery(this).removeAttr('style');
					});											
					NCSsaveUp.fadeOut(400);
					NCSqsInner.delay(600).queue(function(next) { 
						jQuery(this).removeAttr('style'); next(); 
					});		
					NCSmonthlyTop.delay(600).animate({width : "50%"}, 400, function () { 
						jQuery(this).removeAttr('style');
					});	
					NCStitleSaveNew.delay(600).animate({width : "50%"}, 400, function () { 
						jQuery(this).removeAttr('style');
					});					
					NCSpricingWrap.delay(600).animate({width : "75%", "min-width" : "75%"}, 400, function () { 
						jQuery(this).removeAttr('style');						
					});
					NCSImgWrapper.delay(1000).queue(function() {					
						jQuery(this).removeAttr('style');
						jQuery(this).dequeue();
					});
					NCSpriceTop.delay(1200).fadeIn(200);
					NCSmonthlyTopContain.delay(1200).fadeIn(200);
				}else if (NCSExpand.css("border-top") == "1px solid rgb(0, 0, 0)"){//catch to remove styles when the screen size is switched to mobile and the special is closed
					NCSsaveUp.hide();
					NCSpriceTop.show();
					NCSmonthlyTop.removeAttr('style');				
					NCStitleSaveNew.removeAttr('style');									
					NCSpricingWrap.removeAttr('style');			
					NCSImgWrapper.removeAttr('style');					
					NCStitleSave.removeAttr('style');
					NCSExpandMobile.fadeOut(200);					
					NCSshortcodeForm.delay(300).slideUp(400, function () { 
						jQuery(this).removeAttr('style');
					});	
					NCSExpand.delay(300).slideUp(400, function () { 
						jQuery(this).removeAttr('style');
					});	
					NCSmonthlyTopContain.delay(800).fadeIn(200);					
				}								
			}			
		});	
		
		jQuery('.NCSSCFormdiscMobileBtn span').click(function(){
			var NCSSCDiscContent = jQuery(this).parents('.qsContent').find('.NCSFormSCDiscMobile');
			if (NCSSCDiscContent.css('display') !== 'block'){
				NCSSCDiscContent.slideDown();
			}else{
				NCSSCDiscContent.slideUp();
			}
		});
		
		/*jQuery('.disclaimerLink, .ncsDiscClose').click(function() {//open or close disclaimer
			var disc = jQuery(this).parents('.qsContent').find('.qDisclaimer');
			if (jQuery(disc).css('position') == "fixed" || jQuery('.qDisclaimer:visible').css('position') == "fixed" || jQuery(disc).css('position') == "absolute" || jQuery('.qDisclaimer:visible').css('position') == "absolute") {				
				if (jQuery(disc).css('display') == "block") {
					if(!jQuery(NCSspecForm).is(':visible')){
						jQuery(disc).fadeOut();
						jQuery('#q_overlay').fadeOut();
					}else{
						jQuery(disc).fadeOut();
					}
				}else{					
					jQuery(disc).fadeIn();
					jQuery('#q_overlay').fadeIn();			
				} 
			}else {		
				if (jQuery(disc).css("display") == "block") {			
					jQuery(disc).slideUp(400);
				}else{	
					jQuery('.qDisclaimer:visible').slideUp(400);
					jQuery(disc).slideDown(400);
				}
			}
		});	*/		
		
		/*function autoCloseOpenOverlay(){//automatically open and close overlays as needed
			if(jQuery(NCSdisClaim).css("position") != "fixed" && jQuery(NCSdisClaim).css("position") != "absolute" && jQuery(NCSdisClaim).is(':visible') && !jQuery(NCSspecForm).is(':visible')){
				jQuery('#q_overlay').fadeOut();
			}else if((jQuery(NCSdisClaim).css("position") == "fixed" || jQuery(NCSdisClaim).css("position") == "absolute") && jQuery(NCSdisClaim).is(':visible') && !jQuery(NCSspecForm).is(':visible')) {
				jQuery('#q_overlay').fadeIn();				
			}
		}*/
		//jQuery(window).resize(autoCloseOpenOverlay);		
	} //end specials page js	
});// end document ready js

/*=================================================================
Validate form data contents
=================================================================*/
var bColorReset = jQuery('.specialsFormContainer input:eq(0)').css("border-color"),
    bColor = "#c00",
	leadForm = '';

jQuery('.ncsFormSubmit').click(function() {
	leadForm = jQuery("#getSpecialForm-" + jQuery(this).attr("rel") );
	var req = jQuery(leadForm).find('.customer_first, .customer_last'),
	cEmail = jQuery(leadForm).find('.customer_email'),
	cPhone = jQuery(leadForm).find('.customer_phone'),
	NCSPhoneregEx = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/,
	NCSEmailRegEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
	NCSReqTxt = jQuery('.NCSRequiredFormParagraph');
	errors = false;	
	
	jQuery(req).css({borderColor:bColorReset});
	jQuery(cEmail).css({borderColor:bColorReset});
	jQuery(cPhone).css({borderColor:bColorReset});
	
	jQuery.each(NCSReqTxt, function(i, obj){
		if (jQuery(this).css('display') == "block") { //clear any pre-existing error messages from the form if there are any
			jQuery(this).html('');
			jQuery(this).hide();
		}
	});
	
	jQuery.each( req, function(i, obj) {
		if( jQuery(obj).val() == '' || jQuery(obj).val().length <= 1) { //name validation
			if( jQuery(obj).val() == '') {
				jQuery(this).next(NCSReqTxt).show().html('this field is required');				
			}else{
				jQuery(this).next(NCSReqTxt).show().html('please use 2 or more characters');				
			}
			jQuery(obj).css({borderColor: bColor});			
			errors = true;
		}
	});
	
	if( !jQuery(cEmail).val() && !jQuery(cPhone).val()) { //catch for situations where there is no email or phone given
		jQuery(cEmail).next(NCSReqTxt).show().html('you must provide either an email or a phone number');		
		jQuery(cPhone).next(NCSReqTxt).show().html('you must provide either an email or a phone number');		
		jQuery(cEmail).css({borderColor: bColor});
		jQuery(cPhone).css({borderColor: bColor});
		errors = true;
	}else {
		if( jQuery(cEmail).val().length) { //email validation
			if(!jQuery(cEmail).val().match(NCSEmailRegEx)) {
				jQuery(cEmail).next(NCSReqTxt).show().html('please enter a valid email address');				
				jQuery(cEmail).css({borderColor: bColor});
				errors = true;			
			}
		}
		if( jQuery(cPhone).val().length) { //phone validation
			if(!jQuery(cPhone).val().match(NCSPhoneregEx)) {
				jQuery(cPhone).next(NCSReqTxt).show().html('please enter a valid 9 digit phone number');				
				jQuery(cPhone).css({borderColor: bColor});
				errors = true;					
			}
		}
	}
	
	if( !errors ){
		jQuery(this).css({opacity:".7"}).attr("disabled", "disabled"); //disable the submit button assuming that there are no errors
		var data = {
			type: "POST",
			action: 'ncs_form_push_action',
			contents: jQuery(leadForm).serialize()
		};
		var NCSFormPost = 
		jQuery.post(ajaxurl, data, function(response) {	//submit the form data		
			response = jQuery.trim(response);			
			if( response.substr(0,1) == '1' ){ //if all is well send the customer to the thank you page
				var urlElems = response.split( "|" );
				location.href = "http://" + location.hostname + "/" + urlElems[1] + "/?sID=" + urlElems[2];
			}
			else {
				//alert(response);
				if( response.substr(0,1) == '0' ){ //catch server-side errors from submitted data
					var errs = response.replace("0-",""),
					errsArr = errs.split("|");		
					
					if ( errsArr[1] == 'Error: Could not send mail' ){
						alert(errsArr[1]);
					}
					else { // second level server side validation errors catch						
						var errFields = errsArr[2],	//remove invalid submission portion of array and only keep specific errors in a single variable						
						errsFieldsArr = errFields.split(","); //turn errors variable back into an array				
						
						errsFieldsArr.pop();//remove last blank error message from array
						
						var objSearch = '';	
						
						errsFieldsArr.forEach(function(errsFieldsArr){//add periods to each error to turn them into classes
							objSearch += "." + errsFieldsArr;
							objSearch += " ";
						});								
							
						var ObjArr = objSearch.split(" "); //turn classes collection back into an array
						
						ObjArr.forEach(function(ObjArr){							
							var objElements = jQuery(leadForm).find(ObjArr); //find the elements associated with each class
							if(ObjArr === '.customer_first'){ //first name catch
								if (objElements.val() == '') {
									jQuery(ObjArr).css({borderColor: bColor});
									jQuery(ObjArr).next(NCSReqTxt).show().html('this field is required');	
								}else if(objElements.val().length <= 1){
									jQuery(ObjArr).css({borderColor: bColor});
									jQuery(ObjArr).next(NCSReqTxt).show().html('please use 2 or more characters');				
								}
							}
							if(ObjArr === '.customer_last'){ //last name catch
								if (objElements.val() == '') {									
									jQuery(ObjArr).next(NCSReqTxt).show().html('this field is required');
									jQuery(ObjArr).css({borderColor: bColor});
								}else if(objElements.val().length <= 1){									
									jQuery(ObjArr).next(NCSReqTxt).show().html('please use 2 or more characters');	
									jQuery(ObjArr).css({borderColor: bColor});
								}
							}
							if((ObjArr === '.customer_email') || (ObjArr === '.customer_phone')){ //email and phone catch
								if( !jQuery('.customer_email').val() && !jQuery('.customer_phone').val()) { //catch for situations where there is no email or phone given
									jQuery(ObjArr).next(NCSReqTxt).show().html('you must provide either an email or a phone number');												
									jQuery(ObjArr).css({borderColor: bColor});									
								}else {
									if( objElements.val().length && ObjArr === '.customer_email') { //email address validation
										if(!objElements.val().match(NCSEmailRegEx)) {
											jQuery(ObjArr).next(NCSReqTxt).show().html('please enter a valid email address');				
											jQuery(ObjArr).css({borderColor: bColor});														
										}
									}
									if( objElements.val().length && ObjArr === '.customer_phone') { //phone number validation
										if(!objElements.val().match(NCSPhoneregEx)) {
											jQuery(ObjArr).next(NCSReqTxt).show().html('please enter a valid 9 digit phone number');				
											jQuery(ObjArr).css({borderColor: bColor});														
										}
									}
								}								
							}
						});
						
						NCSFormPost.abort(); //abort the post
						jQuery(".ncsFormSubmit").css({opacity:"1"}).removeAttr("disabled");	//re-enable the button for resubmission					
					}
				}
				else {
					//alert("Error:" + response);
				}
			}
		});
	}
});
/*=================================================================
Validate search form data contents -- legacy code for old landing pages
===================================================================*/
var DbColor = jQuery('.findUsForm input:eq(0)').css("border-color");

jQuery('.findUs').click(function() {
	var mapH = (jQuery(window).height() - 100);
	var mapW = (jQuery(window).width() - 200);
	if(mapW < 300) {
		mapW = 300
	}
	var mapForm = jQuery(this).parents('.findUsForm');
	var req = jQuery(mapForm).find('.required');
	var errors = new Array();
	
	jQuery(req).css({borderColor:DbColor});
	
	jQuery.each( req, function(i, obj) {
		if( !(jQuery(obj).val()) ) {
			errors[i] = jQuery(obj);
		}
	});
	if( errors.length == 0 ) {
		var data = {
			type: "POST",
			action: 'ncs_directions_push_action',
			contents: jQuery(mapForm).serialize()
		};
		jQuery.post(ajaxurl, data, function(resp) {
      resp = jQuery.trim(resp); /* pw 3-3-2015 */
			window.open(resp, '_blank', 'width=' + mapW + ',height=' + mapH + ',directories=no,location=no,menubar=no,scrollbars=no,toolbar=no');
		});
	}
	else {
		jQuery.each(errors, function(i, obj) {
			jQuery(obj).css({borderColor: bColor});
		});
	}
});

/*=================================================================
VLP Back to Top -- legacy code for old landing pages
===================================================================*/
function backToTop() {
	jQuery('html, body').animate({scrollTop : 0},1000);
}
jQuery('.vlp_backToTop').click(function(){
	backToTop();
});