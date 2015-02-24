<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once('../../config.php');
require_once('lib.php');
require_once('classes/progress_report.php');
require_once('classes/activity/action.php');
require_once('classes/activity/reaction.php');

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

function get_id($record) {return $record->id;}
$teacher_ids = array_map('get_id', array_values($DB->get_records_sql('
  SELECT {user}.id FROM {user}
  INNER JOIN {role_assignments} ON {role_assignments}.userid = {user}.id
  AND ({role_assignments}.roleid = 3 OR {role_assignments}.roleid = 4)
')));

$report = new progress_report($teacher_ids, array(), array(
    new reaction(array('\\mod_assign\\event\\submission_created', '\\mod_assign\\event\\submission_updated'), '', 100000)
));

echo $report->render();

echo $OUTPUT->footer();
