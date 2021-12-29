<?php 

if (!defined('MOODLE_INTERNAL')) {
        die('Direct access to this script is forbidden.');
}

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/note/lib.php');

class mod_note_mod_form extends moodleform_mod {

    function definition() {
        global $CFG; 

        $mform =& $this->_form;

        $mform->addElement('header',  'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('notename', 'note'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        //$mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maxinumchars', '', 255), 'maxlength', 255, 'client');

        //$this->standard_intro_elements(get_string('description', 'note'));

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }

}