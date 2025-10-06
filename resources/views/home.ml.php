@extends('layouts.app')

@section('title')
{{ $title }}
@endsection

@section('header')
<div class="d-flex align-items-center py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="app-icon-wrapper rounded-circle bg-white shadow-sm p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-database text-primary" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col">
                <h1 class="display-5 fw-bold mb-0">MonkeysLegion</h1>
                <p class="lead mb-0 opacity-75">ORM Relationship Performance Dashboard</p>
                <div class="mt-2">
                    <span class="badge bg-light text-primary">MAX_DEPTH: {{ \App\Controller\HomeController::MAX_DEPTH ?? 3 }}</span>
                </div>
            </div>
            <div class="col-auto">
                <a href="/" class="btn btn-light">
                    <i class="fas fa-redo me-1"></i> Refresh Tests
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container py-4">
    <!-- Performance Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold"><i class="fas fa-tachometer-alt me-2"></i>Performance Overview</h4>
        </div>
        <?php
        // Extract benchmarks from test results
        $benchmarks = [];
        $benchmarkData = [];
        foreach ($testResults as $result) {
            if (strpos($result, '⏱️') === 0 && strpos($result, 'ms') !== false) {
                preg_match('/⏱️\s+(.+?):\s+([\d\.]+)\s+ms\s+\((.+)\)/', $result, $matches);
                if (count($matches) > 3) {
                    $benchmarks[] = [
                        'operation' => $matches[1],
                        'time' => floatval($matches[2]),
                        'description' => $matches[3]
                    ];
                    $benchmarkData[] = floatval($matches[2]);
                }
            }
        }

        // Calculate stats if we have data
        $avgTime = count($benchmarkData) > 0 ? array_sum($benchmarkData) / count($benchmarkData) : 0;
        $maxTime = count($benchmarkData) > 0 ? max($benchmarkData) : 0;
        $minTime = count($benchmarkData) > 0 ? min($benchmarkData) : 0;
        ?>

        <div class="col-md-3 mb-3">
            <div class="performance-card h-100 p-3 rounded animate-fadein delay-1">
                <h6 class="text-muted">Average Query Time</h6>
                <div class="d-flex align-items-baseline">
                    <h3 class="mb-0 me-1">{{ number_format($avgTime, 2) }}</h3>
                    <small class="text-muted">ms</small>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar" style="width: <?= ($avgTime / $maxTime) * 100 ?>%"></div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="performance-card h-100 p-3 rounded animate-fadein delay-2">
                <h6 class="text-muted">Slowest Query</h6>
                <div class="d-flex align-items-baseline">
                    <h3 class="mb-0 me-1">{{ number_format($maxTime, 2) }}</h3>
                    <small class="text-muted">ms</small>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="performance-card h-100 p-3 rounded animate-fadein delay-3">
                <h6 class="text-muted">Fastest Query</h6>
                <div class="d-flex align-items-baseline">
                    <h3 class="mb-0 me-1">{{ number_format($minTime, 2) }}</h3>
                    <small class="text-muted">ms</small>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar" style="width: <?= ($minTime / $maxTime) * 100 ?>%"></div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="performance-card h-100 p-3 rounded animate-fadein delay-4">
                <h6 class="text-muted">Total Queries</h6>
                <div class="d-flex align-items-baseline">
                    <h3 class="mb-0">{{ count($benchmarks) }}</h3>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar" style="width: <?= min(count($benchmarks) * 10, 100) ?>%"></div>
                </div>
            </div>
        </div>

        <x-card>
            <div class="col-md-12 mt-2">
                <div class="ml-card animate-fadein delay-2">
                    @slot('header')
                    <div class="col-12">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2"></i>Query Performance</h5>
                    </div>
                    @endslot
                    <div class="ml-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Operation</th>
                                        <th>Time (ms)</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($benchmarks as $benchmark)
                                    <tr>
                                        <td class="fw-medium">
                                            {{$benchmark['operation']}}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-2" style="width: 100px; height: 6px;">
                                                    <div class="progress-bar" style="width: <?= ($benchmark['time'] / $maxTime) * 100 ?>%"></div>
                                                </div>
                                                {{ number_format($benchmark['time'], 2) }} ms
                                            </div>
                                        </td>
                                        <td>{{ $benchmark['description'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Relationship Test Results -->
    <div class="row mb-4">
        <x-card>
            @slot('header')
            <div class="col-12">
                <h4 class="fw-bold"><i class="fas fa-check-circle me-2"></i>Test Results Summary</h4>
            </div>
            @endslot

            <?php
            // Extract the summary results
            $summaryResults = [];
            $inSummarySection = false;

            foreach ($testResults as $result) {
                if (strpos($result, '🎯 SUMMARY') === 0) {
                    $inSummarySection = true;
                    continue;
                }

                if ($inSummarySection && strpos($result, '=') === 0) {
                    continue;
                }

                if ($inSummarySection && strpos($result, '⏱️') === 0) {
                    $inSummarySection = false;
                    break;
                }

                if ($inSummarySection && !empty(trim($result))) {
                    $summaryResults[] = $result;
                }
            }
            ?>

            <div class="col-md-12">
                <div class="ml-card animate-fadein delay-1">
                    <div class="ml-card-body">
                        <div class="row">
                            <?php foreach ($summaryResults as $index => $result): ?>
                                <div class="col-md-4 col-sm-6 mb-3 animate-fadein" style="animation-delay: <?= 0.1 * ($index + 1) ?>s">
                                    <?php if (strpos($result, '✅') !== false): ?>
                                        <div class="alert alert-success d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-check-circle fa-lg"></i>
                                            </div>
                                            <div>
                                                {!! str_replace('✅', '', $result) !!}
                                            </div>
                                        </div>
                                    <?php elseif (strpos($result, '❌') !== false): ?>
                                        <div class="alert alert-danger d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-times-circle fa-lg"></i>
                                            </div>
                                            <div>
                                                {!! str_replace('❌', '', $result) !!}
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-info-circle fa-lg"></i>
                                            </div>
                                            <div>
                                                {!! $result !!}
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Detailed Test Results -->
    <div class="row">
        <x-card>
            @slot('header')
            <div class="col-12">
                <h4 class="fw-bold"><i class="fas fa-list-ul me-2"></i>Detailed Test Results</h4>
            </div>
            @endslot

            <div class="col-md-12 mb-4">
                <div class="ml-card animate-fadein delay-2">
                    <div class="ml-card-body p-0">
                        <div class="accordion" id="testResultsAccordion">
                            <?php
                            // Process the detailed test results
                            $currentSection = '';
                            $sectionContent = [];
                            $sections = [];

                            foreach ($testResults as $index => $result) {
                                if (strpos($result, '📈') === 0 || strpos($result, '📊') === 0) {
                                    // Save previous section if exists
                                    if (!empty($currentSection) && !empty($sectionContent)) {
                                        $sections[$currentSection] = $sectionContent;
                                    }

                                    // Start new section
                                    $currentSection = trim(str_replace(['📈', '📊'], '', $result));
                                    $sectionContent = [];
                                } elseif (
                                    !empty($currentSection) &&
                                    strpos($result, '🔍') !== 0 &&
                                    strpos($result, '🎯') !== 0 &&
                                    strpos($result, '⏱️ BENCHMARKS') !== 0 &&
                                    strpos($result, '=') !== 0
                                ) {
                                    $sectionContent[] = $result;
                                }
                            }

                            // Save the last section
                            if (!empty($currentSection) && !empty($sectionContent)) {
                                $sections[$currentSection] = $sectionContent;
                            }

                            // Generate accordion items
                            $accordionIndex = 0;
                            foreach ($sections as $title => $content): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $accordionIndex }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $accordionIndex }}" aria-expanded="false"
                                            aria-controls="collapse{{ $accordionIndex }}">
                                            {!! $title !!}
                                        </button>
                                    </h2>
                                    <div id="collapse{{$accordionIndex}}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $accordionIndex }}" data-bs-parent="#testResultsAccordion">
                                        <div class="accordion-body">
                                            <?php foreach ($content as $line): ?>
                                                <div class="result-line">
                                                    <?php if (strpos($line, '✅') !== false): ?>
                                                        <div class="text-success"><strong>{!! $line !!}</strong></div>
                                                    <?php elseif (strpos($line, '❌') !== false): ?>
                                                        <div class="text-danger"><strong>{!! $line !!}</strong></div>
                                                    <?php elseif (strpos($line, '⏱️') !== false): ?>
                                                        <div class="text-warning"><strong>{!! $line !!}</strong></div>
                                                    <?php elseif (strpos($line, '⚠️') !== false): ?>
                                                        <div class="text-warning"><strong>{!! $line !!}</strong></div>
                                                    <?php elseif (strpos($line, ' ') === 0): ?>
                                                        <div class="text-muted ms-3" style="font-family: monospace;">{!! $line !!}</div>
                                                    <?php elseif (strpos($line, ' ') === 0): ?>
                                                        <div class="text-muted ms-4" style="font-family: monospace;">{!! $line !!}</div>
                                                    <?php elseif (empty(trim($line))): ?>
                                                        <br>
                                                    <?php else: ?>
                                                        <div>{!! $line !!}</div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $accordionIndex++;
                            endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection