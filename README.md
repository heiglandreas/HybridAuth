# OrgHeiglHybridAuth

[![Join the chat at https://gitter.im/heiglandreas/HybridAuth](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/heiglandreas/HybridAuth?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/heiglandreas/HybridAuth.png?branch=master)](https://travis-ci.org/heiglandreas/HybridAuth)
[![Code Climate](https://codeclimate.com/github/heiglandreas/HybridAuth/badges/gpa.svg)](https://codeclimate.com/github/heiglandreas/HybridAuth)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/heiglandreas/HybridAuth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/heiglandreas/HybridAuth/?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f98bc80bacff432b81d7d3ee84248901)](https://www.codacy.com/app/github_70/HybridAuth?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=heiglandreas/HybridAuth&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/heiglandreas/HybridAuth/badge.svg?branch=master)](https://coveralls.io/github/heiglandreas/HybridAuth?branch=master)

[![Dependency Status](https://www.versioneye.com/user/projects/58a31cce940b230036768774/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/58a31cce940b230036768774)
[![Total Downloads](https://poser.pugx.org/org_heigl/hybridauth/downloads.png)](https://packagist.org/packages/org_heigl/hybridauth)
[![Latest Stable Version](https://poser.pugx.org/org_heigl/hybridauth/v/stable.png)](https://packagist.org/packages/org_heigl/hybridauth)
[![Stories in Ready](https://badge.waffle.io/heiglandreas/HybridAuth.png?label=ready)](https://waffle.io/heiglandreas/HybridAuth)  

Use the SocialConnect-Library to create an absolute lightweight Authentication-Layer
for your ZendFramework3-App

You can login with all [supported SocialNetwork-Logins](https://github.com/SocialConnect/auth).
The network and a user-object holding id, name, mail and language will be stored in the session. If you already have
SocialNetwork users in your application you can use these to authorize your users.

## Requirements

* The [SocialConnect-Library](https://github.com/SocialConnect/auth). This lib uses the version 3 which is not (yet) stable!
* Zend Framework3 (well, obvious, isn't it?)

## Usage

1. In your application.conf-file add the Module to the list of modules
2. Copy the file ```vendor/org_heigl/hybridauth/config/autoload/module-orgHeiglHybridAuth.local.php``` to your
    applications ```config/autoload```-directory and adapt as appropriate. That might look like this:

    ```php
    return [
        'OrgHeiglHybridAuth' => [
            'socialAuth' => [
                'redirectUri' => 'http://localhost:8080/authenticate/backend',
                'provider' => [
                    'twitter' => [
                        'applicationId' => '',
                        'applicationSecret' => '',
                        'scope' => ['email'],
                    ],
                    'github' => [
                        'applicationId' => '',
                        'applicationSecret' => '',
                        'scope' => ['email'],
                    ],
                ],
            ],
            'session_name' => 'orgheiglhybridauth',
            'backend'         => array('Twitter'), // could also be ['Twitter', 'Facebook']
            // 'link'            => '<a class="hybridauth" href="%2$s">%1$s</a>', // Will be either inserted as first parameter into item or simply returned as complete entry
            // 'item'            => '<li%2$s>%1$s</li>',
            // 'itemlist'        => '<ul%2$s>%1$s</ul>',
            // 'logincontainer'  => '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">%1$s<b class="caret"></b></a>%2$s</li>',
            // 'logoffcontainer' => '<li>%1$s</li>',
            // 'logoffstring'    => 'Logout %1$s',
            // 'loginstring'     => 'Login%1$s',
            // 'listAttribs'     => null, // Will be inserted as 2nd parameter into item
            // 'itemAttribs'     => null, // Will be inserted as 2nd parameter into itemlist
        ]
    ];
    ```

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
    echo $token->getDisplayName(); // The name of the logged in user
    echo $token->getUID();  // The internal UID of the used service
    echo $token->getMail(); // The mail-address the service provides
    echo $token->getLanguage(); // The language the service provides for the user
    echo $token->getService()  // Should print out the Name of the service provider.
    ```

## Installation

### composer

This module is best installed using [composer](http://packagist.org/packages/org_heigl/hybridauth).
For that, run the following command to add the library to your app:

    # Require the hybridauth-module
    composer require org_heigl/hybridauth
    
If you want to use more than one authentication-provider you should instead run this:

    # Require the hybridauth-module
    composer require org_heigl/hybridauth:dev-feature/multipleProviders

### Manual installation

So you want it the hard way? Sure you don't want to give composer a try?

Then go figure it out. You might want to ask on the gitter channel or on IRC (freenode)
but expect a reply along the line "use composer!"

Note that you can either download the zip-files of the libraries or use the git submodule command to clone the
libs into the appropriate folders. You should **not** simply use ```git clone <library> <target>``` as that might
interfere with your local git-repo (when you use one). The submodule approach makes Lib-updates easier bun can
end in a lot of headaches due to the caveats of the submodule-command! I can not provide you with support in that case!
Alternatively you can fork the project at [github](https://github.com/heiglandreas/OrgHeiglHybridAuth).

### Example Implementation.

There is an example-implementation at https://hybridauth.heigl.org - The 
sourcecode is on github.
