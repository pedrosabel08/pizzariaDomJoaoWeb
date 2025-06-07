-- MySQL Workbench Forward Engineering
SET SQL_SAFE_UPDATES = 0;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema bd_pizzaria
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bd_pizzaria` DEFAULT CHARACTER SET utf8mb4 ;
USE `bd_pizzaria` ;

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`clientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`clientes` (
  `idclientes` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `sobrenome` VARCHAR(45) NOT NULL,
  `telefone` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idclientes`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`endereco` (
  `idendereco` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `logradouro` VARCHAR(45) NOT NULL,
  `numero` INT(11) NOT NULL,
  `complemento` VARCHAR(45) NULL DEFAULT NULL,
  `rua` VARCHAR(45) NOT NULL,
  `bairro` VARCHAR(45) NOT NULL,
  `cidade` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idendereco`),
  INDEX `fk_endereco_cliente1_idx` (`cliente_id` ASC),
  CONSTRAINT `fk_endereco_cliente1`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `bd_pizzaria`.`clientes` (`idclientes`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `bd_pizzaria`.`forma_entrega`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`forma_entrega` (
  `idforma_entrega` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idforma_entrega`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`pizzas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`pizzas` (
  `idpizzas` INT(11) NOT NULL AUTO_INCREMENT,
  `nomePizza` VARCHAR(45) NOT NULL,
  `tipoPizza` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idpizzas`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `bd_pizzaria`.`tamanho`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`tamanho` (
  `idtamanho` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`idtamanho`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`unidademedida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`unidademedida` (
  `idunidadeMedida` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idunidadeMedida`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `bd_pizzaria`.`produtos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`produtos` (
  `idprodutos` INT(11) NOT NULL AUTO_INCREMENT,
  `nomeProduto` VARCHAR(45) NOT NULL,
  `unidadeMedida` INT(11) NOT NULL,
  `tipo_id` INT(11) NOT NULL,
  `quantidade_minima` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idprodutos`),
  INDEX `fk_produtos_unidadeMedida1_idx` (`unidadeMedida` ASC),
  CONSTRAINT `fk_produtos_unidadeMedida1`
    FOREIGN KEY (`unidadeMedida`)
    REFERENCES `bd_pizzaria`.`unidademedida` (`idunidadeMedida`),
      CONSTRAINT `fk_produtos_tipoProduto`
      FOREIGN KEY (`tipo_id`)
  REFERENCES `bd_pizzaria`.`tipo_produtos` (`idtipo_produtos`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `bd_pizzaria`.`pizzas_produtos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`pizzas_produtos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pizza_id` INT(11) NOT NULL,
  `produto_id` INT(11) NOT NULL,
  `quantidade` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (pizza_id, produto_id),
  INDEX `fk_pizza_idx` (`pizza_id` ASC),
  INDEX `fk_produto_idx` (`produto_id` ASC),
  CONSTRAINT `fk_pizza`
    FOREIGN KEY (`pizza_id`)
    REFERENCES `bd_pizzaria`.`pizzas` (`idpizzas`),
  CONSTRAINT `fk_produto`
    FOREIGN KEY (`produto_id`)
    REFERENCES `bd_pizzaria`.`produtos` (`idprodutos`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

CREATE TABLE IF NOT EXISTS estoque_lote (
    idlote INT AUTO_INCREMENT PRIMARY KEY,
    idproduto INT NOT NULL,
    data_validade DATE,
    quantidade DECIMAL(10,2) NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    data_entrada DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idproduto) REFERENCES produtos(idprodutos)
);


-- -----------------------------------------------------
-- Table `bd_pizzaria`.`tipo_produtos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`tipo_produtos` (
  `idtipo_produtos` INT(11) NOT NULL AUTO_INCREMENT,
  `nome_tipo` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idtipo_produtos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- -----------------------------------------------------
-- Table `bd_pizzaria`.`bordas_pizza`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bordas_pizza` (
  `idbordas_pizza` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`idbordas_pizza`)
);

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`formaPagamento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `forma_pagamento` (
  `idforma_pagamento` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idforma_pagamento`)
);

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`vendas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vendas` (
  `idvendas` INT(11) NOT NULL AUTO_INCREMENT,
  `data_venda` DATETIME NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `cliente_id` INT(11) NOT NULL,
  `forma_entrega_id` INT(11) NOT NULL,
  `endereco_id` INT(11) NULL,
  `forma_pagamento_id` INT(11) NULL,
    `status_id` INT(11) NULL,
	`valor_entrega` INT(11) NULL,
`tempo_espera` INT(11) NULL,

  PRIMARY KEY (`idvendas`),
  INDEX `fk_vendas_clientes_idx` (`cliente_id` ASC),
  INDEX `fk_vendas_forma_entrega_idx` (`forma_entrega_id` ASC),
  CONSTRAINT `fk_vendas_clientes`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `clientes` (`idclientes`),
    CONSTRAINT `fk_vendas_forma_entrega`
    FOREIGN KEY (`forma_entrega_id`)
    REFERENCES `forma_entrega` (`idforma_entrega`),
	CONSTRAINT `fk_vendas_endereco`
    FOREIGN KEY (`endereco_id`)
    REFERENCES `endereco` (`idendereco`),
	CONSTRAINT `fk_vendas_forma_pagamento`
    FOREIGN KEY (`forma_pagamento_id`)
    REFERENCES `forma_pagamento` (`idforma_pagamento`),
    foreign key (status_id) references status_venda (idstatus)
    
);

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`vendas_pizzas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vendas_pizzas` (
  `idvendas_pizzas` INT(11) NOT NULL AUTO_INCREMENT,
  `vendas_idvendas` INT(11) NOT NULL,
  `pizzas_idpizzas` INT(11) NOT NULL,
  `tamanho_idtamanho` INT(11) NOT NULL,
  `borda_idbordas_pizza` INT(11) NOT NULL,
  PRIMARY KEY (`idvendas_pizzas`),
  INDEX `fk_vendas_pizzas_vendas_idx` (`vendas_idvendas` ASC),
  INDEX `fk_vendas_pizzas_pizzas_idx` (`pizzas_idpizzas` ASC),
  INDEX `fk_vendas_pizzas_tamanho_idx` (`tamanho_idtamanho` ASC),
  INDEX `fk_vendas_pizzas_borda_idx` (`borda_idbordas_pizza` ASC),
  CONSTRAINT `fk_vendas_pizzas_vendas`
    FOREIGN KEY (`vendas_idvendas`)
    REFERENCES `vendas` (`idvendas`),
  CONSTRAINT `fk_vendas_pizzas_pizzas`
    FOREIGN KEY (`pizzas_idpizzas`)
    REFERENCES `pizzas` (`idpizzas`),
  CONSTRAINT `fk_vendas_pizzas_tamanho`
    FOREIGN KEY (`tamanho_idtamanho`)
    REFERENCES `tamanho` (`idtamanho`),
  CONSTRAINT `fk_vendas_pizzas_borda`
    FOREIGN KEY (`borda_idbordas_pizza`)
    REFERENCES `bordas_pizza` (`idbordas_pizza`)
);

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`marcaBebidas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`marcaBebidas` (
  `idmarcaBebidas` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idmarcaBebidas`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`tamanhoBebidas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`tamanhoBebidas` (
  `idtamanhoBebidas` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `volume` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`idtamanhoBebidas`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`categoriaBebidas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`categoriabebidas` (
  `idcategoriaBebidas` INT(11) NOT NULL,
  `nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idcategoriaBebidas`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`bebidas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`bebidas` (
  `idbebidas` INT(11) NOT NULL AUTO_INCREMENT,
  `marca_id` INT(11) NOT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  `quantidade` DOUBLE NOT NULL,
  `validade` DATE NOT NULL,
  `tamanhobebidas_idtamanhoBebidas` INT(11) NOT NULL,
  `categoriabebidas_idcategoriaBebidas` INT(11) NOT NULL,
  PRIMARY KEY (`idbebidas`),
  CONSTRAINT `fk_bebidas_marcaBebidas`
    FOREIGN KEY (`marca_id`)
    REFERENCES `bd_pizzaria`.`marcabebidas` (`idmarcaBebidas`),
  CONSTRAINT `fk_bebidas_tamanhobebidas1`
    FOREIGN KEY (`tamanhobebidas_idtamanhoBebidas`)
    REFERENCES `bd_pizzaria`.`tamanhobebidas` (`idtamanhoBebidas`),
  CONSTRAINT `fk_bebidas_categoriabebidas1`
    FOREIGN KEY (`categoriabebidas_idcategoriaBebidas`)
    REFERENCES `bd_pizzaria`.`categoriabebidas` (`idcategoriaBebidas`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `bd_pizzaria`.`vendas_bebidas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_pizzaria`.`vendas_bebidas` (
  `idvendas_bebidas` INT(11) NOT NULL AUTO_INCREMENT,
  `vendas_idvendas` INT(11) NOT NULL,
  `bebidas_idbebidas` INT(11) NOT NULL,
  `quantidade` INT(11) NOT NULL,
  PRIMARY KEY (`idvendas_bebidas`),
  INDEX `fk_vendas_bebidas_vendas_idx` (`vendas_idvendas` ASC),
  INDEX `fk_vendas_bebidas_bebidas_idx` (`bebidas_idbebidas` ASC),
  CONSTRAINT `fk_vendas_bebidas_vendas`
    FOREIGN KEY (`vendas_idvendas`)
    REFERENCES `bd_pizzaria`.`vendas` (`idvendas`),
  CONSTRAINT `fk_vendas_bebidas_bebidas`
    FOREIGN KEY (`bebidas_idbebidas`)
    REFERENCES `bd_pizzaria`.`bebidas` (`idbebidas`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


CREATE TABLE IF NOT EXISTS status_venda (
	idstatus INT AUTO_INCREMENT PRIMARY KEY,
    nome_status VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS log_status (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    venda_id INT NOT NULL, -- ID do registro original
    status_anterior VARCHAR(50),
    status_novo VARCHAR(50),
    data_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (venda_id) references vendas (idvendas)
);

CREATE TABLE IF NOT EXISTS  fornecedores_tipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idfornecedor INT NOT NULL,
    idtipo INT NOT NULL,
    FOREIGN KEY (idfornecedor) REFERENCES fornecedores(idfornecedor),
    FOREIGN KEY (idtipo) REFERENCES tipos_produtos(idtipo)
);

-- Tabela de Fornecedores (com dados fictícios)
CREATE TABLE IF NOT EXISTS  fornecedores (
    idfornecedor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cnpj_cpf VARCHAR(20),
    telefone VARCHAR(20),
    email VARCHAR(100),
    endereco TEXT,
    status BOOLEAN DEFAULT TRUE
);

INSERT INTO fornecedores (nome, cnpj_cpf, telefone, email, endereco, status) VALUES
('Laticínios Minas', '12.345.678/0001-99', '(31) 99999-0001', 'contato@minas.com', 'Rua A, Belo Horizonte', TRUE),
('Distribuidora Sul', '23.456.789/0001-88', '(51) 98888-1111', 'vendas@sul.com', 'Av. Central, Porto Alegre', TRUE),
('Alimentos do Norte', '34.567.890/0001-77', '(91) 97777-2222', 'norte@alimentos.com', 'Rua Pará, Belém', TRUE),
('Bebidas Brasil', '45.678.901/0001-66', '(11) 96666-3333', 'suporte@bebidasbr.com', 'Av. Paulista, São Paulo', TRUE),
('Embalagens Flex', '56.789.012/0001-55', '(21) 95555-4444', 'embalagens@flex.com', 'Rua Rio, Rio de Janeiro', TRUE);

INSERT INTO fornecedores_tipos (idfornecedor, idtipo) values
(1, 1),
(1, 2),
(1, 3),
(2, 2),
(2, 4),
(3, 6),
(3, 4),
(3, 5),
(4, 7),
(5, 7);

-- Tabela de Compras
CREATE TABLE IF NOT EXISTS  compras (
    idcompra INT AUTO_INCREMENT PRIMARY KEY,
    idfornecedor INT,
    data_compra DATE,
    valor_total DECIMAL(10,2),
    observacoes TEXT,
    FOREIGN KEY (idfornecedor) REFERENCES fornecedores(idfornecedor)
);

-- Tabela de Itens da Compra
CREATE TABLE IF NOT EXISTS itens_compra (
    iditem INT AUTO_INCREMENT PRIMARY KEY,
    idcompra INT,
    idproduto INT,
    quantidade DECIMAL(10,2),
    preco_unitario DECIMAL(10,2),
    subtotal DECIMAL(10,2),
    FOREIGN KEY (idcompra) REFERENCES compras(idcompra),
    FOREIGN KEY (idproduto) REFERENCES produtos(idprodutos)
);

CREATE TABLE saidas_estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    idlote INT NOT NULL,
    quantidade DECIMAL(10,2) NOT NULL,
    pizza_id INT DEFAULT NULL,
    venda_id INT DEFAULT NULL,
    motivo VARCHAR(100) DEFAULT 'produção',
    data_saida DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (produto_id) REFERENCES produtos(idprodutos),
    FOREIGN KEY (idlote) REFERENCES estoque_lote(idlote),
    FOREIGN KEY (pizza_id) REFERENCES pizzas(idpizzas),
    FOREIGN KEY (venda_id) REFERENCES vendas(idvendas)
);


-- -----------------------------------------------------
-- function inserirTipoProdutos
-- -----------------------------------------------------

DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirTipoProdutos`() RETURNS int(11)
    DETERMINISTIC
BEGIN
INSERT INTO tipo_produtos (idtipo_produtos, nome_tipo) VALUES
(1, 'Molhos'),
(2, 'Temperos'),
(3, 'Laticínios'),
(4, 'Carnes'),
(5, 'Vegetais'),
(6, 'Embutidos'),
(7, 'Outros');
RETURN 1;
END$$

DELIMITER ;


-- -----------------------------------------------------
-- function inserirFormaEntrega
-- -----------------------------------------------------

DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirFormaEntrega`() RETURNS int(11)
    DETERMINISTIC
BEGIN
	INSERT INTO forma_entrega (tipo) VALUES ('Retirada');
	INSERT INTO forma_entrega (tipo) VALUES ('Entrega');

RETURN 1;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function inserirBordaPizza
-- -----------------------------------------------------

DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirBordaPizza`() RETURNS int(11)
    DETERMINISTIC
BEGIN
	INSERT INTO bordas_pizza (nome, preco) VALUES ('Catupiry', 10);
	INSERT INTO bordas_pizza (nome, preco) VALUES ('Cheddar', 10);
	INSERT INTO bordas_pizza (nome, preco) VALUES ('Cream Cheese', 10);
	INSERT INTO bordas_pizza (nome, preco) VALUES ('Chocolate preto', 10);
	INSERT INTO bordas_pizza (nome, preco) VALUES ('Chocolate branco', 10);
	INSERT INTO bordas_pizza (nome, preco) VALUES ('Doce de leite', 10);
	INSERT INTO bordas_pizza (nome, preco) VALUES ('Sem borda', 0);
RETURN 1;
END$$

DELIMITER ;
-- -----------------------------------------------------
-- function inserirPizzas
-- -----------------------------------------------------

DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirPizzas`() RETURNS int(11)
    DETERMINISTIC
BEGIN
SET @id := 0;
SET sql_safe_updates = 0;

UPDATE pizzas SET idpizzas = @id := @id + 1;

INSERT INTO pizzas (nomePizza, tipoPizza) VALUES
('Alho e Óleo', 'salgada'),
('Atum', 'salgada'),
('Bacon', 'salgada'),
('Bacon com Milho', 'salgada'),
('Bolonhesa', 'salgada'),
('Brócolis', 'salgada'),
('Brócolis com Bacon', 'salgada'),
('Calabresa', 'salgada'),
('Chester com Catupiry', 'salgada'),
('Frango', 'salgada'),
('Frango com Catupiry', 'salgada'),
('Lombinho com Catupiry', 'salgada'),
('Marguerita', 'salgada'),
('Milão', 'salgada'),
('Milho', 'salgada'),
('Mussarela', 'salgada'),
('Napolitana', 'salgada'),
('Palmito', 'salgada'),
('Peito de Peru', 'salgada'),
('Portuguesa', 'salgada'),
('Quatro Queijos', 'salgada'),
('Romana', 'salgada'),
('Brócolis com Palmito', 'salgada'),
('Vegetariana', 'salgada'),
('Frango Caipira', 'salgada'),
('Dom Napoli', 'salgada'),
('Calabresa Acebolada', 'salgada'),
('Baiana', 'salgada'),
('Coração', 'salgada'),
('Coração Alho e Óleo', 'salgada'),
('Frango Especial', 'salgada'),
('Gaúcha', 'salgada'),
('Linguiça Blumenau', 'salgada'),
('Mexicana', 'salgada'),
('Dom João', 'salgada'),
('Pepperoni', 'salgada'),
('Tomate Seco com Rúcula', 'salgada'),
('Salame Italiano', 'salgada'),
('Do Cheff', 'salgada'),
('Strogonoff de Carne', 'salgada'),
('Strogonoff de Frango', 'salgada'),
('Magnifica', 'salgada'),
('Pepperoni Especial', 'salgada'),
('Moda da Casa', 'salgada'),
('Moda Pizzaiolo', 'salgada'),
('Peru Light', 'salgada'),
('Alemã', 'salgada'),
('Pepper Brie', 'salgada'),
('Camarão', 'salgada'),
('Mignon', 'salgada'),
('Mignon Alho e Óleo', 'salgada'),
('Mignon com Cheddar', 'salgada'),
('Parmegiana', 'salgada'),
('Seis Queijos', 'salgada'),
('Carne Seca', 'salgada'),
('Costelinha ao Barbecue', 'salgada'),
('Gorgonzola Premium', 'salgada'),
('Gorgonzola Especial', 'salgada'),
('Mignon Four Cheese', 'salgada'),
('Sensação Preto', 'doce'),
('Banana Nevada', 'doce'),
('Brigadeiro', 'doce'),
('Chocolate Branco', 'doce'),
('Chocolate Preto', 'doce'),
('Confete', 'doce'),
('Prestígio', 'doce'),
('Romeu e Julieta', 'doce'),
('Sensação Branco', 'doce'),
('Sonho de Valsa', 'doce'),
('Capuccino', 'doce'),
('Banoffe', 'doce'),
('Charge', 'doce'),
('Beijinho', 'doce'),
('Dois Amores', 'doce');

INSERT INTO pizzas_produtos (id, pizza_id, produto_id, quantidade) VALUES
# id, pizza_id, produto_id, quantidade
('2065','1', '1', '100'),
('2066', '1', '6', '100'),
('2067', '1', '5', '10'),
('2068', '1', '2', '30'),
('2069', '1', '4', '30'),
('2070', '1', '3', '20'),
('2071', '2', '1', '100'),
('2072', '2', '6', '100'),
('2073', '2', '5', '10'),
('2074', '2', '7', '100'),
('2075', '2', '8', '30'),
('2076', '3', '1', '100'),
('2077', '3', '6', '100'),
('2078', '3', '5', '10'),
('2079', '3', '9', '100'),
('2080', '4', '1', '100'),
('2081', '4', '6', '100'),
('2082', '4', '5', '10'),
('2083', '4', '9', '100'),
('2084', '4', '10', '30'),
('2085', '5', '1', '100'),
('2086', '5', '6', '100'),
('2087', '5', '5', '10'),
('2088', '5', '11', '100'),
('2089', '6', '1', '100'),
('2090', '6', '6', '100'),
('2091', '6', '5', '10'),
('2092', '6', '12', '100'),
('2093', '7', '1', '100'),
('2094', '7', '6', '100'),
('2095', '7', '5', '10'),
('2096', '7', '12', '100'),
('2097', '7', '9', '50'),
('2102', '8', '1', '100'),
('2103', '8', '6', '100'),
('2104', '8', '5', '10'),
('2105', '8', '13', '100'),
('2106', '9', '1', '100'),
('2107', '9', '6', '100'),
('2108', '9', '5', '10'),
('2109', '9', '14', '100'),
('2110', '9', '15', '50'),
('2111', '10', '1', '100'),
('2112', '10', '6', '100'),
('2113', '10', '5', '10'),
('2114', '10', '16', '100'),
('2115', '11', '1', '100'),
('2116', '11', '6', '100'),
('2117', '11', '5', '10'),
('2118', '11', '16', '100'),
('2119', '11', '15', '50'),
('2120', '12', '1', '100'),
('2121', '12', '6', '100'),
('2122', '12', '5', '10'),
('2123', '12', '17', '100'),
('2124', '12', '15', '50'),
('2125', '13', '1', '100'),
('2126', '13', '6', '100'),
('2127', '13', '5', '10'),
('2128', '13', '18', '50'),
('2129', '13', '19', '40'),
('2130', '14', '1', '100'),
('2131', '14', '6', '100'),
('2132', '14', '5', '10'),
('2133', '14', '13', '100'),
('2134', '14', '18', '50'),
('2135', '14', '15', '30'),
('2136', '15', '1', '100'),
('2137', '15', '6', '100'),
('2138', '15', '5', '10'),
('2139', '15', '10', '100'),
('2140', '16', '1', '100'),
('2141', '16', '6', '100'),
('2142', '16', '5', '10'),
('2144', '17', '1', '100'),
('2145', '17', '6', '100'),
('2146', '17', '5', '10'),
('2147', '17', '18', '50'),
('2148', '17', '20', '80'),
('2149', '18', '1', '100'),
('2150', '18', '6', '100'),
('2151', '18', '5', '10'),
('2152', '18', '21', '100'),
('2157', '19', '1', '100'),
('2158', '19', '6', '100'),
('2159', '19', '5', '10'),
('2160', '19', '22', '100'),
('2161', '20', '1', '100'),
('2162', '20', '6', '100'),
('2163', '20', '5', '10'),
('2164', '20', '23', '50'),
('2165', '20', '24', '50'),
('2166', '20', '8', '50'),
('2167', '20', '25', '20'),
('2168', '21', '1', '100'),
('2169', '21', '6', '100'),
('2170', '21', '5', '10'),
('2171', '21', '20', '30'),
('2172', '21', '26', '30'),
('2173', '21', '15', '30'),
('2174', '22', '1', '100'),
('2175', '22', '6', '100'),
('2176', '22', '5', '10'),
('2177', '22', '13', '100'),
('2178', '22', '18', '50'),
('2179', '22', '3', '20'),
('2180', '23', '1', '100'),
('2181', '23', '6', '100'),
('2182', '23', '5', '10'),
('2183', '23', '12', '100'),
('2184', '23', '21', '50'),
('2185', '24', '1', '100'),
('2186', '24', '6', '100'),
('2187', '24', '5', '10'),
('2188', '24', '12', '50'),
('2189', '24', '18', '50'),
('2190', '24', '21', '30'),
('2191', '25', '1', '100'),
('2192', '25', '6', '100'),
('2193', '25', '5', '10'),
('2194', '25', '16', '100'),
('2195', '25', '9', '50'),
('2196', '25', '27', '40'),
('2197', '26', '1', '100'),
('2198', '26', '6', '100'),
('2199', '26', '5', '10'),
('2200', '26', '9', '100'),
('2201', '26', '10', '30'),
('2202', '26', '28', '30'),
('2203', '27', '1', '100'),
('2204', '27', '6', '100'),
('2205', '27', '5', '10'),
('2206', '27', '13', '100'),
('2207', '27', '8', '50'),
('2208', '28', '1', '100'),
('2209', '28', '6', '100'),
('2210', '28', '5', '10'),
('2211', '28', '29', '100'),
('2212', '28', '30', '100'),
('2213', '28', '8', '30'),
('2214', '28', '14', '50'),
('2215', '28', '31', '20'),
('2216', '29', '1', '100'),
('2217', '29', '6', '100'),
('2218', '29', '5', '10'),
('2219', '29', '32', '100'),
('2220', '30', '1', '100'),
('2221', '30', '6', '100'),
('2222', '30', '5', '10'),
('2223', '30', '32', '100'),
('2224', '30', '2', '20'),
('2225', '30', '4', '20'),
('2226', '31', '1', '100'),
('2227', '31', '6', '100'),
('2228', '31', '5', '10'),
('2229', '31', '16', '100'),
('2230', '31', '33', '50'),
('2231', '31', '34', '30'),
('2232', '32', '1', '100'),
('2233', '32', '6', '100'),
('2234', '32', '5', '10'),
('2235', '32', '16', '100'),
('2236', '32', '34', '50'),
('2455', '32', '21', '50'),
('2237', '33', '1', '100'),
('2238', '33', '6', '100'),
('2239', '33', '5', '10'),
('2240', '33', '36', '100'),
('2241', '33', '34', '50'),
('2248', '34', '1', '100'),
('2249', '34', '6', '100'),
('2250', '34', '5', '10'),
('2251', '34', '11', '100'),
('2252', '34', '34', '30'),
('2253', '34', '37', '50'),
('2254', '34', '38', '10'),
('2255', '34', '39', '50'),
('2256', '35', '1', '100'),
('2257', '35', '6', '100'),
('2258', '35', '5', '10'),
('2259', '35', '35', '100'),
('2260', '35', '8', '50'),
('2261', '35', '2', '20'),
('2262', '35', '4', '20'),
('2263', '36', '1', '100'),
('2264', '36', '6', '100'),
('2265', '36', '5', '10'),
('2266', '36', '30', '100'),
('2267', '37', '1', '100'),
('2268', '37', '6', '100'),
('2269', '37', '5', '10'),
('2270', '37', '40', '80'),
('2271', '37', '41', '70'),
('2272', '38', '1', '100'),
('2273', '38', '6', '100'),
('2274', '38', '5', '10'),
('2275', '38', '29', '100'),
('2276', '38', '42', '30'),
('2277', '38', '37', '50'),
('2278', '39', '1', '100'),
('2279', '39', '6', '100'),
('2280', '39', '5', '10'),
('2281', '39', '18', '50'),
('2282', '39', '29', '100'),
('2283', '39', '34', '50'),
('2284', '40', '1', '100'),
('2285', '40', '6', '100'),
('2286', '40', '5', '10'),
('2287', '40', '43', '100'),
('2288', '40', '45', '50'),
('2289', '40', '27', '50'),
('2290', '41', '1', '100'),
('2291', '41', '6', '100'),
('2292', '41', '5', '10'),
('2293', '41', '44', '100'),
('2294', '41', '45', '50'),
('2295', '41', '27', '50'),
('2296', '42', '1', '100'),
('2297', '42', '6', '100'),
('2298', '42', '5', '10'),
('2299', '42', '35', '100'),
('2300', '42', '8', '30'),
('2301', '42', '33', '50'),
('2302', '43', '1', '100'),
('2303', '43', '6', '100'),
('2304', '43', '5', '10'),
('2305', '43', '30', '100'),
('2306', '43', '42', '20'),
('2307', '43', '34', '50'),
('2308', '44', '1', '100'),
('2309', '44', '6', '100'),
('2310', '44', '5', '10'),
('2311', '44', '35', '100'),
('2312', '44', '8', '30'),
('2313', '44', '45', '50'),
('2314', '44', '9', '50'),
('2315', '45', '1', '100'),
('2316', '45', '6', '100'),
('2317', '45', '5', '10'),
('2318', '45', '35', '100'),
('2319', '45', '18', '30'),
('2320', '45', '37', '30'),
('2321', '45', '25', '20'),
('2322', '46', '1', '100'),
('2323', '46', '6', '100'),
('2324', '46', '5', '10'),
('2325', '46', '22', '100'),
('2326', '46', '12', '80'),
('2327', '46', '15', '50'),
('2328', '47', '1', '100'),
('2329', '47', '6', '100'),
('2330', '47', '5', '10'),
('2331', '47', '36', '100'),
('2332', '47', '12', '80'),
('2333', '47', '34', '50'),
('2334', '48', '1', '100'),
('2335', '48', '6', '100'),
('2336', '48', '5', '10'),
('2337', '48', '63', '100'),
('2338', '48', '64', '70'),
('2339', '49', '1', '100'),
('2340', '49', '6', '100'),
('2341', '49', '5', '10'),
('2342', '49', '65', '100'),
('2343', '50', '1', '100'),
('2344', '50', '6', '100'),
('2345', '50', '5', '10'),
('2346', '50', '66', '100'),
('2347', '51', '1', '100'),
('2348', '51', '6', '100'),
('2349', '51', '5', '10'),
('2350', '51', '66', '100'),
('2351', '51', '2', '30'),
('2352', '51', '4', '30'),
('2353', '52', '1', '100'),
('2354', '52', '6', '100'),
('2355', '52', '5', '10'),
('2356', '52', '66', '100'),
('2357', '52', '37', '50'),
('2358', '53', '1', '100'),
('2359', '53', '6', '100'),
('2360', '53', '5', '10'),
('2361', '53', '23', '100'),
('2362', '53', '67', '100'),
('2363', '54', '1', '100'),
('2364', '54', '6', '100'),
('2365', '54', '5', '10'),
('2366', '54', '20', '20'),
('2367', '54', '26', '20'),
('2368', '54', '15', '20'),
('2369', '54', '70', '20'),
('2370', '54', '37', '20'),
('2371', '55', '1', '100'),
('2372', '55', '6', '100'),
('2373', '55', '54', '50'),
('2374', '55', '34', '50'),
('2375', '55', '68', '100'),
('2376', '55', '18', '40'),
('2377', '56', '1', '100'),
('2378', '56', '6', '100'),
('2379', '56', '5', '10'),
('2380', '56', '69', '100'),
('2381', '56', '33', '50'),
('2382', '57', '1', '100'),
('2383', '57', '6', '100'),
('2384', '57', '5', '10'),
('2385', '57', '35', '100'),
('2386', '57', '70', '70'),
('2387', '58', '1', '100'),
('2388', '58', '6', '100'),
('2389', '58', '5', '10'),
('2390', '58', '70', '100'),
('2391', '58', '42', '30'),
('2392', '59', '1', '100'),
('2393', '59', '6', '100'),
('2394', '59', '5', '10'),
('2395', '59', '66', '100'),
('2396', '59', '20', '30'),
('2397', '59', '26', '30'),
('2398', '59', '15', '30'),
('2399', '60', '46', '100'),
('2400', '60', '48', '90'),
('2401', '60', '49', '30'),
('2402', '61', '28', '30'),
('2403', '61', '50', '100'),
('2404', '61', '47', '80'),
('2405', '62', '46', '100'),
('2406', '62', '51', '60'),
('2407', '62', '49', '30'),
('2408', '63', '47', '100'),
('2409', '64', '46', '100'),
('2410', '65', '46', '100'),
('2411', '65', '52', '50'),
('2412', '66', '46', '100'),
('2413', '66', '53', '80'),
('2452', '67', '6', '100'),
('2453', '67', '54', '50'),
('2454', '67', '34', '50'),
('2456', '68', '47', '100'),
('2457', '68', '48', '80'),
('2458', '68', '49', '50'),
('2427', '69', '46', '100'),
('2428', '69', '55', '80'),
('2459', '70', '28', '30'),
('2460', '70', '56', '100'),
('2429', '71', '28', '40'),
('2430', '71', '56', '100'),
('2432', '71', '57', '40'),
('2433', '71', '50', '100'),
('2434', '71', '58', '20'),
('2435', '71', '47', '50'),
('2437', '72', '28', '30'),
('2438', '72', '57', '50'),
('2439', '72', '46', '100'),
('2440', '72', '59', '60'),
('2441', '73', '28', '30'),
('2442', '73', '47', '100'),
('2443', '73', '60', '80'),
('2444', '73', '61', '10'),
('2445', '74', '28', '30'),
('2446', '74', '46', '100'),
('2447', '74', '47', '100'),
('2448', '74', '62', '60');

RETURN 1;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function inserirProdutos
-- -----------------------------------------------------

DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirProdutos`() RETURNS int(11)
    DETERMINISTIC
BEGIN
INSERT INTO produtos (idprodutos, nomeProduto, unidadeMedida, tipo_id, quantidade_minima) VALUES
(1, 'Molho de tomate', 1, 1, 10000),
(2, 'Alho', 1, 2, 1000),
(3, 'Alho Frito', 1, 2, 500),
(4, 'Óleo', 2, 8, 2000),
(5, 'Orégano', 1, 2, 500),
(6, 'Queijo Mussarela', 1, 3, 50000),
(7, 'Atum', 1, 4, 1000),
(8, 'Cebola', 1, 5, 4000),
(9, 'Bacon', 1, 4, 3000),
(10, 'Milho', 1, 5, 1000),
(11, 'Carne Moída', 1, 4, 2000),
(12, 'Brócolis', 1, 5, 1500),
(13, 'Calabresa', 1, 6, 6000),
(14, 'Chester', 1, 4, 1500),
(15, 'Catupiry', 1, 3, 3000),
(16, 'Frango', 1, 4, 6000),
(17, 'Lombo', 1, 4, 2000),
(18, 'Tomate', 1, 5, 4000),
(19, 'Manjericão', 1, 2, 500),
(20, 'Parmesão', 1, 3, 4000),
(21, 'Palmito', 1, 5, 3000),
(22, 'Peito de Peru', 1, 6, 2000),
(23, 'Presunto', 1, 6, 5000),
(24, 'Ovo', 1, 4, 4500),
(25, 'Azeitona', 1, 7, 2000),
(26, 'Provolone', 1, 3, 3000),
(27, 'Batata Palha', 1, 9, 2000),
(28, 'Creme de Leite', 1, 3, 2000),
(29, 'Salame Italiano', 1, 6, 2000),
(30, 'Pepperoni', 1, 6, 2000),
(31, 'Molho de pimenta', 1, 8, 400),
(32, 'Coração', 1, 4, 3000),
(33, 'Barbecue', 1, 8, 400),
(34, 'Cream Cheese', 1, 3, 750),
(35, 'Tiras de Carne', 1, 4, 2000),
(36, 'Linguiça Blumenau', 1, 6, 2000),
(37, 'Cheddar', 1, 3, 2000),
(38, 'Pimenta biquinho', 1, 2, 400),
(39, 'Doritos', 1, 9, 2000),
(40, 'Tomate seco', 1, 5, 300),
(41, 'Rúcula', 1, 5, 200),
(42, 'Azeitonas pretas', 1, 5, 200),
(43, 'Strogonoff de carne', 1, 4, 200),
(44, 'Strogonoff de frango', 1, 4, 200),
(45, 'Champignon', 1, 5, 200),
(46, 'Chocolate Preto', 1, 7, 1000),
(47, 'Chocolate Branco', 1, 7, 1000),
(48, 'Morango', 1, 5, 600),
(49, 'Leite Condensado', 1, 3, 300),
(50, 'Banana', 1, 5, 300),
(51, 'Granulado', 1, 7, 200),
(52, 'Confetti', 1, 7, 200),
(53, 'Creme de coco', 1, 7, 200),
(54, 'Goiabada', 1, 7, 200),
(55, 'Sonho de valsa', 1, 7, 200),
(56, 'Capuccino', 1, 7, 150),
(57, 'Doce de leite', 1, 7, 200),
(58, 'Canela', 1, 2, 100),
(59, 'Amendoim', 1, 7, 200),
(60, 'Coco ralado', 1, 7, 200),
(61, 'Cereja', 1, 7, 200),
(62, 'Marshmallow', 1, 7, 200),
(63, 'Queijo brie', 1, 3, 200),
(64, 'Geleia de pimenta', 1, 7, 200),
(65, 'Camarão', 1, 4, 500),
(66, 'Mignon', 1, 4, 500),
(67, 'Filé', 1, 4, 500),
(68, 'Carne seca desfiada', 1, 4, 500),
(69, 'Costelinha suína desfiada', 1, 6, 500),
(70, 'Gorgonzola', 1, 3, 500);


RETURN 1;

END$$

DELIMITER ;

-- -----------------------------------------------------
-- function inserirTamanho
-- -----------------------------------------------------

DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirTamanho`() RETURNS int(11)
    DETERMINISTIC
BEGIN
	INSERT INTO tamanho (nome, preco) VALUES ('Love', 20.00);
	INSERT INTO tamanho (nome, preco) VALUES ('Baby', 29.90);
	INSERT INTO tamanho (nome, preco) VALUES ('Média', 58.90);
	INSERT INTO tamanho (nome, preco) VALUES ('Grande', 76.90);
	INSERT INTO tamanho (nome, preco) VALUES ('Gigante', 87.90);

RETURN 1;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function inserirUnidadeMedida
-- -----------------------------------------------------

DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirUnidadeMedida`() RETURNS int(11)
    DETERMINISTIC
BEGIN
	INSERT INTO unidademedida (idunidademedida, nome) VALUES (1, 'Gramas');
	INSERT INTO unidademedida (idunidademedida, nome) VALUES (2, 'ML');

RETURN 1;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- function inserircategoriaBebidas
-- -----------------------------------------------------
DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserircategoriaBebidas`() RETURNS int(11)
    DETERMINISTIC
BEGIN
INSERT INTO `bd_pizzaria`.`categoriabebidas` (`idcategoriaBebidas`, `nome`) 
VALUES 
(1, 'Refrigerante'), 
(2, 'Água Mineral'), 
(3, 'Cerveja');
RETURN 1;
END$$
DELIMITER ;

-- -----------------------------------------------------
-- function inserirMarcaBebidas
-- -----------------------------------------------------
DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirMarcaBebidas`() RETURNS int(11)
    DETERMINISTIC
BEGIN
INSERT INTO `bd_pizzaria`.`marcaBebidas` (`idmarcaBebidas`, `nome`) 
VALUES 
(1, 'Coca Cola'), 
(2, 'Guaraná'), 
(3, 'Sprite'), 
(4, 'Fanta'), 
(5, 'Budweiser'), 
(6, 'Heineken'),
(7, 'Cristal');
RETURN 1;
END$$
DELIMITER ;

-- -----------------------------------------------------
-- function inserirTamanhoBebidas
-- -----------------------------------------------------
DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirTamanhoBebidas`() RETURNS int(11)
    DETERMINISTIC
BEGIN
INSERT INTO `bd_pizzaria`.`tamanhoBebidas` (`idtamanhoBebidas`, `nome`, `volume`) 
VALUES 
(1, 'Lata', 350), 
(2, '600ml', 600), 
(3, '2L', 2000), 
(4, 'Garrafinha', 500), 
(5, 'Garrafa Longneck', 330);
RETURN 1;
END$$

-- -----------------------------------------------------
-- function inserirBebidas
-- -----------------------------------------------------
DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirBebidas`() RETURNS int(11)
    DETERMINISTIC
BEGIN
INSERT INTO `bd_pizzaria`.`bebidas` (`marca_id`, `preco`, `quantidade`, `validade`, `tamanhobebidas_idtamanhoBebidas`, `categoriabebidas_idcategoriaBebidas`) 
VALUES 
(1, 6.00, 1, '2025-06-28', 1, 1),
(2, 6.00, 2, '2025-06-28', 1, 1),
(3, 6.00, 3, '2025-06-28', 1, 1),
(4, 6.00, 4, '2025-06-28', 1, 1),
(1, 8.00, 1, '2025-06-28', 2, 1),
(3, 8.00, 3, '2025-06-28', 2, 1),
(4, 8.00, 4, '2025-06-28', 2, 1),
(1, 15.0, 1, '2025-06-28', 3, 1),
(2, 12.0, 2, '2025-06-28', 3, 1),
(3, 12.0, 3, '2025-06-28', 3, 1),
(4, 12.0, 4, '2025-06-28', 3, 1),
(5, 3.50, 2, '2025-06-28', 4, 2),
(5, 3.50, 7, '2025-06-28', 4, 2),
(6, 10.0, 8, '2025-06-28', 5, 3),
(7, 10.0, 9, '2025-06-28', 5, 3);
RETURN 1;
END$$

-- -----------------------------------------------------
-- function inserirFormaPagamento
-- -----------------------------------------------------
DELIMITER $$
USE `bd_pizzaria`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `inserirFormaPagamento`() RETURNS int(11)
    DETERMINISTIC
BEGIN
INSERT INTO `bd_pizzaria`.`forma_pagamento` (`tipo`) 
VALUES 
('Pix'), 
('Débito'), 
('Crédito'), 
('Dinheiro');

RETURN 1;
END$$

-- -----------------------------------------------------
-- function inserirUsuarios
-- -----------------------------------------------------

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

DELIMITER //

CREATE TRIGGER trg_after_status_update
AFTER UPDATE ON vendas
FOR EACH ROW
BEGIN
    -- Verifica se o status foi alterado
    IF OLD.status_id <> NEW.status_id THEN
        INSERT INTO log_status (venda_id, status_anterior, status_novo, data_alteracao)
        VALUES (OLD.idvendas, OLD.status_id, NEW.status_id, NOW());
    END IF;
END //

DELIMITER ;

select bd_pizzaria.inserirBordaPizza();
select bd_pizzaria.inserirUnidadeMedida();
select bd_pizzaria.inserirFormaEntrega();
select bd_pizzaria.inserirTipoProdutos();
select bd_pizzaria.inserirProdutos();
select bd_pizzaria.inserirTamanho();
select bd_pizzaria.inserirPizzas();
select bd_pizzaria.inserirCategoriaBebidas();
select bd_pizzaria.inserirMarcaBebidas();
select bd_pizzaria.inserirTamanhoBebidas();
select bd_pizzaria.inserirBebidas();
select bd_pizzaria.inserirFormaPagamento();

DELIMITER //

CREATE TRIGGER `vendas_AFTER_INSERT` 
AFTER INSERT ON `vendas` 
FOR EACH ROW 
BEGIN
    IF NEW.status_id = 1 THEN
        -- Insere no log_status
        INSERT INTO log_status (venda_id, status_anterior, status_novo, data_alteracao)
        VALUES (NEW.idvendas, 4, NEW.status_id, NOW());  -- NULL para status_anterior, pois é um novo registro
    END IF;
END //

DELIMITER ;


INSERT INTO status_venda (nome_status) values
('Não começou'),
('Em andamento'),
('Finalizado'),
('Pedido feito');


insert into clientes (nome, sobrenome, telefone, email, senha) values ('Pedro', 'Sabel', '47999160344', 'pedrosabel08@gmail.com', '123');

INSERT INTO estoque_lote (idproduto, quantidade, data_validade, preco_unitario, data_entrada) VALUES
(1, 5821, '2025-10-02', 17.67, '2025-01-29'),
(2, 6664, '2025-07-18', 21.62, '2025-02-24'),
(3, 7633, '2025-10-14', 5.86, '2025-06-01'),
(4, 5382, '2025-07-16', 6.02, '2025-05-06'),
(5, 6826, '2025-07-23', 3.63, '2025-03-27'),
(6, 5618, '2025-09-21', 13.64, '2025-04-08'),
(7, 9576, '2025-08-27', 11.45, '2025-06-04'),
(8, 9877, '2025-07-02', 4.34, '2025-02-08'),
(9, 7446, '2025-09-19', 12.34, '2025-04-14'),
(10, 7289, '2025-11-10', 8.12, '2025-01-15'),
(11, 8524, '2025-08-09', 9.56, '2025-04-29'),
(12, 6402, '2025-10-01', 6.74, '2025-03-11'),
(13, 5329, '2025-09-07', 15.88, '2025-05-23'),
(14, 8497, '2025-07-30', 4.95, '2025-03-01'),
(15, 9742, '2025-10-19', 7.29, '2025-04-17'),
(16, 6795, '2025-09-12', 11.24, '2025-01-31'),
(17, 7193, '2025-10-05', 10.76, '2025-02-12'),
(18, 9944, '2025-09-18', 6.32, '2025-03-22'),
(19, 8348, '2025-07-15', 14.67, '2025-05-19'),
(20, 9431, '2025-08-08', 5.48, '2025-06-02'),
(21, 9001, '2025-09-26', 7.93, '2025-03-16'),
(22, 5933, '2025-10-09', 9.88, '2025-02-07'),
(23, 9820, '2025-07-28', 4.72, '2025-04-10'),
(24, 6974, '2025-11-04', 11.99, '2025-06-06'),
(25, 7659, '2025-08-14', 6.84, '2025-03-04'),
(26, 6412, '2025-09-22', 8.65, '2025-04-21'),
(27, 5800, '2025-10-11', 10.43, '2025-02-18'),
(28, 7198, '2025-07-17', 5.39, '2025-01-27'),
(29, 9937, '2025-08-24', 12.76, '2025-03-31'),
(30, 9324, '2025-10-07', 6.91, '2025-06-01'),
(31, 8557, '2025-07-05', 9.37, '2025-01-12'),
(32, 6749, '2025-09-30', 7.15, '2025-04-03'),
(33, 9125, '2025-08-20', 13.42, '2025-03-14'),
(34, 6217, '2025-07-09', 8.79, '2025-02-10'),
(35, 7342, '2025-09-16', 4.88, '2025-05-25'),
(36, 8521, '2025-08-03', 15.33, '2025-03-30'),
(37, 8993, '2025-10-22', 6.25, '2025-02-14'),
(38, 7566, '2025-07-21', 11.78, '2025-04-18'),
(39, 8810, '2025-09-13', 10.92, '2025-01-19'),
(40, 6005, '2025-10-28', 7.61, '2025-03-05'),
(41, 7784, '2025-08-17', 13.09, '2025-05-14'),
(42, 6886, '2025-07-12', 9.22, '2025-02-27'),
(43, 9735, '2025-09-23', 5.78, '2025-03-18'),
(44, 8383, '2025-08-06', 8.37, '2025-04-27'),
(45, 7877, '2025-10-03', 6.53, '2025-01-23'),
(46, 6510, '2025-07-25', 12.45, '2025-06-03'),
(47, 9512, '2025-08-30', 10.29, '2025-03-10'),
(48, 6145, '2025-09-04', 9.01, '2025-04-20'),
(49, 8814, '2025-07-07', 5.97, '2025-02-15'),
(50, 7998, '2025-09-25', 14.22, '2025-03-26'),
(51, 7400, '2025-08-11', 6.84, '2025-04-01'),
(52, 9510, '2025-09-10', 10.47, '2025-01-17'),
(53, 6428, '2025-07-19', 8.18, '2025-06-07'),
(54, 9313, '2025-08-13', 5.66, '2025-03-12'),
(55, 8527, '2025-09-03', 7.91, '2025-05-01'),
(56, 7991, '2025-10-25', 11.34, '2025-02-06'),
(57, 8613, '2025-08-05', 6.49, '2025-03-21'),
(58, 7600, '2025-09-06', 13.03, '2025-04-09'),
(59, 8876, '2025-07-31', 10.88, '2025-01-26'),
(60, 9933, '2025-10-08', 8.72, '2025-03-09'),
(61, 8149, '2025-09-01', 7.36, '2025-05-18'),
(62, 7003, '2025-07-13', 6.55, '2025-02-01'),
(63, 9771, '2025-10-15', 9.67, '2025-04-29'),
(64, 8415, '2025-08-25', 12.58, '2025-03-07'),
(65, 9349, '2025-09-15', 11.23, '2025-01-28'),
(66, 8830, '2025-07-06', 6.99, '2025-02-20'),
(67, 7772, '2025-10-06', 5.81, '2025-04-04'),
(68, 9330, '2025-08-02', 14.44, '2025-03-25'),
(69, 8691, '2025-09-28', 7.88, '2025-06-05'),
(70, 7575, '2025-07-26', 9.19, '2025-03-15');
