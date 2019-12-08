<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class BlogController extends Controller
{
    /**
     * @Route("/", name="blog_posts")
     */
    public function listAction()
    {
    	$posts = $this->getDoctrine()
    		->getRepository('AppBundle:Post')
    		->findAll();
   
        return $this->render('blog/index.html.twig', array(
        	'posts' => $posts

        ));
    }


     /**
     * @Route("/blog/create", name="blog_create")
     */
    public function createAction(Request $request)
    {   $post = new Post;
       $form = $this->createFormBuilder($post)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'))) 
            ->add('capacity', ChoiceType::class, array('choices' => array('Less than 50' =>'<50', 'Between 50 and 100' => '50-100', 'Between 100 and 500' => '100-500', 'More than 500' => '500<'),'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('event_date', DateTimeType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('email', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('phone', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('url', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('image', FileType::class, array('label' => 'Image Upload Click Here', 'attr' => array('class' => 'btn btn-lg form-group')))
           



            ->add('save', SubmitType::class, array('label' => 'Event Creation', 'attr' => array('class' => 'btn btn-secondary m-3', 'style' => 'margin-bottom:15px')))

            ->getForm();
       $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid()) {

            // get data
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $capacity = $form['capacity']->getData();
            $event_date = $form['event_date']->getData();
            $create_date = new\DateTime('now');
            $email = $form['email']->getData();
            $phone = $form['phone']->getData();
            $url = $form['url']->getData();
            $image = $form['image']->getData()->guessExtension();





            $post->setName($name);
            $post->setCategory($category);
            $post->setDescription($description);
            $post->setCapacity($capacity);
            $post->setEventDate($event_date);
            $post->setCreateDate($create_date);
            $post->setEmail($email);
            $post->setPhone($phone);
            $post->setUrl($url);
            $post->setImage($image);

            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();

            $this->addFlash(
                'notice', 
                'Event added!'
            );

            return $this->redirectToRoute('blog_posts');
       }  


       return $this->render('blog/create.html.twig', array('form' => $form->createView()));
   }



     /**
     * @Route("/blog/edit/{id}", name="blog_edit")
     */
    public function editAction($id, Request $request)
    {
    	$post = $this->getDoctrine()
    		->getRepository('AppBundle:Post')
    		->find($id);

    		$now = new\DateTime('now');

    		$post->setName($post->getName());
            $post->setCategory($post->getCategory());
            $post->setDescription($post->getDescription());
            $post->setCapacity($post->getCapacity());
            $post->setEventDate($post->getEventDate());
            $post->setCreateDate($now);
            $post->setEmail($post->getEmail());
            $post->setPhone($post->getPhone());
            $post->setUrl($post->getUrl());
            $post->setImage($post->getImage());


       $form = $this->createFormBuilder($post)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'))) 
            ->add('capacity', ChoiceType::class, array('choices' => array('Less than 50' =>'<50', 'Between 50 and 100' => '50-100', 'Between 100 and 500' => '100-500', 'More than 500' => '500<'),'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('event_date', DateTimeType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('email', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('phone', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('url', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('image', FileType::class, array('label' => 'Image Upload Click Here', 'attr' => array('class' => 'btn btn-lg form-group')))
           



            ->add('save', SubmitType::class, array('label' => 'Update Event', 'attr' => array('class' => 'btn btn-secondary m-3', 'style' => 'margin-bottom:15px')))

            ->getForm();
       $form->handleRequest($request);
       
       if($form->isSubmitted() && $form->isValid()) {

            // get data
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $capacity = $form['capacity']->getData();
            $event_date = $form['event_date']->getData();
            $create_date = new\DateTime('now');
            $email = $form['email']->getData();
            $phone = $form['phone']->getData();
            $url = $form['url']->getData();
            $image = $form['image']->getData()->guessExtension();


           $em = $this->getDoctrine()->getManager();
           $post = $em->getRepository('AppBundle:Post')->find($id);

            $post->setName($name);
            $post->setCategory($category);
            $post->setDescription($description);
            $post->setCapacity($capacity);
            $post->setEventDate($event_date);
            $post->setCreateDate($create_date);
            $post->setEmail($email);
            $post->setPhone($phone);
            $post->setUrl($url);
            $post->setImage($image);

                    
            $em->flush();

            $this->addFlash(
                'notice', 
                'Event updated!'
            );

            return $this->redirectToRoute('blog_posts');
       }
   
        return $this->render('blog/edit.html.twig', array(
        	'post' => $post,
        	'form' => $form->createView()

        ));
    	
    }





     /**
     * @Route("/blog/details/{id}", name="blog_details")
     */
    public function detailsAction($id)
    {
    	$post = $this->getDoctrine()
    		->getRepository('AppBundle:Post')
    		->find($id);
   
        return $this->render('blog/details.html.twig', array(
        	'post' => $post

        ));
    }


    /**
     * @Route("/blog/delete/{id}", name="blog_delete")
     */
    public function deleteAction($id)
    {
    	

    	$this->addFlash(
                'notice', 
                'Event removed!'
            );

        return $this->redirectToRoute('blog_posts');
             
    }
}
