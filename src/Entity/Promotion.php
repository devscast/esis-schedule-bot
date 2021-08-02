<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PromotionRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $code = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $link = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $file = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updated_at = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_at = null;

    /**
     * Promotion constructor.
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
    }

    /**
     * @return int|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param string|null $file
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @param DateTimeInterface|null $updated_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setUpdatedAt(?DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeInterface $created_at
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
