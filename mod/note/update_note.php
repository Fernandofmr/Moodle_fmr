<?php

require('../../config.php');
require_once($CFG->dirroot.'/mod/note/lib.php');
//require_once($CFG->dirroot.'/mod/note/locallib.php');
require_once($CFG->libdir.'/completionlib.php');

global $PAGE, $CFG, $DB;

$id = required_param('id', PARAM_INT);
$id_note = required_param('id_note', PARAM_INT);

$course = $DB->get_record('course', ['id' => $COURSE->id]);

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

$url_note = '/moodle_fmr/mod/note/view.php?id=' . $id_note;

$note = $DB->get_record('note_user_notes', ['id' => $id]);

$rows_textarea = 0;
if (strlen($note->content) > 500) {
    $rows_textarea = 30;

}else {
    $rows_textarea = 5;
}

if ($_POST['apunte-textarea']) {
    $apunte_textarea = $_POST['apunte-textarea'];
    $notetitle = $_POST['apunte-titulo'];
    
    $DB->update_record('note_user_notes', ['id' => $id, 'content' => $apunte_textarea, 'notetitle' => $notetitle]);
    ?>

    <div class="alert alert-success">Apuntes actualizados <br> Redirigiendo al historial</div>
    <div class="d-none">
        <div id="url-note" class="url_note"><?php echo $url_note ?></div>
        <a id="btn-url-note" href="<?php echo $url_note ?>" class="btn btn-success">Volver</a>
    </div>

    <script>
        window.onload = function() {
            var url_note = document.getElementById('url-note').innerHTML;
            var btn_url_note = document.getElementById('btn-url-note');
            
            btn_url_note.click();
        }
    </script>

    <?php

}else{

    $form_action = $_SERVER['REQUEST_URI'];
    ?>
    
    <div id="form-new-note" class="form-new-note">
        <form action="<?php echo $form_action ?>" method="POST">
            <input type="hidden" class="form-control" name="notesection-input" value="<?php echo $id ?>">    
            <input type="hidden" class="form-control" name="url-note-input" value="<?php echo $url_note ?>">
        <div class="form-group">
            <label for="apunte-titulo">Título (opcional)</label>
            <input class="form-control" id="apunte-titulo" name="apunte-titulo" value="<?php echo $note->notetitle ?>">
        </div>
        <div class="form-group">
            <label for="apunte-textarea">Apunte seleccionado</label>
            <textarea class="form-control" id="apunte-textarea" name="apunte-textarea" rows="<?php echo $rows_textarea ?>" required><?php echo $note->content; ?></textarea>
            <small>Modifica tus apuntes</small>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar apunte</button>
        <a href="<?php echo $url_note ?>" class="btn btn-secondary">Volver</a>
        </form>
    </div>
    
    <?php
}

echo $OUTPUT->footer();
