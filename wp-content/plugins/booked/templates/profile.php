<?php

// This template only shows up if you are logged in or if you have a username after the /profile/ in the url.

global $current_user,$custom_query,$custom_recipe_title,$custom_type,$error,$post;

get_currentuserinfo();
$profile_username = $current_user->user_login;
$my_id = $current_user->ID;
$my_profile = true;

$user_data = get_user_by( 'id', $current_user->ID );

?><div id="booked-profile-page"<?php if ($my_profile): ?> class="me"<?php endif; ?>><?php

if (empty($user_data)) {

	echo '<h2>' . __('No profile here!','booked') . '</h2>';
	echo '<p>' . __('Sorry, this user profile does not exist.','booked') . '</p>';

} else { ?>

	<div class="booked-profile-header bookedClearFix">

		<div class="booked-avatar">
			<?php echo booked_avatar($user_data->ID,150); ?>
		</div>
		
		<?php
			
			$user_meta = get_user_meta($user_data->ID);
			$user_url = $user_data->data->user_url;
			$user_desc = $user_meta['description'][0];
			$h3_class = '';
			
		?>
		
		<div class="booked-info">
			<div class="booked-user">
				<h3 class="<?php echo $h3_class; ?>"><?php echo get_user_meta( $user_data->ID, 'nickname', true ); ?></h3>
				<?php if ($user_url){ echo '<p><a href="'.$user_url.'" target="_blank">'.$user_url.'</a></p>'; } ?>
				<?php if ($user_desc){ echo wpautop($user_desc); } ?>
				<?php if ($my_profile): ?>
					<a class="booked-logout-button" href="<?php echo wp_logout_url(get_permalink($post->ID)); ?>" title="<?php _e('Logout','booked'); ?>"><?php _e('Logout','booked'); ?></a>
				<?php endif; ?>
			</div>
		</div>

	</div>
	
	<ul class="booked-tabs bookedClearFix">
		<li><a href="#appointments"><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php _e('Appointments','booked'); ?></a></li>
		<li class="edit-button"><a href="#edit"><i class="fa fa-edit"></i><?php _e('Edit Profile','booked'); ?></a></li>
	</ul>
	
	<?php $appointment_default_status = get_option('booked_new_appointment_default','draft');
		
	if ( is_user_logged_in() && $my_profile ) : ?>
		<div id="profile-appointments" class="booked-tab-content bookedClearFix">
			
			<div id="data-ajax-url"><?php echo home_url(); ?>/</div>
		
			<?php if (isset($_GET['appt_requested']) && isset($_GET['new_account'])){
				
				echo '<p class="booked-form-notice">'.__('Your appointment has been requested! We have also set up an account for you. Your login information has been sent via email. When logged in, you can view your upcoming appointments below. Be sure to change your password to something more memorable by using the Edit Profile tab above.','booked').'</p>';
				
			} else if (isset($_GET['appt_requested'])){
				
				if ($appointment_default_status == 'draft'):
					echo '<p class="booked-form-notice">'.__('Your appointment has been requested! It will be updated below if approved.','booked').'</p>';
				else :
					echo '<p class="booked-form-notice">'.__('Your appointment has been added to our calendar!','booked').'</p>';
				endif;
				
			} ?>
			
			<?php echo do_shortcode('[booked-appointments remove_wrapper=1]'); ?>
		
		</div>
		
		<div id="profile-edit" class="booked-tab-content bookedClearFix">
				
			<?php echo '<h4><i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;'.__('Edit Profile','booked').'</h4>'; ?>	
				
	        <form method="post" enctype="multipart/form-data" id="booked-page-form" action="<?php the_permalink(); ?>">
	        	
	        	<div class="bookedClearFix">
		            <p class="form-avatar">	
		                <label for="avatar"><?php _e('Update Avatar', 'booked'); ?></label><br>
		                <span class="booked-upload-wrap"><span><?php _e('Choose image ...','booked'); ?></span><input class="field" name="avatar" type="file" id="avatar" value="" /></span>
		                <?php wp_nonce_field( 'avatar_upload', 'avatar_nonce' ); ?>
		                <span class="hint-p"><?php _e('Recommended size: 100px by 100px or larger', 'booked'); ?></span>
		            </p><!-- .form-nickname -->
	        	</div>
	        	
	            <div class="bookedClearFix">
		            <p class="form-nickname">
		                <label for="nickname"><?php _e('Display Name', 'booked'); ?></label>
		                <input class="text-input" name="nickname" type="text" id="nickname" value="<?php the_author_meta( 'nickname', $current_user->ID ); ?>" />
		            </p><!-- .form-nickname -->
		            <p class="form-email">
		                <label for="email"><?php _e('E-mail *', 'booked'); ?></label>
		                <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
		            </p><!-- .form-email -->
		            <p class="form-url">
		                <label for="url"><?php _e('Website', 'booked'); ?></label>
		                <input class="text-input" name="url" type="text" id="url" value="<?php the_author_meta( 'user_url', $current_user->ID ); ?>" />
		            </p><!-- .form-url -->
	            </div>
	            <div class="bookedClearFix">
		            <p class="form-password">
		                <label for="pass1"><?php _e('Change Password', 'booked'); ?></label>
		                <input class="text-input" name="pass1" type="password" id="pass1" />
		            </p><!-- .form-password -->
		            <p class="form-password last">
		                <label for="pass2"><?php _e('Repeat Password', 'booked'); ?></label>
		                <input class="text-input" name="pass2" type="password" id="pass2" />
		            </p><!-- .form-password -->
	            </div>
	            <p class="form-textarea">
	                <label for="description"><?php _e('Short Bio', 'booked') ?></label>
	                <textarea name="description" id="description" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea>
	            </p><!-- .form-textarea -->
	
	            <?php 
	                //action hook for plugin and extra fields
	                do_action('edit_user_profile',$current_user); 
	            ?>
	            <p class="form-submit">
	                <input name="updateuser" type="submit" id="updateuser" class="submit button button-primary" value="<?php _e('Update', 'booked'); ?>" />
	                <?php wp_nonce_field( 'update-user' ) ?>
	                <input name="action" type="hidden" id="action" value="update-user" />
	            </p><!-- .form-submit -->
	        </form><!-- #adduser -->
	        
		</div>
		
	<?php endif; ?>	
	

<?php } ?>
	
</div>