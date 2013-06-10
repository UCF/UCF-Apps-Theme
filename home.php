<?php get_header();?>
<?php global $post; ?>
<?php $options = get_option(THEME_OPTIONS_NAME);?>
<?php $page    = get_page_by_title('Home');?>

<?php $description = $options['site_description'];?>
<?php if($description):?>
    <div id="home-description" class="wide">
        <div class="container">
            <div class="row">
                <div class="span12">
                    <?=$description; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>

    <div class="container">
        <div class="page-content" id="home">
            <?php $home_links = get_posts( array ( 'numberposts' => '-1', 'post_type' => 'page', 'meta_key' => 'page_home_link', 'meta_value' => 'on', 'orderby' => 'menu_order', 'order' => 'ASC')); ?>
            <?php if(count($home_links)): ?>
                <div class="row home-links">
                <?php foreach($home_links as $key => $post): setup_postdata($post); ?>
                    <?php $key++; ?>
                    <div class="span4">
                        <div class="home-link <?php echo($key % 3 == 0 ? '': 'side-bar') ?>">
                            <a href="<?=get_permalink(); ?>">
                                <div class="home-link-wrapper">
                                    <?php the_post_thumbnail(); ?>
                                    <div class="home-link-title"><?=the_title(); ?></div>
                                    <div class="home-link-desc"><?=get_post_meta(get_the_ID(), 'page_home_description', true); ?></div>
                                    <div style="clear: both;"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php if($key % 3 == 0): ?>
                </div>
                        <?php if($key < count($home_links)): ?>
                <div class="row home-links">
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="span12">
                        <p>No homepage links available.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div id="events-header" class="wide">
        <div class="container">
            <div class="row">
                <div class="span12"></div>
            </div>
        </div>
    </div>
    <div id="events" class="container">
        <?php get_template_part('includes/below-the-fold'); ?>
    </div>
<?php get_footer();?>