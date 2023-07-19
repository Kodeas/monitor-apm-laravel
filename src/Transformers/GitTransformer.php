<?php

namespace Kodeas\Monitor\Transformers;

class GitTransformer
{
    public function run(): array
    {
        return [
            'commit_hash' => trim(shell_exec('git rev-parse HEAD')) ?: null,
            'branch_name' => trim(shell_exec('git rev-parse --abbrev-ref HEAD')) ?: null,
        ];
    }
}