FROM silintl/php7
COPY docker/config.conf /etc/apache2/sites-enabled/

RUN mkdir -p /app
WORKDIR /app

RUN apt-get update && apt-get install nodejs npm -y
RUN git clone https://github.com/tawazz/spendee.git .
RUN git checkout master
COPY app/config/config.php /app/app/config/
RUN composer install

WORKDIR /app/web
RUN npm install -g grunt 
RUN grunt

WORKDIR /app


EXPOSE 80
CMD ["apache2ctl", "-D", "FOREGROUND"]
