<div class="wrap">
	<?php if(!empty($this->messages)){?>
		<?php foreach($this->messages as $message){?>
		<div class="<?php _e($message['type'],'easy-modal')?>"><strong><?php _e($message['message'],'easy-modal')?>.</strong></div>
		<?php }?>
	<?php }?>
	<?php screen_icon()?>
	<h2><?php _e('Help','easy-modal')?></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="meta-box-sortables ui-sortable">
						<h2 id="em-tabs" class="nav-tab-wrapper">
							<a href="#top#usage" id="usage-tab" class="nav-tab"><?php _e('Usage','easy-modal')?></a>
						</h2>
						<div class="tabwrapper">
							<div id="usage" class="em-tab">
								<h4><?php _e('Copy the class to the link/button you want to open this modal.','easy-modal')?><span class="desc">Will start with eModal- and end with a # of the modal you want to open.</span></h4>
								<h4>Link Example</h4>
								<a href="#" onclick="return false;" class="eModal-1">Open Modal</a>
								<pre>&lt;a href="#" class="eModal-1">Open Modal&lt;/a></pre>
								<h4>Button Example</h4>
								<button onclick="return false;" class="eModal-1">Open Modal</button>
								<pre>&lt;button class="eModal-1">Open Modal&lt;/button></pre>
								<h4>Image Example</h4>
								<img style="cursor:pointer;" src="<?php echo EASYMODAL_URL?>/inc/images/admin/easy-modal-icon.png" onclick="return false;" class="eModal-1" />
								<pre>&lt;img src="easy-modal-icon.png" class="eModal-1" /></pre>
							</div>
						</div>
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