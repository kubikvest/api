FROM kubikvest/api

RUN echo "@community http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories && \
    apk add --update \
        php7@community \
        php7-iconv@community \
        php7-xdebug@community && \
    ln -s /usr/bin/php7 /usr/bin/php && \
    echo 'zend_extension=xdebug.so' >> /etc/php7/conf.d/xdebug.ini && \
    rm -rf /var/cache/apk/*

ENTRYPOINT ["/bin/sh", "/app/entrypoint/kubikvest-dev.sh"]
