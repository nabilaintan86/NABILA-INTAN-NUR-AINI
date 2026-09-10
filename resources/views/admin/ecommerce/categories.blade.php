@extends('layouts.admin')
@section('title', 'Categories - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        Categories
                                    </h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/ecommerce/categories') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/ecommerce/categories') }}#">ecommerce</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Categories
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                        <li>
                                            <a href="{{ url('/admin/ecommerce/add-category') }}" class="btn btn-primary btn-md d-md-none"><em class="icon ni ni-plus"></em><span>Add</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/ecommerce/add-category') }}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Category</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card data-table-card">
                                <table class="datatable-init table" data-nk-container="table-responsive">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="tb-col tb-col-check" data-sortable="false">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </th>
                                            <th class="tb-col">
                                                <span class="overline-title">Category</span>
                                            </th>
                                            <th class="tb-col tb-col-md">
                                                <span class="overline-title">type</span>
                                            </th>
                                            <th class="tb-col tb-col-sm">
                                                <span class="overline-title">items</span>
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
                                                    <input class="form-check-input" type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td class="tb-col">
                                                <div class="media-group">
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/f.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Household</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Decurate your home with our wide selection
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>125</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>125</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/g.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Watches</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Discover watches you've never seen before
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>24</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>24</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/h.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Headphones</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Discover a wide range of Headphone including Razer, Gamdias, Logitech headphone
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-info-soft">Sub</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span class="badge text-bg-danger-soft">Stock out</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/i.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Speakers</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Get your preferable Speakers at the best price
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>123</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-info-soft">Sub</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>123</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/j.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Toothbrush</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            New Children Toothbrush U-Shape Baby Toothbrush With Handle Silicone
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>45</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>45</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/k.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Wines</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Get the best offers on wine matched to your taste
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>23</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>23</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/l.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Cameras</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Browse B&H for a vast selection of Digital Cameras
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>12</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-info-soft">Sub</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>12</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/m.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">T-shirts</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Explore trendy collection of printed & solid t-shirts
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>12</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-info-soft">Sub</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <div class="d-flex align-items-center gap g-1">
                                                    <div class="gap-col">
                                                        <span class="text-warning">12</span>
                                                    </div>
                                                    <div class="gap-col">
                                                        <span class="badge text-bg-warning-soft">Low</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/n.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Health & Beauty</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            A great collection of Health and Beauty products online.
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>55</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>55</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/o.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Jewelry</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Find necklaces, bracelets, earrings, body jewelry, rings, anklets, and more
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>134</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>134</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/p.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Toys</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Shop your way for our amazing selection Toys
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>67</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>67</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/q.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Mobiles</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Find latest official mobile phone, smartphone, android, feature phone etc
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span class="badge text-bg-danger-soft">Stock out</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/r.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Shoes</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Shop DSW for the best athletic shoes, sneakers, boots, sandals, accessories and more.
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>4</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <div class="d-flex align-items-center gap g-1">
                                                    <div class="gap-col">
                                                        <span class="text-warning">4</span>
                                                    </div>
                                                    <div class="gap-col">
                                                        <span class="badge text-bg-warning-soft">Low</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/s.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Chairs</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Explore our extensive range of chairs for your dining room, living room and office.
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>42</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>42</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
                                                    <div class="media media-middle">
                                                        <img src="{{ asset('admin-assets/images/product/t.jpg') }}" alt="product">
                                                    </div>
                                                    <div class="media-text">
                                                        <a href="{{ url('/admin/ecommerce/edit-category') }}" class="title">Speakers</a>
                                                        <div class="text smaller d-none d-sm-block">
                                                            Get your preferable Speakers at the best price
                                                        </div>
                                                        <div class="d-sm-none">
                                                            <span>11</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="tb-col tb-col-md">
                                                <span class="badge text-bg-primary-soft">Main</span>
                                            </td>
                                            <td class="tb-col tb-col-sm">
                                                <span>11</span>
                                            </td>
                                            <td class="tb-col tb-col-end">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/ecommerce/categories') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <div class="dropdown-content py-1">
                                                            <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/edit-category') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ url('/admin/ecommerce/categories') }}"><em class="icon ni ni-eye"></em><span>View Category</span></a>
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
