FROM tawazz/php7

COPY docker/config.conf /etc/apache2/sites-enabled/

RUN mkdir -p /app
RUN mkdir -p /app/logs
WORKDIR /app

RUN apt-get update && apt-get install nodejs npm -y
RUN apt-get install supervisor cron -y

COPY docker/services /etc/supervisor/conf.d
COPY docker/tasks /etc/cron.d/tasks

RUN touch /app/logs/cron.log
RUN chmod 0644 /etc/cron.d/tasks
RUN cp /usr/bin/nodejs /usr/bin/node

WORKDIR /app

EXPOSE 80
CMD ["/usr/bin/supervisord", "-n"]
