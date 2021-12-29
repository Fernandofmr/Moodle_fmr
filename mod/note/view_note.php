<?php 

require('../../config.php');
require_once($CFG->dirroot.'/mod/note/lib.php');
require_once($CFG->libdir.'/completionlib.php');

global $PAGE, $CFG, $DB;

$id = required_param('id', PARAM_INT);
$id_note = required_param('id_note', PARAM_INT);

$course = $DB->get_record('course', ['id' => $COURSE->id]);
$note = $DB->get_record('note_user_notes', ['id' => $id]);
$url_note = '/moodle_fmr/mod/note/view.php?id=' . $id_note;
$url_update = '/moodle_fmr/mod/note/update_note.php?id=' . $id . '&id_note=' . $id_note;

$course_section = $DB->get_record('note', ['id' => $note->noteid]);
$course_section_id = $course_section->coursesectionid;
$module = '';
$course_sections = $DB->get_records('course_sections');
foreach ($course_sections as $section) {

    $sequence = explode(",", $section->sequence);
    foreach ($sequence as $sec) {
        if ($sec == $course_section_id){
            if ($section->name == null){
                $module = 'Tema: ' . $section->section;
            
            }else {
                $module = $section->name;
            }
        }
    }
}


if (!$course){
    print_error('Course does not exists');
}

if ($course->id == SITEID){
    $context = context_system::instance();

}else {
    $context = context_course::instance($course->id);

}

$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/mod/note/update_note.php');
$PAGE->requires->jquery();


$PAGE->set_title('Moodle Note');
$PAGE->set_heading('Moodle Note');
$PAGE->set_cacheable( true);

echo $OUTPUT->header();
?>

<div id="note_content" class="note_content">
    <div class="card">
        <h5 class="card-header text-center"><?php echo $module ?></h5>
        <div class="card-body">
            <h5 class="card-title"><?php echo $note->notetitle ?></h5>
            <p class="card-text"><?php echo $note->content; ?></p>
            <a href="<?php echo $url_note ?>" class="btn btn-primary">Volver</a>
            <a href="<?php echo $url_update ?>" class="btn btn-secondary">Modificar</a>
        </div>
    </div>
    
</div>

<?php

echo $OUTPUT->footer();

