<?php $page=basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html><html lang="pt-BR"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($titulo ?? 'Minha Lojinha') ?></title>
<link rel="stylesheet" href="css/style.css"></head><body>
<div class="app">
<aside class="sidebar" id="sidebar">
<div class="brand"><span>🏪</span><div><strong>Minha Lojinha</strong><small>Controle de estoque</small></div></div>
<nav>
<a class="<?= $page==='index.php'?'active':'' ?>" href="index.php">📊 <span>Dashboard</span></a>
<a class="<?= $page==='produtos.php'?'active':'' ?>" href="produtos.php">📦 <span>Produtos</span></a>
<a class="<?= $page==='movimentacoes.php'?'active':'' ?>" href="movimentacoes.php">↕️ <span>Movimentações</span></a>
</nav>
<div class="sidebar-bottom"><div class="user-mini">🏠 Sistema local</div></div>
</aside>
<main class="main"><header class="topbar"><button class="menu-btn" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
<div><h2><?= htmlspecialchars($titulo ?? 'Dashboard') ?></h2><span class="muted"><?= date('d/m/Y') ?></span></div>
<div class="top-user">Minha Lojinha 🛍️</div></header><div class="content">
