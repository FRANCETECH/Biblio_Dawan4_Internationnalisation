<?php

namespace App\Form;


use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\SluggerInterface;



class FormListenerFactory
{

    public function __construct(private SluggerInterface $slugger)
    {

    }

    public function autoslug(string $field): callable
    {

        return function (PreSubmitEvent $event) use ($field) {
            // On recupère les données soumises dans le formulaire
            $data = $event->getData();
            if (empty($data['slug'])) {
                $data['slug'] = strtolower($this->slugger->slug($data[$field]));
                $event->setData($data);
           }

        };


    }

    public function timestamps(): callable
    {
        return function (PostSubmitEvent $event) {

            $data = $event->getData();
        
            $data->setUpdatedAt(new \DateTimeImmutable());
 
            if (!$data->getId()) {
                $data->setCreatedAt(new \DateTimeImmutable());
            }

        };

    }



}