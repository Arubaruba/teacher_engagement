<?php

require_once('activity.php');

class reaction extends activity {
    /**
     * @var $trigger_event string External action after which reaction becomes necessary
     * @var $days_to_complete int Time in days the user has to react to an external action
     */
    public $trigger_event, $days_to_complete;

    function __construct($name, $days_to_complete, $event, $trigger_event) {
        $this->days_to_complete = $days_to_complete;
        $this->event = $event;
        $this->trigger_event = $trigger_event;
        $this->name = $name;
    }

    /**
     * @return string A dynamically generated label with localization
     */
    function percentage_reactions_within_days_label() {
        return sprintf(
            get_string('percentage_action_within_days', 'report_teacher_engagement'), // the body of the string
            get_string('reaction:' . $this->name, 'report_teacher_engagement'),
            $this->days_to_complete,
            get_string('day:'.(($this->days_to_complete == 1) ? 'singular' : 'plural'), 'report_teacher_engagement'));
    }
}