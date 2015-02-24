<?php

require_once('activity.php');

class action extends activity {
    /**
     * @var $interval_required int How often the target should perform this event in minutes; If null, this action is optional
     */
    public $interval_required;
}