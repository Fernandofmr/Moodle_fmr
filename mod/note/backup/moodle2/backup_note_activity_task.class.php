<?php 


defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/note/backup/moodle2/backup_note_stepslib.php');

class backup_note_activity_task extends backup_activity_task {

    /**
     * No specific settings for this activity
     */
    protected function define_my_settings() {
    }

    /**
     * Defines a backup step to store the instance data in the note.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_note_activity_structure_step('note_structure', 'note.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot,"/");

        // Link to the list of note
        $search="/(".$base."\/mod\/note\/index.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@NOTEINDEX*$2@$', $content);

        // Link to note view by moduleid
        $search="/(".$base."\/mod\/note\/view.php\?id\=)([0-9]+)/";
        $content= preg_replace($search, '$@NOTEVIEWBYID*$2@$', $content);

        return $content;
    }
}