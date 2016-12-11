namespace :symfony do
    desc 'Update doctrine schema'
    task :update_schema do
        on roles :web do
            within release_path do
                execute :php, 'bin/console', 'doctrine:schema:update --force --env=prod'
            end
        end
    end

    desc 'Load Doctrine fixtures'
    task :load_fixtures do
        on roles :web do
            within release_path do
                execute :php, 'bin/console', 'doctrine:fixtures:load -n'
            end
        end
    end

    desc 'Dump FOS JS routes'
    task :dump_js_routes do
        on roles :web do
            within release_path do
                execute :php, 'bin/console', 'fos:js-routing:dump -e prod'
            end
        end
    end

    desc 'Dump assets'
    task :dump_assets do
        on roles :web do
            within release_path do
                execute :php, 'bin/console', 'assetic:dump -e prod'
            end
        end
    end

    desc 'Clear Liip Imagine cache'
    task :clear_liip_cache do
        on roles :web do
            within release_path do
                execute :php, 'bin/console', 'liip:imagine:cache:remove'
            end
        end
    end

    after 'deploy:updated', 'symfony:update_schema'
    after 'deploy:updated', 'symfony:load_fixtures'
    after 'deploy:updated', 'symfony:dump_js_routes'
    after 'deploy:updated', 'symfony:dump_assets'
    after 'deploy:updated', 'symfony:clear_liip_cache'
end
