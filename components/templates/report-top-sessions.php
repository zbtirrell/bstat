<?php

// don't show this panel if there are no sessions
$sessions = bstat()->report()->top_sessions( array_merge( bstat()->report()->filter, array( 'user' => 0 ) ) ); // @TODO: array( 'user' => 0 ) doesn't work because of how the filter is sanitized
if ( ! count( $sessions ) )
{
	return;
}

// for sanity, limit this to just the top few sessions
$sessions = array_slice( $sessions, 0, bstat()->options()->report->max_items );

$total_activity = 0;
foreach ( $sessions as $user )
{
	$total_activity += $user->hits;
}

echo '<h2>Sessions</h2>';
echo '<p>Showing ' . count( $sessions ) . ' sessions with ' . $total_activity . ' total actions.</p>';
echo '<ol>';
foreach ( $sessions as $user )
{
	$posts = bstat()->report()->get_posts( bstat()->report()->posts_for_session( $user->session ), array( 'posts_per_page' => 3, 'post_type' => 'any' ) );

	// it appears WP's get_the_author() emits the author display name with no sanitization
	printf(
		'<li><a href="%1$s">%2$s</a> (%3$s hits)',
		bstat()->report()->report_url( array( 'session' => $user->session, ) ),
		$user->session,
		(int) $user->hits
	);

	echo '<ol>';
	foreach ( $posts as $post )
	{
		printf(
			'<li %1$s><a href="%2$s">%3$s</a> (%4$s hits)</li>',
			get_post_class( '', $post->ID ),
			bstat()->report()->report_url( array( 'post' => $post->ID, ) ),
			get_the_title( $post->ID ),
			(int) $post->hits
		);
	}

	echo '</ol></li>';


}
echo '</ol>';