<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- CSS del Sidebar y Topbar (SIEMPRE PRIMERO) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Utils/Sidebar.css">
    
    <!-- Helpers -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Utils/Helpers.css">
    
    <!-- CSS específico de la página (Home) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Home/Home.css">
    
    <title><?= $title ?? 'Cotizador' ?></title>
</head>
<body>