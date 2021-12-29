<?php 

defined('MOODLE_INTERNAL') || die;

function note_add_instance($data, $mform = null) {
    global $DB;

    $data->courseid = $data->course;
    $data->coursesectionid = $data->coursemodule;
    //$data->description = $data->intro;
    $data->timemodified = time();

    $returnid= $DB->insert_record('note', $data);

    $completiontimeexpected = !empty($data->completionexpected) ? $data->completionexpected : null;
    \core_completion\api::update_completion_date_event($data->coursemodule, 'note', $id, $completiontimeexpected);

    return $returnid;
}


function note_update_instance($data, $mform) {
    global $DB;

    $data->timemodified = time();
    $data->id = $data->instance;

    $DB->update_record('note', $data);

    $note = $DB->get_record('note', array('id' => $data->id));    

    $completiontimeexpected = !empty($data->completionexpected) ? $data->completionexpected : null;
    \core_completion\api::update_completion_date_event($data->coursemodule, 'note', $note->id, $completiontimeexpected);

    return true;
}


function note_delete_instance($id) {
    global $DB;

    if (!$note = $DB->get_record('note', array('id'=>$id))) {
        return false;
    }

    $cm = get_coursemodule_from_instance('note', $id);
    \core_completion\api::update_completion_date_event($cm->id, 'note', $id, null);

    $DB->delete_records('note', array('id'=>$note->id));

    return true;
}