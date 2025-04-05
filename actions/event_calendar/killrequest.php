<?php
// this action allows an admin or event owner to reject a calendar request

require_once(elgg_get_plugins_path() . 'event_calendar/models/model.php');

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$event_guid = get_input('event_guid');

$user = get_entity($user_guid);
$event = get_entity($event_guid);

if ($event->subtype == 'event_calendar'
	&& $user instanceof ElggUser
	&& $event->canEdit()
	&& $user->hasRelationship($event_guid, 'event_calendar_request')) {
	$event->removeRelationship($event_guid, 'event_calendar_request');
	elgg_register_success_message(elgg_echo('event_calendar:requestkilled'));
} else {
	elgg_register_error_message(elgg_echo('event_calendar:review_requests:error:reject'));
}

elgg_redirect_response();
