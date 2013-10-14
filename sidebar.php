<?php disallow_direct_load('sidebar.php');?>

<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Sidebar')):?>

	<?php $side_menu = get_post_meta($post->ID, 'page_sidebar_menu', TRUE) ? get_post_meta($post->ID, 'page_sidebar_menu', TRUE) : $post->post_name; ?>
	
	<?=wp_nav_menu(array(
	'theme_location' => '',
	'menu' => $side_menu,
	'container' => 'false',
	'menu_class' => 'menu '.get_header_styles(),
	'menu_id' => 'sidebar-menu',
	'walker' => new Bootstrap_Walker_Nav_Menu()
	));
	?>
		
<?php endif;?>
