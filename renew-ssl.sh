sudo certbot certonly --webroot --webroot-path ~/base-project/src/public -d damogame.com
sudo cp -r -L /etc/letsencrypt/live/damogame.com/fullchain.pem /etc/letsencrypt/ssl/
sudo cp -r -L /etc/letsencrypt/live/damogame.com/privkey.pem /etc/letsencrypt/ssl/
sudo chown admin:admin /etc/letsencrypt/ssl/fullchain.pem
sudo chown admin:admin /etc/letsencrypt/ssl/privkey.pem