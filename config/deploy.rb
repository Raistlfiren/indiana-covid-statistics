####
# General settings
####
set :application, "Indiana-Covid-Statistics"
set :keep_releases,  3

####
# Git Information
####
set :repo_url, "https://github.com/Raistlfiren/indiana-covid-statistics.git"
set :scm_verbose, :true

set :composer_install_flags, '--no-dev --prefer-dist --no-interaction --optimize-autoloader'

namespace :deploy do
  after :updated, 'composer:install_executable'
end