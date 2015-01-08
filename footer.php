				</div>
				<div id = "footer-background">	
						<div id="footer" class="container">
							<div id="footer-widget-wrap" class="clearfix">																<div class="row">
								<div class="span2 offset4">
										<a href="https://play.google.com/store/apps/details?id=com.citrix.Receiver" alt="Download for Android">
											<img src="<?php echo THEME_IMG_URL; ?>/en_app_rgb_and_45.png" alt="android" class="img-thumbnail img-responsive" />
										</a>
									</div>
									<div class="span2">
										<a href="https://itunes.apple.com/us/app/citrix-receiver/id363501921" alt="Download for iOS">
											<img src="<?php echo THEME_IMG_URL; ?>/en_app_rgb_ios_45.png" alt="ios" class="img-thumbnail img-responsive" />
										</a>
									</div>
								</div>
								<br />
								<div class="row">
									
										<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column Two')):?>
											<?php $options = get_option(THEME_OPTIONS_NAME);?>
											
											<div>
												<?php 
													if (has_nav_menu('footer-menu')) { ?>
														<?=wp_nav_menu(array(
															'theme_location' => 'footer-menu', 
															'container' => 'false', 
															'menu_class' => '', 
															'menu_id' => 'footer-menu', 
															'fallback_cb' => false,
															'depth' => 1,
															'walker' => new Bootstrap_Walker_Nav_Menu()
															));
													}   ?>		

												<p>© 2014 University of Central Florida, All Rights Reserved</p>
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
