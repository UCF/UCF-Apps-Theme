<?php
	$settings = $this->getSettings();
	$messages = $this->messages;
	$license_status = get_option('EasyModal_License_Status');
	$valid = is_array($license_status) && !empty($license_status['status']) && in_array($license_status['status'], array(2000,2001,3003)) ? true : false;
?>
<div class="wrap">
	<?php if(!empty($this->messages)){?>
		<?php foreach($this->messages as $message){?>
		<div class="<?php _e($message['type'],'easy-modal')?>"><strong><?php _e($message['message'],'easy-modal')?>.</strong></div>
		<?php }?>
	<?php }?>
	<?php screen_icon()?>
	<h2>
		<?php _e('Settings','easy-modal')?>
	</h2>
	<?php if(!$valid){?>
	<div class="error">
		<p>If you purchased the Pro version and havent already recieved a key please email us at <a href="mailto:danieliser@wizardinternetsolutions.com">danieliser@wizardinternetsolutions.com</a></p>
	</div>   
	<?php }?>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<h2 id="em-tabs" class="nav-tab-wrapper">
						<a href="#top#general" id="general-tab" class="nav-tab"><?php _e('General','easy-modal')?></a>
					</h2>
					<div class="tabwrapper">
						<form method="post" action="admin.php?page=<?php echo EASYMODAL_SLUG?>-settings">
							<input type="hidden" name="em_settings" value="settings"/>
							<div id="general" class="em-tab">
								<table class="form-table">
									<tbody>
										<tr class="form-field form-required">
											<th scope="row">
												<label for="name"><?php _e('License Key', 'easy-modal');?> <span class="description">(required)</span></label>
											</th>
											<td>
												<input <?php echo $valid ? 'style="background-color:#0f0;border-color:#090;"' : '' ?> type="text" id="license" name="license" value="<?php esc_attr_e(get_option('EasyModal_License'))?>"/>
												<p class="description"><?php _e( is_array($license_status) && !empty($license_status['message']) ? $license_status['message'] : 'Enter a key to unlock Easy Modal Pro.','easy-modal')?></p>
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
			<div id="postbox-container-1" class="postbox-container">
				<?php require(EASYMODAL_DIR.'/inc/views/sidebar.php')?>
			</div>
		</div>
		<br class="clear"/>
	</div>
</div>