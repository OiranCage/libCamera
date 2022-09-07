<?php

namespace oirancage\libcamera;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InteractPacket;

class EventHandler implements Listener{

    public function onDataPacketReceive(DataPacketReceiveEvent $event): void{
        $pk = $event->getPacket();
        if($pk instanceof InteractPacket && $pk->action === InteractPacket::ACTION_LEAVE_VEHICLE){
            libCamera::getInstance()
                ->getCamera($pk->targetActorRuntimeId)
                ?->watchBy($event->getOrigin()->getPlayer());
        }
    }
}