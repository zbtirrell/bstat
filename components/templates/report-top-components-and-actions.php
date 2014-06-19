<?php

// don't show this panel if there's only one component:action
$components_and_actions = bstat()->report()->top_components_and_actions();
if ( 2 > count( $components_and_actions ) )
{
	return;
}

// for sanity, limit this to just the top 100 component:action pairs
$components_and_actions = array_slice( $components_and_actions, 0, bstat()->options()->report->max_items );

$total_activity = 0;
foreach ( $components_and_actions as $component_and_action )
{
	$total_activity += $component_and_action->hits;
}

echo '<h2>Components and actions</h2>';
echo '<p>Showing ' . count( $components_and_actions ) . ' component:action pairs with ' . $total_activity . ' total actions.</p>';
echo '<ol>';
foreach ( $components_and_actions as $component_and_action )
{
	printf(
		'<li><a href="%1$s">%2$s</a>:<a href="%3$s">%4$s</a> (%5$s hits)</li>',
		bstat()->report()->report_url( array( 'component' => $component_and_action->component, ) ),
		$component_and_action->component,
		bstat()->report()->report_url( array( 'component' => $component_and_action->component, 'action' => $component_and_action->action ) ),
		$component_and_action->action,
		$component_and_action->hits
	);
}// end foreach
?>
</ol>

<div id="components-actions-graph">
	<script>
	/*
	bstat_report.actions_pie_data = [
		<?php
		echo implode( ',', wp_list_pluck( $component_and_action, 'hits' ) );
		?>
	];
	*/
	</script>
</div>