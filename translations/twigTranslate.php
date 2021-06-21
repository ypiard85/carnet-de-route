<?php

use Symfony\Contracts\Translation\TranslatorInterface;



class Twig_Extensions_Extension_Date extends Twig_Extension
{
    public static $units = array(
        'y' => 'year',
        'm' => 'month',
        'd' => 'jours',
        'h' => 'hour',
        'i' => 'm',
        's' => 'second',
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