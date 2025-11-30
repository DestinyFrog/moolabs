
CREATE DATABASE IF NOT EXISTS moolabs;

USE moolabs;

ALTER DATABASE moolabs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE imagem (
    id INTEGER AUTO_INCREMENT,
    href VARCHAR(255) NOT NULL,
    alt VARCHAR(255) NOT NULL,

    CONSTRAINT pk_imagem
        PRIMARY KEY (id)
);

CREATE TABLE tipo_alimento (
    id INTEGER AUTO_INCREMENT,
    slug VARCHAR(50) NOT NULL,
    nome VARCHAR(255) NOT NULL,

    CONSTRAINT pk_tipo_alimento
        PRIMARY KEY (id)
);

CREATE TABLE produto (
    id INTEGER AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    tags JSON,
    preco DECIMAL(10, 2) NOT NULL,

    imagem_id INTEGER,
    tipo_alimento_id INTEGER NOT NULL,

    CONSTRAINT pk_produto
        PRIMARY KEY (id),

    CONSTRAINT fk_produto_imagem
        FOREIGN KEY (imagem_id)
        REFERENCES imagem(id),

    CONSTRAINT fk_produto_tipo_alimento
        FOREIGN KEY (tipo_alimento_id)
        REFERENCES tipo_alimento(id)
);

CREATE TABLE profissao (
    id INTEGER AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    prefixo_masculino VARCHAR(20) NOT NULL,
    prefixo_feminino VARCHAR(20) NOT NULL,

    CONSTRAINT pk_profissao
        PRIMARY KEY (id)
);

CREATE TABLE cliente (
    id INTEGER AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    nivel ENUM('bronze', 'prata', 'ouro', 'platina') NOT NULL DEFAULT 'bronze',
    sexo ENUM('M', 'F', 'O') DEFAULT 'O',
    titulo VARCHAR(255) DEFAULT NULL,
    cidade VARCHAR(100),
    uf VARCHAR(2),

    data_cliente TIMESTAMP DEFAULT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    profissao_id INTEGER DEFAULT NULL,
    imagem_id INTEGER DEFAULT NULL,

    CONSTRAINT pk_cliente
        PRIMARY KEY (id),

    CONSTRAINT fk_cliente_profissao
        FOREIGN KEY (profissao_id)
        REFERENCES profissao(id),

    CONSTRAINT fk_cliente_imagem
        FOREIGN KEY (imagem_id)
        REFERENCES imagem(id)
);

CREATE TABLE avaliacao_produto (
    id INTEGER AUTO_INCREMENT,
    descricao TEXT NOT NULL,

    cliente_id INTEGER NOT NULL,
    produto_id INTEGER,

    CONSTRAINT pk_avaliacao_produto
        PRIMARY KEY (id),

    CONSTRAINT fk_avaliacao_produto_produto
        FOREIGN KEY (produto_id)
        REFERENCES produto(id)
);

CREATE TABLE avaliacao (
    id INTEGER AUTO_INCREMENT,
    descricao TEXT NOT NULL,
    nota INTEGER NOT NULL CHECK (nota >= 1 AND nota <= 5),
    recomendaria BOOLEAN DEFAULT NULL,

    cliente_id INTEGER NOT NULL,

    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_avaliacao
        PRIMARY KEY (id),

    CONSTRAINT fk_avaliacao_cliente
        FOREIGN KEY (cliente_id)
        REFERENCES cliente(id)
);

CREATE TABLE parceiro (
    id INTEGER AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    uf VARCHAR(2) NOT NULL,

    imagem_id INTEGER NOT NULL,

    CONSTRAINT pk_parceiro
        PRIMARY KEY (id),

    CONSTRAINT fk_parceiro_imagem
        FOREIGN KEY (imagem_id)
        REFERENCES imagem(id)
);

CREATE TABLE usuario (
    id INTEGER AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    admin BOOLEAN DEFAULT FALSE,

    imagem_id INTEGER DEFAULT NULL,

    CONSTRAINT pk_usuario
        PRIMARY KEY (id),

    CONSTRAINT fk_usuario_imagem
        FOREIGN KEY (imagem_id)
        REFERENCES imagem(id)
);

CREATE VIEW vw_produto AS
    SELECT
        p.nome,
        p.descricao,
        tp.slug AS tipo_alimento_slug,
        tp.nome AS tipo_alimento_nome,
        p.tags,
        i.href as imagem_href,
        i.alt as imagem_alt,
        p.preco
    FROM produto p
    INNER JOIN imagem i
        ON i.id = p.imagem_id
    INNER JOIN tipo_alimento tp
        ON p.tipo_alimento_id = tp.id;

CREATE VIEW vw_avaliacao AS
    SELECT
        a.nota,
        a.descricao,
        c.nome AS cliente_nome,
        c.titulo AS cliente_titulo,
        c.nivel AS cliente_nivel,
        c.sexo AS cliente_sexo,
        c.cidade AS cliente_cidade,
        c.uf AS cliente_uf,
        im.href AS cliente_imagem_href,
        im.alt AS cliente_imagem_alt,
        pr.nome AS cliente_profissao,
        pr.prefixo_masculino AS cliente_profissao_prefixo_masculino,
        pr.prefixo_feminino AS cliente_profissao_prefixo_feminino
    FROM avaliacao a
    INNER JOIN cliente c
        ON c.id = a.cliente_id
    LEFT JOIN imagem im
        ON im.id = c.imagem_id
    LEFT JOIN profissao pr
        ON pr.id = c.profissao_id;

CREATE VIEW vw_avaliacao_produto AS
    SELECT
        ap.descricao,
        c.nome AS cliente_nome,
        c.titulo AS cliente_titulo,
        c.nivel AS cliente_nivel,
        c.sexo AS cliente_sexo,
        c.cidade AS cliente_cidade,
        c.uf AS cliente_uf,
        ap_im.href AS cliente_imagem_href,
        ap_im.alt AS cliente_imagem_alt,
        pr.nome AS cliente_profissao,
        pr.prefixo_masculino AS cliente_profissao_prefixo_masculino,
        pr.prefixo_feminino AS cliente_profissao_prefixo_feminino,
        p.nome AS produto_nome,
        p.descricao AS produto_descricao,
        p_i.href AS produto_imagem_href,
        p_i.alt AS produto_imagem_alt
    FROM avaliacao_produto ap
    INNER JOIN cliente c
        ON c.id = ap.cliente_id
    LEFT JOIN imagem ap_im
        ON ap_im.id = c.imagem_id
    LEFT JOIN profissao pr
        ON pr.id = c.profissao_id
    INNER JOIN produto p
        ON p.id = ap.produto_id
    LEFT JOIN imagem p_i
        ON p_i.id = p.imagem_id;

CREATE VIEW vw_usuario AS
    SELECT
        u.id,
        u.nome,
        u.email,
        u.senha,
        u.admin,
        im.href AS usuario_imagem_href,
        im.alt AS usuario_imagem_alt
    FROM usuario u
    LEFT JOIN imagem im
        ON im.id = u.imagem_id;

CREATE VIEW vw_estatistica_avaliacao AS
    SELECT 
        COUNT(*) AS total_avaliacoes,
        AVG(nota) AS media_avaliacao,
        COUNT(CASE WHEN recomendaria = TRUE THEN 1 END) * 100.0 / COUNT(*) AS percentual_recomendacao
    FROM avaliacao;

CREATE VIEW vw_parceiro AS
    SELECT
        p.nome,
        p.descricao,
        p.cidade,
        p.uf,
        i.href AS imagem_href,
        i.alt AS imagem_alt
    FROM parceiro p
    INNER JOIN imagem i
        ON i.id = p.imagem_id;

INSERT INTO imagem (id, href, alt)
VALUES
    (1, '/assets/images/cerrado.png', 'Foto de Queijo Cerrado'),
    (2, '/assets/images/creme_de_queijo_came.png', 'Foto de Creme de Queijo Camê'),
    (3, '/assets/images/luna_azul.png', 'Foto de Luna Azul'),
    (4, '/assets/images/nata_real.png', 'Foto de Nata Real'),
    (5, '/assets/images/neblina_dos_alpes.png', 'Foto de Neblina dos Alpes'),
    (6, '/assets/images/opala_branca.png', 'Foto de Opala Branca'),
    (7, '/assets/images/orvalho_branco.png', 'Foto de Orvalho Branco'),
    (8, '/assets/images/parmesano_estelar.png', 'Foto de Parmesano Estelar'),
    (9, '/assets/images/salso_ruby.png', 'Foto de Salso Ruby'),
    (10, '/assets/images/seranata_de_ouro.png', 'Foto de Serenata de Ouro'),
    (11, '/assets/images/veu_de_noiva.png', 'Foto de Véu de Noiva'),
    (12, '/assets/images/cliente_maxican.png', 'Logo do Maxican Gourmet'),
    (13, '/assets/images/cliente_la_pasta.png', 'Logo do La Pasta Nostra'),
    (14, '/assets/images/cliente_bistro_frances.png', 'Logo do Bistro Francês'),
    (15, '/assets/images/cliente_pascados.png', 'Logo do Pascados Chef'),
    (16, '/assets/images/cliente_vegala.png', 'Logo do Vegala'),
    (17, '/assets/images/foto_renata_montenegro.png', 'Foto de Perfil de Renata Montenegro')
;

INSERT INTO tipo_alimento (id, slug, nome)
VALUES
    (1, 'queijo', 'Queijos Artesanais'),
    (2, 'creme', 'Cremes e Natas'),
    (3, 'leite', 'Leites Especiais')
;

INSERT INTO produto (nome, tipo_alimento_id, tags, imagem_id, preco, descricao)
VALUES
    ('Queijo Cerrado', 1, '["premium"]', 1, 78.90,
        'Queijo artesanal inspirado nos sabores do cerrado brasileiro. Textura cremosa com notas herbais únicas, maturado por 60 dias em caves naturais.'),
    ('Creme de Queijo Camê', 2, '["artesanal"]', 2, 45.90,
        'Creme suave e aveludado com a sofisticação do camembert. Perfeito para acompanhar pães artesanais e harmonizar com vinhos brancos.'),
    ('Luna Azul', 1, '["exclusivo"]', 3, 156.90,
        'Queijo azul de edição limitada, maturado sob a luz da lua cheia. Sabor intenso e complexo, ideal para conhecedores de queijos especiais.'),
    ('Nata Real', 2, '["premium"]', 4, 32.90,
        'Nata fresca de primeira qualidade, extraída do melhor leite A2. Textura sedosa e sabor delicado, perfeita para sobremesas gourmet.'),
    ('Neblina dos Alpes', 1, '["artesanal"]', 5, 98.90,
        'Inspirado nos queijos alpinos tradicionais. Casca natural lavada, sabor frutado e levemente picante. Maturação de 90 dias em altitude.'),
    ('Opala Branca', 1, '["premium"]', 6, 67.90,
        'Queijo fresco de cabra com textura cremosa e sabor suave. Produzido com leite de cabras criadas em pastagens orgânicas certificadas.'),
    ('Orvalho Branco', 3, '["A2"]', 7, 18.90,
        'Leite integral A2 coletado nas primeiras horas da manhã. Processamento mínimo preserva todos os nutrientes e o sabor natural.'),
    ('Parmesano Estelar', 1, '["24 meses"]', 8, 189.90,
        'Parmesão artesanal maturado por 24 meses. Textura granulosa, sabor intenso e cristais crocantes. Perfeito para risotos e massas gourmet.'),
    ('Salso Ruby', 1, '["especial"]', 9, 85.90,
        'Queijo temperado com ervas finas e pimenta rosa. Sabor marcante e aromático, ideal para tábuas de queijos sofisticadas.'),
    ('Serenata de Ouro', 1, '["ouro"]', 10, 245.90,
        'Nossa joia mais preciosa. Queijo de leite cru maturado por 18 meses, com casca dourada natural. Sabor complexo e inesquecível.'),
    ('Véu de Noiva', 1, '["delicado"]', 11, 54.90,
        'Queijo fresco envolvido em fina película comestível. Textura delicada e sabor suave, perfeito para ocasiões especiais e celebrações.')
;

INSERT INTO profissao (id, nome, prefixo_masculino, prefixo_feminino)
VALUES
    (1, 'Nutricionista', 'Dr.', 'Dra.'),
    (2, 'Chef de Cozinha', 'Chef', 'Chef'),
    (3, 'Gastrônomo', 'Gastrônomo', 'Gastrônoma'),
    (4, 'Sommelier', 'Sommelier', 'Sommelier')
;

INSERT INTO cliente (id, nome, nivel, sexo, cidade, uf, profissao_id, imagem_id, titulo)
VALUES
    (1, 'Ana Luísa Brandão', 'bronze', 'F', 'Rio de Janeiro', 'RJ', 1, NULL,
        'Nutricionista especializada em gastronomia'),
    (2, 'Carlos Eduardo Fonseca', 'ouro', 'M', 'Belo Horizonte', 'MG', 4, NULL,
        'Enófilo e assinante do "Círculo MooLabs"'),
    (3, 'Renata Montenegro', 'prata', 'F', 'São Paulo', 'SP', 2, 17,
        'Chef do restaurante estrelado "Terra & Brasa'),
    (4, 'João Santos', 'platina', 'M', 'Curitiba', 'PR', NULL, NULL,
        NULL),
    (5, 'Maria Silva', 'bronze', 'F', 'Londrina', 'PR', NULL, NULL,
        NULL)
;

INSERT INTO avaliacao_produto (descricao, cliente_id, produto_id)
VALUES
    ('Elevou meus pratos a outro patamar', 3, 1),
    ('Harmonizou perfeitamente com meu Bordeaux 2015', 2, 3),
    ('Recomendo para pacientes com sensibilidade alimentar', 1, 7)
;

INSERT INTO avaliacao (nota, cliente_id, recomendaria, descricao)
VALUES
    (5, 1, TRUE,
        "Recomendo os laticínios MooLabs para pacientes com sensibilidade alimentar. O Lacto Harmonia (leite fermentado) tem uma seleção de probióticos clinicamente comprovados, e o selo 'Animal Welfare Approved' me assegura que estou indicando produtos éticos. Meus clientes adoram as receitas que desenvolvi com a manteiga clarificada deles."),
    (4, 2, TRUE,
        'Assino o kit mensal há 6 meses e cada entrega é uma experiência sensorial. O Queijo da Lua (maturado em adega natural) que veio na última caixa harmonizou perfeitamente com meu Bordeaux 2015. A embalagem sustentável e o livro de histórias dos produtores mostram que valorizam cada detalhe - exatamente o que espero de uma marca de luxo.'),
    (5, 3, TRUE,
        "Recomendo os laticínios MooLabs para pacientes com sensibilidade alimentar. O Lacto Harmonia (leite fermentado) tem uma seleção de probióticos clinicamente comprovados, e o selo 'Animal Welfare Approved' me assegura que estou indicando produtos éticos. Meus clientes adoram as receitas que desenvolvi com a manteiga clarificada deles.")
;

INSERT INTO parceiro (nome, cidade, uf, imagem_id, descricao)
VALUES
    ('Maxican Gourmet', 'São Paulo', 'SP', 12,
        'Restaurante mexicano premium que utiliza nossos queijos especiais em pratos tradicionais com toque contemporâneo.'),
    ('Pascados Chef', 'Rio de Janeiro', 'RJ', 15,
        'Serviço de catering gourmet que incorpora nossos laticínios artesanais em menus personalizados para eventos exclusivos.'),
    ('Vegala', 'Porto Alegre', 'RS', 16,
        'Café vegano que utiliza nossas natas e cremes em sobremesas e bebidas, oferecendo opções deliciosas e éticas.')
;
    
INSERT INTO usuario (nome, email, senha, admin, imagem_id)
VALUES 
    ('admin', 'admin@email.com', '$2y$10$xAaajE.DH1WtgFOCHtzG6.VpszaObcYzkN/cdScXSWj.URbfyLa/G', TRUE, NULL)
;
