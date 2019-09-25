<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Client
 *
 * @ORM\Entity
 * @ORM\Table(name="clients")
 *
 * @package App\Entity
 */
class Client
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="client_id", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="client_name")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string", name="client_safe_name")
     */
    protected $safe_name;

    /**
     * @var string $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime" name="client_created")
     */
    private $created;

    /**
     * @var string $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime" name="client_updated" nullable="true")
     */
    private $updated;

    /**
     * @var string $deleted
     *
     * @ORM\Column(type="datetime" name="client_deleted" nullable="true")
     */
    private $deleted;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSafeName(): string
    {
        return $this->safe_name;
    }

    /**
     * @param string $safe_name
     */
    public function setSafeName(string $safe_name): void
    {
        $this->safe_name = $safe_name;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated(string $created): void
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     */
    public function setUpdated(string $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return string
     */
    public function getDeleted(): string
    {
        return $this->deleted;
    }

    /**
     * @param string $deleted
     */
    public function setDeleted(string $deleted): void
    {
        $this->deleted = $deleted;
    }
}