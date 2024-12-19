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

require_once($CFG->dirroot . '/mod/serlo/backup/moodle2/backup_serlo_stepslib.php');

/**
 * The task that provides all the steps to perform a complete backup is defined here.
 *
 * @package   mod_serlo
 * @author    Faisal Kaleem <serlo@adornis.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024 Serlo (https://adornis.de)
 */
class backup_serlo_activity_task extends backup_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Defines a backup step to store the instance data in the serlo.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_serlo_activity_structure_step('serlo_structure', 'serlo.xml'));
    }

    /**
     * Codes the transformations to perform in the activity in order to get transportable (encoded) links.
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    public static function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, "/");

        // Link to the list of serlo.
        $search = "/(" . $base . "\/mod\/serlo\/index.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@SERLOINDEX*$2@$', $content);

        // Link to serlo view by moduleid.
        $search = "/(" . $base . "\/mod\/serlo\/view.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@SERLOVIEWBYID*$2@$', $content);

        return $content;
    }
}
