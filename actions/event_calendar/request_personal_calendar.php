<?php
// asks the event owner to add you to the event

require_once(elgg_get_plugins_path() . 'event_calendar/models/model.php');

$event_guid = get_input('guid', 0);
$user_guid = elgg_get_logged_in_user_guid();
$event = get_entity($event_guid);

if ($event->subtype == 'event_calendar') {
	if (event_calendar_send_event_request($event, $user_guid)) {
		elgg_register_success_message(elgg_echo('event_calendar:request_event_response'));
	} else {
		elgg_register_error_message(elgg_echo('event_calendar:request_event_error'));
	}
}

elgg_redirect_response();
