php bin/console doctrine:schema:drop --full-database --force -vvv
php bin/console doctrine:schema:update --force -vvv
#php bin/console admin:reload-fixtures -vvv
php bin/console doctrine:fixtures:load --no-interaction --multiple-transactions
