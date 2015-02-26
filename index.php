<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once('../../config.php');
require_once('lib.php');
require_once('classes/progress_report.php');
require_once('classes/activity/action.php');
require_once('classes/activity/reaction.php');
require_once($CFG->dirroot.'/lib/tablelib.php');

require_login();

global $USER, $DB;

// Set Renderer Options
$PAGE->set_pagelayout('report'); // To add the sidebar
$PAGE->set_title(get_string('title', 'report_teacher_engagement'));
$PAGE->set_heading(get_string('title', 'report_teacher_engagement'));

// Check for permission
if (!report_teacher_engagement_can_access_user_report($USER)) {
    echo $OUTPUT->header();
    echo get_string('nopermission', 'report_teacher_engagement');
    echo $OUTPUT->footer();
    die();
}

echo $OUTPUT->header();

function get_id($record) {
    return $record->id;
}

$teacher_ids = array_map('get_id', array_values($DB->get_records_sql('
  SELECT {user}.id FROM {user}
  INNER JOIN {role_assignments} ON {role_assignments}.userid = {user}.id
  AND ({role_assignments}.roleid = 3 OR {role_assignments}.roleid = 4)
')));

$report = new progress_report($teacher_ids, array(), array(
    new reaction('assignments_graded', 7, '\\mod_assign\\event\\submission_graded', array('\\assignsubmission_file\\event\\submission_created', '\\assignsubmission_file\\event\\submission_updated'))
));

echo var_dump($report->get_sql_fields());
echo var_dump($report->get_sql_joins());

// TODO translation
$sql_fields = 'CONCAT({user}.firstname, " ", {user}.lastname) AS "teacher",
    GROUP_CONCAT(DISTINCT {course}.shortname ORDER BY {course}.shortname SEPARATOR ", ") AS "courses",
    '.$report->get_sql_fields();
$sql_from = '{user}
    INNER JOIN {role_assignments} ON {role_assignments}.userid = {user}.id
        AND ({role_assignments}.roleid = 3 OR {role_assignments}.roleid = 4)
    LEFT JOIN {context} ON contextlevel = 50 AND {context}.id = {role_assignments}.contextid
    LEFT JOIN {course} ON {course}.id = {context}.instanceid AND {course}.visible = 1 AND {course}.format != "site"
    '.$report->get_sql_joins(); // teacher or editingteacher
$sql_where = '1';

$table = new table_sql('uniqueid');
$table->set_sql($sql_fields, $sql_from, $sql_where);
$table->out(40, true);

//echo $report->render();

echo $OUTPUT->footer();
