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

namespace mod_serlo;

/**
 * UnitTest for the serlo activity
 *
 * @package   mod_serlo
 * @author    Faisal Kaleem <serlo@adornis.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024 Serlo (https://adornis.de)
 */
final class serlo_test extends \advanced_testcase {
    public function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Make sure all state type must in specific lists.
     * @covers \serlo_get_content_types
     * @dataProvider content_types_provider
     * @param string $type
     */
    public function test_content_type_keys(string $type): void {
        $this->assertTrue(in_array($type, [
            'articleIntroduction',
            'empty',
            'text',
            'image',
            'spoiler',
            'box',
            'serloTable',
            'equations',
            'geogebra',
            'highlight',
            'video',
            'scMcExercise',
            'inputExercise',
            'blanksExercise',
        ]));
    }

    /**
     * Create course and make course state created exactly.
     * @covers \create_course
     * @dataProvider content_types_provider
     * @param string $type
     * @param array $expected
     */
    public function test_create_course(string $type, array $expected): void {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/course/modlib.php');

        $this->setAdminUser();

        $serlorecords = $DB->get_records('serlo');
        $this->assertEmpty($serlorecords);

        $course = $this->getDataGenerator()->create_course();

        $serlomodule = $DB->get_record('modules', ['name' => 'serlo']);
        $data = (object) [
            'name' => 'Serlo name',
            'intro' => 'Serlo intro',
            'modulename' => 'serlo',
            'type' => $type,
            'module' => $serlomodule->id,
            'visible' => 1,
            'section' => 0,
        ];

        $serlo = $this->get_serlo_record($data, $course);

        $serlostate = json_decode($serlo->state, true);

        $this->assertTrue($serlostate === $expected['initalContent']);

        $this->assertNotEmpty($serlo);
        $this->assertEquals($course->id, $serlo->course);
        $this->assertEquals($data->name, $serlo->name);
        $this->assertEquals($data->intro, $serlo->intro);
    }

    /**
     * Get serlo get_serlo_record
     * @param mixed $data
     * @param mixed $course
     * @return mixed
     */
    private function get_serlo_record($data, $course) {
        global $DB;
        $moduleinfo = add_moduleinfo($data, $course);
        return $DB->get_record('serlo', ['course' => $moduleinfo->course]);
    }

    /**
     * Data provider
     *
     * @return array List of data sets - (string) data set name => (array) data
     */
    public static function content_types_provider(): array {
        $results = [];
        foreach (serlo_get_content_types() as $key => $item) {
            $item['initalContent'] = json_decode($item['initalContent'], true);
            $results[] = [$key, $item];
        }
        return $results;
    }
}
