<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class NotificationRequestModel
{
    #[Assert\NotBlank(message: "user_id is required")]
    #[Assert\Type(type: "numeric", message: "user_id must be a number")]
    public mixed $user_id = null;

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}