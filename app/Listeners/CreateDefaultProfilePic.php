<?php

namespace App\Listeners;

use App\Events\UserEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateDefaultProfilePic {
    const TARGET_CONTRAST = 4.5;

    private static function srgbToSpaceless(float $component) {
        return $component <= 0.03928 ? $component / 12.92 : (($component + 0.055) / 1.055) ** 2.4;
    }

    private static function spacelessToSRGB(float $component) {
        return $component * 12.92 <= 0.03928 ? $component * 12.92 : (($component ** (1 / 2.4)) * 1.055) - 0.055;
    }

    private static function hsvToSRGB(float $h, float $s, float $v) {
        $sector = $h / 60;
        $c = $s * $v;
        $x = $c * (1 - abs($sector % 2 - 1));

        if ($sector < 1)
            return [$c, $x, 0];
        if ($sector < 2)
            return [$x, $c, 0];
        if ($sector < 3)
            return [0, $c, $x];
        if ($sector < 4)
            return [0, $x, $c];
        if ($sector < 5)
            return [$x, 0, $c];

        return [$c, 0, $x];

    }

    # Based on the formulas at
    # https://www.w3.org/TR/WCAG20/#relativeluminancedef and
    # https://www.w3.org/TR/WCAG20/#contrast-ratiodef
    private static function generateColor(int $seed) {
        mt_srand($seed);

        $RAND_MAX = mt_getrandmax();

        $h = ((float) mt_rand()) / $RAND_MAX * 360;

        $contrast = -1;
        while ($contrast < static::TARGET_CONTRAST) {
            $s = ((float) mt_rand()) / $RAND_MAX * .4 + .6;
            $v = ((float) mt_rand()) / $RAND_MAX * .4 + .6;

            list($r_srgb, $g_srgb, $b_srgb) = static::hsvToSRGB($h, $s, $v);

            $r = static::srgbToSpaceless($r_srgb);
            $g = static::srgbToSpaceless($g_srgb);
            $b = static::srgbToSpaceless($b_srgb);

            $y = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;

            $contrast = 1.05 / ($y + 0.05);
        }

        Log::debug('COLOR: ', [$h, $s, $v, $r_srgb, $g_srgb, $b_srgb]);

        return sprintf("#%02x%02x%02x", $r_srgb * 255, $g_srgb * 255, $b_srgb * 255);
    }

    public function handle(UserEvent $event) {
        Storage::put(
            "public/users/default_{$event->user->id}.svg",
                view('other.pfp', [
                    'background' => static::generateColor($event->user->id),
                    'text' => $event->user->name[0]
                ])->render());
    }
}