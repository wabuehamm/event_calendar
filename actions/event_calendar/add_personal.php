<?php

require_once(elgg_get_plugins_path() . 'event_calendar/models/model.php');

$event_guid = get_input('guid', 0);
$event = get_entity($event_guid);
if ($event->subtype == 'event_calendar') {
	$user_guid = elgg_get_logged_in_user_guid();
	if (!event_calendar_has_personal_event($event_guid,$user_guid)) {
		if (event_calendar_add_personal_event($event_guid,$user_guid)) {
			elgg_register_success_message(elgg_echo('event_calendar:add_to_my_calendar_response'));
		} else {
			elgg_register_error_message(elgg_echo('event_calendar:add_to_my_calendar_error'));
		}
	}
}

elgg_redirect_response();
