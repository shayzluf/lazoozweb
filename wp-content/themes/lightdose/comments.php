<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to light_dose_comment() which is
 * located in the inc/template-tags.php file.
 *
 * @package light_dose
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required())
    return;
?>

<?php // You can start editing here -- including this comment!  ?>

<?php if (have_comments()) : ?>
    <!--
    <h2 class="comments-title">
    <?php
    printf(_nx('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'light_dose'), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>');
    ?>
    </h2>
    -->
    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through  ?>
        <nav id="comment-nav-above" class="comment-navigation" role="navigation">
            <h1 class="screen-reader-text"><?php _e('Comment navigation', 'light_dose'); ?></h1>
            <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'light_dose')); ?></div>
            <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'light_dose')); ?></div>
        </nav><!-- #comment-nav-above -->
    <?php endif; // check for comment navigation  ?>

    <ol class="commentlist">
        <?php
        /* Loop through and list the comments. Tell wp_list_comments()
         * to use light_dose_comment() to format the comments.
         * If you want to overload this in a child theme then you can
         * define light_dose_comment() and that will be used instead.
         * See light_dose_comment() in inc/template-tags.php for more.
         */
        wp_list_comments(array('callback' => 'light_dose_comment', 'style' => 'ol', 'avatar_size' => 70,));
        ?>
    </ol><!-- .comment-list -->

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through  ?>
        <nav id="comment-nav-below" class="comment-navigation" role="navigation">
            <h1 class="screen-reader-text"><?php _e('Comment navigation', 'light_dose'); ?></h1>
            <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'light_dose')); ?></div>
            <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'light_dose')); ?></div>
        </nav><!-- #comment-nav-below -->
    <?php endif; // check for comment navigation  ?>

<?php endif; // have_comments() ?>

<?php
// If comments are closed and there are comments, let's leave a little note, shall we?
if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
    ?>
    <p class="no-comments"><?php _e('Comments are closed.', 'light_dose'); ?></p>
<?php else : ?>
<h4><?php echo __('Leave a Comment', 'light_dose'); ?></h4>
<?php
global $user_level;
ob_start();
if ($user_level == 0) {
    $form_args = array(
        'comment_notes_after' => '',
        'comment_notes_before' => '<span class="pull-right italic label-required"> * Required fields are marked </span>',
        'title_reply ' => '',
        'label_submit' => __('Submit', 'light_dose'),
        'comment_field' => '<div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<textarea name="comment" name="comment" class="stretch-width form-control" placeholder="' . __('Comment *', 'light_dose') . '" data-validator="required"></textarea>
                                    </div>
				</div>',
        'fields' => apply_filters('comment_form_default_fields', array(
            'author' => '<div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
                                            <input name="author" id="author" type="text" class="form-control" placeholder="' . __('Name *', 'light_dose') . '" data-validator="required|alpha_numeric">
                                            <input id="spam_bot" type="hidden">
                                    </div>
                             </div>',
            'email' => '<div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
                                            <input name="email" id="email" type="text" class="form-control" placeholder="' . __('Email *', 'light_dose') . '" data-validator="required|valid_email">
                                    </div>
                            </div>',
            'url' => '',
        ))
    );
} else {
    $form_args = array(
        'comment_notes_after' => '',
        'logged_in_as' => '',
        'title_reply ' => '',
        'label_submit' => __('Submit', 'light_dose'),
        'class_submit' => 'btn',
        'comment_field' => '<div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<textarea name="comment" name="comment" class="stretch-width form-control" placeholder="' . __('Comment *', 'light_dose') . '" data-validator="required"></textarea>
                                    </div>
				</div>',
    );
}

comment_form($form_args);
$form = ob_get_clean();
$form = str_replace('id="submit"', 'class="btn"', $form);
$form = str_replace('id="commentform"', 'name="commentsForm"', $form);
echo str_replace('class="comment-form"', 'class="validator"', $form);
?>
<?php endif; ?>

<!-- #comments -->