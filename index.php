<?php

require('../../config.php');
require('./lib.php');

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
// TODO
echo 'TODO add tables here';
echo $OUTPUT->footer();
