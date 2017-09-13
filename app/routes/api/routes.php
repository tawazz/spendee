<?php
    use \HTTP\Controllers\API\ExpensesApi;
    use \HTTP\Controllers\API\IncomesApi;
    use \HTTP\Controllers\API\Totals;
    use \HTTP\Controllers\API\Tags;
    use \HTTP\Controllers\API\ExpenseTags;
    use \HTTP\Controllers\API\Places;

    $this->get('/expenses[/{year}[/{month}[/{day}]]]',ExpensesApi::class)->setName('api.expenses');
    $this->get('/expense/{id}',ExpensesApi::class.':retrieve')->setName('api.expense.retrieve');
    $this->post('/expense',ExpensesApi::class.':create')->setName('api.expense.create');
    $this->put('/expense/{id}',ExpensesApi::class.':update')->setName('api.expense.update');
    $this->delete('/expense/{id}',ExpensesApi::class.':delete')->setName('api.expense.delete');
    $this->get('/options/expenses/repeat',ExpensesApi::class.':repeatOptions')->setName('api.expense.repeat');
    $this->post('/import/expenses',ExpensesApi::class.':import')->setName('api.expense.import');

    $this->get('/incomes[/{year}[/{month}[/{day}]]]',IncomesApi::class)->setName('api.incomes');
    $this->get('/income/{name}[/{year}]',IncomesApi::class.':retrieve')->setName('api.income.retrieve');
    $this->post('/income',IncomesApi::class.':create')->setName('api.income.create');
    $this->put('/income/{id}',IncomesApi::class.':update')->setName('api.income.update');
    $this->delete('/income/{id}',IncomesApi::class.':delete')->setName('api.income.delete');

    $this->get('/totals[/{year}[/{month}[/{day}]]]',Totals::class)->setName('api.totals');

    $this->get('/tags',Tags::class)->setName('api.tags');
    $this->get('/tag/{id}',Tags::class.':retrieve')->setName('api.tag');
    $this->get('/tags/expenses[/{id}[/{year}[/{month}]]]',ExpenseTags::class)->setName('api.tags.expenses');

    $this->get('/places',Places::class)->setName('api.places');

 ?>
