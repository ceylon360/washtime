<section id="booked-plugin-page">
	<div id="data-ajax-url"><?php echo get_admin_url(); ?></div>
	
	<?php
		
	if (!function_exists('cal_days_in_month')):
		echo '<div style="text-align:center; margin:30px 30px 0 3px; padding:30px 30px 12px 30px; border:2px solid #D54E21;"><p style="width:70%; font-size:20px; font-weight:bold; margin:0 auto 10px;">Whoops!</p><p style="width:70%; margin:0 auto 15px; font-size:16px;">Your server seems to have the <strong><a href="http://php.net/manual/en/function.cal-days-in-month.php" target="_blank">cal_days_in_month()</a></strong> function disabled, which is required by Booked to work. Please get in touch with your hosting provider to make sure this function is turned on.</p></div>';
		return false;
	endif;
			
	$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
								
	if (!empty($calendars)):
		
		?><div id="booked-calendarSwitcher"><p>
			<i class="fa fa-calendar"></i><?php
		
			echo '<select name="bookedCalendarDisplayed">';
			echo '<option value="">'.__('All Appointments','booked').'</option>';
		
			foreach($calendars as $calendar):
				
				?><option value="<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?></option><?php
			
			endforeach;
			
			echo '</select>';
			
		?></p></div><?php
		
	else :
	
		?><div class="noCalendarsSpacer"></div><?php
	
	endif;
	
	?>
		
	<div class="booked-admin-calendar-wrap">
		<?php booked_admin_calendar(); ?>
	</div>
</section>