# Company Management Software
Company Management Software

# Requirements
- Mysql 5.7 or higher
- PHP 7.2 or higher

# Installation
    git clone https://github.com/daniel3303/company-management.git
    cd company-management
    composer install
    
Open and configure your database in .env file then run.
    
    php bin/console doctrine:schema:update --force

# Dummy data
To load test dummy data run:

    php bin/console doctrine:fixtures:load
