<?php

$event_id = get_input("event_id", 0);
$group_id = get_input("group_id", 0);
$event = get_entity($event_id);
$event->removeRelationship($group_id, "display_on_group");
elgg_register_success_message(elgg_echo('event_calendar:remove_from_group:success'));
elgg_redirect_response($event->getUrl());
