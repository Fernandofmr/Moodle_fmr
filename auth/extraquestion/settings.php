<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Admin settings and defaults.
 *
 * @package auth_extraquestion
 * @copyright  2017 Stephen Bourget
 * @author Fernando Montilla Ruiz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_extraquestion/pluginname', '',
        new lang_string('auth_extraquestiondescription', 'auth_extraquestion')));

    
    // Field to introduce a question
    $settings->add(new admin_setting_configtext(
        'auth_extraquestion/pregunta_login', 
        'Pregunta Login', 
        'Pregunta a efectuar al usuario que quiera iniciar sesión.<br><br><br>', 
        ''
    ));

    // Possible answers
    $settings->add(new admin_setting_configtext(
        'auth_extraquestion/opcion_1', 
        'Opción de respuesta', 
        'Opción 1<br><br>', 
        ''
    ));
    $settings->add(new admin_setting_configtext(
        'auth_extraquestion/opcion_2', 
        'Opción de respuesta', 
        'Opción 2<br><br>', 
        ''
    ));
    $settings->add(new admin_setting_configtext(
        'auth_extraquestion/opcion_3', 
        'Opción de respuesta', 
        'Opción 3<br><br>', 
        ''
    ));
    $settings->add(new admin_setting_configtext(
        'auth_extraquestion/opcion_4', 
        'Opción de respuesta', 
        'Opción 4<br><br><br>', 
        ''
    ));
    
    $settings->add(new admin_setting_configtext(
        'auth_extraquestion/opcion_5', 
        'Opción de respuesta', 
        'Opción 5<br><br><br>', 
        ''
    ));
    /*$settings->add(new admin_setting_configtextarea(
        'auth_extraquestion/opcion_respuesta', 
        'Opciones respuestas', 
        'Elige las opciones de respuestas que quieres plantear al usuario.<br>Una opción por línea.<br><br>', 
        ''
    ));*/

    // True option
    $options_answer = array(1, 2, 3, 4, 5);

    $settings->add(new admin_setting_configselect(
        'auth_extraquestion/opcion_correcta', 
        'Opción correcta', 
        'Especifica la opción correcta', 
        0,
        $options_answer
    ));

    // Display locking / mapping of profile fields.
    $authplugin = get_auth_plugin('extraquestion');
    display_auth_lock_options($settings, $authplugin->authtype, $authplugin->userfields,
        get_string('auth_fieldlocks_help', 'auth'), false, false);
}

?>

<!--<script>
    <button class="btn btn-primary" onclick="add_option();">Añadir opción</button>
    function add_option(){
        event.preventDefault();
        document.write('<?php //hello() ?>');
    }
</script>-->

<?php

/*function hello(){

    // Possible answers
    $settings->add(new admin_setting_configtextarea(
        'auth_extraquestion/opcion_respuesta', 
        'Opciones respuestas', 
        'Elige las opciones de respuestas que quieres plantear al usuario.<br>Una opción por línea.<br><br>', 
        ''
    ));

}*/

