<?php

/*
 * Converting personal_event annotations (old way of managing the addition of an event to a user's personal calendar)
 * into personal_event relationships (the new way)
 */

use Doctrine\DBAL\Query\QueryBuilder;

set_time_limit(0);

elgg_call(ELGG_IGNORE_ACCESS & ELGG_SHOW_DISABLED_ENTITIES, function() use ($elgg) {
	elgg_register_event_handler('permissions_check', 'all', 'elgg_override_permissions');
	elgg_register_event_handler('container_permissions_check', 'all', 'elgg_override_permissions');

	$db_prefix = elgg_get_config('dbprefix');

	$batch = new ElggBatch('elgg_get_annotations', [
		'type' => 'object',
		'subtype' => 'event_calendar',
		'annotation_name' => 'personal_event',
		'limit' => false,
	]);

	// now collect the ids of the personal_event annotations for later deletion and create a corresponding relationship
	$personal_event_entry_id = [];
	foreach ($batch as $personal_event_annotation) {
		// collect annotation id for deletion only if addition of relationship was a success
		if ($personal_event_annotation->addRelationship($personal_event_annotation->annotation_value, 'personal_event')) {
			$personal_event_entry_id[] = $personal_event_annotation->id;
		}
	}

	// and finally delete the rows of the personal_event annotations in the annotations table
	if ($personal_event_entry_id) {
		$personal_event_entry_id = implode(', ', $personal_event_entry_id);
		$qb = new QueryBuilder($elgg->db->getConnection());
		$qb->delete("{$db_prefix}annotations")->where("id IN ($personal_event_entry_id)");
		elgg()->db->deleteData($qb);
		unset($personal_event_entry_id);
	}
});
