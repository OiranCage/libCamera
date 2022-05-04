<?php

namespace oirancage\libcamera;

use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityLink;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\player\Player;

class Camera extends Entity
{
    protected $gravityEnabled = false;

    public function __construct(
        Location $location,
        private CameraSetting $cameraSetting
    ){
        parent::__construct($location);
    }

    public function watchBy(Player $player){
        $this->link($player);
    }

    public function unwatchBy(Player $player){
        $this->unlink($player);
    }

    private function link(Player $player): void{
        $properties = $player->getNetworkProperties();
        $properties->setVector3(EntityMetadataProperties::RIDER_SEAT_POSITION, new Vector3(0,0,0));
        $properties->setByte(EntityMetadataProperties::RIDER_ROTATION_LOCKED, ($this->cameraSetting->isCameraAngleLimited) ? 1 : 0);
        $properties->setFloat(EntityMetadataProperties::RIDER_SEAT_ROTATION_OFFSET, 0);
        $properties->setFloat(EntityMetadataProperties::RIDER_MIN_ROTATION, -$this->cameraSetting->cameraHalfAngle);
        $properties->setFloat(EntityMetadataProperties::RIDER_MAX_ROTATION, $this->cameraSetting->cameraHalfAngle);
        $pk = SetActorLinkPacket::create(new EntityLink(
            $this->getId(),
            $player->getId(),
            EntityLink::TYPE_PASSENGER,
            false,
            false
        ));
        $player->getNetworkSession()->sendDataPacket($pk);
    }

    private function unlink(Player $player){
        $pk = SetActorLinkPacket::create(new EntityLink(
            $this->getId(),
            $player->getId(),
            EntityLink::TYPE_REMOVE,
            false,
            false
        ));
        $player->getNetworkSession()->sendDataPacket($pk);
    }



    protected function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(0.0, 0.0);
    }

    public static function getNetworkTypeId(): string
    {
        return "libcamera:camera";
    }
}