@extends('layouts.admin')
@section('title', 'Tables - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content wide-md">
                                <h1 class="nk-block-title">
                                    Tables
                                </h1>
                                <p>
                                    Documentation and examples for opt-in styling of tables (given their prevalent use in JavaScript plugins) with Bootstrap.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                    <p>
                                        Using the most basic table markup, here’s how <code>.table</code>-based tables look in Bootstrap.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table small">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td colspan="2">
                                                        Larry the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-1" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-1">&lt;table class=&quot;table&quot;&gt;  &lt;thead&gt;    &lt;tr&gt;      &lt;th scope=&quot;col&quot;&gt;#&lt;/th&gt;      &lt;th scope=&quot;col&quot;&gt;First&lt;/th&gt;      &lt;th scope=&quot;col&quot;&gt;Last&lt;/th&gt;      &lt;th scope=&quot;col&quot;&gt;Handle&lt;/th&gt;    &lt;/tr&gt;  &lt;/thead&gt;  &lt;tbody&gt;    &lt;tr&gt;      &lt;th scope=&quot;row&quot;&gt;1&lt;/th&gt;      &lt;td&gt;Mark&lt;/td&gt;      &lt;td&gt;Otto&lt;/td&gt;      &lt;td&gt;@mdo&lt;/td&gt;    &lt;/tr&gt;    &lt;tr&gt;      &lt;th scope=&quot;row&quot;&gt;2&lt;/th&gt;      &lt;td&gt;Jacob&lt;/td&gt;      &lt;td&gt;Thornton&lt;/td&gt;      &lt;td&gt;@fat&lt;/td&gt;    &lt;/tr&gt;    &lt;tr&gt;      &lt;th scope=&quot;row&quot;&gt;3&lt;/th&gt;      &lt;td colspan=&quot;2&quot;&gt;Larry the Bird&lt;/td&gt;      &lt;td&gt;@twitter&lt;/td&gt;    &lt;/tr&gt;  &lt;/tbody&gt;&lt;/table&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Variants
                                    </h3>
                                    <p>
                                        Use contextual classes to color tables, table rows or individual cells.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table small">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        Class
                                                    </th>
                                                    <th scope="col">
                                                        Heading
                                                    </th>
                                                    <th scope="col">
                                                        Heading
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        Default
                                                    </th>
                                                    <td>
                                                        Cell
                                                    </td>
                                                    <td>
                                                        Cell
                                                    </td>
                                                </tr>
                                                <tr class="table-primary">
                                                    <th scope="row">
                                                        Primary
                                                    </th>
                                                    <td>
                                                        Cell
                                                    </td>
                                                    <td>
                                                        Cell
                                                    </td>
                                                </tr>
                                                <tr class="table-secondary">
                                                    <th scope="row">
                                                        Secondary
                                                    </th>
                                                    <td>
                                                        Cell
                                                    </td>
                                                    <td>
                                                        Cell
                                                    </td>
                                                </tr>
                                                <tr class="table-success">
                                                    <th scope="row">
                                                        Success
                                                    </th>
                                                    <td>
                                                        Cell
                                                    </td>
                                                    <td>
                                                        Cell
                                                    </td>
                                                </tr>
                                                <tr class="table-danger">
                                                    <th scope="row">
                                                        Danger
                                                    </th>
                                                    <td>
                                                        Cell
                                                    </td>
                                                    <td>
                                                        Cell
                                                    </td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <th scope="row">
                                                        Warning
                                                    </th>
                                                    <td>
                                                        Cell
                                                    </td>
                                                    <td>
                                                        Cell
                                                    </td>
                                                </tr>
                                                <tr class="table-info">
                                                    <th scope="row">
                                                        Info
                                                    </th>
                                                    <td>
                                                        Cell
                                                    </td>
                                                    <td>
                                                        Cell
                                                    </td>
                                                </tr>
                                                <tr class="table-light">
                                                    <th scope="row">
                                                        Light
                                                    </th>
                                                    <td>
                                                        Cell
                                                    </td>
                                                    <td>
                                                        Cell
                                                    </td>
                                                </tr>
                                                <tr class="table-dark">
                                                    <th scope="row">
                                                        Dark
                                                    </th>
                                                    <td>
                                                        Cell
                                                    </td>
                                                    <td>
                                                        Cell
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-2" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-2">&lt;!-- On tables --&gt;&lt;table class=&quot;table-primary&quot;&gt;...&lt;/table&gt;&lt;table class=&quot;table-secondary&quot;&gt;...&lt;/table&gt;&lt;table class=&quot;table-success&quot;&gt;...&lt;/table&gt;&lt;table class=&quot;table-danger&quot;&gt;...&lt;/table&gt;&lt;table class=&quot;table-warning&quot;&gt;...&lt;/table&gt;&lt;table class=&quot;table-info&quot;&gt;...&lt;/table&gt;&lt;table class=&quot;table-light&quot;&gt;...&lt;/table&gt;&lt;table class=&quot;table-dark&quot;&gt;...&lt;/table&gt;&lt;!-- On rows --&gt;&lt;tr class=&quot;table-primary&quot;&gt;...&lt;/tr&gt;&lt;tr class=&quot;table-secondary&quot;&gt;...&lt;/tr&gt;&lt;tr class=&quot;table-success&quot;&gt;...&lt;/tr&gt;&lt;tr class=&quot;table-danger&quot;&gt;...&lt;/tr&gt;&lt;tr class=&quot;table-warning&quot;&gt;...&lt;/tr&gt;&lt;tr class=&quot;table-info&quot;&gt;...&lt;/tr&gt;&lt;tr class=&quot;table-light&quot;&gt;...&lt;/tr&gt;&lt;tr class=&quot;table-dark&quot;&gt;...&lt;/tr&gt;&lt;!-- On cells (`td` or `th`) --&gt;&lt;tr&gt;  &lt;td class=&quot;table-primary&quot;&gt;...&lt;/td&gt;  &lt;td class=&quot;table-secondary&quot;&gt;...&lt;/td&gt;  &lt;td class=&quot;table-success&quot;&gt;...&lt;/td&gt;  &lt;td class=&quot;table-danger&quot;&gt;...&lt;/td&gt;  &lt;td class=&quot;table-warning&quot;&gt;...&lt;/td&gt;  &lt;td class=&quot;table-info&quot;&gt;...&lt;/td&gt;  &lt;td class=&quot;table-light&quot;&gt;...&lt;/td&gt;  &lt;td class=&quot;table-dark&quot;&gt;...&lt;/td&gt;&lt;/tr&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Striped rows
                                    </h3>
                                    <p>
                                        Use <code> .table-striped</code> to add zebra-striping to any table row within the <code>tbody</code>.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped small">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td colspan="2">
                                                        Larry the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <h4 class="mt-5">
                                        Striped columns
                                    </h4>
                                    <p>
                                        Use <code>.table-striped-columns </code> to add zebra-striping to any table column.
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-striped-columns small">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td colspan="2">
                                                        Larry the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <h4 class="mt-5">
                                        Dark
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-dark table-striped small">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td colspan="2">
                                                        Larry the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <h4 class="mt-5">
                                        Dark columns
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-dark table-striped-columns small">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td colspan="2">
                                                        Larry the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <h4 class="mt-5">
                                        Table Success
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-success table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td colspan="2">
                                                        Larry the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            table striped
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-3" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-3">&lt;table class=&quot;table table-striped&quot;&gt;  ...&lt;/table&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            table- striped columns
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-4" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-4">&lt;table class=&quot;table table-striped-columns&quot;&gt;  ...&lt;/table&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            dark striped
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-5" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-5">&lt;table class=&quot;table table-dark table-striped&quot;&gt;  ...&lt;/table&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            dark striped columns
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-6" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-6">&lt;table class=&quot;table table-dark table-striped-columns&quot;&gt;  ...&lt;/table&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            success
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-7" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-7">&lt;table class=&quot;table table-success table-striped&quot;&gt;  ...&lt;/table&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Hoverable
                                    </h3>
                                    <p>
                                        Add <code>.table-hover</code> to enable a hover state on table
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover small">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td colspan="2">
                                                        Larry the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-8" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-8">&lt;table class=&quot;table table-hover&quot;&gt;  ...&lt;/table&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Table borders
                                    </h3>
                                    <p>
                                        Add <code>.table-bordered</code> for borders on all sides of the table and cells.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered small">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td colspan="2">
                                                        Larry the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            HTML
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-9" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-9">&lt;table class=&quot;table table-bordered&quot;&gt;  ...&lt;/table&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Table head
                                    </h3>
                                    <p>
                                        Similar to tables and dark tables, use the modifier classes <code>.table-light</code> or <code>.table-dark </code> to make <code>thead</code> s appear light or dark gray.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table small">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td>
                                                        Larry
                                                    </td>
                                                    <td>
                                                        the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive pt-5">
                                        <table class="table small">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td>
                                                        Larry
                                                    </td>
                                                    <td>
                                                        the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            light
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-10" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-10">&lt;table class=&quot;table&quot;&gt;  &lt;thead class=&quot;table-light&quot;&gt;    ...  &lt;/thead&gt;  &lt;tbody&gt;    ...  &lt;/tbody&gt;&lt;/table&gt;</code></pre>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            dark
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-11" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-11">&lt;table class=&quot;table&quot;&gt;  &lt;thead class=&quot;table-dark&quot;&gt;    ...  &lt;/thead&gt;  &lt;tbody&gt;    ...  &lt;/tbody&gt;&lt;/table&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content wide-md">
                                    <h3 class="nk-block-title">
                                        Sizing
                                    </h3>
                                    <p>
                                        Add <code>.table-sm</code> to make any <code>.table</code> more compact by cutting all cell <code>padding</code> in half.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">
                                                        #
                                                    </th>
                                                    <th scope="col">
                                                        First
                                                    </th>
                                                    <th scope="col">
                                                        Last
                                                    </th>
                                                    <th scope="col">
                                                        Handle
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        1
                                                    </th>
                                                    <td>
                                                        Mark
                                                    </td>
                                                    <td>
                                                        Otto
                                                    </td>
                                                    <td>
                                                        @mdo
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        2
                                                    </th>
                                                    <td>
                                                        Jacob
                                                    </td>
                                                    <td>
                                                        Thornton
                                                    </td>
                                                    <td>
                                                        @fat
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        3
                                                    </th>
                                                    <td>
                                                        Larry
                                                    </td>
                                                    <td>
                                                        the Bird
                                                    </td>
                                                    <td>
                                                        @twitter
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex align-items-center code-toolbar py-2">
                                        <div class="overline-title">
                                            html
                                        </div>
                                        <div class="js-copy-wrap ms-auto">
                                            <button data-clipboard-target="#code-example-12" class="js-copy text-light" type="button"></button>
                                        </div>
                                    </div>
                                    <pre class="code-preview"><code class="language-html" id="code-example-12">&lt;table class=&quot;table table-sm&quot;&gt;  ...&lt;/table&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
