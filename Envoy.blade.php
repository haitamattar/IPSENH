@servers(['web' => 'deployer@136.144.157.36'])

@task('list', ['on' => 'web'])
    ls -l
@endtask

@setup
    $repository = 'ssh://git@136.144.214.88:23/root/portfolio.git';
    $releases_dir = '/var/www/schoolProjectPortfolio/portfolio/releases';
    $app_dir = '/var/www/schoolProjectPortfolio/portfolio';
    $release = date('YmdHis');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup


@story('deploy')
    clone_repository
    run_composer
    update_symlinks
    install_run_npm
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
@endtask

@task('run_composer')
    echo "Starting deployment ({{ $release }})"
    cd {{ $new_release_dir }}
    chmod -R 777 bootstrap/cache/
    composer install --prefer-dist --no-scripts -v -o
@endtask

@task('install_run_npm')
    echo "Starting npm"
    ls
    cd {{ $new_release_dir }}
    npm install
    npm run prod
    php artisan storage:link
@endtask


@task('update_symlinks')
    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask
