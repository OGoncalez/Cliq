# Diagrama Entidade-Relacionamento - SOUL System

```mermaid
erDiagram
    %% Tabela de Usuários e Autenticação
    USUARIO {
        int id PK
        varchar(100) nome
        varchar(100) email UK
        varchar(255) senha
        varchar(14) cpf UK
        varchar(50) cargo
        int perfil_id FK
        varchar(20) telefone
        varchar(20) celular
        boolean ativo
        datetime data_cadastro
        datetime ultimo_acesso
        datetime data_atualizacao
        varchar(100) token_recuperacao
        datetime expiracao_token
    }
    
    PERFIL {
        int id PK
        varchar(50) nome UK
        text descricao
        json permissoes
        json modulos_acesso
        boolean is_admin
        datetime data_criacao
    }
    
    %% Tabelas de Produtos e Lotes
    PRODUTO {
        int id PK
        varchar(50) codigo_produto UK
        varchar(20) codigo_barras
        varchar(200) nome
        text descricao
        varchar(50) categoria
        varchar(50) subcategoria
        varchar(100) marca
        varchar(10) unidade_medida
        decimal(10,3) peso_unitario
        decimal(10,3) volume_unitario
        int estoque_minimo
        int estoque_maximo
        decimal(10,2) preco_custo_medio
        decimal(10,2) preco_venda
        boolean controla_lote
        boolean controla_validade
        int dias_validade
        varchar(20) status
        datetime data_cadastro
    }
    
    LOTE {
        int id PK
        varchar(50) codigo_lote UK
        varchar(50) lote_fabricante
        int produto_id FK
        int quantidade_inicial
        int quantidade_atual
        int quantidade_reservada
        int quantidade_avariada
        int quantidade_bloqueada
        datetime data_fabricacao
        datetime data_vencimento
        datetime data_recebimento
        int deposito_id FK
        varchar(50) endereco_completo
        int fornecedor_id FK
        varchar(50) nota_fiscal
        decimal(10,2) custo_unitario
        varchar(20) status_qualidade
        boolean is_bloqueado
        datetime data_bloqueio
        int usuario_bloqueio FK
    }
    
    %% Tabelas de Movimentação
    MOVIMENTACAO {
        int id PK
        varchar(20) tipo_movimentacao
        int lote_id FK
        int produto_id FK
        int quantidade
        datetime data_hora
        varchar(50) documento_referencia
        varchar(30) numero_documento
        int origem_id FK
        int destino_id FK
        int responsavel_id FK
        text observacao
        varchar(20) status
    }
    
    ENDERECO_ESTOQUE {
        int id PK
        int deposito_id FK
        varchar(30) codigo_endereco UK
        varchar(20) rua
        varchar(20) corredor
        varchar(20) prateleira
        varchar(10) coluna
        varchar(10) nivel
        boolean ocupado
        int lote_id_atual FK
    }
    
    %% Tabelas de Qualidade
    CONTROLE_QUALIDADE {
        int id PK
        int lote_id FK UK
        varchar(20) classificacao
        text resultado_inspecao
        decimal(4,2) nota_qualidade
        datetime data_inspecao
        int responsavel_id FK
        boolean needs_reinspecao
    }
    
    OCORRENCIA {
        int id PK
        int qualidade_id FK
        varchar(30) tipo
        text descricao
        varchar(20) gravidade
        json fotos
        datetime data_registro
        int usuario_registro_id FK
        datetime data_resolucao
        text acao_corretiva
        varchar(20) status
    }
    
    %% Tabelas de Estrutura Física
    UNIDADE {
        int id PK
        varchar(20) codigo_unidade UK
        varchar(100) nome
        varchar(20) cnpj UK
        varchar(200) endereco
        varchar(50) cidade
        varchar(2) estado
        boolean matriz
        boolean ativo
    }
    
    DEPOSITO {
        int id PK
        varchar(20) codigo_deposito UK
        varchar(100) nome
        int unidade_id FK
        varchar(30) tipo_deposito
        int responsavel_id FK
        decimal(10,2) capacidade_total
        boolean ativo
    }
    
    %% Tabelas de Inventário
    INVENTARIO {
        int id PK
        varchar(20) codigo_inventario UK
        int deposito_id FK
        datetime data_inicio
        datetime data_fim
        varchar(20) status
        int responsavel_id FK
        int total_lotes_previstos
        int total_lotes_contados
        int divergencias_encontradas
    }
    
    CONTAGEM {
        int id PK
        int inventario_id FK
        int lote_id FK
        int quantidade_sistema
        int quantidade_contada
        int diferenca
        datetime data_contagem
        int responsavel_id FK
        boolean validada
        int validador_id FK
    }
    
    %% Tabelas de Logs
    LOG {
        bigint id PK
        int usuario_id FK
        varchar(50) acao
        varchar(50) entidade_afetada
        int id_entidade
        json dados_antigos
        json dados_novos
        varchar(45) ip
        datetime data_hora
    }
    
    NOTIFICACAO {
        int id PK
        int usuario_id FK
        varchar(50) tipo
        varchar(200) titulo
        text mensagem
        boolean lida
        datetime data_envio
        datetime data_leitura
    }
    
    %% Tabelas de Compras
    FORNECEDOR {
        int id PK
        varchar(20) codigo_fornecedor UK
        varchar(200) nome
        varchar(20) cnpj UK
        varchar(200) endereco
        varchar(20) telefone
        varchar(100) email
        varchar(20) status
    }
    
    ORDEM_COMPRA {
        int id PK
        varchar(30) numero_oc UK
        int fornecedor_id FK
        datetime data_emissao
        datetime data_entrega_prevista
        decimal(12,2) valor_total
        varchar(20) status
    }
    
    ITEM_COMPRA {
        int id PK
        int ordem_compra_id FK
        int produto_id FK
        int quantidade
        decimal(10,2) valor_unitario
        int quantidade_recebida
        int lote_id_gerado FK
    }

    %% RELACIONAMENTOS
    USUARIO }o--|| PERFIL : "possui"
    
    PRODUTO ||--o{ LOTE : "possui"
    LOTE }o--|| DEPOSITO : "armazenado em"
    LOTE }o--|| FORNECEDOR : "fornecido por"
    LOTE }o--o| ENDERECO_ESTOQUE : "localizado em"
    
    MOVIMENTACAO }o--|| LOTE : "movimenta"
    MOVIMENTACAO }o--|| USUARIO : "realizada por"
    MOVIMENTACAO }o--|| DEPOSITO : "origem"
    MOVIMENTACAO }o--|| DEPOSITO : "destino"
    
    DEPOSITO ||--o{ ENDERECO_ESTOQUE : "contém"
    
    LOTE ||--|| CONTROLE_QUALIDADE : "submetido a"
    CONTROLE_QUALIDADE ||--o{ OCORRENCIA : "registra"
    
    UNIDADE ||--o{ DEPOSITO : "possui"
    
    DEPOSITO ||--o{ INVENTARIO : "realiza"
    INVENTARIO ||--o{ CONTAGEM : "possui"
    CONTAGEM }o--|| LOTE : "conta"
    
    USUARIO ||--o{ LOG : "gera"
    USUARIO ||--o{ NOTIFICACAO : "recebe"
    
    FORNECEDOR ||--o{ ORDEM_COMPRA : "recebe"
    ORDEM_COMPRA ||--o{ ITEM_COMPRA : "contém"
    ITEM_COMPRA }o--|| PRODUTO : "referencia"
    ITEM_COMPRA }o--o| LOTE : "gera"
