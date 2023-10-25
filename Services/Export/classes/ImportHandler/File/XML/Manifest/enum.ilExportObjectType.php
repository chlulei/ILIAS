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

namespace ImportHandler\File\XML\Manifest;

enum ilExportObjectType
{
    case MIXED;
    case EXPORT_SET;
    case EXPORT_FILE;
    case NONE;

    public static function toString(ilExportObjectType $type): string
    {
        return match ($type) {
            ilExportObjectType::EXPORT_FILE => 'ExportFile',
            ilExportObjectType::EXPORT_SET => 'ExportSet',
            ilExportObjectType::NONE => ''
        };
    }
}
