<?php

require_once('activity.php');

class reaction extends activity {
    /**
     * @var $trigger_event string External action after which reaction becomes necessary
     * @var $time_to_complete int Time in seconds the user has to react to an external action
     */
    public $trigger_event, $time_to_complete;

    function __construct($time_to_complete, $event, $trigger_event) {
        $this->time_to_complete = $time_to_complete;
        $this->event = $event;
        $this->trigger_event = $trigger_event;
    }
}