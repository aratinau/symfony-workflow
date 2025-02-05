<?php

namespace App\Workflow;

use Symfony\Component\Workflow\Workflow;

/**
 * @deprecated
 */
class DynamicWorkflowServiceFactory
{
    public function create(DynamicWorkflowLoader $loader, string $target): Workflow
    {
        return $loader->createDynamicWorkflow($target);
    }
}
