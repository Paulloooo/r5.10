## Pour exécuter et rentrer dans le conteneur Docker :

docker build -t php-mongodb-script . && docker run -it --network host php-mongodb-script bash

## Pour lancer les commandes :

Dans le terminal que vous avez ouvert :


php main.php mongo:connect
-> Permet de vérifier qu'on est bien connecté au MongoDB

php main.php mongo:delete
-> Permet de supprimer toutes les données

php main.php mongo:insert
-> Insérer des données tests

php main.php mongo:select 
-> Sélectionner toutes les données
