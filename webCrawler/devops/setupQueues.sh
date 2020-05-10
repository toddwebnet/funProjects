
if [ ! -e /etc/supervisor/conf.d/supervisor-webCrawler.conf ]; then
  echo "configure supervisor  ##########################################################"

  cp /home/vagrant/www/funProjects/webCrawler/devops/supervisor-webCrawler.conf /etc/supervisor/conf.d/supervisor-webCrawler.conf
  supervisorctl reread
  supervisorctl update
  sudo supervisorctl start supervisor-processQueueUrls:*
  sudo supervisorctl start supervisor-processQueueHtmls:*
  sudo supervisorctl start supervisor-processQueueLinks:*

fi
