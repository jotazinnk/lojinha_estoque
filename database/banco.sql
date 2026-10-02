CREATE DATABASE IF NOT EXISTS lojinha CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lojinha;
CREATE TABLE IF NOT EXISTS produtos(
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL,
 categoria VARCHAR(100) DEFAULT NULL,
 preco DECIMAL(10,2) NOT NULL DEFAULT 0,
 estoque INT NOT NULL DEFAULT 0,
 estoque_minimo INT NOT NULL DEFAULT 5,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS movimentacoes(
 id INT AUTO_INCREMENT PRIMARY KEY,
 produto_id INT NOT NULL,
 tipo ENUM('entrada','saida') NOT NULL,
 quantidade INT NOT NULL,
 observacao VARCHAR(255) DEFAULT NULL,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_mov_produto FOREIGN KEY(produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);
INSERT INTO produtos(nome,categoria,preco,estoque,estoque_minimo)
SELECT 'Camiseta Básica','Roupas',39.90,12,5
WHERE NOT EXISTS(SELECT 1 FROM produtos WHERE nome='Camiseta Básica');
INSERT INTO produtos(nome,categoria,preco,estoque,estoque_minimo)
SELECT 'Boné','Acessórios',29.90,3,5
WHERE NOT EXISTS(SELECT 1 FROM produtos WHERE nome='Boné');
