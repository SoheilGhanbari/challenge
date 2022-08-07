FROM registry.gitlab.shiveh.com/upstream/nginx-fpm:8.1-ubuntu
LABEL Author="Soheil Ghanbari"
LABEL AuthorEmail="<ghanbari.soheil@gmail.com>"

COPY ./user-manager /var/www/html

RUN wget https://packages.sury.org/php/apt.gpg -O /etc/apt/trusted.gpg.d/php-sury.gpg

RUN composer install \
 && chown -R nginx:nginx /var/www/html \
 && chmod -R 755 /var/www/html/storage/logs

RUN if [ -d "/var/www/html/supervisor" ]; then \
      cp -r /var/www/html/supervisor/* /etc/supervisord.d/; \
	fi

RUN apt update
RUN apt install -y libargon2-1 libidn2-0 libpcre2-8-0 libpcre3 libxml2 libzstd1 apt-utils libpcre2-dev
RUN apt install --no-install-recommends --no-install-suggests -q -y php8.0-soap


ADD user-manager-start /usr/local/bin/
CMD ["user-manager-start"]
