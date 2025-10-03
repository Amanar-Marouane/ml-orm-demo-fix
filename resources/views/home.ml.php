@extends('layouts.app')

@section('title')
{{ $title }}
@endsection

@section('header')
<h1>MonkeysLegion</h1>
<p class="lead">Many-to-Many Relationship Depth Test</p>
@endsection

@section('content')
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-database"></i> Relationship Depth Test Results</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($testResults) && !empty($testResults)): ?>
                        <div class="test-results">
                            <?php foreach ($testResults as $result): ?>
                                <div class="result-line">
                                    <?php if (str_contains($result, '✅')): ?>
                                        <div class="text-success"><strong><?= htmlspecialchars($result) ?></strong></div>
                                    <?php elseif (str_contains($result, '❌')): ?>
                                        <div class="text-danger"><strong><?= htmlspecialchars($result) ?></strong></div>
                                    <?php elseif (str_contains($result, '🔍') || str_contains($result, '📈') || str_contains($result, '📊') || str_contains($result, '🎯')): ?>
                                        <div class="text-primary mt-3"><strong><?= htmlspecialchars($result) ?></strong></div>
                                    <?php elseif (str_starts_with($result, '=')): ?>
                                        <div class="text-muted"><?= htmlspecialchars($result) ?></div>
                                    <?php elseif (str_starts_with($result, '   ')): ?>
                                        <div class="text-muted ml-3" style="font-family: monospace;"><?= htmlspecialchars($result) ?></div>
                                    <?php elseif (str_starts_with($result, '     ')): ?>
                                        <div class="text-muted ml-4" style="font-family: monospace;"><?= htmlspecialchars($result) ?></div>
                                    <?php elseif (empty(trim($result))): ?>
                                        <br>
                                    <?php else: ?>
                                        <div><?= htmlspecialchars($result) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No test results available.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>What This Test Shows</h4>
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>Depth 1:</strong> Can User load its Bugs? Can Bug load its Users?</li>
                        <li><strong>Depth 2:</strong> Can User → Bugs → Users work? Can Bug → Users → Bugs work?</li>
                        <li><strong>Junction Table:</strong> Is the data actually stored in the database?</li>
                    </ul>
                    <a href="/" class="btn btn-primary btn-sm mt-2">
                        <i class="fas fa-redo"></i> Run Test Again
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .result-line {
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .card {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
    }
</style>
@endsection