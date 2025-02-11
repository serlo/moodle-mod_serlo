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

/**
 * External update state code for the serlo activity
 *
 * @package   mod_serlo
 * @author    Faisal Kaleem <serlo@adornis.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024 Serlo (https://adornis.de)
 */

namespace mod_serlo\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;
use context_module;

/**
 * Summary of update_serlo_state
 */
class update_serlo_state extends external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'serloid' => new external_value(PARAM_INT, 'id of serlo coursemodule'),
            'state' => new external_value(PARAM_TEXT, 'state of serlo editor'),
        ]);
    }

    /**
     * Summary of execute_returns
     * @return external_value
     */
    public static function execute_returns() {
        return new external_value(PARAM_BOOL, 'whether the update was successful or not');
    }

    /**
     * Summary of execute
     * @param mixed $serloid
     * @param mixed $state
     * @throws \moodle_exception
     * @return bool
     */
    public static function execute($serloid, $state) {
        global $DB;
        $params = external_api::validate_parameters(self::execute_parameters(), [
            'serloid' => $serloid,
            'state' => $state,
        ]);
        $serloid = $params['serloid'];
        $state = $params['state'];

        // Request and permission validation.
        list($course, $cm) = get_course_and_cm_from_instance($serloid, 'serlo');

        $context = context_module::instance($cm->id);
        self::validate_context($context);
        require_capability('mod/serlo:update', $context);

        if (!$serlo = $DB->get_record('serlo', ['id' => $cm->instance])) {
            throw new \moodle_exception('invalidid');
        }

        $serlo->state = $state;

        $DB->update_record('serlo', $serlo);

        return true;
    }
}
