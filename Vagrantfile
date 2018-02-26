# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT
DBPASSWD=root
echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
echo "phpmyadmin phpmyadmin/app-password-confirm password $DBPASSWD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-pass password $DBPASSWD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password $DBPASSWD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none" | debconf-set-selections
apt-get -y install phpmyadmin > /dev/null 2>&1
apt-add-repository ppa:ondrej/php -y
apt-get update
apt-get install -y php7.2
apt-get install -y php-pear php7.2-curl php7.2-dev php7.2-gd php7.2-mbstring php7.2-zip php7.2-mysql php7.2-xml
update-alternatives --set php /usr/bin/php7.2
a2dismod php5
a2dismod php7.0
a2enmod php7.2
service apache2 restart
composer self-update
SCRIPT

Vagrant.configure("2") do |config|

    config.vm.box = "scotch/box"
    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.hostname = "scotchbox"
    config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=777"]
    config.vm.provision "shell", inline: $script
    # Optional NFS. Make sure to remove other synced_folder line too
    #config.vm.synced_folder ".", "/var/www", :nfs => { :o:mount_options => ["dmode=777","fmode=777"] }

end
