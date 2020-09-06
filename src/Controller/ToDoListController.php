<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\EmailType; 
use Symfony\Component\Form\Extension\Core\Type\FormType; 
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Tasks;
use Symfony\Component\Security\Core\User\UserInterface;
class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="create_new")

     */
    public function index()
    {
       $articles = $this->getDoctrine()->getRepository(Tasks::class)->findAll();
       return $this->render('dodolist/index.html.twig', array('articles'=>$articles));
    }

    

/**
     * @Route("/article/new", name="new_article")
    
    

     */
  public function  new(Request $request){

  $task = new Tasks();
  $form = $this->createFormBuilder($task)

  ->add('title', TextType::class,array('attr'=> array('class'=>'form-control ')))
  ->add('status', TextType::class,array('attr'=> array( 'class'=>'form-control ')))
  ->add('save', SubmitType::class,array('label'=> 'Create' , 'attr'=>array('class'=>'btn btn-primary mt-3')))
  
   ->getForm();
   $form->handleRequest($request);
   if($form->isSubmitted() && $form->isValid())
   {
     $task= $form->getData();
     $entityManager = $this->getDoctrine()->getManager();
     $entityManager->persist($task);
     $entityManager->flush();
     return $this->redirectTORoute('create_new');
   }
   return $this->render('dodolist/new.html.twig' , array('form'=>$form->createView()));
}

     /**
     * @Route("/article/form", name="form _article")
    

     */
    public function  form(Request $request){

      
      $form = $this->createFormBuilder()

      
      ->add('article', TextType::class,array( 'attr'=> array('class'=>'form-control ' )))
      ->add('search', SubmitType::class,array('label'=> 'search' , 'attr'=>array('class'=>'btn btn-primary mt-3')))
      
       ->getForm();
      $form->handleRequest($request);
   if($form->isSubmitted() && $form->isValid())
   {
    
    $data = $request->request->get('form')['article'];
    
    $em = $this->getDoctrine()->getManager();
    $query = $em->createQuery(
    'SELECT p.title FROM App:Tasks p
      WHERE p.title LIKE :data')
      ->setParameter('data', '%'.$data.'%');
      $res = $query->getResult();
      
      return $this->render('dodolist/resultsearch.html.twig', array('articles'=>$res));
    
   }
      
       return $this->render('dodolist/search.html.twig' , array('form'=>$form->createView()));
    }
    

     
/**
     * @Route("/article/edit/{id}", name="edit_article")
    
    

     */
    public function  edit(Request $request, $id){

        
        $tasks=$this->getDoctrine()->getRepository(Tasks::class)->find($id);
        $form = $this->createFormBuilder($tasks)
      
        ->add('title', TextType::class, array('attr'=> array('class'=>'form-control ')))
        ->add('status', TextType::class,array('attr'=> array( 'class'=>'form-control ')))
        ->add('save', SubmitType::class,array('label'=> 'Create' , 'attr'=>array('class'=>'btn btn-primary mt-3')))
        
         ->getForm();
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid())
         {
           
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->flush();
           return $this->redirectTORoute('create_new');
         }
         return $this->render('dodolist/edit.html.twig' , array('form'=>$form->createView()));
      }
/**
     * @Route("/article/{id}", name="show_article")
    

     */
    public function show($id){

        $article=$this->getDoctrine()->getRepository(Tasks::class)->find($id);
        
        return $this->render("dodolist/show.html.twig",array('article'=>$article));
        
        }
       
        /**
          * @Route("/article/delete/{id}", name="delete_article")

         */
    public function delete(Request $request , $id){

        $tasks=$this->getDoctrine()->getRepository(Tasks::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tasks);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        }
        
}
