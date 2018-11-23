<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function index()
    {
        return $this->render('front/home.html.twig', [
            'title' => 'Home',
            'menus' => ['Lorem', 'Ipsum', 'Dolor', 'Sit', 'Amet'],
            'courses_offered' => [
                'Information Technology',
                'Arts & Media',
                'Business Management',
                'Project Management'
            ],
            'left_soc' => ['fb', 'twt', 'gplus', 'pin'],
            'sup_icons' => ['chat', 'info', 'fund'],
            'ftr_soc' => ['fb', 'twt', 'in', 'gplus', 'pin']
        ]);
    }
}
