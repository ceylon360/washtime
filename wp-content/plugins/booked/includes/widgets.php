<?php

/* SINGLE RECIPE WIDGET */

add_action('widgets_init', create_function('', 'return register_widget("booked_calendar");'));

class booked_calendar extends WP_Widget {

    function booked_calendar() {
        parent::WP_Widget(false, $name = __('Appointment Calendar','booked'));
    }
    
    function form($instance) {	
	
	    $title = (isset($instance['title']) ? esc_attr($instance['title']) : '');
	
	    ?>
	
		<p>
	      	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title','booked'); ?>:</label>
	      	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
	    
	    <?php
	}

    function widget($args, $instance) {
        
        extract( $args );

		// these are our widget options
	    $title = apply_filters('widget_title', $instance['title']);
	
	    echo $before_widget;
	
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		echo do_shortcode('[booked-calendar size="small"]');
	    
	    echo $after_widget;
	
	}
	
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
    }

}