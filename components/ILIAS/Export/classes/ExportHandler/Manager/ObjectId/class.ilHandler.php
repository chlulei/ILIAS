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

namespace ILIAS\Export\ExportHandler\Manager\ObjectId;

use ILIAS\Data\ObjectId;
use ILIAS\Export\ExportHandler\I\Manager\ObjectId\ilHandlerInterface as ilExportHandlerManagerObjectIdInterface;

class ilHandler implements ilExportHandlerManagerObjectIdInterface
{
    protected ObjectId $object_id;
    protected bool $reuse_export;

    public function withObjectId(ObjectId $object_id): ilExportHandlerManagerObjectIdInterface
    {
        $clone = clone $this;
        $clone->object_id = $object_id;
        return $clone;
    }

    public function withReuseExport(bool $reuse_export): ilExportHandlerManagerObjectIdInterface
    {
        $clone = clone $this;
        $clone->reuse_export = $reuse_export;
        return $clone;
    }

    public function getObjectId(): ObjectId
    {
        return $this->object_id;
    }

    public function getReuseExport(): bool
    {
        return $this->reuse_export;
    }
}
