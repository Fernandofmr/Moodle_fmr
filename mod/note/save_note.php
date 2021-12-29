<?php 

if ($_POST['apunte-textarea']) {
    
    require('../../config.php');

    global $USER, $DB;

    $apunte_textarea = $_POST['apunte-textarea'];
    $notesection_input = intval($_POST['notesection-input']);
    $url_note = $_POST['url-note-input'];
    $notetile = $_POST['apunte-titulo'];

    $apunte = new stdClass();
    $apunte->userid = $USER->id;
    $apunte->noteid = $notesection_input;
    $apunte->notetitle = $notetile;
    $apunte->content = $apunte_textarea;
    $apunte->timemodified = time();

    $DB->insert_record('note_user_notes', $apunte);

    //echo 'Tus apuntes han sido guardados';
    //header('Location: '.$_SERVER['REQUEST_URI']);
    header('Refresh: 0; url = ' . $url_note);

}else {
    ?>
    <script>
        window.onload = function() {
            window.history.back();
        }
    </script>
    <?php
}