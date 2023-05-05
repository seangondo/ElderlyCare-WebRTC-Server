<?php
/* *********************************************************************
 * This Original Work is copyright of 51 Degrees Mobile Experts Limited.
 * Copyright 2022 51 Degrees Mobile Experts Limited, Davidson House,
 * Forbury Square, Reading, Berkshire, United Kingdom RG1 3EU.
 *
 * This Original Work is licensed under the European Union Public Licence
 * (EUPL) v.1.2 and is subject to its terms as set out below.
 *
 * If a copy of the EUPL was not distributed with this file, You can obtain
 * one at https://opensource.org/licenses/EUPL-1.2.
 *
 * The 'Compatible Licences' set out in the Appendix to the EUPL (as may be
 * amended by the European Commission) shall be deemed incompatible for
 * the purposes of the Work and the provisions of the compatibility
 * clause in Article 5 of the EUPL shall not apply.
 * 
 * If using the Work as, or as part of, a network application, by 
 * including the attribution notice(s) required under Article 5 of the EUPL
 * in the end user terms of the application under an appropriate heading, 
 * such notice(s) shall fulfill the requirements of that article.
 * ********************************************************************* */



namespace fiftyone\pipeline\core\tests;

require(__DIR__ . "/ErrorFlowData.php");
require(__DIR__ . "/StopFlowData.php");
require(__DIR__ . "/MemoryLogger.php");
require(__DIR__ . "/ExampleFlowElement1.php");
require(__DIR__ . "/ExampleFlowElement2.php");

use fiftyone\pipeline\core\PipelineBuilder;

// Test Pipeline builder for use with PHP unit tests
class TestPipeline
{
    public $pipeline;

    public $flowElement1;

    public $flowData;

    public $logger;

    public function __construct($suppressException = true)
    {
        $this->logger = new MemoryLogger("info");
        $this->flowElement1 = new ExampleFlowElement1();
        $this->pipeline = (new PipelineBuilder())
            ->add($this->flowElement1)
            ->add(new ErrorFlowData())
            ->add(new StopFlowData())
            ->add(new ExampleFlowElement2())
            ->addLogger($this->logger)
            ->build();
        $this->pipeline->suppressProcessExceptions = $suppressException;
        $this->flowData = $this->pipeline->createFlowData();
        $this->flowData->evidence->set("header.user-agent", "test");
        $this->flowData->evidence->set("some.other-evidence", "test");
        $this->flowData->process();
    }
}
