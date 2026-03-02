erDiagram
    USUARIO {
        int id PK
        string nome
        string email
    }
    
    PRODUTO {
        int id PK
        string nome
        string codigo
    }
    
    LOTE {
        int id PK
        string codigo
        int quantidade
        date vencimento
        int produto_id FK
    }
    
    MOVIMENTACAO {
        int id PK
        string tipo
        int quantidade
        datetime data
        int lote_id FK
    }
    
    DEPOSITO {
        int id PK
        string nome
    }
    
    PRODUTO ||--o{ LOTE : possui
    LOTE ||--o{ MOVIMENTACAO : gera
    LOTE }o--|| DEPOSITO : armazenado
