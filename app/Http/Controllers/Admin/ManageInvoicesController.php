<?php

namespace App\Http\Controllers\Admin;

use App\CreditNotes;
use App\Currency;
use App\Helper\Reply;
use App\Http\Requests\Invoices\StoreInvoice;
use App\Invoice;
use App\InvoiceItems;
use App\InvoiceSetting;
use App\Notifications\NewInvoice;
use App\Payment;
use App\Project;
use App\EmployeeDetails;
use App\Task;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ManageInvoicesController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.projects';
        $this->pageIcon = 'icon-layers';
        $this->middleware(function ($request, $next) {
            if (!in_array('invoices',$this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->tasks = Task::findOrFail();
        $this->currencies = Currency::all();
        $this->lastInvoice = Invoice::orderBy('id', 'desc')->first();
        $this->invoiceSetting = invoice_setting();

        return view('admin.projects.invoices.create', $this->data);
    }

    public function createInvoice(Request $request)
    {
        $this->tasks = Task::findOrFail();
        $this->project = Project::findOrFail($request->id);
        $this->currencies = Currency::all();
        $this->lastInvoice = Invoice::orderBy('id', 'desc')->first();
        $this->invoiceSetting = invoice_setting();
        return view('admin.projects.invoices.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoice $request)
    {
        $items = $request->input('item_name');
        $cost_per_item = $request->input('cost_per_item');
        $quantity = $request->input('quantity');
        $hsnSacCode = request()->input('hsn_sac_code');
        $amount = $request->input('amount');
        $type = $request->input('type');

        if ($request->total == 0) {
            return Reply::error(__('messages.amountIsZero'));
        }

        if (trim($items[0]) == '' || trim($items[0]) == '' || trim($cost_per_item[0]) == '') {
            return Reply::error(__('messages.addItem'));
        }

        foreach ($quantity as $qty) {
            if (!is_numeric($qty) && $qty < 1) {
                return Reply::error(__('messages.quantityNumber'));
            }
        }

        foreach ($cost_per_item as $rate) {
            if (!is_numeric($rate)) {
                return Reply::error(__('messages.unitPriceNumber'));
            }
        }

        foreach ($amount as $amt) {
            if (!is_numeric($amt)) {
                return Reply::error('Amount should be a number.');
            }
        }

        foreach ($items as $itm) {
            if (is_null($itm)) {
                return Reply::error('Item name cannot be blank.');
            }
        }


        $invoice = new Invoice();
        $invoice->task_id = $request->project_id ?? NULL;
        $invoice->issue_date = Carbon::createFromFormat($this->global->date_format, $request->issue_date)->format('Y-m-d');
        $invoice->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $invoice->sub_total = $request->sub_total;
        $invoice->total = $request->total;
        $invoice->currency_id = $request->currency_id;
        $invoice->save();

        foreach ($items as $key => $item):
            InvoiceItems::create(['invoice_id' => $invoice->id, 'item_name' => $item, 'hsn_sac_code' => (isset($hsnSacCode[$key]) && !is_null($hsnSacCode[$key])) ? $hsnSacCode[$key] : null, 'type' => $type[$key], 'quantity' => $quantity[$key], 'unit_price' => $cost_per_item[$key], 'amount' => $amount[$key]]);
        endforeach;

        $this->logSearchEntry($invoice->id, 'Invoice #'.$invoice->invoice_number, 'admin.all-invoices.show', 'invoice');

        $this->project = Task::findOrFail($request->project_id);
        $view = view('admin.projects.invoices.invoice-ajax', $this->data)->render();
        return Reply::successWithData(__('messages.invoiceCreated'), ['html' => $view]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->project = Project::with('invoices', 'invoices.currency')->findOrFail($id);
        return view('admin.projects.invoices.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Reply::error(__('messages.invoiceCanNotDeleted'));
    }

    public function download($id) {
        //        header('Content-type: application/pdf');
        $this->invoice = Invoice::with(['task'])->findOrFail($id);
      //  dd($this->invoice->task->users[]);
        $this->clientName = User::where('id', '=', $this->invoice->task->users[0]->id)->first();
        $this->clientDetail = EmployeeDetails::with('countries', 'states')->where('user_id', '=', $this->invoice->task->users[0]->id)->first();
        $this->paidAmount = $this->invoice->getPaidAmount();
        $this->creditNote = 0;
        if ($this->invoice->credit_note) {
            $this->creditNote = CreditNotes::where('invoice_id', $id)
                ->select('cn_number')
                ->first();
        }

        // Download file uploaded
        if ($this->invoice->file != null) {
            return response()->download(storage_path('app/public/invoice-files').'/'.$this->invoice->file);
        }

        if ($this->invoice->discount > 0) {
            if ($this->invoice->discount_type == 'percent') {
                $this->discount = (($this->invoice->discount/100)*$this->invoice->sub_total);
            } else {
                $this->discount = $this->invoice->discount;
            }
        } else {
            $this->discount = 0;
        }

        $taxList = array();

        $items = InvoiceItems::whereNotNull('taxes')
            ->where('invoice_id', $this->invoice->id)
            ->get();
        foreach ($items as $item) {
            foreach (json_decode($item->taxes) as $tax){
                $this->tax = InvoiceItems::taxbyid($tax)->first();
                if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                } else {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = $this->global;

        $this->payments = Payment::with(['offlineMethod'])->where('invoice_id', $this->invoice->id)->where('status', 'complete')->orderBy('paid_on', 'desc')->get();

        $this->invoiceSetting = invoice_setting();
          
        
    //    return view('invoices.'.$this->invoiceSetting->template, $this->data);

        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $pdf->loadView('invoices.'.$this->invoiceSetting->template, $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $filename = $this->invoice->invoice_number;
        //       return $pdf->stream();
        return $pdf->download($filename . '.pdf');
    }
}
