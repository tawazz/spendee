FROM ubuntu:18.04 as app
ENV TZ=Australia/Perth
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt-get update
RUN apt-get install software-properties-common build-essential checkinstall -y
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
		apache2 apache2-dev php7.3 libapache2-mod-php7.3 php7.3-cli wget  \
    php7.3-fpm  php7.3-curl php7.3-mysql \
    php7.3-xml php7.3-zip php7.3-gd php7.3-mbstring php7.3-sockets \
    php7.3-common php7.3-json php7.3-intl php7.3-exif php7.3-tokenizer\
    libcurl4-openssl-dev libedit-dev git \
    libsodium-dev libsqlite3-dev libssl-dev libxml2-dev zlib1g-dev curl libapache2-mod-php \
    libxi6 libgconf-2-4 supervisor cron mysql-client beanstalkd

RUN a2dismod mpm_event && a2enmod mpm_prefork && a2enmod rewrite && a2enmod php7.3 \
&& a2enmod proxy_fcgi setenvif && a2enconf php7.3-fpm && a2dismod php7.3

RUN mkdir -p /app
WORKDIR /app
COPY . .
RUN mkdir -p /app/logs

COPY docker/services /etc/supervisor/conf.d
COPY docker/tasks /etc/cron.d/tasks
COPY docker/php.ini /etc/php/7.3/apache2/php.ini

RUN touch /app/logs/cron.log
RUN chmod 0644 /etc/cron.d/tasks
RUN rm /etc/apache2/sites-enabled/000-default.conf

RUN chown -R www-data:www-data .

EXPOSE 80
CMD ["/bin/bash", "boot.sh"]
