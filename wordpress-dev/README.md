# ANSEGTV - WordPress Development

Este é o ambiente de desenvolvimento WordPress para o site da ANSEGTV.

## Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Node.js 14.x ou superior
- Composer
- WP-CLI

## Configuração do Ambiente

1. Clone este repositório
2. Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente
3. Execute `composer install` para instalar as dependências PHP
4. Execute `npm install` para instalar as dependências Node.js
5. Configure o banco de dados MySQL
6. Execute `wp core install` para instalar o WordPress
7. Ative o tema ANSEGTV: `wp theme activate ansegtv-theme`

## Estrutura do Projeto

```
wordpress-dev/
├── wp-content/
│   ├── themes/
│   │   └── ansegtv-theme/    # Tema personalizado
│   ├── plugins/             # Plugins personalizados
│   └── uploads/            # Mídias
├── wp-config.php           # Configuração do WordPress
└── .env                    # Variáveis de ambiente
```

## Desenvolvimento

1. Para iniciar o servidor de desenvolvimento:
   ```bash
   wp server
   ```

2. Para compilar assets:
   ```bash
   npm run dev    # Desenvolvimento
   npm run build  # Produção
   ```

## Plugins Necessários

- Yoast SEO
- WP Super Cache
- Advanced Custom Fields
- Wordfence
- WPForms

## Deploy

1. Execute `npm run build` para compilar os assets
2. Faça backup do banco de dados
3. Siga as instruções de deploy no ambiente de produção

## Contribuição

1. Crie uma branch para sua feature
2. Faça commit das alterações
3. Crie um Pull Request

## Suporte

Para suporte, entre em contato com a equipe de desenvolvimento. 