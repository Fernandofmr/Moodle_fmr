<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/note/backup/moodle2/restore_note_stepslib.php'); 

class restore_note_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // label only has one structure step
        $this->add_step(new restore_note_activity_structure_step('note_structure', 'note.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('note', array('intro', 'content'), 'note');

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules() {
        $rules = array();

        $rules[] = new restore_decode_rule('NOTEVIEWBYID', '/mod/note/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('NOTEINDEX', '/mod/note/index.php?id=$1', 'course');

        return $rules;

    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * note logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('note', 'add', 'view.php?id={course_module}', '{note}');
        $rules[] = new restore_log_rule('note', 'update', 'view.php?id={course_module}', '{note}');
        $rules[] = new restore_log_rule('note', 'view', 'view.php?id={course_module}', '{note}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        $rules[] = new restore_log_rule('note', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}