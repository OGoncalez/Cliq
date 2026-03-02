# SOUL - Sistema de Organização Universal Logístico

## Diagrama Entidade-Relacionamento

### Visão Geral (Simplificada)

```mermaid
erDiagram
    USUARIO {
        int id PK
        string nome
        string email
        string cpf
        int perfil_id FK
    }
    
    PERFIL {
        int id PK
        string nome
        boolean is_admin
    }
    
    PRODUTO {
        int id PK
        string codigo_produto
        string nome
        string categoria
        boolean controla_lote
    }
    
    LOTE {
        int id PK
        string codigo_lote
        int quantidade_atual
        date data_vencimento
        int produto_id FK
        int deposito_id FK
        string status_qualidade
    }
    
    MOVIMENTACAO {
        int id PK
        string tipo
        int quantidade
        datetime data_hora
        int lote_id FK
        int responsavel_id FK
    }
    
    DEPOSITO {
        int id PK
        string nome
        int unidade_id FK
    }
    
    UNIDADE {
        int id PK
        string nome
        string cnpj
    }
    
    FORNECEDOR {
        int id PK
        string nome
        string cnpj
    }
    
    CONTROLE_QUALIDADE {
        int id PK
        int lote_id FK
        string classificacao
        date data_inspecao
    }
    
    %% Relacionamentos
    USUARIO }o--|| PERFIL : possui
    
    PRODUTO ||--o{ LOTE : possui
    LOTE }o--|| DEPOSITO : armazenado_em
    LOTE }o--|| FORNECEDOR : fornecido_por
    
    MOVIMENTACAO }o--|| LOTE : movimenta
    MOVIMENTACAO }o--|| USUARIO : realizada_por
    
    UNIDADE ||--o{ DEPOSITO : contem
    
    LOTE ||--o{ CONTROLE_QUALIDADE : inspecionado
