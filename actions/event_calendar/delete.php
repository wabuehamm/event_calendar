<?php

$event_guid = get_input('guid', 0);
$event = get_entity($event_guid);

if ($event->subtype == 'event_calendar' && $event->canEdit()) {
	if (get_input('cancel', '')) {
		elgg_register_success_message(elgg_echo('event_calendar:delete_cancel_response'));
	} else {
		$container = get_entity($event->container_guid);
		$event->delete();
		elgg_register_success_message(elgg_echo('event_calendar:delete_response'));
		if ($container instanceof ElggGroup) {
			elgg_redirect_response('event_calendar/group/'.$container->guid);
		} else {
			elgg_redirect_response('event_calendar/list');
		}
	}
} else {
	elgg_register_error_message(elgg_echo('event_calendar:error_delete'));
}

elgg_redirect_response();
