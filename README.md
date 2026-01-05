# 🛣️ Desafio de Gestão de Trechos Rodoviários (Docker Edition)

Sistema desenvolvido para cadastro e espacialização de trechos rodoviários, integrando dados reais do DNIT em um ambiente conteinerizado.

## 🌐 Ambiente em Deploy
Você pode acessar a versão estável do projeto através do link abaixo:
> **Link:** [https://desafio-suply-ti.onrender.com/](https://desafio-suply-ti.onrender.com/)

---

### ⚠️ Status do Projeto & Disponibilidade
> **PROJETO EM CONSTRUÇÃO:** Informamos que o sistema ainda está passando por atualizações. Algumas funcionalidades no ambiente de deploy estão sendo refinadas para garantir a melhor experiência e performance.
> 
> **Nota sobre o Servidor:** O projeto está hospedado em uma instância gratuita no Render. Por este motivo, o ambiente entra em modo de repouso após períodos de inatividade. 
> 
> **Se o link demorar para carregar, por favor aguarde entre 1 a 2 minutos** para que a build da aplicação seja reiniciada automaticamente.

---

## 🐳 Ambiente Docker
O projeto utiliza o **Docker** para padronizar o ambiente de desenvolvimento, garantindo que as versões do PHP (8.3), MySQL e Node.js sejam consistentes entre diferentes máquinas.

### 🛠️ Tecnologias e Funcionalidades
- **Ambiente:** Laravel Sail (Docker).
- **Backend:** PHP 8.3 + Laravel 11.
- **Frontend:** Vue.js 3 + Inertia.js + Tailwind CSS.
- **Integração:** Consumo de APIs Geoespaciais do DNIT.

## 🧠 Desafios de Engenharia Resolvidos
- **Persistência de Dados em Containers:** Gestão de volumes MySQL para evitar perda de dados e resolução de conflitos de integridade referencial.
- **Performance no Docker:** Tratamento do erro de memória `Out of sort memory` configurando índices de banco de dados diretamente via migrations.
- **Relacionamentos Complexos:** Implementação de `Eager Loading` no Eloquent para exibir nomes de rodovias (ex: BR-110) em vez de IDs crus na interface Vue.
- **Bypass de Bloqueio de Rede:** Implementação de busca de GeoJSON via Client-side (Axios no Vue) para contornar restrições de IP em ambientes de produção (Render).

## 🚀 Como Executar com Docker
1. Clone o repositório.
2. Copie o ambiente: `cp .env.example .env`.
3. Instalação das Dependências (Sem PHP local):
    `docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs`
5. Suba os containers: `./vendor/bin/sail up -d` (ou `docker-compose up -d`).
6. Execute as migrations dentro do container: `./vendor/bin/sail artisan key:generate` `./vendor/bin/sail artisan migrate --seed`.
7. Instale as dependências de front: `./vendor/bin/sail npm install && ./vendor/bin/sail npm run dev`.
