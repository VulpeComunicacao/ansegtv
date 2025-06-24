# Sistema de Redirecionamentos 301 - Notícias ANSEGTV

## Visão Geral
Sistema de redirecionamentos 301 implementado para preservar SEO e funcionalidade de URLs antigas de notícias, redirecionando automaticamente para as novas URLs.

## Arquivos do Sistema

### `redirects.php`
- Classe principal `NewsRedirects`
- Gerencia mapeamento de URLs antigas para novas
- Valida redirecionamentos contra API WordPress

### `manage-redirects.php`
- Interface web para gerenciar redirecionamentos
- Adicionar/remover mapeamentos
- Visualizar redirecionamentos ativos

### Integração no `router.php`
- Verificação automática de redirecionamentos
- Execução de 301 antes do roteamento normal

## Como Funciona

### Fluxo de Redirecionamento
1. **Usuário acessa URL antiga**: `/noticias/2019/materia-antiga.html`
2. **Sistema verifica**: Se existe mapeamento para esta URL
3. **Validação**: Confirma se o slug novo existe no WordPress
4. **Redirecionamento 301**: Para `/noticias/materia-antiga/`
5. **Usuário vê**: Página normalmente carregada

### Exemplo Prático
```
URL Antiga: /noticias/2019/seguranca-transporte-valores.html
↓ (Redirecionamento 301)
URL Nova: /noticias/seguranca-transporte-valores/
↓ (Carregamento normal)
Página: Notícia carregada via API WordPress
```

## Benefícios

### SEO
- 🔗 **Preserva autoridade** de links externos
- 📊 **Mantém histórico** de analytics
- 🚀 **Evita penalizações** por URLs quebradas
- 📈 **Transfere PageRank** para novas URLs

### Usuários
- ✅ **Links antigos funcionam** automaticamente
- 🔄 **Redirecionamento transparente**
- 📱 **Funciona em todos os dispositivos**
- ⚡ **Performance mantida**

### Manutenção
- 🛠️ **Interface web** para gerenciar
- 📝 **Logs automáticos** de redirecionamentos
- 🔍 **Validação** contra WordPress
- 🗑️ **Remoção fácil** de redirecionamentos

## Configuração

### Adicionar Redirecionamento
1. Acesse: `https://ansegtv.com.br/noticias/manage-redirects.php`
2. Preencha:
   - **URL Antiga**: `noticias/2019/materia-antiga.html`
   - **Slug Novo**: `materia-antiga`
3. Clique em "Adicionar"

### Editar Mapeamentos Diretamente
```php
// Em redirects.php, adicione ao array $redirects_map:
'noticias/2019/sua-materia.html' => 'sua-materia',
'noticias/2020/outra-materia.php' => 'outra-materia',
```

## Monitoramento

### Logs de Redirecionamento
```bash
# Verificar logs do servidor para redirecionamentos
grep "301 Redirect" /path/to/error.log
```

### Analytics
- Redirecionamentos aparecem como "301 Moved Permanently"
- URLs de destino recebem o tráfego
- Histórico preservado para análise

## Troubleshooting

### Redirecionamento não funciona
1. Verificar se o slug novo existe no WordPress
2. Confirmar formato da URL antiga no mapeamento
3. Verificar logs de erro do servidor

### Loop de redirecionamento
1. Verificar se URL antiga e nova são diferentes
2. Confirmar que slug novo não está mapeado para outra URL
3. Limpar cache se necessário

### Performance lenta
1. Verificar se validação da API está funcionando
2. Considerar aumentar timeout da validação
3. Verificar conectividade com WordPress

## Estrutura de Arquivos
```
noticias/
├── redirects.php           # Sistema principal
├── manage-redirects.php    # Interface de gerenciamento
├── README-redirects.md     # Esta documentação
└── router.php             # Integração (modificado)
```

## Segurança

### Validação
- ✅ Redirecionamentos validados contra API WordPress
- ✅ Prevenção de redirecionamentos maliciosos
- ✅ Logs de todas as operações

### Acesso
- 🔒 Interface de gerenciamento pode ser protegida
- 📝 Logs de todas as modificações
- 🛡️ Validação de entrada de dados

## Próximos Passos

### Otimizações Futuras
- 📊 Dashboard de analytics de redirecionamentos
- 🔄 Sincronização automática com WordPress
- 📈 Relatórios de performance
- 🗂️ Backup automático de mapeamentos 