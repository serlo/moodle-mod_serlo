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

namespace mod_serlo\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;

/**
 * Show editor template
 *
 * @package   mod_serlo
 * @author    Faisal Kaleem <serlo@adornis.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2024 Serlo (https://adornis.de)
 */
class serlo_editor implements renderable, templatable {
    /**
     * Determines that users can update the content
     * @var bool
     */
    private $cansave = false;

    /**
     * Summary of editorattrs
     * @var string
     */
    private $editorattrs = "";

    /**
     * Creates a new loading page data container.
     * @param array $data
     */
    public function __construct(bool $cansave, array $editorattrs) {
        $this->cansave = $cansave;
        $this->editorattrs = $editorattrs;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output): stdClass {
        $editorattrs = "";
        array_walk($this->editorattrs, function ($value, $key) use (&$editorattrs) {
            $editorattrs .= $key . '="' . $value . '" ';
        });

        $data = new stdClass;
        $data->cansave = $this->cansave;
        $data->editorattrs = $editorattrs;

        return $data;
    }
}
