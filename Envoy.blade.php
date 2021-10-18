@servers(['web' => ['root@207.154.241.76']])

@story('deploy')
    update-code
    install-dependencies
    migrate
    link-storage
@endstory

@task('update-code')
    cd /var/www/mentor-IT11Z
    git pull origin main
@endtask

@task('install-dependencies')
    cd /var/www/mentor-IT11Z
    composer install
    npm install
    npm run prod
@endtask

@task('migrate')
    cd /var/www/mentor-IT11Z
    php artisan migrate --force
@endtask

@task('link-storage')
    cd /var/www/mentor-IT11Z
    php artisan storage:link --force
@endtask