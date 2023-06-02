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
    ) {
        $iObj = new SymfonyStyle($input, $output);

        $iObj->title('Attempting to import feed...');

        $readerRoom = Reader::createFromPath(path: '%kernel.root_dir%/../public/data/Room.csv');

        $readerRoom->setHeaderOffset(0);
        $rooms = $readerRoom->getRecords();

        foreach ($rooms as $header => $row) {
            $room = new AdventureRoom();

            if ($row['name']) {
                $room->setName($row['name']);
            }

            if ($row['description']) {
                $room->setDescription($row['description']);
            }

            if ($row['image']) {
                $room->setImage($row['image']);
            }

            if ($row['north']) {
                $room->setNorth($row['north']);
            }

            if ($row['east']) {
                $room->setEast($row['east']);
            }

            if ($row['south']) {
                $room->setSouth($row['south']);
            }

            if ($row['west']) {
                $room->setWest($row['west']);
            }

            if ($row['inspect']) {
                $room->setInspect($row['inspect']);
            }

            $this->emi->persist($room);
        }

        $readerItems = Reader::createFromPath(path: '%kernel.root_dir%/../public/data/Items.csv');

        $readerItems->setHeaderOffset(0);
        $items = $readerItems->getRecords();

        foreach ($items as $header => $row) {
            $item = new AdventureItems();

            if ($row['name']) {
                $item->setName($row['name']);
            }

            if ($row['description']) {
                $item->setDescription($row['description']);
            }

            if ($row['price']) {
                $item->setPrice(intval($row['price']));
            }

            if ($row['image']) {
                $item->setImage($row['image']);
            }

            if ($row['room']) {
                $item->setRoom($row['room']);
            }

            $this->emi->persist($item);
        }

        $this->emi->flush();

        $iObj->success('Everything went well!');
        return Command::SUCCESS;
    }
}
