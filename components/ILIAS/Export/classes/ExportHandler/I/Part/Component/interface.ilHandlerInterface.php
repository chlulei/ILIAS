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

namespace ILIAS\Export\ExportHandler\I\Part\Component;

use ILIAS\Export\ExportHandler\I\Info\Export\Component\ilHandlerInterface as ilExportHanlderExportComponentInfoInterface;
use ILIAS\Export\ExportHandler\I\Info\Export\ilHandlerInterface as ilExportHanlderExportInfoInterface;
use ILIAS\Export\ExportHandler\I\Part\Component\ilHandlerInterface as ilExportHandlerPartComponentInterface;
use ILIAS\Export\ExportHandler\I\Part\ilHandlerInterface as ilExportHandlerPartInterface;

interface ilHandlerInterface extends ilExportHandlerPartInterface
{
    public function getXML(bool $formatted = true): string;

    public function withExportInfo(ilExportHanlderExportInfoInterface $export_info): ilExportHandlerPartComponentInterface;

    public function withComponentInfo(ilExportHanlderExportComponentInfoInterface $component_info): ilExportHandlerPartComponentInterface;

}
