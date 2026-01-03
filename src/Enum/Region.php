<?php
declare(strict_types=1);

namespace Znojil\Heureka\Enum;

enum Region: string{

	case Cz = 'cz';

	case Sk = 'sk';

	public function label(): string{
		return match($this){
			static::Cz => 'Heureka.cz',
			static::Sk => 'Heureka.sk'
		};
	}

	public function baseUrl(): string{
		return 'https://www.heureka.' . $this->value;
	}

	public function apiUrl(): string{
		return 'https://api.heureka.' . $this->value;
	}

}
