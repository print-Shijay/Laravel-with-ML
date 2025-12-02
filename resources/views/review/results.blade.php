<!DOCTYPE html>
<html>
<head>
    <title>Reviewer Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4 text-center">✅ Reviewer Results</h1>

        {{-- Display Success Message if saved --}}
        @if(session('success'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('review.form') }}" class="btn btn-secondary">← Back to Input</a>

            {{-- Conditional Save/Saved Button --}}
            @auth
                @if(isset($saved))
                    {{-- Display after successful saving --}}
                    <button type="button" class="btn btn-success" disabled>
                        <i class="fas fa-check-circle"></i> Review Saved (#{{ $reviewId }})
                    </button>
                    {{-- Optional: Link to a view page for the saved review --}}
                    {{-- <a href="{{ route('review.show', $reviewId) }}" class="btn btn-info">View Saved</a> --}}
                @else
                    {{-- Display on initial generation/preview --}}
                    <form action="{{ route('review.save') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Review
                        </button>
                    </form>
                @endif
            @else
                <a href="/login" class="btn btn-primary disabled" aria-disabled="true">Log in to Save</a>
            @endauth
        </div>

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
