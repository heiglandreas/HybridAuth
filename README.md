# OrgHeiglHybridAuth

Use the HybridAuth-Library to create an absolute lightweight Authentication-Layer
for your ZendFramework2-App

You can login with all supported SocialNetwork-Logins. THe network and the 
loginname will be stored in the session. If you already have SocialNetwork users 
in your application you can use these to authorize your users.

## Usage

1. In your application.conf-file add the Module to the list of modules
2. Add this snippet to create a login-link
::

    <?php echo $this->orgheiglhybridauth(); ?>
    
## Installation

This module is best installed using [composer](http://packagist.org/packages/org_heigl/hybridauth).

Alternatively you can clone the project at [github](https://github.com/heiglandreas/OrgHeiglHybridAuth).