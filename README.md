## SETUP

## Ambiente de DEV
### Setup recomendado para Windows (Visual Studio Code + WSL2 + Docker + Git)
Subsistema recomendado: [Ubuntu](https://ubuntu.com/tutorials/install-ubuntu-on-wsl2-on-windows-10#1-overview).

**[Visual Studio Code](https://code.visualstudio.com/download)**
**[Postman](https://www.postman.com/downloads/)**
**[Docker for Windows](https://docs.docker.com/desktop/install/windows-install/)**
**[WSL2](https://docs.microsoft.com/pt-br/windows/wsl/install-win10)**
**[Git](https://git-scm.com/download/win)**
**[VS code + Docker + WSL2](https://code.visualstudio.com/blogs/2020/03/02/docker-in-wsl2)**

### **Setup recomendado para Linux** (Visual Studio Code + Docker + Git)
Distro recomendada: [Ubuntu](https://ubuntu.com/download/desktop).

**[Visual Studio Code](https://code.visualstudio.com/download)**
**[Postman](https://www.postman.com/downloads/)**
**[Docker](https://docs.docker.com/engine/install/ubuntu/)**
**[Git](https://git-scm.com/book/pt-br/v2/Come%C3%A7ando-Instalando-o-Git)**

## Começando
Para subir o projeto vai precisar:

- Instalar depedências com **composer update**.
- Subir os containers com **docker compose -f "docker-compose.yml" up -d --build**.
- Colocar as pastas *api/storage* e *api/vendor* com permissão 777.
- Gerar chave pro laravel com **php artisan key:generate**
- Subir o banco com **php artisan migrate**
- Subir a documentação com **php artisan l5-swagger:generate**.

#### OBS
Se tiver mesmo assim não subir, pode optar para executar o makefile, que tem a intenção de ser uma autoconfiguração da aplicação:

- Autoconfiguração com **make dev**.
- Entrar no bd com user: *furb* e password: *furb123* 