<?php

defined('MOODLE_INTERNAL') || die();

class backup_serlo_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {
        // Define the root element describing the structure.
        $serlo = new backup_nested_element('serlo', array('id'), array(
            'course', 'name', 'intro', 'state', 'introformat', 'timemodified'
        ));

        // Define sources.
        $serlo->set_source_table('serlo', array('id' => backup::VAR_ACTIVITYID));

        // Define file annotations.
        $serlo->annotate_files('mod_serlo', 'intro', null);

        // Return the root element wrapped into a standard activity structure.
        return $this->prepare_activity_structure($serlo);
    }
}
