# OrgHeiglHybridAuth

[![Build Status](https://travis-ci.org/heiglandreas/HybridAuth.png?branch=master)](https://travis-ci.org/heiglandreas/HybridAuth)

Use the HybridAuth-Library to create an absolute lightweight Authentication-Layer
for your ZendFramework2-App

You can login with all [supported SocialNetwork-Logins](http://hybridauth.sourceforge.net/userguide.html).
The network and a user-object holding id, name, mail and language will be stored in the session. If you already have
SocialNetwork users in your application you can use these to authorize your users.

## Usage

1. In your application.conf-file add the Module to the list of modules
2. Add this snippet to create a login-link

    <?php echo $this->orgheiglhybridauth(); ?>
    
## Installation

This module is best installed using [composer](http://packagist.org/packages/org_heigl/hybridauth). For that, add the
following line to the ```require```-section of your ```composer.json```-file and run composer.

    "org_heigl/hybridauth": "dev-master"

Alternatively you can fork the project at [github](https://github.com/heiglandreas/OrgHeiglHybridAuth).