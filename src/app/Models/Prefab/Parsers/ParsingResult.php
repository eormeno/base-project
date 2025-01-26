<?php

namespace App\Models\Prefab\Parsers;

class ParsingResult {
	public bool $isPrefab;
	public string $name;
	public ?string $prefab;

	public function __construct(array $parts) {
		$data = $this->buildResult($parts);
		$this->isPrefab = $data['is_prefab'];
		$this->name = $data['name'];
		$this->prefab = $data['prefab'] ?? null;
	}

	private function buildResult(array $parts): array
	{
		return count($parts) === 1
			? $this->buildSimpleNameResult($parts[0])
			: $this->buildPrefabNameResult($parts[0], $parts[1]);
	}

	private function buildSimpleNameResult(string $name): array
	{
		return [
			'is_prefab' => false,
			'name' => $name
		];
	}

	private function buildPrefabNameResult(string $name, string $prefab): array
	{
		return [
			'is_prefab' => true,
			'name' => $name,
			'prefab' => $prefab
		];
	}
}
