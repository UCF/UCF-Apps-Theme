				</div>
				<div id = "footer-background">	
						<div id="footer" class="container">
							<div id="footer-widget-wrap" class="clearfix">
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
