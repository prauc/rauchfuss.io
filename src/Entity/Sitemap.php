<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SitemapRepository")
 */
class Sitemap
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $loc;

    /**
     * @ORM\Column(type="date")
     */
    private $lastmod;

    public function getLoc(): ?string
    {
        return $this->loc;
    }

    public function setLoc(string $loc): self
    {
        $this->loc = $loc;

        return $this;
    }

    public function getLastmod(): ?string
    {
        return date_format($this->lastmod, 'Y-m-d');
    }

    public function setLastmod(\DateTimeInterface $lastmod): self
    {
        $this->lastmod = $lastmod;

        return $this;
    }
}
