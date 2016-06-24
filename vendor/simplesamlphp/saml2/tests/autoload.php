<?php

// Load Composer autoloader
require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';

// And set the Mock container as the Container to use.
SAML2_Compat_ContainerSingleton::setContainer(new SAML2_Compat_MockContainer());
