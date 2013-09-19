jQuery(document).ready(function(){	
	var active_tab = window.location.hash.replace('#top#','');
	if ( active_tab == '' )
		active_tab = jQuery('.em-tab').attr('id');
	jQuery('#'+active_tab).addClass('active');
	jQuery('#'+active_tab+'-tab').addClass('nav-tab-active');
	
	jQuery('#em-tabs a').click(function() {
		jQuery('#em-tabs a').removeClass('nav-tab-active');
		jQuery('.em-tab').removeClass('active');
	
		var id = jQuery(this).attr('id').replace('-tab','');
		jQuery('#'+id).addClass('active');
		jQuery(this).addClass('nav-tab-active');
	});

	/* Value Sliders */
	jQuery("#overlayOpacitySlider")
		.slider({value: 0,range: "min",min: 0,max: 100,step: 1,slide: function(t, n){
			jQuery("#overlayOpacity").val(n.value)
			jQuery("#overlayOpacityValue").text(n.value)
			jQuery(".example-modal-overlay").css({
				opacity: n.value / 100
			})
		}})
	jQuery("#containerPaddingSlider")
		.slider({value: 0,range: "min",min: 0,max: 40,step: 1,slide: function(t, n){
			jQuery("#containerPadding").val(n.value)
			jQuery("#containerPaddingValue").text(n.value)
			jQuery(".example-modal").css({
				padding: n.value + "px"
			})
		}})
	jQuery("#containerBorderWidthSlider")
		.slider({value: 0,range: "min",min: 0,max: 10,step: 1,slide: function(t, n){
			jQuery("#containerBorderWidth").val(n.value), jQuery("#containerBorderWidthValue").text(n.value), jQuery(".example-modal").css({
				borderWidth: n.value + "px"
			})
		}})		
	jQuery("#containerBorderRadiusSlider")
		.slider({value: 0,range: "min",min: 0,max: 50,step: 1,slide: function(t, n){
			jQuery("#containerBorderRadius").val(n.value), jQuery("#containerBorderRadiusValue").text(n.value);
			var r = n.value + "px";
			jQuery(".example-modal").css({
				"-moz-border-radius": r,
				"-webkit-border-radius": r,
				"border-radius": r
			})
		}})
	jQuery("#contentTitleFontSizeSlider")
		.slider({value: 0,range: "min",min: 12,max: 48,step: 1,slide: function(t, n){
			jQuery("#contentTitleFontSize").val(n.value), jQuery("#contentTitleFontSizeValue").text(n.value);
			var r = n.value + "px";
			jQuery(".example-modal .title").css({
				fontSize: r
			})
		}})
	jQuery("#closeBorderRadiusSlider")
		.slider({value: 0,range: "min",min: 0,max: Math.round(jQuery("#closeSize").val() / 2),step: 1,slide: function(t, n){
			jQuery("#closeBorderRadius").val(n.value), jQuery("#closeBorderRadiusValue").text(n.value);
			var r = n.value + "px";
			jQuery(".example-modal .close-modal").css({
				"-moz-border-radius": r,
				"-webkit-border-radius": r,
				"border-radius": r
			})
		}})
	jQuery("#closeFontSizeSlider")
		.slider({value: 0,range: "min",min: 0,max: Math.round(jQuery("#closeSize").val() - 2),step: 1,slide: function(t, n){
			jQuery("#closeFontSize").val(n.value), jQuery("#closeFontSizeValue").text(n.value);
			var r = n.value + "px";
			jQuery(".example-modal .close-modal").css({
				fontSize: r
			})
		}})
	jQuery("#closeSizeSlider")
		.slider({value: 0,range: "min",min: 0,max: 40,step: 1,slide: function(t, n){
			jQuery("#closeSize").val(n.value)
			jQuery("#closeSizeValue").text(n.value);
			var r = n.value,i = s = o = u = "auto";
			switch (jQuery("[name=closePosition]").val()) {
				case "topright":
					i = -(r / 2) + "px", u = -(r / 2) + "px";
					break;
				case "topleft":
					i = -(r / 2) + "px", o = -(r / 2) + "px";
					break;
				case "bottomright":
					s = -(r / 2) + "px", u = -(r / 2) + "px";
					break;
				case "bottomleft":
					s = -(r / 2) + "px", o = -(r / 2) + "px"
			}
			
			var a = jQuery("#closeFontSizeSlider").slider("value");
			
			a > r - 2 && (a = r - 2)
			jQuery("#closeFontSizeValue").text(a)
			jQuery(".example-modal .close-modal")
				.css({fontSize: a + "px"})
				
			jQuery("#closeFontSizeSlider")
				.slider("option", "max", r - 2)
				.slider("option", "value", a);
				
			var f = Math.round(r / 2),
				l = jQuery("#closeBorderRadiusSlider").slider("value");
			l > f && (l = f)
			jQuery("#closeBorderRadiusValue").text(l)
		
			jQuery(".example-modal .close-modal")
				.css({"-webkit-border-radius": l + "px","border-radius": l + "px"})
				
			jQuery("#closeBorderRadiusSlider")
				.slider("option", "max", f)
				.slider("option", "value", l)
			
			jQuery(".example-modal .close-modal")
				.css({width: r + "px",height: r + "px",lineHeight: r + "px",left: o,right: u,top: i,bottom: s})
		}})
	jQuery("#autoOpenDelaySlider")
		.slider({value: 0,range: "min",min: 0,max: 1e4,step: 500,slide: function(t, n){
			jQuery("#autoOpenDelay").val(n.value)
			jQuery("#autoOpenDelayValue").text(n.value)
		}})
	/* Color Pickers */
	
	
	jQuery(".color-swatch")
		.each(function(){
			var swatch = jQuery(this);
			var input = swatch.prev('.colorSelect');
			swatch.ColorPicker({
				color: input.val(),
				onShow: function(colpkr){
					return jQuery(colpkr).fadeIn(500), !1
				},
				onHide: function(colpkr){
					return jQuery(colpkr).fadeOut(500), !1
				},
				onChange: function(n, r, i){
					swatch.css("backgroundColor", "#" + r)
					input.val("#" + r);
					switch (input.attr("name")) {
						case "overlayColor": jQuery(".example-modal-overlay").css({backgroundColor: "#" + r});
							break;
						case "containerBgColor": jQuery(".example-modal").css({backgroundColor: "#" + r});
							break;
						case "containerBorderColor": jQuery(".example-modal").css({borderColor: "#" + r});
							break;
						case "contentTitleFontColor": jQuery(".example-modal .title").css({color: "#" + r});
							break;
						case "contentFontColor": jQuery(".example-modal").css({color: "#" + r});
							break;
						case "closeBgColor": jQuery(".example-modal .close-modal").css({backgroundColor: "#" + r});
							break;
						case "closeBorderColor": jQuery(".example-modal .close-modal").css({borderColor: "#" + r});
							break;
						case "closeFontColor": jQuery(".example-modal .close-modal").css({color: "#" + r})
					}
				}
			})
		})
	jQuery(".colorSelect")
		.on('focusout', function(){
			var $this = jQuery(this);
			$this.next('.color-swatch').css('backgroundColor', $this.val()).ColorPickerSetColor($this.val())
			var color = $this.val();
			switch ($this.attr("name")){
				case "overlayColor": jQuery(".example-modal-overlay").css({backgroundColor: color});
					break;
				case "containerBgColor": jQuery(".example-modal").css({backgroundColor: color});
					break;
				case "containerBorderColor": jQuery(".example-modal").css({borderColor: color});
					break;
				case "contentTitleFontColor": jQuery(".example-modal .title").css({color: color});
					break;
				case "contentFontColor": jQuery(".example-modal").css({color: color});
					break;
				case "closeBgColor": jQuery(".example-modal .close-modal").css({backgroundColor: color});
					break;
				case "closeBorderColor": jQuery(".example-modal .close-modal").css({borderColor: color});
					break;
				case "closeFontColor": jQuery(".example-modal .close-modal").css({color: color})
			}
		})
	
	jQuery("#autoOpenDelay")
		.on("change", function(){
			jQuery("#autoOpenDelaySlider").slider("option", "value", jQuery(this).val())
		})
	jQuery(".cb-enable")
		.click(function(){
			var t = jQuery(this).parents(".switch");
			jQuery(".cb-disable", t).removeClass("selected")
			jQuery(this).addClass("selected")
			jQuery(".checkbox", t).attr("checked", !0)
		})
	
	jQuery(".cb-disable")
		.click(function(){
			var t = jQuery(this).parents(".switch");
			jQuery(".cb-enable", t).removeClass("selected")
			jQuery(this).addClass("selected")
			jQuery(".checkbox", t).attr("checked", !1)
		})
	jQuery(".switch")
		.each(function(){
			var t = jQuery(this),
				n = jQuery(".checkbox", t);
			n.is(":checked") && (jQuery(".cb-disable", t).removeClass("selected"), jQuery(".cb-enable", t).addClass("selected"))
		})
	jQuery("#containerBorderStyle")
		.on('change',function(){
			var $this = jQuery(this)
			var val = $this.val();
			jQuery(".example-modal").css({borderStyle: val})
			if(val == 'none')
			{
				jQuery('.border-only').hide()
			}
			else
			{
				jQuery('.border-only').show()
			}
		})
	
	jQuery("#contentTitleFontFamily")
		.change(function(){
			var t = jQuery(this).val();
			jQuery(".example-modal .title").css({
				fontFamily: t
			})
		})
	jQuery("#closePosition")
		.change(function(){
			var t = parseInt(jQuery("#closeSize").val()),
				n = bottom = left = right = "auto";
			switch (jQuery(this).val()) {
				case "topright":
					n = -(t / 2) + "px", right = -(t / 2) + "px";
					break;
				case "topleft":
					n = -(t / 2) + "px", left = -(t / 2) + "px";
					break;
				case "bottomright":
					bottom = -(t / 2) + "px", right = -(t / 2) + "px";
					break;
				case "bottomleft":
					bottom = -(t / 2) + "px", left = -(t / 2) + "px"
			}
			jQuery(".example-modal .close-modal")
				.css({left: left,right: right,top: n,bottom: bottom})
		})
	jQuery('#closeLocation')
		.on('change',function(){
			var $this = jQuery(this)
			var val = $this.val();
			if(val == 'inside')
			{
				jQuery('.outside-only').hide()
				jQuery('.close-modal').removeAttr('styles');
			}
			else
			{
				jQuery('.outside-only').show()
			}
		})
	jQuery("#size")
		.on('change',function(){
			var $this = jQuery(this)
			var val = $this.val();
			if(val != 'custom')
			{
				jQuery('.custom-size-only').hide()
			}
			else
			{
				jQuery('.custom-size-only').show()
			}
		})
	jQuery("#animation")
		.on('change',function(){
			var $this = jQuery(this)
			var val = $this.val();
			if(val == 'fade' || val == 'grow')
			{
				jQuery('.animation-only').hide()
			}
			else
			{
				jQuery('.animation-only').show()
			}
		})
	jQuery("#overlayOpacitySlider").slider("option", "value", jQuery("#overlayOpacity").val())
	jQuery("#containerPaddingSlider").slider("option", "value", jQuery("#containerPadding").val())
	jQuery("#containerBorderWidthSlider").slider("option", "value", jQuery("#containerBorderWidth").val())
	jQuery("#containerBorderRadiusSlider").slider("option", "value", jQuery("#containerBorderRadius").val())	
	jQuery("#contentTitleFontSizeSlider").slider("option", "value", jQuery("#contentTitleFontSize").val())
	jQuery("#closeBorderRadiusSlider").slider("option", "value", jQuery("#closeBorderRadius").val())
	jQuery("#closeFontSizeSlider").slider("option", "value", jQuery("#closeFontSize").val())	
	jQuery("#closeSizeSlider").slider("option", "value", jQuery("#closeSize").val())
	jQuery("#autoOpenDelaySlider").slider("option", "value", jQuery("#autoOpenDelay").val())
	
	jQuery(document)
		.on("click", '.close-modal',function(){
			jQuery(this).parent('.modal').fadeOut();
			jQuery('.modal-overlay').fadeOut(function(){jQuery(this).remove();})
		})
		.on("click", '.modal-overlay',function(){
			jQuery('.modal:visible').fadeOut(function(){jQuery(this).css('visibility','hidden');});
			jQuery(this).fadeOut(function(){jQuery(this).remove();})
		})
		.on("keyup",function(e){
			if (e.keyCode == 27) {
				jQuery('.modal:visible').fadeOut(function(){jQuery(this).css('visibility','hidden');});
				jQuery('.modal-overlay').fadeOut(function(){jQuery(this).remove();})
			}
		})
		
	jQuery(document)
		.on("click", '.write-it', function(){
			jQuery('<div class="modal-overlay" />').appendTo('body').fadeIn();
			jQuery('#eModal-Admin-1').css('visibility','visible').fadeIn();
			return false;
		})
});