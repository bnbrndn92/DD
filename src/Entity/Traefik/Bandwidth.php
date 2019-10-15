<?php

namespace App\Entity\Traefik;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class Bandwidth
 *
 * @ORM\Entity(repositoryClass="App\Repository\Traefik\BandwidthRepository")
 * @ORM\Table(name="bw",
 *     indexes={@Index(name="id", columns={"id"})}
 * )
 *
 * @package App\Entity\Traefik
 */
class Bandwidth extends Entity
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="frontend")
     */
    protected $frontend;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="hostname")
     */
    protected $hostname;

    /**
     * @var string $created
     *
     * @ORM\Column(type="string", name="created")
     */
    private $created;

    /**
     * @var int
     *
     * @ORM\Column(type="int", name="count")
     */

    private $count;

    /**
     * getId()
     *
     * @return int|null
     */
    public function getId () : ?int
    {
        return $this->id;
    }

    /**
     * setId()
     *
     * @param int $id
     */
    public function setId (int $id)
    {
        $this->id = $id;
    }

    /**
     * getFrontend()
     *
     * @return string|null
     */
    public function getFrontend () : ?string
    {
        return $this->frontend;
    }

    /**
     * setFrontend()
     *
     * @param string $frontend
     */
    public function setFrontend (string $frontend)
    {
        $this->frontend = $frontend;
    }

    /**
     * getHostname()
     *
     * @return string|null
     */
    public function getHostname () : ?string
    {
        return $this->hostname;
    }

    /**
     * setHostname()
     *
     * @param string $hostname
     */
    public function setHostname (string $hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * getCreated()
     *
     * @return string|null
     */
    public function getCreated () : ?string
    {
        return $this->created;
    }

    /**
     * setCreated()
     *
     * @param string $created
     */
    public function setCreated (string $created)
    {
        $this->created = $created;
    }

    /**
     * getCount()
     *
     * @return int|null
     */
    public function getCount () : ?int
    {
        return $this->count;
    }

    /**
     * setCount()
     *
     * @param int $count
     */
    public function setCount (int $count)
    {
        $this->count = $count;
    }
}