# ANSEGTV Theme

Tema WordPress personalizado para a ANSEGTV.

## Requisitos

- WordPress 6.0 ou superior
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx)

## Instalação

1. Faça o backup do banco de dados e arquivos do WordPress
2. Compacte o diretório `ansegtv-theme` em um arquivo ZIP
3. No painel administrativo do WordPress:
   - Vá em Aparência > Temas
   - Clique em "Adicionar novo"
   - Clique em "Enviar tema"
   - Selecione o arquivo ZIP
   - Clique em "Instalar agora"
   - Após a instalação, clique em "Ativar"

## Configuração

1. Configure os menus:
   - Vá em Aparência > Menus
   - Crie e configure o Menu Principal
   - Crie e configure o Menu Rodapé

2. Configure os widgets:
   - Vá em Aparência > Widgets
   - Configure os widgets na Sidebar Principal

3. Configure as opções do tema:
   - Vá em Aparência > Personalizar
   - Configure o logo
   - Configure as cores
   - Configure as fontes
   - Configure as imagens

4. Configure as traduções:
   - Vá em Configurações > Geral
   - Selecione o idioma desejado
   - As traduções serão carregadas automaticamente

## Otimizações

O tema inclui várias otimizações:

1. Minificação de CSS e JavaScript
2. Otimização de imagens
3. Carregamento assíncrono de scripts
4. Cache de consultas
5. Compressão GZIP
6. Headers de segurança

## Segurança

O tema inclui várias medidas de segurança:

1. Sanitização de entrada
2. Validação de formulários
3. Headers de segurança
4. Proteção contra XSS
5. Proteção contra CSRF
6. Limitação de tentativas de login

## Acessibilidade

O tema inclui várias melhorias de acessibilidade:

1. Suporte a ARIA labels
2. Skip links
3. Alto contraste
4. Suporte a RTL
5. Navegação por teclado
6. Textos alternativos

## Internacionalização

O tema inclui suporte completo a internacionalização:

1. Tradução de strings
2. Formatação de data
3. Formatação de moeda
4. Formatação de números
5. Formatação de telefone
6. Formatação de CEP
7. Formatação de CPF/CNPJ

## Testes

Para executar os testes:

1. Instale o PHPUnit
2. Configure o `wp-tests-config.php`
3. Execute os testes:

```bash
cd wp-content/themes/ansegtv-theme
phpunit tests/test-theme.php
```

## Implantação em Produção

1. Prepare o ambiente:
   - Configure o servidor web (Apache/Nginx)
   - Configure o PHP
   - Configure o MySQL
   - Configure o SSL

2. Configure o WordPress:
   - Atualize o `wp-config.php`
   - Configure as constantes de segurança
   - Configure as URLs
   - Configure o banco de dados

3. Configure o tema:
   - Ative o tema
   - Configure os menus
   - Configure os widgets
   - Configure as opções
   - Configure as traduções

4. Configure o cache:
   - Instale e configure um plugin de cache
   - Configure o cache do navegador
   - Configure o cache do servidor

5. Configure o backup:
   - Configure backup automático do banco de dados
   - Configure backup automático dos arquivos
   - Configure backup manual

6. Configure o monitoramento:
   - Configure logs de erro
   - Configure monitoramento de uptime
   - Configure alertas

7. Configure a segurança:
   - Configure o firewall
   - Configure o SSL
   - Configure as senhas
   - Configure as permissões

8. Configure o SEO:
   - Configure as meta tags
   - Configure o sitemap
   - Configure o robots.txt
   - Configure o .htaccess

9. Configure a performance:
   - Configure a minificação
   - Configure a compressão
   - Configure o cache
   - Configure o CDN

10. Configure a manutenção:
    - Configure as atualizações
    - Configure os backups
    - Configure os logs
    - Configure os relatórios

## Suporte

Para suporte, entre em contato:

- Email: suporte@ansegtv.com.br
- Telefone: (11) 99999-9999
- Site: https://ansegtv.com.br/suporte

## Licença

Este tema é licenciado sob a GPL v2 ou posterior.

## Créditos

- Desenvolvido por ANSEGTV
- Baseado no WordPress
- Utiliza Bootstrap
- Utiliza Font Awesome 