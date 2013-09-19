<div class="wrap">
	<?php if(!empty($this->messages)){?>
		<?php foreach($this->messages as $message){?>
		<div class="<?php _e($message['type'],'easy-modal')?>"><strong><?php _e($message['message'],'easy-modal')?>.</strong></div>
		<?php }?>
	<?php }?>
	<?php screen_icon()?>
	<h2><?php _e('Delete Modal','easy-modal')?></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<<< - <a href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php echo intval($modal_id)?>">Back to Modal</a>
				<p>Are you sure you want to delete this modal?</p>
				<a style="color:#fff;border:1px solid #333; background-color:#900; text-shadow: none;"class="add-new-h2" href="admin.php?page=<?php echo EASYMODAL_SLUG?>&modal_id=<?php echo intval($modal_id)?>&action=delete&confirm=yes&safe_csrf_nonce_easy_modal=<?php echo wp_create_nonce("safe_csrf_nonce_easy_modal")?>">Confirm</a>
			</div>
			<div id="postbox-container-1" class="postbox-container">
				<?php require(EASYMODAL_DIR.'/inc/views/sidebar.php')?>
			</div>
		</div>
		<br class="clear"/>
	</div>
</div>