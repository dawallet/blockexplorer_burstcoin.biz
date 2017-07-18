# blockexplorer_burstcoin.biz

    Burstcoin Block-Explorer Code - Burstcoin.biz
    Copyright (c) 2017 by BURSTize (haitch, daWallet)
    
    License: GNU AGPLv3

    Block-Explorer for the Burstcoin Project
    Copyright (C) 2017 by BURSTize (haitch, daWallet) 

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.


INSTRUCTIONS:

1. Modify all variables of config/config.php
 - absolute path to script
 - DB settings
 - SMTP settings (contact)
 - IP/URL of Burst API
 - Wallet Passphrase for Facuet
 - eBesucher API data

2. Upload folder with FTP. The files in the folder public should be the only one that are accessible by browser.
    The directories: app, config and core should be put outside of wwwroot for more security.

3. Make these directories writeable:
 - /app/cache
 - /app/log
 - /app/temp
 - /app/view_c
 - /public/media/qr

4. Open file /app/tasks/index.php and change line 3 to the path of your config.php.

5. Enable following Cronjobs (SSH > crontab -e)  (here Unix):

 * * * * * php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php
 * * * * * sleep 5; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php -- action=cycle
 * * * * * sleep 10; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php
 * * * * * sleep 15; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php -- action=cycle
 * * * * * sleep 20; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php
 * * * * * sleep 25; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php -- action=cycle
 * * * * * sleep 30; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php
 * * * * * sleep 35; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php -- action=cycle
 * * * * * sleep 40; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php
 * * * * * sleep 45; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php -- action=cycle
 * * * * * sleep 50; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php
 * * * * * sleep 55; php /var/www/pfad/zur/burstcoinseite/app/tasks/index.php -- action=cycle

Done.
