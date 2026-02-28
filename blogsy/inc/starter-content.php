<?php
/**
 * Starter Content
 *
 * @package Blogsy
 */

namespace Blogsy;

/**
 * Starter Content Class
 *
 * @since 1.0.0
 */
class Starter_Content {

	/**
	 * Get starter content array.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get(): array {
		return [
			'widgets'   => [
				'sidebar-1'       => [
					'text_about',
					'search',
					'recent-posts',
					'text_business_info',
				],
				'blogsy-footer-1' => [
					'text_about',
				],
				'blogsy-footer-2' => [
					'recent-posts',
				],
				'blogsy-footer-3' => [
					'archives' => [
						'title' => __( 'Archives', 'blogsy' ),
						'count' => true,
						'type'  => 'monthly',
					],
				],
				'blogsy-footer-4' => [
					'text_business_info',
				],
			],
			'posts'     => [
				'home'  => [
					'post_type'    => 'page',
					'post_title'   => __( 'Home', 'blogsy' ),
					'template'     => 'full-width-template.php',
					'post_content' => '
                        <!-- wp:spacer {"height":"16px"} -->
                        <div style="height:16px" aria-hidden="true" class="wp-block-spacer"></div>
                        <!-- /wp:spacer -->
                        <!-- wp:pattern {"slug":"blogsy/post-slider-02"} /-->
                        <!-- wp:pattern {"slug":"blogsy/featured-items"} /-->
                        <!-- wp:pattern {"slug":"blogsy/post-list-02"} /-->
                        <!-- wp:pattern {"slug":"blogsy/post-list-03"} /-->
                        <!-- wp:pattern {"slug":"blogsy/call-to-action"} /-->
                    ',
				],
				'blog'  => [
					'post_type'  => 'page',
					'post_title' => __( 'Blog', 'blogsy' ),
					'post_name'  => 'blog',
				],
				'about' => [
					'post_type'    => 'page',
					'post_title'   => __( 'About', 'blogsy' ),
					'template'     => 'full-width-template.php',
					'post_content' => '
                        <!-- wp:pattern {"slug":"blogsy/about-us-01"} /-->
                        <!-- wp:pattern {"slug":"blogsy/call-to-action"} /-->
                        <!-- wp:pattern {"slug":"blogsy/about-us-02"} /-->
                        <!-- wp:pattern {"slug":"blogsy/info-items"} /-->
                        <!-- wp:spacer {"height":"20px"} -->
                        <div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
                        <!-- /wp:spacer -->
                    ',
				],
				'contact',
			],
			'options'   => [
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{blog}}',
			],
			'nav_menus' => [
				'primary_menu' => [
					'name'  => __( 'Primary Menu', 'blogsy' ),
					'items' => [
						'page_home',
						'page_blog',
						'page_about',
						'page_contact',
					],
				],
			],
		];
	}
}
