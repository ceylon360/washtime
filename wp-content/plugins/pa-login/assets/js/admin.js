// Preventing embedding in iframe
if ( window.top !== window.self ) {
	window.top.location.replace( window.self.location.href );
}
