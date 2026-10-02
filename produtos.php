<?php
require_once __DIR__.'/config/database.php';
$msg=$_GET['msg']??'';$erro='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{
  $acao=$_POST['acao']??'';
  if($acao==='cadastrar'){
   $nome=trim($_POST['nome']);$categoria=trim($_POST['categoria']??'');
   $preco=(float)str_replace(',','.',$_POST['preco']);$estoque=max(0,(int)$_POST['estoque']);$min=max(0,(int)$_POST['estoque_minimo']);
   if(!$nome) throw new Exception('Informe o nome do produto.');
   $s=$pdo->prepare("INSERT INTO produtos(nome,categoria,preco,estoque,estoque_minimo) VALUES(?,?,?,?,?)");
   $s->execute([$nome,$categoria,$preco,$estoque,$min]);
   header('Location: produtos.php?msg=Produto cadastrado com sucesso!');exit;
  }
  if($acao==='editar'){
   $s=$pdo->prepare("UPDATE produtos SET nome=?,categoria=?,preco=?,estoque_minimo=? WHERE id=?");
   $s->execute([trim($_POST['nome']),trim($_POST['categoria']),str_replace(',','.',$_POST['preco']),max(0,(int)$_POST['estoque_minimo']),(int)$_POST['id']]);
   header('Location: produtos.php?msg=Produto atualizado!');exit;
  }
  if($acao==='excluir'){
   $pdo->prepare("DELETE FROM produtos WHERE id=?")->execute([(int)$_POST['id']]);
   header('Location: produtos.php?msg=Produto excluído!');exit;
  }
 }catch(Throwable $e){$erro=$e->getMessage();}
}
$busca=trim($_GET['busca']??'');
if($busca){$s=$pdo->prepare("SELECT * FROM produtos WHERE nome LIKE ? OR categoria LIKE ? ORDER BY id DESC");$like="%$busca%";$s->execute([$like,$like]);$produtos=$s->fetchAll();}
else $produtos=$pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();
$titulo='Produtos';require 'partials/header.php';
?>
<?php if($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if($erro): ?><div class="alert danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<div class="section-head page-head"><div><h1>Produtos</h1><p>Cadastre e gerencie os produtos da sua loja.</p></div><button class="btn primary" onclick="abrirModal('modalProduto')">+ Novo produto</button></div>
<form class="searchbar" method="get"><input name="busca" value="<?= htmlspecialchars($busca) ?>" placeholder="🔎 Buscar produto ou categoria..."><button class="btn">Buscar</button><?php if($busca): ?><a class="btn" href="produtos.php">Limpar</a><?php endif; ?></form>
<section class="card"><div class="table-wrap"><table><thead><tr><th>Produto</th><th>Categoria</th><th>Preço</th><th>Estoque</th><th>Mínimo</th><th>Status</th><th>Ações</th></tr></thead><tbody>
<?php foreach($produtos as $p): ?><tr>
<td><b><?= htmlspecialchars($p['nome']) ?></b></td><td><?= htmlspecialchars($p['categoria']?:'—') ?></td><td>R$ <?= number_format($p['preco'],2,',','.') ?></td><td><b><?= $p['estoque'] ?></b></td><td><?= $p['estoque_minimo'] ?></td>
<td><?= $p['estoque']<=$p['estoque_minimo']?'<span class="badge danger">Baixo</span>':'<span class="badge success">Normal</span>' ?></td>
<td class="actions"><button class="btn small" onclick='editarProduto(<?= json_encode($p,JSON_UNESCAPED_UNICODE) ?>)'>✏️ Editar</button><button class="btn small" onclick='abrirMovimentacao(<?= $p["id"] ?>,<?= json_encode($p["nome"],JSON_UNESCAPED_UNICODE) ?>)'>↕️ Estoque</button>
<form method="post" onsubmit="return confirm('Excluir este produto?')"><input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="btn small danger-btn">Excluir</button></form></td>
</tr><?php endforeach; ?>
<?php if(!$produtos): ?><tr><td colspan="7" class="empty">Nenhum produto encontrado.</td></tr><?php endif; ?>
</tbody></table></div></section>

<div class="modal" id="modalProduto"><div class="modal-box"><div class="modal-head"><h3 id="produtoModalTitle">Novo produto</h3><button onclick="fecharModal('modalProduto')">×</button></div>
<form method="post" id="produtoForm"><input type="hidden" name="acao" id="produtoAcao" value="cadastrar"><input type="hidden" name="id" id="produtoId">
<label>Nome</label><input name="nome" id="produtoNome" required><label>Categoria</label><input name="categoria" id="produtoCategoria" placeholder="Ex.: Roupas">
<div class="form-grid"><div><label>Preço (R$)</label><input name="preco" id="produtoPreco" type="number" min="0" step="0.01" value="0"></div><div><label>Estoque mínimo</label><input name="estoque_minimo" id="produtoMin" type="number" min="0" value="5"></div></div>
<div id="estoqueInicial"><label>Estoque inicial</label><input name="estoque" id="produtoEstoque" type="number" min="0" value="0"></div>
<div class="modal-actions"><button type="button" class="btn" onclick="fecharModal('modalProduto')">Cancelar</button><button class="btn primary">Salvar</button></div></form></div></div>

<div class="modal" id="modalMov"><div class="modal-box"><div class="modal-head"><h3>Movimentar estoque</h3><button onclick="fecharModal('modalMov')">×</button></div>
<form method="post" action="movimentacoes.php"><input type="hidden" name="acao" value="movimentar"><input type="hidden" name="produto_id" id="movProdutoId">
<p id="movProdutoNome" class="selected-product"></p><label>Tipo</label><select name="tipo"><option value="entrada">Entrada</option><option value="saida">Saída</option></select>
<label>Quantidade</label><input name="quantidade" type="number" min="1" required><label>Observação</label><input name="observacao" placeholder="Ex.: venda ou compra">
<div class="modal-actions"><button type="button" class="btn" onclick="fecharModal('modalMov')">Cancelar</button><button class="btn primary">Registrar</button></div></form></div></div>
<?php require 'partials/footer.php'; ?>
