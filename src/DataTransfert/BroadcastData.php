<?php

declare(strict_types=1);

namespace App\DataTransfert;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BroadcastData
 * @package App\DataTransfert
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BroadcastData
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="5")
     */
    public string $message;

    public bool $all = false;
    public string $promotion;

    /**
     * @Assert\File(maxSize="5M", mimeTypes={"application/pdf", "application/x-pdf"})
     */
    public ?File $file = null;
    public ?string $attachement = null;
}
