# Sensi YAML Gui Bundle#

This bundle generates from a given yaml config file, a simple web form in sonata admin dashboard.
It's also usable as standalone whitout the SonataAdminBundle.

The bundle is in an early development version available. So feel free to fork and contribute to the bundle.

## Installation

Using composer:

    $ php composer.phar require sensi/sensi-yaml-gui-bundle
    
Enable the bundle in AppKernel:

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
        // ...
        new Sensi\Bundle\YamlGuiBundle\SensiYamlGuiBundle(),
        // ...
        );
    }
    
Insert the routing file:

	# app/config/routing.yml
    sensi_yaml_gui:
        resource: "@SensiYamlGuiBundle/Resources/config/routing.yml"
        prefix:   /yamlgui
        
Create and modify the *app/config/yamlgui.yml and insert it into your app/config.yml:

    # app/config/yamlgui.yml
    sensi_yaml_gui:
		# Includes the sonata admin pool into the yaml gui (use sonata base template). 
		# Default value false.
		sonata_admin_modus: true
	
		# Path where the managed yaml config files were located
		# Notice you have to create the folder and make it writeable for the yamlgui writer
		config_root_dir: "%kernel.root_dir%/config/yamlgui/"   
	
		# List of all files which were available throw the yamlgui. Must be located in 
		# config_root_dir
		managed_files:
		  "demo.yml": { title: "Demo config" }
    
Add yamlgui.yml: 

    # app/config/config.yml 
    - { resource: yamlgui.yml }
    
Finally create your yml config file and add them under managed_files. After that,
the files are editable by calling following path in your browser: /yamlgui/list or /yamlgui/edit/{file_name}

## Translations

If you wana translate your config key labels to a human readable name you can create a 
translation file in *app/Resources/translations/sensi_yaml_gui.<language_key>.yml*. This file can
extends the original translation file with your custom translations.


