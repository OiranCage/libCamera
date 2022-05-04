<?php

namespace oirancage\libcamera;

use pocketmine\entity\Location;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use SplObjectStorage;

class libCamera{

    use SingletonTrait;

    /** @var SplObjectStorage<int, Camera> */
    private SplObjectStorage $cameras;

    public function register(PluginBase $plugin){
        $plugin->getServer()->getPluginManager()->registerEvents(
            new EventHandler(),
            $plugin
        );
    }

    public function createCamera(Location $location, CameraSetting $cameraSetting): Camera{
        $camera = new Camera($location, $cameraSetting);
        $this->cameras->attach($camera);
        return $camera;
    }

    public function destroyCamera(Camera $camera): void{
        $this->cameras->detach($camera);
        $camera->flagForDespawn();
    }

    public function getCameras(): array{
        return $this->cameras;
    }

    public function getCamera(int $id): ?Camera{
        foreach ($this->cameras as $camera){
            /** @var Camera $camera */
            if($camera->getId() === $id){
                return $camera;
            }
        }
        return null;
    }
}