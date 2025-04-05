<?php

$event_guid = get_input('event_guid');
$event = get_entity($event_guid);
$event_calendar_add_users = elgg_get_plugin_setting('add_users', 'event_calendar');

if (($event_calendar_add_users == 'yes') && $event->subtype == 'event_calendar' && $event->canEdit()) {
	$members = get_input('members');
	// clear the event from all personal calendars
	$event->removeAllRelationships('personal_event', true);
	// add event to personal calendars
	$event_calendar_add_users_notify = elgg_get_plugin_setting('add_users_notify', 'event_calendar');
	foreach ($members as $user_guid) {
		$event->addRelationship($user_guid, 'personal_event');
		if ($event_calendar_add_users_notify == 'yes') {
			if ($user_guid != elgg_get_logged_in_user_guid()) {
				$user = get_user($user_guid);
				$user_language = ($user->language) ? $user->language : (($site_language = elgg_get_config('language')) ? $site_language : 'en');
				$subject = elgg_echo('event_calendar:add_users_notify:subject', [], $user_language);
				$message = elgg_echo('event_calendar:add_users_notify:body', [$user->name, $event->title, $event->getURL()], $user_language);
				notify_user($user_guid, elgg_get_logged_in_user_guid(), $subject, $message, [
					'object' => $event,
					'action' => 'subscribe',
					'summary' => $subject,
				]);
			}
		}
	}
	elgg_register_success_message(elgg_echo('event_calendar:manage_subscribers:success'));
	elgg_redirect_response($event->getURL());
} else {
	elgg_register_error_message(elgg_echo('event_calendar:manage_subscribers:error'));
	elgg_redirect_response();
}
