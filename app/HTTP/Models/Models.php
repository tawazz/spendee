<?php

  $container['User'] = function(){
      return new \HTTP\Models\User();
  };
  $container['Exp'] = function(){
      return new \HTTP\Models\Expense();
  };
  $container['Inc'] = function(){
      return new \HTTP\Models\Income();
  };
  $container['Tags'] = function(){
      return new \HTTP\Models\Tag();
  };
  $container['ExpTags'] = function(){
      return new \HTTP\Models\ExpenseTag();
  };
  $container['IncTags'] = function(){
      return new \HTTP\Models\IncomeTag();
  };
  $container['Remember'] = function(){
      return new \HTTP\Models\Remember();
  };
  $container['Budget'] = function(){
      return new \HTTP\Models\Budget();
  };
  $container['BudgetTag'] = function(){
      return new \HTTP\Models\BudgetTag();
  };
  $container['Places'] = $container->factory(function($c) {
      return new \HTTP\Models\Place();
  });
  $container['RecurringExpense'] = $container->factory(function($c) {
      return new \HTTP\Models\RecurringExpense();
  });
  $container['Location'] = $container->factory(function($c) {
      return new \HTTP\Models\Location();
  });
 ?>
