<?php
/**
 * The footer for our theme
 *
 * @package Blogsy
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_template_part( 'template-parts/footer/footer' ); ?>
	</div><!-- #site-inner -->
</div><!-- #site -->

<?php get_template_part( 'template-parts/footer/back-to-top' ); ?>
<?php
if ( \Blogsy\Helper::get_option( 'cursor_effect' ) && empty( $_REQUEST['elementor-preview'] ) ) {
	echo '<div class="blogsy-mouse-cursor outer"></div><div class="blogsy-mouse-cursor inner"></div>';
}
?>
<?php wp_footer(); ?>

</body>
</html>
