<?php
/**
 * Testes do tema ANSEGTV
 */

class ANSEGTV_Theme_Test extends WP_UnitTestCase {
    /**
     * Testa se o tema está ativo
     */
    public function test_theme_is_active() {
        $this->assertEquals('ansegtv-theme', wp_get_theme()->get_template());
    }

    /**
     * Testa se as funções de acessibilidade estão disponíveis
     */
    public function test_accessibility_functions() {
        $this->assertTrue(function_exists('ansegtv_skip_link'));
        $this->assertTrue(function_exists('ansegtv_aria_labels'));
        $this->assertTrue(function_exists('ansegtv_high_contrast_mode'));
    }

    /**
     * Testa se as funções de otimização estão disponíveis
     */
    public function test_optimization_functions() {
        $this->assertTrue(function_exists('ansegtv_dequeue_scripts'));
        $this->assertTrue(function_exists('ansegtv_optimize_scripts'));
        $this->assertTrue(function_exists('ansegtv_optimize_styles'));
        $this->assertTrue(function_exists('ansegtv_optimize_images'));
    }

    /**
     * Testa se as funções de segurança estão disponíveis
     */
    public function test_security_functions() {
        $this->assertTrue(function_exists('ansegtv_security_headers'));
        $this->assertTrue(function_exists('ansegtv_remove_sensitive_info'));
        $this->assertTrue(function_exists('ansegtv_sanitize_input'));
        $this->assertTrue(function_exists('ansegtv_validate_form_data'));
    }

    /**
     * Testa se as funções de internacionalização estão disponíveis
     */
    public function test_i18n_functions() {
        $this->assertTrue(function_exists('ansegtv_i18n_setup'));
        $this->assertTrue(function_exists('ansegtv_rtl_support'));
        $this->assertTrue(function_exists('ansegtv_format_date'));
        $this->assertTrue(function_exists('ansegtv_currency_format'));
    }

    /**
     * Testa formatação de data
     */
    public function test_date_formatting() {
        $date = '2024-03-20';
        $formatted = ansegtv_format_date($date, 'short');
        $this->assertEquals('20/03/2024', $formatted);
    }

    /**
     * Testa formatação de moeda
     */
    public function test_currency_formatting() {
        $amount = 1234.56;
        $formatted = ansegtv_currency_format($amount, 'BRL');
        $this->assertEquals('R$ 1.234,56', $formatted);
    }

    /**
     * Testa formatação de telefone
     */
    public function test_phone_formatting() {
        $phone = '11999999999';
        $formatted = ansegtv_phone_format($phone);
        $this->assertEquals('(11) 99999-9999', $formatted);
    }

    /**
     * Testa formatação de CEP
     */
    public function test_zip_formatting() {
        $zip = '12345678';
        $formatted = ansegtv_zip_format($zip);
        $this->assertEquals('12345-678', $formatted);
    }

    /**
     * Testa formatação de CPF
     */
    public function test_cpf_formatting() {
        $cpf = '12345678901';
        $formatted = ansegtv_document_format($cpf);
        $this->assertEquals('123.456.789-01', $formatted);
    }

    /**
     * Testa formatação de CNPJ
     */
    public function test_cnpj_formatting() {
        $cnpj = '12345678901234';
        $formatted = ansegtv_document_format($cnpj);
        $this->assertEquals('12.345.678/9012-34', $formatted);
    }

    /**
     * Testa sanitização de entrada
     */
    public function test_input_sanitization() {
        $input = '<script>alert("XSS")</script>';
        $sanitized = ansegtv_sanitize_input($input);
        $this->assertEquals('alert("XSS")', $sanitized);
    }

    /**
     * Testa validação de formulário
     */
    public function test_form_validation() {
        $data = array(
            'email' => 'test@example.com',
            'url' => 'https://example.com',
            'textarea' => 'Test content',
            'text' => 'Test text'
        );
        
        $validated = ansegtv_validate_form_data($data);
        
        $this->assertEquals('test@example.com', $validated['email']);
        $this->assertEquals('https://example.com', $validated['url']);
        $this->assertEquals('Test content', $validated['textarea']);
        $this->assertEquals('Test text', $validated['text']);
    }

    /**
     * Testa suporte a RTL
     */
    public function test_rtl_support() {
        $this->assertTrue(current_theme_supports('rtl'));
    }

    /**
     * Testa carregamento de scripts
     */
    public function test_script_loading() {
        global $wp_scripts;
        
        $this->assertTrue(isset($wp_scripts->registered['ansegtv-navigation']));
        $this->assertTrue(isset($wp_scripts->registered['ansegtv-accessibility']));
    }

    /**
     * Testa carregamento de estilos
     */
    public function test_style_loading() {
        global $wp_styles;
        
        $this->assertTrue(isset($wp_styles->registered['ansegtv-style']));
        $this->assertTrue(isset($wp_styles->registered['ansegtv-accessibility']));
    }

    /**
     * Testa suporte a imagens
     */
    public function test_image_support() {
        $this->assertTrue(current_theme_supports('post-thumbnails'));
        
        $sizes = get_intermediate_image_sizes();
        $this->assertContains('ansegtv-featured', $sizes);
        $this->assertContains('ansegtv-thumbnail', $sizes);
    }

    /**
     * Testa suporte a menus
     */
    public function test_menu_support() {
        $locations = get_nav_menu_locations();
        $this->assertArrayHasKey('primary', $locations);
        $this->assertArrayHasKey('footer', $locations);
    }

    /**
     * Testa suporte a widgets
     */
    public function test_widget_support() {
        global $wp_registered_sidebars;
        $this->assertArrayHasKey('sidebar-1', $wp_registered_sidebars);
    }
} 