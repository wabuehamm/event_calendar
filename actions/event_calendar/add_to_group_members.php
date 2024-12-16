<?php

require_once(elgg_get_plugins_path() . 'event_calendar/models/model.php');

$event_guid = get_input("event_guid", 0);
$event = get_entity($event_guid);
$group = get_entity($event->container_guid);

if ($group instanceof ElggGroup && $event->subtype == 'event_calendar' && $group->canEdit()) {
	$members = $group->getMembers(['limit' => false]);
	foreach($members as $member) {
		event_calendar_add_personal_event($event->guid, $member->guid);
	}
	elgg_register_success_message(elgg_echo('event_calendar:add_to_group_members:success'));
} else {
	elgg_register_error_message(elgg_echo('event_calendar:add_to_group_members:error'));
}
elgg_redirect_response("event_calendar/manage_users/$event_guid");
