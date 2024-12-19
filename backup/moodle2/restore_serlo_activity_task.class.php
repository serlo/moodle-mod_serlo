<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/serlo/backup/moodle2/restore_serlo_stepslib.php');

/**
 * The task that provides all the steps to perform a complete restore is defined here.
 *
 * @package   mod_serlo
 * @author    Faisal Kaleem <serlo@adornis.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024 Serlo (https://adornis.de)
 */
class restore_serlo_activity_task extends restore_activity_task {
    /**
     * Defines particular settings that this activity can have.
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Defines particular steps that this activity can have.
     */
    protected function define_my_steps() {
        // Add a restore step for Serlo!
        $this->add_step(new restore_serlo_activity_structure_step('serlo_structure', 'serlo.xml'));
    }

    /**
     * Defines the contents in the activity that must be processed by the link decoder.
     */
    public static function define_decode_contents() {
        $contents = [];
        $contents[] = new restore_decode_content('serlo', ['intro'], 'serlo');

        return $contents;
    }

    /**
     * Defines the decoding rules for links belonging to the activity to be executed by the link decoder.
     */
    public static function define_decode_rules() {
        $rules = [];
        $rules[] = new restore_decode_rule('SERLOINDEX', '/mod/serlo/index.php?id=$1', 'course');
        $rules[] = new restore_decode_rule('SERLOVIEWBYID', '/mod/serlo/view.php?id=$1', 'course_module');

        return $rules;
    }

    /**
     * Define the restoring rules for logs belonging to the activity to be executed by the link decoder.
     */
    public static function define_restore_log_rules() {
        $rules = [];

        $rules[] = new restore_log_rule('serlo', 'add', 'view.php?id={course_module}', '{serlo}');
        $rules[] = new restore_log_rule('serlo', 'update', 'view.php?id={course_module}', '{serlo}');
        $rules[] = new restore_log_rule('serlo', 'view', 'view.php?id={course_module}', '{serlo}');

        return $rules;
    }
}
