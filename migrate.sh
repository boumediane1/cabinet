rm -rf migrations/*.php
./bin/console doctrine:database:drop --force
./bin/console doctrine:database:create
./bin/console make:migration
./bin/console doctrine:migrations:migrate
./bin/console doctrine:fixtures:load
psql -f insert.sql cabinet

