<?php

add_action( 'template_redirect', 'bp_fe_ajax_loaders', 10 );
function bp_fe_ajax_loaders() {
	
	/*
	Load a calendar month
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'calendar_month' && isset($_POST['gotoMonth']))
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		$timestamp = ($_POST['gotoMonth'] != 'false' ? strtotime($_POST['gotoMonth']) : current_time('timestamp'));
		
		$year = date('Y',$timestamp);
		$month = date('m',$timestamp);
		
		booked_fe_calendar($year,$month,$calendar_id);
		
		exit;
		
	}
	
	/*
	Load a calendar date
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'calendar_date' && isset($_POST['date']))
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_fe_calendar_date_content($_POST['date'],$calendar_id);
		exit;
		
	}
	
	
	/*
	Refresh a calendar date square
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'refresh_date_square' && isset($_POST['date']))
	{
		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_fe_calendar_date_square($_POST['date'],$calendar_id);
		exit;
	}
	
	
	/*
	Load the New Appointment form
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'new_appointment_form' && isset($_POST['date']) && isset($_POST['timeslot']))
	{

		$date = $_POST['date'];
		$timeslot = $_POST['timeslot'];
		$timeslot_parts = explode('-',$timeslot);
		
		$date_format = get_option('date_format');
		$time_format = get_option('time_format');
		
		$args = array('orderby' => 'display_name');
		$user_array = get_users($args);
		
		$calendar_id = (isset($_POST['calendar_id']) ? intval($_POST['calendar_id']) : false);
		
		if ($timeslot_parts[0] == '0000' && $timeslot_parts[1] == '2400'):
			$timeslotText = 'All day';
		else :
			$timeslotText = date_i18n($time_format,strtotime($timeslot_parts[0])) . (!get_option('booked_hide_end_times') ? ' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1])) : '');
		endif;
		
		$appt_date_time = '<p class="name"><b><i class="fa fa-calendar-o"></i>&nbsp;&nbsp;' . date_i18n($date_format, strtotime($date)) . '&nbsp;&nbsp;&nbsp;&nbsp;</b><b><i class="fa fa-clock-o"></i>&nbsp;&nbsp;' . $timeslotText . '</b></p>';
	
		?><div class="booked-form">
	
			<p><small><?php _e('Request Appointment','booked'); ?></small></p>		
	
			<?php $reached_limit = false;
				
			if (is_user_logged_in()):
			
				?><form action="" method="post" id="newAppointmentForm" data-calendar-id="<?php echo $calendar_id ? $calendar_id : 0; ?>">
				
					<input type="hidden" name="date" value="<?php echo date('Y-m-j', strtotime($date)); ?>" />
					<input type="hidden" name="timestamp" value="<?php echo strtotime($date.' '.$timeslot_parts[0]); ?>" />
					<input type="hidden" name="timeslot" value="<?php echo $timeslot; ?>" />
					<input type="hidden" name="customer_type" value="<?php if (is_user_logged_in()): echo 'current'; else : echo 'new'; endif; ?>" />
					<input type="hidden" name="action" value="add_appt" />
					<input type="hidden" name="calendar_id" value="<?php echo $calendar_id ? $calendar_id : 0; ?>" /><?php
					
					global $current_user;
					get_currentuserinfo();
					
					$appointment_limit = get_option('booked_appointment_limit');
					if ($appointment_limit):
						$upcoming_user_appointments = booked_user_appointments($current_user->ID,true);
						if ($upcoming_user_appointments >= $appointment_limit):
							$reached_limit = true;
						else :
							$reached_limit = false;
						endif;
					endif;
					
					if (!$reached_limit):
					
						?><p><?php echo sprintf( __( 'You are about to request an appointment for %s.','booked' ), get_user_meta( $current_user->ID, 'nickname', true )); ?> <?php _e('Please confirm that you would like to request the following appointment:','booked'); ?></p>
						<?php echo $appt_date_time; ?>
					
						<input type="hidden" name="user_id" value="<?php echo $current_user->ID; ?>" />
						
						<div class="spacer"></div>
					
						<?php booked_custom_fields(); ?>
					
						<div class="field">
							<?php if (!$reached_limit): ?>
								<input type="submit" id="submit-request-appointment" class="button button-primary" value="<?php _e('Request Appointment','booked'); ?>">
								<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
							<?php else: ?>
								<button class="cancel button"><?php _e('Okay','booked'); ?></button>
							<?php endif; ?>
						</div>
					
					<?php else : ?>
					
						<p><?php echo sprintf(_n("Sorry, but you've hit the appointment limit. Each user may only book %d appointment at a time.","Sorry, but you've hit the appointment limit. Each user may only book %d appointments at a time.", $appointment_limit, "booked" ), $appointment_limit); ?></p>
					
					<?php endif; ?>
				
				</form>
				
			<?php else : ?>
			
				<?php echo $appt_date_time; ?>
			
				<form name="customerChoices" action="" id="customerChoices">
					
					<div class="field">
						<span class="checkbox-radio-block">
							<input data-condition="customer_choice" type="radio" name="customer_choice[]" id="customer_new" value="new" checked="checked">
							<label for="customer_new"><?php _e('I am a new customer','booked'); ?></label>
						</span>
					</div>
					
					<div class="field">
						<span class="checkbox-radio-block">
							<input data-condition="customer_choice" type="radio" name="customer_choice[]" id="customer_current" value="current">
							<label for="customer_current"><?php _e('I am a current customer','booked'); ?></label>
						</span>
					</div>
					
				</form>
				
				<hr>
				
				<div class="condition-block customer_choice" id="condition-current">
					
					<form id="ajaxlogin" action="" method="post">
					
						<div class="cf-block">
							<div class="field">
								
								<div class="field">
									<label class="field-label"><?php _e('Username', 'booked') ?></label>
									<input value="<?php _e('Username...','booked'); ?>" title="<?php _e('Username...','booked'); ?>" class="large textfield" id="username" name="username" type="text" >
								</div>
								
								<div class="field">
									<label class="field-label"><?php _e('Password', 'booked') ?></label>
									<input value="" class="large textfield" id="password" name="password" type="password" >
								</div>
						
								<input type="hidden" name="action" value="ajax_login">
								<?php wp_nonce_field( 'ajax_login_nonce', 'security' ); ?>
								
								<div class="field">
									<p class="status"></p>
								</div>
								
							</div>
						</div>
						
						<div class="field">
							<input name="submit" type="submit" class="button button-primary" value="<?php _e('Sign in', 'booked') ?>">
							<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
						</div>

					</form>
						
				</div>
				
				<form action="" method="post" id="newAppointmentForm" data-calendar-id="<?php echo $calendar_id ? $calendar_id : 0; ?>">
				
					<input type="hidden" name="date" value="<?php echo date('Y-m-j', strtotime($date)); ?>" />
					<input type="hidden" name="timestamp" value="<?php echo strtotime($date.' '.$timeslot_parts[0]); ?>" />
					<input type="hidden" name="timeslot" value="<?php echo $timeslot; ?>" />
					<input type="hidden" name="customer_type" value="<?php if (is_user_logged_in()): echo 'current'; else : echo 'new'; endif; ?>" />
					<input type="hidden" name="action" value="add_appt" />
					<input type="hidden" name="calendar_id" value="<?php echo $calendar_id ? $calendar_id : 0; ?>" />
					
					<div class="condition-block customer_choice default" id="condition-new">
						<div class="field">
							<input value="<?php _e('First name...','booked'); ?>" title="<?php _e('First name...','booked'); ?>" type="text" class="textfield" name="first_name" />
							<input value="<?php _e('Last name...','booked'); ?>" title="<?php _e('Last name...','booked'); ?>" type="text" class="textfield" name="last_name" />
						</div>
						<div class="field">
							<input value="<?php _e('Email...','booked'); ?>" title="<?php _e('Email...','booked'); ?>" type="email" class="large textfield" name="email" />
						</div>
						
						<div class="spacer"></div>
					
						<?php booked_custom_fields(); ?>
						
						<?php if (class_exists('ReallySimpleCaptcha')) :
			
							?><p class="captcha">
								<label for="captcha_code"><?php _e('Please enter the following text:','booked'); ?></label><?php
							
								$rsc_url = WP_PLUGIN_URL . '/really-simple-captcha/';
								
						        $captcha = new ReallySimpleCaptcha();
						        $captcha->bg = array(245,245,245);
						        $captcha->fg = array(150,150,150);
					            $captcha_word = $captcha->generate_random_word(); //generate a random string with letters
					            $captcha_prefix = mt_rand(); //random number
					            $captcha_image = $captcha->generate_image($captcha_prefix, $captcha_word); //generate the image file. it returns the file name
					            $captcha_file = rtrim(get_bloginfo('wpurl'), '/') . '/wp-content/plugins/really-simple-captcha/tmp/' . $captcha_image; //construct the absolute URL of the captcha image
						        
						        echo '<img class="captcha-image" src="'.$rsc_url.'tmp/'.$captcha_image.'">';
						        
						    ?></p>
						    							   
							<div class="field"> 
								<input type="text" name="captcha_code" class="textfield large" value="" tabindex="104" />
								<input type="hidden" name="captcha_word" value="<?php echo $captcha_word; ?>" />
							</div>
							
							<br><?php
								
						endif; ?>
					
						<div class="field">
							<?php if (!$reached_limit): ?>
								<input type="submit" id="submit-request-appointment" class="button button-primary" value="<?php _e('Request Appointment','booked'); ?>">
								<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
							<?php else: ?>
								<button class="cancel button"><?php _e('Okay','booked'); ?></button>
							<?php endif; ?>
						</div>
					
					</div>
				
				</form>
			
			<?php endif; ?>
		
		</div>
		
		<?php echo '<a href="#" class="close"><i class="fa fa-remove"></i></a>';
		exit;
		
	}

	
	
	
}