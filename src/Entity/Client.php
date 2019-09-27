<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class Client
 *
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @ORM\Table(name="clients",
 *     uniqueConstraints={@UniqueConstraint(name="client_safe_name", columns={"client_safe_name"})},
 *     indexes={@Index(name="client_id", columns={"client_id"})}
 * )
 *
 * @package App\Entity
 */
class Client extends Entity
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
     * @ORM\Column(type="string", name="client_safe_name", unique=true)
     */
    protected $safe_name;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime", name="client_created")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(type="datetime", name="client_updated", nullable=true)
     */
    private $updated;

    /**
     * @var \DateTime $deleted
     *
     * @ORM\Column(type="datetime", name="client_deleted", nullable=true)
     */
    private $deleted;

    /**
     * @return int
     */
    public function getId (): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId (int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName (): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName (string $name): void
    {
        $this->name = $name;
        $this->setUpdated();
    }

    /**
     * @return string
     */
    public function getSafeName (): string
    {
        return $this->safe_name;
    }

    /**
     * @param string $safe_name
     */
    public function setSafeName (string $safe_name): void
    {
        $this->safe_name = $safe_name;
        $this->setUpdated();
    }

    /**
     * @return string
     */
    public function getCreated (): string
    {
        return $this->created->format(parent::DATE_FORMAT);
    }

    /**
     * @param string $created
     */
    public function setCreated (string $created = "now"): void
    {
        try {
            $this->created = new \DateTime($created);
            $this->setUpdated();
        } catch (\Exception $e) {
            // Silence
        }
    }

    /**
     * @return string|null
     */
    public function getUpdated (): ?string
    {
        if (!empty($this->updated)) {
            return $this->updated->format(parent::DATE_FORMAT);
        } else {
            return null;
        }
    }

    /**
     * @param string $updated
     */
    public function setUpdated (string $updated = "now"): void
    {
        try {
            $this->updated = new \DateTime($updated);
        } catch (\Exception $e) {
            // Silence
        }

    }

    /**
     * @return string|null
     */
    public function getDeleted (): ?string
    {
        if (!empty($this->deleted)) {
            return $this->deleted->format(parent::DATE_FORMAT);
        } else {
            return null;
        }
    }

    /**
     * @param string $deleted
     */
    public function setDeleted (string $deleted = "now"): void
    {
        try {
            $this->deleted = new \DateTime($deleted);
            $this->setUpdated();
        } catch (\Exception $e) {
            // Silence
        }
    }

    /**
     * generateSafeName()
     *
     * Takes the passed string and creates a safe name
     *
     * @param string $name
     */
    public function generateSafeName (string $name) {
        $name = preg_replace('/[^\da-z ]/i', '', $name);
        $name = preg_replace('/ /i', '-', $name);
        $name = strtolower($name);
        $this->safe_name = $name;
        $this->setUpdated();
    }

    /**
     * factory()
     *
     * Takes an array of values and generates
     *
     * @param array $data
     *
     * @return bool
     */
    public function factory (array $data = array())
    {
        $requiredKeys = array(
            "client_name",
        );
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data) || empty($data[$key])) {
                return false;
            }
        }

        $this->setName(trim($data['client_name']));
        $this->generateSafeName($this->getName());
        $this->setCreated(date(parent::DATE_FORMAT));

        return true;
    }
}