<?php

$count = intval($vars["entity"]->events_count);
if(empty($count)){
	$count = 4;
}

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo("event_calendar:num_display"),
	'name' => 'params[events_count]',
	'value' => $count,
	'min' => '1',
	'max' => '20',
	'step' => '1',
]);