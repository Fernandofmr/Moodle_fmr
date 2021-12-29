<?php 

require('../../config.php');

global $DB;

$id = required_param('id', PARAM_INT);
$id_note = required_param('id_note', PARAM_INT);

$url_note = '/moodle_fmr/mod/note/view.php?id=' . $id_note;

if (!$note = $DB->get_record('note_user_notes', ['id' => $id])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);

}

$DB->delete_records('note_user_notes', ['id' => $id]);
header('Refresh: 0; url = ' . $url_note);

