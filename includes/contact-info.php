<?php
/**
 * Informações de Contato Centralizadas - ANSEGTV
 * Arquivo central para gerenciar endereço, e-mail e outras informações de contato
 */

// Informações de contato institucional
$contact_info = [
    'address' => 'SAUS, Quadra 1, Bloco M, Edifício Libertas, Sala 303 | 70.070-935 | Brasília - DF',
    'email' => 'diretoria@ansegtv.com.br',
    'phone' => '', // Adicionar se necessário
    'website' => 'https://ansegtv.com.br'
];

// Função para renderizar o footer de contato
function render_contact_footer($show_email = true) {
    global $contact_info;
    ?>
    <section id="footer" class="container">
        <div class="row">
            <div class="col-12 mb-3 contato-institucional text-center">
                <p><strong>Endereço:</strong> <?php echo $contact_info['address']; ?></p>
                <?php if ($show_email): ?>
                    <p><strong>E-mail:</strong> <a href="mailto:<?php echo $contact_info['email']; ?>"><?php echo $contact_info['email']; ?></a></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
}

// Função para obter apenas o endereço
function get_contact_address() {
    global $contact_info;
    return $contact_info['address'];
}

// Função para obter apenas o e-mail
function get_contact_email() {
    global $contact_info;
    return $contact_info['email'];
}

// Função para obter todas as informações de contato
function get_contact_info() {
    global $contact_info;
    return $contact_info;
}
?>
