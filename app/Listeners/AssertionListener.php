<?php

namespace App\Listeners;

use LightSaml\ClaimTypes;
use LightSaml\Model\Assertion\Attribute;
use CodeGreenCreative\SamlIdp\Events\Assertion;

class AssertionListener
{
    public function handle(Assertion $event)
    {   
        $subList = array();
        
        if(auth()->user() == null){
            $config_auth = auth('secondary_admin')->user();
        }else{
            $config_auth = auth()->user();
        }


        if($config_auth->user_type == 'admin'){
            foreach($config_auth->subscription_list as $subId){
                array_push($subList, json_encode(config('app_data.apps')[$subId]));
            }
        }else{
            $subIdCollection = $config_auth->owner->subscription_list;
            foreach($subIdCollection as $subId){
                array_push($subList, json_encode(config('app_data.apps')[$subId]));
            }
        }

        $event->attribute_statement
            ->addAttribute(new Attribute('Subscription', $subList))
            ->addAttribute(new Attribute('Company', $config_auth->company))
            ->addAttribute(new Attribute('C_UUID', $config_auth->c_uuid))
            ->addAttribute(new Attribute('SV_TOKEN', $config_auth->c_token))
            ->addAttribute(new Attribute(ClaimTypes::PPID, $config_auth->id))
            ->addAttribute(new Attribute(ClaimTypes::NAME, $config_auth->name))
            ->addAttribute(new Attribute(ClaimTypes::ROLE , $config_auth->user_type))
            ->addAttribute(new Attribute(ClaimTypes::GROUP , $config_auth->user_group));
    }
}