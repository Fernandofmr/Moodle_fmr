<?php 

class block_mail_express extends block_base {
    
    public function init() {
        $this->title = get_string( 'mail_express', 'block_mail_express' );
    }

    public function get_content() {
        global $USER, $DB;
        $mensaje_enviado = '';
        $lista_correos = $DB->get_records('user');

        if ($this->content !== null) {
            return $this->content;
        }

        if (isset($_POST['submit'])){
            $para = $_POST['email'];
            $desde = $USER->email;
            $nombre = $USER->username;
            $titulo = 'Correo de prueba';
            $titulo2 = 'Copia del correo de prueba';
            $mensaje = $nombre . " envía lo siguiente: " . "\n\n" . $_POST['mensaje'];

            $cabeceras = "Para: " . $desde;
            $cabeceras2 = "De: " . $para;

            if (mail($desde,$titulo,$mensaje,$cabeceras) && mail($para,$titulo2,$mensaje,$cabeceras2)) {
                $mensaje_enviado = 'true';
            }else {
                $mensaje_enviado = 'false';
            }
        }

        $form_send_email = '<form action="" method="post">
                            <label for="email">Para: </label>
                            <select class="form-control" name="email">
                                        <option hidden>Selecciona un correo</option>';
                                        foreach ($lista_correos as $correo) {
                                            $form_send_email .= '<option value=' . $correo->email . '>' . $correo->username . ': ' . $correo->email . '</option>';
                                        }
        $form_send_email .= '</select>
                            <label for="mensaje" class="mt-3">Mensaje: </label>
                            <textarea rows="5" name="mensaje" cols="30" class="form-control"></textarea><br>
                            <div class="d-flex justify-content-center">
                            <input class="btn btn-primary mt-3" type="submit" name="submit" value="Enviar">
                            </div>
                            </form>';

        $this->content          = new stdClass;
        $this->content->text    = '<h4 class="text-center">Mail Express</h4>';
        
        if($mensaje_enviado == ''){
            $this->content->text .= '<div class="alert alert-primary text-center" role="alert">
                                        Envía un correo desde aquí
                                    </div>';

        }else if ($mensaje_enviado == 'true') {
            $this->content->text .= '<div class="alert alert-success text-center" role="alert">
                                        ¡Correo enviado!
                                    </div>';
        }else if ($mensaje_enviado == 'false'){
            $this->content->text .= '<div class="alert alert-danger text-center" role="alert">
                                        ¡No se ha podido enviar el correo!
                                    </div>';
        }

        $this->content->text   .= $form_send_email;
        $this->content->footer  = '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script><br>
                                    <script src="../blocks/mail_express/js/mail_express.js"></script>';

        return $this->content;
    }

    public function specialization() {
        if (isset($this->config)) {
            $this->title = get_string( 'defaulttitle', 'block_mail_express' );
        }else {
            $this->title = $this->config->title;
        }
    }

    function has_config() {
        return true;
    }

    public function instance_config_save($data, $nolongerused = false) {
        if (get_config('mail_express', 'Allow_HTML') == '1') {
            $data->text = strip_tags($data->text);
        }

        return parent::instance_config_save($data, $nolongerused);
    }

}