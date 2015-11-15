=== Plugin Name ===
Plugin Name: Booked
Plugin URI: http://demo.boxystudio.com/booked/
Tags: bookings, appointments, calendar
Author URI: http://boxystudio.com
Author: Boxy Studio
Donate link: http://www.boxystudio.com/#coffee
Requires at least: 4.0
Tested up to: 4.1.1

== Changelog ==

= 1.4.8 =
* *NEW:* This update supports the new **Booked Add-Ons** functionality. Take a look at *Appointments > Add-Ons* after updating!

= 1.4.7 =
* *FIX:* Character encoding issues fixed for emails.
* *FIX:* Fixed some timezone-related issues.
* *FIX:* Numerous other bug fixes throughout.

= 1.4.6 =
* *FIX:* Character encoding issues fixes.

= 1.4.5 =
* *FIX:* Fixed a few new translation issues
* *FIX:* Fixed a conflict with some translated day names
* *FIX:* Now shows first and last name in emails (when available)
* *FIX:* More character encoding issues fixed

= 1.4.4 =
* *FIX:* Fixed a header styling issue on the Profile template.
* *FIX:* Fixed some translation issues
* *FIX:* REALLY fixed the issue where accents would not show up properly. (UTF-8 encoding issue)

= 1.4.3 =
* *FIX:* Fixes an AJAX loading issue from v1.4.2 on some WordPress installs.

= 1.4.2 =
* *FIX:* Fixed a conflict with the Cooked plugin.
* *FIX:* Fix the front-end calendar styling issues on mobile (weird borders).
* *FIX:* Fixed an issue where accents would not show up properly in emails. (UTF-8 encoding issue)
* *FIX:* Some other quick bug fixes.

= 1.4.1 =
* *FIX:* Fixed the styling issues with sites using different languages.
* *FIX:* Some other quick bug fixes.

= 1.4 =
* **NEW:** Custom time slots! Refer to the [documentation](http://docs.boxystudio.com/plugins/booked/custom-time-slots/) for more information.
* **NEW:** Added the ability to assign calendars to a user (so they get the emails).
* **NEW:** Added an "Upcoming Appointments" WordPress admin dashboard widget.
* **NEW:** Added "All Day" time slots.
* **NEW:** Added an option to ONLY show start times, not end times.
* **NEW:** Visually show stale appointments in pending list.
* **NEW:** Added an option to the calendar shortcode to set the start month/year.
* **NEW:** Added an option to the calendar shortcode to display a switcher dropdown to switch between calendars.
* **NEW:** Profile is now tabbed and more user friendly.
* **NEW:** Added the "Calendar Name" to user's appointments list.
* **NEW:** Added an option to choose a page for booking redirection (instead of the Profile).
* **NEW:** Added an option to choose a page for login redirection (instead of a refresh).
* **NEW:** Added option to hide Google Calendar link.
* **NEW:** Added sign in option to the booking form if logged out.
* **NEW:** Added captcha (optional) to registration/booking form.
* *FIX:* Applied wp_reset_postdata() after shortcodes.
* *FIX:* Loaded Google fonts with // instead of http:// to support SSL sites.
* *FIX:* Show Username if no first/last name exists.
* *FIX:* Fixed some stylistic issues
* *FIX:* Fixed an issue where the "Cancel" button didn't show up.
* *FIX:* Fixed javascript conflict issues with some setups.

= 1.3.6 =
* *FIX:* Some mobile stylistic fixes.

= 1.3.5 =
* *FIX:* Fixed some stylistic issues with certain themes.
* *FIX:* Fixed an issue where the "Cancel" button might not show up with appointments.
* *FIX:* Fixed an issue where the calendar wasn't loading correctly on some sites.
* *FIX:* Fixed an issue where the uploaded email logo would disappear from Settings panel.

= 1.3 =
* **NEW:** Multiple Calendars!
* **NEW:** Default time slots for each custom calendar (optional)
* **NEW:** Shortcode tab for easy access to the Booked shortcode list.

= 1.2.2 =
* *FIX:* Fixed a bunch of timezone issues.

= 1.2.1 =
* *FIX:* Fixed a bug where in rare cases a booked appointment would show on the wrong day in the admin.

= 1.2 =
* **NEW:** Custom fields are here! Just go to "Appointments > Settings > Custom Fields" to set them up.
* **NEW:** A new email template that you can customize with your logo (or heading image).
* *FIX:* Fixed an "invalid username" issue.
* *FIX:* Time slots can now be entered up to 12:00am the next day.

= 1.1.2 =
* *FIX:* Fixed some translation issues
* *FIX:* Fixed a PHP notice error.
* Bigger updates coming soon!

= 1.1.1 =
* *FIX:* Fixed an issue when saving the default timeslot intervals.

= 1.1 =
* **NEW:** A "Cancellation Buffer" setting so that the customer cannot cancel when it gets too close to the appointment date/time.
* **NEW:** An "Appointment Limit" setting so the customer cannot book more than X upcoming appointments.
* **NEW:** A [booked-appointments] shortcode to list the currently logged-in user's appointments anywhere on your site.
* **NEW:** Added more interval options for default time slots (45 minutes, 1:30, etc.).
* **NEW:** Added a "time between" option (5 minutes, 10 minutes, etc.) for default appointment time slots.
* **NEW:** Added an option to automatically approve appointments as they come in.
* *FIX:* The pending appointments dialog is now hidden if you're not logged in as an admin.

= 1.0.1 =
* **NEW:** Added an appointment booking buffer to prevent people from booking appointments to close to current date and/or time.
* **NEW:** Added "Google Calendar" buttons to appointment list on profile page.
* **NEW:** Added some color pickers to the Settings panel to change the front-end calendar colors.
* *FIX:* Some quick and minor bug fixes.

= 1.0.0 =
* Initial Release!