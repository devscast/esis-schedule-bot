<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class CommandController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class CommandController extends AbstractController
{
    private const RUNNABLE_COMMANDS = [
        'bot:telegram-push',
        'bot:fetch-timetable',
        'bot:web-scrape',
    ];

    public function index(): Response
    {
        return $this->render("command.html.twig");
    }

    /**
     * @throws \Exception
     */
    public function execute(string $command, KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        if (in_array($command, self::RUNNABLE_COMMANDS)) {
            try {
                $input = new ArrayInput(compact('command'));

                $output = new BufferedOutput();
                $application->run($input, $output);
                $content = $output->fetch();

                return new JsonResponse([
                    'message' => 'Commande exécutée avec succès !',
                    'content' => $content
                ], Response::HTTP_ACCEPTED);
            } catch (\Exception $e) {
                goto execution_failed;
            }
        }

        execution_failed: {
            return new JsonResponse(
                ['message' => 'Commande non reconnue ou indisponible !'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
