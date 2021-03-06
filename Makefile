include vars.mk

IMAGES = kubikvest/api
CONTAINERS ?= kubikvest_db kubikvest kubikvest_nginx
DOCKER_RM = false

build:
	@docker build -t kubikvest/api .

build-dev: build
	@docker build -t kubikvest/api-dev -f tests/Dockerfile .

composer:
	@-docker run --rm -v $(CURDIR):/data imega/composer install $(COMPOSER_FLAGS)

start: composer build
	@docker run -d --name "kubikvest_db" -v $(CURDIR)/mysql.conf.d:/etc/mysql/conf.d imega/mysql

	@docker run --rm \
		--link kubikvest_db:kubikvest_db \
		imega/mysql-client \
		mysqladmin --silent --host=kubikvest_db --wait=5 ping

	@docker run --rm \
		-v $(CURDIR)/sql:/sql \
		--link kubikvest_db:kubikvest_db \
		imega/mysql-client \
		mysql --host=kubikvest_db -e "source /sql/kubikvest.sql"

	@docker run -d \
		--name "kubikvest" \
		--link kubikvest_db:kubikvest_db \
		-v $(CURDIR):/app \
		-e VK_CLIENT_ID=$(VK_CLIENT_ID) \
		-e VK_CLIENT_SECRET=$(VK_CLIENT_SECRET) \
		-e VK_REDIRECT_URI=$(VK_REDIRECT_URI) \
		-e URI_OAUTH_VK=$(URI_OAUTH_VK) \
		-e URL=$(URL) \
		-e KEY=$(KEY) \
		kubikvest/api \
		php-fpm7 -F \
			-d ENV[VK_CLIENT_ID]=1122 \
			-d error_reporting=E_ALL \
			-d log_errors=On \
			-d error_log=/dev/stdout \
			-d display_errors=On \
			-d always_populate_raw_post_data=-1

	@docker run -d \
		--name "kubikvest_nginx" \
		--link kubikvest:service \
		-p 8300:80 \
		kubikvest/nginx

test: COMPOSER_FLAGS = --ignore-platform-reqs --no-interaction
test: composer build-dev
	cd tests/mock-servers/vk;make start

	@docker run -d --name "kubikvest_db" imega/mysql

	@-docker run --rm \
		-v $(CURDIR)/sql:/sql \
		--link kubikvest_db:kubikvest_db \
		imega/mysql-client \
		mysql --host=kubikvest_db -e "source /sql/kubikvest.sql"

	@docker run -d \
		--name "kubikvest" \
		--link "mock_server_vk:vk-server" \
		--link kubikvest_db:kubikvest_db \
		-v $(CURDIR):/app \
		-e VK_CLIENT_ID=$(VK_CLIENT_ID) \
		-e VK_CLIENT_SECRET=$(VK_CLIENT_SECRET) \
		-e VK_REDIRECT_URI=$(VK_REDIRECT_URI) \
		-e URI_OAUTH_VK=$(URI_OAUTH_VK) \
		-e URL=$(URL) \
		-e KEY=$(KEY) \
		-p 9015:9005 \
		kubikvest/api-dev \
		php-fpm7 -F \
			-d error_reporting=E_ALL \
			-d log_errors=On \
			-d error_log=/dev/stdout \
			-d display_errors=On \
			-d always_populate_raw_post_data=-1

	@docker run -d \
		--name "kubikvest_nginx" \
		--link kubikvest:service \
		-p 8300:80 \
		kubikvest/nginx

	@docker run --rm=$(DOCKER_RM) \
		-v $(CURDIR)/tests:/data \
		-w /data \
		--link kubikvest_nginx:service \
		alpine \
		sh -c 'apk add --update bash curl jq && ./test.sh service'
#@-cd tests/mock-servers/vk;make destroy

testunit:
	@docker run --rm \
		--link kubikvest_db:server_db \
		-v $(CURDIR):/app \
		-e DB_HOST=server_db \
		-w /app \
		kubikvest/api-dev \
		sh -c '/app/vendor/bin/phpunit $(PHPUNITARG)'

stop:
	@-docker stop $(CONTAINERS)

clean: stop
	@-docker rm -fv $(CONTAINERS)
	cd tests/mock-servers/vk;make clean

destroy: clean
	@-docker rmi -f $(IMAGES)

deploy: COMPOSER_FLAGS = --no-dev --ignore-platform-reqs --no-interaction
deploy: CONTAINERS = kubikvest kubikvest_nginx
deploy: composer destroy build
	@docker run -d \
		--name "kubikvest" \
		--link kubikvest_db:kubikvest_db \
		-e VK_CLIENT_ID=$(VK_CLIENT_ID) \
		-e VK_CLIENT_SECRET=$(VK_CLIENT_SECRET) \
		-e VK_REDIRECT_URI=$(VK_REDIRECT_URI) \
		-e URI_OAUTH_VK=$(URI_OAUTH_VK) \
		-e URL=$(URL) \
		-e KEY=$(KEY) \
		-e KV_WEBHOOK=$(KV_WEBHOOK) \
		kubikvest/api \
		php-fpm7 -F \
			-d error_reporting=E_ERROR \
			-d log_errors=On \
			-d error_log=/dev/stdout \
			-d display_errors=Off \
			-d always_populate_raw_post_data=-1
	@docker run -d \
		--name "kubikvest_nginx" \
		--link kubikvest:service \
		-p 8300:80 \
		kubikvest/nginx

migrate:
	@docker run --rm \
		-v $(CURDIR)/sql:/sql \
		--link kubikvest_db:kubikvest_db \
		imega/mysql-client mysql --host=kubikvest_db --database=kubikvest -e "source /sql/2016-11-15-01.sql"

.PHONY: build
