#Add permission R/W httpd to directory storage
chcon -t httpd_sys_rw_content_t /var/www/html/deve_teorico/storage -R
