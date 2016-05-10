# OrgHeiglHybridAuth

[![Join the chat at https://gitter.im/heiglandreas/HybridAuth](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/heiglandreas/HybridAuth?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/heiglandreas/HybridAuth.png?branch=master)](https://travis-ci.org/heiglandreas/HybridAuth)
[![Latest Stable Version](https://poser.pugx.org/org_heigl/hybridauth/v/stable.png)](https://packagist.org/packages/org_heigl/hybridauth)
[![Total Downloads](https://poser.pugx.org/org_heigl/hybridauth/downloads.png)](https://packagist.org/packages/org_heigl/hybridauth)
[![Stories in Ready](https://badge.waffle.io/heiglandreas/HybridAuth.png?label=ready)](https://waffle.io/heiglandreas/HybridAuth)  


Use the HybridAuth-Library to create an absolute lightweight Authentication-Layer
for your ZendFramework2-App

You can login with all [supported SocialNetwork-Logins](http://hybridauth.sourceforge.net/userguide.html).
The network and a user-object holding id, name, mail and language will be stored in the session. If you already have
SocialNetwork users in your application you can use these to authorize your users.

## Requirements

* The [hybridAuth-library](http://hybridauth.sourceforge.net). This lib uses the version 3 which is not (yet) stable!
* Zend Framework2 (well, obvious, isn't it?)

## Usage

1. In your application.conf-file add the Module to the list of modules
2. Copy the file ```vendor/org_heigl/hybridauth/config/autoload/module-orgHeiglHybridAuth.local.php``` to your
    applications ```config/autoload```-directory and adapt as appropriate.
3. Add this snippet to create a login-link

    ```php
    <?php
    $provider = "Twitter";
    echo $this->hybridauthinfo($provider);
    ?>
    ```

4. After login you can access the user-info the following way:

    ```php
    $config = $this->getServiceLocator()->get('Config');
    $config = $config['OrgHeiglHybridAuth'];
    $hybridAuth = new Hybridauth($config['hybrid_auth']);

    $token = $this->getServiceLocator()->get('OrgHeiglHybridAuthToken');
    if (! $token->isAuthenticated()) {
        echo 'No user logged in';
    }
    /** @var OrgHeiglHybridAuth\UserToken $user */
    echo $token->getName(); // The name of the logged in user
    echo $token->getUID();  // The internal UID of the used service
    echo $token->getMail(); // The mail-address the service provides
    echo $token->getLanguage(); // The language the service provides for the user
    echo $token->getService()  // Should print out the Name of the service provider.
    ```

## Installation

### composer

This module is best installed using [composer](http://packagist.org/packages/org_heigl/hybridauth).
For that, run the following command to add the library to your app:

    # Require the unstable dependency to enable installation with min-requirement "stable"
    composer require --no-update hybridauth/hybridauth:@dev
    # Require the hybridauth-module
    composer require org_heigl/hybridauth
    
If you want to use more than one authentication-provider you should instead run this:

    # Require the unstable dependency to enable installation with min-requirement "stable"
    composer require --no-update hybridauth/hybridauth:@dev
    # Require the hybridauth-module
    composer require org_heigl/hybridauth:dev-feature/multipleProviders

### Manual installation

So you want it the hard way? Sure you don't want to give composer a try?

OK, you wanted it that way. But don't blame me!

I have to assume some things here:

* You have a dedicated ```vendor```-Folder where you install all your external libraries.
* Inside that vendor-Folder you have a subfolder for each vendor.
* Inside a vendor-subfolder you have subfolders for the actual library
  (Yes, that's the way composer organizes the files!)
  So your ZF-Library is installed inside ```vendor/zendframework/zendframework```.
* You have the ZF2-autoloader set up successfully.

So from there you'll have to follow these steps:

* Download the [Hybridauth-library](http://hybridauth.sourceforge.net/) to a folder ```vendor/hybridauth/hybridauth```
* Download this library to a folder ```vendor/org_heigl/hybridauth```
* Run the script ```vendor/org_heigl/hybridauth/bin/createAutoloadSupport.php```

Now you should be up and running to follow the steps outlined in the [Usage]-section.

Note that you can either download the zip-files of the libraries or use the git submodule command to clone the
libs into the appropriate folders. You should **not** simply use ```git clone <library> <target>``` as that might
interfere with your local git-repo (when you use one). The submodule approach makes Lib-updates easier bun can
end in a lot of headaches due to the caveats of the submodule-command! I can not provide you with support in that case!
Alternatively you can fork the project at [github](https://github.com/heiglandreas/OrgHeiglHybridAuth).


