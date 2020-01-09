(function ($) {
    $.Master = function (settings) {
        var config = {
			weekstart : 0,
            lang: {
                button_text: "Choose file...",
				mbutton_text: "Choose multiple files...",
                empty_text: "No file...",
				monthsFull : '',
				monthsShort : '',
				weeksFull : '',
				weeksShort : '',
				weeksMed : '',
				today : "Today",
				delBtn : "Delete Record",
				clear : "Clear",
				selProject : "Select Project",
				invImage : "Invalid Image type",
				delMsg1: "Are you sure you want to delete this record?",
				delMsg2: "This action cannot be undone!!!",
				working: "working..."
            }
        };

        if (settings) {
            $.extend(config, settings);
        }
		
        var itemid = ($.url(true).param('id')) ? $.url(true).param('id') : 0;
        var plugname = $.url(true).param('plugin');
        var modname = $.url(true).param('module');
        var posturl = (plugname ? "../plugins/" + plugname + "/controller.php" : (modname ? "../modules/" + modname + "/controller.php" : ADMINURL + "/controller.php"));
        
		$('.wojo.dropdown').dropdown();

		$('select[data-cover="true"]').selecter({cover: true});
		$('select[data-links="true"]').selecter({links: true});
		$("select").selecter();
		$('body [data-content]').tooltip();
		$(".wojo.scrollbox").scroller();
		$('.wojo.carousel').slick();
		$('a[data-lightbox=true]').lightbox();
		$("table.sortable").tablesort();
		$("[data-toggler=true]").checkbox();
		$('.wojo.range.slider').range();
		$('.eq').matchHeight();
		$('.roundchart').easyPieChart({easing: 'easeOutQuad'});
		$(".wojo.progress").simpleprogress();
		if ($.fn.sparkline) {
			var sparkline = function() {
				$('.sparkline').sparkline('html',{enableTagOptions: true, tagOptionsPrefix: "data"});
			}
			var sparkResize;
			$(window).resize(function() {
				clearTimeout(sparkResize);
				sparkResize = setTimeout(sparkline, 500);
			});
			sparkline();
		}
	
		$(".filefield").filestyle({buttonText: config.lang.button_text});
		$(".multifile").filestyle({buttonText: config.lang.mbutton_text});
		
		/* == Datepicker == */
        $.fn.datetimepicker.dates['en'] = 
		 {
			  days:        config.lang.weeksFull,
			  daysShort:   config.lang.weeksMed,
			  daysMin:     config.lang.weeksShort,
			  months:      config.lang.monthsFull,
			  monthsShort: config.lang.monthsShort,
			  meridiem:    ["am", "pm"],
			  suffix:      ["st", "nd", "rd", "th"],
			  today:       config.lang.today
		}
			
		$('body [data-datepicker]').datetimepicker({
			weekStart: config.weekstart,
			format: "dd, MM yyyy",
			autoclose: true,
			todayBtn: true,
			linkField: true,
			linkFormat: "yyyy-mm-dd",
			startView: 2,
			minView: 2,
			forceParse: 0,
		});
		
		$('body [data-timepicker]').datetimepicker({
			weekStart: config.weekstart,
			format: "HH:ii:00",
			autoclose: true,
			todayBtn: true,
			linkField: true,
			startView: 1,
			minView: 0,
			maxView: 1,
			forceParse: 0
		});

		/* == Close Message == */
		$('body').on('click', '.message i.close.icon', function () {
			var $msgbox = $(this).closest('.message');
			$msgbox.slideUp(500, function () {
				$(this).remove();
			});
		});

		/* == Show/Hide Panel == */
		$('body').on('click', '[data-showhide]', function () {
			var dataset = $(this).data('set');
			$('body').find(dataset.el).slideToggle();
			$(this).children().toggleClass('down up');
		});

		/* == Check All == */
		 $('#masterCheckbox').click(function (e) {
			var parent = $(this).data('parent'); 
			var $checkBoxes = $(parent + " input[type=checkbox]");
			$($checkBoxes).prop("checked",$(this).prop("checked"));
		});
	
		/* == Clear Session Debug Queries == */
		$('body').on('click', 'a.clear_session', function () {
			$.get(ADMINURL + '/helper.php', {ClearSessionQueries: 1});
			$(this).css('color','#222');
		});
		
		/* == Close Note == */
		$('body').on('click', '.note i.close.icon', function () {
			var $msgbox = $(this).closest('.note');
			$msgbox.slideUp(500, function () {
				$(this).remove();
			});
		});
		
		/* == Language Switcher == */
        $('#langmenu').on('click', 'a', function() {
            var target = $(this).attr('href');
            $.cookie("LANG_PMP", $(this).data('lang'), {
                expires: 120,
                path: '/'
            });
            $('body').fadeOut(1000, function() {
                window.location.href = target;
            });
            return false;
        });
  
		/* == Tabs == */
		$(".wojo.tab.item").hide();
		$(".wojo.tabs a:first").addClass("active").show();
		$(".wojo.tab.item:first").show();
		$(".wojo.tabs a").on('click', function () {
			$(".wojo.tabs a").removeClass("active");
			$(this).addClass("active");
			$(".wojo.tab.item").hide();
			var activeTab = $(this).data("tab");
			$(activeTab).show();
			return false;
		});
		
		

		/* == Single File Picker == */
		$('body').on('click', '.filepicker', function () {
			var type = $(this).prev('input').data('ext');
			Messi.load('controller.php', {
				pickFile: 1,
				ext: type
			}, {
				title: config.lang.button_text
			});
		});

		$("body").on("click", ".filelist a", function () {
			var path = $(this).data('path');
			$('input[name=filename], input[name=attr]').val(path);
			$('.messi-modal, .messi').remove();

		});

		/* == Editor == 
		$('.bodypost').redactor({
			observeLinks: true,
			wym: true,
			toolbarFixed: false,
			minHeight: 300,
			maxHeight: 500,
			plugins: ['fullscreen']
		});*/

		/* == Editor == 
		$('.fullpage').redactor({
			observeLinks: true,
			toolbarFixed: false,
			minHeight: 500,
			maxHeight: 800,
			iframe: true,
			focus: true,
			plugins: ['fullscreen']
		});
		
		$('.altpost').redactor({
			observeLinks: true,
			minHeight: 100,
			buttons: ['formatting', 'bold', 'italic', 'unorderedlist', 'orderedlist', 'outdent', 'indent'],
			wym: true,
			plugins: ['fullscreen']
		});

		/* == Submit Search by date == */
		$("#doDates").on('click', function () {
			$("#admin_form").submit();
			return false;
		});

		/* == Avatar Upload == */
        $('[data-type="image"]').ezdz({
            text: 'drop a picture',
            validators: {
                maxWidth: 2400,
                maxHeight: 1200
            },
            reject: function(file, errors) {
                if (errors.mimeType) {
                    new Messi(file.name + ' must be an image.', {
                        title: 'Error',
                        modal: true
                    });
                }
                if (errors.maxWidth || errors.maxHeight) {
                    new Messi(file.name + ' must be width:2400px, and height:1200px  max.', {
                        title: 'Error',
                        modal: true
                    });
                }
            }
        });
	
		/* == From/To date range == */
        //var nowTemp = new Date();
        //var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		$('#fromdate')
			.datetimepicker({
				weekStart: config.weekstart,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				minView: 2,
				forceParse: 0
			})
			.on('changeDate', function(e) {
				var newDate = new Date(e.date);
				newDate.setDate(newDate.getDate() + 1);
				$('#enddate').datetimepicker('setStartDate', newDate);
		});

		/* == Set Status == */
		$('body').on('click', 'a.doStatus', function() {
		    var data = $(this).data("set");
		    data.value = data.value == 1 ? 0 : 1;
		    $(this).attr("data-set", JSON.stringify(data));
		    $(this).children().toggleClass(data.toggle).toggleClass(data.togglealt);
		    if (data.remove) {
		       var $parent = $(this).closest(data.parent);
		        $parent.addClass('loading');
		    }

		    $.get(ADMINURL + "/helper.php", {
		        quickStatus: 1,
		        field: data.field,
		        id: data.id,
		        table: data.table,
		        value: data.value
		    }, function(e) {}, "json").done(function(json) {
		        if (data.response) {
		            $.sticky(decodeURIComponent(json.message), {
		                type: json.type,
		                title: json.title
		            });
		        }
		        if (data.remove) {
		            $parent.removeClass('loading');
		            $parent.slideUp();
		        }
		    });
		});
		
		/* == Set Status ws== */
		$('body').on('click', 'a.doStatusws', function() {
		    var data = $(this).data("set");
		     var id = data.ws_id;
		    data.value = data.value == 1 ? 0 : 1;
		   $(this).attr("data-set", JSON.stringify(data));
		    $(this).children().toggleClass(data.toggle).toggleClass(data.togglealt);
		    if (data.remove) {
		       var $parent = $(this).closest(data.parent);
		        $parent.addClass('loading');
		    }

		    $.get(ADMINURL + "/helper.php", {
		        quickStatus: 1,
		        field: data.field,
		        id: data.id,
		        table: data.table,
		        value: data.value,
		        ws_id: data.ws_id
		    }, function(e) {}, "json").done(function(json) {
		        if (data.response) {
		            $.sticky(decodeURIComponent(json.message), {
		                type: json.type,
		                title: json.title
		            });
		               
		            
		        }
		        if (data.remove) {
		           
		            $parent.removeClass('loading');
		            $parent.slideUp();
		        }
		        
		        
		    });
		    
		    $.ajax({
	            type: "get",
	            url: ADMINURL + '/helper.php',
	            data: {
	            	loadWebSpecialsPreview: 1,
	            	 value: id   
	            },
	           
	            success: function(res) {
	            	
	                $('#webspecialspreview').html(res).show();
	            }
	        });     
		    
		});
	
		/* == Master Form == */
		$('body').on('click', 'button[name=dosubmit], .dosubmit', function() {
		    var $parent = ($(this).is("button") || $(this).hasClass("bform")) ? $(this).closest('.wojo.form') : $('.messi-content').find('.wojo.form');
			var redirect = $(this).data('redirect');
		    $('#wojo_form').validate({
		        errorPlacement: function(error, element) {}
		    });
            var action = $(this).data('action');
		    function showResponse(json) {
				setTimeout(function() {
				   $($parent).removeClass("loading");
				}, 500);
				$.sticky(decodeURIComponent(json.message), {
					autoclose: 12000,
					type: json.type,
					title: json.title
				});
				
				if(json.type === "success" && redirect){
					setTimeout(function() {
					   window.location.href = redirect;
					}, 500);
				}
		    }

		    function showLoader() {
		        $($parent).addClass("loading");
		    }
		    var options = {
		        target: null,
		        beforeSubmit: showLoader,
		        success: showResponse,
		        type: "post",
		        url: posturl,
				data: {action: action},
		        dataType: 'json'
		    };

		    $('#wojo_form').ajaxForm(options).submit();
		});
		
		
		/* == Basic Submit Form == */
		$("#doFormSubmit").on('click', function () {
			$("#wojo_form").submit();
			return false;
		});
	
		/* == Delete Multiple == */
		$('body').on('click', 'button[name=mdelete]', function () {
			var form = $(this).data('form');
			function showResponse(json) {
				$("button[name='mdelete']").removeClass("loading");
				$('.wojo.table tbody tr').each(function () {
					if ($(this).find('input:checked').length) {
						$(this).fadeOut(400, function () {
							$(this).remove();
						});
					}
				});
				$("#msgholder").html(json.message);
			}

			function showLoader() {
				$("button[name='mdelete']").addClass("loading");
				$('.wojo.table tbody tr').each(function () {
					if ($(this).find('input:checked').length) {
						$(this).animate({
							'backgroundColor': '#FFBFBF'
						}, 400);
					}
				});

			}

			var options = {
				target: "#msgholder",
				beforeSubmit: showLoader,
				success: showResponse,
				type: "post",
				url: posturl,
				dataType: 'json'
			};

			$(form).ajaxForm(options).submit();
		});

		/* == Delete Item == */
		$('body').on('click', 'a.delete', function () {
			var data = $(this).data("set");
			var id = data.ws_id;
			var $parent = $(this).closest(data.parent);
			new Messi("<div class=\"messi-warning\"><i class=\"huge icon negative notification sign\"></i><p>" + config.lang.delMsg1  + "<br><strong>" + config.lang.delMsg2  + "</strong></p></div>", {
				title: data.title,
				titleClass: '',
				modal: true,
				closeButton: true,
				buttons: [{
					id: 0,
					label: config.lang.delBtn,
					class: 'negative',
					icon: '<i class="icon trash"></i>',
					val: 'Y'
				}],
				callback: function (val) {
					$.ajax({
						type: 'post',
						url: posturl,
						dataType: 'json',
						data: {
							id: data.id,
							delete: data.option,
							extra: data.extra ? data.extra : null,
							title: encodeURIComponent(data.name)
						},
						beforeSend: function () {
							$parent.css({
								'opacity': '.35'
							});
						},
						success: function (json) {
							$parent.fadeOut(400, function () {
								$parent.remove();
							});
							$.sticky(decodeURIComponent(json.message), {
								type: json.type,
								title: json.title
							});
							
							$.ajax({
					            type: "get",
					            url: ADMINURL + '/helper.php',
					            data: {
					            	loadWebSpecialsPreview: 1,
					            	 value: id   
					            },
					           
					            success: function(res) {
					            	
					                $('#webspecialspreview').html(res).show();
					            }
					        }); 
							
						}

					});
				}
			});
		});

		/* == Add New Item == */
		$("#addNew").on('click', function () {
			var dataset = $(this).data("set");
			var fid = (dataset.id) ? '/helper.php?id=' + dataset.id : '/helper.php';
			Messi.load(ADMINURL + fid, {
				"addNew": dataset.option
			}, '',{
				title: dataset.title,
				buttons: [{
					id: 0,
					class: 'positive dosubmit',
					label: dataset.label,
					val: 'action',
					data: 'data-redirect=' + dataset.redirect
				}],
				callback: function (val) {}
			});
		});
	
		/* == Search == */
		$("#searchfield").on('keyup', function() {
		    var srch_string = $(this).val();
		    var type_string = $(this).prop('name');
		    var $icon = $(this).parent().find('.icon');
		    if (srch_string.length > 3) {
		        $.ajax({
		            type: "get",
		            url: ADMINURL + '/helper.php',
		            data: {
		                doLiveSearch: 1,
		                value: srch_string,
		                type: type_string
		            },
		            beforeSend: function() {
		                $icon.toggleClass("spinner circles spinning");
		            },
		            success: function(res) {
		                $('#suggestions').html(res).show();
		                $icon.removeClass("spinner circles spinning").addClass("find");
		                $("input").blur(function() {
		                    $('#suggestions').fadeOut();
		                });
		            }
		        });
		    }
		    return false;
		});
		
		/* == Main Menu == */
        var MenuNav = (function() {
            var $listItems = $('nav > ul > li'),
                $menuItems = $listItems.children('a'),
                $body = $('body'),
                current = -1;

            function init() {
                $menuItems.on('click', open);
                $listItems.on('click', function(event) {
                    event.stopPropagation();
                });
                $("div.mobilemenu a").on('click', function(event) {
                    $('nav').slideToggle();
                });
				
            }

            function open(event) {
                if (current !== -1) {
                    $listItems.eq(current).removeClass('opened').find('.dotoggle').removeClass('minus').addClass('add');
                }

                var $item = $(event.currentTarget).parent('li'),
                    idx = $item.index();

                if (current === idx) {
                    $item.removeClass('opened').find('.dotoggle').removeClass('minus').addClass('add');
                    current = -1;
                } else {
                    $item.addClass('opened').find('.dotoggle').removeClass('add').addClass('minus');
                    current = idx;
                    $body.off('click').on('click', close);
                }
				
				var $ul = $item.find('ul');
				if(!$ul.isOnScreen()) {
					$ul.css({'bottom':'0','top':'auto'});
				}

            }

            function close(event) {
                $listItems.eq(current).removeClass('opened').find('.dotoggle').removeClass('minus').addClass('add');
                current = -1;
            }

            return {
                init: init
            };

        })();

        MenuNav.init();

		$(".mnav").on('click', function() {
		    $("nav").toggle();
		});

		$(window).resize(function() {
		    if ($(window).width() > 769) {
		        $('nav').css("display", "inline-block");
		    } else {
		        $('nav').css("display", "none");
		    }
		});
		
		/* == Submit Search by date == */
		$("#doDates").on('click', function () {
			$("#wojo_form").submit();
			return false;
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
				url: ADMINURL + "/helper.php",
				data: ({
					'title': val,
					'type': data.type,
					'key': data.key,
					'path': data.path,
					'id': data.id,
				    'quickedit': 1
				}),
				beforeSend: function() {
					$this.text(config.lang.working).animate({
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
		
		 /* == Inline Edit WS == */
		$('#editablews').editableTableWidgetws();
		$('#editablews')
		.on('validate', '[data-editablews]', function(e, val) {
			if (val === "") {
				return false;
			}
		})
		.on('change', '[data-editablews]', function(e, val) {
			var data = $(this).data('set');
			var $this = $(this);
			$.ajax({
				type: "POST",
				url: ADMINURL + "/helper.php",
				data: ({
					'title': val,
					'type': data.type,
					'key': data.key,
					'path': data.path,
					'id': data.id,
					'ws_id': data.ws_id,
				    'quickedit': 1
				}),
				beforeSend: function() {
					$this.text(config.lang.working).animate({
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
					
					$.ajax({
			            type: "get",
			            url: ADMINURL + '/helper.php',
			            data: {
			            	loadWebSpecialsPreview: 1,
			            	 value: data.ws_id   
			            },
			           
			            success: function(res) {
			            	
			                $('#webspecialspreview').html(res).show();
      
			               
			            }
			        });
				}
				
			});
		});
		
    };
})(jQuery);