FROM reload/drupal-apache-ssl

# Install xdebug.
RUN apt-get update && \
  DEBIAN_FRONTEND=noninteractive \
  apt-get install --no-install-recommends -y -q php5-xdebug && \
  echo "xdebug.remote_enable = 1" >> /etc/php5/mods-available/xdebug-config.ini && \
  echo "xdebug.remote_connect_back = 1" >> /etc/php5/mods-available/xdebug-config.ini && \
  php5enmod xdebug && \
  php5enmod xdebug-config && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install mysql-client to support mysql commands from Drush.
# For some reason this package is not available without updating.
RUN apt-get update && \
  DEBIAN_FRONTEND=noninteractive \
  apt-get install --no-install-recommends -y -q mysql-client && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# vhost exposes /var/www/html as docroot. drupal-project assumes /web to be
# docroot. Symlink web to html to make it work for both parties.
RUN rm -rf /var/www/html
RUN ln -s /var/www/web /var/www/html
# Make Composer commands universally available
ENV PATH "/var/www/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin"
