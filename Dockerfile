FROM alpine:3.4

VOLUME ["/app"]

ENV VK_CLIENT_ID="111" \
    VK_CLIENT_SECRET="secret" \
    VK_REDIRECT_URI="kubikvest" \
    URI_OAUTH_VK="vk-server"

COPY . /app

RUN echo "@main http://dl-cdn.alpinelinux.org/alpine/edge/main" >> /etc/apk/repositories && \
    echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories && \
    apk add --update \
        php7-fpm@community \
        php7-zlib@community \
        php7-pdo_mysql@community \
        php7-dom@community \
        libressl@main \
        php7-openssl@community \
        php7-json@community \
        php7-pdo@community \
        tzdata && \
        cp /usr/share/zoneinfo/Europe/Moscow /etc/localtime && \
        echo "Europe/Moscow" >  /etc/timezone && \
        apk del tzdata && \
    rm -rf /var/cache/apk/*

ENTRYPOINT ["/bin/sh", "/app/entrypoint/kubikvest.sh"]

CMD ["php-fpm7", "-F", "-d error_reporting=E_ALL", "-d log_errors=ON", "-d error_log=/dev/stdout","-d display_errors=YES"]
