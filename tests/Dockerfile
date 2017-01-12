FROM kubikvest/api

RUN echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories && \
    apk add --update \
        php7-iconv@community \
        php7-xdebug@community && \
    rm -rf /var/cache/apk/* && \
    cd /usr/bin && \
    ln -s php7 php

ENTRYPOINT ["/bin/sh", "/app/entrypoint/kubikvest-dev.sh"]
