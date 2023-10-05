<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 155)]
    private ?string $username = null;

    #[ORM\Column(length: 155, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 155, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $create_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $last_connection_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $email_verification_date = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Channel::class, orphanRemoval: true)]
    private Collection $own_channels;

    public function __construct()
    {
        $this->own_channels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->create_date;
    }

    public function setCreateDate(\DateTimeInterface $create_date): static
    {
        $this->create_date = $create_date;

        return $this;
    }

    public function getLastConnectionDate(): ?\DateTimeInterface
    {
        return $this->last_connection_date;
    }

    public function setLastConnectionDate(\DateTimeInterface $last_connection_date): static
    {
        $this->last_connection_date = $last_connection_date;

        return $this;
    }

    public function getEmailVerificationDate(): ?\DateTimeInterface
    {
        return $this->email_verification_date;
    }

    public function setEmailVerificationDate(\DateTimeInterface $email_verification_date): static
    {
        $this->email_verification_date = $email_verification_date;

        return $this;
    }

    /**
     * @return Collection<int, Channel>
     */
    public function getOwnChannels(): Collection
    {
        return $this->own_channels;
    }

    public function addOwnChannel(Channel $ownChannel): static
    {
        if (!$this->own_channels->contains($ownChannel)) {
            $this->own_channels->add($ownChannel);
            $ownChannel->setOwner($this);
        }

        return $this;
    }

    public function removeOwnChannel(Channel $ownChannel): static
    {
        if ($this->own_channels->removeElement($ownChannel)) {
            // set the owning side to null (unless already changed)
            if ($ownChannel->getOwner() === $this) {
                $ownChannel->setOwner(null);
            }
        }

        return $this;
    }
}
