<?php

namespace ApiPlatform\Core\DataPersister;

use App\Entity\Place;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class PlacePersister implements DataPersisterInterface
{
    public function supports($data) : bool
    {
        return $data instanceof Place;
    }

    public function persist($data)
    {
        
    }

    public function remove($data)
    {

    }
}