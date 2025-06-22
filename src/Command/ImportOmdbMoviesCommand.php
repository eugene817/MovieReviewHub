<?php
namespace App\Command;

use App\Entity\Movie;
use App\Service\OmdbClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        name: "app:import-omdb-movies",
        description: "Import N movies from OMDb by search query"
    )
]
class ImportOmdbMoviesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private OmdbClient $omdbClient
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            "query",
            InputArgument::OPTIONAL,
            "Search query",
            "the"
        )->addArgument(
            "count",
            InputArgument::OPTIONAL,
            "Number of movies to import",
            100
        );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $query = $input->getArgument("query");
        $count = (int) $input->getArgument("count");
        $imported = 0;
        $page = 1;

        $output->writeln(
            "<info>Searching OMDb for “{$query}” up to {$count} movies…</info>"
        );

        while ($imported < $count) {
            $results = $this->omdbClient->search($query, $page);
            if (empty($results)) {
                $output->writeln("<comment>No more results.</comment>");
                break;
            }

            foreach ($results as $item) {
                if ($imported >= $count) {
                    break 2;
                }
                $imdbId = $item["imdbID"];
                try {
                    $data = $this->omdbClient->fetchById($imdbId);
                } catch (\Throwable $e) {
                    $output->writeln(
                        "<error>Failed fetch {$imdbId}: {$e->getMessage()}</error>"
                    );
                    continue;
                }

                $movie = new Movie();
                $movie
                    ->setTitle($data["Title"] ?? "")
                    ->setApiId($data["imdbID"] ?? null)
                    ->setDescription($data["Plot"] ?? null)
                    ->setPoster($data["Poster"] ?? null)
                    ->setReleaseDate(
                        !empty($data["Released"])
                            ? new \DateTime($data["Released"])
                            : null
                    )
                    ->setRating($data["imdbRating"] ?? null);

                $this->em->persist($movie);
                $imported++;
                $output->writeln(
                    "  • Imported [{$imported}] {$movie->getTitle()}"
                );
            }

            $this->em->flush();
            $page++;
        }

        $output->writeln("<info>Done. Total imported: {$imported}</info>");
        return Command::SUCCESS;
    }
}
