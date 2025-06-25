#!/bin/bash

# Script de Configuração do Cron Job - ANSEGTV
# Configura conversão automática de imagens

echo "=== Configuração do Cron Job - ANSEGTV ==="
echo ""

# Verificar se estamos no diretório correto
if [ ! -f "image-converter.php" ]; then
    echo "ERRO: Execute este script no diretório noticias/"
    exit 1
fi

# Obter caminho absoluto
SCRIPT_PATH=$(pwd)/auto-convert.php
echo "Caminho do script: $SCRIPT_PATH"

# Verificar se o script existe
if [ ! -f "auto-convert.php" ]; then
    echo "ERRO: Script auto-convert.php não encontrado"
    exit 1
fi

# Verificar permissões
if [ ! -r "auto-convert.php" ]; then
    echo "ERRO: Sem permissão de leitura no script"
    exit 1
fi

# Criar entrada do cron
CRON_JOB="0 2 * * * php $SCRIPT_PATH"

echo ""
echo "Entrada do cron job:"
echo "$CRON_JOB"
echo ""

# Perguntar se deseja adicionar ao cron
read -p "Deseja adicionar este cron job? (y/n): " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    # Adicionar ao crontab
    (crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -
    
    if [ $? -eq 0 ]; then
        echo "✅ Cron job adicionado com sucesso!"
        echo ""
        echo "O script será executado diariamente às 2:00 da manhã"
        echo ""
        echo "Para verificar o cron job:"
        echo "crontab -l"
        echo ""
        echo "Para remover o cron job:"
        echo "crontab -e"
    else
        echo "❌ Erro ao adicionar cron job"
        exit 1
    fi
else
    echo "Cron job não foi adicionado"
    echo ""
    echo "Para adicionar manualmente, execute:"
    echo "crontab -e"
    echo "E adicione a linha:"
    echo "$CRON_JOB"
fi

echo ""
echo "=== Configuração Concluída ==="
echo ""
echo "Próximos passos:"
echo "1. Teste o script manualmente: php auto-convert.php"
echo "2. Verifique os logs em logs/conversion.log"
echo "3. Acesse /noticias/convert-images.php para interface web"
echo ""
echo "Documentação completa: README-WEBP.md" 