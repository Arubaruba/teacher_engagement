<?php

require('../../config.php');
require('./lib.php');
require_once($CFG->dirroot.'/lib/tablelib.php');
require_once('./db/install.php');

xmldb_report_teacher_engagement_install();

require_login();

global $USER;

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

// TODO translation
$sql_fields = 'CONCAT({user}.firstname, " ", {user}.lastname) AS "teacher", {user}.email AS "email address", {user}.phone1 AS "phone",
    GROUP_CONCAT(DISTINCT {course}.shortname ORDER BY {course}.shortname SEPARATOR ", ") AS "courses",
    "TODO" AS "neglected courses",
    get_time_spent_on_moodle({user}.id) AS "time on moodle this week",
    "TODO" AS "average time on moodle per week"';
$sql_from = '{user}
    INNER JOIN {role_assignments} ON {role_assignments}.userid = {user}.id
        AND ({role_assignments}.roleid = 3 OR {role_assignments}.roleid = 4)
    LEFT JOIN {context} ON contextlevel = 50 AND {context}.id = {role_assignments}.contextid
    LEFT JOIN {course} ON {course}.id = {context}.instanceid AND {course}.visible = 1 AND {course}.format != "site"'; // teacher or editingteacher
$sql_where = '1';

$table = new table_sql('uniqueid');
$table->set_sql($sql_fields, $sql_from, $sql_where);
$table->out(40, true);

echo $OUTPUT->footer();
