Modules extend your site functionality beyond Drupal core.

WHAT TO PLACE IN THIS DIRECTORY?
--------------------------------

Placing downloaded and custom modules in this directory separates downloaded and
custom modules from Drupal core's modules. This allows Drupal core to be updated
without overwriting these files.

DOWNLOAD ADDITIONAL MODULES
---------------------------

Contributed modules from the Drupal community may be downloaded at
https://www.drupal.org/project/project_module.

ORGANIZING MODULES IN THIS DIRECTORY
------------------------------------

You may create subdirectories in this directory, to organize your added modules,
without breaking the site. Some common subdirectories include "contrib" for
contributed modules, and "custom" for custom modules. Note that if you move a
module to a subdirectory after it has been enabled, you may need to clear the
Drupal cache so it can be found.

There are number of directories that are ignored when looking for modules. These
are 'src', 'lib', 'vendor', 'assets', 'css', 'files', 'images', 'js', 'misc',
'templates', 'includes', 'fixtures' and 'Drupal'.

MULTISITE CONFIGURATION
-----------------------

In multisite configurations, modules found in this directory are available to
all sites. You may also put modules in the sites/all/modules directory, and the
versions in sites/all/modules will take precedence over versions of the same
module that are here. Alternatively, the sites/your_site_name/modules directory
pattern may be used to restrict modules to a specific site instance.

MORE INFORMATION
----------------

Refer to the “Developing for Drupal” section of the README.txt in the Drupal
root directory for further information on extending Drupal with custom modules.

Credits
----------------

Form API
https://suyati.com/blog/step-step-guide-create-custom-modules-drupal-8/ "A Step-By-Step-Guide to Create Custom Modules in Drupal 8"
https://niklan.net/blog/73 "Drupal 8: Form API что изменилось и как использовать"
https://drupalfly.ru/lesson/create-my-module-for-drupal-8 "Пишем свой модуль для Drupal 8. Создание страницы и пункта меню."
http://drup.by/articles/html5-elementy-form-v-drupal-8 "HTML5 элементы форм в Drupal 8"

Cron and Queue 
https://niklan.net/blog/79 "Drupal 8: Queue API"
https://www.sitepoint.com/drupal-8-queue-api-powerful-manual-and-cron-queueing/ "Drupal 8 Queue API – Powerful Manual and Cron Queueing"

