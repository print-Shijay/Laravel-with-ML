<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Reviewer Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Custom CSS for the fixed layout */
        :root {
            --sidebar-width: 280px;
            --collapsed-width: 60px;
            --header-vertical-padding: 1rem; /* New: Matches top/bottom padding of sidebar content */
            --header-height: 58px; /* New: Adjusted height for a more compact look (approx 16px top + element + 16px bottom) */
            --main-padding: 2rem; /* Horizontal padding for .main-content */
            --sidebar-bg: #f8f9fa;
            --main-bg: #fff;
        }

        body {
            height: 100vh;
            margin: 0;
            display: flex;
            background-color: var(--main-bg);
            overflow: hidden;
        }

        /* 1. Sidebar Container (Unchanged) */
        .sidebar-container {
            flex-shrink: 0;
            height: 100%;
            display: flex;
            position: relative;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #dee2e6;
            width: var(--sidebar-width);
            transition: width 0.3s ease-in-out;
            overflow: hidden;
        }

        .sidebar-container.collapsed {
            width: var(--collapsed-width);
        }

        .sidebar-toggle-area {
            position: absolute;
            top: 1rem; /* 16px top margin */
            left: 0;
            width: var(--collapsed-width);
            height: auto;
            padding: 0 0.5rem;
        }

        .sidebar-content {
            flex-grow: 1;
            padding: var(--header-vertical-padding); /* Uses the same vertical padding */
            padding-left: var(--collapsed-width);
            height: 100%;
            overflow-y: auto;
        }

        .sidebar-container.collapsed .sidebar-content {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }

        /* 2. Main Content Area (Fixed Height Logic) */
        .main-content {
            flex-grow: 1;
            /* Changed vertical padding to use the header variable for consistency */
            padding: var(--header-vertical-padding) var(--main-padding);
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow-y: hidden;
        }

        /* 3. The Header Content */
        .header-content {
            /* Now only uses vertical padding */
            padding-bottom: var(--header-vertical-padding);
            padding-top: 0; /* No need for extra top padding, already in .main-content */
            /* Using a flex-grow on the content instead of fixed height on the header */
            flex-shrink: 0;
        }

        /* 4. The Form Container (Needs dynamic height) */
        .form-wrapper {
            /* 100vh - (top padding of .main-content) - (bottom padding of .main-content)
               - (the height of the header row) - (the height of the hr line + its margin)
               We approximate the header row height based on its padding and content.
            */
            /* Calculate height based on 100% of parent minus fixed elements */
            height: 100%;
            overflow-y: hidden;

            /* Inner wrapper takes up remaining space and scrolls */
            display: flex;
            flex-direction: column;
        }

        .form-scroll-area {
             /* 100% of .form-wrapper height, minus the hr margin (1rem + 0.5rem) */
            height: calc(100% - 1.5rem);
            overflow-y: auto;
            padding-top: 0.5rem; /* Small space after the hr line */
        }

        /* General Styling adjustments */

        .toggle-btn {
            background: none;
            border: none;
            padding: 0.5rem;
            margin-right: 0;
            border-radius: 50%;
            transition: background-color 0.2s;
            display: block;
            width: 100%;
            text-align: center;
        }

        .toggle-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .profile-icon-btn {
            background-color: #e9ecef;
            color: #495057;
            border-radius: 50%;
            padding: 0.5rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
            cursor: pointer;
        }

        .profile-icon-btn:hover {
            background-color: #dee2e6;
        }

        .new-reviewer-btn {
            background-color: #d1e7ff;
            color: #0d6efd;
            border: none;
            text-align: left;
            padding: 0.75rem 1rem;
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .new-reviewer-btn:hover {
            background-color: #c0d8f0;
        }

        .recent-item {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .recent-item:hover {
            background-color: #e9ecef;
        }

    </style>
</head>
<body>

    <div class="sidebar-container" id="sidebarContainer">
        <div class="sidebar-toggle-area">
            <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle Sidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>
        <div class="sidebar-content">
            <button class="btn new-reviewer-btn fw-bold">
                <i class="bi bi-plus-lg me-2"></i> New Reviewer
            </button>
            <hr>
            <h6 class="text-secondary mb-3">Recent Reviewers</h6>
            <div class="list-group">
                <a href="#" class="recent-item text-decoration-none text-dark" title="Review on Quantum Physics">
                    <i class="bi bi-chat-text me-2"></i> Review on Quantum Physics
                </a>
                <a href="#" class="recent-item text-decoration-none text-dark" title="Review on Laravel Framework">
                    <i class="bi bi-chat-text me-2"></i> Review on Laravel Framework
                </a>
            </div>
        </div>
    </div>

    <div class="main-content" id="mainContent">
        <div class="container-fluid h-100 p-0">

            <header class="header-content d-flex justify-content-between align-items-center">
                <h2 class="fw-bold m-0 text-dark">reReview</h2>
                <div class="profile-icon-btn">
                    <i class="bi bi-person-circle fs-5"></i>
                </div>
            </header>



            <div class="form-wrapper">
                <div class="form-scroll-area">
                    <div class="card p-4 shadow-lg border-0 mx-auto" style="max-width: 800px;">
                        <h1 class="display-6 fw-light text-center mb-4">🧠 AI Reviewer Tool</h1>
                        <p class="text-secondary text-center mb-4">Generate key summaries and questions from your text.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                Please ensure the text is at least 50 characters long.
                            </div>
                        @endif

                        <form action="{{ route('review.generate') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="input_text" class="form-label fw-bold">Paste Text for Reviewer Generation:</label>
                                <textarea class="form-control" name="input_text" id="input_text" rows="8" placeholder="Paste your study notes, article, or document here (Min 50 chars)">{{ old('input_text') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="sentence_count" class="form-label fw-bold">Number of Sentences for Reviewer:</label>
                                <input type="number" class="form-control" name="sentence_count" id="sentence_count" value="{{ old('sentence_count', 5) }}" min="1" max="20">
                                <small class="form-text text-muted">This sets the length of the generated summary.</small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Generate Reviewer & Questions</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebarContainer = document.getElementById('sidebarContainer');
            sidebarContainer.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
