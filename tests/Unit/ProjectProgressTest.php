<?php

namespace Tests\Unit;

use App\Models\Project;
use PHPUnit\Framework\TestCase;

class ProjectProgressTest extends TestCase
{
    public function test_it_calculates_project_progress_from_task_counts(): void
    {
        $project = new Project();
        $project->tasks_count = 4;
        $project->completed_tasks_count = 3;

        $this->assertSame(75, $project->progress);
    }

    public function test_a_project_without_tasks_has_zero_progress(): void
    {
        $project = new Project();
        $project->tasks_count = 0;
        $project->completed_tasks_count = 0;

        $this->assertSame(0, $project->progress);
    }
}
