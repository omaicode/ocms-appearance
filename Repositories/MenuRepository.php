<?php

namespace Modules\Appearance\Repositories;

use Illuminate\Support\Collection;
use Omaicode\Repository\Contracts\RepositoryInterface;

/**
 * Interface MenuRepository.
 *
 * @package namespace Modules\Appearance\Repositories;
 */
interface MenuRepository extends RepositoryInterface
{
    public function getAllWithChilds($position): Collection;
    public function getRootMenus($position): Collection;
}
