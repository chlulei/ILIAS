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

namespace ILIAS\Export\ExportHandler\PublicAccess;

use ilDBInterface;
use ILIAS\Export\ExportHandler\I\ilFactoryInterface as ilExportHandlerFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\ilFactoryInterface as ilExportHandlerPublicAccessFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\ilHandlerInterface as ilExportHandlerPublicAccessInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Link\ilFactoryInterface as ilExportHandlerPublicAccessLinkFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\Repository\ilFactoryInterface as ilExportHandlerPublicAccessRepositoryFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\TypeRestriction\ilFactoryInterface as ilExportHandlerPublicAccessTypeRestrictionFactoryInterface;
use ILIAS\Export\ExportHandler\PublicAccess\ilHandler as ilExportHandlerPublicAccess;
use ILIAS\Export\ExportHandler\PublicAccess\Link\ilFactory as ilExportHandlerPublicAccessLinkFactory;
use ILIAS\Export\ExportHandler\PublicAccess\Repository\ilFactory as ilExportHandlerPublicAccessRepositoryFactory;
use ILIAS\Export\ExportHandler\PublicAccess\TypeRestriction\ilFactory as ilExportHandlerPublicAccessTypeRestrictionFactory;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;
use ILIAS\StaticURL\Services as StaticUrl;

class ilFactory implements ilExportHandlerPublicAccessFactoryInterface
{
    protected ilExportHandlerFactoryInterface $export_handler;
    protected ResourcesStorageService $irss;
    protected ilDBInterface $db;
    protected StaticURL $static_url;

    public function __construct(
        ilExportHandlerFactoryInterface $export_handler,
        ResourcesStorageService $irss,
        ilDBInterface $db,
        StaticURL $static_url
    ) {
        $this->export_handler = $export_handler;
        $this->irss = $irss;
        $this->db = $db;
        $this->static_url = $static_url;
    }

    public function handler(): ilExportHandlerPublicAccessInterface
    {
        return new ilExportHandlerPublicAccess($this->export_handler);
    }

    public function link(): ilExportHandlerPublicAccessLinkFactoryInterface
    {
        return new ilExportHandlerPublicAccessLinkFactory($this->export_handler, $this->static_url);
    }

    public function repository(): ilExportHandlerPublicAccessRepositoryFactoryInterface
    {
        return new ilExportHandlerPublicAccessRepositoryFactory($this->export_handler, $this->irss, $this->db);
    }

    public function typeRestriction(): ilExportHandlerPublicAccessTypeRestrictionFactoryInterface
    {
        return new ilExportHandlerPublicAccessTypeRestrictionFactory($this->export_handler, $this->db);
    }
}
