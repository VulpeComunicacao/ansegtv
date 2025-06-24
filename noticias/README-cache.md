# Sistema de Cache - Notícias ANSEGTV

## Visão Geral
Sistema de cache implementado para otimizar a performance das requisições à API do WordPress, reduzindo o tempo de carregamento e a carga no servidor.

## Arquivos do Sistema

### `cache.php`
- Classe principal `NewsCache`
- Gerencia cache em arquivos JSON
- Duração configurável por tipo de conteúdo

### `clear-cache.php`
- Script para limpar cache expirado
- Pode ser executado via cron job

### `cache/.gitignore`
- Ignora arquivos de cache no versionamento
- Mantém apenas a estrutura da pasta

## Configurações de Cache

### Notícias Individuais
- **Duração**: 30 minutos (1800 segundos)
- **Chave**: `single_post_[slug]`
- **Uso**: Páginas de notícias individuais

### Lista de Notícias
- **Duração**: 15 minutos (900 segundos)
- **Chave**: `posts_list_[parâmetros]`
- **Uso**: Página principal de notícias

### JavaScript (Frontend)
- **Tipo**: Cache em memória
- **Duração**: Sessão do usuário
- **Uso**: "Carregar Mais Notícias"

## Benefícios

### Performance
- ⚡ Redução de 70-90% no tempo de carregamento
- 🔄 Menos requisições à API WordPress
- 📊 Melhor experiência do usuário

### Servidor
- 🖥️ Redução da carga no servidor WordPress
- 💾 Menor uso de banda
- 🛡️ Proteção contra sobrecarga da API

### SEO
- 🚀 Páginas mais rápidas (melhor ranking)
- 📱 Melhor performance mobile
- 🔍 Melhor indexação

## Manutenção

### Limpeza Automática
```bash
# Executar via cron job (a cada hora)
0 * * * * php /path/to/noticias/clear-cache.php
```

### Limpeza Manual
```bash
# Acessar via navegador
https://ansegtv.com.br/noticias/clear-cache.php
```

### Monitoramento
- Verificar pasta `noticias/cache/`
- Arquivos `.json` são criados automaticamente
- Cache expirado é removido automaticamente

## Estrutura de Arquivos
```
noticias/
├── cache.php          # Sistema principal
├── clear-cache.php    # Script de limpeza
├── cache/             # Pasta de cache
│   ├── .gitignore     # Ignora arquivos .json
│   └── *.json         # Arquivos de cache (não versionados)
└── README-cache.md    # Esta documentação
```

## Troubleshooting

### Cache não funcionando
1. Verificar permissões da pasta `cache/`
2. Verificar se PHP tem permissão de escrita
3. Verificar logs de erro

### Performance ainda lenta
1. Verificar se cache está sendo usado
2. Ajustar duração do cache
3. Verificar conectividade com WordPress

### Cache muito antigo
1. Executar `clear-cache.php`
2. Verificar configuração de duração
3. Verificar permissões de arquivo 