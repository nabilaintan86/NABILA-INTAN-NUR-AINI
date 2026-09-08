@extends('layouts.admin')
@section('title', 'Invoice List - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        Invoice List
                                    </h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/apps/invoice/invoice-list') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/apps/invoice/invoice-list') }}#">Invoice</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Invoices List
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                        <li>
                                            <a href="{{ url('/admin/apps/invoice/invoice-list') }}#" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Create</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/apps/invoice/invoice-list') }}#" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Create Invoice</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card">
                                <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="tb-col tb-col-check" data-sortable="false">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </th>
                                            <th class="tb-col">
                                                <span class="overline-title">invoice id</span>
                                            </th>
                                            <th class="tb-col">
                                                <span class="overline-title">email</span>
                                            </th>
                                            <th class="tb-col">
                                                <span class="overline-title">product</span>
                                            </th>
                                            <th class="tb-col">
                                                <span class="overline-title">amount</span>
                                            </th>
                                            <th class="tb-col tb-col-xl">
                                                <span class="overline-title">date</span>
                                            </th>
                                            <th class="tb-col tb-col-xxl">
                                                <span class="overline-title">Status</span>
                                            </th>
                                            <th class="tb-col tb-col-end" data-sortable="false">
                                                <span class="overline-title">Action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#790033</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>euismod@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>Basic</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>$460.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/25</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Paid</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#790034</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>kevin@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>Enterprice</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>$123.99</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/03/23</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Paid</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#985233</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>kamran@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>Enterprice</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>$365.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/03/22</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Paid</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#790038</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>alex@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                Team
                                            </td>
                                            <td class="tb-col">
                                                <span>$384.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/02/15</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-warning-soft">Pending</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#787025</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>softnio@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                Company
                                            </td>
                                            <td class="tb-col">
                                                <span>$460.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/15</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-warning-soft">Pending</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#256943</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>nioboard@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>Enterprice</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>$943.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/25</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-danger-soft">Canceled</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#790845</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>martin@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                Basic
                                            </td>
                                            <td class="tb-col">
                                                <span>$278.99</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/25</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-secondary-soft">Refund</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#623862</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>jecica@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                Basic
                                            </td>
                                            <td class="tb-col">
                                                <span>$989.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/25</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Paid</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#623861</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>dashlite@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                Basic
                                            </td>
                                            <td class="tb-col">
                                                <span>$289.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/25</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Paid</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#623899</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>ninetheme@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                Basic
                                            </td>
                                            <td class="tb-col">
                                                <span>$289.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/25</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Paid</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#623876</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>ninetheme@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                Basic
                                            </td>
                                            <td class="tb-col">
                                                <span>$289.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/25</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Paid</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#623876</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>ninetheme@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                Basic
                                            </td>
                                            <td class="tb-col">
                                                <span>$289.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/25</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-warning-soft">Pending</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="text-light">#623876</a>
                                            </td>
                                            <td class="tb-col">
                                                <span>muffin@outlook.net</span>
                                            </td>
                                            <td class="tb-col">
                                                Basic
                                            </td>
                                            <td class="tb-col">
                                                <span>$289.00</span>
                                            </td>
                                            <td class="tb-col tb-col-xl">
                                                <span>2022/04/25</span>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-danger-soft">Canceled</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="d-flex justify-content-end gap g-2">
                                                    <div class="gap-col">
                                                        <button type="button" class="btn btn-sm btn-icon btn-lighter" title="Print"><em class="icon ni ni-printer"></em></button>
                                                    </div>
                                                    <div class="gap-col">
                                                        <a href="{{ url('/admin/apps/invoice/invoice-preview') }}" class="btn btn-sm btn-lighter"> Preview </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="{{ asset('admin/assets/js/data-tables/data-tables.js') }}"></script>
    @endpush
