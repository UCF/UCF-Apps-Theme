<?php disallow_direct_load('sidebar.php');?>

<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Sidebar')):?>

	<?php $side_menu = get_post_meta($post->ID, 'page_sidebar_menu', TRUE) ? get_post_meta($post->ID, 'page_sidebar_menu', TRUE) : $post->post_name; ?>
	
	<h2 class=widgettitle"><? echo $side_menu ?> </h2>
	<?=wp_nav_menu(array(
	'theme_location' => '',
	'menu' => $side_menu,
	'container' => 'false',
	'menu_class' => 'widget widget_nav_menu'.get_header_styles(),
	'menu_id' => 'sidebar-menu'
	));
	?>
		
<?php endif;?>
