<!DOCTYPE html>
<html>
<head>
    <title>Saved Review #{{ $reviewId }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container py-5">
        <h1 class="mb-4 text-center">
            <i class="fas fa-bookmark text-warning"></i> Viewing Saved Review #{{ $reviewId }}
        </h1>

        {{-- Display Success Message (e.g., after deletion) --}}
        @if(session('success'))
            <div class="text-center alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('review.form') }}" class="btn btn-secondary">← Back to Input</a>

            <div>
                {{-- Always display 'Review Saved' status --}}
                <button type="button" class="btn btn-success me-2" disabled>
                    <i class="fas fa-check-circle"></i> Review Saved
                </button>

                {{-- Delete Button for Saved Views --}}
                {{-- The variable $is_show_view is set to true in the controller for this view --}}
                @if(isset($is_show_view) && $is_show_view && isset($reviewId))
                    <form action="{{ route('review.delete', $reviewId) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        ---

        <div class="row">
            <div class="col-lg-12">
                {{-- Reviewer Summary --}}
                <div class="p-4 mb-5 shadow-sm card">
                    <h2 class="card-title text-primary"><i class="fas fa-file-alt"></i> Extracted Reviewer Summary</h2>
                    <hr>
                    {{-- Note: We use the 'summary' key passed from the controller --}}
                    <p class="card-text lead">{{ $summary }}</p>
                    <p class="mt-2 text-muted small">Generated from approximately **{{ $original_text_length }}** characters.</p>
                </div>
            </div>
        </div>

        ---

        <div class="row">
            <div class="col-lg-12">
                {{-- Questions Section --}}
                <div class="p-4 shadow-sm card">
                    <h2 class="card-title text-success"><i class="fas fa-question-circle"></i> Generated Questions</h2>
                    <hr>

                    @if(is_array($questions) && count($questions) > 0)
                        <ol>
                            @foreach($questions as $q)
                                <li class="mb-3">
                                    <p class="mb-0 fw-bold">{{ $q['question'] ?? 'Question key not found' }}</p>

                                    <p class="text-muted small">
                                        <strong>Simplified Answer:</strong>
                                        <span class="badge bg-success">
                                            {{ $q['answer'] ?? 'N/A' }}
                                        </span>
                                    </p>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="text-muted">No questions weres generated from this summary.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</body>
</html>
