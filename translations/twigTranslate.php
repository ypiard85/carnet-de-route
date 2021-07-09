<?php

use Symfony\Contracts\Translation\TranslatorInterface;



class Twig_Extensions_Extension_Date extends Twig_Extension
{
    public static $units = array(
        'y' => 'ans',
        'm' => 'mois',
        'd' => 'jour',
        'h' => 'heure',
        'i' => 'minutes',
        's' => 'seconde',
    );

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator A TranslatorInterface instance.
     */
    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
    }

}