<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

require_once base_path('ai/architecture/living_graph.php');
require_once base_path('ai/graph/bootstrap_graph.php');

class BuildLivingGraph extends Command
{
    protected $signature = 'ai:graph:living';

    protected $description = 'Build Living Architecture Graph';

    public function handle()
    {
        \GraphBootstrap::ensure(base_path());

        $graph = new \LivingGraph(base_path());

        $result = $graph->build();

        $this->info("Graph generated successfully");
        $this->info("Nodes: " . count($result['nodes']));
    }
}