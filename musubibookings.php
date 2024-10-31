<?php
/*
Plugin Name: Musubi Bookings Plugin
Description: Booking Forms Plugin for MusubiApp.com's registered services
Version: 0.1.0
*/
/* Start Adding Functions Below this Line */

// Register your assets during `wp_enqueue_scripts` hook.
function musubibookings_register_scripts() {
    wp_register_style('musubibookings-app-css', 'https://cdn.musubiapp.com/plugin/dist/app.css');
    wp_register_style('musubibookings-chunk-vendors-css', 'https://cdn.musubiapp.com/plugin/dist/chunk-vendors.css');
    wp_register_script('musubibookings-app-js', 'https://cdn.musubiapp.com/plugin/dist/app.js');
    wp_register_script('musubibookings-chunk-vendors-js', 'https://cdn.musubiapp.com/plugin/dist/chunk-vendors.js');
}
// Use wp_enqueue_scripts hook
add_action('wp_enqueue_scripts', 'musubibookings_register_scripts');

// Register and load the widget
function musubibookings_load_widget() {
	register_widget( 'musubibookings_widget' );
}
add_action( 'widgets_init', 'musubibookings_load_widget' );

// Creating the widget 
class musubibookings_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'musubibookings_widget',

			// Widget name will appear in UI
			__('Musubi Bookings Widget', 'musubibookings_widget_domain'),

			// Widget description
			array( 'description' => __( 'Bookings Widget by MusubiApp.com', 'musubibookings_widget_domain' ), )
		);
	}

	// Creating widget front-end
	 
	public function widget( $args, $instance ) {
		// Enqueue needed assets inside the `widget` function.
        wp_enqueue_style('musubibookings-app-css');
        wp_enqueue_style('musubibookings-chunk-vendors-css');
        wp_enqueue_script('musubibookings-app-js');
        wp_enqueue_script('musubibookings-chunk-vendors-js');

		$title = apply_filters( 'widget_title', $instance['title'] );
		$code = apply_filters( 'widget_code', $instance['code'] );

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];

		// Print title
		if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];

		// This is where you run the code and display the output

?>
	<script>
		var code = "<?php echo $code; ?>";
	</script>
<?php
		echo __( '<div id="musubipluginapp"></div>', 'musubibookings_widget_domain' );

		echo $args['after_widget'];
	}

	// Widget Backend

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'New title', 'musubibookings_widget_domain' );
		}

		if ( isset( $instance[ 'code' ] ) ) {
			$code = $instance[ 'code' ];
		} else {
			$code = __( 'Code', 'musubibookings_widget_domain' );
		}

	// Widget Admin Form
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		<label for="<?php echo $this->get_field_id( 'code' ); ?>"><?php _e( 'Code:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'code' ); ?>" name="<?php echo $this->get_field_name( 'code' ); ?>" type="text" value="<?php echo esc_attr( $code ); ?>" />
	</p>
<?php
	}

	// Updating widget replacing old instances with new

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['code'] = ( ! empty( $new_instance['code'] ) ) ? strip_tags( $new_instance['code'] ) : '';
		return $instance;
	}
}

// Class musubibookings_widget ends here

/* Musubi Bookings Plugin Shortcode */

function musubibookings_shortcodes_init() {
    function musubibookings_shortcode($atts) {
    	wp_enqueue_style('musubibookings-app-css');
        wp_enqueue_style('musubibookings-chunk-vendors-css');
        wp_enqueue_script('musubibookings-app-js');
        wp_enqueue_script('musubibookings-chunk-vendors-js');

		$atts = shortcode_atts( array(
			'code' => ''
		), $atts, 'musubibookings' );

		$return .= '<script>';
		if ($atts['code'] != '') $return .= 'var code = "' . $atts['code'] . '";';
		$return .= '</script><div id="musubipluginapp"></div>';

		return $return;
    }
    add_shortcode('musubibookings', 'musubibookings_shortcode');
}
add_action('init', 'musubibookings_shortcodes_init');

/* Stop Adding Functions Below this Line */
?>
