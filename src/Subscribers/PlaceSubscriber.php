<?php

namespace App\subscribers;

use App\Entity\Place;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;


class PlaceSubscraber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security){
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUser']
        ];
    }

    public function setUser(BeforeEntityPersistedEvent $event){

        $entity = $event->getEntityInstance();

        if($entity instanceof Place){
            $entity->setUser($this->security->getUser());
        };

        dd($event);
    }
}