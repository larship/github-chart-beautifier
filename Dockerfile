FROM php:8.0-cli
RUN apt-get update && apt-get install -y --no-install-recommends git
COPY . /var/www/github-chart-beautifier
WORKDIR /var/www/github-chart-beautifier
CMD [ "php", "./beautifier.php" ]
