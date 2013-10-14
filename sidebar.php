<?php disallow_direct_load('sidebar.php');?>

<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Sidebar')):?>

	<?php $taxonomy_term = get_post_meta($post->ID, 'page_taxonomy_term', TRUE) ? get_post_meta($post->ID, 'page_taxonomy_term', TRUE) : $post->post_name; ?>
	
	<p class="post-search-header-text">Search This Section</p>
	<?=//do_shortcode('[post-type-search post_type_name="resourcelink" taxonomy="pg_sections" taxonomy_term="'.$taxonomy_term.'" column_width="span3" column_count="1" show_empty_sections=true default_search_text="Find a Resource"]')?>
	<div id="sidebar-nav">
		<?=wp_nav_menu(array(
			'theme_location' => '',
			'menu' => $taxonomy_term,
			'menu_class' => 'nav nav-list',
			'container' => 'false', 
			'menu_id' => 'sidebar-menu', 
			'fallback_cb' => false,
			'depth' => 0,
			'walker' => new wp_bootstrap_navwalker(),
			));
		?>
	</div>			
<?php endif;?>
