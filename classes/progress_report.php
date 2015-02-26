<?php

require_once('./../../config.php');
require_once('activity/action.php');
require_once('activity/reaction.php');

class progress_report {
    /**
     * @var $target_users array(int)
     * @var $actions array(activity/action)
     * @var $reactions array(activity/reaction)
     */
    private $target_users, $actions, $reactions;

    function __construct($target_users, $actions, $reactions) {
        $this->target_users = $target_users;
        $this->actions = $actions;
        $this->reactions = $reactions;
    }

    public function get_sql_fields() {
        $fields = array();
        foreach ($this->reactions as &$reaction) {
            array_push($fields, 'FLOOR(COUNT(' . $reaction->name . '_prerequisite.id) / COUNT(' . $reaction->name . '.id) * 100) AS "' . $reaction->percentage_reactions_within_days_label() . '"');
        }
        return implode(",\n", $fields);
    }

    public function get_sql_joins() {
        $joins = array();
        foreach ($this->reactions as &$reaction) {
            array_push($joins, 'LEFT JOIN {logstore_standard_log} AS ' . $reaction->name . ' ON ' . $reaction->name . '.eventname IN(' . $this->event_input($reaction->trigger_event) . ') AND ' . $reaction->name . '.courseid = {course}.id',
            'LEFT JOIN {logstore_standard_log} AS '. $reaction->name . '_prerequisite ON ' . $reaction->name . '_prerequisite.eventname IN(' . $this->event_input($reaction->event) . ') AND ' . $reaction->name . '_prerequisite.objectid = ' . $reaction->name . '.objectid  AND ' . $reaction->name . '_prerequisite.timecreated - ' . $reaction->name . '.timecreated > 60 * 60 * 24 * ('.$reaction->days_to_complete.')');
        }
        return implode("\n", $joins);
    }

//    public function render() {
//        global $DB;
//        $reaction = $this->reactions[0];
//        return var_dump($DB->get_records_sql('
//            SELECT {user}.firstname FROM {user}
//                SELECT * FROM {logstore_standard_log}
//                WHERE eventname IN('. $this->event_input($reaction->trigger_event) . ');
//            ')). var_dump($this->event_input($reaction->trigger_event));
//    }

    /**
     * Convert a string or array containing event names to a sanitized and quoted format
     * separated by commas fit for the mysql IN function
     * @param $events
     * @return string
     */
    private function event_input($events) {
        if (!is_array($events)) $events = array($events);
        $quoted_events = array_map(function ($event) {
            return '"' . addslashes($event) . '"';
        }, $events);
        return implode(',', $quoted_events);
    }
}