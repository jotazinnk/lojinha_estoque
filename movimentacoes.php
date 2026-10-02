<?php
require_once __DIR__.'/config/database.php';
$msg=$_GET['msg']??'';$erro='';
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['acao']??'')==='movimentar'){
 try{
  $id=(int)$_POST['produto_id'];$tipo=$_POST['tipo'];$q=(int)$_POST['quantidade'];$obs=trim($_POST['observacao']??'');
  if(!in_array($tipo,['entrada','saida'])||$q<1) throw new Exception('Dados inválidos.');
  $pdo->beginTransaction();
  $s=$pdo->prepare("SELECT estoque FROM produtos WHERE id=? FOR UPDATE");$s->execute([$id]);$p=$s->fetch();
  if(!$p) throw new Exception('Produto não encontrado.');
  if($tipo==='saida' && $q>$p['estoque']) throw new Exception('A saída é maior que o estoque disponível.');
  $novo=$tipo==='entrada'?$p['estoque']+$q:$p['estoque']-$q;
  $pdo->prepare("UPDATE produtos SET estoque=? WHERE id=?")->execute([$novo,$id]);
  $pdo->prepare("INSERT INTO movimentacoes(produto_id,tipo,quantidade,observacao) VALUES(?,?,?,?)")->execute([$id,$tipo,$q,$obs]);
  $pdo->commit();header('Location: movimentacoes.php?msg=Movimentação registrada!');exit;
 }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$erro=$e->getMessage();}
}
$mov=$pdo->query("SELECT m.*,p.nome FROM movimentacoes m JOIN produtos p ON p.id=m.produto_id ORDER BY m.id DESC")->fetchAll();
$produtos=$pdo->query("SELECT id,nome,estoque FROM produtos ORDER BY nome")->fetchAll();
$titulo='Movimentações';require 'partials/header.php';
?>
<?php if($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?><?php if($erro): ?><div class="alert danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<div class="section-head page-head"><div><h1>Movimentações</h1><p>Registre todas as entradas e saídas.</p></div><button class="btn primary" onclick="abrirModal('modalMov')">+ Nova movimentação</button></div>
<section class="card"><div class="table-wrap"><table><thead><tr><th>Data</th><th>Produto</th><th>Tipo</th><th>Quantidade</th><th>Observação</th></tr></thead><tbody>
<?php foreach($mov as $m): ?><tr><td><?= date('d/m/Y H:i',strtotime($m['criado_em'])) ?></td><td><b><?= htmlspecialchars($m['nome']) ?></b></td><td><span class="badge <?= $m['tipo']==='entrada'?'success':'danger' ?>"><?= ucfirst($m['tipo']) ?></span></td><td><?= $m['quantidade'] ?></td><td><?= htmlspecialchars($m['observacao']?:'—') ?></td></tr><?php endforeach; ?>
<?php if(!$mov): ?><tr><td colspan="5" class="empty">Nenhuma movimentação registrada.</td></tr><?php endif; ?>
</tbody></table></div></section>
<div class="modal" id="modalMov"><div class="modal-box"><div class="modal-head"><h3>Nova movimentação</h3><button onclick="fecharModal('modalMov')">×</button></div>
<form method="post"><input type="hidden" name="acao" value="movimentar"><label>Produto</label><select name="produto_id" required><option value="">Selecione...</option><?php foreach($produtos as $p): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?> — estoque: <?= $p['estoque'] ?></option><?php endforeach; ?></select>
<label>Tipo</label><select name="tipo"><option value="entrada">Entrada</option><option value="saida">Saída</option></select><label>Quantidade</label><input name="quantidade" type="number" min="1" required><label>Observação</label><input name="observacao" placeholder="Ex.: venda, compra, reposição...">
<div class="modal-actions"><button type="button" class="btn" onclick="fecharModal('modalMov')">Cancelar</button><button class="btn primary">Registrar</button></div></form></div></div>
<?php require 'partials/footer.php'; ?>
