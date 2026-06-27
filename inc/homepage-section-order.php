<?php
/**
 * Homepage Section Order — admin UI and helpers.
 *
 * @package bcmarket
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option name for stored homepage section order.
 */
define( 'BCMARKET_HOMEPAGE_SECTION_ORDER_OPTION', 'homepage_section_order' );

/**
 * Nonce action for saving homepage section order.
 */
define( 'BCMARKET_HOMEPAGE_SECTION_ORDER_NONCE', 'bcmarket_homepage_section_order' );

/**
 * Get all homepage section definitions keyed by term ID.
 *
 * @return array<string, array<string, mixed>>
 */
function bcmarket_get_homepage_section_definitions() {
	$sections = array();

	$parent_terms = get_terms(
		array(
			'taxonomy'   => 'item_cat',
			'hide_empty' => false,
			'parent'     => 0,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $parent_terms ) || empty( $parent_terms ) ) {
		return $sections;
	}

	foreach ( $parent_terms as $parent ) {
		$child_terms = get_terms(
			array(
				'taxonomy'   => 'item_cat',
				'hide_empty' => false,
				'parent'     => $parent->term_id,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ) {
			foreach ( $child_terms as $child ) {
				$key = (string) $child->term_id;

				$sections[ $key ] = array(
					'id'     => (int) $child->term_id,
					'label'  => trim( $parent->name . ' ' . $child->name ),
					'parent' => $parent,
					'child'  => $child,
					'type'   => 'child',
				);
			}
		} else {
			$key = (string) $parent->term_id;

			$sections[ $key ] = array(
				'id'     => (int) $parent->term_id,
				'label'  => $parent->name,
				'parent' => $parent,
				'child'  => null,
				'type'   => 'parent',
			);
		}
	}

	return $sections;
}

/**
 * Merge saved order with current sections; append new sections at the bottom.
 *
 * @return array<string, array<string, mixed>>
 */
function bcmarket_get_homepage_section_order() {
	$definitions = bcmarket_get_homepage_section_definitions();
	$saved       = get_option( BCMARKET_HOMEPAGE_SECTION_ORDER_OPTION, array() );

	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$ordered = array();

	foreach ( $saved as $section_id ) {
		$section_id = (string) absint( $section_id );

		if ( isset( $definitions[ $section_id ] ) ) {
			$ordered[ $section_id ] = $definitions[ $section_id ];
			unset( $definitions[ $section_id ] );
		}
	}

	foreach ( $definitions as $section_id => $section ) {
		$ordered[ $section_id ] = $section;
	}

	return $ordered;
}

/**
 * Register Dashboard submenu page.
 */
function bcmarket_register_homepage_section_order_menu() {
	add_submenu_page(
		'index.php',
		__( 'Homepage Section Order', 'bcmarket' ),
		__( 'Homepage Section Order', 'bcmarket' ),
		'manage_options',
		'homepage-section-order',
		'bcmarket_homepage_section_order_page'
	);
}
add_action( 'admin_menu', 'bcmarket_register_homepage_section_order_menu' );

/**
 * Enqueue admin assets for the sortable list page.
 *
 * @param string $hook_suffix Current admin page hook suffix.
 */
function bcmarket_homepage_section_order_admin_assets( $hook_suffix ) {
	if ( 'dashboard_page_homepage-section-order' !== $hook_suffix ) {
		return;
	}

	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_script(
		'bcmarket-homepage-section-order',
		get_template_directory_uri() . '/js/homepage-section-order-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		filemtime( get_template_directory() . '/js/homepage-section-order-admin.js' ),
		true
	);

	wp_localize_script(
		'bcmarket-homepage-section-order',
		'bcmarketHomepageSectionOrder',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( BCMARKET_HOMEPAGE_SECTION_ORDER_NONCE ),
			'i18n'    => array(
				'saving'  => __( 'Saving…', 'bcmarket' ),
				'saved'   => __( 'Order saved successfully.', 'bcmarket' ),
				'error'   => __( 'Could not save order. Please try again.', 'bcmarket' ),
				'empty'   => __( 'No homepage sections found.', 'bcmarket' ),
			),
		)
	);

	wp_add_inline_style(
		'wp-admin',
		'
		#homepage-section-order-list { max-width: 640px; margin-top: 16px; }
		#homepage-section-order-list .homepage-section-item {
			display: flex;
			align-items: center;
			gap: 12px;
			padding: 12px 14px;
			margin: 0 0 8px;
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 4px;
			cursor: move;
		}
		#homepage-section-order-list .homepage-section-handle {
			color: #787c82;
			font-size: 18px;
			line-height: 1;
		}
		#homepage-section-order-order-status { margin-left: 8px; font-style: italic; }
		.homepage-section-placeholder {
			height: 46px;
			margin-bottom: 8px;
			border: 1px dashed #2271b1;
			background: #f0f6fc;
		}
		'
	);
}
add_action( 'admin_enqueue_scripts', 'bcmarket_homepage_section_order_admin_assets' );

/**
 * Render the Homepage Section Order admin page.
 */
function bcmarket_homepage_section_order_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'bcmarket' ) );
	}

	$sections = bcmarket_get_homepage_section_order();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Homepage Section Order', 'bcmarket' ); ?></h1>
		<p><?php esc_html_e( 'Drag and drop sections to change the order they appear on the homepage.', 'bcmarket' ); ?></p>

		<?php if ( empty( $sections ) ) : ?>
			<div class="notice notice-warning">
				<p><?php esc_html_e( 'No homepage category sections were found.', 'bcmarket' ); ?></p>
			</div>
		<?php else : ?>
			<ul id="homepage-section-order-list">
				<?php foreach ( $sections as $section ) : ?>
					<li class="homepage-section-item" data-section-id="<?php echo esc_attr( $section['id'] ); ?>">
						<span class="homepage-section-handle dashicons dashicons-menu" aria-hidden="true"></span>
						<span class="homepage-section-label"><?php echo esc_html( $section['label'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>

			<p>
				<button type="button" class="button button-primary" id="save-homepage-section-order">
					<?php esc_html_e( 'Save Order', 'bcmarket' ); ?>
				</button>
				<span id="homepage-section-order-status" aria-live="polite"></span>
			</p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * AJAX handler: save homepage section order.
 */
function bcmarket_save_homepage_section_order_ajax() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error(
			array( 'message' => __( 'You do not have permission to perform this action.', 'bcmarket' ) ),
			403
		);
	}

	check_ajax_referer( BCMARKET_HOMEPAGE_SECTION_ORDER_NONCE, 'nonce' );

	$order = isset( $_POST['order'] ) ? wp_unslash( $_POST['order'] ) : array();

	if ( ! is_array( $order ) ) {
		wp_send_json_error(
			array( 'message' => __( 'Invalid order data received.', 'bcmarket' ) ),
			400
		);
	}

	$definitions   = bcmarket_get_homepage_section_definitions();
	$sanitized     = array();
	$seen          = array();

	foreach ( $order as $section_id ) {
		$section_id = (string) absint( $section_id );

		if ( '' === $section_id || '0' === $section_id ) {
			continue;
		}

		if ( ! isset( $definitions[ $section_id ] ) || isset( $seen[ $section_id ] ) ) {
			continue;
		}

		$sanitized[]           = (int) $section_id;
		$seen[ $section_id ] = true;
	}

	// Append any sections missing from the submitted list.
	foreach ( array_keys( $definitions ) as $section_id ) {
		if ( ! isset( $seen[ $section_id ] ) ) {
			$sanitized[] = (int) $section_id;
		}
	}

	update_option( BCMARKET_HOMEPAGE_SECTION_ORDER_OPTION, $sanitized );

	wp_send_json_success(
		array(
			'message' => __( 'Order saved successfully.', 'bcmarket' ),
			'order'   => $sanitized,
		)
	);
}
add_action( 'wp_ajax_bcmarket_save_homepage_section_order', 'bcmarket_save_homepage_section_order_ajax' );
