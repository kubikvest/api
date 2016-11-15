FROM kubikvest/api

RUN echo "@testing http://dl-cdn.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories && \
    apk add --update \
        php7-xdebug@testing && \
    rm -rf /var/cache/apk/*

ENTRYPOINT ["/bin/sh", "/app/entrypoint/kubikvest-dev.sh"]
