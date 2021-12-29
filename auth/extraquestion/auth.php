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
 * 
 *
 * @package auth_extraquestion
 * @author Martin Dougiamas
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

/**
 * Plugin for no authentication.
 */
class auth_plugin_extraquestion extends auth_plugin_base {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'extraquestion';
        $this->config = get_config('auth_extraquestion');
    }

    /**
     * Old syntax of class constructor. Deprecated in PHP7.
     *
     * @deprecated since Moodle 3.1
     */
    public function auth_plugin_extraquestion() {
        debugging('Use of class name as constructor is deprecated', DEBUG_DEVELOPER);
        self::__construct();
    }

    /**
     *  Login page hook.
     *  Code to add an extra question at login when the user should answer it.
     */
    function loginpage_hook(){
        ?>
        <div id="content-box-modal" style="display:none">
        <div id="error-message" class="text-center"></div>
        <button id="btn-hacer-pregunta" class="btn btn-primary d-none" onclick="hacer_pregunta()">Pregunta login</button>
        <!-- Button trigger modal -->
        <button id="btn-modal-question" type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#modalQuestion"></button>

        <!-- Modal -->
        <div class="modal fade" id="modalQuestion" tabindex="-1" role="dialog" aria-labelledby="modalQuestionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalQuestionLabel"></h5>
                <button id="cierra-modal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                for ($i=1; $i<=5; $i++){
                ?>
                <div class="form-check">
                    <input id="input-answer-<?php echo $i-1 ?>" class="form-check-input" type="radio" name="optionanswer" id="option<?php echo $i ?>" value="">
                    <label id="label-answer-<?php echo $i-1 ?>" class="form-check-label" for="option<?php echo $i ?>">
                        
                    </label>
                </div>
                <?php 
                } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btn-enviar-respuesta" type="button" class="btn btn-primary">Enviar respuesta</button>
            </div>
            </div>
        </div>
        </div>
        </div>
        

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script>
            $(window).on("load", function() {
                document.getElementById('content-box-modal').style.display = 'block';
                $('#error-message').insertBefore('#username');
                $('#btn-hacer-pregunta').insertAfter('#loginbtn');
                $('#btn-modal-question').insertAfter('#btn-hacer-pregunta');
                const BTN_HACER_PREGUNTA = document.getElementById('btn-hacer-pregunta');

                var count_push_btn = 0;
                var loginform = document.getElementById('login');
                loginform.onsubmit = function(event) {
                    if (count_push_btn == 0) {
                        event.preventDefault(); 

                        var username = document.getElementById('username').value;
                        if (username == 'admin'){
                            count_push_btn ++;
                            loginform.submit();
                        }

                        BTN_HACER_PREGUNTA.click();
                    }
                }
            });

            function hacer_pregunta() {
                var resultado = '';
                var url = '../auth/extraquestion/ask_question.php';
                var username = document.getElementById('username').value;
                var password = document.getElementById('password').value;
                password += 'ewgweghst8546948sertthth684964dsafsafdkglkogedhed85787734,+*-/jfajpqwerpo`jmqfpqfp456+456984kgjkgkghkghkg164998416';
                var encrypt = btoa(password);
                
                var data_ajax = new FormData();
                data_ajax.append('username', username);
                data_ajax.append('context', encrypt);

                $.ajax({
                    type: "POST", 
                    url: url, 
                    async: false, 
                    data: data_ajax, 
                    contentType: false, 
                    processData: false, 
                    success: function(text) {
                        resultado = text;
                        resultado = resultado.split('/');
                        
                        if(resultado[0] == 'true'){                            
                            
                            if(resultado[1] != 'extraquestion'){
                                count_push_btn ++;
                                loginform.submit();
                            }else{
                                var respuesta_valida = parseInt(resultado[8]) + 1;
                                showQuestion(resultado[2], resultado[3], resultado[4], resultado[5], resultado[6], resultado[7], respuesta_valida);
                            }

                        }else{
                            count_push_btn ++;
                            loginform.submit();
                        }
                    }
                }).done(function(o){
                    console.log('Solicitando pregunta para el usuario');
                });
            }

            function showQuestion(question, option1, option2, option3, option4, option5, op_correcta){                
                var btn_modal_question = document.getElementById('btn-modal-question');
                document.getElementById('modalQuestionLabel').innerHTML = question;
                const options = [option1, option2, option3, option4, option5];

                for(var i=0; i<options.length; i++){
                    document.getElementById('label-answer-' + i).innerHTML = options[i];
                    document.getElementById('input-answer-' + i).value = options[i];
                }

                btn_modal_question.click();

                $('#btn-enviar-respuesta').on("click", function() {
                    checkAnswer(options, op_correcta);
                })

            }

            function checkAnswer(options, op_correcta){                
                var loginform = document.getElementById('login');
                    var mensaje_error = document.getElementById('error-message');
                for(var i=0; i<options.length; i++){
                    if(document.querySelector('#input-answer-' + i + ':checked') !== null ){
                        var respuesta_usuario = i + 1;
                        break;
                    }
                }
                if(respuesta_usuario == op_correcta){
                    //count_push_btn ++;
                    mensaje_error.style.color = 'green';
                    mensaje_error.innerHTML = 'Respuesta correcta';
                    $('#cierra-modal').click();
                    setTimeout(() => {loginform.submit()}, 1000);
                    
                }else{
                    mensaje_error.style.color = 'red';
                    mensaje_error.innerHTML = 'Respuesta incorrecta';
                    $('#cierra-modal').click();
                }
            }

        </script>
        <?php
    }

    /**
     * Returns true if the username and password work or don't exist and false
     * if the user exists and the password is wrong.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login ($username, $password) {
        global $CFG, $DB;
        if ($user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id))) {
            return validate_internal_user_password($user, $password);
        }
        return true;
    }

    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object
     * @param  string  $newpassword Plaintext password
     * @return boolean result
     *
     */
    function user_update_password($user, $newpassword) {
        $user = get_complete_user_data('id', $user->id);
        // This will also update the stored hash to the latest algorithm
        // if the existing hash is using an out-of-date algorithm (or the
        // legacy md5 algorithm).
        return update_internal_user_password($user, $newpassword);
    }

    function prevent_local_passwords() {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return true;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return true;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return true;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    function can_be_manually_set() {
        return true;
    }

}


