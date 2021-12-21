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
 * Code to ask a question.
 *
 * @package auth_extraquestion
 * @author Fernando Montilla Ruiz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

require(__DIR__ . '/../../config.php');
require(__DIR__ . '/../../config-dist.php');

 global $DB, $CFG;


 if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = $_POST['username'];
    $password = $_POST['context'];
    $decrypt = base64_decode($password);
    $password = explode('ewgweghst8546948sertthth684964dsafsafdkglkogedhed85787734,+*-/jfajpqwerpo`jmqfpqfp456+456984kgjkgkghkghkg164998416', $decrypt);
    $password = $password[0];


    $data_user = $DB->get_record('user', ['username' => $user]);

    $validate_user = validate_internal_user_password($data_user, $password);

    if ($validate_user){
       $validate_user = 'true';
    }else{
       $validate_user = 'false';
    }

    
    $auth_method = $data_user->auth;

   
    $options = array();
    if($auth_method == 'extraquestion'){
       $question_db = $DB->get_records_sql('SELECT * FROM mdl_config_log cl WHERE cl.plugin="auth_extraquestion" 
                                             AND cl.name="pregunta_login";');
       $question = end($question_db);

       for ($i=1; $i<=5; $i++){
         $statement = $DB->get_records_sql('SELECT * FROM mdl_config_log cl WHERE cl.plugin="auth_extraquestion" 
                                             AND cl.name="opcion_' . $i . '";');
         $options[$i] = end($statement);
       }

       $correct_option_db = $DB->get_records_sql('SELECT * FROM mdl_config_log cl WHERE cl.plugin="auth_extraquestion" 
                                             AND cl.name="opcion_correcta";');
       $correct_option = end($correct_option_db);
    }

    $send = $validate_user . '/' . $auth_method . '/' . $question->value;

    foreach ($options as $option) {
       $send .= '/' . $option->value;
    }

    $send .= '/' . $correct_option->value;

    echo $send; 

 }