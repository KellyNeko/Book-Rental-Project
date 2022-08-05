<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\BookRentingRepository;

#[AsCommand(name: 'app:delete-old-book-rent')]
class DeleteAfterMonthCommand extends Command
{
    private $book_renting_repository;

    public function __construct(BookRentingRepository $book_renting_repository)
    {
        $this->book_renting_repository = $book_renting_repository;

        //call of the parent constructor
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setDescription('Delete old book renting after a month of being rented')
            ->setHelp('This command delete old book renting after a month of being rented')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Begin deleting old book renting');
        $this->book_renting_repository->deleteOldBookRenting();
        $output->writeln('Deleting old  book renting');

        return 1;
    }
}