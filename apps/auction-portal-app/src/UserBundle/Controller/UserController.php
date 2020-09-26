<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use UserBundle\Entity\User;
use UserBundle\Form\RegisterUserType;

class UserController extends Controller
{
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->encodePlainPassword($user);

            $this->getDoctrineManager()->persist($user);
            $this->getDoctrineManager()->flush();

            return $this->render('user/thank_you_page.html.twig');
        }

        return $this->render(
            'user/register_form.html.twig',
            ['form' => $form->createView()]
        );
    }

    public function loginAction() {

        $error = $this->getLastAuthenticationError()->getLastAuthenticationError();

        $lastUsername = $this->getLastAuthenticationError()->getLastUsername();

        return $this->render(
            'user/login.html.twig', array(
            'username' => $lastUsername,
            'error' => $error,
        ));
    }

    protected function encodePlainPassword(User $user)
    {
        $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }

    protected function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function encodeUserPassword(UserPasswordEncoderInterface $passwordEncoder, User $user)
    {
        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }

    protected function getLastAuthenticationError()
    {
        return $this->get('security.authentication_utils');
    }
}
