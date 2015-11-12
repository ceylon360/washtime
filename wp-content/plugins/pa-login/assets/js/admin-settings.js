jQuery( document ).ready( function( $ ) {

	/**
	 * Apply Select2
	 */
	$( '.palo_select2' ).select2();

	/**	
	 * Attach a color picker to all input fields that have
	 * the class "palo-color-picker"
	 */
	$( 'input.palo-color-picker' ).wpColorPicker();
	
	/**	
	 * Attach media modal window 
	 */
	$( '#palo_background_image_button' ).click( function() {
		wp.media.editor.send.attachment = function( props, attachment ) {
			$( '#palo_background_image' ).val( attachment.url ).change();
		}
		wp.media.editor.open( $ );
		return false;
	} );

	/**
	 * Show/hide preview 
	 */
	$( '#palo_background_image' ).change( function (){

		var $this = $( this );
		var val = $this.val();
		var $preview = $this.parent().find( 'p' );
		var $img = $preview.find( 'img' );

		if( val ) {
			$img.attr( 'src', val );
			$preview.fadeIn( 'fast' );
		} else {
			$preview.fadeOut( 'fast' );
		}
	} );

	/**
	 * Empty image url field if "Remove Image" is clicked
	 */
	$( '.palo-remove-image' ).click( function( e ) {
		
		var $this = $( this );
		var $img = $( this ).siblings( 'img' );
		var src = $img.attr( 'src' );
		var $input = $this.parent().parent().find( 'input' );

		if ( src ) {
			e.preventDefault();
			$input.val( '' ).change();
		}
	});

	/**
	 * Animate checkboxes
	 *
	 * Change checboxes colors when necessary and mark the selected
	 * choice when necessary
	 */
	$( 'label.button :checkbox').bind( 'click', function( e ) {

		var $this = $( this );
		var name = $this.attr( 'name' );

		// Uncolorize unckecked
		$( ':checkbox[name="' + name + '"]:not(checked)')
			.closest( 'label' )
			.removeClass('active button-primary');

		// Colorize unchecked
		$( ':checkbox[name="' + name + '"]' )
			.filter(':checked')
			.closest( 'label' )
			.addClass('active button-primary');
	} );

	/**
	 * Trigger checkboxes colorization on load
	 */
	$( 'label.button :checkbox[name*=palo]').click().click();


	/**
	 * Animate radio button selection
	 *
	 * Change radio buttons colors when necessary and mark the selected
	 * choice when necessary
	 */
	$( 'label.button :radio').bind( 'click', function( e ) {

		var $this = $( this );
		var name = $this.attr( 'name' );

		// Uncolorize all
		$( ':radio[name="' + name + '"]' )
			.closest( 'label' )
			.removeClass('active button-primary');

		// Colorize checked
		$this.closest( 'label' ).addClass( 'active button-primary' );

		// Blur
		$( 'body' ).focus();
	} );

	// Colorize any active radio
	$( ':radio:checked' )
		.closest( 'label.button' )
		.toggleClass( 'active button-primary' );

	// Enable Login  URL field if applicable
	$( '[name*=login_behavior]' ).bind( 'change', function () {

		var checked = ( $( '[name*=login_behavior][value$=_URL]:checked').length );
		var $dependee = $( '[name="palo_options[palo_login_url]"]' );
		$dependee.prop( 'readonly', ! checked );
	} ).change();

	// Enable Logout  URL field if applicable
	$( '[name*=logout_behavior]' ).bind( 'change', function () {

		var checked = ( $( '[name*=logout_behavior][value$=_URL]:checked').length );
		var $dependee = $( '[name="palo_options[palo_logout_url]"]' );
		$dependee.prop( 'readonly', ! checked );
	} ).change();

	// Enable Access URL field if applicable
	$( '[name*=access_behavior]' ).bind( 'change', function () {

		var checked = ( $( '[name*=access_behavior][value$=_URL]:checked').length );
		var $dependee = $( '[name="palo_options[palo_access_url]"]' );
		$dependee.prop( 'readonly', ! checked );
	} ).change();
} );
