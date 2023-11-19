<?php

namespace Deployer;

require 'recipe/symfony3.php';
require __DIR__ . '/.deploy/recipe.php';

inventory('.deploy/hosts.yaml');

set('keep_releases', 5);
set('default_stage', 'stage');
set('project_path', __DIR__);

set('shared_files', [
    '.env.local'
]);

set('shared_dirs', [
    'public/var',
    'var/application-logger',
    'var/email',
    'var/admin',
    'var/log',
    'var/recyclebin',
    'var/versions',
    'var/sessions'
]);

set('bin/composer', 'COMPOSER_MEMORY_LIMIT=-1 /usr/bin/composer');
set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-suggest');

desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:pimcore:assets:install',
    'deploy:cache:clear:opcodes',
    'deploy:pimcore:migrate:core',
    'deploy:pimcore:rebuild-classes',
    'deploy:pimcore:migrate',
    'deploy:clear_paths',
    'deploy:pimcore:cache:clear',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
])->desc('Deploy your project');

after('deploy:failed', 'deploy:unlock');
