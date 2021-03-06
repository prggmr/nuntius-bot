<?php

namespace Nuntius\Commands;

use Nuntius\Nuntius;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * CLI support to the entity manager service.
 */
class EntityManagerCommand extends Command {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('nuntius:entity')
      ->setDescription('Manage entities')
      ->setHelp('Manage entities from the terminal')
      ->addArgument('name', InputArgument::REQUIRED, 'The entity name.')
      ->addArgument('operation', InputArgument::OPTIONAL, 'What kind of operation you want to preform: list or live_view', 'list')
      ->addArgument('limit', InputArgument::OPTIONAL, 'Amount of entities to display', 25);
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new SymfonyStyle($input, $output);
    $arguments = $input->getArguments();
    $entities = Nuntius::getEntityManager()->getEntities();

    if (empty($entities[$arguments['name']])) {
      $io->error('The entity ' . $arguments['name'] . ' does not exists');
      return;
    }

    $results = Nuntius::getDb()->getQuery()->table($arguments['name']);

    if ($arguments['operation'] == 'live_view') {

      if (!Nuntius::getDb()->getMetadata()->supportRealTime()) {
        throw new \Exception('Your current driver does not support real time. Try another one');
      }

      $cursor = $results->setChanges()->execute();

      $io->section('Starting live feeds');
      foreach ($cursor as $row) {
        var_dump($row->getArrayCopy());
      }

    }

    $rows = $results->pager(0, intval($arguments['limit']))->execute();

    foreach ($rows as $row) {
      \Kint::dump($row);
    }

  }

}
