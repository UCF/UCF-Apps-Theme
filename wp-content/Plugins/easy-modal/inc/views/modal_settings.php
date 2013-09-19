<?php
$sizes = array(
	'' => 'Normal',
	'tiny' => 'Tiny',
	'small' => 'Small',
	'medium' => 'Medium',
	'large' => 'Large',
	'xlarge' => 'X Large',
	'custom' => 'Custom'
);
$animations = array(
	'fade' => 'Fade',
	'fadeAndSlide' => 'Fade and Slide',
	'grow' => 'Grow',
	'growAndSlide' => 'Grow and Slide',
);
$directions = array(
	'top' => 'Top',
	'bottom' => 'Bottom',
	'left' => 'Left',
	'right' => 'Right',
	'topleft' => 'Top Left',
	'topright' => 'Top Right',
	'bottomleft' => 'Bottom Left',
	'bottomright' => 'Bottom Right',
	'mouse' => 'Mouse',
);
$cssUnits = array(
	'px' => 'PX',
	'%' => '%',
	'em' => 'EM',
	'rem' => 'REM'
);
$animationshowhide = !in_array($settings['animation'], array('fadeAndSlide','growAndSlide')) ? ' style="display:none"' : '';
$sizeshowhide = $settings['size'] != 'custom' ? ' style="display:none"' : '';
?><div class="wrap">
	<?php if(!empty($this->messages)){?>
		<?php foreach($this->messages as $message){?>
		<div class="<?php _e($message['type'],'easy-modal')?>"><strong><?php _e($message['message'],'easy-modal')?>.</strong></div>
		<?php }?>
	<?php }?>
	<?php screen_icon()?>
	<h2>
		<?php echo ucfirst(esc_html($settings['name'])).' '; _e('Modal','easy-modal')?>
		<?php if(is_numeric($modal_id)){?>
		<a style="color:#fff;border:1px solid #333; background-color:#21759B; text-shadow: none;"class="add-new-h2" href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php echo intval($modal_id)?>&action=clone&safe_csrf_nonce_easy_modal=<?php echo wp_create_nonce("safe_csrf_nonce_easy_modal")?>">Clone</a>
		<a style="color:#fff;border:1px solid #333; background-color:#900; text-shadow: none;"class="add-new-h2" href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php echo intval($modal_id)?>&action=delete&safe_csrf_nonce_easy_modal=<?php echo wp_create_nonce("safe_csrf_nonce_easy_modal")?>">Delete</a>
		<?php }?>
	</h2>
	<div><<< - <a href="admin.php?page=<?php echo EASYMODAL_SLUG?>">Back to Modal Lists</a></div>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<h2 id="em-tabs" class="nav-tab-wrapper">
						<a href="#top#general" id="general-tab" class="nav-tab"><?php _e('General','easy-modal')?></a>
						<a href="#top#options" id="options-tab" class="nav-tab"><?php _e('Options','easy-modal')?></a>
						<a href="#top#examples" id="examples-tab" class="nav-tab"><?php _e('Examples','easy-modal')?></a>
					</h2>
					<div class="tabwrapper">
						<form method="post" action="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php echo is_numeric($modal_id) ? intval($modal_id) : 'new' ?>">
							<input type="hidden" name="em_settings" value="modal"/>
							<div id="general" class="em-tab">
								<table class="form-table">
									<tbody>
										<tr class="form-field form-required">
											<th scope="row">
												<label for="name"><?php _e('Name', 'easy-modal');?> <span class="description">(required)</span></label>
											</th>
											<td>
												<input type="text" name="name" id="name" value="<?php esc_attr_e($settings['name'])?>"/>
												<p class="description"><?php _e('Enter a name for your modal.','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required">
											<th scope="row">
												<label for="sitewide"><?php _e('Sitewide', 'easy-modal');?></label>
											</th>
											<td>
												<p class="field switch" style="clear:both; overflow:auto; display:block;">
													<label class="cb-enable"><span>Yes</span></label>
													<label class="cb-disable selected"><span>No</span></label>
													<input type="checkbox" class="checkbox" id="sitewide" name="sitewide" value="true" <?php echo $settings['sitewide'] == true ? 'checked="checked"' : '' ?> />
												</p>
												<p class="description"><?php _e('Should this modal be loaded on every page of the site?','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required">
											<th scope="row">
												<label for="title"><?php _e('Title', 'easy-modal');?></label>
											</th>
											<td>
												<input type="text" id="title" name="title" value="<?php esc_attr_e($settings['title']);?>" />
												<p class="description"><?php _e('The title that appears in the modal window.','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required">
											<th scope="row">
												<label for="content"><?php _e('Content', 'easy-modal');?></label>
											</th>
											<td>
												<?php wp_editor($settings['content'], "content");?>
												<p class="description"><?php _e('Modal content. Can contain shortcodes.','easy-modal')?></p>
											</td>
										</tr>
									</tbody>
								</table>							
							</div>
							<div id="options" class="em-tab">
								<table class="form-table">
									<tbody>
										<tr class="form-field form-required">
											<th scope="row">
												<label for="size"><?php _e('Size', 'easy-modal');?></label>
											</th>
											<td>
												<select name="size" id="size">
													<?php foreach($sizes as $value => $name){?>
													<option value="<?php echo $value?>" <?php echo $value == $settings['size'] ? 'selected="selected"' : ''?>><?php echo $name?></option>
													<?php }?>
												</select>
												<p class="description"><?php _e('Select the size of the modal.','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required custom-size-only"<?php echo $sizeshowhide?>>
											<th scope="row">
												<label for="userHeight"><?php _e('Height', 'easy-modal');?></label>
											</th>
											<td>
												<input type="text" id="userHeight" name="userHeight" size="5" value="<?php esc_attr_e($settings['userHeight'])?>" />
												<select name="userHeightUnit" id="userHeightUnit">
													<?php foreach($cssUnits as $value => $name){?>
													<option value="<?php echo $value?>" <?php echo $value == $settings['userHeightUnit'] ? 'selected="selected"' : ''?>><?php echo $name?></option>
													<?php }?>
												</select>
												<p class="description"><?php _e('Set a custom height for the modal.','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required custom-size-only"<?php echo $sizeshowhide?>>
											<th scope="row">
												<label for="userWidth"><?php _e('Width', 'easy-modal');?></label>
											</th>
											<td>
												<input type="text" id="userWidth" name="userWidth" size="5" value="<?php echo esc_attr_e($settings['userWidth'])?>" />
												<select name="userWidthUnit" id="userWidthUnit">
													<?php foreach($cssUnits as $value => $name){?>
													<option value="<?php echo $value?>" <?php echo $value == $settings['userWidthUnit'] ? 'selected="selected"' : ''?>><?php echo $name?></option>
													<?php }?>
												</select>
												<p class="description"><?php _e('Set a custom height for the modal.','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required">
											<th scope="row">
												<label for="animation"><?php _e('Animation', 'easy-modal');?></label>
											</th>
											<td>
												<select name="animation" id="animation">
													<?php foreach($animations as $value => $name){?>
													<option value="<?php echo $value?>" <?php echo $value == $settings['animation'] ? 'selected="selected"' : ''?>><?php echo $name?></option>
													<?php }?>
												</select>
												<p class="description"><?php _e('Select an animation for the modal.','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required">
											<th scope="row">
												<label for="duration"><?php _e('Speed', 'easy-modal');?></label>
											</th>
											<td>
												<input type="text" id="duration" name="duration" size="3" value="<?php esc_attr_e(intval($settings['duration']))?>" />MS
												<p class="description"><?php _e('Set the animation speed for the modal.','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required animation-only"<?php echo $animationshowhide?>>
											<th scope="row">
												<label for="direction"><?php _e('Direction', 'easy-modal');?></label>
											</th>
											<td>
												<select name="direction" id="direction">
													<?php foreach($directions as $value => $name){?>
													<option value="<?php echo $value?>" <?php echo $value == $settings['direction'] ? 'selected="selected"' : ''?>><?php echo $name?></option>
													<?php }?>
												</select>
												<p class="description"><?php _e('Select a direction for the animation.','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required">
											<th scope="row">
												<label for="overlayClose"><?php _e('Click Overlay to Close?', 'easy-modal');?></label>
											</th>
											<td>
												<p class="field switch" style="clear:both; overflow:auto; display:block;">
													<label class="cb-enable"><span>On</span></label>
													<label class="cb-disable selected"><span>Off</span></label>
													<input type="checkbox" class="checkbox" id="overlayClose" name="overlayClose" value="true" <?php echo $settings['overlayClose'] == true ? 'checked="checked"' : '' ?> />
												</p>
												<p class="description"><?php _e('Choose whether the modal will close when you click the overlay.','easy-modal')?></p>
											</td>
										</tr>
			
										<tr class="form-field form-required">
											<th scope="row">
												<label for="overlayEscClose"><?php _e('ESC Key to Close?', 'easy-modal');?></label>
											</th>
											<td>
												<p class="field switch" style="clear:both; overflow:auto; display:block;">
													<label class="cb-enable"><span>On</span></label>
													<label class="cb-disable selected"><span>Off</span></label>
													<input type="checkbox" class="checkbox" id="overlayEscClose" name="overlayEscClose" value="true" <?php echo $settings['overlayEscClose'] == true ? 'checked="checked"' : '' ?> />
												</p>
												<p class="description"><?php _e('Choose whether the modal will close press Esc.','easy-modal')?></p>
											</td>
										</tr>
			
									</tbody>
								</table>							
							</div>
							<div id="examples" class="em-tab">
								<h4><?php _e('Copy this class to the link/button you want to open this modal.','easy-modal')?><span class="desc">eModal-<?php echo intval($settings['id'])?></span></h4>
								<h4>Link Example</h4>
								<a href="#" onclick="return false;" class="eModal-<?php echo intval($settings['id'])?>">Open Modal</a>
								<pre>&lt;a href="#" class="eModal-<?php echo intval($settings['id'])?>">Open Modal&lt;/a></pre>
								<h4>Button Example</h4>
								<button onclick="return false;" class="eModal-<?php echo intval($settings['id'])?>">Open Modal</button>
								<pre>&lt;button class="eModal-<?php echo intval($settings['id'])?>">Open Modal&lt;/button></pre>
								<h4>Image Example</h4>
								<img style="cursor:pointer;" src="<?php echo EASYMODAL_URL?>/inc/images/admin/easy-modal-icon.png" onclick="return false;" class="eModal-<?php echo intval($settings['id'])?>" />
								<pre>&lt;img src="easy-modal-icon.png" class="eModal-<?php echo intval($settings['id'])?>" /></pre>
							</div>
							<div class="submit">
								<input type="hidden" name="safe_csrf_nonce_easy_modal" id="safe_csrf_nonce_easy_modal" value="<?php echo wp_create_nonce("safe_csrf_nonce_easy_modal"); ?>">
								<input type="submit" value="<?php _e('Save Settings','easy-modal')?>" name="submit" class="button-primary">
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="postbox-container-1" class="postbox-container">
				<?php require(EASYMODAL_DIR.'/inc/views/sidebar.php')?>
			</div>
		</div>
		<br class="clear"/>
	</div>
</div>