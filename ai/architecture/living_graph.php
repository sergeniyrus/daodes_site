<?php

class LivingGraph
{
    private string $basePath;

    private array $graph = [
        'nodes' => [],
        'edges' => [],
        'summary' => []
    ];

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * MAIN ENTRY
     */
    public function build(): array
    {
        $this->ensureStorage();

        if ($this->isDebounced()) {
            return $this->graph;
        }

        $this->scanDirectory($this->basePath . '/app');

        $this->buildSummary();
        $this->writeOutputs();

        $this->markBuildTime();

        return $this->graph;
    }

    /**
     * Ensure graph folder exists
     */
    private function ensureStorage(): void
    {
        $dir = $this->basePath . '/ai/graph';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * Scan Laravel app structure
     */
    private function scanDirectory(string $path): void
    {
        if (!is_dir($path)) return;

        $items = scandir($path);

        foreach ($items as $item) {

            if ($item === '.' || $item === '..') continue;

            $fullPath = $path . '/' . $item;

            if (is_dir($fullPath)) {
                $this->scanDirectory($fullPath);
                continue;
            }

            if (!str_ends_with($item, '.php')) {
                continue;
            }

            $this->analyzeFile($fullPath);
        }
    }

    /**
     * Analyze single PHP file
     */
    private function analyzeFile(string $file): void
    {
        $content = file_get_contents($file);

        $relative = str_replace($this->basePath . '/', '', $file);

        $nodeType = $this->detectType($file, $content);

        $nodeId = md5($file);

        $this->graph['nodes'][] = [
            'id' => $nodeId,
            'file' => $relative,
            'type' => $nodeType
        ];

        $this->detectRelations($relative, $content, $nodeType);
    }

    /**
     * Detect architecture type
     */
    private function detectType(string $file, string $content): string
    {
        $fileName = basename($file);

        if (str_contains($fileName, 'Controller')) {
            return 'controller';
        }

        if (str_contains($fileName, 'Service')) {
            return 'service';
        }

        if (str_contains($fileName, 'Model')) {
            return 'model';
        }

        if (str_contains($content, 'IPFS') || str_contains($content, 'cid')) {
            return 'ipfs';
        }

        if (str_contains($content, 'DB::') || str_contains($content, 'query')) {
            return 'database';
        }

        if (str_contains($fileName, 'Job')) {
            return 'job';
        }

        if (str_contains($fileName, 'Event')) {
            return 'event';
        }

        return 'file';
    }

    /**
     * Detect relationships between components
     */
    private function detectRelations(string $file, string $content, string $type): void
    {
        // Service usage
        preg_match_all('/([A-Z][a-zA-Z0-9_]+Service)/', $content, $matches);

        foreach ($matches[1] ?? [] as $service) {
            $this->graph['edges'][] = [
                'from' => $file,
                'to' => $service,
                'type' => 'uses_service'
            ];
        }

        // IPFS usage
        if (str_contains($content, 'IPFS') || str_contains($content, 'cid')) {
            $this->graph['edges'][] = [
                'from' => $file,
                'to' => 'IPFS_LAYER',
                'type' => 'stores_cid'
            ];
        }

        // DB usage
        if (str_contains($content, 'DB::')) {
            $this->graph['edges'][] = [
                'from' => $file,
                'to' => 'DATABASE',
                'type' => 'queries_db'
            ];
        }
    }

    /**
     * Build architecture summary
     */
    private function buildSummary(): void
    {
        $types = [];

        foreach ($this->graph['nodes'] as $node) {
            $types[$node['type']] = ($types[$node['type']] ?? 0) + 1;
        }

        $this->graph['summary'] = [
            'total_nodes' => count($this->graph['nodes']),
            'total_edges' => count($this->graph['edges']),
            'types' => $types,
            'architecture_mode' => 'LIVING_GRAPH_V1',
            'generated_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Write outputs for AI consumption
     */
    private function writeOutputs(): void
    {
        $graphFile = $this->basePath . '/ai/graph/living_graph.json';
        $contextFile = $this->basePath . '/ai/graph/live_context.json';

        file_put_contents(
            $graphFile,
            json_encode($this->graph, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        file_put_contents(
            $contextFile,
            json_encode([
                'summary' => $this->graph['summary'],
                'last_update' => time()
            ], JSON_PRETTY_PRINT)
        );
    }

    /**
     * Prevent rebuild spam (debounce)
     */
    private function isDebounced(): bool
    {
        $lock = $this->basePath . '/ai/graph/.lock';

        if (!file_exists($lock)) {
            return false;
        }

        return (time() - filemtime($lock)) < 2;
    }

    private function markBuildTime(): void
    {
        $lock = $this->basePath . '/ai/graph/.lock';

        file_put_contents($lock, (string) time());
    }
}