<?php


require_once('./../../config.php');
require_once('activity/action.php');
require_once('activity/reaction.php');

define('MOODLE_ICONS', $module_icons);

class progress_report {
    /**
     * @var $target_users array(int)
     * @var $actions array(activity/action)
     * @var $reactions array(activity/reaction)
     */
    private $target_users, $actions, $reactions;
    private $icons;

    function __construct($target_users, $actions, $reactions) {
        $this->target_users = $target_users;
        $this->actions = $actions;
        $this->reactions = $reactions;

        // Load icon urls
        $fake_course = json_decode(json_encode(array('id' => 1))); // needs to be an object
        $modules = get_module_metadata($fake_course, get_module_types_names(), null);
        $this->icons = array();
        foreach($modules as $key => $value) $this->icons[$key] = $modules[$key]->icon;
    }

    public function render() {
        global $DB;
        $reaction = null;
        foreach($this->reactions as &$_reaction) {
            $reaction = $_reaction;
        }
        return var_dump($DB->get_records_sql('
                SELECT * FROM {logstore_standard_log}
                WHERE eventname = ;
            ', array($reaction->trigger_event)));
    }
}