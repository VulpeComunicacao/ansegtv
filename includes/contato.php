<section id="contato" class="container">
        <div class="row">
            <div class="col">
                <div class="contato-title">
                    <h2>Contato</h2>
                </div>
                <p class="text-center">Para entrar em contato com a ANSEGTV, por favor, preencha todos os campos abaixo.</p>

             <?php // Check if form was submitted:
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Build POST request:
                        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
                        $recaptcha_secret = '6LcpR8cUAAAAAHh8Pw5WBNl03teQuJN2MiOqK3xq';
                        $recaptcha_response = $_POST['recaptcha_response'];
                        // Make and decode POST request:
                        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
                        $recaptcha = json_decode($recaptcha);
                        if($recaptcha->success==true){
                            // Take action based on the score returned:
                            if ($recaptcha->score >= 0.5) {
                                echo '<pre>';
                                print_r("Verified - send email");
                                echo '</pre>';
                                exit;
                                // Verified - send email
                            } else {
                                echo '<pre>';
                                print_r("Not verified - show form error");
                                echo '</pre>';
                                exit;
                                // Not verified - show form error
                            }
                        }else{ // there is an error /
                            ///  timeout-or-duplicate meaning you are submit the  form
                            echo '<pre>';
                            print_r($recaptcha);
                            echo '</pre>';
                            exit;
                        }
                    }
                 ?>

                <form id="contact-form" class="p-5" action="/envio-home.php" method="post">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
	 		    <input class="form-control" type="text" required="true" name="nome" id="nome" placeholder="Nome">
                            <input class="form-control" type="email" required="true" name="email" id="email" placeholder="E-mail">
                            <select class="form-control" required="true" name="setor" id="setor">
                                <option value="">Assuntos</option>
                                <option value="administrativo">Administrativo</option>
                                <option value="financeiro">Financeiro</option>
                                <option value="institucional">Institucional</option>
                                <option value="juridico">Jurídico</option>
                                <option value="outros">Outros</option>
                            </select>
                            <textarea class="form-control" required="true" name="mensagem" id="mensagem" rows="3" placeholder="Mensagem"></textarea>
                            <input class="btn btn-warning btn-block py-0" type="submit" value="Enviar">
                        </div>
                    </div>
              </form>
             </div>
        </div>
    </div>
</section>


