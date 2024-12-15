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
 * Library code for the serlo activity
 *
 * @package   mod_serlo
 * @author    Faisal Kaleem <serlo@adornis.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024 Serlo (https://adornis.de)
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/serlo/locallib.php');

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $serlo
 * @return int
 */
function serlo_add_instance($serlo) {
    global $DB;
    $serlo->timemodified = time();

    $types = serlo_get_content_types();
    $type = $serlo->type ?? array_key_first($types);
    unset($serlo->type);
    $serlo->state = $types[$type]['initalContent'];

    $returnid = $DB->insert_record("serlo", $serlo);

    return $returnid;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $serlo
 * @return bool
 */
function serlo_update_instance($serlo) {
    global $DB;

    $serlo->timemodified = time();
    $serlo->id = $serlo->instance;

    $DB->update_record("serlo", $serlo);

    return true;
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id
 * @return bool
 */
function serlo_delete_instance($id) {
    global $DB;
    if (!$serlo = $DB->get_record('serlo', ['id' => $id])) {
        return false;
    }
    $result = true;
    // Delete any dependent records here.
    if (!$DB->delete_records('serlo', ['id' => $serlo->id])) {
        $result = false;
    }

    return $result;
}

/**
 * Return the preconfigured tools which are configured for inclusion in the activity picker.
 *
 * @param \core_course\local\entity\content_item $defaultmodulecontentitem reference to the content item for the LTI module.
 * @param \stdClass $user the user object, to use for cap checks if desired.
 * @param stdClass $course the course to scope items to.
 * @return array the array of content items.
 */
function serlo_get_course_content_items(
    \core_course\local\entity\content_item $defaultmodulecontentitem,
    \stdClass $user,
    \stdClass $course
) {
    global $OUTPUT;

    $items = [];

    foreach (serlo_get_content_types() as $key => $value) {
        $items[] = new \core_course\local\entity\content_item(
            $defaultmodulecontentitem->get_id() + 1,
            $key,
            new \core_course\local\entity\string_title('Serlo ' . $value['title']),
            new moodle_url('/course/modedit.php', ["course" => $course->id, "add" => "serlo", "return" => 0, "type" => $key]),
            $OUTPUT->pix_icon($value['image'], '', 'serlo', ['class' => "activityicon nofilter"]),
            $defaultmodulecontentitem->get_help(),
            SERLO_CONTENT_ITEM_ARCHETYPE,
            $defaultmodulecontentitem->get_component_name(),
            $defaultmodulecontentitem->get_purpose()
        );
    }
    return $items;
}

/**
 * Set activity of serlo_get_content_types
 * @return string[][]
 */
function serlo_get_content_types() {
    return [
        'articleIntroduction' => [
            'image' => 'Multimedia',
            'title' => get_string('articleIntroduction', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"articleIntroduction","state":
            {"explanation":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]},
            "multimedia":{"plugin":"image","state":{"src":"","caption":{"plugin":"text","state":[
            {"type":"p","children":[{"text":""}]}]}}},"illustrating":true,"width":50}}]}',
        ],
        'empty' => [
            'image' => 'serlo-logo-feather',
            'title' => get_string('empty', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}]}',
        ],
        'text' => [
            'image' => 'Text',
            'title' => get_string('text', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}]}',
        ],
        'image' => [
            'image' => 'Image',
            'title' => get_string('image', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"image","state":{
            "src":"","caption":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}}}]}',
        ],
        'spoiler' => [
            'image' => 'Spoiler',
            'title' => get_string('spoiler', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"spoiler","state":{"richTitle":{"plugin":"text","state":[
            {"type":"p","children":[{"text":""}]}]},"content":{"plugin":"rows","state":[{"plugin":"text","state":[
            {"type":"p","children":[{"text":""}]}]}]}}}]}',
        ],
        'box' => [
            'image' => 'Box',
            'title' => get_string('box', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"box","state":{"type":"example","title":{"plugin":"text",
            "state":[{"type":"p","children":[{"text":""}]}]},"anchorId":"","content":{"plugin":"rows","state":[
            {"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}]}}}]}',
        ],
        'serloTable' => [
            'image' => 'Table',
            'title' => get_string('serloTable', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"serloTable","state":{"rows":[{"columns":[
            {"content":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}},{
            "content":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}}]},
            {"columns":[{"content":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}},
            {"content":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}}]},{"columns":[{"content":{
            "plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}},{"content":{"plugin":"text","state":[
            {"type":"p","children":[{"text":""}]}]}}]},{"columns":[
            {"content":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}},{"content":{"plugin":"text","state":[
            {"type":"p","children":[{"text":""}]}]}}]}],"tableType":"OnlyColumnHeader"}}]}',
        ],
        'injection' => [
            'image' => 'injection',
            'title' => get_string('injection', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"injection","state":""}]}',
        ],
        'equations' => [
            'image' => 'Equation',
            'title' => get_string('equations', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"equations","state":{"steps":[
            {"left":"","sign":"equals","right":"","transform":"","explanation":{"plugin":"text","state":[
            {"type":"p","children":[{"text":""}]}]}},{"left":"","sign":"equals","right":"","transform":"",
            "explanation":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}}],
            "firstExplanation":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]},
            "transformationTarget":"equation"}}]}',
        ],
        'geogebra' => [
            'image' => 'GeoGebra',
            'title' => get_string('geogebra', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"geogebra","state":""}]}',
        ],
        'highlight' => [
            'image' => 'Code',
            'title' => get_string('highlight', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"highlight","state":{
            "code":"","language":"text","showLineNumbers":false}}]}',
        ],
        'video' => [
            'image' => 'Video',
            'title' => get_string('video', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"video","state":{"src":"","alt":""}}]}',
        ],
        'scMcExercise' => [
            'image' => 'Auswahlaufgabe',
            'title' => get_string('scMcExercise', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"exercise","state":{
            "content":{"plugin":"rows","state":[{"plugin":"text","state":[{"type":"p",
            "children":[{"text":""}]}]}]},"interactive":{"plugin":"scMcExercise",
            "state":{"isSingleChoice":false,"answers":[{"content":{"plugin":"text",
            "state":[{"type":"p","children":[{"text":""}]}]},"isCorrect":false,
            "feedback":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}},{
            "content":{"plugin":"text","state":[{"type":"p","children":[{"text":""}]}]},
            "isCorrect":false,"feedback":{"plugin":"text",
            "state":[{"type":"p","children":[{"text":""}]}]}}]}}}}]}',
        ],
        'inputExercise' => [
            'image' => 'Eingabefeld',
            'title' => get_string('inputExercise', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"exercise",
            "state":{"content":{"plugin":"rows","state":[{"plugin":"text",
            "state":[{"type":"p","children":[{"text":""}]}]}]},"interactive":{
            "plugin":"inputExercise","state":{"type":"input-number-exact-match-challenge",
            "unit":"","answers":[{"value":"","isCorrect":true,"feedback":{
            "plugin":"text","state":[{"type":"p","children":[{"text":""}]}]}}]}}}}]}',
        ],
        'h5p' => [
            'image' => 'H5P',
            'title' => get_string('h5p', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"exercise","state":{"content":{
            "plugin":"rows","state":[{"plugin":"text","state":[{"type":"p","children":[
            {"text":""}]}]}]},"interactive":{"plugin":"h5p","state":""}}}]}',
        ],
        'blanksExercise' => [
            'image' => 'Fill-the-gap',
            'title' => get_string('blanksExercise', 'mod_serlo'),
            'initalContent' => '{"plugin":"rows","state":[{"plugin":"exercise","state":{
            "content":{"plugin":"rows","state":[{"plugin":"text","state":[{
            "type":"p","children":[{"text":""}]}]}]},"interactive":{
            "plugin":"blanksExercise","state":{"text":{"plugin":"text",
            "state":[{"type":"p","children":[{"text":""}]}]},"mode":"typing"}}}}]}',
        ],
    ];
}
