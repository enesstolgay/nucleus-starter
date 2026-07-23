<?php
namespace App\Controller;

use App\Entity\User;
use App\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    /* Olası hatalar: E-posta zaten kullanımda, e-posta sonuna başına boşluk, şifre kontrolleri (min-max uzunluk, özel karakter, sayı vb.), şifre tekrarı ile eşleşmeme, zayıf şifre vb. -Bunları hata mesajı olarak dön */

    #[Route("/api/register", name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['email']) || empty($data['password']) || empty($data['passwordAgain'])) {
            return $this->json([
                'message' => 'Lütfen zorunlu alanları eksiksiz giriniz!'
            ], Response::HTTP_BAD_REQUEST);
        }

        $email = trim($data['email']);

        if ($userRepository->findOneBy(['email' => $email])) {
            return $this->json([
                'message' => 'Bu email zaten kullanımda.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $password = $data['password'];
        $passwordAgain = $data['passwordAgain'];

        if ($password !== $passwordAgain) {
            return $this->json([
                'message' => 'Şifreleriniz uyuşmuyor!'
            ], Response::HTTP_BAD_REQUEST);
        }

        if (strlen(trim($password)) < 6) {
            return $this->json([
                'message' => 'Şifreniz en az 6 karakter uzunluğunda olmalıdır!'
            ], Response::HTTP_BAD_REQUEST); 
        }

        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return $this->json([
                'message' => 'Şifreniz en az bir özel karakter içermelidir!'
            ], Response::HTTP_BAD_REQUEST); 
        }

        if (!preg_match('/[0-9]/', $password)) {
            return $this->json([
                'message' => 'Şifreniz en az bir rakam içermelidir!'
            ], Response::HTTP_BAD_REQUEST); 
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return $this->json([
                'message' => 'Şifreniz en az bir büyük harf içermelidir!'
            ], Response::HTTP_BAD_REQUEST); 
        }

        if (!preg_match('/[a-z]/', $password)) {
            return $this->json([
                'message' => 'Şifreniz en az bir küçük harf içermelidir!'
            ], Response::HTTP_BAD_REQUEST); 
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([UserRole::USER->value]);
        
        // Şifreyi Güvenli Hashleme
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'Kayıt başarılı! Giriş sayfasına yönlendiriliyorsunuz...'
        ], Response::HTTP_CREATED);
        
    }
}