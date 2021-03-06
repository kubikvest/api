FROM alpine:3.8

VOLUME ["/app"]

ENV VK_CLIENT_ID="111" \
    VK_CLIENT_SECRET="secret" \
    VK_REDIRECT_URI="kubikvest" \
    URI_OAUTH_VK="vk-server"

COPY . /app

RUN apk add --update \
        php7-fpm \
        php7-zlib \
        php7-pdo_mysql \
        php7-dom \
        libressl \
        php7-openssl \
        php7-json \
        php7-pdo \
        php7-curl \
        tzdata && \
        cp /usr/share/zoneinfo/Europe/Moscow /etc/localtime && \
        echo "Europe/Moscow" >  /etc/timezone && \
        apk del tzdata && \
    rm -rf /var/cache/apk/*

ENTRYPOINT ["/bin/sh", "/app/entrypoint/kubikvest.sh"]

CMD ["php-fpm7", "-F", "-d error_reporting=E_ALL", "-d log_errors=ON", "-d error_log=/dev/stdout","-d display_errors=YES"]
