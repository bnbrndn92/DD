<?php

namespace App\Entity\Traefik;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class DatabaseUser
 *
 * @ORM\Entity(repositoryClass="App\Repository\Traefik\DatabaseUserRepository")
 * @ORM\Table(name="db",
 *     indexes={@Index(name="id", columns={"id"})}
 * )
 *
 * @package App\Entity\Traefik
 */
class DatabaseUser extends Entity
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
     * @ORM\Column(type="string", name="username")
     */
    protected $username;

    /**
     * @var string $time
     *
     * @ORM\Column(type="string", name="time")
     */
    private $time;

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
     * getUsername()
     *
     * @return string|null
     */
    public function getUsername () : ?string
    {
        return $this->username;
    }

    /**
     * setUsername()
     *
     * @param string $username
     */
    public function setUsername (string $username)
    {
        $this->username = $username;
    }

    /**
     * getTime()
     *
     * @return string|null
     */
    public function getTime () : ?string
    {
        return $this->time;
    }

    /**
     * setTime()
     *
     * @param string $time
     */
    public function setTime (string $time)
    {
        $this->time = $time;
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