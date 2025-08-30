<?php

declare(strict_types=1);

namespace App\Types;

use App\Enums\CaseCollection;

enum Color
{
    use CaseCollection;

    case DARK;
    case DARKER;
    case LIGHT;
    case LIGHTER;
    case RED;
    case GREEN;
    case PINK;
    case ORANGE;
    case LIGHTRED;
    case LIGHTGRAY;
    case GRAY;
    case BLACK;
    case RED_BRIGHTER;
    case GREEN_BRIGHTER;

    const Color DEFAULT = self::BLACK;

    public static function variableDefinitions(): string
    {
        $list = self::caseCollection()
            ->map(fn (self $case) => '--' . $case->label() . ': ' . $case->color() . ';')
            ->join('');

        return ':root {' . $list . '}';
    }

    public function label(): string
    {
        return match ($this) {
            self::DARK => 'dark',
            self::DARKER => 'darker',
            self::LIGHT => 'light',
            self::LIGHTER => 'lighter',
            self::RED => 'red',
            self::GREEN => 'green',
            self::PINK => 'pink',
            self::ORANGE => 'orange',
            self::LIGHTRED => 'lightred',
            self::LIGHTGRAY => 'lightgray',
            self::GRAY => 'gray',
            self::BLACK => 'black',
            self::RED_BRIGHTER => 'red-brighter',
            self::GREEN_BRIGHTER => 'green-brighter',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DARK => '#19376D',
            self::DARKER => '#0B2447',
            self::LIGHT => '#576CBC',
            self::LIGHTER => '#A5D7E8',
            self::RED => '#ff000075',
            self::GREEN => '#4cc34caf',
            self::PINK => '#ff00ff',
            self::ORANGE => '#ffc864',
            self::LIGHTRED => 'rgb(250, 100, 100)',
            self::LIGHTGRAY => 'rgb(180, 180, 180)',
            self::GRAY => 'rgb(81, 81, 81)',
            self::BLACK => 'black',
            self::RED_BRIGHTER => 'red',
            self::GREEN_BRIGHTER => '#24ea24',
        };
    }

    public static function classDefinitions(): string
    {
        $textColor = self::caseCollection()
            ->map(fn (self $case) => '.' . $case->label() . '{ color: ' . $case->color() . '; }')
            ->join('');

        $backgroundColor = self::caseCollection()
            ->map(fn (self $case) => '.' . $case->bgLabel() . '{ background-color: ' . $case->color() . '; }')
            ->join('');

        return $textColor . $backgroundColor;
    }

    public function bgLabel(): string
    {
        return $this->label() . '-bg';
    }

    public static function randomForChart(): string
    {
        return '#' . collect([
            'FCECC9',
            'FCB0B3',
            'F93943',
            '7EB2DD',
            '445E93',
            'A20021',
            'F52F57',
            'F79D5C',
            'F3752B',
            'DDFFF7',
            '93E1D8',
            'FFA69E',
            'AA4465',
            '861657',
            'CACAAA',
            'EEC584',
            'C8AB83',
            '55868C',
            '7F636E',
        ])->random();
    }

    public static function random(): Color
    {
        return self::caseCollection()->random();
    }

    public function var(): string
    {
        return 'var(--' . $this->label() . ')';
    }
}
