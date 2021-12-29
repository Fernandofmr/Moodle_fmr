<?php

require('../../config.php');
require_once($CFG->dirroot.'/mod/note/lib.php');
//require_once($CFG->dirroot.'/mod/note/locallib.php');
require_once($CFG->libdir.'/completionlib.php');

global $PAGE, $CFG;

$id_note = required_param('id', PARAM_INT);

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
$PAGE->set_url('/mod/note/view.php');
$PAGE->requires->jquery();


$PAGE->set_title('Moodle Note');
$PAGE->set_heading('Moodle Note');
$PAGE->set_cacheable( true);

echo $OUTPUT->header();

$module = '';
$url_note = $_SERVER['REQUEST_URI'];

$course_sections = $DB->get_records('course_sections');
foreach ($course_sections as $section) {

    $sequence = explode(",", $section->sequence);
    foreach ($sequence as $sec) {
        if ($sec == $id_note){
            if ($section->name == null){
                $module = 'Tema: ' . $section->section;
            
            }else {
                $module = $section->name;
            }
        }
    }
}

$noteid = $DB->get_record('note', array('coursesectionid' => $id_note));
$note_id = $noteid->id;

$info_table = array();
$table_headers = array('APUNTE', 'FECHA', 'ACCIÓN');
$info_notes = array();
$notes = $DB->get_records('note_user_notes');
foreach ($notes as $nt) {
    $note = array();
    if ($nt->notetitle !== ''){
        $note['apunte'] = '<a href="view_note.php?id=' . $nt->id . '&id_note=' . $id_note . '">' . $nt->notetitle . '</a>';
    }else {
        $note['apunte'] = '<a href="view_note.php?id=' . $nt->id . '&id_note=' . $id_note . '">' . substr($nt->content, 0, 20) . '</a>';
    }
    
    $note['fecha'] = date('d/m/y', $nt->timemodified);
    $note['accion'] =  '<a href="delete_note.php?id=' . $nt->id . '&id_note=' . $id_note . '" id="borrar-' . $nt->id . '" class="btn btn-danger d-none">Borrar</a> 
                        <a href="update_note.php?id=' . $nt->id . '&id_note=' . $id_note . '" class="btn btn-warning">Modificar</a>
                        <button onclick="abreModal(' . $nt->id . ')" class="btn btn-danger">Borrar</button>';
                        
    array_push($info_notes, $note);
    ?>

    
    <!-- Button trigger modal -->
    <button id="btn-modal<?php echo $nt->id ?>" type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#modal<?php echo $nt->id ?>">
    </button>

    <!-- Modal -->
    <div class="modal fade" id="modal<?php echo $nt->id ?>" tabindex="-1" role="dialog" aria-labelledby="modal<?php echo $nt->id ?>Title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modal<?php echo $nt->id ?>Title"><?php echo $note['apunte'] ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            ¿Seguro que deseas borrar este apunte?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-danger" onclick="borrarApunte(<?php echo $nt->id ?>)">Borrar</button>
        </div>
        </div>
    </div>
    </div>

    <?php
}

?>

<h3 class="text-center"><?php echo $module ?></h3>
<br><br>

<div id="box-table-notes" class="box-table-notes mt-3">
    <h5>Historial</h5>
    <table class="table table-hover">
    <thead>
        <tr>
            <?php 
            foreach ($table_headers as $t_h){
            ?>
            <th scope="col" class="text-center"><?php echo $t_h ?></th>
            <?php 
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($info_notes as $info) {
        ?>
        <tr>
            <?php 
            foreach ($info as $note){
            ?>            
                <td class="text-center"><?php echo $note ?></td>
            <?php
            }
            ?>
        </tr>
        <?php 
        }
        ?>
    </tbody>
    </table>
</div>
<br><br>
<hr>

<div id="form-new-note" class="form-new-note">
    <form action="save_note.php" method="POST">
        <input type="hidden" class="form-control" name="notesection-input" value="<?php echo $note_id ?>">    
        <input type="hidden" class="form-control" name="url-note-input" value="<?php echo $url_note ?>">
    <div class="form-group">
        <label for="apunte-titulo">Título (opcional)</label>
        <input class="form-control" id="apunte-titulo" name="apunte-titulo">
    </div>
    <div class="form-group">
        <label for="apunte-textarea">Añadir apuntes</label>
        <textarea class="form-control" id="apunte-textarea" name="apunte-textarea" rows="5" required></textarea>
        <small>Escribe aquí tus apuntes</small>
    </div>
    <button type="submit" class="btn btn-primary">Guardar apunte</button>
    </form>
</div>


<script>
    function abreModal(id) {
        document.getElementById('btn-modal' + id).click();
    }

    function borrarApunte(id) {
        document.getElementById('borrar-' + id).click();
    }
</script>

<?php

echo $OUTPUT->footer();
