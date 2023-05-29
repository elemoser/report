<?php

namespace App\Command;

use App\Entity\AdventureItems;
use App\Entity\AdventureRoom;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CsvImportCommand extends Command
{
    protected EntityManagerInterface $emi;

    public function __construct(EntityManagerInterface $emi)
    {
        parent::__construct();

        $this->emi = $emi;
    }
    protected function configure(): void
    {
        $this
            ->setName('csv:import')
            ->setDescription('Imports data from CSV file')
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    )
    {
        $iObj = new SymfonyStyle($input, $output);

        $iObj->title('Attempting to import feed...');

        $readerRoom = Reader::createFromPath(path: '%kernel.root_dir%/../public/data/Room.csv');

        $readerRoom->setHeaderOffset(0);
        $rooms = $readerRoom->getRecords();

        foreach ($rooms as $header => $row) {
            $room = (new AdventureRoom())
            ->setName($row['name'])
            ->setDescription($row['description'])
            ->setImage($row['image'])
            ->setNorth($row['north'])
            ->setEast($row['east'])
            ->setSouth($row['south'])
            ->setWest($row['west'])
            ->setInspect($row['inspect'])
            ;

            $this->emi->persist($room);
        }

        $readerItems = Reader::createFromPath(path: '%kernel.root_dir%/../public/data/Items.csv');

        $readerItems->setHeaderOffset(0);
        $items = $readerItems->getRecords();

        foreach ($items as $header => $row) {
            $item = (new AdventureItems())
            ->setName($row['name'])
            ->setDescription($row['description'])
            ->setPrice($row['price'])
            ->setImage($row['image'])
            ->setRoom($row['room'])
            ;

            $this->emi->persist($item);
        }

        $this->emi->flush();

        $iObj->success('Everything went well!');
        return Command::SUCCESS;
    }
}
