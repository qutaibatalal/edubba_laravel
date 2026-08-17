<?php

namespace App\Observers;

use App\Models\LibraryBook;
use App\Models\LibraryIssue;

class LibraryObserver
{
    /**
     * Decrement available quantity on issue.
     */
    public function issueCreated(LibraryIssue $issue): void
    {
        if ($issue->state === LibraryIssue::STATE_ISSUED) {
            $book = $issue->book;
            if ($book) {
                $book->decrement('available_qty');
            }
        }
    }

    /**
     * Restore available quantity on return.
     */
    public function issueUpdated(LibraryIssue $issue): void
    {
        if ($issue->wasChanged('state') && $issue->state === LibraryIssue::STATE_RETURNED) {
            $book = LibraryBook::find($issue->book_id);
            if ($book) {
                $book->increment('available_qty');
            }
        }
    }
}
