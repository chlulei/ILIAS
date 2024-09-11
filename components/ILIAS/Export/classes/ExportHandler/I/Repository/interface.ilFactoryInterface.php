<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

namespace ILIAS\Export\ExportHandler\I\Repository;

use ILIAS\Export\ExportHandler\I\Repository\Element\ilFactoryInterface as ilExportHandlerRepositoryElementFactoryInterface;
use ILIAS\Export\ExportHandler\I\Repository\ilHandlerInterface as ilExportHandlerRepositoryInterface;
use ILIAS\Export\ExportHandler\I\Repository\Key\ilFactoryInterface as ilExportHandlerRepositoryKeyFactoryInterface;
use ILIAS\Export\ExportHandler\I\Repository\Wrapper\ilFactoryInterface as ilExportHandlerRepositoryWrapperFactoryInterface;
use ILIAS\Export\ExportHandler\Repository\ilResourceStakeholder as ilExportHandlerRepositoryResourceStakeholder;

interface ilFactoryInterface
{
    public function handler(): ilExportHandlerRepositoryInterface;

    public function element(): ilExportHandlerRepositoryElementFactoryInterface;

    public function stakeholder(): ilExportHandlerRepositoryResourceStakeholder;

    public function key(): ilExportHandlerRepositoryKeyFactoryInterface;

    public function wrapper(): ilExportHandlerRepositoryWrapperFactoryInterface;
}
