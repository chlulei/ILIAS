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

namespace ImportHandler\File\XML;

use ilLogger;
use ImportHandler\File\XML\Manifest\ilFactory as ilManifestFileFactory;
use ImportHandler\I\File\XML\ilFactoryInterface as ilXMLFileFactoryInterface;
use ImportHandler\I\File\XML\ilHandlerCollectionInterface as ilXMLFileHanlderCollectionInterface;
use ImportHandler\I\File\XML\ilHandlerInterface as ilXMLFileHanlderInterface;
use ImportHandler\File\XML\ilHandler as ilXMLFileHanlder;
use ImportHandler\File\XML\ilHandlerCollection as ilXMLFileHanlderCollection;
use ImportHandler\I\File\XML\Manifest\ilFactoryInterface as ilManifestFileFactoryInterface;
use ImportHandler\I\File\ilFactoryInterface as ilFileFactoryInterface;
use ImportHandler\I\Parser\ilFactoryInterface as ilParserFactoryInterface;
use ImportStatus\ilFactory as ilStatusFactory;

class ilFactory implements ilXMLFileFactoryInterface
{
    protected ilLogger $logger;
    protected ilFileFactoryInterface $file;
    protected ilParserFactoryInterface $parser;

    public function __construct(
        ilFileFactoryInterface $file,
        ilParserFactoryInterface $parser,
        ilLogger $logger
    ) {
        $this->logger = $logger;
        $this->file = $file;
        $this->parser = $parser;
    }

    public function handler(): ilXMLFileHanlderInterface
    {
        return new ilXMLFileHanlder(
            new ilStatusFactory()
        );
    }

    public function handlerCollection(): ilXMLFileHanlderCollectionInterface
    {
        return new ilXMLFileHanlderCollection();
    }

    public function manifest(): ilManifestFileFactoryInterface
    {
        return new ilManifestFileFactory(
            $this->file,
            $this->parser,
            $this->logger
        );
    }
}
