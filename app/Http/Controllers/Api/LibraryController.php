<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LibraryBook;
use App\Models\LibraryIssue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * GET /library/books
     */
    public function books(Request $request): JsonResponse
    {
        $query = LibraryBook::where('active', true);

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('author', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $books = $query->orderBy('title')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => collect($books->items())->map(fn ($b) => [
                'id' => $b->id,
                'title' => $b->title,
                'author' => $b->author,
                'isbn' => $b->isbn,
                'category' => $b->category,
                'available' => $b->available_qty,
            ]),
            'pagination' => [
                'total' => $books->total(),
                'per_page' => $books->perPage(),
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
            ],
        ]);
    }

    /**
     * POST /library/books/{id}/reserve
     */
    public function reserve(Request $request, int $bookId): JsonResponse
    {
        $book = LibraryBook::findOrFail($bookId);

        if (! $book->active || $book->available_qty <= 0) {
            abort(422, 'Book is not available');
        }

        $user = $request->user();
        $student = $user->student;

        if (! $student) {
            abort(403, 'Only students can reserve books');
        }

        $already = LibraryIssue::where('book_id', $book->id)
            ->where('student_id', $student->id)
            ->whereIn('state', [LibraryIssue::STATE_ISSUED, LibraryIssue::STATE_OVERDUE])
            ->exists();

        if ($already) {
            abort(422, 'You already have this book');
        }

        $book->decrement('available_qty');

        $issue = LibraryIssue::create([
            'book_id' => $book->id,
            'student_id' => $student->id,
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'state' => LibraryIssue::STATE_ISSUED,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Book reserved',
            'data' => [
                'issue_id' => $issue->id,
                'due_date' => $issue->due_date->toDateString(),
            ],
        ], 201);
    }

    /**
     * GET /library/my-books
     */
    public function myBooks(Request $request): JsonResponse
    {
        $user = $request->user();
        $student = $user->student;

        if (! $student) {
            abort(403, 'Only students have borrowed books');
        }

        $issues = $student->libraryIssues()->with('book')->orderByDesc('id')->get();

        return response()->json([
            'status' => 'success',
            'data' => $issues->map(fn ($i) => [
                'issue_id' => $i->id,
                'book_id' => $i->book_id,
                'title' => $i->book?->title,
                'author' => $i->book?->author,
                'issue_date' => $i->issue_date?->toDateString(),
                'due_date' => $i->due_date?->toDateString(),
                'state' => $i->state,
            ]),
        ]);
    }
}
