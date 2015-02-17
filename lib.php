<?php

defined('MOODLE_INTERNAL') || die();

function report_teacher_engagement_extend_navigation_user($navigation, $user, $course) {
    if (report_teacher_engagement_can_access_user_report($user)) {
        $url = new moodle_url('/report/teacher_engagement/index.php');
        $navigation->add(get_string('pluginname', 'report_teacher_engagement'), $url);
    }
}

function report_teacher_engagement_can_access_user_report($user) {
    $personalcontext = context_user::instance($user->id);
    return has_capability('moodle/user:viewuseractivitiesreport', $personalcontext);
}

function get_table_data() {
    global $DB;
    return $DB->get_records_sql('
        SELECT id, firstname FROM {user}
    ');
}