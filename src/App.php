<?php
/**
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    BSD License
 * @copyright  2014 Kenji Suzuki
 * @link       https://github.com/kenjis/Nagoya.php.vol6-DouKaku
 */

namespace Nagoyaphp\Lesson;

class App
{
    const MAX_STUDENTS = 4;

    /**
     * @param type $input
     * @return string
     */
    public function run($input)
    {
        $appilcants = $this->parseInput($input);
        $lessons = $this->processApplicants($appilcants);
        return $this->output($lessons);
    }

    /**
     * @param string $input
     * @return array
     */
    public function parseInput($input)
    {
        $applicants = [];

        $data = explode('|', $input);
        foreach ($data as $applicant) {
            list($id, $daysString) = explode('_', $applicant);
            $applicants[$id] = $daysString;
        }

        return $applicants;
        // [id => daysString]
        // eg, [1 => '12345']
        // daysString: 月=1, 火=2, 水=3, 木=4, 金=5
    }

    /**
     * @param array $applicants
     * @return array
     */
    public function processApplicants($applicants)
    {
        $classes[1] = [];
        $classes[2] = [];
        $classes[3] = [];
        $classes[4] = [];
        $classes[5] = [];
        $remainingApplicants = [];

        $i = 0;
        while ($i < 5) {
            foreach ($applicants as $id => $daysString) {
                // get the $i th choice
                $day = $daysString[$i];

                if (count($classes[$day]) < static::MAX_STUDENTS) {
                    $classes[$day][] = $id;
                } else {
                    $remainingApplicants[$id] = $daysString;
                }
            }

            $i++;
            $applicants = $remainingApplicants;
            $remainingApplicants = [];
        }

        // remove empty classes
        foreach ($classes as $id => $students) {
            if ($students === []) {
                unset($classes[$id]);
            }
        }

        return $classes;
        // [day => [students_id_array]]
        // eg, [1 => [1, 2, 3]]
    }

    /**
     * @param array $lessons
     * @return string
     */
    public function output($lessons)
    {
        $output = '';

        foreach ($lessons as $day => $students) {
            sort($students, SORT_NUMERIC);
            $output .= $day . '_' .implode(':', $students) .'|';
        }

        return rtrim($output, '|');
    }
}
