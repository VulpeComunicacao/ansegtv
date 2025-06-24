<?php
/**
 * Script para Gerenciar Redirecionamentos 301
 * ANSEGTV - Notícias
 */

require_once __DIR__ . '/redirects.php';

// Verificar se é uma requisição POST (para adicionar/remover redirecionamentos)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $redirects = getNewsRedirects();
    
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                if (isset($_POST['old_url']) && isset($_POST['new_slug'])) {
                    $redirects->addRedirect($_POST['old_url'], $_POST['new_slug']);
                    $message = "Redirecionamento adicionado com sucesso!";
                }
                break;
                
            case 'remove':
                if (isset($_POST['old_url'])) {
                    $redirects->removeRedirect($_POST['old_url']);
                    $message = "Redirecionamento removido com sucesso!";
                }
                break;
        }
    }
}

$redirects = getNewsRedirects();
$all_redirects = $redirects->getAllRedirects();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Redirecionamentos - ANSEGTV</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Gerenciar Redirecionamentos 301</h1>
        <p class="text-muted">ANSEGTV - Sistema de Notícias</p>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Adicionar Novo Redirecionamento</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="add">
                            <div class="form-group">
                                <label>URL Antiga:</label>
                                <input type="text" name="old_url" class="form-control" 
                                       placeholder="ex: noticias/2019/materia-antiga.html" required>
                                <small class="form-text text-muted">Caminho relativo da URL antiga</small>
                            </div>
                            <div class="form-group">
                                <label>Slug Novo:</label>
                                <input type="text" name="new_slug" class="form-control" 
                                       placeholder="ex: materia-antiga" required>
                                <small class="form-text text-muted">Slug da nova URL (sem /noticias/)</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Redirecionamentos Ativos</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($all_redirects)): ?>
                            <p class="text-muted">Nenhum redirecionamento configurado.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>URL Antiga</th>
                                            <th>Slug Novo</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all_redirects as $old_url => $new_slug): ?>
                                            <tr>
                                                <td><code><?php echo htmlspecialchars($old_url); ?></code></td>
                                                <td><code><?php echo htmlspecialchars($new_slug); ?></code></td>
                                                <td>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="action" value="remove">
                                                        <input type="hidden" name="old_url" value="<?php echo htmlspecialchars($old_url); ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('Remover este redirecionamento?')">
                                                            Remover
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h5>Como usar:</h5>
            <ul>
                <li><strong>URL Antiga:</strong> Caminho relativo da URL antiga (ex: <code>noticias/2019/materia.html</code>)</li>
                <li><strong>Slug Novo:</strong> Slug da nova URL no WordPress (ex: <code>materia</code>)</li>
                <li>O sistema automaticamente redirecionará para <code>/noticias/slug/</code></li>
                <li>Redirecionamentos são validados contra a API do WordPress</li>
            </ul>
        </div>
        
        <div class="mt-3">
            <a href="/noticias/" class="btn btn-secondary">Voltar para Notícias</a>
        </div>
    </div>
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html> 