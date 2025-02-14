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
 * View code for the serlo activity
 *
 * @package   mod_serlo
 * @author    Faisal Kaleem <serlo@adornis.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024 Serlo (https://adornis.de)
 */

use mod_serlo\output\serlo_editor;

require(__DIR__ . '/../../config.php');

$id = optional_param('id', 0, PARAM_INT);

if (!$id) {
    throw new moodle_exception('invalidcoursemodule');
}

$cm = get_coursemodule_from_id('serlo', $id);

if (!$cm) {
    throw new moodle_exception('invalidcoursemodule');
}
if (!$course = $DB->get_record('course', ['id' => $cm->course])) {
    throw new moodle_exception('coursemisconf');
}
if (!$serlo = $DB->get_record('serlo', ['id' => $cm->instance])) {
    throw new moodle_exception('invalidserloid', 'serlo');
}

require_course_login($course, true, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/serlo:view', $context);

serlo_view($serlo, $course, $cm, $context);

$PAGE->set_context($context);

$title = $serlo->name;

// Initialize $PAGE.
$PAGE->set_url('/mod/serlo/view.php', ['id' => $cm->id]);
$PAGE->set_title($title);
$PAGE->set_heading($course->fullname);

$modes = [
    true => "write",
    false => "read",
];

if (isset($_SERVER['HTTP_USER_AGENT']) && strlen(strstr($_SERVER['HTTP_USER_AGENT'], 'Firefox')) > 0) {
    \core\notification::warning(get_string('firefox_warning', 'mod_serlo'));
}

$editorattrs = [
    'use-shadow-dom' => "false",
    'language' => current_language(),
    'is-production-environment' => true, // TODO: Should be true in Moodle Plugin Directory, false otherwise. 
];

$cansave = has_capability('mod/serlo:update', $context) && $PAGE->user_is_editing();

if ($cansave) {
    $editorattrs['mode'] = "write";
    $editorattrs['testing-secret'] = get_config('serlo', 'editor_secret');
} else {
    $editorattrs['mode'] = "read";
}

// Print the page header.
echo $OUTPUT->header();

if ($serlo->intro) {
    echo $OUTPUT->box(format_module_intro('serlo', $serlo, $cm->id), 'generalbox', 'intro');
}

echo $OUTPUT->box_start('generalbox', 'notallowenter');

$output = $PAGE->get_renderer('mod_serlo');
echo $output->render(new serlo_editor(
    $cansave,
    $editorattrs,
));

$PAGE->requires->js_call_amd('mod_serlo/serlo-lazy', 'init', [$serlo->id]);

echo $OUTPUT->box_end();
echo $OUTPUT->footer();
