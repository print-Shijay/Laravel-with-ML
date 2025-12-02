<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReviewerGenerator;

class ReviewController extends Controller
{
    public function showForm()
    {
        return view('review.form');
    }

    public function generateReview(Request $request, ReviewerGenerator $generator)
    {
        // 1. Validate the input
        $request->validate([
            'input_text' => 'required|string|min:50',
            'sentence_count' => 'required|integer|min:1|max:20'
        ]);

        $inputText = $request->input('input_text');
        $count = $request->input('sentence_count');

        // 2. Call the generator service for Extractive Summarization
        $reviewerSentences = $generator->generateReviewer($inputText, $count);
        $reviewerText = implode(" ", $reviewerSentences); // Join sentences for display

        // 3. Call the generator service for Question Creation (Re-enabled)
        $questions = $generator->generateQuestions($reviewerSentences);

        // 4. Return results to a view
        return view('review.results', [
            'reviewer' => $reviewerText,
            'questions' => $questions,
            'original_text_length' => strlen($inputText),
        ]);
    }
}
