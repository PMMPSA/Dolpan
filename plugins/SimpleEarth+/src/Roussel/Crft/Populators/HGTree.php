<?php

namespace Roussel\Crft\Populators;

use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\Tree as ObjectTree;
use pocketmine\utils\Random;
use pocketmine\level\generator\populator\Populator;

class HGTree extends Populator {
	/**
	 * @var ChunkManager
	 */
	private $level;
	private $randomAmount;
	private $baseAmount;
	public function setRandomAmount($amount) {
		$this->randomAmount = $amount;
	}
	public function setBaseAmount($amount) {
		$this->baseAmount = $amount;
	}
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $random->nextRange ( 0, $this->randomAmount + 1 ) + $this->baseAmount;
		for($i = 0; $i < $amount; ++ $i) {
			$x = $random->nextRange ( $chunkX << 4, ($chunkX << 4) + 15 );
			$z = $random->nextRange ( $chunkZ << 4, ($chunkZ << 4) + 15 );
			$y = $this->getHighestWorkableBlock ( $x, $z );
			if ($y === - 1) {
				continue;
			}
			$fi = $random->nextFloat();
			if ($fi > 0.4 && $fi < 0.5) {
				$meta = Sapling::SPRUCE;
			} elseif ($fi > 0.5 && $fi < 0.75) {
				$meta = Sapling::BIRCH;
			} elseif ($fi > 0.75) {
				$meta = Sapling::JUNGLE;
			} else {
				$meta = Sapling::OAK;
			}
			ObjectTree::growTree ( $this->level, $x, $y, $z, $random, $meta );
		}
	}
	private function getHighestWorkableBlock($x, $z) {
		for($y = 128; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt ( $x, $y, $z );
			if ($b !== Block::DIRT and $b !== Block::GRASS) {
				if (-- $y <= 0) {
					return - 1;
				}
			} else {
				break;
			}
		}
		
		return ++ $y;
	}
}
