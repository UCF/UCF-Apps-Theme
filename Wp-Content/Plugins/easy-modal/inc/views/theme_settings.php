<?php
$borderTypes = array(
	'none' => 'None',
	'solid' => 'Solid',
	'dotted' => 'Dotted',
	'dashed' => 'Dashed',
	'double' => 'Double',
	'groove' => 'Groove',
	'inset' => 'Inset',
	'outset' => 'Outset',
	'ridge' => 'Ridge'
);
$bordershowhide = $settings['containerBorderStyle'] == 'none' ? 'style="display:none"' : '';
$fontFamilys = array(
	'Sans-Serif'=>'Sans-Serif',
	'Tahoma' => 'Tahoma',
	'Georgia' => 'Georgia',
	'Comic Sans MS' => 'Comic Sans MS',
	'Arial' => 'Arial',
	'Lucida Grande' => 'Lucida Grande',
	'Times New Roman' => 'Times New Roman'
);
$closeLocations = array(
	'inside' => 'Inside',
	'outside' => 'Outside',
);
$closeshowhide = $settings['closeLocation'] == 'inside' ? 'style="display:none"' : '';
$closePositions = array(
	'topleft' => 'Top Left',
	'topright' => 'Top Right',
	'bottomleft' => 'Bottom Left',
	'bottomright' => 'Bottom Right'
);
?><div style="border-style:" class="wrap">
	<?php if(!empty($this->messages)){?>
		<?php foreach($this->messages as $message){?>
		<div class="<?php _e($message['type'],'easy-modal')?>"><strong><?php _e($message['message'],'easy-modal')?>.</strong></div>
		<?php }?>
	<?php }?>
	<?php screen_icon()?>
	<h2><?php echo ucfirst(esc_html($settings['name'])).' '; _e('Theme','easy-modal')?></h2>
	<div class="emthemes metabox-holder">
		<div class="meta-box-sortables ui-sortable">
			<h2 id="em-tabs" class="nav-tab-wrapper">
				<a href="#top#general" id="general-tab" class="nav-tab"><?php _e('General','easy-modal')?></a>
				<a href="#top#overlay" id="overlay-tab" class="nav-tab"><?php _e('Overlay','easy-modal')?></a>
				<a href="#top#close" id="close-tab" class="nav-tab"><?php _e('Close','easy-modal')?></a>
				<a href="#top#container" id="container-tab" class="nav-tab"><?php _e('Container','easy-modal')?></a>
				<a href="#top#content" id="content-tab" class="nav-tab"><?php _e('Content','easy-modal')?></a>
			</h2>
			<div class="tabwrapper">
				<form method="post" action="admin.php?page=easy-modal-themes&theme_id=1">
					<input type="hidden" name="em_settings" value="theme"/>
					<div id="general" class="em-tab">
						<table class="form-table">
							<tbody>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="name"><?php _e('Theme Name', 'easy-modal');?> <span class="description">(required)</span></label>
									</th>
									<td>
										<input type="text" name="name" id="name" value="<?php esc_attr_e($settings['name'])?>"/>
										<p class="description"><?php _e('Enter a name for your theme.','easy-modal')?></p>
									</td>
								</tr>
							</tbody>
						</table>							
					</div>
					<div id="overlay" class="em-tab">
						<table class="form-table">
							<tbody>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="overlayColor"><?php _e('Color', 'easy-modal');?></label>
									</th>
									<td>
										<input type="text" name="overlayColor" id="overlayColor" value="<?php esc_attr_e($settings['overlayColor'])?>" class="colorSelect" />
										<div class="color-swatch" style="background-color:<?php esc_attr_e($settings['overlayColor'])?>" ></div>
										<p class="description"><?php _e('Choose the overlay color.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="overlayOpacity"><?php _e('Opacity', 'easy-modal');?></label>
									</th>
									<td>
										<div id="overlayOpacitySlider" class="value-slider"></div>
										<div class="slider-value"><span id="overlayOpacityValue"><?php echo intval($settings['overlayOpacity'])?></span>%</div>
										<input type="hidden" id="overlayOpacity" name="overlayOpacity" size="20" value="<?php esc_attr_e(intval($settings['overlayOpacity']))?>"/>
										<p class="description"><?php _e('The opacity value for the overlay.','easy-modal')?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="close" class="em-tab">
						<table class="form-table">
							<tbody>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="closeLocation"><?php _e('Close Location', 'easy-modal');?></label>
									</th>
									<td>
										<select name="closeLocation" id="closeLocation">
										<?php foreach($closeLocations as $value => $name){?>
											<option value="<?php echo $value?>" <?php echo $value == $settings['closeLocation'] ? 'selected="selected"' : ''?>><?php echo $name?></option>
										<?php }?>
										</select>
										<p class="description"><?php _e('Choose whether the close button is inside or outside of the modal.','easy-modal')?></p>
									</td>
								</tr>
								
								<tr class="form-field form-required outside-only"<?php echo $closeshowhide?>>
									<th scope="row">
										<label for="closeBgColor"><?php _e('Background Color', 'easy-modal');?></label>
									</th>
									<td>
										<input type="text" name="closeBgColor" id="closeBgColor" value="<?php esc_attr_e($settings['closeBgColor'])?>" class="colorSelect" />
										<div class="color-swatch" style="background-color:<?php esc_attr_e($settings['closeBgColor'])?>" ></div>
										<p class="description"><?php _e('Choose the background color for your close button.','easy-modal')?></p>
									</td>
								</tr>
								
								<tr class="form-field form-required">
									<th scope="row">
										<label for="closeFontColor"><?php _e('Font Color', 'easy-modal');?></label>
									</th>
									<td>
										<input type="text" name="closeFontColor" id="closeFontColor" value="<?php esc_attr_e($settings['closeFontColor'])?>" class="colorSelect" />
										<div class="color-swatch" style="background-color:<?php esc_attr_e($settings['closeFontColor'])?>" ></div>
										<p class="description"><?php _e('Choose the font color for your close button.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required outside-only"<?php echo $closeshowhide?>>
									<th scope="row">
										<label for="closeFontSize"><?php _e('Font Size', 'easy-modal');?></label>
									</th>
									<td>
										<div id="closeFontSizeSlider" class="value-slider"></div>
										<div class="slider-value"><span id="closeFontSizeValue"><?php esc_attr_e(intval($settings['closeFontSize']))?></span>px</div>
										<input type="hidden" name="closeFontSize" id="closeFontSize" value="<?php esc_attr_e(intval($settings['closeFontSize']))?>" />
										<p class="description"><?php _e('Choose the font size for your close button.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required outside-only"<?php echo $closeshowhide?>>
									<th scope="row">
										<label for="closeBorderRadius"><?php _e('Border Radius', 'easy-modal');?></label>
									</th>
									<td>
										<div id="closeBorderRadiusSlider" class="value-slider"></div>
										<div class="slider-value"><span id="closeBorderRadiusValue"><?php esc_attr_e(intval($settings['closeBorderRadius']))?></span>px</div>
										<input type="hidden" id="closeBorderRadius" name="closeBorderRadius" value="<?php esc_attr_e(intval($settings['closeBorderRadius']))?>"/>
										<p class="description"><?php _e('Choose a corner radius for your close button.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required outside-only"<?php echo $closeshowhide?>>
									<th scope="row">
										<label for="closeSize"><?php _e('Size', 'easy-modal');?></label>
									</th>
									<td>
										<div id="closeSizeSlider" class="value-slider"></div>
										<div class="slider-value"><span id="closeSizeValue"><?php esc_attr_e(intval($settings['closeSize']))?></span>px</div>
										<input type="hidden" id="closeSize" name="closeSize" value="<?php esc_attr_e(intval($settings['closeSize']))?>"/>
										<p class="description"><?php _e('Choose a size for your close button.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required outside-only"<?php echo $closeshowhide?>>
									<th scope="row">
										<label for="closePosition"><?php _e('Position', 'easy-modal');?></label>
									</th>
									<td>
										<select name="closePosition" id="closePosition">
											<?php foreach($closePositions as $value => $name){?>
												<option value="<?php echo $value?>" <?php echo $value == $settings['closePosition'] ? 'selected="selected"' : ''?>><?php echo $name?></option>
											<?php }?>
										</select>
										<p class="description"><?php _e('Choose which corner the close button will be positioned.','easy-modal')?></p>
									</td>
								</tr>
							</tbody>
						</table>							
					</div>
					<div id="container" class="em-tab">
						<table class="form-table">
							<tbody>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="containerBgColor"><?php _e('Background Color', 'easy-modal');?></label>
									</th>
									<td>
										<input type="text" name="containerBgColor" id="containerBgColor" value="<?php esc_attr_e($settings['containerBgColor'])?>" class="colorSelect" />
										<div class="color-swatch" style="background-color:<?php esc_attr_e($settings['containerBgColor'])?>" ></div>
										<p class="description"><?php _e('Choose a color for the background of the modal.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="containerPadding"><?php _e('Padding', 'easy-modal');?></label>
									</th>
									<td>
										<div id="containerPaddingSlider" class="value-slider"></div>
										<div class="slider-value"><span id="containerPaddingValue"><?php esc_attr_e(intval($settings['containerPadding']))?></span>px</div>
										<input type="hidden" id="containerPadding" name="containerPadding" width="20" value="<?php esc_attr_e(intval($settings['containerPadding']))?>"/>
										<p class="description"><?php _e('Choose the amount of padding for the modal.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="containerBorderStyle"><?php _e('Border Style', 'easy-modal');?></label>
									</th>
									<td>
										<select name="containerBorderStyle" id="containerBorderStyle">
											<?php foreach($borderTypes as $value => $name){?>
											<option value="<?php echo $value?>" <?php echo $value == $settings['containerBorderStyle'] ? 'selected="selected"' : ''?>><?php echo $name?></option>
											<?php }?>
										</select>
										<p class="description"><?php _e('Choose a style for the border of the modal.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required border-only"<?php echo $bordershowhide?>>
									<th scope="row">
										<label for="containerBorderColor"><?php _e('Border Color', 'easy-modal');?></label>
									</th>
									<td>
										<input type="text" name="containerBorderColor" id="containerBorderColor" value="<?php esc_attr_e($settings['containerBorderColor'])?>" class="colorSelect" />
										<div class="color-swatch" style="background-color:<?php esc_attr_e($settings['containerBorderColor'])?>" ></div>
										<p class="description"><?php _e('Choose a color for the border of the modal.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required border-only"<?php echo $bordershowhide?>>
									<th scope="row">
										<label for="containerBorderWidth"><?php _e('Border Width', 'easy-modal');?></label>
									</th>
									<td>
										<div id="containerBorderWidthSlider" class="value-slider"></div>
										<div class="slider-value"><span id="containerBorderWidthValue"><?php esc_attr_e(intval( $settings['containerBorderWidth']))?></span>px</div>
										<input type="hidden" id="containerBorderWidth" name="containerBorderWidth" value="<?php esc_attr_e(intval( $settings['containerBorderWidth']))?>"/>
										<p class="description"><?php _e('Choose a width for the border of the modal.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="containerBorderRadius"><?php _e('Border Radius', 'easy-modal');?></label>
									</th>
									<td>
										<div id="containerBorderRadiusSlider" class="value-slider"></div>
										<div class="slider-value"><span id="containerBorderRadiusValue"><?php echo $settings['containerBorderRadius']?></span>px</div>
										<input type="hidden" id="containerBorderRadius" name="containerBorderRadius" value="<?php echo $settings['containerBorderRadius']?>"/>
										<p class="description"><?php _e('Choose a width for the border of the modal.','easy-modal')?></p>
									</td>
								</tr>
							</tbody>
						</table>	
					</div>
					<div id="content" class="em-tab">
						<table class="form-table">
							<tbody>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="contentTitleFontColor"><?php _e('Title Color', 'easy-modal');?></label>
									</th>
									<td>
										<input type="text" name="contentTitleFontColor" id="contentTitleFontColor" value="<?php esc_attr_e($settings['contentTitleFontColor'])?>" class="colorSelect" />
										<div class="color-swatch" style="background-color:<?php esc_attr_e($settings['contentTitleFontColor'])?>" ></div>
										<p class="description"><?php _e('Choose a color for the title.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="contentTitleFontSize"><?php _e('Title Font Size', 'easy-modal');?></label>
									</th>
									<td>
										<div id="contentTitleFontSizeSlider" class="value-slider"></div>
										<div class="slider-value"><span id="contentTitleFontSizeValue"><?php esc_attr_e(intval($settings['contentTitleFontSize']))?></span>px</div>
										<input type="hidden" name="contentTitleFontSize" id="contentTitleFontSize" value="<?php esc_attr_e(intval($settings['contentTitleFontSize']))?>" />
										<p class="description"><?php _e('Choose a font size for the title.','easy-modal')?></p>
									</td>
								</tr>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="contentTitleFontFamily"><?php _e('Title Font Family', 'easy-modal');?></label>
									</th>
									<td>
										<select name="contentTitleFontFamily" id="contentTitleFontFamily">
											<?php foreach($fontFamilys as $value => $name){?>
											<option value="<?php echo $value?>" <?php echo $value == $settings['contentTitleFontFamily'] ? 'selected="selected"' : ''?>><?php echo $name?></option>
											<?php }?>
										</select>
										<p class="description"><?php _e('Choose a font for the title.','easy-modal')?></p>
									</td>
								<tr class="form-field form-required">
									<th scope="row">
										<label for="contentFontColor"><?php _e('Font Color', 'easy-modal');?></label>
									</th>
									<td>
										<input type="text" name="contentFontColor" id="contentFontColor" value="<?php esc_attr_e($settings['contentFontColor'])?>" class="colorSelect" />
										<div class="color-swatch" style="background-color:<?php esc_attr_e($settings['contentFontColor'])?>" ></div>
										<p class="description"><?php _e('Choose a font color body of the modal.','easy-modal')?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="submit">
						<input type="hidden" name="safe_csrf_nonce_easy_modal" id="safe_csrf_nonce_easy_modal" value="<?php echo wp_create_nonce("safe_csrf_nonce_easy_modal"); ?>">
						<input type="submit" value="<?php _e('Save Settings','easy-modal')?>" name="submit" class="button-primary">
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="empreview">
		<div id="eModal-Preview">
			<div class="example-modal-overlay" style="background-color:<?php echo $settings['overlayColor']?>;opacity:<?php echo intval($settings['overlayOpacity'])/100 ?>;"></div>
			<h2>
				<?php _e('eModal Theme Preview','easy-modal')?>
			</h2>
			<div class="example-modal" style="
				background-color:<?php esc_attr_e($settings['containerBgColor'])?>;
				padding:<?php esc_attr_e(intval($settings['containerPadding']))?>px;
				border:<?php esc_attr_e($settings['containerBorderColor'] .' '. intval($settings['containerBorderWidth']).'px '.$settings['containerBorderStyle'])?>;
				-webkit-border-radius:<?php esc_attr_e(intval($settings['containerBorderRadius']))?>px;
				border-radius:<?php esc_attr_e(intval($settings['containerBorderRadius']))?>px;
				color:<?php esc_attr_e($settings['contentFontColor'])?>;
			">
				<div class="title" style="
					color:<?php esc_attr_e($settings['contentTitleFontColor'])?>;
					font-family:<?php esc_attr_e($settings['contentTitleFontFamily'])?>;
					font-size:<?php esc_attr_e(intval($settings['contentTitleFontSize']))?>px;
				">Title Text</div>
				<p>Suspendisse ipsum eros, tincidunt sed commodo ut, viverra vitae ipsum. Etiam non porta neque. Pellentesque nulla elit, aliquam in ullamcorper at, bibendum sed eros. Morbi non sapien tellus, ac vestibulum eros. In hac habitasse platea dictumst. Nulla vestibulum, diam vel porttitor placerat, eros tortor ultrices lectus, eget faucibus arcu justo eget massa. Maecenas id tellus vitae justo posuere hendrerit aliquet ut dolor.</p>
				<a class="close-modal" style="
						color:<?php esc_attr_e($settings['closeFontColor'])?>;
						<?php if($settings['closeLocation'] == 'outside'){?>
						font-size:<?php esc_attr_e(intval($settings['closeFontSize']))?>px;
						width:<?php esc_attr_e(intval($settings['closeSize']))?>px;
						height:<?php esc_attr_e(intval($settings['closeSize']))?>px;
						line-height:<?php esc_attr_e(intval($settings['closeSize']))?>px;
						
						background-color:<?php esc_attr_e($settings['closeBgColor'])?>;
						-webkit-border-radius:<?php esc_attr_e(intval($settings['closeBorderRadius']))?>px;
						border-radius:<?php esc_attr_e(intval($settings['closeBorderRadius']))?>px;
						<?php
						$size = intval(0-($settings['closeSize']/2)).'px';
						$top = $left = $bottom = $right = 'auto';
						switch($settings['closePosition'])
						{
							case 'topleft': 
								$top = $size; 
								$left = $size; 
								break;
							case 'topright':
								$top = $size; 
								$right = $size; 
								break;
							case 'bottomleft': 
								$left = $size; 
								$bottom = $size;
								break;
							case 'bottomright':
								$right = $size; 
								$bottom = $size;
								break;
						}
						?>
						top: <?php esc_attr_e($top)?>;
						bottom: <?php esc_attr_e($bottom)?>;
						left: <?php esc_attr_e($left)?>;
						right: <?php esc_attr_e($right)?>;
						<?php }?>
					">&#215;</a>
			</div>
		</div>
	</div>
</div>