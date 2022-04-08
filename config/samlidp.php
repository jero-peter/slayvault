<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SAML idP configuration file
    |--------------------------------------------------------------------------
    |
    | Use this file to configure the service providers you want to use.
    |
     */
    // Outputs data to your laravel.log file for debugging
    'debug' => true,
    // Define the email address field name in the users table
    'email_field' => 'email',
    // The URI to your login page
    'login_uri' => 'login',
    // Log out of the IdP after SLO
    'logout_after_slo' => env('LOGOUT_AFTER_SLO', false),
    // The URI to the saml metadata file, this describes your idP
    'issuer_uri' => 'saml/metadata',
    // Name of the certificate PEM file
    'certname' =>  'cert.pem',
    // Name of the certificate key PEM file
    'keyname' => 'key.pem',
    // Encrypt requests and responses
    'encrypt_assertion' => false,
    // Make sure messages are signed
    'messages_signed' => true,
    // Defind what digital algorithm you want to use
    'digest_algorithm' => \RobRichards\XMLSecLibs\XMLSecurityDSig::SHA1,
    // list of all service providers
    'sp' => [
        'aHR0cHM6Ly9zdXBwb3J0LXNsYXlhaWQuaGVyb2t1YXBwLmNvbS9zbGF5dmF1bHQvYWNz' =>[
            'destination' => 'https://support.saaslay.test/slayvault/acs',
            'logout' => 'https://support.saaslay.test/slayvault/sls',
            'certificate' => '',
        ],
	/**
	   This is a loop back test for the service -> do not let this go into production
	*/
	    // 'aHR0cDovLzEyNy4wLjAuMTo4MDAwL3NsYXl2YXVsdC9hY3M=' =>[
        //     'destination' => 'https://127.0.0.1:8000/slayvault/acs',
        //     'logout' => 'https://127.0.0.1:8000/slayvault/sls',
        //     'certificate' => '',
        // ],
        // 'aHR0cDovL2Y3NzktMTAzLTE0OC0yMC00Ni5uZ3Jvay5pby9zbGF5dmF1bHQvYWNz ' => [
        //     'destination' => 'https://f779-103-148-20-46.ngrok.io/slayvault/acs',
        //     'logout' => 'https://f779-103-148-20-46.ngrok.io/slayvault/sls',
        //     'certificate' => ''
        // ]
        
    ],

    // If you need to redirect after SLO depending on SLO initiator
    // key is beginning of HTTP_REFERER value from SERVER, value is redirect path
    'sp_slo_redirects' => [
        // 'http://localhost:8000/' => 'http://localhost:8000/'
    ],

    // All of the Laravel SAML IdP event / listener mappings.
    'events' => [
        'CodeGreenCreative\SamlIdp\Events\Assertion' => [],
        'Illuminate\Auth\Events\Logout' => [
            'CodeGreenCreative\SamlIdp\Listeners\SamlLogout',
        ],
        'Illuminate\Auth\Events\Authenticated' => [
            'CodeGreenCreative\SamlIdp\Listeners\SamlAuthenticated',
        ],
        'Illuminate\Auth\Events\Login' => [
            'CodeGreenCreative\SamlIdp\Listeners\SamlLogin',
        ],
    ],

    // List of guards saml idp will catch Authenticated, Login and Logout events
    'guards' => ['admin','secondary_admin']
];
