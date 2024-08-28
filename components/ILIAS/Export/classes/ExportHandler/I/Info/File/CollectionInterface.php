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

namespace ILIAS\Export\ExportHandler\I\Info\File;

use Countable;
use ILIAS\Export\ExportHandler\I\Info\File\HandlerInterface as ilExportHandlerFileInfoInterface;
use Iterator;

interface CollectionInterface extends Countable, Iterator
{
    public function withFileInfo(ilExportHandlerFileInfoInterface $file_info): CollectionInterface;

    public function elementAt(int $index): ?ilExportHandlerFileInfoInterface;

    public function mergeWith(CollectionInterface $other): CollectionInterface;

    public function current(): ilExportHandlerFileInfoInterface;

    public function key(): int;

    public function next(): void;

    public function rewind(): void;

    public function valid(): bool;

    public function count(): int;
}
