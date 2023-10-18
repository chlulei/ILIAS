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

namespace ImportHandler\Parser\Path;

use ImportHandler\I\Parser\Path\ilComparisonInterface as ilParserPathComparisonInterface;
use ImportHandler\I\Parser\Path\ilFactoryInterface as ilParserPathFactory;
use ImportHandler\I\Parser\Path\ilHandlerInterface as ilParserPathHandlerInterface;
use ImportHandler\I\Parser\Path\Node\ilFactoryInterface as ilParserPathNodeFactoryInterface;
use ImportHandler\Parser\Path\ComparisonOperator as ilParserPathComparisonOperator;
use ImportHandler\Parser\Path\ilComparison as ilParserPathComparison;
use ImportHandler\Parser\Path\ilHandler as ilParserPathHandler;
use ImportHandler\Parser\Path\Node\ilFactory as ilParserPathNodeFactory;

class ilFactory implements ilParserPathFactory
{
    public function handler(): ilParserPathHandlerInterface
    {
        return new ilParserPathHandler();
    }

    public function node(): ilParserPathNodeFactoryInterface
    {
        return new ilParserPathNodeFactory();
    }

    public function comparison(ilParserPathComparisonOperator $operator, string $value): ilParserPathComparisonInterface
    {
        return new ilParserPathComparison($operator, $value);
    }
}
