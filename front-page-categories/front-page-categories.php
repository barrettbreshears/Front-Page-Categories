<?php
/**
 * Plugin Name: Front Page Categories
 * Plugin URI: http://sledgedev.com
 * Description: Lists most recent posts by category
 * Version: 1.0 there can be only 1
 * Author: Dr. Barrett Andreas Breshears
 * Author URI: http://sledgedev.com
 * License: A "Slug" license name e.g. GPL2
 */


class Front_Page_Categories extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'front_page_categories', // Base ID
			__('Front Page Categories'), // Name
			array( 'description' => __( 'Display most recent posts based on category' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$num_posts = $instance['num_posts'];
		$categories = explode(',', $instance['categories']);

		echo $args['before_widget'];

		if ( ! empty( $title ) )
			echo "<h2>" . $title . "</h2>";

		foreach ($categories as $category) {
			$my_query = new WP_Query('category_name='. $category .'&posts_per_page='. $num_posts);

			while ($my_query->have_posts()) : $my_query->the_post();
		 		
		 		// print_r($my_query);
				?>
				<div class="alignleft">

					<?php echo get_the_post_thumbnail(get_the_id(), 'thumbnail', array('class' => 'alignleft')); ?>
            		<h4  style="clear:right"><a class="latest_post_title" href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h4>
            		<?php echo the_excerpt(); ?>
            		
            	
            	</div>

            	<?php
            	//echo $my_query->post_content;

		  	endwhile;

		}

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}

		if ( isset( $instance[ 'categories' ] ) ) {
			$categories = $instance[ 'categories' ];
		}
		else {
			$categories = __( 'Categories' );
		}

		if ( isset( $instance[ 'num_posts' ] ) ) {
			$num_posts = $instance[ 'num_posts' ];
		}
		else {
			$num_posts = __( '5' );
		}

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'categories' ); ?>"><?php _e( 'Categories: (seperate by comma)' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories' ); ?>" type="text" value="<?php echo esc_attr( $categories ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e( 'Number of posts:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" type="text" value="<?php echo esc_attr( $num_posts ); ?>" />
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['categories'] = ( ! empty( $new_instance['categories'] ) ) ? strip_tags( $new_instance['categories'] ) : '';
		$instance['num_posts'] = ( ! empty( $new_instance['num_posts'] ) ) ? strip_tags( $new_instance['num_posts'] ) : '';

		return $instance;
	}

} // class Foo_Widget

// register Foo_Widget widget
function register_front_page_categories() {
    register_widget( 'Front_Page_Categories' );
}
add_action( 'widgets_init', 'register_front_page_categories' );

