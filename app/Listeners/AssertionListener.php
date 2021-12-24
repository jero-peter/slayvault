<?php

namespace App\Listeners;

use LightSaml\ClaimTypes;
use LightSaml\Model\Assertion\Attribute;
use CodeGreenCreative\SamlIdp\Events\Assertion;

class AssertionListener
{
    public function handle(Assertion $event)
    {
        $event->attribute_statement
            ->addAttribute(new Attribute(ClaimTypes::PPID, auth()->user()->id))
            ->addAttribute(new Attribute(ClaimTypes::NAME, auth()->user()->name))
            ->addAttribute(new Attribute(ClaimTypes::ROLE , auth()->user()->user_type))
            ->addAttribute(new Attribute(ClaimTypes::GROUP , auth()->user()->user_group));
    }
}