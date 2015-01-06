<?php
/**
 * The template for displaying search forms in Light Dose
 *
 * @package Light Dose
 */
?>

<form class="search" method="get" name="search" action="<?php echo esc_url(home_url('/')); ?>">
        <input class="form-control" type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php echo esc_attr_x('Search', 'placeholder', 'light_dose'); ?>" />
        <div class="overlay"></div>
        <button class="glyphicon glyphicon-search" type="submit" name="<?php echo esc_attr_x('Search', 'submit button', 'light_dose'); ?>" value=""></button>
</form>

