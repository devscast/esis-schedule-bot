<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use TelegramBot\Api\Types\Message;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
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
     * @ORM\Column(type="integer", unique=true)
     */
    private ?int $chat_id = null;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private ?string $promotion = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_at = null;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private ?bool $is_active = true;

    /**
     * Subscription constructor.
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
     * @param string|null $name
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getChatId(): ?int
    {
        return $this->chat_id;
    }

    /**
     * @param int $chat_id
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setChatId(int $chat_id): self
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    /**
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getPromotion(): ?string
    {
        return $this->promotion;
    }

    /**
     * @param string $promotion
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setPromotion(string $promotion): self
    {
        $this->promotion = $promotion;

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

    /**
     * @return bool|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    /**
     * @return bool|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    /**
     * @param bool $is_active
     * @return $this
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @param Message $message
     * @param string $promotion
     * @return self
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function fromMessageCommand(Message $message, string $promotion): self
    {
        return (new Subscription())
            ->setName($message->getChat()->getUsername() ?: '')
            ->setPromotion($promotion)
            ->setChatId($message->getChat()->getId())
            ->setIsActive(true);
    }
}
