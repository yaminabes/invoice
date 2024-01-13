<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenStorageInterface $tokenStorage): Response
    {
        // Create a new User entity with default values
        $user = new User();
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']); // Default role is admin
        $user->setBasicCost('0.0'); // Default basic cost is 0.0

        $form = $this->createForm(RegistrationFormType::class, $user);

        // Handle registration form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

            // Save the user to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            // ... handle the registration success or redirect to a login page
            return $this->redirectToRoute('app_home'); // Adjust the route as needed
        }

        // Render the registration form template
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
