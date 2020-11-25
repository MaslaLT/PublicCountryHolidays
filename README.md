# PublicCountryHolidays
Recruitment task. Display public country holidays Using https://kayaposoft.com/enrico/json/ API. 

## Start project
* clone project repository
``
git clone https://github.com/MaslaLT/PublicCountryHolidays.git
``
* Install composer packages
``
composer install
``
* Overwrite .env.local with .env.local.example
``
mv .env.local.example .env.local with
``
* Fill local settings in .env.local

* Run docker command
``
docker-compose --env-file ./.env.local up -d
``
