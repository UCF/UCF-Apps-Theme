				<div id = "footer-background">	
						<div id="footer" class="container">
							<div id="footer-widget-wrap" class="clearfix">
								<div class="row">
									
										<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column Two')):?>
											<?php $options = get_option(THEME_OPTIONS_NAME);?>
											
											<div class = "span3">
												<h4>Contact us</h4>
												<?php if($options['organization_name']): ?>
													<span class="footer-emphasize"><?= $options['organization_name']; ?></span>
													<br />
												<?php endif;?>
												University of Central Florida<br />
												<?php if ($options['organization_name'] and $options['street_address'] and $options['city_address'] and $options['state_address'] and $options['zip_address']): ?>
													<?=$options['street_address'];?>
													<br />
													<?=$options['city_address'];?>, <?=$options['state_address'];?> <?=$options['zip_address'];?>
													<br />
												<?php elseif($options['street_address'] and $options['city_address'] and $options['state_address'] and $options['zip_address']): ?>
													<?=$options['street_address'];?>
													<br />
													<?=$options['city_address'];?>, <?=$options['state_address'];?> <?=$options['zip_address'];?>
													<br />
												<?php endif;?>
												<?php if($options['phone_number'] and $options['fax_number']): ?>
													<br />Phone: <?=$options['phone_number'];?> | Fax: <?=$options['fax_number'];?>
												<?php elseif($options['phone_number'] and !$options['fax_number']): ?>
													<br /><?=$options['phone_number'];?>
												<?php elseif(!$options['phone_number'] and $options['fax_number']): ?>
													<br />Fax: <?=$options['fax_number'];?>
												<?php endif; ?>
												<?php if($options['site_contact']): ?>
													&nbsp; | &nbsp;<a href="mailto:<?=$options['site_contact']?>"><?=$options['site_contact']?></a>
												<?php endif; ?>								   
											</div>
											<div class = "span2">
											</div>
											<div class = "span3">
												<h4>Useful Links</h4>
												<?=wp_nav_menu(array(
													'theme_location' => 'footer-menu', 
													'container' => 'false', 
													'menu_class' => '', 
													'menu_id' => 'footer-menu', 
													'fallback_cb' => false,
													'depth' => 1,
													'walker' => new Bootstrap_Walker_Nav_Menu()
													));
												?>
											</div>
											<div class = "span2">
											</div>
											<div class = "span3">								
												<a class="ignore-external" href="http://www.ucf.edu"><img id="footer-logo" src="<?=THEME_IMG_URL?>/50th-220x80.png" alt="" title="" /></a>
											</div>	

										<?php endif;?>
									
								</div>
							<div>
						</div>
				</div>
			</div><!-- container -->
		</div>
	</div> <!-- wrap -->
	</body>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?="\n".footer_()."\n"?>
</html>
