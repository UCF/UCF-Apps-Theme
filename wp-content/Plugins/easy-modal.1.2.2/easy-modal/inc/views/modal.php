<div id="eModal-<?php esc_attr_e($modal['id'])?>" class="<?php esc_attr_e($modal['size'] != 'custom' ? $modal['size'].' ' : '')?>modal">
	<?php if($modal['title'] != '') {?>
	<div class="title"><?php esc_html_e($modal['title'])?></div>
	<?php }?>
	<?php echo apply_filters('em_modal_content', $modal['content']);?>
	<a class="close-modal">&#215;</a>
</div>