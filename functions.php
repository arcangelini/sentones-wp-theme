<?php
/**
 * SentOnes Theme functions and definitions
 *
 * @package SentOnes
 * @since 1.0.0
 */

if ( ! function_exists( 'sent_ones_wp_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @return void
	 */
	function sent_ones_wp_setup() {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for font appearance tools.
		add_theme_support( 'appearance-tools' );

		// Add support for custom units.
		add_theme_support( 'custom-units' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );
	}
}
add_action( 'after_setup_theme', 'sent_ones_wp_setup' );

/**
 * Enqueue theme stylesheets.
 *
 * @return void
 */
function sent_ones_wp_styles() {
		// Register theme stylesheet.
		wp_register_style(
			'sent-ones-wp-style',
			get_stylesheet_directory_uri() . '/style.css',
			array(),
			wp_get_theme()->get( 'Version' )
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'sent-ones-wp-style' );

		// Add inline styles for fonts to ensure they load on the front end.
		$font_css = '
		@font-face {
			font-family: "Montserrat";
			font-style: normal;
			font-weight: 400;
			src: url(' . get_theme_file_uri( 'assets/fonts/montserrat/montserrat.woff2' ) . ') format("woff2");
			font-display: swap;
		}
		@font-face {
			font-family: "Covered by Your Grace";
			font-style: normal;
			font-weight: 400;
			src: url(' . get_theme_file_uri( 'assets/fonts/covered-by-your-grace/covered-by-your-grace.woff2' ) . ') format("woff2");
			font-display: swap;
		}
		';

		wp_add_inline_style( 'sent-ones-wp-style', $font_css );
}
add_action( 'wp_enqueue_scripts', 'sent_ones_wp_styles' );

/**
 * Register custom blocks
 */
function sent_ones_wp_register_blocks() {
	$blocks_dir = get_template_directory() . '/build';

	if ( ! file_exists( $blocks_dir ) ) {
		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
	 * based on the registered block metadata.
	 */
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( $blocks_dir, $blocks_dir . '/blocks-manifest.php' );
		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` file.
	 */
	if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		wp_register_block_metadata_collection( $blocks_dir, $blocks_dir . '/blocks-manifest.php' );
	}

	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 */
	$manifest_file = $blocks_dir . '/blocks-manifest.php';
	if ( file_exists( $manifest_file ) ) {
		$manifest_data = require $manifest_file;
		foreach ( array_keys( $manifest_data ) as $block_type ) {
			register_block_type( $blocks_dir . "/{$block_type}" );
		}
	}
}
add_action( 'init', 'sent_ones_wp_register_blocks' );

/**
 * Add Podcasting post type
 */
require_once get_template_directory() . '/podcasts.php';

/**
 * Register Categories Section block with server-side rendering
 */
function sent_ones_wp_register_categories_section_block() {
	if ( function_exists( 'register_block_type' ) ) {
		register_block_type(
			get_template_directory() . '/blocks/categories-section/build',
			array(
				'render_callback' => 'sent_ones_wp_render_categories_section_block',
			)
		);
	}
}
add_action( 'init', 'sent_ones_wp_register_categories_section_block' );

/**
 * Render callback for Categories Section block
 *
 * @param array $attributes Block attributes.
 * @return string Block HTML output.
 */
function sent_ones_wp_render_categories_section_block( $attributes ) {
	$selected_category = isset( $attributes['selectedCategory'] ) ? absint( $attributes['selectedCategory'] ) : 0;

	ob_start();
	?>
	<div class="wp-block-create-block-categories-section categories-section">
		<div class="category-dropdown">
			<label for="category-select"><?php esc_html_e( 'Select Category:', 'sent-ones-wp' ); ?></label>
			<select id="category-select" name="category-select" onchange="window.location.href='?' + new URLSearchParams(Object.assign(Object.fromEntries(new URLSearchParams(window.location.search)), {category: this.value})).toString()">
				<option value=""><?php esc_html_e( 'All Categories', 'sent-ones-wp' ); ?></option>
				<?php
				$categories = get_categories( array( 'hide_empty' => true ) );
				foreach ( $categories as $category ) {
					$selected = ( $selected_category === $category->term_id ) ? 'selected' : '';
					printf(
						'<option value="%d" %s>%s</option>',
						esc_attr( $category->term_id ),
						esc_attr( $selected ),
						esc_html( $category->name )
					);
				}
				?>
			</select>
		</div>

		<div class="posts-grid">
			<?php
			$args = array(
				'posts_per_page' => 3,
				'post_status'    => 'publish',
			);

			if ( $selected_category ) {
				$args['cat'] = $selected_category;
			}

			$posts = get_posts( $args );

			if ( $posts ) :
				?>
				<div class="posts-row">
					<?php foreach ( $posts as $post ) : ?>
						<div class="post-item">
							<?php if ( has_post_thumbnail( $post->ID ) ) : ?>
								<div class="post-image">
									<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
										<?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?>
									</a>
								</div>
							<?php endif; ?>
							<h3 class="post-title">
								<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
									<?php echo esc_html( get_the_title( $post->ID ) ); ?>
								</a>
							</h3>
							<div class="post-date">
								<?php echo esc_html( get_the_date( '', $post->ID ) ); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php
			else :
				?>
				<div class="no-posts">
					<?php
					if ( $selected_category ) {
						esc_html_e( 'No posts found in this category.', 'sent-ones-wp' );
					} else {
						esc_html_e( 'No posts found.', 'sent-ones-wp' );
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
