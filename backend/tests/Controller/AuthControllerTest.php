<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
     // 1. Senaryo: Eksik parametre gönderildiğinde 400 Bad Request dönmeli
    public function testRegisterMissingFields(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com'
            // password eksik
        ]));
        $this->assertResponseStatusCodeSame(400);
    }
    // 2. Senaryo: Şifreler uyuşmadığında 400 dönmeli
    public function testRegisterPasswordMismatch(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'Pass123!',
            'passwordAgain' => 'Different123!'
        ]));
        $this->assertResponseStatusCodeSame(400);
    }
    // 3. Senaryo: Özel karakter içermeyen zayıf şifrede 400 dönmeli
    public function testRegisterWeakPassword(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'password123', // Özel karakter yok
            'passwordAgain' => 'password123'
        ]));
        $this->assertResponseStatusCodeSame(400);
    }
    // 4. Senaryo: Başarılı Kayıt (201 Created)
    public function testRegisterSuccess(): void
    {
        $client = static::createClient();
        $email = 'user_' . uniqid() . '@example.com';
        $client->request('POST', '/api/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => $email,
            'password' => 'StrongPass123!',
            'passwordAgain' => 'StrongPass123!'
        ]));
        $this->assertResponseStatusCodeSame(201);
    }
}