<p>
	<input type="hidden" name="safe_csrf_nonce_easy_modal" id="safe_csrf_nonce_easy_modal" value="<?php echo wp_create_nonce("safe_csrf_nonce_easy_modal"); ?>">
	<label for="easy_modal_post_modals"><?php _e( "Select which modals to load", 'easy-modal' ); ?></label>
	<select class="widefat" multiple="multiple" name="easy_modal_post_modals[]" id="easy_modal_post_modals">
		<?php foreach($modals as $key => $name)
		{
			$modal = $this->getModalSettings($key);
			if(!$modal['sitewide']) {?>
			<option value="<?php esc_attr_e($key)?>"<?php echo (is_array($current_modals) && in_array($key,$current_modals)) ? esc_attr(' selected="selected"') : ''?>><?php esc_html_e($name)?></option>
			<?php }
		}?>
	</select>
</p>
