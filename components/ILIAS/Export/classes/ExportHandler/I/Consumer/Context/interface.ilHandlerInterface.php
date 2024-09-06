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

namespace ILIAS\Export\ExportHandler\I\Consumer\Context;

use ilAccessHandler;
use ilCtrlInterface;
use ilDBInterface;
use ilExportGUI;
use ILIAS\Export\ExportHandler\I\Consumer\File\ilFactoryInterface as ilExportHandlerConsumerFileFactoryInterface;
use ILIAS\Export\ExportHandler\I\PublicAccess\ilHandlerInterface as ilExportHandlerPublicAccessInterface;
use ILIAS\ResourceStorage\Services as ResourcesStorageService;
use ilLanguage;
use ilObject;
use ilObjUser;

interface ilHandlerInterface
{
    public function ilCtrl(): ilCtrlInterface;

    public function ilLng(): ilLanguage;

    public function ilDB(): ilDBInterface;

    public function irss(): ResourcesStorageService;

    public function user(): ilObjUser;

    public function exportGUIObject(): ilExportGUI;

    public function exportObject(): ilObject;

    public function fileFactory(): ilExportHandlerConsumerFileFactoryInterface;

    public function ilAccess(): ilAccessHandler;
}
