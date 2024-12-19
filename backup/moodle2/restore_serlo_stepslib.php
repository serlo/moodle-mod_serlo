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
 * Restore steps for mod_serlo are defined here.
 *
 * For more information about the backup and restore process, please visit:
 * https://docs.moodle.org/dev/Backup_2.0_for_developers
 * https://docs.moodle.org/dev/Restore_2.0_for_developers
 * @package   mod_serlo
 * @author    Faisal Kaleem <serlo@adornis.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024 Serlo (https://adornis.de)
 */
class restore_serlo_activity_structure_step extends restore_activity_structure_step {

    /**
     * Define the structure for restoring the Serlo activity.
     *
     * @return restore_path_element[]
     */
    protected function define_structure() {
        $paths = [];

        // Add a path for restoring the main Serlo data!
        $paths[] = new restore_path_element('serlo', '/activity/serlo');

        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process the main Serlo activity data.
     *
     * @param array $data
     */
    protected function process_serlo($data) {
        global $DB;

        $data = (object)$data;

        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // Insert the serlo record.
        $newitemid = $DB->insert_record('serlo', $data);
        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Defines post-execution actions.
     */
    protected function after_execute() {
        // Add serlo related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_serlo', 'intro', null);
    }
}
