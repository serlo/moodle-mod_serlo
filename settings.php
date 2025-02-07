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
 * Plugin administration pages are defined here.
 *
 * @package   mod_serlo
 * @author    Faisal Kaleem <serlo@adornis.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024 Serlo (https://adornis.de)
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // Only show serlo/editor_secret on settings page.
    if (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] === '/admin/settings.php') {
        $settings->add(new admin_setting_heading('modserloplaceholder', get_string('settings_placeholder', 'mod_serlo'), ''));
        $settings->add(
            new admin_setting_configpasswordunmask(
                'serlo/editor_secret',
                get_string('settings_editor_secret_label', 'mod_serlo'),
                get_string('settings_editor_secret_description', 'mod_serlo'),
                ''
            )
        );
    }
}
