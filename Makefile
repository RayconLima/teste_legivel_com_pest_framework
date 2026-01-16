# ==========================================
# Configurações
# ==========================================
# 💡 TIP: Execute 'make help' para ver todos os comandos disponíveis
# 📖 Para documentação completa, veja o README.md
#
# Comandos mais usados:
#   make test              → Executa todos os testes
#   make test-filter       → Executa testes específicos
#   make test-coverage-html → Gera relatório de cobertura visual
#   make lint              → Verifica estilo de código
#   make ci                → Simula pipeline completa de CI/CD
#
.PHONY: help workspace test test-unit test-feature test-parallel test-coverage test-coverage-html test-coverage-xml test-filter test-group setup-test-db fresh-test xdebug-on xdebug-off xdebug-status pcov-on pcov-off

.DEFAULT_GOAL := help

DOCKER_COMPOSE := $(shell if docker compose version >/dev/null 2>&1; then echo "docker compose"; else echo "docker-compose"; fi)
PHP_USER := php_manaus

# Cores para output
RED := \033[0;31m
GREEN := \033[0;32m
YELLOW := \033[0;33m
BLUE := \033[0;34m
NC := \033[0m # No Color

# ==========================================
# Help
# ==========================================
help: ## Mostra este menu de ajuda
	@echo "$(BLUE)╔════════════════════════════════════════════════════════╗$(NC)"
	@echo "$(BLUE)║          🐛 Sistema de Bugs - Comandos Make          ║$(NC)"
	@echo "$(BLUE)╚════════════════════════════════════════════════════════╝$(NC)"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "$(GREEN)%-20s$(NC) %s\n", $1, $2}'
	@echo ""

# ==========================================
# Workspace & Shell
# ==========================================
workspace: ## Acessa o bash do container como usuário php_manaus
	@echo "$(BLUE)→ Acessando workspace...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app bash

shell: workspace ## Alias para workspace

root: ## Acessa o bash do container como root
	@echo "$(YELLOW)⚠ Acessando como root...$(NC)"
	$(DOCKER_COMPOSE) exec -u root app bash

# ==========================================
# Testes - Execução
# ==========================================
test: ## Executa todos os testes
	@echo "$(BLUE)→ Executando todos os testes...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan test

test-unit: ## Executa apenas testes unitários
	@echo "$(BLUE)→ Executando testes unitários...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan test --testsuite=Unit

test-feature: ## Executa apenas testes de integração
	@echo "$(BLUE)→ Executando testes de integração...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan test --testsuite=Feature

test-parallel: ## Executa testes em paralelo
	@echo "$(BLUE)→ Executando testes em paralelo...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan test --parallel

test-filter: ## Executa teste específico (use: make test-filter FILTER=BugTest)
	@echo "$(BLUE)→ Executando testes com filtro: $(FILTER)$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan test --filter=$(FILTER)

test-group: ## Executa grupo de testes (use: make test-group GROUP=bugs)
	@echo "$(BLUE)→ Executando grupo de testes: $(GROUP)$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan test --group=$(GROUP)

# ==========================================
# Testes - Cobertura (PCOV)
# ==========================================
test-coverage: pcov-on ## Executa testes com relatório de cobertura básico
	@echo "$(BLUE)→ Executando testes com cobertura (PCOV)...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) -e XDEBUG_MODE=off app php artisan test --coverage --min=80
	@$(MAKE) pcov-off

test-coverage-html: pcov-on ## Gera relatório HTML de cobertura
	@echo "$(BLUE)→ Gerando relatório HTML de cobertura...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) -e XDEBUG_MODE=off app ./vendor/bin/pest --coverage --coverage-html=coverage-report
	@echo "$(GREEN)✓ Relatório gerado em: coverage-report/index.html$(NC)"
	@$(MAKE) pcov-off

test-coverage-xml: pcov-on ## Gera relatório XML de cobertura (Clover)
	@echo "$(BLUE)→ Gerando relatório XML de cobertura...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) -e XDEBUG_MODE=off app ./vendor/bin/pest --coverage --coverage-clover=coverage.xml
	@echo "$(GREEN)✓ Relatório gerado: coverage.xml$(NC)"
	@$(MAKE) pcov-off

coverage-xml:
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) -e PCOV_ENABLED=1 -e XDEBUG_MODE=off app php -d pcov.enabled=1 ./vendor/bin/pest --coverage --coverage-clover=coverage.xml

# executar-relatorio-de-cobertura: ## Executa testes com relatório de cobertura PCOV
# 	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php -d pcov.enabled=1 artisan test --coverage

# executar-relatorio-de-cobertura-web: ## Executa testes com relatório de cobertura HTML PCOV
# 	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php -d pcov.enabled=1 ./vendor/bin/pest --coverage --coverage-html=coverage-report
	
# ==========================================
# Database para Testes
# ==========================================
setup-test-db: ## Configura banco de dados de teste
	@echo "$(BLUE)→ Configurando banco de testes...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan migrate:fresh --env=testing
	@echo "$(GREEN)✓ Banco de testes configurado$(NC)"

fresh-test: ## Reseta banco e executa testes
	@echo "$(BLUE)→ Resetando banco e executando testes...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan migrate:fresh --env=testing
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan test

seed-test: ## Popula banco de testes com dados
	@echo "$(BLUE)→ Populando banco de testes...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan db:seed --env=testing

# ==========================================
# XDebug - Debug Interativo
# ==========================================
xdebug-on: ## Ativa XDebug para debugging
	@echo "$(YELLOW)→ Ativando XDebug...$(NC)"
	$(DOCKER_COMPOSE) exec -u root app bash -c "echo 'zend_extension=xdebug.so' > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
	$(DOCKER_COMPOSE) exec -u root app bash -c "cat /var/www/docker/php/xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
	$(DOCKER_COMPOSE) exec -u root app bash -c "kill -USR2 1"
	@echo "$(GREEN)✓ XDebug ativado$(NC)"

xdebug-off: ## Desativa XDebug
	@echo "$(YELLOW)→ Desativando XDebug...$(NC)"
	$(DOCKER_COMPOSE) exec -u root app bash -c "rm -f /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
	$(DOCKER_COMPOSE) exec -u root app bash -c "kill -USR2 1"
	@echo "$(GREEN)✓ XDebug desativado$(NC)"

xdebug-status: ## Verifica status do XDebug
	@echo "$(BLUE)→ Status do XDebug:$(NC)"
	@$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php -m | grep -i xdebug && echo "$(GREEN)✓ XDebug está ativado$(NC)" || echo "$(RED)✗ XDebug está desativado$(NC)"

# ==========================================
# PCOV - Cobertura de Código
# ==========================================
pcov-on: ## Ativa PCOV para cobertura
	@echo "$(YELLOW)→ Ativando PCOV...$(NC)"
	@$(DOCKER_COMPOSE) exec -u root app bash -c "echo 'extension=pcov.so' > /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini"
	@$(DOCKER_COMPOSE) exec -u root app bash -c "cat /var/www/docker/php/pcov.ini >> /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini"
	@$(DOCKER_COMPOSE) exec -u root app bash -c "kill -USR2 1"
	@echo "$(GREEN)✓ PCOV ativado$(NC)"

pcov-off: ## Desativa PCOV
	@echo "$(YELLOW)→ Desativando PCOV...$(NC)"
	@$(DOCKER_COMPOSE) exec -u root app bash -c "rm -f /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini"
	@$(DOCKER_COMPOSE) exec -u root app bash -c "kill -USR2 1"
	@echo "$(GREEN)✓ PCOV desativado$(NC)"

pcov-status: ## Verifica status do PCOV
	@echo "$(BLUE)→ Status do PCOV:$(NC)"
	@$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php -m | grep -i pcov && echo "$(GREEN)✓ PCOV está ativado$(NC)" || echo "$(RED)✗ PCOV está desativado$(NC)"

# ==========================================
# Comandos Úteis de Desenvolvimento
# ==========================================
pest: ## Executa Pest diretamente (use: make pest ARGS="--filter BugTest")
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app ./vendor/bin/pest $(ARGS)

phpunit: ## Executa PHPUnit diretamente
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app ./vendor/bin/phpunit $(ARGS)

clear-test-cache: ## Limpa cache de testes
	@echo "$(BLUE)→ Limpando cache de testes...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan cache:clear
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app php artisan config:clear
	@echo "$(GREEN)✓ Cache limpo$(NC)"

# ==========================================
# CI/CD Simulation
# ==========================================
ci: ## Simula pipeline de CI (lint + tests + coverage)
	@echo "$(BLUE)╔════════════════════════════════════════════════════════╗$(NC)"
	@echo "$(BLUE)║             🚀 Simulando Pipeline CI/CD               ║$(NC)"
	@echo "$(BLUE)╚════════════════════════════════════════════════════════╝$(NC)"
	@echo ""
	@echo "$(YELLOW)→ Passo 1: Linting...$(NC)"
	@$(MAKE) lint || true
	@echo ""
	@echo "$(YELLOW)→ Passo 2: Testes Unitários...$(NC)"
	@$(MAKE) test-unit
	@echo ""
	@echo "$(YELLOW)→ Passo 3: Testes de Integração...$(NC)"
	@$(MAKE) test-feature
	@echo ""
	@echo "$(YELLOW)→ Passo 4: Cobertura de Código...$(NC)"
	@$(MAKE) test-coverage-xml
	@echo ""
	@echo "$(GREEN)✓ Pipeline concluído com sucesso!$(NC)"

lint: ## Executa linter (Pint)
	@echo "$(BLUE)→ Executando Laravel Pint...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app ./vendor/bin/pint --test

lint-fix: ## Corrige código com Pint
	@echo "$(BLUE)→ Corrigindo código com Pint...$(NC)"
	$(DOCKER_COMPOSE) exec -u $(PHP_USER) app ./vendor/bin/pint

# ==========================================
# SonarQube Integration
# ==========================================
sonar-up:
	$(DOCKER_COMPOSE) up -d sonarqube postgres_sonar

sonar-down:
	$(DOCKER_COMPOSE) stop sonarqube postgres_sonar

sonar-logs:
	$(DOCKER_COMPOSE) logs -f sonarqube

sonar-scan:
	@echo "🧪 Gerando relatório de cobertura..."
	@$(MAKE) coverage-xml
	@echo "📊 Executando análise SonarQube..."
	docker run --rm \
		--network=teste_legivel_com_pest_framework_teste_legivel_com_pest \
		-v "$(PWD):/usr/src" \
		sonarsource/sonar-scanner-cli \
		-Dsonar.host.url=http://sonarqube:9000 \
		-Dsonar.login=$(SONAR_TOKEN)
	@echo "✅ Análise concluída! Acesse: http://localhost:9000"

sonar-reset:
	$(DOCKER_COMPOSE) down sonarqube postgres_sonar -v
	@echo "⚠️  Todos os dados do SonarQube foram apagados!"