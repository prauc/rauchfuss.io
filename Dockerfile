FROM httpd:2.4

COPY src /usr/local/apache2/htdocs/

RUN sed -i \
        -e 's/^#\(LoadModule .*mod_deflate.so\)/\1/' \
        conf/httpd.conf