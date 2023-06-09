<?php

namespace App\Command;

use App\Entity\AdventureItems;
use App\Entity\AdventureRoom;
use Doctrine\ORM\EntityManagerInterface;
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

        $roomCSV = '%kernel.root_dir%/../public/data/Room.csv';
        if (($handle = fopen($roomCSV, 'r')) !== false) {
            $header = fgetcsv($handle); // Read and discard the header row

            while (($row = fgetcsv($handle)) !== false) {
                $room = new AdventureRoom();

                if ($row[0]) {
                    $room->setName($row[0]);
                }

                if ($row[1]) {
                    $room->setDescription($row[1]);
                }

                if ($row[2]) {
                    $room->setImage($row[2]);
                }

                if ($row[3]) {
                    $room->setNorth($row[3]);
                }

                if ($row[4]) {
                    $room->setEast($row[4]);
                }

                if ($row[5]) {
                    $room->setSouth($row[5]);
                }

                if ($row[6]) {
                    $room->setWest($row[6]);
                }

                if ($row[7]) {
                    $room->setInspect($row[7]);
                }

                $this->emi->persist($room);
            }

            fclose($handle);
        }

        $itemsCSV = '%kernel.root_dir%/../public/data/Items.csv';
        if (($handle = fopen($itemsCSV, 'r')) !== false) {
            $header = fgetcsv($handle); // Read and discard the header row

            while (($row = fgetcsv($handle)) !== false) {
                $room = new AdventureItems();

                if ($row[0]) {
                    $room->setName($row[0]);
                }

                if ($row[1]) {
                    $room->setDescription($row[1]);
                }

                if (intval($row[2]) >= 0) {
                    $room->setPrice($row[2]);
                }

                if ($row[3]) {
                    $room->setImage($row[3]);
                }

                if ($row[4]) {
                    $room->setRoom($row[4]);
                }

                $this->emi->persist($room);
            }

            fclose($handle);
        }

        $this->emi->flush();

        $iObj->success('Everything went well!');
        return Command::SUCCESS;
    }
}
