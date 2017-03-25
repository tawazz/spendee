<?php

  $app->container->set('Customer',function(){
    return new HTTP\Models\Customer();
  });
  $app->container->set('Service',function(){
    return new HTTP\Models\Service();
  });
  $app->container->set('Vault',function(){
    return new HTTP\Models\Vault();
  });
  $app->container->set('Billing',function(){
    return new HTTP\Models\Billing();
  });
 ?>
