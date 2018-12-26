FROM ubuntu:18.04

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
    libxi6 libgconf-2-4

RUN a2dismod mpm_event && a2enmod mpm_prefork && a2enmod rewrite && a2enmod php7.3 \
&& a2enmod proxy_fcgi setenvif && a2enconf php7.3-fpm && a2dismod php7.2
COPY docker/config.conf /etc/apache2/sites-enabled/

RUN mkdir -p /app
RUN mkdir -p /app/logs
WORKDIR /app

#node

RUN curl -sL https://deb.nodesource.com/setup_8.x -o nodesource_setup.sh && bash nodesource_setup.sh

RUN apt-get update && apt-get install -y  \
    supervisor cron mysql-client beanstalkd nodejs \
    fontconfig libxrender1 xfonts-75dpi xfonts-base libjpeg-turbo8-dev

# puppeteer
RUN apt-get install -y gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 \
  libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 \
  libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 \
  libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 \
   libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation \
  libappindicator1 libnss3 lsb-release xdg-utils

COPY docker/services /etc/supervisor/conf.d
COPY docker/tasks /etc/cron.d/tasks
COPY docker/php.ini /etc/php/7.3/apache2/php.ini

RUN touch /app/logs/cron.log
RUN chmod 0644 /etc/cron.d/tasks
RUN rm /etc/apache2/sites-enabled/000-default.conf

# composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

EXPOSE 80
CMD ["/bin/bash", "boot.sh"]
