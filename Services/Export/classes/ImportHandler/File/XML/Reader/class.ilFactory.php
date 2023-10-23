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

namespace ImportHandler\File\XML\Reader;

use ilLogger;
use ImportHandler\I\File\XML\Reader\ilFactoryInterface as ilXMLFileReaderFactoryInterface;
use ImportHandler\I\File\XML\Reader\ilHandlerInterface as ilXMLFileReaderHandlerInterface;
use ImportHandler\File\XML\Reader\ilHandler as ilXMLFileReaderHandler;
use ImportHandler\I\File\XML\Reader\Path\ilFactoryInterface as ilXMLFileReaderPathFactoryInterface;
use ImportHandler\File\XML\Reader\Path\ilFactory as ilXMLFileReaderPathFactory;
use ImportStatus\ilFactory as ilImportStatusFactory;

class ilFactory implements ilXMLFileReaderFactoryInterface
{
    protected ilLogger $logger;

    public function __construct(
        ilLogger $logger
    ) {
        $this->logger = $logger;
    }

    public function handler(): ilXMLFileReaderHandlerInterface
    {
        return new ilXMLFileReaderHandler(
            $this->logger,
            new ilImportStatusFactory(),
            new ilXMLFileReaderPathFactory()
        );
    }

    public function path(): ilXMLFileReaderPathFactoryInterface
    {
        return new ilXMLFileReaderPathFactory();
    }
}
