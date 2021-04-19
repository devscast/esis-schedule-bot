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

    /**
     * @return DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getRequestedAt(): ?DateTimeInterface
    {
        return $this->requested_at;
    }

    /**
     * @param DateTimeInterface $requested_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setRequestedAt(DateTimeInterface $requested_at): self
    {
        $this->requested_at = $requested_at;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getRespondedAt(): ?DateTimeInterface
    {
        return $this->responded_at;
    }

    /**
     * @param DateTimeInterface|null $responded_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setRespondedAt(?DateTimeInterface $responded_at): self
    {
        $this->responded_at = $responded_at;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getPayload(): ?string
    {
        return $this->payload;
    }

    /**
     * @param string|null $payload
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setPayload(?string $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return int|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getResponseCode(): ?int
    {
        return $this->response_code;
    }

    /**
     * @param int|null $response_code
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setResponseCode(?int $response_code): self
    {
        $this->response_code = $response_code;

        return $this;
    }
}
