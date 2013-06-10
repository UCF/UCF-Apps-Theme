<?php
/**
 * Template Name: Two Column
 **/
?>
<?php get_header(); ?>
	<div class="container">
		<?php the_post();?>
			<div class="row page-content" id="<?=$post->post_name?>">
				<div class="span12" id="page-top">
					<article>
						<?=get_breadcrumbs($post->ID)?>
						<? if(!is_front_page())	{ ?>
							<h2><?php the_title();?></h2>
						<? } ?>
						
						<?php if (get_post_meta($post->ID, 'page_subheader', TRUE)) {
							print get_post_meta($post->ID, 'page_subheader', TRUE);
						}
						?>
					</article>
				</div>
				<div id="sidebar" class="span3">
					<?=get_sidebar();?>
				</div>
				<div class="span9">
					<article>
						<?php the_content();?>
					</article>
				</div>
			</div>
	</div>
	<?
	if(get_post_meta($post->ID, 'page_hide_fold', True) != 'on'): 
		get_template_part('includes/below-the-fold'); 
	endif
	?>
<?php get_footer();?>