FROM tawazz/php7

COPY docker/config.conf /etc/apache2/sites-enabled/

RUN mkdir -p /app
RUN mkdir -p /app/logs
WORKDIR /app

RUN curl -sL https://deb.nodesource.com/setup_8.x -o nodesource_setup.sh && bash nodesource_setup.sh

RUN apt-get update && apt-get install -y  \
    supervisor cron  php7.0-gd  php-xdebug mysql-client beanstalkd nodejs

RUN npm install -g yarn

COPY docker/services /etc/supervisor/conf.d
COPY docker/tasks /etc/cron.d/tasks
COPY docker/php.ini /etc/php/7.0/apache2/php.ini

RUN touch /app/logs/cron.log
RUN chmod 0644 /etc/cron.d/tasks

WORKDIR /app

EXPOSE 80
CMD ["/usr/bin/supervisord", "-n"]
