<?php

declare(strict_types=1);

namespace App\Model;

class Notification
{
    private string $title;
    private string $description;
    private string $cta;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        
        return $this;
    }

    public function getCta(): string
    {
        return $this->cta;
    }

    public function setCta(string $cta): self
    {
        $this->cta = $cta;
        
        return $this;
    }
}