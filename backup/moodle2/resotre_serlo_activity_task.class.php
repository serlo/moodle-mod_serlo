<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/serlo/backup/moodle2/restore_serlo_stepslib.php');

class restore_serlo_activity_task extends restore_activity_task {
    /**
     * Define (add) particular settings this activity can have
     *
     * @return void
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define the steps required for restoring the module.
     */
    protected function define_my_steps() {
        // Add a restore step for Serlo
        $this->add_step(new restore_serlo_activity_structure_step('serlo_structure', 'serlo.xml'));
    }

    /**
     * Define content that must be processed by the link decoder.
     */
    public static function define_decode_contents() {
        $contents = [];

        $contents[] = new restore_decode_content('serlo', ['intro'], 'serlo');

        return $contents;
    }

    /**
     * Define the decoding rules for links in the activity.
     */
    public static function define_decode_rules() {
        $rules = [];

        $rules[] = new restore_decode_rule('SERLOINDEX', '/mod/serlo/index.php?id=$1', 'course');
        $rules[] = new restore_decode_rule('SERLOVIEWBYID', '/mod/serlo/view.php?id=$1', 'course_module');

        return $rules;
    }

    /**
     * Define the restore log rules for activity-level logs.
     */
    public static function define_restore_log_rules() {
        $rules = [];

        $rules[] = new restore_log_rule('serlo', 'add', 'view.php?id={course_module}', '{serlo}');
        $rules[] = new restore_log_rule('serlo', 'update', 'view.php?id={course_module}', '{serlo}');
        $rules[] = new restore_log_rule('serlo', 'view', 'view.php?id={course_module}', '{serlo}');

        return $rules;
    }

    /**
     * Define the restore log rules for course-level logs.
     */
    public static function define_restore_log_rules_for_course() {
        $rules = [];

        $rules[] = new restore_log_rule('serlo', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}
