#!/usr/bin/env bash

# récuperer le code source
git pull origin master

# recupere les nouvelles librairies si il y'en a
composer install --no-dev

# vide le cache
drush cr

# Met a jour de la base de données avec option yes
drush updb -y

#exporte les configs de prod
drush csex prod -y

# Importe les configs
drush cim -y

#vide le cache
drush cr

# Normalement c'est bon !!!
