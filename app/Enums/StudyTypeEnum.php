<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StudyTypeEnum extends Enum
{
    public const LT =   0;
    public const TH =   1;
    public const LT_TH = 2;
}
