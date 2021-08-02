<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransfert\BroadcastData;
use App\Form\BroadcastType;
use App\Service\Subscription\SubscriptionService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * Class BroadcastController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BroadcastController extends AbstractController
{
    public function index(Request $request, SubscriptionService $service, KernelInterface $kernel): Response
    {
        $data = new BroadcastData();
        $form = $this->createForm(BroadcastType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($data->file) {
                    $path = $kernel->getProjectDir() . "/public/upload/broadcast";
                    $filename = 'attachment.' . $data->file->guessExtension();

                    try {
                        $data->file->move($path, $filename);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Impossible d\'envoyer ce fichier');
                        $this->redirectToRoute('app_broadcast_index');
                    }

                    $data->attachement = "$path/$filename";
                }

                $service->broadcast($data);
                $this->addFlash('success', 'Message diffusé avec succès !');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'Une erreur est survenue lors de la diffusion du message !');
            }

            $this->redirectToRoute('app_broadcast_index');
        }

        return $this->render('broadcast.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
