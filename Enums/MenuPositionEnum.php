<?php

namespace Modules\Appearance\Enums;

use Omaicode\Enum\Enum;

/**
 * @method static static PRIMARY_MENU()
 * @method static static SECONDARY_MENU()
 * @method static static TOP_MENU()
 */
final class MenuPositionEnum extends Enum
{
    const PRIMARY_MENU     =   0;
    const SECONDARY_MENU   =   1;
    const TOP_MENU         =   2;
}
