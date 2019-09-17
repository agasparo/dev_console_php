This script is made by arthur gasparotto


To reload all modules do : system.reload_modules

To use module commande do : {module_name}.{command} [arguments ...]

To get usage of a module : module_name.

Modules descriptions:

![Alt text](/img/mod_screen.png?raw=true "Optional Title")

HEADER :

![Alt text](/img/header_screen.png?raw=true "Optional Title")

Change the name of the console header 		ex: change_headet.change 'new_name'

STYLE :

![Alt text](/img/style_screen.png?raw=true "Optional Title")

Change the size of the console in % *//* height_mini = 31 height_maxi = 97	*//*	ex: style.size 'width' 'height'   (values must be int)

Change the color of the text *//* ex: style.txt_color 'color' 

Change the console background color *//* ex: style.back_color 'color'

STATS :

![Alt text](/img/stats_screen.png?raw=true "Optional Title")

FILE :

![Alt text](/img/file_screen.png?raw=true "Optional Title")

show file ex: file.show 'file'

show all the path from a repository ex: file.structure 'path to the repository' 

HTTP_REQUEST :

![Alt text](/img/http_screen.png?raw=true "Optional Title")

SYSTEM :

![Alt text](/img/system_screen.png?raw=true "Optional Title")

Reload all modules ex: system.reload_modules

Clear the console *//* ex: system.clear

Show the module model *//* ex: system.model_module

Show the server infos *//* ex: system.server_infos

Search for a particular information *//* ex: system.server_get_infos 'info'

BDD : 

![Alt text](/img/bdd_screen.png?raw=true "Optional Title")

GIT :

![Alt text](/img/git_screen.png?raw=true "Optional Title")






































To add module juste put the module file in the directory modules
if file name is module_type.module.php
the classname must be like : module_type
The file name must be like : name.module.php