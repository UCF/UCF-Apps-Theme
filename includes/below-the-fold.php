<?php $options = get_option(THEME_OPTIONS_NAME);?>
<?php if($options['enable_events']):?>
    <?php display_events('h2')?>
<?php else:?>&nbsp;
    <?php debug("Events feed is disabled.")?>
<?php endif;?>
<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Bottom Right')):?><?php endif;?>