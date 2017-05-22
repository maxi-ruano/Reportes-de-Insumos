#Add permission R/W httpd to directory storage
chown apache:apache -R /var/www/html/(Directory)

chcon -t httpd_sys_content_t /var/www/html/(Directory) -R
chcon -t httpd_sys_rw_content_t /var/www/html/(Directory)/storage -R
#configurar httpd.conf dejar en "All" todos los "none"
chcon -t httpd_sys_rw_content_t /var/www/html/teorico/bootstrap -R

