<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\BookRentingRepository;

#[AsCommand(name: 'app:return-old-book-rent')]
class ReturnAfterMonthCommand extends Command
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
            ->setDescription('Return every old books after a month of being rented')
            ->setHelp('This command returns every old books after a month of being rented')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Begin returning old books');
        $this->book_renting_repository->returnOldBookRenting();
        $output->writeln('Returning old books');

        return 1;
    }
}