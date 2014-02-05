<?php

// configure your app for the production environment

$db = $app['db'];
$sm = $db->getSchemaManager();

if (!$sm->tablesExist(['beers'])){

	$table = new Doctrine\DBAL\Schema\Table('beers');
	$table->addColumn('code','string',['primary'=>true]);
	$table->addColumn('name','string');
	$table->addColumn('type','string');
	$table->addColumn('country','string');
	$table->addColumn('description','string');
	$table->addColumn('image','string');
	$table->setPrimaryKey(['code']);
	$sm->createTable($table);
}