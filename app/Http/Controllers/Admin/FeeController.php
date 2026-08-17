<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\FeeStructure;
use App\Models\Invoice;
use App\Models\Program;
use App\Models\Receipt;
use App\Services\FeeService;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeeController extends Controller
{
    public function structures(): View
    {
        $structures = FeeStructure::with('lines', 'batch', 'program', 'academicYear')->get();

        return view('admin.fees.structures', compact('structures'));
    }

    public function structureCreate(): View
    {
        return view('admin.fees.structure-form', [
            'structure' => null,
            'batches' => Batch::where('active', true)->get(),
            'programs' => Program::where('active', true)->get(),
            'years' => AcademicYear::where('active', true)->get(),
        ]);
    }

    public function structureStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'academic_year_id' => 'nullable|integer|exists:academic_years,id',
            'batch_id' => 'nullable|integer|exists:batches,id',
            'program_id' => 'nullable|integer|exists:programs,id',
            'lines.*.name' => 'required_with:lines|string',
            'lines.*.amount' => 'required_with:lines|numeric|min:0',
            'lines.*.type' => 'nullable|string',
        ]);

        $structure = FeeStructure::create([
            'name' => $validated['name'],
            'academic_year_id' => $validated['academic_year_id'] ?? null,
            'batch_id' => $validated['batch_id'] ?? null,
            'program_id' => $validated['program_id'] ?? null,
            'active' => true,
        ]);

        if (! empty($validated['lines'])) {
            foreach ($validated['lines'] as $index => $line) {
                $structure->lines()->create([
                    'name' => $line['name'],
                    'amount' => $line['amount'],
                    'type' => $line['type'] ?? null,
                    'sequence' => $index,
                ]);
            }
        }

        return redirect()->route('admin.fees.structures')->with('success', 'تم إنشاء هيكل الرسوم.');
    }

    public function generateInvoices(FeeStructure $structure): RedirectResponse
    {
        $count = FeeService::generateInvoicesForBatch($structure);

        return back()->with('success', "تم توليد {$count} فاتورة.");
    }

    public function invoices(Request $request): View
    {
        $invoices = Invoice::with('student', 'parent')
            ->when($request->state, fn ($q, $v) => $q->where('state', $v))
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.fees.invoices', compact('invoices'));
    }

    /**
     * Register a payment against an invoice (cash/card/transfer/wallet).
     */
    public function registerPayment(Request $request, Invoice $invoice): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['required', 'in:cash,card,transfer,wallet'],
            'date' => ['nullable', 'date'],
        ]);

        try {
            FeeService::registerPayment($invoice, [
                'amount' => (float) $request->amount,
                'method' => $request->method,
                'date' => $request->date,
            ]);

            return back()->with('success', 'تم تسجيل الدفعة بنجاح.');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function invoicePdf(Invoice $invoice)
    {
        $invoice->load('lines', 'student.batch', 'parent');

        return PdfService::download('pdf.invoice', ['invoice' => $invoice], 'invoice-'.$invoice->number.'.pdf');
    }

    public function receiptPdf(Receipt $receipt)
    {
        $receipt->load('invoice.student', 'invoice.parent', 'payment');

        return PdfService::download('pdf.receipt', ['receipt' => $receipt], 'receipt-'.$receipt->receipt_no.'.pdf');
    }
}
