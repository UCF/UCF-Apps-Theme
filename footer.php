			<div id="footer" class="container">
				<div class="row">
                    <div class="span12">
				<?=wp_nav_menu(array(
					'theme_location' => 'footer-menu', 
					'container' => 'false', 
					'menu_class' => 'menu horizontal', 
					'menu_id' => 'footer-menu', 
					'fallback_cb' => false,
					'depth' => 1,
					'walker' => new Bootstrap_Walker_Nav_Menu()
					));
				?>
                    </div>
                </div>
                <div class="row">
                    <div class="span12">
                        <?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column Two')):?>
                            <?php $options = get_option(THEME_OPTIONS_NAME);?>
                            <div id="footer-info">
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
                                <span class="footer-emphasize">
                                <?php if($options['phone_number'] and $options['fax_number']): ?>
                                    <br />Phone: <?=$options['phone_number'];?> | Fax: <?=$options['fax_number'];?>
                                <?php elseif($options['phone_number'] and !$options['fax_number']): ?>
                                    <br /><?=$options['phone_number'];?>
                                <?php elseif(!$options['phone_number'] and $options['fax_number']): ?>
                                    <br />Fax: <?=$options['fax_number'];?>
                                <?php endif; ?>
                                </span>

                                <span class="footer-emphasize">
                                <?php if($options['site_contact']): ?>
                                    &nbsp; | &nbsp;<a href="mailto:<?=$options['site_contact']?>"><?=$options['site_contact']?></a>
                                <?php endif; ?>
                                </span>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
			</div>
		</div><!-- container -->
	</body>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?="\n".footer_()."\n"?>
</html>