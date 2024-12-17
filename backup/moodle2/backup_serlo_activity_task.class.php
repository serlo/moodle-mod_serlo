<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/serlo/backup/moodle2/backup_serlo_stepslib.php');

class backup_serlo_activity_task extends backup_activity_task {

    /**
     * No specific settings for this activity
     */
    protected function define_my_settings() {
    }

    /**
     * Defines a backup step to store the instance data in the serlo.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_serlo_activity_structure_step('serlo_structure', 'serlo.xml'));
    }

    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot,"/");

        // Link to the list of serlo
        $search="/(".$base."\/mod\/serlo\/index.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@SERLOINDEX*$2@$', $content);

        // Link to serlo view by moduleid
        $search="/(".$base."\/mod\/serlo\/view.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@SERLOVIEWBYID*$2@$', $content);

        // Return the now encoded content
        return $content;
    }
}
