<?php

class WWTW_Suggestions_Widget extends WP_Widget {
	public function __construct()
	{
		parent::__construct(
			'wwtw_suggestion_widget',
			__('Suggestions', 'wwtw'),
			array('description' => __('Lighweight widget for handling user submissions', 'wwtw'))
		);
	}

	public function widget( $args, $instance )
	{
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

			printf('%s %s %s',
				$args['before_title'],
				$title,
				$args['after_title']
			);

			echo apply_filters( 'the_content', $instance['intro'] );

			?>
			<form action="" method="post" accept-charset="utf-8" class="wwtw-suggestion--form">
				<p>
					<label for="wwtw_suggestion">Suggestion</label>
					<textarea name="wwtw_suggestion" id="wwtw_suggestion" cols="30" rows="10"></textarea>
				</p>

				<p>
					<label for="wwtw_email">Email (optional)</label>
					<input name="wwtw_email" id="wwtw_email" type="text"/>
				</p>


				<?php wp_nonce_field( -1,'wwtw_nonce'); ?>

				<input type="submit" value="Submit"/>
			</form>
			<div class="wwtw-suggestion--response" class="js--only">
				
			</div>
			<?php

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __('Suggestions', 'wwtw');
		$intro = isset( $instance['intro'] ) ? $instance['intro'] : __('', 'wwtw');

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'wwtw') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('intro'); ?>"><?php echo __('Intro', 'wwtw') ?></label>
			<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'intro' ); ?>" name="<?php echo $this->get_field_name( 'intro' ); ?>"><?php echo $intro; ?></textarea>
		</p>
		<?php
	}
}

add_action('widgets_init', function() {
	register_widget('WWTW_Suggestions_Widget');
});
