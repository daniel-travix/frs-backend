<?php

namespace Deployer;

// @see: https://ma.ttias.be/how-to-clear-php-opcache/
task('deploy:cache:clear:opcodes', function () {
    // download cachetool if not exists
    run('if [[ ! -f {{deploy_path}}/cachetool.phar ]]; then curl -L -s http://gordalina.github.io/cachetool/downloads/cachetool.phar -o {{deploy_path}}/cachetool.phar; fi');
    // run cachetool to clear php opcode cache
    run('{{bin/php}} {{deploy_path}}/cachetool.phar opcache:reset --fcgi=/run/php/php-fpm.sock');
});

task('deploy:pimcore:assets:install', function () {
    run('{{bin/php}} {{bin/console}} assets:install --symlink --relative');
});

task('deploy:pimcore:migrate:core', function () {
    run("{{bin/php}} {{bin/console}} doctrine:migrations:migrate --prefix 'Pimcore\Bundle\CoreBundle'");
})->desc('Pimcore X: Run Pimcore core migrations.');

task('deploy:pimcore:migrate', function () {
    run('{{bin/php}} {{bin/console}} doctrine:migrations:migrate --allow-no-migration -n');
});

task('deploy:pimcore:rebuild-classes', function () {
    run('{{bin/php}} {{bin/console}} pimcore:deployment:classes-rebuild -c -d -n');
});

task('deploy:pimcore:datahub:graphql:rebuild', function () {
    run('{{bin/php}} {{bin/console}} datahub:graphql:rebuild-definitions');
});

task('deploy:pimcore:cache:clear', function () {
    run('{{bin/php}} {{bin/console}} cache:clear -q --no-warmup -n --ignore-maintenance-mode --env=prod --no-debug');
    run('{{bin/php}} {{bin/console}} pimcore:cache:clear -n -a -q');
});

task('deploy:pimcore:merge:shared', function () {
    if (!has('pimcore_shared_configurations')) {
        return;
    }
    $sharedFiles = get('shared_files');
    $pimcoreSharedConfigFiles = get('pimcore_shared_configurations');
    $all = array_merge($sharedFiles, $pimcoreSharedConfigFiles);
    set('shared_files', $all);
});

// Process the pimcore config files
// Add empty array if file is empty
task('deploy:pimcore:shared:config', function () {
    if (!has('pimcore_shared_configurations')) {
        return;
    }

    $sharedPath = "{{deploy_path}}/shared";
    $emptyContent = "<?php return [];";

    foreach (get('pimcore_shared_configurations') as $configFile) {
        run("[ -s '$sharedPath/$configFile' ] || echo '$emptyContent' >> '$sharedPath/$configFile'");
    }
});
