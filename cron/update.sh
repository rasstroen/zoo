cd /home/zoo/cron/ && rm -f *.txt && rm -f *.tar.gz && wget http://ipgeobase.ru/files/db/Main/geo_files.tar.gz && tar xvf geo_files.tar.gz && echo "import_geobase " && php import_geobase.php && echo "normalize" && php normalize_regions.php