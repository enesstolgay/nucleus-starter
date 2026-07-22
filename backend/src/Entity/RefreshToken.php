<?php

namespace App\Entity;

use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
#[ORM\Table(name: 'refresh_tokens')]
class RefreshToken extends AbstractRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int|string|null $id = null;

    #[ORM\Column(type: 'string', unique: true)]
    protected ?string $refreshToken = null;

    #[ORM\Column(type: 'string')]
    protected ?string $username = null;

    #[ORM\Column(type: 'datetime')]
    protected ?\DateTimeInterface $valid = null;
}
