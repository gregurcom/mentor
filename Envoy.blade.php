@servers(['web' => ['root@207.154.241.76']])

@story('deploy')
    update-code
    install-dependencies
    migrate
    route-cache
    link-storage
    app:generate-sitemap
    app:generate-open-api
    queue
@endstory

@task('update-code')
    cd /var/www/mentor-IT11Z

    @if ($branch)
        git pull origin {{ $branch }}
    @else
        git pull origin main
    @endif
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

@task('route-cache')
    cd /var/www/mentor-IT11Z
    php artisan route:cache
@endtask

@task('app:generate-sitemap')
    cd /var/www/mentor-IT11Z
    php artisan app:generate-sitemap
@endtask

@task('app:generate-open-api')
    cd /var/www/mentor-IT11Z
    php artisan l5-swagger:generate
@endtask

@task('queue')
    cd /var/www/mentor-IT11Z
    php artisan queue:restart
@endtask
