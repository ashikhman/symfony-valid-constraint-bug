**Passed tests on 4.2.5:**

    cd /app && \
    composer require symfony/validator:4.2.5 && \
    php vendor/bin/phpunit
    
**Failed tests on >=4.2.6:**

    cd /app && \
    composer require symfony/validator:4.2.8 && \
    php vendor/bin/phpunit
    

**Run tests from docker**

    docker-compose up -d --build && \
    docker-compose exec php bash
    # then run the commands described above to run tests.
