#!/usr/bin/env sh

cat <<EOF>/etc/php/php-fpm.conf
[global]
error_log = /proc/self/fd/2
log_level = notice
emergency_restart_threshold = 10
emergency_restart_interval = 1m
process_control_timeout = 10s
daemonize = no

[www]
user = nobody
group = nobody
listen = 9000
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
pm.max_requests = 1000
access.log = /proc/1/fd/2
request_terminate_timeout = 35s
chdir = /
catch_workers_output = yes
php_admin_value[error_log] = /proc/self/fd/2
php_admin_flag[log_errors] = on

EOF

set -e

echo "env[VK_CLIENT_ID]=$VK_CLIENT_ID" >> /etc/php/php-fpm.conf
echo "env[VK_CLIENT_SECRET]=$VK_CLIENT_SECRET" >> /etc/php/php-fpm.conf
echo "env[VK_REDIRECT_URI]=$VK_REDIRECT_URI" >> /etc/php/php-fpm.conf
echo "env[URI_OAUTH_VK]=$URI_OAUTH_VK" >> /etc/php/php-fpm.conf
echo "env[URL]=$URL" >> /etc/php/php-fpm.conf
echo "env[KEY]=$KEY" >> /etc/php/php-fpm.conf

exec "$@"
