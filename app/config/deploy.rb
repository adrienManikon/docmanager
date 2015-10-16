set   :application,   "Docmanager"
set   :deploy_to,     "/var/www/vhosts/businessdev.ch/httpdocs/clients2013/webproject/docmanager"
set   :domain,        "docmanager.businessdev.ch"

set   :scm,           :git
set   :repository,    "git@github.com:adrienManikon/docmanager.git"

role  :web,           domain
role  :app,           domain, :primary => true

set   :use_sudo,      false
set   :keep_releases, 3