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

		</div>
	</div>
<?php get_footer();?>
