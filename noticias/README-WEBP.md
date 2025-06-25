# Sistema de Conversão WebP - ANSEGTV

## Visão Geral

Este sistema converte automaticamente imagens do WordPress (JPG, PNG, GIF) para o formato WebP, que oferece melhor compressão e performance sem perda significativa de qualidade.

## Benefícios do WebP

- **Redução de 25-35% no tamanho** comparado a JPEG/PNG
- **Melhor qualidade** em tamanhos menores
- **Suporte a transparência** como PNG
- **Carregamento mais rápido** das páginas
- **Menor uso de banda** para usuários

## Arquivos do Sistema

### Core
- `image-converter.php` - Classe principal de conversão
- `image-optimizer.php` - Sistema de otimização atualizado
- `convert-images.php` - Interface web para gerenciamento

### Scripts
- `auto-convert.php` - Conversão automática em background
- `logs/conversion.log` - Log de conversões

## Como Funciona

### 1. Conversão Automática
```php
$converter = getImageConverter(85); // Qualidade 85%
$result = $converter->convertFromAPI();
```

### 2. Fallback Inteligente
- Se WebP não for suportado pelo navegador, usa formato original
- Se conversão falhar, mantém imagem original
- Preserva transparência de PNGs

### 3. Estrutura de Diretórios
```
website/wp-content/uploads/
├── 2025/06/imagem.jpg          # Original
└── webp/2025/06/imagem.webp    # WebP convertida
```

## Uso

### Interface Web
Acesse `/noticias/convert-images.php` para:
- Converter imagens da API
- Converter todo diretório
- Limpar WebP antigos
- Verificar status do sistema

### Conversão Manual
```php
require_once 'image-converter.php';

$converter = getImageConverter();
$converter->convertToWebP('/path/to/image.jpg');
```

### Conversão Automática
```bash
# Via cron (recomendado)
0 2 * * * php /path/to/noticias/auto-convert.php

# Via linha de comando
php noticias/auto-convert.php
```

## Configuração

### Qualidade
```php
$converter = getImageConverter(85); // 85% qualidade (padrão)
```

### Formatos Suportados
- JPG/JPEG
- PNG (com transparência)
- GIF

### Requisitos do Servidor
- PHP 7.4+
- Extensão GD
- Suporte a WebP (imagewebp)

## Integração no Frontend

### Tags Otimizadas
```php
// Antes
<img src="imagem.jpg" alt="Descrição">

// Depois (automático)
<picture>
  <source srcset="imagem.webp" type="image/webp">
  <img src="imagem.jpg" alt="Descrição" loading="lazy">
</picture>
```

### JavaScript
```javascript
// Verificar suporte a WebP
function supportsWebP() {
    const elem = document.createElement('canvas');
    return elem.getContext && elem.getContext('2d') 
        ? elem.toDataURL('image/webp').indexOf('data:image/webp') === 0 
        : false;
}
```

## Monitoramento

### Logs
- `logs/conversion.log` - Log detalhado de conversões
- Timestamps e estatísticas
- Erros e avisos

### Métricas
- Número de imagens convertidas
- Taxa de erro
- Economia de espaço
- Tempo de conversão

## Manutenção

### Limpeza Automática
```php
$deleted = $converter->cleanOldWebP();
// Remove WebP de imagens deletadas
```

### Verificação de Integridade
```php
$has_webp = $converter->hasWebPVersion($image_url);
$webp_url = $converter->getWebPUrl($image_url);
```

## Troubleshooting

### Problema: Conversão não funciona
**Solução:** Verificar extensões PHP
```bash
php -m | grep -i gd
php -m | grep -i webp
```

### Problema: WebP não carrega
**Solução:** Verificar permissões
```bash
chmod 755 website/wp-content/uploads/webp/
```

### Problema: Qualidade baixa
**Solução:** Ajustar qualidade
```php
$converter = getImageConverter(90); // Aumentar para 90%
```

## Performance

### Benchmarks Típicos
- **JPEG → WebP:** 30% menor
- **PNG → WebP:** 25% menor
- **Tempo de conversão:** ~0.5s por imagem

### Otimizações
- Cache de conversão
- Conversão incremental
- Limpeza automática
- Lazy loading

## Segurança

- Validação de tipos de arquivo
- Sanitização de caminhos
- Timeout de execução
- Limite de memória

## Suporte

Para problemas ou dúvidas:
1. Verificar logs em `logs/conversion.log`
2. Testar suporte WebP em `/noticias/convert-images.php`
3. Verificar permissões de diretório
4. Consultar documentação PHP GD

## Próximos Passos

- [ ] Conversão AVIF (formato mais novo)
- [ ] Redimensionamento automático
- [ ] CDN integration
- [ ] Métricas avançadas
- [ ] Interface de configuração 