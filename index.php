<?php
require_once __DIR__ . '/config/database.php';

$totalProdutos = (int)$pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$totalUnidades = (int)$pdo->query("SELECT COALESCE(SUM(estoque),0) FROM produtos")->fetchColumn();
$baixoEstoque = (int)$pdo->query("SELECT COUNT(*) FROM produtos WHERE estoque <= estoque_minimo")->fetchColumn();
$valorEstoque = (float)$pdo->query("SELECT COALESCE(SUM(estoque * preco),0) FROM produtos")->fetchColumn();
$alertas = $pdo->query("SELECT * FROM produtos WHERE estoque <= estoque_minimo ORDER BY estoque ASC, nome ASC LIMIT 8")->fetchAll();
$recentes = $pdo->query("SELECT m.*, p.nome FROM movimentacoes m JOIN produtos p ON p.id=m.produto_id ORDER BY m.id DESC LIMIT 8")->fetchAll();
$titulo='Dashboard';
require __DIR__ . '/partials/header.php';
?>
<div class="cards">
<div class="card stat"><div class="icon blue">📦</div><div><span>Produtos</span><strong><?= $totalProdutos ?></strong></div></div>
<div class="card stat"><div class="icon green">🧮</div><div><span>Unidades em estoque</span><strong><?= $totalUnidades ?></strong></div></div>
<div class="card stat"><div class="icon orange">⚠️</div><div><span>Estoque baixo</span><strong><?= $baixoEstoque ?></strong></div></div>
<div class="card stat"><div class="icon purple">💰</div><div><span>Valor do estoque</span><strong>R$ <?= number_format($valorEstoque,2,',','.') ?></strong></div></div>
</div>
<div class="grid-2">
<section class="card"><div class="section-head"><div><h3>⚠️ Estoque baixo</h3><p>Produtos que precisam de atenção.</p></div><a class="btn small" href="produtos.php">Ver produtos</a></div>
<div class="table-wrap"><table><thead><tr><th>Produto</th><th>Estoque</th><th>Mínimo</th></tr></thead><tbody>
<?php foreach($alertas as $p): ?><tr><td><b><?= htmlspecialchars($p['nome']) ?></b></td><td><span class="badge danger"><?= $p['estoque'] ?></span></td><td><?= $p['estoque_minimo'] ?></td></tr><?php endforeach; ?>
<?php if(!$alertas): ?><tr><td colspan="3" class="empty">Nenhum produto com estoque baixo.</td></tr><?php endif; ?>
</tbody></table></div></section>
<section class="card"><div class="section-head"><div><h3>↕️ Últimas movimentações</h3><p>Entradas e saídas recentes.</p></div><a class="btn small" href="movimentacoes.php">Ver histórico</a></div>
<div class="table-wrap"><table><thead><tr><th>Produto</th><th>Tipo</th><th>Qtd.</th></tr></thead><tbody>
<?php foreach($recentes as $m): ?><tr><td><?= htmlspecialchars($m['nome']) ?></td><td><span class="badge <?= $m['tipo']==='entrada'?'success':'danger' ?>"><?= ucfirst($m['tipo']) ?></span></td><td><?= $m['quantidade'] ?></td></tr><?php endforeach; ?>
<?php if(!$recentes): ?><tr><td colspan="3" class="empty">Nenhuma movimentação registrada.</td></tr><?php endif; ?>
</tbody></table></div></section>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
