<?php

defined('MOODLE_INTERNAL') || die();

class restore_serlo_activity_structure_step extends restore_activity_structure_step {

    /**
     * Define the structure for restoring the Serlo activity.
     *
     * @return restore_path_element[]
     */
    protected function define_structure() {
        $paths = [];

        // Add a path for restoring the main Serlo data
        $paths[] = new restore_path_element('serlo', '/activity/serlo');

        // Add a path for restoring related content if needed
        $paths[] = new restore_path_element('serlo_item', '/activity/serlo/items/item');

        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process the main Serlo activity data.
     *
     * @param array $data
     */
    protected function process_serlo($data) {
        global $DB;

        // Transform data as needed
        $data = (object)$data;

        // Map old ID to new ID
        $newitemid = $DB->insert_record('serlo', $data);
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Process the related Serlo items.
     *
     * @param array $data
     */
    protected function process_serlo_item($data) {
        global $DB;

        $data = (object)$data;

        // Handle foreign key mapping if necessary
        $data->serloid = $this->get_new_parentid('serlo');

        $DB->insert_record('serlo_items', $data);
    }
}
