<?php

namespace App\Controller;

use App\Entity\Test;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController{

    #[Route('/test/create', name: 'test.create')]
    public function indexAction(Request $request, EntityManagerInterface $em): Response
    {
        $allTags = [];
        $allSources =[];
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
                    // Add tag to $uniqueTags array if it doesn't already exist
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
        if ($request->getMethod() === 'POST') {
            $test = new Test();
            $test->setDescription($request->get('description'));
            $test->setDevis($request->get('devis'));
            $test->setEstimation($request->get('estimation'));
            $test->setDateEcheance(new \DateTime($request->get('echeance')));
            // Handling tags as an array
            $tags = $request->get('tags') ?: [];
            if (!empty($tags)) {
                // If tags were created dynamically, they might be strings and not an array
                if (!is_array($tags)) {
                    $tags = explode(',', $tags); // Convert string to array if needed
                    $tags = array_map('trim', $tags); // Trim each tag
                }
                $tags = array_unique($tags); // Ensure tags are unique
                $test->setTags($tags); // Set the tags as an array
            }

            // Handling sources as an array
            $sources = $request->get('source') ?: [];
            if (!empty($sources)) {
                // If sources were created dynamically, they might be strings and not an array
                if (!is_array($sources)) {
                    $sources = explode(',', $sources); // Convert string to array if needed
                    $sources = array_map('trim', $sources); // Trim each source
                }
                $sources = array_unique($sources); // Ensure sources are unique
                $test->setSource($sources); // Set the sources as an array
            }

            $em->persist($test);
            $em->flush();

            return $this->redirectToRoute('test.list');
        }

        return $this->render('test/index.html.twig',[
            'allTags' => $allTags,
            'allSources' => $allSources,
        ]);
    }





    #[Route('/test/list', name: 'test.list')]
    public function listAction(EntityManagerInterface $em):Response{
        $tests = $em->getRepository(Test::class)->findAll();
        return $this->render('test/list.html.twig', [
            'tests' => $tests
        ]);
    }

    #[Route('/test/edit/{id}', name: 'test.edit', methods: ['GET', 'POST'])]
public function editAction(Request $request, EntityManagerInterface $entityManager, Test $test): Response
{
    // Fetch all Test entities from the repository
    $allTests = $entityManager->getRepository(Test::class)->findAll();
    $allTags = [];
    $allSources = [];
    $activites = $test->getActivites();
    // Iterate through each Test entity
    foreach ($allTests as $test1) {
        // Get the tags for each Test entity
        $tags1 = $test1->getTags();
        $source1 = $test1->getSource();
        // Ensure $tags is an array and not null
        if (is_array($tags1)) {
            foreach ($tags1 as $tag) {
                // Add tag to $uniqueTags array if it doesn't already exist
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
    if ($request->getMethod() === 'POST') {
        // Update test entity with form data
        $test->setDescription($request->request->get('description'));
        $test->setDevis($request->request->get('devis'));
        $test->setEstimation($request->request->get('estimation'));
        $test->setDateEcheance(new \DateTime($request->request->get('echeance')));
        // Handling tags as an array
        $tags = $request->get('tags') ?: [];
        if (!empty($tags)) {
            // If tags were created dynamically, they might be strings and not an array
            if (!is_array($tags)) {
                $tags = explode(',', $tags); // Convert string to array if needed
                $tags = array_map('trim', $tags); // Trim each tag
            }
            $tags = array_unique($tags); // Ensure tags are unique
            $test->setTags($tags); // Set the tags as an array
        }
        // Handling sources as an array
        $sources = $request->get('source') ?: [];
        if (!empty($sources)) {
            // If sources were created dynamically, they might be strings and not an array
            if (!is_array($sources)) {
                $sources = explode(',', $sources); // Convert string to array if needed
                $sources = array_map('trim', $sources); // Trim each source
            }
            $sources = array_unique($sources); // Ensure sources are unique
            $test->setSource($sources); // Set the sources as an array
        }
        // Persist changes to database
        $entityManager->flush();
        // Return updated data as JSON response
        return $this->json([
            'description' => $test->getDescription(),
            'devis' => $test->getDevis(),
            'estimation' => $test->getEstimation(),
            'dateEcheance' => $test->getDateEcheance() ? $test->getDateEcheance()->format('d M Y') : null,
            'tags' => $test->getTags(),
            'source' => $test->getSource(),
        ]);
    }
    // Render the edit form with existing data
    return $this->render('test/edit.html.twig', [
        'test' => $test,
        'allTags' => $allTags,
        'allSources' => $allSources,
        'activities' => $activites,
    ]);
}

}



