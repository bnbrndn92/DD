<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class Frontend
 *
 * @ORM\Entity(repositoryClass="App\Repository\FrontendRepository")
 * @ORM\Table(name="frontends",
 *     uniqueConstraints={@UniqueConstraint(name="frontend_name", columns={"frontend_name"})},
 *     indexes={@Index(name="frontend_id", columns={"frontend_id"})}
 * )
 *
 * @package App\Entity
 */
class Frontend extends Entity
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="frontend_id", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="frontend_name")
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="frontend_client_id")
     */
    protected $client_id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="frontend_service_id")
     */
    protected $service_id;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime", name="frontend_created")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(type="datetime", name="frontend_updated", nullable=true)
     */
    private $updated;

    /**
     * @var \DateTime $deleted
     *
     * @ORM\Column(type="datetime", name="frontend_deleted", nullable=true)
     */
    private $deleted;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return int|null
     */
    public function getClientId (): ?int
    {
        return $this->client_id;
    }

    /**
     * @param int $client_id
     */
    public function setClientId (int $client_id): void
    {
        $this->client_id = $client_id;
        $this->setUpdated();
    }

    /**
     * @return int|null
     */
    public function getServiceId (): ?int
    {
        return $this->service_id;
    }

    /**
     * @param int $service_id
     */
    public function setServiceId (int $service_id): void
    {
        $this->service_id = $service_id;
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
            "frontend_name",
            "client_id",
            "service_id",
        );
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data) || empty($data[$key])) {
                return false;
            }
        }

        $this->setName(trim($data['frontend_name']));
        $this->setCreated(date(parent::DATE_FORMAT));
        $this->setClientId($data['client_id']);
        $this->setServiceId($data['service_id']);

        return true;
    }
}
