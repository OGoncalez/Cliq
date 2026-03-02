erDiagram
    USUARIO {
        int id PK
        string nome
        string email UK
        string senha
        string cpf UK
        string cargo
        int perfil_id FK
        string telefone
        string celular
        boolean ativo
        datetime data_cadastro
        datetime ultimo_acesso
    }
    
    PERFIL {
        int id PK
        string nome UK
        text descricao
        boolean is_admin
        datetime data_criacao
    }
    
    PRODUTO {
        int id PK
        string codigo_produto UK
        string codigo_barras
        string nome
        text descricao
        string categoria
        string marca
        string unidade_medida
        float peso_unitario
        int estoque_minimo
        int estoque_maximo
        float preco_custo_medio
        float preco_venda
        boolean controla_lote
        boolean controla_validade
        int dias_validade
        string status
        datetime data_cadastro
    }
    
    LOTE {
        int id PK
        string codigo_lote UK
        string lote_fabricante
        int produto_id FK
        int quantidade_inicial
        int quantidade_atual
        int quantidade_reservada
        datetime data_fabricacao
        datetime data_vencimento
        int deposito_id FK
        string endereco_completo
        int fornecedor_id FK
        string nota_fiscal
        float custo_unitario
        string status_qualidade
        boolean is_bloqueado
    }
    
    MOVIMENTACAO {
        int id PK
        string tipo_movimentacao
        int lote_id FK
        int quantidade
        datetime data_hora
        string documento_referencia
        int origem_id FK
        int destino_id FK
        int responsavel_id FK
        text observacao
    }
    
    DEPOSITO {
        int id PK
        string codigo_deposito UK
        string nome
        int unidade_id FK
        string tipo_deposito
        int responsavel_id FK
        float capacidade_total
        boolean ativo
    }
    
    UNIDADE {
        int id PK
        string codigo_unidade UK
        string nome
        string cnpj UK
        string endereco
        string cidade
        string estado
        boolean matriz
    }
    
    FORNECEDOR {
        int id PK
        string codigo_fornecedor UK
        string nome
        string cnpj UK
        string telefone
        string email
        string status
    }
    
    CONTROLE_QUALIDADE {
        int id PK
        int lote_id FK
        string classificacao
        text resultado_inspecao
        float nota_qualidade
        datetime data_inspecao
        int responsavel_id FK
    }
    
    INVENTARIO {
        int id PK
        string codigo_inventario UK
        int deposito_id FK
        datetime data_inicio
        string status
        int responsavel_id FK
    }
    
    LOG {
        bigint id PK
        int usuario_id FK
        string acao
        string entidade_afetada
        datetime data_hora
    }

    %% Relacionamentos
    USUARIO }o--|| PERFIL : possui
    
    PRODUTO ||--o{ LOTE : possui
    LOTE }o--|| DEPOSITO : armazenado_em
    LOTE }o--|| FORNECEDOR : fornecido_por
    
    MOVIMENTACAO }o--|| LOTE : movimenta
    MOVIMENTACAO }o--|| USUARIO : realizada_por
    
    UNIDADE ||--o{ DEPOSITO : contem
    
    LOTE ||--o{ CONTROLE_QUALIDADE : inspecionado_em
    
    DEPOSITO ||--o{ INVENTARIO : realiza
    
    USUARIO ||--o{ LOG : gera
