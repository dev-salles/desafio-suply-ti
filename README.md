# 🛣️ Desafio de Gestão de Trechos Rodoviários (Docker Edition)

Sistema desenvolvido para cadastro e espacialização de trechos rodoviários, integrando dados reais do DNIT em um ambiente conteinerizado.

## 🐳 Ambiente Docker
O projeto utiliza o **Docker** para padronizar o ambiente de desenvolvimento, garantindo que as versões do PHP (8.3), MySQL e Node.js sejam consistentes entre diferentes máquinas.

### 🛠️ Tecnologias e Funcionalidades
- **Ambiente:** Laravel Sail (Docker).
- **Backend:** PHP 8.3 + Laravel 11.
- **Frontend:** Vue.js 3 + Inertia.js + Tailwind CSS.
- **Integração:** Consumo de APIs Geoespaciais do DNIT.

## 🧠 Desafios de Engenharia Resolvidos
- **Persistência de Dados em Containers:** Gestão de volumes MySQL para evitar perda de dados e resolução de conflitos de integridade referencial (SQLSTATE 23000)].
- **Performance no Docker:** Tratamento do erro de memória `Out of sort memory` configurando índices de banco de dados diretamente via migrations].
- **Relacionamentos Complexos:** Implementação de `Eager Loading` no Eloquent para exibir nomes de rodovias (ex: BR-110) em vez de IDs crus na interface Vue.

## 🚀 Como Executar com Docker
1. Clone o repositório.
2. Copie o ambiente: `cp .env.example .env`.
3. Suba os containers: `./vendor/bin/sail up -d` (ou `docker-compose up -d`).
4. Execute as migrations dentro do container: `./vendor/bin/sail artisan migrate`.
5. Instale as dependências de front: `./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev`.