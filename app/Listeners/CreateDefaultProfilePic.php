<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Events\UserEvent;
use App\Events\UserUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Blade;
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

    # Based on the formulas at
    # https://www.w3.org/TR/WCAG20/#relativeluminancedef and
    # https://www.w3.org/TR/WCAG20/#contrast-ratiodef
    private static function generateColor(int $seed) {
        srand($seed);

        $b_8bit = -1;
        while ($b_8bit > 255 || $b_8bit < 0) {
            $r_8bit = rand(0, 255);
            $g_8bit = rand(0, 255);

            $r_srgb = $r_8bit / 255.0;
            $g_srgb = $g_8bit / 255.0;

            $r = static::srgbToSpaceless($r_srgb);
            $g = static::srgbToSpaceless($g_srgb);

            $b = -0.692521 + 14.5429 / static::TARGET_CONTRAST - 2.9446 * $r - 9.90582 * $g;

            $b_srgb = static::spacelessToSRGB($b);
            $b_8bit = round($b_srgb * 255);
        }

        return sprintf("#%02X%02X%02X", $r_8bit, $g_8bit, $b_8bit);
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