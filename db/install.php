<?php

defined('MOODLE_INTERNAL') || die();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

function xmldb_report_teacher_engagement_install() {

    // can use semi colons ... need to find workaround
    $max_seconds_between_events = 600; // Ten Minutes
    global $DB;
    $database = $DB;
    $DB->execute("DROP FUNCTION IF EXISTS get_time_spent_on_moodle");
    $Reflection = new ReflectionProperty(get_class($DB), 'mysqli');
    $Reflection->setAccessible(true);
    var_dump($Reflection->getValue($DB));
    "
   DROP FUNCTION IF EXISTS get_time_spent_on_moodle;# MySQL returned an empty result set (i.e. zero rows).

DELIMITER $$
CREATE FUNCTION get_time_spent_on_moodle(user_id INT, since_time INT) RETURNS int
	DETERMINISTIC
    BEGIN
    	DECLARE max_seconds_between_events INT DEFAULT 600; /* Ten Minutes */
    	DECLARE done, time_current, time_previous, time_difference, session_time INT DEFAULT 0;
   		DECLARE query_cursor CURSOR FOR SELECT timecreated FROM mdl_logstore_standard_log WHERE userid = user_id AND timecreated > since_time ORDER BY id;
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
        RETURN session_time;
    END;
    $$
    DELIMITER ;

SELECT get_time_spent_on_moodle(3, 0);
    "
}
