<?php
/**
 * Template Name: One Column
 **/
?>
<?php get_header(); ?>
    <?php the_post();?>
        <div class="page-content clearfix" id="<?=$post->post_name?>">
            <div class="span12">
                <article>
                    <? if(!is_front_page())	{ ?>
                            <h2><?php the_title();?></h2>
                    <? } ?>
                    <?php the_content();?>
                </article>
            </div>
        </div>
<?php get_footer();?>
