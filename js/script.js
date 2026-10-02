function abrirModal(id){document.getElementById(id)?.classList.add('show')}
function fecharModal(id){document.getElementById(id)?.classList.remove('show')}
function abrirMovimentacao(id,nome){document.getElementById('movProdutoId').value=id;document.getElementById('movProdutoNome').textContent='Produto: '+nome;abrirModal('modalMov')}
function editarProduto(p){
 document.getElementById('produtoModalTitle').textContent='Editar produto';
 document.getElementById('produtoAcao').value='editar';document.getElementById('produtoId').value=p.id;
 document.getElementById('produtoNome').value=p.nome;document.getElementById('produtoCategoria').value=p.categoria||'';
 document.getElementById('produtoPreco').value=p.preco;document.getElementById('produtoMin').value=p.estoque_minimo;
 document.getElementById('estoqueInicial').style.display='none';abrirModal('modalProduto');
}
document.querySelectorAll('.modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('show')}));
setTimeout(()=>document.querySelectorAll('.alert').forEach(a=>{a.style.opacity='0';a.style.transition='.4s';setTimeout(()=>a.remove(),400)}),4000);
