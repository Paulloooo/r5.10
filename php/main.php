<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use MongoDB\Client;

class MongoConnectCommand extends Command
{
    protected static $defaultName = 'mongo:connect';

    protected function configure()
    {
        $this
            ->setDescription('Connect to MongoDB')
            ->setHelp('This command establishes a connection to MongoDB.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mongo = new Client('mongodb://root:example@localhost:27017');  // Remplacez localhost et le port si nécessaire
        $output->writeln('Connected to MongoDB');
        return Command::SUCCESS;
    }
}

class MongoDeleteCommand extends Command
{
    protected static $defaultName = 'mongo:delete';

    protected function configure()
    {
        $this
            ->setDescription('Delete existing documents')
            ->setHelp('This command deletes existing documents in a MongoDB collection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mongo = new Client('mongodb://root:example@localhost:27017');  // Remplacez localhost et le port si nécessaire
        $database = $mongo->selectDatabase('test_database');
        $collection = $database->selectCollection('test_collection');
        $collection->deleteMany([]);
        $output->writeln('Deleted existing documents');
        return Command::SUCCESS;
    }
}

class MongoInsertCommand extends Command
{
    protected static $defaultName = 'mongo:insert';

    protected function configure()
    {
        $this
            ->setDescription('Insert data into MongoDB')
            ->setHelp('This command inserts data into a MongoDB collection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mongo = new Client('mongodb://root:example@localhost:27017');  // Remplacez localhost et le port si nécessaire
        $database = $mongo->selectDatabase('test_database');
        $collection = $database->selectCollection('test_collection');
        $data = [
            [
                'name' => 'John',
                'age' => 30
            ],
            [
                'name' => 'Alice',
                'age' => 25
            ],
            [
                'name' => 'Bob',
                'age' => 28
            ]
        ];
        $collection->insertMany($data);
        $output->writeln('Inserted data into MongoDB');
        return Command::SUCCESS;
    }
}

class MongoSelectCommand extends Command
{
    protected static $defaultName = 'mongo:select';

    protected function configure()
    {
        $this
            ->setDescription('Select data from MongoDB')
            ->setHelp('This command selects data from a MongoDB collection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mongo = new Client('mongodb://root:example@localhost:27017');  // Remplacez localhost et le port si nécessaire
        $database = $mongo->selectDatabase('test_database');
        $collection = $database->selectCollection('test_collection');
        $cursor = $collection->find();
        foreach ($cursor as $document) {
            $output->writeln("Nom : " . $document['name'] . ", Age : " . $document['age']);
        }
        return Command::SUCCESS;
    }
}

$application = new Application();
$application->add(new MongoConnectCommand());
$application->add(new MongoDeleteCommand());
$application->add(new MongoInsertCommand());
$application->add(new MongoSelectCommand());
$application->run();

// Pour exécuter les commandes dans le terminal, il faut faire ça les gens :
// - php main.php mongo:connect
// - php main.php mongo:delete
// - php main.php mongo:insert
// - php main.php mongo:select
