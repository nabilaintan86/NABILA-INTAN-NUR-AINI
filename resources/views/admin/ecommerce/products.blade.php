@extends('layouts.admin')
@section('title', 'Products - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        Products
                                    </h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/ecommerce/products') }}#">ecommerce</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Products
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                        <li>
                                            <a href="{{ url('/admin/ecommerce/add-product') }}" class="btn btn-primary btn-md d-md-none"><em class="icon ni ni-plus"></em><span>Add</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/ecommerce/add-product') }}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Product</span></a>
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
                                                <span class="overline-title">products</span>
                                            </th>
                                            <th class="tb-col tb-col-md">
                                                <span class="overline-title">sku</span>
                                            </th>
                                            <th class="tb-col">
                                                <span class="overline-title">qty</span>
                                            </th>
                                            <th class="tb-col tb-col-md">
                                                <span class="overline-title">price</span>
                                            </th>
                                            <th class="tb-col tb-col-md">
                                                <span class="overline-title">rating</span>
                                            </th>
                                            <th class="tb-col">
                                                <span class="overline-title">Status</span>
                                            </th>
                                            <th class="tb-col tb-col-end" data-sortable="false">
                                                <span class="overline-title">action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="tb-col tb-col-check">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="pid1">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/a.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Pink Fitness Tracker</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0533009</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>42</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$126.00</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Published</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/b.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Pool Party Drink Holder</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253800</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>12</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$35.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-info-soft">Scheduled</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/c.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">AliExpress Fitness Trackers</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253804</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>15</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$135.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-danger-soft">Inactive</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/d.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">iPhone 7 Headphones</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253807</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>13</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$13.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-danger-soft">Inactive</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/e.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Purple Blue Gradient iPhone Case</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253808</span>
                                            </td>
                                            <td class="tb-col">
                                                <div class="d-flex align-items-center gap g-1">
                                                    <div class="gap-col">
                                                        <span class="fw-semibold text-danger">0</span>
                                                    </div>
                                                    <div class="gap-col">
                                                        <span class="badge text-bg-danger-soft">Sold out</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$55.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Published</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/a.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Wireless Waterproof Speaker</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253808</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>55</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$135.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Published</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/a.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Black Headphones</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0533009</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>42</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$126.00</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Published</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/b.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Flared Shift Dress</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253800</span>
                                            </td>
                                            <td class="tb-col">
                                                <div class="d-flex align-items-center gap g-1">
                                                    <div class="gap-col">
                                                        <span class="fw-semibold text-warning">4</span>
                                                    </div>
                                                    <div class="gap-col">
                                                        <span class="badge text-bg-warning-soft">Low stock</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$35.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-info-soft">Scheduled</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/c.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Nio Extended Camera</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253804</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>15</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$135.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-danger-soft">Inactive</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/d.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Battery Charger</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253807</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>13</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$13.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-danger-soft">Inactive</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/e.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title"> Laptop Case Bag</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253808</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>33</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$55.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Published</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/a.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Digital Camera 16x</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253808</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>55</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$135.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Published</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/a.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Casual Note Bag</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0533009</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>42</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$126.00</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-success-soft">Published</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/b.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Blue Gentle Shoes</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253800</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>12</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$35.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-info-soft">Scheduled</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/c.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Casual Blue Shoes</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253804</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>15</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$135.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-danger-soft">Inactive</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
                                                <div class="media-group">
                                                    <div class="media media-md media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/d.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-product') }}" class="title">Black Grey Headset</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>0253807</span>
                                            </td>
                                            <td class="tb-col">
                                                <span>13</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span>$13.99</span>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <ul class="rating">
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label checked">
                                                        <em class="icon ni ni-star-fill"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                    <li class="rating-label">
                                                        <em class="icon ni ni-star"></em>
                                                    </li>
                                                </ul>
                                            </td>
                                            <td class="tb-col">
                                                <span class="badge text-bg-danger-soft">Inactive</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/products') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-product') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/products') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
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
        <script src="{{ asset('admin-assets/assets/js/data-tables/data-tables.js') }}"></script>
    @endpush
