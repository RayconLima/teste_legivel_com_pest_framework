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
- [Makefile - Guia de Comandos](#makefile---guia-de-comandos)
  - [Workspace & Shell](#workspace--shell)
  - [Testes - Execução](#testes---execução)
  - [Testes - Cobertura](#testes---cobertura-de-código)
  - [Database de Testes](#database-de-testes)
  - [XDebug - Debug Interativo](#xdebug---debug-interativo)
  - [PCOV - Cobertura de Código](#pcov---cobertura-de-código)
  - [Linting & Code Style](#linting--code-style)
  - [CI/CD](#cicd)
  - [SonarQube - Análise de Código](#sonarqube---análise-de-código)
- [Debugging Avançado](#-debugging-avançado)
  - [XDebug no PHPStorm](#configuração-phpstorm)
  - [XDebug no VSCode](#configuração-vscode)
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

## 🧪 Comandos de Teste

### Execução Básica

#### Ver todos os comandos disponíveis
```bash
make help
```

Mostra um menu interativo com todos os comandos Makefile disponíveis.

#### Executar todos os testes
```bash
make test
```

Executa a suite completa de testes (Unit + Feature).

---

## Makefile - Guia de Comandos

### Workspace & Shell

#### Acessar o container como usuário padrão
```bash
make workspace
# ou
make shell
```

Acessa o bash do container com permissões de usuário `php_manaus`.

**Exemplos de uso:**
```bash
make workspace
cd app
php artisan tinker          # Acessa console interativo do Laravel
php artisan migrate:fresh   # Recria migrations
```

#### Acessar como root
```bash
make root
```

Acessa o bash com permissões de root. Use para operações que exigem privilégios elevados.

---

### Testes - Execução

#### Testes Unitários
```bash
make test-unit
```

Executa apenas testes da suite `Unit` (testes isolados, sem dependências externas).

**Quando usar:**
- Testar funções e métodos isoladamente
- Testar validações de modelos
- Testar helpers e utilitários
- Testar regras de negócio sem banco de dados

**Exemplo prático:**
```bash
make test-unit
# Tests: 15 passed (120 assertions)
```

#### Testes de Integração (Feature)
```bash
make test-feature
```

Executa apenas testes da suite `Feature` (testes completos com rotas, controllers, banco).

**Quando usar:**
- Testar endpoints da API
- Testar fluxos completos (CRUD)
- Testar autenticação e autorização
- Testar interações entre componentes

**Exemplo prático:**
```bash
make test-feature
# Tests: 27 passed (85 assertions)
```

#### Testes em Paralelo
```bash
make test-parallel
```

Executa todos os testes dividindo entre múltiplos processos para maior velocidade.

**⚠️ Atenção:** Requer configuração adequada do banco para suportar múltiplas conexões.

**Quando usar:**
- Suite de testes grande (>100 testes)
- Quando precisa otimizar tempo de CI/CD
- Em ambiente com múltiplos cores disponíveis

#### Filtrar Testes por Nome
```bash
make test-filter FILTER=BugTest
```

Executa apenas testes cujos nomes correspondem ao filtro.

**Exemplos práticos:**
```bash
# Testar apenas uma classe
make test-filter FILTER=BugControllerTest

# Testar apenas um método
make test-filter FILTER=test_can_create_bug

# Testar padrão (partial match)
make test-filter FILTER=bug
```

#### Executar Grupo de Testes
```bash
make test-group GROUP=bugs
```

Executa todos os testes de um grupo específico.

**Como agrupar testes no Pest:**
```php
// tests/Feature/BugControllerTest.php

test('can create bug', function () {
    // ...
})->group('bugs', 'crud');

test('can update bug', function () {
    // ...
})->group('bugs', 'crud');

test('can delete bug', function () {
    // ...
})->group('bugs', 'permissions');
```

**Exemplos de uso:**
```bash
make test-group GROUP=bugs           # Todos de bugs
make test-group GROUP=crud           # Todos CRUD
make test-group GROUP=permissions    # Todos de permissões
```

---

### Testes - Cobertura de Código

A cobertura mostra quais linhas do seu código estão sendo testadas. PCOV é mais rápido que XDebug.

#### Relatório de Cobertura no Terminal
```bash
make test-coverage
```

Executa testes e mostra percentual de cobertura no terminal.

**Saída esperada:**
```
Tests:    42 passed (145 assertions)
Duration: 2.34s

  Cov: 85.5% (2341/2738)
```

#### Relatório HTML (Recomendado)
```bash
make test-coverage-html
```

Gera um relatório visual em HTML no diretório `coverage-report/`.

**O que você verá:**
- ✅ **Linhas verdes:** testadas
- ❌ **Linhas vermelhas:** não testadas
- ⚠️ **Linhas amarelas:** parcialmente testadas

**Abrindo o relatório:**
```bash
# Abra no navegador
open coverage-report/index.html          # macOS
xdg-open coverage-report/index.html      # Linux
start coverage-report/index.html         # Windows
```

#### Relatório XML (Para SonarQube)
```bash
make test-coverage-xml
```

Gera relatório em formato Clover XML (`coverage.xml`) para integração com SonarQube.

**Quando usar:**
- Integrar com SonarQube
- Enviar para serviços de CI/CD
- Gerar análises de qualidade automatizadas

---

### Database de Testes

#### Configurar Banco de Testes
```bash
make setup-test-db
```

Configura o banco de dados de testes executando migrations.

**O que faz:**
- Cria as tabelas do banco de testes
- Executa todas as migrations
- Prepara o ambiente para testes

#### Reseta Banco e Executa Testes
```bash
make fresh-test
```

Apaga e recria o banco de testes, depois executa todos os testes.

**Quando usar:**
- Verificar se testes passam com banco limpo
- Antes de fazer commit
- Quando testes estão com comportamento estranho

#### Popular com Dados de Teste
```bash
make seed-test
```

Popula o banco de testes com dados de exemplo.

**Use para:**
- Ter dados de teste disponíveis durante testes
- Verificar comportamento com dados reais
- Testar paginação, filtros, etc.

#### Limpar Cache de Testes
```bash
make clear-test-cache
```

Limpa cache e config cache do Laravel. Útil quando testes têm comportamento estranho.

---

### XDebug - Debug Interativo

XDebug permite pausar a execução e inspecionar variáveis em tempo real.

#### Ativar XDebug
```bash
make xdebug-on
```

**Após ativar:**
1. Configure sua IDE (PHPStorm/VSCode)
2. Coloque breakpoints
3. Inicie listening na IDE
4. Execute os testes

#### Desativar XDebug
```bash
make xdebug-off
```

**⚠️ Importante:** XDebug deixa os testes ~5-10x mais lentos. Desative quando não estiver usando.

#### Verificar Status
```bash
make xdebug-status
```

Verifica se XDebug está ativado ou desativado.

**Saída esperada:**
```bash
# Se ativado:
✓ XDebug está ativado

# Se desativado:
✗ XDebug está desativado
```

---

### PCOV - Cobertura de Código

PCOV é uma alternativa mais rápida ao XDebug para gerar relatórios de cobertura.

#### Ativar PCOV
```bash
make pcov-on
```

#### Desativar PCOV
```bash
make pcov-off
```

#### Verificar Status
```bash
make pcov-status
```

---

### Linting & Code Style

#### Verificar Estilo de Código (Pint)
```bash
make lint
```

Verifica se o código segue as regras do Laravel Pint (PSR-12).

**Saída esperada:**
```bash
# Se tudo OK:
✓ Code style is correct

# Se houver problemas:
❌ File: app/Models/Bug.php (line 42)
```

#### Corrigir Código Automaticamente
```bash
make lint-fix
```

Corrige automaticamente problemas de estilo usando Pint.

**Exemplo:**
```bash
make lint-fix
# Corrigido: app/Models/Bug.php
# Corrigido: app/Http/Controllers/BugController.php
```

---

### CI/CD

#### Simular Pipeline Completo
```bash
make ci
```

Executa toda a pipeline de CI (linting + testes unit + testes feature + cobertura).

**O que faz:**
1. ✅ Linting (Laravel Pint)
2. ✅ Testes Unitários
3. ✅ Testes de Integração
4. ✅ Relatório de Cobertura XML

**Tempo esperado:** ~30-60 segundos (dependendo da quantidade de testes)

---

### Comandos Avançados

#### Executar Pest Diretamente
```bash
make pest ARGS="--filter BugTest --stop-on-failure"
```

Executa Pest com argumentos customizados.

**Argumentos úteis:**
- `--filter=<nome>` - Filtrar testes
- `--stop-on-failure` ou `--bail` - Para no primeiro erro
- `--group=<grupo>` - Executar grupo
- `--exclude-group=<grupo>` - Excluir grupo
- `--coverage` - Mostrar cobertura
- `--profile` - Mostrar testes mais lentos
- `--testdox` - Formato de saída legível

**Exemplos:**
```bash
# Para no primeiro erro
make pest ARGS="--bail"

# Mostrar testes mais lentos
make pest ARGS="--profile"

# Saída estilo BDD
make pest ARGS="--testdox"
```

#### Executar PHPUnit Diretamente
```bash
make phpunit ARGS="--testdox"
```

Executa PHPUnit com argumentos customizados.

---

### SonarQube - Análise de Código

SonarQube fornece análise contínua de código para detectar bugs, vulnerabilidades e cobertura de testes.

#### Iniciar SonarQube
```bash
make sonar-up
```

Inicia os containers do SonarQube e PostgreSQL necessários para análise.

**Após iniciar:**
1. Aguarde ~30 segundos para SonarQube estar pronto
2. Acesse: http://localhost:9000
3. Login padrão: admin / admin

#### Executar Análise
```bash
make sonar-scan SONAR_TOKEN=seu_token_aqui
```

Executa a análise completa do código:
1. 🧪 Gera relatório de cobertura (coverage.xml)
2. 📊 Executa análise SonarQube
3. ✅ Exibe link para dashboard

**Como obter um token:**
```bash
# 1. Acesse http://localhost:9000
# 2. Login: admin / admin
# 3. Vá em: Profile → Security → Generate Token
# 4. Use o token no comando acima
```

**Exemplo completo:**
```bash
# 1. Iniciar SonarQube
make sonar-up

# 2. Gerar um token na interface web

# 3. Executar análise
make sonar-scan SONAR_TOKEN=sqa_e497345a46d9ba3976dc4d378a6ba7aa5e48363f

# 4. Ver resultados em http://localhost:9000
```

#### Ver Logs do SonarQube
```bash
make sonar-logs
```

Mostra logs em tempo real do SonarQube (útil para troubleshooting).

#### Parar SonarQube
```bash
make sonar-down
```

Para os containers do SonarQube e PostgreSQL sem apagar dados.

#### Resetar SonarQube
```bash
make sonar-reset
```

**⚠️ Atenção:** Remove completamente todos os dados e históricos do SonarQube.

```bash
make sonar-reset
# Todos os dados do SonarQube foram apagados!
```

---

#### Configuração do SonarQube

O projeto está configurado com `sonar-project.properties`:

**O que é analisado:**
- ✅ Código em `app/` (excluindo Providers, Middleware, Exceptions)
- ✅ Testes em `tests/`
- ✅ Relatório de cobertura do PCOV

**Exclusões:**
- ❌ vendor/, node_modules/, storage/
- ❌ Migrations, Seeders, Factories
- ❌ Arquivos de configuração
- ❌ Views e recursos estáticos

**Quality Gate:**
- Aguarda resultado automático
- Timeout: 300 segundos

---

#### Dashboard do SonarQube

**Métricas disponíveis:**

| Métrica | Descrição |
|---------|-----------|
| **Bugs** | Problemas críticos encontrados |
| **Vulnerabilidades** | Riscos de segurança |
| **Code Smells** | Problemas de qualidade |
| **Coverage** | Percentual de cobertura de testes |
| **Duplications** | Código duplicado |
| **Complexity** | Complexidade do código |

**Acessar dashboard:**
```bash
# 1. Abra http://localhost:9000 no navegador
# 2. Procure pelo projeto "Bug Tracker - Laravel"
# 3. Explore as métricas e issues encontradas
```

---

## 🐞 Debugging Avançado

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

---

## 🧪 Boas Práticas com Makefile

### ✅ Workflow Recomendado (Básico)

```bash
# 1. Iniciar trabalho
make workspace
make setup-test-db

# 2. Desenvolver e testar
make test-filter FILTER=BugTest      # Teste específico rápido
make xdebug-on                       # Se precisar debugar
make test-filter FILTER=BugTest      # Rodar teste com debug
make xdebug-off                      # Desligar debug

# 3. Antes de commitar
make lint                            # Verificar estilo
make test                            # Todos os testes
make test-coverage-html              # Verificar cobertura

# 4. Antes de push
make ci                              # Simular CI/CD completo
```

### ✅ Workflow Completo (Com SonarQube)

```bash
# 1. Iniciar ambiente
make workspace
make setup-test-db
make sonar-up                        # Inicia SonarQube

# 2. Desenvolver e testar
make test-filter FILTER=BugTest      # Teste específico rápido
make xdebug-on                       # Se precisar debugar
make test-filter FILTER=BugTest
make xdebug-off

# 3. Antes de commitar
make lint                            # Verificar estilo
make test                            # Todos os testes
make test-coverage-html              # Verificar cobertura

# 4. Análise de qualidade
make sonar-scan SONAR_TOKEN=seu_token  # Análise completa
# Acesse http://localhost:9000 para ver resultados

# 5. Antes de push
make ci                              # Pipeline final

# 6. Quando terminar
make sonar-down                      # Parar SonarQube (opcional)
```

### ✅ Otimizando Velocidade

```bash
# Mais rápido: apenas o que você está desenvolvendo
make test-filter FILTER=YourFeature

# Paralelo para suite grande
make test-parallel

# Sem cobertura (mais rápido)
make test

# Com cobertura apenas quando necessário
make test-coverage-html
```

### ✅ Sempre Faça

- Execute `make lint` antes de commits
- Mantenha cobertura acima de 80%: `make test-coverage`
- Use nomes descritivos nos testes
- Agrupe testes relacionados com `->group()`
- Desative XDebug quando não precisar
- Execute `make ci` antes de push para validação final
- Execute análise SonarQube regularmente para detectar issues

### ❌ Evite

- Commitar com testes falhando
- Deixar XDebug sempre ativado (impacta performance)
- Usar dados reais em testes
- Pular testes sem motivo (`skip()`)
- Deixar código desformatado (use `make lint-fix`)
- Ignorar warnings do SonarQube (Code Smells, Vulnerabilidades)
- Deixar cobertura cair abaixo de 80%

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

### ❌ SonarQube não inicia

**Solução:**
```bash
# 1. Verifique se o container está rodando
docker compose ps | grep sonarqube

# 2. Veja os logs
make sonar-logs

# 3. Se não iniciar, resetar e tentar novamente
make sonar-reset
make sonar-up

# 4. Aguarde 30-60 segundos para inicializar
```

### ❌ Erro ao executar sonar-scan

**Solução:**
```bash
# 1. Certifique-se que SonarQube está rodando
make sonar-up

# 2. Verifique o token (deve estar válido)
make sonar-scan SONAR_TOKEN=seu_token_correto

# 3. Se o token expirou, gere um novo:
#    - Acesse http://localhost:9000
#    - Login: admin / admin
#    - Profile → Security → Generate New Token
```

### ❌ Análise SonarQube muito lenta

**Soluções:**
```bash
# 1. Execute a análise sem cobertura (mais rápido)
# Edite sonar-project.properties e comente a linha:
# sonar.php.coverage.reportPaths=coverage.xml

# 2. Ou execute apenas testes necessários antes:
make test-filter FILTER=YourTest

# 3. Use cache do SonarQube:
#    As análises subsequentes são mais rápidas
```

---

## 📚 Recursos Adicionais

- [Documentação Pest](https://pestphp.com)
- [Documentação Laravel Testing](https://laravel.com/docs/testing)
- [XDebug Documentation](https://xdebug.org/docs)
- [Laravel Pint](https://laravel.com/docs/pint)

---

## 🎯 Tabela de Comandos por Cenário

| Cenário | Comando | Tempo |
|---------|---------|-------|
| Primeira execução | `make setup-test-db` | 10s |
| Testar uma feature | `make test-filter FILTER=FeatureName` | 5-15s |
| Testar tudo | `make test` | 15-30s |
| Testar em paralelo | `make test-parallel` | 8-20s |
| Cobertura HTML | `make test-coverage-html` | 30-60s |
| Debugar um teste | `make xdebug-on && make test-filter FILTER=Test` | variável |
| Code style check | `make lint` | 5s |
| Code style fix | `make lint-fix` | 5s |
| Simular CI completo | `make ci` | 45-90s |
| Resetar banco | `make fresh-test` | 15-30s |
| Limpar cache | `make clear-test-cache` | 2s |
| Iniciar SonarQube | `make sonar-up` | 30s |
| Análise SonarQube | `make sonar-scan SONAR_TOKEN=xxx` | 30-60s |
| Ver logs SonarQube | `make sonar-logs` | contínuo |
| Parar SonarQube | `make sonar-down` | 5s |

---

## 📖 Guia Rápido Inicial

### Primeira vez usando?

```bash
# 1. Configurar ambiente
docker compose up -d                # Inicia containers
make setup-test-db                  # Configura banco de testes

# 2. Executar um teste rápido
make test-filter FILTER=BugTest

# 3. Ver todos os comandos
make help
```

### Desenvolver uma nova feature

```bash
# 1. Acessar container
make workspace

# 2. Se precisar debugar
make xdebug-on
make test-filter FILTER=YourTest
make xdebug-off

# 3. Antes de commitar
make lint-fix
make test-coverage-html
```

### Antes de fazer push

```bash
# Simula exatamente o que o CI/CD vai fazer
make ci
```

### Análise de Qualidade com SonarQube

```bash
# 1. Iniciar SonarQube (primeira vez)
make sonar-up

# 2. Gerar token em http://localhost:9000
#    Profile → Security → Generate Token

# 3. Executar análise
make sonar-scan SONAR_TOKEN=seu_token_aqui

# 4. Visualizar resultados
#    Acesse http://localhost:9000 no navegador
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