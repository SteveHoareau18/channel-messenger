<?php

namespace App\Entity;

use App\Repository\ChannelUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChannelUserRepository::class)]
class ChannelUser
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Channel $channel = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $join_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Channel|null
     */
    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    /**
     * @param Channel|null $channel
     */
    public function setChannel(?Channel $channel): void
    {
        $this->channel = $channel;
    }



    public function getJoinDate(): ?\DateTimeInterface
    {
        return $this->join_date;
    }

    public function setJoinDate(?\DateTimeInterface $join_date): static
    {
        $this->join_date = $join_date;

        return $this;
    }
}
