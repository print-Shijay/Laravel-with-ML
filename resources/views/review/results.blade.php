<!DOCTYPE html>
<html>
<head>
    <title>Reviewer Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4 text-center">✅ Reviewer Results</h1>
        <a href="{{ route('review.form') }}" class="btn btn-secondary mb-4">← Back to Input</a>

        <div class="card p-4 shadow mb-5">
            <h2 class="card-title text-primary">Extracted Reviewer Summary</h2>
            <hr>
            <p class="card-text lead">{{ $reviewer }}</p>
            <p class="text-muted small mt-2">Generated from {{ $original_text_length }} characters.</p>
        </div>

        <div class="card p-4 shadow">
            <h2 class="card-title text-success">Generated Questions</h2>
            <hr>
            @if(count($questions) > 0)
                <ol>
                    @foreach($questions as $index => $q)
                        <li class="mb-3">
                            <p class="fw-bold mb-0">{{ $q['question'] }}</p>
                            <p class="text-muted small">
                                **Simplified Answer:** <span class="badge bg-success">{{ $q['answer'] }}</span>
                            </p>
                        </li>
                    @endforeach
                </ol>
            @else
                <p>Could not generate any questions from the extracted sentences.</p>
            @endif
        </div>
    </div>
</body>
</html>
