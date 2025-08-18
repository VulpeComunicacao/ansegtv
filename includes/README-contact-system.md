# Sistema Centralizado de Informações de Contato - ANSEGTV

## Visão Geral
Sistema criado para centralizar e gerenciar todas as informações de contato da ANSEGTV em um único local, eliminando a necessidade de editar múltiplos arquivos quando houver mudanças.

## Arquivos do Sistema

### `contact-info.php`
- **Localização**: `includes/contact-info.php`
- **Função**: Arquivo central com todas as informações de contato
- **Conteúdo**: Endereço, e-mail, telefone, website

### `update-contact-info.php`
- **Localização**: Raiz do projeto
- **Função**: Script para atualizar automaticamente todas as páginas
- **Uso**: Executar via linha de comando ou navegador

## Como Usar

### 1. Alterar Informações de Contato
Para alterar o endereço, e-mail ou outras informações:

1. Edite apenas o arquivo `includes/contact-info.php`
2. Altere as variáveis no array `$contact_info`
3. Salve o arquivo
4. Todas as páginas serão atualizadas automaticamente

### 2. Incluir em Novas Páginas
Para adicionar o footer de contato em uma nova página:

```php
<?php
// Incluir sistema centralizado
require_once 'includes/contact-info.php';

// Renderizar footer completo
render_contact_footer();
?>
```

### 3. Usar Informações Individuais
Para usar apenas uma informação específica:

```php
<?php
require_once 'includes/contact-info.php';

// Obter apenas o endereço
$endereco = get_contact_address();

// Obter apenas o e-mail
$email = get_contact_email();

// Obter todas as informações
$contato = get_contact_info();
?>
```

## Funções Disponíveis

### `render_contact_footer($show_email = true)`
- Renderiza o footer completo de contato
- Parâmetro `$show_email`: controla se o e-mail deve ser exibido

### `get_contact_address()`
- Retorna apenas o endereço

### `get_contact_email()`
- Retorna apenas o e-mail

### `get_contact_info()`
- Retorna array com todas as informações

## Estrutura de Dados

```php
$contact_info = [
    'address' => 'SAUS, Quadra 1, Bloco M, Edifício Libertas, Sala 303 | 70.070-935 | Brasília - DF',
    'email' => 'diretoria@ansegtv.com.br',
    'phone' => '', // Adicionar se necessário
    'website' => 'https://ansegtv.com.br'
];
```

## Atualização Automática

### Executar o Script de Atualização
```bash
php update-contact-info.php
```

### O que o Script Faz
1. Identifica todas as páginas com footers hardcoded
2. Substitui pelos includes centralizados
3. Ajusta caminhos relativos automaticamente
4. Processa páginas principais e notícias

## Benefícios

### ✅ Manutenção Simplificada
- Editar apenas um arquivo para atualizar todo o site
- Elimina inconsistências de endereços

### ✅ Consistência
- Todas as páginas sempre com informações atualizadas
- Formato padronizado em todo o site

### ✅ Flexibilidade
- Fácil adição de novos campos (telefone, horário, etc.)
- Controle sobre o que exibir em cada página

### ✅ Performance
- Includes PHP são eficientes
- Sem duplicação de código

## Exemplo de Uso

### Página Principal
```php
<?php include_once 'includes/footer.php'; ?>
```

### Página de Notícias
```php
<?php
require_once '../../includes/contact-info.php';
render_contact_footer();
?>
```

### Página com Caminho Diferente
```php
<?php
require_once '../includes/contact-info.php';
render_contact_footer(false); // Sem e-mail
?>
```

## Troubleshooting

### Problema: Include não encontrado
**Solução**: Verificar se o caminho relativo está correto

### Problema: Footer não aparece
**Solução**: Verificar se o arquivo `contact-info.php` existe e tem permissões

### Problema: Erro de sintaxe
**Solução**: Verificar se o PHP está habilitado e se não há conflitos de código

## Manutenção

### Backup
- Sempre fazer backup antes de executar o script de atualização
- O script não faz backup automático

### Teste
- Testar em ambiente de desenvolvimento antes de produção
- Verificar se todas as páginas estão funcionando

### Versionamento
- Commitar as mudanças no Git após atualização
- Manter histórico de alterações de endereço
