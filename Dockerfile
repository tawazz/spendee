FROM silintl/php7
COPY docker/config.conf /etc/apache2/sites-enabled/

RUN mkdir -p /app
WORKDIR /app

run git clone https://github.com/tawazz/spendee.git .
RUN git checkout master
COPY app/config/config.php /app/app/config/
RUN composer install
RUN php vendor/bin/phinx migrate -c app/config/config-phinix.php

EXPOSE 80
CMD ["apache2ctl", "-D", "FOREGROUND"]
