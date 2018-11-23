<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Form\Forms;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Utilities\Fibonacci;
use App\Utilities\Palindrome;
use App\Utilities\Anagram;
use App\Utilities\NumberSorting;

class DashboardController extends Controller
{
    public function index()
    {
        $doctrine = $this->getDoctrine();
        
        return $this->render('dashboard/index.html.twig', [
            'title' => 'Dashboard',
            'users' => $doctrine->getRepository(User::class)->findAll()
        ]);
    }
    
    public function users(Request $request)
    {
        $doctrine = $this->getDoctrine();
        
        return $this->render('dashboard/users/index.html.twig', [
            'title' => 'Users',
            'users' => $doctrine->getRepository(User::class)->findAll()
        ]);
    }
    
    public function user_form(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $doctrine = $this->getDoctrine()->getEntityManager();
        
        $id = $request->get("id");
        $now = new \DateTime("now");

        if (!is_null($id)) {
            $user = $doctrine->getRepository(User::class)->find($id);
            if (empty($user)) {
                return $this->redirectToRoute("users");
            }
        } else {
            $user = new User();
            $user->setRoles("ROLE_USER");
        }

        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();
        $form = $formFactory->createBuilder(UserType::class, $user)->getForm();
        $form->handleRequest();
        
        if("POST" == $request->getMethod()) {            
            if($form->isValid()){
                $formData = $form->getData();
                
                // encode password
                $plainPassword = $formData->getPlainPassword();
                if (!empty($plainPassword)) {
                    $encodedPassword = $passwordEncoder->encodePassword($user, $plainPassword);
                    $formData->setPassword($encodedPassword);
                }
                
                $doctrine->persist($formData);
                $doctrine->flush();
                
                return $this->redirectToRoute("user.edit", ['id' => $formData->getId()]);
            }
        }

        $view = array(
            "id" => $id,
            "title" => (!empty($id) ? "Edit" : "Create") . " User",
            'form' => $form->createView()
        );

        return $this->render("dashboard/users/form.html.twig", $view);
    }
    
    public function user_delete(Request $request)
    {
        $id = $request->get('id');
        $doctrine = $this->getDoctrine()->getEntityManager();
        
        $user = $doctrine->getRepository(User::class)->find($id);
        if (is_object($user)) {
            $doctrine->remove($user);
            $doctrine->flush();
            
            return $this->json([
                'message' => "User with $id has been deleted."
            ]);
        } else {
            return $this->json([
                'error' => "User with $id does not exists."
            ]);
        }
    }

    public function fibonacci(Request $request)
    {
        $series_length = $request->get('series_length');
        
        if (intval($series_length) > 0) {
            $series = Fibonacci::FibonacciSeries($series_length);
        } elseif (null !== ($request->get('series_length')) && intval($series_length) === 0) {
            $error = "Please enter a number greater than 0";
        }
        
        return $this->render('dashboard/fibonacci.html.twig', [
            'title' => 'Fibonacci Series',
            'series' => isset($series) ? $series : null,
            'error' => isset($error) ? $error : null
        ]);
    }
    
    public function palindrome(Request $request)
    {
        $phrase = $request->get('phrase');
        
        if (strlen($phrase) > 0) {
            $is_palindrome = Palindrome::isPalindrome($phrase);
        } elseif (null !== ($request->get('phrase')) && strlen($phrase) === 0) {
            $error = "Please enter a phrase";
        }
        
        return $this->render('dashboard/palindrome.html.twig', [
            'title' => 'Palindrome Tester',
            'is_palindrome' => isset($is_palindrome) ? $is_palindrome : null,
            'error' => isset($error) ? $error : null
        ]);
    }
    
    public function anagram(Request $request)
    {
        $word1 = $request->get('word1');
        $word2 = $request->get('word2');
        
        if (strlen($word1) > 0 && strlen($word2) > 0) {
            $are_anagram = Anagram::areAnagram($word1, $word2);
        } elseif (null !== ($request->get('word1')) && (strlen($word1) === 0 || strlen($word2) === 0)) {
            $error = "Please enter a word for both inputs";
        }
        
        return $this->render('dashboard/anagram.html.twig', [
            'title' => 'Anagram Tester',
            'are_anagram' => isset($are_anagram) ? $are_anagram : null,
            'error' => isset($error) ? $error : null
        ]);
    }
    
    public function number_sorting(Request $request)
    {
        $numbers = $request->get('numbers');
        if (!is_null($numbers)) {
            foreach($numbers as $number) {
                if (!is_numeric($number)) {
                    $error = "Please enter a number for each input";
                    break;
                }
            }
        }
        
        if (null !== $request->get('numbers') && !isset($error)) {
            $sort_order = NumberSorting::sort($numbers);
        }
        
        return $this->render('dashboard/number-sorting.html.twig', [
            'title' => 'Number Sorting',
            'sort_order' => isset($sort_order) ? $sort_order : null,
            'error' => isset($error) ? $error : null
        ]);
    }
}
