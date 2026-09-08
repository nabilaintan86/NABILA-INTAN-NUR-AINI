@extends('layouts.admin')
@section('title', 'Breakpoints - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Breakpoints
                                </h1>
                                <p>
                                    Breakpoints are customizable widths that determine how your responsive layout behaves across device or viewport sizes in Bootstrap.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Breakpoints
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Breakpoint
                                                    </th>
                                                    <th>
                                                        Class infix
                                                    </th>
                                                    <th>
                                                        Dimensions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="small">
                                                        Extra small
                                                    </td>
                                                    <td>
                                                        <em>None</em>
                                                    </td>
                                                    <td class="small">
                                                        &lt;576px
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="small">
                                                        Small
                                                    </td>
                                                    <td>
                                                        <code>sm</code>
                                                    </td>
                                                    <td class="small">
                                                        ≥576px
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="small">
                                                        Medium
                                                    </td>
                                                    <td>
                                                        <code>md</code>
                                                    </td>
                                                    <td class="small">
                                                        ≥768px
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="small">
                                                        Large
                                                    </td>
                                                    <td>
                                                        <code>lg</code>
                                                    </td>
                                                    <td class="small">
                                                        ≥992px
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="small">
                                                        Extra large
                                                    </td>
                                                    <td>
                                                        <code>xl</code>
                                                    </td>
                                                    <td class="small">
                                                        ≥1200px
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="small">
                                                        Extra extra large
                                                    </td>
                                                    <td>
                                                        <code>xxl</code>
                                                    </td>
                                                    <td class="small">
                                                        ≥1400px
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
            </div>
        </div>
    @endsection
