// Easy Modal v1.2
(function ($)
{
	var currentMousePos = { x: -1, y: -1 };
    var methods = {
        init: function (options)
        {
            var opts = $.extend({}, $.fn.emodal.defaults, options);
			return $(this).data('emodal', opts);
        },
        close: function (options)
        {
            var options = $.extend({
				speed: 'fast',
				overlay: true
			}, $.fn.emodal.defaults, options);
			
			var $this = $(this);
            var opts = $this.data('emodal');
            $this.removeClass('active').fadeOut(options.speed,function(){
                if(options.overlay)
					$('#modal-overlay').fadeOut(options.speed,function(){
						options.onClose();
					});
            })
			$(window).unbind('scroll.emodal').unbind('keyup.emodal');
            return this;
        },
		open: function()
		{
			var $this = $(this);
            var opts = $this.data('emodal');
			if(themes[opts.theme] === undefined)
			{
				var theme = themes[1];
			}
			else
			{
				var theme = themes[opts.theme];
			}
			// Check for and create Modal Overlay
			if(!$('#modal-overlay').length){ $('<div id="modal-overlay">').appendTo('body');	}
			
			// If not stackable close all other modals.
			if($('.modal.active').length)
			{
				$('.modal.active').each(function(){$(this).emodal('close',{speed:100,overlay:false})});
			}
			else
			{
				$('#modal-overlay').css('opacity',0).show(0);
			}
			
			$this.addClass('active');
			// Modal Clos Button
			
			if(!opts.closeDisabled && $('.close-modal',$this).length)
			{
				$('.close-modal',$this)
					.unbind('click')
					.click(function(){	$this.emodal('close');	})
					.themeClose(opts);
			}
			$('#modal-overlay')
				.unbind('click')
				.click(function()
				{
					if (opts.overlayClose == true)
					{
						$this.emodal('close');
					}
				})
				.themeOverlay(opts);
				
			if(opts.overlayEscClose == true)
			{
				$(window).bind('keyup.emodal',function(e){
					if($('.modal.active').length && e.keyCode == 27)
					{
						$this.emodal('close');
					}
				});
			}
			$this.themeModal(opts);
			$this.animation(opts.animation,opts);
			return $this;
		}/*,
        show: function ()
        {
            if (opts.type === 'Image')
            {
                container.css(
                {
                    maxWidth: opts.maxWidth,
                    maxHeight: opts.maxHeight
                });
                var abcs = $("a.eModal-Image")
                var prevButton = $('<a>')
					.attr('id',opts.prevId)
					.click(function (){
                    var current = $('.eModal-Opened')
					var prev = abcs.eq(abcs.index(current) - 1)
					current.removeClass('eModal-Opened')
                    if (prev.length <= 0) prev = abcs.eq(abcs.length)
					prev.addClass('eModal-Opened')
					container
						.animate({opacity: '.01'}, function (){
							var img = $("<img/>")
								.attr('src', prev.attr('href'))
								.css({
									maxWidth: '100%',
									maxHeight: '100%'
								})
								.load(function (){
									if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0)
									{
										alert('broken image!')
									}
									else
									{
										if (this.naturalWidth > opts.maxWidth) img.attr('width', opts.maxWidth)
										if (this.naturalHeight > opts.maxHeight) img.attr('height', opts.maxHeight)
										content
											.html(img)
											.css({opacity:'.01'})
										
										container
											.emodal('center')
											.animate({opacity:'.01'})
											.animate({opacity:'1'})
											
										content.animate({opacity: '1'})
									}
								})
						})
                    return false
                })
                var nextButton = $('<a>')
					.attr('id',opts.nextId)
					.click(function (){
						var current = $('.eModal-Opened')
						var next = abcs.eq(abcs.index(current) + 1)
						current.removeClass('eModal-Opened')
						if (next.length == 0) next = abcs.eq(0)
						next.addClass('eModal-Opened')
						container
							.animate({opacity: '.01'}, function (){
								var img = $("<img/>")
									.attr('src', next.attr('href'))
									.css({
										maxWidth: '100%',
										maxHeight: '100%'
									})
									.load(function (){
										if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0)
										{
											alert('broken image!')
										}
										else
										{
											if (this.naturalWidth > opts.maxWidth) img.attr('width', opts.maxWidth)
											if (this.naturalHeight > opts.maxHeight) img.attr('height', opts.maxHeight)
											content
												.html(img)
												.css({opacity:'.01'})
											
											container
												.emodal('center')
												.animate({opacity:'.01'})
												.animate({opacity:'1'})
												
											content.animate({opacity: '1'})
										}
									})
							})
						return false
					})
				var buttons = $('<div>')
					.attr('id',opts.buttonsId)
					.append(prevButton, nextButton)
					.appendTo(container)
            }
            if (opts.type === 'Link')
			{
                opts.requestData.url = $(this).attr('href')
                opts.requestData.iframeWidth = opts.maxWidth
                opts.requestData.iframeHeight = opts.maxHeight
            }
            var loaded = false
            if (opts.url != null){
                if (opts.type === 'Image'){
                    var img = $("<img/>")
						.attr('src', opts.url)
						.css({
							maxWidth: '100%',
							maxHeight: '100%'
						})
						.load(function (){
							if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0)								alert('broken image!')
							else {
								if (this.naturalWidth > opts.maxWidth) img.attr('width', opts.maxWidth)
								if (this.naturalHeight > opts.maxHeight) img.attr('height', opts.maxHeight)
								content.append(img)
								loaded = true
							}
						})
                }
                else
                {
                    $.post(opts.url, opts.requestData, function (data){
						content.prepend(data)
						if($('form',content).length)
						{
							$orig_action = $('form',content).attr('action').split('#');
							$('form',content).attr('action',"#" + $orig_action[1]).attr('action')
						}
                        container
							.show()
							.css({opacity: '.01'});
						if(opts.userMaxHeight > 0)
						{
							content.css({maxHeight: $(window).height() * (opts.userMaxHeight / 100) + 'px'});
						}
						else if(content.innerHeight() > opts.maxHeight && opts.type != 'Link')
						{
							content.css({maxHeight: (opts.maxHeight - 60) + 'px'});
						}
						
						if(opts.userHeight > 0)
						{
							content.css({height: opts.userHeight + 'px'});
						}
						
						if(opts.userMaxWidth > 0)
						{
							content.css({maxWidth: $(window).width() * (opts.userMaxWidth / 100) + 'px'});
						}
						
						if(opts.userWidth > 0)
						{
							content.css({width: opts.userWidth + 'px'});
						}
                        var title = content
							.find("#eModal-Title")
							.css({
								color: theme.contentTitleFontColor,
								fontFamily: theme.contentTitleFontFamily,
								fontSize: theme.contentTitleFontSize + 'px'
							})
                        if(title) title.attr('title', title.text()).appendTo(controls)
                        opts.onLoad()
                        if(opts.cf7form == true) loadCf7()
                        if(opts.gravityform == true) loadGravityForms()
                        loaded = true
                    })
                }
            }
        },*/
    };
    $.fn.emodal = function(method)
    {
        // Method calling logic
        if (methods[method])
        {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method)
        {
            return methods.init.apply(this, arguments);
        }
        else
        {
            $.error('Method ' + method + ' does not exist on jQuery.emodal');
        }
    };
	$.fn.themeOverlay = function(opts)
	{
		var $this = $(this);
		if(themes[opts.theme] === undefined)
		{
			var theme = themes[1];
		}
		else
		{
			var theme = themes[opts.theme];
		}
		return $this.addClass('theme-'+opts.theme).animate({
			backgroundColor: theme.overlayColor,
			opacity: theme.overlayOpacity / 100
		},opts.duration);
	};
	$.fn.themeModal = function(opts)
	{
		var $this = $(this);
		if(themes[opts.theme] === undefined)
		{
			var theme = themes[1];
		}
		else
		{
			var theme = themes[opts.theme];
		}
		if(opts.size == 'custom')
		{
			$this.css({
				'height': opts.userHeight ? opts.userHeight + opts.userHeightUnit : $this.css('height'),
				'width': opts.userWidth ? opts.userWidth + opts.userWidthUnit : $this.css('width'),
				'margin-left': opts.userWidth ? -(opts.userWidth / 2) + opts.userWidthUnit : $this.css('margin-left')
			});
		}
		return $this
			.addClass(opts.size)
			.addClass('theme-'+opts.theme)
			.css({
				color: theme.contentFontColor,
				backgroundColor: theme.containerBgColor,
				padding: theme.containerPadding + 'px',
				border: theme.containerBorderColor + ' ' + theme.containerBorderStyle + ' ' + theme.containerBorderWidth + 'px',
				"-webkit-border-radius": theme.containerBorderRadius + 'px',
				"border-radius": theme.containerBorderRadius + 'px'
			})
	};
	$.fn.themeClose = function(opts)
	{
		var $this = $(this);
		if(themes[opts.theme] === undefined)
		{
			var theme = themes[1];
		}
		else
		{
			var theme = themes[opts.theme];
		}
		if(theme.closeLocation == 'outside')		
		{
			var val = theme.closeSize;
			var top = bottom = left = right = 'auto';
			switch (theme.closePosition)
			{
				case 'topright':
					top = -(val / 2) + 'px';
					right = -(val / 2) + 'px';
					break;
				case 'topleft':
					top = -(val / 2) + 'px';
					left = -(val / 2) + 'px';
					break;
				case 'bottomright':
					bottom = -(val / 2) + 'px';
					right = -(val / 2) + 'px';
					break;
				case 'bottomleft':
					bottom = -(val / 2) + 'px';
					left = -(val / 2) + 'px';
					break;
			}
			$this
				.addClass('outside')
				.css({
					left: left,
					right: right,
					top: top,
					bottom: bottom,
					height: theme.closeSize + 'px',
					fontSize: theme.closeFontSize + 'px',
					width: theme.closeSize + 'px',
					backgroundColor: theme.closeBgColor,
					"-webkit-border-radius": theme.closeBorderRadius + 'px',
					"border-radius": theme.closeBorderRadius + 'px',
					lineHeight: theme.closeSize + 'px'
				});
		}
		return $this.addClass('theme-'+opts.theme)
			.html(theme.closeText)
			.css({
				color: theme.closeFontColor,
			});
	};
	
	var animations = {
		fade: function(options)
		{
			var $this = $(this).show(0).css({'opacity':0,'top':$(window).scrollTop() + 100 +'px'});
			var opts = $.extend($.fn.animation.defaults, options);
			$this.animate({
				opacity: 1
			},parseInt(opts.duration),opts.easing,function(){
				$this
					.removeAttr('style')
					.css({'display':'block','visibility':'visible','top': ($(window).scrollTop() + 100) +'px'})
					.themeModal(opts);
			});
		},
		fadeAndSlide: function(options)
		{
			var $this = $(this).show(0).css('opacity',0);
			var opts = $.extend($.fn.animation.defaults, options);
			switch(opts.direction)
			{
				case 'mouse': $this.css({'top': currentMousePos.y + 'px','left': currentMousePos.x +'px'}); break;
				case 'top': $this.css({'top':  $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'left': $this.css({'left': '-'+$this.outerWidth(true)+'px','top':$(window).scrollTop() + 100 +'px'}); break;
				case 'bottom': $this.css({'top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
				case 'right': $this.css({'left': $(window).innerWidth()+'px','top':$(window).scrollTop() + 100 +'px'}); break;
				case 'topleft': $this.css({'left': '-'+$this.outerWidth(true)+'px','top':$(window).scrollTop() + 100 +'px','top': $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'topright': $this.css({'left': $(window).innerWidth()+'px','top': $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'bottomleft': $this.css({'left': '-'+$this.outerWidth(true)+'px','top':$(window).scrollTop() + 100 +'px','top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
				case 'bottomright': $this.css({'left': $(window).innerWidth()+'px','top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
			}
			$('html').css('overflow-x','hidden');
			$this.animate({
				'left': '50%',
				'top': $(window).scrollTop() + 100 +'px'
			},{duration: opts.duration , queue:false},opts.easing);
			setTimeout(function()
			{
				$this.animate({
					'opacity': 1
				},opts.duration * .75,opts.easing,function(){
					$this
						.removeAttr('style')
						.css({'display':'block','visibility':'visible','top': ($(window).scrollTop() + 100) +'px'})
						.themeModal(opts);
					$('html').css('overflow-x','inherit');
				});
			},opts.duration * .25);
		},
		grow: function(options)
		{
			var $this = $(this).show(0);
			var opts = $.extend($.fn.animation.defaults, options);
			var currently = {
				width: parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100 + '%',
				height: parseInt($this.css('height')),
				marginLeft: '-' + parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100  / 2 + '%',
				padding: parseInt($this.css('padding-left')) / parseInt($this.css('font-size')) + 'em'
			};
			$('*',$this).fadeOut(0);
			$this.css({
				'top': (currently.height/10) * 5 + $(window).scrollTop() + 100 +'px',
				'left': (currently.width/10) * 5 + ($(window).innerWidth() / 2) +'px',
				'height': 0,
				'width': 0,
				'padding': 0,
				'margin-left': 0
			}).animate({
				'top': $(window).scrollTop() + 100 +'px',
				'left': '50%',
				'padding': currently.padding,
				'height': currently.height,
				'width': currently.width,
				'margin-left': currently.marginLeft
			},opts.duration,function(){
				$this
					.removeAttr('style')
					.css({'display':'block','visibility':'visible','top': ($(window).scrollTop() + 100) +'px'})
					.themeModal(opts);
				$('*',$this).fadeIn('fast');
			});
		},
		growAndSlide: function(options)
		{
			var $this = $(this).show(0);
			var opts = $.extend($.fn.animation.defaults, options);
			var currently = {
				width: parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100 + '%',
				height: parseInt($this.css('height')),
				marginLeft: '-' + parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100  / 2 + '%',
				padding: parseInt($this.css('padding-left')) / parseInt($this.css('font-size')) + 'em'
			};
			$('html,body').css('overflow-x','hidden');
			$('*',$this).fadeOut(0);
			$this.css({
				'opacity': 1,
				'height': 0,
				'width': 0,
				'padding': 0,
				'margin-left': 0
			});
			switch(opts.direction)
			{
				case 'mouse': $this.css({'top': currentMousePos.y + 'px','left': currentMousePos.x +'px'}); break;
				case 'top': $this.css({'top':  $(window).scrollTop() + $this.outerHeight(true) + 'px'}); break;
				case 'left': $this.css({'left': 0,'top':$(window).scrollTop() + 100 +'px'}); break;
				case 'bottom': $this.css({'top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
				case 'right': $this.css({'left': $(window).innerWidth()+'px','top':$(window).scrollTop() + 100 +'px'}); break;
				case 'topleft': $this.css({'left': 0,'top':$(window).scrollTop() + 100 +'px','top': $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'topright': $this.css({'left': $(window).innerWidth()+'px','top': $(window).scrollTop() - $this.outerHeight(true) + 'px'}); break;
				case 'bottomleft': $this.css({'left': 0,'top':$(window).scrollTop() + 100 +'px','top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
				case 'bottomright': $this.css({'left': $(window).innerWidth()+'px','top': $(window).innerHeight() + $(window).scrollTop() + 'px'}); break;
			}
			$this.animate({
				'height': currently.height,
				'padding': currently.padding,
				'width': currently.width,
				'margin-left': currently.marginLeft
			},{duration: opts.duration , queue:false},opts.easing);
			setTimeout(function()
			{
				$this.animate({
					'height': 'auto',
					'top': ($(window).scrollTop() + 100) +'px',
					'left': '50%'
				},opts.duration * .95,opts.easing,function(){
					$this
						.removeAttr('style')
						.css({'display':'block','visibility':'visible','top': ($(window).scrollTop() + 100) +'px'})
						.themeModal(opts);
					$('*',$this).fadeIn('fast');	
					$('html').css('overflow-x','inherit');
				});
			},opts.duration * .05);
		}/*,
		canvas: function(options)
		{
			var $this = $(this)
			$this.css('top',$(document).height() + $this.height()).show(0);
			html2canvas($this, {
				onrendered: function(canvas) {
					var canvas = canvas;
					//$('body').append(canvas);
					var x = 4,
						y = 3,
						random = true,
						width = parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100,
						height = parseInt($this.css('height')),
						marginLeft = '-' + parseInt($this.css('width')) / parseInt($this.parent().innerWidth()) * 100  / 2 + '%',
						$img = canvas.toDataURL(),
						n_tiles = x * y, // total number of tiles
						tiles = [],
						$tiles = {};
						
					for ( var i = 0; i < n_tiles; i++ ) {
						tiles.push('<div class="tile"/>');
					}
						 
					$tiles = $( tiles.join('') );
					// Hide original image and insert tiles in DOM
					$this.hide().after(
						$('<div class="modal-placeholder"/>').attr('style', $this.attr('style')).css({
							'opacity': 1,
							'height': height,
							'margin-left': '-' + (parseInt(width) / 2) + '%',
							'padding': 0,
							'width': parseInt(width) + '%',
							'top': $(window).scrollTop() + 100
						})
						.show(0)
						.append( $tiles )
					);
					// Adjust position
					$tiles.each(function(){
						var pos = $(this).position();
						
						console.log($this.outerHeight() / y + 'px');
						$(this).css({
							'border': 0,
							'backgroundPosition': -pos.left +'px ' + -pos.top + 'px',
							'width': $this.outerWidth() / x + 'px',
							'height': $this.outerHeight() / y + 'px',
							'background-image': 'url('+ $img +')'
						});
					});
					
				}
			});
			//$this.hide(0);
			var opts = $.extend($.fn.animation.defaults, options);
		}*/
	};
    $.fn.animation = function(style)
    {
        // Method calling logic
        if (animations[style])
        {
            return animations[style].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else
        {
            $.error('Animation style ' + animations + ' does not exist on jQuery.animation');
        }
    };
    
	$.fn.animation.defaults = {
		duration:750,
		direction: 'mouse',
		easing: 'swing'
	};
    $.fn.emodal.defaults = {
        theme: 1,
        onLoad: function (){},
        onClose: function (){},
        type: null,
        maxHeight: null,
        maxWidth: null,
		userHeight: null,
		userWidth: null,
		animation: 'fadeAndSlide',
		direction: 'bottom',
        overlayClose: false,
        overlayEscClose: false
    };
    var modals = easymodal.modals;
    var themes = easymodal.themes;
    var settings = easymodal.settings;
	
    $(document).ready(function()
    {
		$('.modal').each(function()
		{
			var $this = $(this).css({visibility:'visible'}).hide(0);
			var modalId = $this.attr('id').split("-")[1];
			$this.emodal(modals[modalId]);
			
			$(document).on('click','.'+$this.attr('id'),function(e){
				e.preventDefault();
				e.stopPropagation();
				currentMousePos.x = e.pageX;
				currentMousePos.y = e.pageY;
				$this.emodal('open');
			});
			$('.'+$this.attr('id')).css('cursor','pointer');
		});
		if(easymodal.autoOpen && !$.cookie("eModal-autoOpen-"+easymodal.autoOpen.id))
		{
			setTimeout(function(){
				$('#eModal-'+easymodal.autoOpen.id).emodal('open');
				$.cookie("eModal-autoOpen-"+easymodal.autoOpen.id, true, { expires : parseInt(easymodal.autoOpen.timer) });
			},easymodal.autoOpen.delay);
		}
		if(easymodal.autoExit && !$.cookie("eModal-autoExit-"+easymodal.autoExit.id))
		{
			$('body').one('mouseleave',function(){
				if(easymodal.force_user_login)
				{
					return false;	
				}
				$this = $('#eModal-'+easymodal.autoExit.id).emodal('open');
				$.cookie("eModal-autoExit-"+easymodal.autoExit.id, true, { expires : parseInt(easymodal.autoExit.timer) });
			});	
		}
		
		
		
	// Run our login ajax
	$('#eModal-Login form').on('submit', function(e) {
		$form = $(this);
		// Stop the form from submitting so we can use ajax.
		e.preventDefault();
		// Check what form is currently being submitted so we can return the right values for the ajax request.
		// Display our loading message while we check the credentials.
		$form.append('<p class="message notice">' + easymodal.loadingtext + '</p>');
		// Check if we are trying to login. If so, process all the needed form fields and return a faild or success message.
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: easymodal.ajaxLogin,
			data: {
				'action'     : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
				'username'   : $('#user_login',$form).val(),
				'password'   : $('#user_pass',$form).val(),
				'rememberme' : $('#rememberme',$form).is(':checked') ? true : false,
				'login'      : true,
				'easy-modal' : $('#safe_csrf_nonce_easy_modal',$form).val()
			},
			success: function(results) {
				// Check the returned data message. If we logged in successfully, then let our users know and remove the modal window.
				if(results.loggedin === true) {
					$('p.message',$form).removeClass('notice').addClass('success').text(results.message).show();
					setTimeout(function(){
						$('#eModal-Login').emodal('close',{onClose: function(){
							window.location.href = easymodal.redirecturl;
						}});
						
					},2000);
					
				} else {
					$('p.message',$form).removeClass('notice').addClass('error').text(results.message).show();
				}
			}
		});
	});
	// Run our register ajax
	$('#eModal-Register form').on('submit', function(e) {
		$form = $(this);
		// Stop the form from submitting so we can use ajax.
		e.preventDefault();
		// Check what form is currently being submitted so we can return the right values for the ajax request.
		// Display our loading message while we check the credentials.
		$form.append('<p class="message notice">' + easymodal.loadingtext + '</p>');
		// Check if we are trying to login. If so, process all the needed form fields and return a faild or success message.
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: easymodal.ajaxLogin,
			data: {
				'action'     : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
				'username'   : $('#forgot_login',$form).val(),
				'email'   	 : $('#reg_email',$form).val(),
				'register'      : true,
				'easy-modal' : $('#safe_csrf_nonce_easy_modal',$form).val()
			},
			success: function(results) {
				// Check the returned data message. If we logged in successfully, then let our users know and remove the modal window.
				if(results.loggedin === true) {
					$('p.message',$form).removeClass('notice').addClass('success').text(results.message).show();
					setTimeout(function(){
						$('#eModal-Login').emodal('close',{onClose: function(){
							window.location.href = easymodal.redirecturl;
						}});
						
					},2000);
					
				} else {
					$('p.message',$form).removeClass('notice').addClass('error').text(results.message).show();
				}
			}
		});
	});
	// Run our forgot password ajax
	$('#eModal-Forgot form').on('submit', function(e) {
		$form = $(this);
		// Stop the form from submitting so we can use ajax.
		e.preventDefault();
		// Check what form is currently being submitted so we can return the right values for the ajax request.
		// Display our loading message while we check the credentials.
		$form.append('<p class="message notice">' + easymodal.loadingtext + '</p>');
		// Check if we are trying to login. If so, process all the needed form fields and return a faild or success message.
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: easymodal.ajaxLogin,
			data: {
				'action'     : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
				'username'   : $('#forgot_login',$form).val(),
				'forgotten'      : true,
				'easy-modal' : $('#safe_csrf_nonce_easy_modal',$form).val()
			},
			success: function(results) {
				// Check the returned data message. If we logged in successfully, then let our users know and remove the modal window.
				if(results.loggedin === true) {
					$('p.message',$form).removeClass('notice').addClass('success').text(results.message).show();
					setTimeout(function(){
						$('#eModal-Login').emodal('close',{onClose: function(){
							window.location.href = easymodal.redirecturl;
						}});
						
					},2000);
					
				} else {
					$('p.message',$form).removeClass('notice').addClass('error').text(results.message).show();
				}
			}
		});
	});
	/*
		} else if ( form_id === 'register' ) {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: wpml_script.ajax,
				data: {
					'action'   : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'username' : $('#form #reg_user').val(),
					'email'    : $('#form #reg_email').val(),
					'register' : $('#form input[name="register"]').val(),
					'security' : $('#form #security').val()
				},
				success: function(results) {
					if(results.registerd === true) {
						$('.wpml-content > p.message').removeClass('notice').addClass('success').text(results.message).show();
						$('#register #form input:not(#user-submit)').val('');
					} else {
						$('.wpml-content > p.message').removeClass('notice').addClass('error').text(results.message).show();
					}
				}
			});
		} else if ( form_id === 'forgotten' ) {
			$.ajax({
				type: 'GET',
				dataType: 'json',
				url: wpml_script.ajax,
				data: {
					'action'    : 'ajaxlogin', // Calls our wp_ajax_nopriv_ajaxlogin
					'username'  : $('#form #forgot_login').val(),
					'forgotten' : $('#form input[name="register"]').val(),
					'security'  : $('#form #security').val()
				},
				success: function(results) {
					if(results.reset === true) {
						$('.wpml-content > p.message').removeClass('notice').addClass('success').text(results.message).show();
						$('#forgotten #form input:not(#user-submit)').val('');
					} else {
						$('.wpml-content > p.message').removeClass('notice').addClass('error').text(results.message).show();
					}
				}
			});
		} else {
			// if all else fails and we've hit here... something strange happen and notify the user.
			$('.wpml-content > p.message').text('Something  Please refresh your window and try again.');
		}
	});
		
		
		
		
		
		/*
		$.expr[':'].external = function (obj)
		{
			return !obj.href.match(/^mailto\:/) && (obj.hostname != location.hostname);
		};
		$('a:external').addClass('external eModal-Link').emodal(easymodal.modals['Link']);
        $('a[href$=".gif"], a[href$=".jpg"], a[href$=".png"], a[href$=".bmp"]').children('img').each(function ()
        {
            var anch = $(this).parents('a').addClass('eModal-Image');
            var url = $(anch).attr('href');
            $(anch).emodal(
            {
                url: url,
                theme: '1',
                type: 'Image'
            });
        });
		if(settings.autoOpen == 'true')
		{
			setTimeout(function(){
				$('#emModal-'+settings.autoOpenId).emodal('open');
			}, settings.autoOpenDelay);
		}
		*/
    })
})(jQuery);
/*!
 * jQuery Cookie Plugin v1.3.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */

(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals.
		factory(jQuery);
	}
}(function ($) {
	var pluses = /\+/g;
	function raw(s) {
		return s;
	}
	function decoded(s) {
		return decodeURIComponent(s.replace(pluses, ' '));
	}
	function converted(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}
		try {
			return config.json ? JSON.parse(s) : s;
		} catch(er) {}
	}
	var config = $.cookie = function (key, value, options) {
		// write
		if (value !== undefined) {
			options = $.extend({}, config.defaults, options);
			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}
			value = config.json ? JSON.stringify(value) : String(value);
			return (document.cookie = [
				config.raw ? key : encodeURIComponent(key),
				'=',
				config.raw ? value : encodeURIComponent(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}
		// read
		var decode = config.raw ? raw : decoded;
		var cookies = document.cookie.split('; ');
		var result = key ? undefined : {};
		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = decode(parts.join('='));
			if (key && key === name) {
				result = converted(cookie);
				break;
			}
			if (!key) {
				result[name] = converted(cookie);
			}
		}
		return result;
	};
	config.defaults = {};
	$.removeCookie = function (key, options) {
		if ($.cookie(key) !== undefined) {
			// Must not alter options, thus extending a fresh object...
			$.cookie(key, '', $.extend({}, options, { expires: -1 }));
			return true;
		}
		return false;
	};
}));
(function(h,m){function n(a,b,c){var d=r[b.type]||{};if(null==a)return c||!b.def?null:b.def;a=d.floor?~~a:parseFloat(a);return isNaN(a)?b.def:d.mod?(a+d.mod)%d.mod:0>a?0:d.max<a?d.max:a}function s(a){var b=f(),c=b._rgba=[],a=a.toLowerCase();j(v,function(d,g){var e,i=g.re.exec(a);e=i&&g.parse(i);i=g.space||"rgba";if(e)return e=b[i](e),b[k[i].cache]=e[k[i].cache],c=b._rgba=e._rgba,!1});return c.length?("0,0,0,0"===c.join()&&h.extend(c,o.transparent),b):o[a]}function p(a,b,c){c=(c+1)%1;return 1>6*c?
a+6*(b-a)*c:1>2*c?b:2>3*c?a+6*(b-a)*(2/3-c):a}var w=/^([\-+])=\s*(\d+\.?\d*)/,v=[{re:/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,parse:function(a){return[a[1],a[2],a[3],a[4]]}},{re:/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,parse:function(a){return[2.55*a[1],2.55*a[2],2.55*a[3],a[4]]}},{re:/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,parse:function(a){return[parseInt(a[1],16),parseInt(a[2],16),
parseInt(a[3],16)]}},{re:/#([a-f0-9])([a-f0-9])([a-f0-9])/,parse:function(a){return[parseInt(a[1]+a[1],16),parseInt(a[2]+a[2],16),parseInt(a[3]+a[3],16)]}},{re:/hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,space:"hsla",parse:function(a){return[a[1],a[2]/100,a[3]/100,a[4]]}}],f=h.Color=function(a,b,c,d){return new h.Color.fn.parse(a,b,c,d)},k={rgba:{props:{red:{idx:0,type:"byte"},green:{idx:1,type:"byte"},blue:{idx:2,type:"byte"}}},hsla:{props:{hue:{idx:0,
type:"degrees"},saturation:{idx:1,type:"percent"},lightness:{idx:2,type:"percent"}}}},r={"byte":{floor:!0,max:255},percent:{max:1},degrees:{mod:360,floor:!0}},t=f.support={},u=h("<p>")[0],o,j=h.each;u.style.cssText="background-color:rgba(1,1,1,.5)";t.rgba=-1<u.style.backgroundColor.indexOf("rgba");j(k,function(a,b){b.cache="_"+a;b.props.alpha={idx:3,type:"percent",def:1}});f.fn=h.extend(f.prototype,{parse:function(a,b,c,d){if(a===m)return this._rgba=[null,null,null,null],this;if(a.jquery||a.nodeType)a=
h(a).css(b),b=m;var g=this,e=h.type(a),i=this._rgba=[];b!==m&&(a=[a,b,c,d],e="array");if("string"===e)return this.parse(s(a)||o._default);if("array"===e)return j(k.rgba.props,function(d,c){i[c.idx]=n(a[c.idx],c)}),this;if("object"===e)return a instanceof f?j(k,function(c,d){a[d.cache]&&(g[d.cache]=a[d.cache].slice())}):j(k,function(d,c){var b=c.cache;j(c.props,function(d,e){if(!g[b]&&c.to){if(d==="alpha"||a[d]==null)return;g[b]=c.to(g._rgba)}g[b][e.idx]=n(a[d],e,true)});if(g[b]&&h.inArray(null,g[b].slice(0,
3))<0){g[b][3]=1;if(c.from)g._rgba=c.from(g[b])}}),this},is:function(a){var b=f(a),c=!0,d=this;j(k,function(a,e){var i,h=b[e.cache];h&&(i=d[e.cache]||e.to&&e.to(d._rgba)||[],j(e.props,function(a,d){if(null!=h[d.idx])return c=h[d.idx]===i[d.idx]}));return c});return c},_space:function(){var a=[],b=this;j(k,function(c,d){b[d.cache]&&a.push(c)});return a.pop()},transition:function(a,b){var c=f(a),d=c._space(),g=k[d],e=0===this.alpha()?f("transparent"):this,i=e[g.cache]||g.to(e._rgba),h=i.slice(),c=c[g.cache];
j(g.props,function(a,d){var g=d.idx,e=i[g],f=c[g],j=r[d.type]||{};null!==f&&(null===e?h[g]=f:(j.mod&&(f-e>j.mod/2?e+=j.mod:e-f>j.mod/2&&(e-=j.mod)),h[g]=n((f-e)*b+e,d)))});return this[d](h)},blend:function(a){if(1===this._rgba[3])return this;var b=this._rgba.slice(),c=b.pop(),d=f(a)._rgba;return f(h.map(b,function(a,b){return(1-c)*d[b]+c*a}))},toRgbaString:function(){var a="rgba(",b=h.map(this._rgba,function(a,d){return null==a?2<d?1:0:a});1===b[3]&&(b.pop(),a="rgb(");return a+b.join()+")"},toHslaString:function(){var a=
"hsla(",b=h.map(this.hsla(),function(a,d){null==a&&(a=2<d?1:0);d&&3>d&&(a=Math.round(100*a)+"%");return a});1===b[3]&&(b.pop(),a="hsl(");return a+b.join()+")"},toHexString:function(a){var b=this._rgba.slice(),c=b.pop();a&&b.push(~~(255*c));return"#"+h.map(b,function(a){a=(a||0).toString(16);return 1===a.length?"0"+a:a}).join("")},toString:function(){return 0===this._rgba[3]?"transparent":this.toRgbaString()}});f.fn.parse.prototype=f.fn;k.hsla.to=function(a){if(null==a[0]||null==a[1]||null==a[2])return[null,
null,null,a[3]];var b=a[0]/255,c=a[1]/255,d=a[2]/255,a=a[3],g=Math.max(b,c,d),e=Math.min(b,c,d),i=g-e,h=g+e,f=0.5*h;return[Math.round(e===g?0:b===g?60*(c-d)/i+360:c===g?60*(d-b)/i+120:60*(b-c)/i+240)%360,0===f||1===f?f:0.5>=f?i/h:i/(2-h),f,null==a?1:a]};k.hsla.from=function(a){if(null==a[0]||null==a[1]||null==a[2])return[null,null,null,a[3]];var b=a[0]/360,c=a[1],d=a[2],a=a[3],c=0.5>=d?d*(1+c):d+c-d*c,d=2*d-c;return[Math.round(255*p(d,c,b+1/3)),Math.round(255*p(d,c,b)),Math.round(255*p(d,c,b-1/3)),
a]};j(k,function(a,b){var c=b.props,d=b.cache,g=b.to,e=b.from;f.fn[a]=function(a){g&&!this[d]&&(this[d]=g(this._rgba));if(a===m)return this[d].slice();var b,q=h.type(a),k="array"===q||"object"===q?a:arguments,l=this[d].slice();j(c,function(a,d){var b=k["object"===q?a:d.idx];null==b&&(b=l[d.idx]);l[d.idx]=n(b,d)});return e?(b=f(e(l)),b[d]=l,b):f(l)};j(c,function(d,b){f.fn[d]||(f.fn[d]=function(c){var e=h.type(c),g="alpha"===d?this._hsla?"hsla":"rgba":a,f=this[g](),j=f[b.idx];if("undefined"===e)return j;
"function"===e&&(c=c.call(this,j),e=h.type(c));if(null==c&&b.empty)return this;"string"===e&&(e=w.exec(c))&&(c=j+parseFloat(e[2])*("+"===e[1]?1:-1));f[b.idx]=c;return this[g](f)})})});f.hook=function(a){a=a.split(" ");j(a,function(a,c){h.cssHooks[c]={set:function(a,b){var e,i="";if("string"!==h.type(b)||(e=s(b))){b=f(e||b);if(!t.rgba&&1!==b._rgba[3]){for(e="backgroundColor"===c?a.parentNode:a;(""===i||"transparent"===i)&&e&&e.style;)try{i=h.css(e,"backgroundColor"),e=e.parentNode}catch(j){}b=b.blend(i&&
"transparent"!==i?i:"_default")}b=b.toRgbaString()}try{a.style[c]=b}catch(k){}}};h.fx.step[c]=function(a){a.colorInit||(a.start=f(a.elem,c),a.end=f(a.end),a.colorInit=!0);h.cssHooks[c].set(a.elem,a.start.transition(a.end,a.pos))}})};f.hook("backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor");h.cssHooks.borderColor={expand:function(a){var b={};j(["Top","Right","Bottom","Left"],function(c,d){b["border"+
d+"Color"]=a});return b}};o=h.Color.names={aqua:"#00ffff",black:"#000000",blue:"#0000ff",fuchsia:"#ff00ff",gray:"#808080",green:"#008000",lime:"#00ff00",maroon:"#800000",navy:"#000080",olive:"#808000",purple:"#800080",red:"#ff0000",silver:"#c0c0c0",teal:"#008080",white:"#ffffff",yellow:"#ffff00",transparent:[null,null,null,0],_default:"#ffffff"}})(jQuery);