<?php



namespace App\Enums\PosTerminal;


enum PosTerminalBrandEnum:string {

    case NewLand = 'NewLand';

    public static function getList(): array
    {
        return [
            self::NewLand,
        ];
    }
}
