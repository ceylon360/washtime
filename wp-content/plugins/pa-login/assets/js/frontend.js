jQuery( document ).ready( function( $ ){
	var wrap, next;

	$( '.palo-modal-login-trigger' ).bind( 'click', function( e ) {
		e.preventDefault();
		show( $(e.target).data('palo-modal') );
	});

	function show( url ) {
		var parent = $('#wp-auth-check'),
			form = $('#wp-auth-check-form'),
			noframe = wrap.find('.wp-auth-fallback-expired'),
			frame, loaded = false;

		form.data( 'src', url );

		if ( form.length ) {
			frame = $( '<iframe id="wp-auth-check-frame" frameborder="0">' ).attr( 'title', noframe.text() );
			frame.load( function() {
				var height, body;

				if ( frame[0].contentWindow.location.href.indexOf( "/wp-login.php" ) == -1 ) {
					window.location = frame[0].contentWindow.location.href;
				}

				loaded = true;

				try {
					body = $(this).contents().find('body');
					$( '#backtoblog', body ).remove();
					height = $( '#login', body ).outerHeight( true );
					// height = body.height();
				} catch( e ) {
					wrap.addClass( 'fallback' );
					parent.css( 'max-height', '' );
					form.remove();
					noframe.focus();
					return;
				}

				if ( height ) {
					if ( body && body.hasClass('interim-login-success') )
						hide();
					else
						parent.css( 'max-height', height + 40 + 'px' );
				} else if ( ! body || ! body.length ) {
					// Catch "silent" iframe origin exceptions in WebKit after another page is loaded in the iframe
					wrap.addClass('fallback');
					parent.css( 'max-height', '' );
					form.remove();
					noframe.focus();
				}
			}).attr( 'src', form.data('src') );

			$('#wp-auth-check-form').append( frame );
		}

		wrap.removeClass('hidden');

		if ( frame ) {
			frame.focus();
			// WebKit doesn't throw an error if the iframe fails to load because of "X-Frame-Options: DENY" header.
			// Wait for 10 sec. and switch to the fallback text.
			setTimeout( function() {
				if ( ! loaded ) {
					wrap.addClass('fallback');
					form.remove();
					noframe.focus();
				}
			}, 10000 );
		} else {
			noframe.focus();
		}
	}

	function hide() {
		$('#wp-auth-check-frame').remove();

		$(window).off( 'beforeunload.wp-auth-check' );

		// When on the Edit Post screen, speed up heartbeat after the user logs in to quickly refresh nonces
		if ( typeof adminpage !== 'undefined' && ( adminpage === 'post-php' || adminpage === 'post-new-php' ) &&
			typeof wp !== 'undefined' && wp.heartbeat ) {

			wp.heartbeat.connectNow();
		}

		wrap.fadeOut( 200, function() {
			wrap.addClass('hidden').css('display', '');
			$('#wp-auth-check-frame').remove();
		});
	}

	$( document ).ready( function() {
		wrap = $('#wp-auth-check-wrap');
		wrap.find('.wp-auth-check-close').on( 'click', function() {
			hide();
		});
	});
});
