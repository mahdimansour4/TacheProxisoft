<?php

namespace App\Controller;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class TagController extends AbstractController
{
    #[Route('/tag/create/{id}', name: 'tag.create')]
    public function indexAction(Request $request, EntityManagerInterface $em,$id): Response
    {   
        $alltags = $em->getRepository(Tag::class)->findAll();
        if($request->getMethod()=='POST'){
        $tag = new Tag();
        $tag->setName($request->get('name'));
        $tag->setType($request->get('type'));
        $tag->setColor($request->get('color'));
        $em->persist($tag);
        $em->flush();
        return $this->json([
            'type' => $tag->getType(),
            'name' => $tag->getName(),
            'color' => $tag->getColor()
        ]);
    }
    return $this->render('test/edit.html.twig', [
        'tags' => $alltags
    ]);
    }
}