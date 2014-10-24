<?php
/**
 * Template Name: Two Column
 **/
?>
<?php get_header(); ?>
		<?php the_post();?>
			<div class="row page-content" id="<?=$post->post_name?>">
				<div class="span12" id="page-top">
					<article> 
						<?=get_breadcrumbs($post->ID)?> 
					 </article> 
				</div>
				<div class="span9">
					<article>
						<? if(!is_front_page())	{ ?>
							<h2><?php the_title();?></h2>
						<? } ?>
						
						<?php if (get_post_meta($post->ID, 'page_subheader', TRUE)) {
							print get_post_meta($post->ID, 'page_subheader', TRUE);
						}
						?>  					
						<?php the_content();?>
					</article>
				</div>
				<div id="sidebar" class="span3">
					<?=get_sidebar();?> 
				</div>
			</div>

<?php get_footer();?>
