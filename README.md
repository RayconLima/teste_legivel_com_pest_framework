# 🐛 BugTracker - Guia de Testes

![PHP Version](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php)
![Laravel](https://img.shields.io/badge/Laravel-Framework-FF2D20?style=flat-square&logo=laravel)
![Pest](https://img.shields.io/badge/Pest-Testing-8B5CF6?style=flat-square)
![Docker](https://img.shields.io/badge/Docker-Container-2496ED?style=flat-square&logo=docker)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Database-336791?style=flat-square&logo=postgresql)

Sistema de rastreamento de bugs desenvolvido com Laravel, Vue.js e PostgreSQL, rodando em ambiente Docker.

---

## 📋 Índice

- [Pré-requisitos](#-pré-requisitos)
- [Configuração Inicial](#-configuração-inicial)
- [Comandos de Teste](#-comandos-de-teste)
  - [Execução Básica](#execução-básica)
  - [Testes por Tipo](#testes-por-tipo)
  - [Testes Específicos](#testes-específicos)
  - [Cobertura de Código](#cobertura-de-código)
- [Debugging](#-debugging)
  - [XDebug (Debugging Interativo)](#xdebug-debugging-interativo)
  - [Configuração PHPStorm](#configuração-phpstorm)
  - [Configuração VSCode](#configuração-vscode)
- [Database de Testes](#-database-de-testes)
- [CI/CD](#-cicd)
- [Troubleshooting](#-troubleshooting)

---

## 🚀 Pré-requisitos

- Docker & Docker Compose
- Make (geralmente já vem instalado no Linux/Mac)
- Git

### Verificar instalação

```bash
docker --version
docker compose version
make --version
```

---

## ⚙️ Configuração Inicial

### 1. Clone o repositório

```bash
git clone <seu-repositorio>
cd bugtracker
```

### 2. Configure o banco de dados de testes

```bash
make setup-test-db
```

Este comando:
- Cria as tabelas no banco de testes
- Executa todas as migrations
- Prepara o ambiente para rodar testes

### 3. (Opcional) Popular banco com dados de teste

```bash
make seed-test
```

---

## 🧪 Comandos de Teste

### Execução Básica

#### Executar todos os testes
```bash
make test
```

#### Ver menu de ajuda com todos os comandos
```bash
make help
```

---

### Testes por Tipo

#### Testes Unitários (Unit)
Testa funções e métodos isoladamente, sem dependências externas.

```bash
make test-unit
```

**Exemplo de uso:**
- Testar validações de modelos
- Testar helpers e utilitários
- Testar regras de negócio isoladas

#### Testes de Integração (Feature)
Testa funcionalidades completas, incluindo rotas, controllers e banco de dados.

```bash
make test-feature
```

**Exemplo de uso:**
- Testar endpoints da API
- Testar fluxos completos (criar, editar, deletar bugs)
- Testar autenticação e autorização

#### Testes em Paralelo
Acelera a execução dividindo testes entre múltiplos processos.

```bash
make test-parallel
```

**⚠️ Atenção:** Requer configuração adequada do banco de dados para suportar múltiplas conexões.

---

### Testes Específicos

#### Filtrar por nome de teste
```bash
make test-filter FILTER=BugTest
```

**Exemplos práticos:**
```bash
# Testar apenas criação de bugs
make test-filter FILTER=test_can_create_bug

# Testar classe específica
make test-filter FILTER=BugControllerTest

# Testar método específico
make test-filter FILTER=test_bug_validation
```

#### Executar grupo de testes
```bash
make test-group GROUP=bugs
```

**Como agrupar testes no Pest:**
```php
// Em seu teste
test('can create bug', function () {
    // ...
})->group('bugs', 'crud');

test('can delete bug', function () {
    // ...
})->group('bugs', 'crud', 'permissions');
```

**Exemplos de uso:**
```bash
make test-group GROUP=bugs      # Todos os testes de bugs
make test-group GROUP=crud      # Todos os testes CRUD
make test-group GROUP=api       # Todos os testes de API
```

---

### Cobertura de Código

A cobertura de código mostra quais linhas do seu código estão sendo testadas.

#### Relatório de cobertura no terminal
```bash
make coverage-pcov
```

**Saída esperada:**
```
Tests:    42 passed (145 assertions)
Duration: 2.34s

  Cov: 85.5% (2341/2738)
```

#### Relatório HTML (recomendado)
```bash
make coverage-html-pcov
```

Após executar, abra o relatório:
```bash
# O relatório estará em: coverage-report/index.html
# Abra no navegador para visualizar linha por linha
```

**O que você verá:**
- ✅ Linhas verdes: testadas
- ❌ Linhas vermelhas: não testadas
- ⚠️ Linhas amarelas: parcialmente testadas

---

## 🐞 Debugging

### XDebug (Debugging Interativo)

O XDebug permite pausar a execução do código e inspecionar variáveis em tempo real.

#### Ativar XDebug
```bash
make xdebug-on
```

#### Desativar XDebug
```bash
make xdebug-off
```

#### Verificar status
```bash
make xdebug-status
```

**⚠️ Importante:** XDebug deixa os testes mais lentos. Use apenas quando necessário debugar.

---

### Configuração PHPStorm

#### 1. Configurar Servidor PHP

1. Vá em `Settings → PHP → Servers`
2. Adicione um servidor com:
   - **Name:** `bugtracker-docker`
   - **Host:** `localhost`
   - **Port:** `8000` (ou a porta do seu app)
   - **Debugger:** `Xdebug`
   - Marque: ✅ `Use path mappings`
   - Mapeie: `/var/www` → `<caminho-local-do-projeto>`

#### 2. Configurar Interpretador PHP

1. Vá em `Settings → PHP`
2. CLI Interpreter: `Add → From Docker, Vagrant...`
3. Selecione `Docker Compose`
4. Service: `app`
5. Configuration file: `docker-compose.yml`

#### 3. Configurar Debug

1. Vá em `Settings → PHP → Debug`
2. Defina:
   - **Debug port:** `9003`
   - Marque: ✅ `Can accept external connections`
   - Marque: ✅ `Break at first line in PHP scripts`

#### 4. Usar o Debug

```bash
# 1. Ative o XDebug
make xdebug-on

# 2. No PHPStorm, clique no ícone de telefone (Start Listening)

# 3. Coloque breakpoints no código (clique na margem esquerda)

# 4. Execute o teste
make test-filter FILTER=BugTest

# 5. O PHPStorm pausará no breakpoint!
```

---

### Configuração VSCode

#### 1. Instalar extensão

- Instale: **PHP Debug** (Felix Becker)

#### 2. Criar configuração de debug

Crie `.vscode/launch.json`:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for XDebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www": "${workspaceFolder}"
            },
            "log": true
        }
    ]
}
```

#### 3. Usar o Debug

```bash
# 1. Ative o XDebug
make xdebug-on

# 2. No VSCode, pressione F5 ou vá em Run → Start Debugging

# 3. Coloque breakpoints (clique na margem esquerda)

# 4. Execute o teste
make test-filter FILTER=BugTest

# 5. O VSCode pausará no breakpoint!
```

---

## 💾 Database de Testes

### Resetar banco de testes
Remove todos os dados e recria as tabelas:
```bash
make setup-test-db
```

### Resetar e executar testes
```bash
make fresh-test
```

### Popular com dados de exemplo
```bash
make seed-test
```

### Limpar cache
Se testes estiverem com comportamento estranho:
```bash
make clear-test-cache
```

---

## 🚀 CI/CD

### Simular pipeline completo
```bash
make ci
```

Este comando executa:
1. ✅ Linting (Laravel Pint)
2. ✅ Testes Unitários
3. ✅ Testes de Integração
4. ✅ Relatório de Cobertura

### Apenas linting
```bash
make lint
```

### Corrigir código automaticamente
```bash
make lint-fix
```

---

## 🔧 Comandos Avançados

### Acessar container
```bash
make workspace  # ou make shell
```

### Executar Pest diretamente
```bash
make pest ARGS="--filter BugTest --stop-on-failure"
```

**Argumentos úteis do Pest:**
- `--filter=<nome>` - Filtrar testes
- `--stop-on-failure` - Para no primeiro erro
- `--bail` - Para no primeiro erro (alias)
- `--group=<grupo>` - Executar grupo
- `--exclude-group=<grupo>` - Excluir grupo
- `--coverage` - Mostrar cobertura
- `--profile` - Mostrar testes mais lentos

### Executar PHPUnit diretamente
```bash
make phpunit ARGS="--testdox"
```

---

## 🆘 Troubleshooting

### ❌ Testes falham: "Database connection error"

**Solução:**
```bash
# 1. Verifique se o container do PostgreSQL está rodando
docker compose ps

# 2. Reconfigure o banco de testes
make setup-test-db

# 3. Verifique o arquivo .env.testing
```

### ❌ XDebug não conecta

**Solução PHPStorm:**
```bash
# 1. Verifique se está ativado
make xdebug-status

# 2. Verifique as configurações:
#    - Port: 9003
#    - Path mappings corretos
#    - "Start Listening" ativado

# 3. Reinicie o container
docker compose restart app
```

**Solução VSCode:**
```bash
# 1. Verifique o launch.json
# 2. Port deve ser 9003
# 3. pathMappings: "/var/www" → "${workspaceFolder}"
```

### ❌ Testes lentos

**Soluções:**
```bash
# 1. Desative o XDebug se não estiver usando
make xdebug-off

# 2. Use testes em paralelo
make test-parallel

# 3. Execute apenas os testes necessários
make test-filter FILTER=BugTest
```

### ❌ Erro "Permission denied"

**Solução:**
```bash
# Acesse como root e ajuste permissões
make root
chown -R php_manaus:php_manaus /var/www/storage
chown -R php_manaus:php_manaus /var/www/bootstrap/cache
exit
```

### ❌ PCOV não gera relatório

**Solução:**
```bash
# 1. Verifique se está ativado
make pcov-status

# 2. Se não estiver, ative
make pcov-on

# 3. Execute novamente
make coverage-html-pcov
```

### ❌ Testes passam localmente mas falham no CI

**Verificações:**
```bash
# 1. Simule o ambiente de CI
make ci

# 2. Limpe cache
make clear-test-cache

# 3. Recrie o banco
make fresh-test

# 4. Verifique dependências
docker compose exec app composer install
```

---

## 📚 Recursos Adicionais

- [Documentação Pest](https://pestphp.com)
- [Documentação Laravel Testing](https://laravel.com/docs/testing)
- [XDebug Documentation](https://xdebug.org/docs)
- [Laravel Pint](https://laravel.com/docs/pint)

---

## 🎯 Boas Práticas

### ✅ Sempre faça

- Execute testes antes de commits: `make test`
- Mantenha cobertura acima de 80%: `make coverage-pcov`
- Use nomes descritivos nos testes
- Agrupe testes relacionados com `->group()`
- Desative XDebug quando não precisar

### ❌ Evite

- Commitar com testes falhando
- Deixar XDebug sempre ativado (performance)
- Usar dados reais em testes
- Pular testes sem motivo (`skip()`)

---

## 📝 Exemplo de Fluxo de Trabalho

```bash
# 1. Antes de começar a trabalhar
make workspace
php artisan migrate:fresh --seed

# 2. Durante o desenvolvimento
make test-filter FILTER=BugTest  # Teste específico
make xdebug-on                   # Se precisar debugar
make xdebug-off                  # Quando terminar

# 3. Antes de commitar
make lint                        # Verificar código
make test                        # Todos os testes
make coverage-pcov              # Verificar cobertura

# 4. Se tudo passar
git add .
git commit -m "feat: implementa feature X"
git push
```

---

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch: `git checkout -b feature/nova-feature`
3. Escreva testes para nova funcionalidade
4. Implemente a funcionalidade
5. Execute: `make ci`
6. Commit: `git commit -m 'feat: adiciona nova feature'`
7. Push: `git push origin feature/nova-feature`
8. Abra um Pull Request

---

## 📄 Licença

Este projeto está sob a licença MIT.

---

**Desenvolvido com ❤️ pela equipe BugTracker**