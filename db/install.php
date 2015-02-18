<?php

defined('MOODLE_INTERNAL') || die();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

function xmldb_report_teacher_engagement_install() {
    global $DB;
    $max_seconds_between_events = 600; // Ten Minutes
    // Use closures to break class member protection because
    // moodle won't let us use semicolons in $DB->execute
    // so modify the function to be inside the class context
    // giving us access
    // http://ocramius.github.io/blog/accessing-private-php-class-members-without-reflection/
    $get_mysqli = Closure::bind(function ($moodle_database) {
        return $moodle_database->mysqli;
    }, null, $DB);
    $mysqli = $get_mysqli($DB);
    $mysqli->query('DROP FUNCTION IF EXISTS get_time_spent_on_moodle;');
    $mysqli->query("
CREATE FUNCTION get_time_spent_on_moodle(user_id INT, since_time INT) RETURNS INT
	DETERMINISTIC
    BEGIN
    	DECLARE max_seconds_between_events INT DEFAULT 600;
    	DECLARE done, time_current, time_previous, time_difference, session_time INT DEFAULT 0;
   		DECLARE query_cursor CURSOR FOR SELECT timecreated FROM mdl_logstore_standard_log WHERE userid = user_id AND FROM_UNIXTIME(timecreated) > since_time ORDER BY id;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
        OPEN query_cursor;
        read_loop: LOOP
        	FETCH query_cursor INTO time_current;
            SET time_difference = time_current - time_previous;
            IF time_difference < max_seconds_between_events THEN
            	SET session_time = session_time + time_difference;
            END IF;
            SET time_previous = time_current;
            IF done THEN
            	LEAVE read_loop;
			END IF;
        END LOOP;
        CLOSE query_cursor;
        RETURN ROUND(session_time / 60 / 60 * 100) / 100; # seconds / min / hours
    END;
    #SELECT get_time_spent_on_moodle(3, 0);
    ");
}
