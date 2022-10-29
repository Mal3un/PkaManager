<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StatusPostEnum extends Enum
{
    public const notPublic =   0;
    public const Public =   1;
//    public const Deleted = 3;
}
