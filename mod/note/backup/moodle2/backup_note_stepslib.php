<?php 

defined('MOODLE_INTERNAL') || die;

class backup_note_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated
        $note = new backup_nested_element('note', array('id'), array(
            'name', 'intro', 'introformat', 'content', 'contentformat',
            'legacyfiles', 'legacyfileslast', 'display', 'displayoptions',
            'revision', 'timemodified'));

        // Build the tree
        // (love this)

        // Define sources
        $note->set_source_table('note', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations
        // (none)

        // Define file annotations
        $note->annotate_files('mod_note', 'intro', null); // This file areas haven't itemid
        $note->annotate_files('mod_note', 'content', null); // This file areas haven't itemid

        // Return the root element (note), wrapped into standard activity structure
        return $this->prepare_activity_structure($note);
    }
}