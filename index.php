<?php
global $relativePath;
// Definindo as rotas e os arquivos correspondentes
$routes = [
    '/' => 'estrutura.php',
    '/estrutura' => 'estrutura.php',
    '/postes' => 'postes.php',
    '/orcamento' => 'orcamento.php',
    '/material' => 'material.php',
];

// Rotas para controladores
$controllerRoutes = [
    '/estrutura_controller' => 'estrutura_controller.php',
    '/postes_controller' => 'postes_controller.php',
    '/orcamento_controller' => 'orcamento_controller.php',
    '/material_controller' => 'material_controller.php',
];

// Pega a URL atual sem o domínio
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Caminho base do projeto
$basePath = '/listagem_orcamentos2';

// Remove o caminho base da URL para lidar com as rotas corretamente
$relativePath = str_replace($basePath, '', $path);

// Verifica se o caminho relativo está vazio 
if (empty($relativePath) || $relativePath === '/') {
    $relativePath = '/';
}

// Divide a URL em partes e pega o último segmento
$segments = explode('/', trim($relativePath, '/'));
$lastSegment = '/' . end($segments);

$routeFound = false;

// Verifica se a rota existe no array de rotas (views)
if (array_key_exists($lastSegment, $routes)) {
    include 'enviroments/database.php';
    include 'view/parts/sidebar.php';
    
    // Carrega a view correspondente
    include 'view/' . $routes[$lastSegment];
    $routeFound = true; // Rota de view encontrada
}

// Verifica se a rota existe no array de rotas para controladores
if (array_key_exists($lastSegment, $controllerRoutes)) {
    require_once __DIR__ . '/vendor/autoload.php'; 
    include 'enviroments/database.php'; 
    include 'controller/' . $controllerRoutes[$lastSegment];
    $routeFound = true; 
}

// Se a rota não existir, mostra uma página 404 ou redireciona para home
if (!$routeFound) {
    include 'view/404.php';
}
