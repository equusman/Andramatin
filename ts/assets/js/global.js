/*** INIT SCRIPTS ***/
$(function(){
	pfw_prep_valign();
	pfw_prep_viewonhover();
	pfw_prep_input_icon();
	pfw_prep_message_block();
	pfw_ajax_init();
	pfw_prep_submit_button();
	pfw_prep_menulink();
});



















/*** SUPPORTING SCRIPTS ***/
/*** Preparing clickable menu li ***/
function pfw_prep_menulink(){
	$('.navigation-bar-content.container div.element a.dropdown-toggle').each(function(){
		$(this).css({padding:'15px 25px 15px 15px', display:'block'}).addClass('full').parent().css({padding:'0px'});
		
		// $(this).parent().css('cursor','pointer').click(function(){
			// $('a.dropdown-toggle',this).click();
		// });
	});
}

/*** Preparing centered vertical align elements ***/
function pfw_prep_valign(){
	//var wh = $(window).height();
	$('.vert-c').each(function(){
		var wh = $(this).parent().height();
		$(this).css({marginTop: ($(this).height()>wh? 0 : -($(this).height()/2))});
	});
}

function pfw_prep_message_block(){
	if ($('#message-bar').length>0) {
		if ($('#message-bar .icon').length<=0) {
			$('#message-bar').append('<div class="icon icon-l"></div>');
		}
		if ($('#message-bar .icon-label').length<=0) {
			$('#message-bar').append('<div class="icon-label"></div>');
		}
	}
}
function pfw_prep_viewonhover(){
	$('.view-hovered').parent().hover(function(){
		$('.view-hovered',this).stop().slideDown();
	}, function(){
		$('.view-hovered',this).stop().slideUp();
	});
}

function pfw_prep_input_icon(){
	$('input[data-icon]').each(function(){
		var icon = $(this).data('icon');
		var type = $(this).attr('type').toLowerCase();
		$(this).wrap('<div class="input-txt-box"></div>').before('<div class="icon icon-'+icon+' icon-s"></div>');
		if ($(this).data('placeholder') && (type=='text')) {
			$(this).focus(function(){ 
				if($(this).val()==$(this).data('placeholder')) { 
					$(this).val('').removeClass('placeholder');
				} 
			}).blur(function(){
				if($(this).val().replace(/\s+/gi,'')=='') { 
					$(this).val($(this).data('placeholder')).addClass('placeholder');
				} else {
					$(this).removeClass('placeholder')
				}
			});
			if($(this).val().replace(/\s+/gi,'')=='') { 
				$(this).val($(this).data('placeholder')).addClass('placeholder');
			}
		}
		if ($(this).data('placeholder') && (type=='password')) {
			var pl = $('<input type="text" class="input-txt placeholder" value="Password" />');
			var o = $(this);
			o.after(pl);
			pl.focus(function(){ 
				$(this).hide().prev().show().focus(); 
			});
			o.blur(function(){
				if($(this).val().replace(/\s+/gi,'')=='') { 
					$(this).hide().next().show();
				} 
			});
			if(o.val().replace(/\s+/gi,'')=='') { 
				$(this).hide().next().show();
			}
		}
	});
}

function pfw_ajax_init(){
	var obj = $( "#ajax-loading" ).html('Loading...');
	if (obj.length<=0) obj = $('<div id="ajax-loading">Loading...</div>').prependTo($('body')).hide();
	
	$( document ).ajaxStart(function() {
	  //$( "#ajax-loading" ).slideDown(50);
	  $( "#ajax-loading" ).show();
	}).ajaxComplete(function(e,xhr,cfg) {
		if (((xhr.responseText.indexOf('modal-outer')>-1) && (xhr.responseText.indexOf('modal-content')>-1)) || (xhr.responseText.indexOf('html_response')>-1)) $( "#ajax-loading" ).slideUp(100);
	});
}

var _CMODAL;
function pfw_prep_submit_button(prt){
	var btn = $('input[type="submit"], button[type="submit"]');
	if(typeof prt != 'undefined'){
		btn = $('input[type="submit"], button[type="submit"]',prt);
	}
	btn.each(function(){
		var ob= $(this);
		$(this).unbind('click').click(function(){
			var frm = $(this).closest('form');
			if (frm.length>0) {
				var tgt = frm.attr('action');
				$.post(tgt,frm.serialize(), function(res){
					console.log(res);
					if (res['message'] && (res['message']!='')) {
						if ($('#message-bar').length>0) {
							var _st = res['status']? 'success': 'error';
							$('#message-bar').removeClass('success,error').addClass(_st);
							$('#message-bar .icon').removeClass('icon-checkmark, icon-cancel-2').addClass(res['status']? 'icon-checkmark': 'icon-cancel-2');
							$('#message-bar .icon-label').html(res['message']);
							$('#message-bar').slideDown('fast');
							if (res['redirect'] && (_st=='success')) {
								$( "#ajax-loading" ).html(res['message']);
							} else $( "#ajax-loading" ).slideUp(100);
						}else $( "#ajax-loading" ).slideUp(100); 
						//else alert(res);
						//$( "#ajax-loading" ).slideUp(100);
						console.log('1');
					}
					if (res['redirect']) {
						console.log('2');
						var to = res['redirect-time']? parseInt(res['redirect-time'],10) : 0;
						if (res['targetClass']) {
							$.get(res['redirect'],{},function(data){
								//if ($('.'+res['targetClass'],$(data)).length>0)
									$('.'+res['targetClass']).replaceWith(data);
								//else $('.'+res['targetClass']).html(data);	
							});
							$( "#ajax-loading" ).slideUp(100);
							//window.setTimeout(function(){ $('.'+res['targetClass']).load(res['redirect']); }, to);
						} else {
							window.setTimeout(function(){ document.location = res['redirect']; }, to);
						}
						return false;
					} 
					if (ob.data('submitted')) {
						console.log('3');
						ob.data('submitted')(res);
						$( "#ajax-loading" ).slideUp(100);
					}
				});
			}
			return false;
		});
	});
	
	var actlinks = $('a[data-type=post]');
	actlinks.each(function(){
		$(this).unbind('click').click(function(){
			var tgt = $(this).attr('href');
			var pdata = $(this).data('post');
			var ob = $(this);
			$.post(tgt,pdata,function(res){
				if (res['redirect']) {
					document.location = res['redirect'];
				} else if (ob.data('submitted')) {
					ob.data('submitted')(res);
				} else {
					alert(res);
				}
			});
			return false;
		});
	});
	
	$('div.pagination div.links a').click(function(){
		var md = $(this).closest('.modal-outer');
		if (md.length>0) {
			$.get($(this).attr('href'),function(res){
				md.replaceWith(res);
				//_CMODAL = md;
				//window.setTimeout(function(){pfw_prep_submit_button(_CMODAL);},100);
			});
		} else {
			document.location = $(this).attr('href');
		}
		return false;
	});
	
	var buttonChk = $('button[data-check-controls]').hide();

	var btnlink = $('button[data-link], input[type="button"][data-link], input[type="submit"][data-link]');
	btnlink.click(function(){
		document.location = $(this).data('link');
		return false;
	});

}

function pfw_checkAll(obj){
	var ch = $('.'+$(obj).data('children'));
	ch.prop('checked',$(obj).is(':checked'));
	pfw_checkBoundChkControls(obj);
}
function pfw_checkItem(obj){
	var ch = $('.'+$(obj).data('parent'));
	var sb = $('.'+$(obj).attr('class')+'');
	var sbc = $('.'+$(obj).attr('class')+':checked');
	ch.prop('checked',(sb.length==sbc.length));
	pfw_checkBoundChkControls(obj);
}

function pfw_checkBoundChkControls(obj){
	var sb = $('.'+$(obj).attr('class')+':checked');
	if (sb.length>0) {
		$('button[data-check-controls*='+$(obj).attr('class')+']').show();
	} else {
		$('button[data-check-controls*='+$(obj).attr('class')+']').hide();
	}
}

function pfw_addTableCounter(obj,col) {
	// use table 'tr' as obj
	if (!col) col = 0;
	obj.each(function(o,i){
		$('td:nth('+col+')',this).html(o+1);
	});
}