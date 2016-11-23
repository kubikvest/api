FROM kubikvest/api

RUN echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories && \
    apk add --update \
        php7-xdebug@community && \
    rm -rf /var/cache/apk/*

ENTRYPOINT ["/bin/sh", "/app/entrypoint/kubikvest-dev.sh"]
