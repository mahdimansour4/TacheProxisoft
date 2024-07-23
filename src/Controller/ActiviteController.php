<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ActiviteController extends AbstractController{

    #[Route('/test/activity/{id}', name: 'test.edit.activity')]
    public function createActivite(EntityManagerInterface $em, Request $request, $id): Response
    {
        $allTags = [];
        $allSources = [];

        // Fetch all Test entities from the repository
        $allTests = $em->getRepository(Test::class)->findAll();

        // Iterate through each Test entity
        foreach ($allTests as $test1) {
            // Get the tags for each Test entity
            $tags1 = $test1->getTags();
            $source1 = $test1->getSource();
            
            // Ensure $tags is an array and not null
            if (is_array($tags1)) {
                foreach ($tags1 as $tag) {
                    // Add tag to $allTags array if it doesn't already exist
                    if (!in_array($tag, $allTags)) {
                        $allTags[] = $tag;
                    }
                }
            }
            if (is_array($source1)) {
                foreach ($source1 as $source) {
                    if (!in_array($source, $allSources)) {
                        $allSources[] = $source;
                    }
                }
            }
        }

        if ($request->isMethod('POST')) {
            $activite = new Activite();
            $activite->setType($request->get('type'));
            $activite->setDateCreation(new \DateTime('now'));
            $activite->setDate(new \DateTime('now'));

            switch ($request->get('type')) {
                case 'Commentaire':
                    $activite->setCommentaire($request->get('commentaire'));
                    break;
                case 'Email':
                    $activite->setEmail($request->get('email'));
                    $activite->setDestinataire($request->get('destinataire'));
                    $activite->setTypeAppel($request->get('typeTel'));
                    $activite->setCommentaire($request->get('commentaire'));
                    break;
                case 'Appel':
                case 'Rappel':
                    $activite->setTelephone($request->get('telephone'));
                    $activite->setCommentaire($request->get('commentaire'));
                    $activite->setTypeAppel($request->get('typeTel'));
                    $activite->setCommentaire($request->get('commentaire'));
                break;
                case 'SMS':
                    $activite->setTelephone($request->get('SMS'));
                    $activite->setTypeAppel($request->get('typeTel'));
                    $activite->setCommentaire($request->get('commentaire'));
                    break;
                case 'RDV':
                    $activite->setTelephone($request->get('telephone'));
                    $activite->setCommentaire($request->get('commentaire'));
                    $activite->setTypeAppel($request->get('typeTel'));
                    $activite->setDate(new \DateTime($request->get('date')));
                    break;
            }

            $activite->addTest($em->getRepository(Test::class)->find($id));
            $em->persist($activite);
            $em->flush();

            // Return JSON response
            return new JsonResponse([
                'status' => 'success',
                'activity' => [
                    'id' => $activite->getId(),
                    'type' => $activite->getType(),
                    'commentaire' => $activite->getCommentaire(),
                    'email' => $activite->getEmail(),
                    'date' => $activite->getDate()->format('Y-m-d H:i:s'),
                ],
            ]);
        }

        return $this->render('test/edit.html.twig', [
            'test' => $em->getRepository(Test::class)->find($id),
            'allTags' => $allTags,
            'allSources' => $allSources,
        ]);
    }

    #[Route('/test/activity/delete/{id}', name: 'test.activity.delete', methods: ['POST'])]
    public function delete(Request $request, $id, EntityManagerInterface $em) {
        $activite = $em->getRepository(Activite::class)->find($id);

        if (!$activite) {
            return new JsonResponse(['status' => 'error', 'message' => 'Activity not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($activite);
        $em->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Activity deleted successfully']);
    }


}