<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RequestRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequestRepository::class)
 */
class Request
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $requested_at = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $responded_at = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $payload = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $method = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $response_code = 200;

    /**
     * @return int|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestedAt(): ?DateTimeInterface
    {
        return $this->requested_at;
    }

    public function setRequestedAt(DateTimeInterface $requested_at): self
    {
        $this->requested_at = $requested_at;

        return $this;
    }

    public function getRespondedAt(): ?DateTimeInterface
    {
        return $this->responded_at;
    }

    public function setRespondedAt(?DateTimeInterface $responded_at): self
    {
        $this->responded_at = $responded_at;

        return $this;
    }

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function setPayload(?string $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getResponseCode(): ?int
    {
        return $this->response_code;
    }

    public function setResponseCode(?int $response_code): self
    {
        $this->response_code = $response_code;

        return $this;
    }
}
