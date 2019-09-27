<?php

namespace App\Entity;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Entity
{
    /**
     * @const string
     */
    const DATE_FORMAT = "Y-m-d H:i:s";

    /**
     * jsonSerialize()
     */
    public function jsonSerialize ()
    {
        // Set up the serializer
        $encoders = array(new JsonEncoder());
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($this, 'json');
    }
}