@extends('layouts.admin')

@section('title', 'DHCP Lease · RADNET NOC')

@push('styles')
<style>
    :root {
        --dhcp-accent: #4f46e5;
        --dhcp-accent-dark: #4338ca;
        --dhcp-text: #1c2b4a;
        --dhcp-muted: #6b7a99;
        --dhcp-border: #e8ecf3;
        --dhcp-surface: #ffffff;
        --dhcp-background: #f4f6fb;
    }

    body,
    .nk-main {
        background: var(--dhcp-background);
    }

    .dhcp-breadcrumb {
        margin-bottom: 18px;
    }

    .dhcp-breadcrumb .breadcrumb {
        margin-bottom: 0;
    }

    .dhcp-page {
        min-width: 0;
    }

    .dhcp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .dhcp-title-group {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .dhcp-title-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--dhcp-accent), #7c3aed);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        font-size: 20px;
        box-shadow: 0 8px 20px rgba(79, 70, 229, .28);
    }

    .dhcp-title h1 {
        margin: 0;
        color: var(--dhcp-text);
        font-size: 20px;
        font-weight: 800;
        letter-spacing: .2px;
    }

    .dhcp-title p {
        margin: 3px 0 0;
        color: var(--dhcp-muted);
        font-size: 12.5px;
        line-height: 1.5;
    }

    .dhcp-header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .dhcp-meta-chip {
        min-height: 34px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border: 1px solid var(--dhcp-border);
        border-radius: 999px;
        background: var(--dhcp-surface);
        color: var(--dhcp-muted);
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .dhcp-meta-chip strong {
        color: var(--dhcp-text);
    }

    .btn-dhcp-primary,
    .btn-dhcp-outline,
    .btn-dhcp-icon {
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        cursor: pointer;
        font-size: 12.5px;
        font-weight: 700;
        line-height: 1;
        text-decoration: none;
        transition: background .15s ease, border-color .15s ease, color .15s ease, transform .15s ease, box-shadow .15s ease;
    }

    .btn-dhcp-primary {
        border: 1px solid var(--dhcp-accent);
        background: var(--dhcp-accent);
        color: #fff;
        padding: 9px 16px;
    }

    .btn-dhcp-primary:hover {
        border-color: var(--dhcp-accent-dark);
        background: var(--dhcp-accent-dark);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, .22);
    }

    .btn-dhcp-outline,
    .btn-dhcp-icon {
        border: 1px solid var(--dhcp-border);
        background: var(--dhcp-surface);
        color: var(--dhcp-text);
        padding: 9px 16px;
    }

    .btn-dhcp-outline:hover,
    .btn-dhcp-icon:hover {
        border-color: var(--dhcp-accent);
        color: var(--dhcp-accent);
    }

    .btn-dhcp-primary:disabled,
    .btn-dhcp-outline:disabled,
    .btn-dhcp-icon:disabled {
        cursor: not-allowed;
        opacity: .65;
        transform: none;
    }

    .btn-sm {
        padding: 7px 12px;
        font-size: 11.5px;
    }

    .btn-dhcp-icon {
        width: 36px;
        height: 36px;
        padding: 0;
    }

    .btn-dhcp-icon .icon {
        font-size: 15px;
    }

    .btn-dhcp-primary.loading .ni-refresh,
    .btn-dhcp-outline.loading .ni-refresh,
    .btn-dhcp-icon.loading .ni-refresh {
        animation: dhcp-spin .8s linear infinite;
    }

    @keyframes dhcp-spin {
        to { transform: rotate(360deg); }
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 18px;
    }

    .stat-card {
        min-width: 0;
        padding: 18px 20px;
        border: 1px solid var(--dhcp-border);
        border-radius: 14px;
        background: var(--dhcp-surface);
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        margin-bottom: 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .stat-total .stat-icon { background: #eef2ff; color: var(--dhcp-accent); }
    .stat-aktif .stat-icon { background: #dcfce7; color: #15803d; }
    .stat-expired .stat-icon { background: #fee2e2; color: #dc2626; }
    .stat-unknown .stat-icon { background: #fef3c7; color: #d97706; }

    .stat-label {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 2px;
        color: var(--dhcp-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .stat-value {
        color: var(--dhcp-text);
        font-size: 26px;
        font-weight: 800;
        line-height: 1.15;
    }

    .stat-sub {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 4px;
        color: var(--dhcp-muted);
        font-size: 11.5px;
    }

    .trend-chip {
        padding: 3px 9px;
        border-radius: 999px;
        background: #eef2ff;
        color: var(--dhcp-accent);
        font-size: 10.5px;
        font-weight: 800;
        white-space: nowrap;
    }

    .trend-green { background: #dcfce7; color: #15803d; }
    .trend-red { background: #fee2e2; color: #dc2626; }
    .trend-amber { background: #fef3c7; color: #b45309; }

    .dhcp-panel {
        min-width: 0;
        margin-bottom: 18px;
        padding: 18px;
        border: 1px solid var(--dhcp-border);
        border-radius: 14px;
        background: var(--dhcp-surface);
    }

    .dhcp-panel-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 14px;
        color: var(--dhcp-text);
        font-size: 14px;
        font-weight: 800;
    }

    .count-pill {
        padding: 2px 9px;
        border-radius: 999px;
        background: #eef2ff;
        color: var(--dhcp-accent);
        font-size: 11px;
        font-weight: 700;
    }

    .filter-bar {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .filter-field {
        display: flex;
        min-width: 0;
        flex-direction: column;
        gap: 6px;
    }

    .ff-label {
        color: var(--dhcp-muted);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .filter-select,
    .filter-search {
        min-width: 150px;
        height: 38px;
        border: 1px solid var(--dhcp-border);
        border-radius: 10px;
        background-color: #fff;
        color: var(--dhcp-text);
        font-size: 12.5px;
        font-weight: 600;
    }

    .filter-select {
        padding: 0 34px 0 12px;
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7a99' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 14px;
        appearance: none;
    }

    .filter-search {
        min-width: 220px;
        padding: 0 12px;
    }

    .filter-select:focus,
    .filter-search:focus {
        border-color: var(--dhcp-accent);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .1);
        outline: 0;
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 38px;
    }

    .filter-reset {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 6px;
        color: var(--dhcp-muted);
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
    }

    .filter-reset:hover {
        color: #dc2626;
    }

    .filter-chips {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #eef1f8;
    }

    .filter-chips-label {
        color: var(--dhcp-muted);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        max-width: 100%;
        padding: 6px 9px 6px 10px;
        border: 1px solid #c7d2fe;
        border-radius: 999px;
        background: #eef2ff;
        color: #3730a3;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
    }

    .filter-chip:hover {
        border-color: #a5b4fc;
        background: #e0e7ff;
        color: #312e81;
    }

    .filter-chip strong {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .filter-chip .chip-key {
        color: #6366f1;
    }

    .dhcp-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .dhcp-table {
        width: 100%;
        min-width: 940px;
        border-collapse: collapse;
    }

    .dhcp-table thead th {
        padding: 10px 12px;
        border-bottom: 2px solid var(--dhcp-border);
        background: #f8fafc;
        color: var(--dhcp-muted);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .4px;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dhcp-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #eef1f8;
        color: var(--dhcp-text);
        font-size: 12.5px;
        vertical-align: middle;
    }

    .dhcp-table tbody tr.lease-row {
        cursor: pointer;
        transition: background .12s ease, transform .12s ease;
    }

    .dhcp-table tbody tr.lease-row:hover,
    .dhcp-table tbody tr.lease-row:hover td {
        background: #f6f7ff;
    }

    .dhcp-table tbody tr.lease-row:hover {
        transform: translateX(2px);
    }

    .dhcp-table tbody tr.lease-row:hover td:first-child {
        border-left: 3px solid #4F46E5;
        box-shadow: inset 3px 0 0 var(--dhcp-accent);
    }

    .dhcp-table tbody tr.row-expired td {
        background: #fef2f2;
    }

    .dhcp-table tbody tr.row-unknown td {
        background: #fffaf0;
    }

    .dhcp-table tbody tr.row-expired:hover td,
    .dhcp-table tbody tr.row-unknown:hover td {
        background: #f6f7ff;
    }

    .lease-host {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .hc-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        font-size: 14px;
    }

    .hc-known { background: #eef2ff; color: var(--dhcp-accent); }
    .hc-new { background: #dcfce7; color: #15803d; }
    .hc-unknown { background: #fef3c7; color: #d97706; }

    .hc-name {
        color: var(--dhcp-text);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.3;
    }

    .hc-sub {
        margin-top: 2px;
        color: var(--dhcp-muted);
        font-size: 11px;
    }

    .dev-link {
        color: var(--dhcp-accent);
        text-decoration: none;
    }

    .dev-link:hover {
        color: var(--dhcp-accent-dark);
        text-decoration: underline;
    }

    .ip-badge,
    .vendor-badge {
        display: inline-block;
        max-width: 170px;
        overflow: hidden;
        padding: 3px 9px;
        border-radius: 7px;
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ip-badge {
        font-family: 'Consolas', 'Menlo', monospace;
    }

    .mac-text,
    .router-main,
    .router-sub {
        white-space: nowrap;
    }

    .mac-text {
        color: #94a3b8;
        font-family: 'Consolas', 'Menlo', monospace;
        font-size: 11px;
    }

    .router-main {
        color: var(--dhcp-text);
        font-weight: 700;
    }

    .router-sub {
        margin-top: 2px;
        color: var(--dhcp-muted);
        font-size: 11px;
    }

    .badge-lease {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 999px;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-aktif { background: #16a34a; }
    .badge-known { background: var(--dhcp-accent); }
    .badge-expired { background: #dc2626; }
    .badge-unknown { background: #d97706; }

    .dhcp-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 14px;
    }

    .pg-info {
        color: var(--dhcp-muted);
        font-size: 12px;
        font-weight: 600;
    }

    .dhcp-footer .form-select {
        padding: 5px 28px 5px 10px;
        border-color: var(--dhcp-border);
        border-radius: 8px;
        color: var(--dhcp-text);
        font-size: 12px;
        font-weight: 700;
    }

    .bootstrap-pagination .pagination {
        justify-content: flex-end;
        flex-wrap: wrap;
        margin: 0;
    }

    .bootstrap-pagination .page-link {
        padding: 6px 12px;
        border-color: var(--dhcp-border);
        color: var(--dhcp-text);
        font-size: 12px;
        font-weight: 700;
    }

    .bootstrap-pagination .page-item.active .page-link {
        border-color: var(--dhcp-accent);
        background: var(--dhcp-accent);
    }

    .empty-state {
        padding: 30px 20px 8px;
        text-align: center;
    }

    .empty-icon {
        width: 84px;
        height: 84px;
        margin: 0 auto 18px;
        border-radius: 24px;
        background: linear-gradient(135deg, #eef2ff, #e4e9ff);
        color: var(--dhcp-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 38px;
        box-shadow: 0 10px 26px rgba(79, 70, 229, .18);
    }

    .empty-state h5 {
        margin: 0 0 8px;
        color: var(--dhcp-text);
        font-size: 17px;
        font-weight: 800;
    }

    .empty-state p {
        max-width: 600px;
        margin: 0 auto 18px;
        color: var(--dhcp-muted);
        font-size: 12.5px;
        line-height: 1.7;
    }

    .command-box {
        display: inline-flex;
        align-items: center;
        max-width: 100%;
        padding: 11px 14px;
        border-radius: 10px;
        background: #1c2b4a;
        color: #dbeafe;
        font-family: 'Consolas', 'Menlo', monospace;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
    }

    .sync-notice {
        margin-bottom: 14px;
        padding: 12px 14px;
        border: 1px solid #c7d2fe;
        border-radius: 12px;
        background: #eef2ff;
        color: #3730a3;
        font-size: 12.5px;
        font-weight: 600;
        line-height: 1.6;
    }

    .no-result {
        padding: 30px 16px;
        color: var(--dhcp-muted);
        font-size: 13px;
        font-weight: 600;
        text-align: center;
    }

    .no-result strong {
        color: var(--dhcp-text);
    }

    .no-result .btn-dhcp-outline {
        margin-top: 14px;
    }

    .modal-backdrop-dhcp {
        position: fixed;
        inset: 0;
        z-index: 1040;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(15, 23, 42, .45);
        animation: dhcp-fade-in 150ms ease both;
    }

    .modal-backdrop-dhcp.hidden {
        display: none;
    }

    .modal-box-dhcp {
        position: relative;
        z-index: 1050;
        width: 100%;
        max-width: 620px;
        overflow: hidden;
        border-radius: 16px;
        background: #fff;
        opacity: 1;
        pointer-events: auto;
        box-shadow: 0 24px 60px rgba(28, 43, 74, .28);
        animation: dhcp-slide-up 220ms cubic-bezier(.2,.8,.2,1) both;
    }

    @keyframes dhcp-fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes dhcp-slide-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-head-dhcp,
    .modal-foot-dhcp,
    .modal-body-dhcp {
        flex-shrink: 0;
    }

    .modal-head-dhcp {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 20px;
        color: #fff;
    }

    .modal-head-dhcp.head-status-aktif { background: #16a34a; }
    .modal-head-dhcp.head-status-expired { background: #dc2626; }
    .modal-head-dhcp.head-status-unknown { background: #d97706; }

    .modal-head-dhcp .mh-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        background: rgba(255, 255, 255, .2);
        font-size: 19px;
    }

    .modal-head-dhcp .mh-title {
        display: flex;
        min-width: 0;
        flex-direction: column;
    }

    .modal-head-dhcp .mh-title strong {
        overflow: hidden;
        font-size: 16px;
        font-weight: 800;
        line-height: 1.3;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .modal-head-dhcp .mh-title span {
        overflow: hidden;
        font-family: 'Consolas', 'Menlo', monospace;
        font-size: 12px;
        opacity: .85;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .modal-close-dhcp {
        width: 32px;
        height: 32px;
        margin-left: auto;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #fff;
        cursor: pointer;
        font-size: 20px;
        opacity: .85;
        transition: opacity .15s ease, background .15s ease;
    }

    .modal-close-dhcp:hover {
        background: rgba(255, 255, 255, .16);
        opacity: 1;
    }

    .modal-body-dhcp {
        max-height: 65vh;
        overflow-y: auto;
        padding: 20px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .detail-item {
        min-width: 0;
        padding: 11px 13px;
        border: 1px solid #eef1f8;
        border-radius: 12px;
        background: #f8fafc;
    }

    .detail-item.full {
        grid-column: 1 / -1;
    }

    .di-label {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 4px;
        color: var(--dhcp-muted);
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .di-value {
        color: var(--dhcp-text);
        font-size: 13px;
        font-weight: 700;
        word-break: break-word;
    }

    .di-value.mono {
        font-family: 'Consolas', 'Menlo', monospace;
        font-size: 12.5px;
        word-break: break-all;
    }

    .di-value.muted {
        color: var(--dhcp-muted);
        font-weight: 600;
    }

    .modal-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .modal-foot-dhcp {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 14px 20px;
        border-top: 1px solid #eef1f8;
        flex-wrap: wrap;
    }

    .modal-foot-dhcp .device-link {
        margin-right: auto;
    }

    .toast-stack {
        position: fixed;
        top: 18px;
        right: 18px;
        z-index: 2100;
        display: flex;
        max-width: 360px;
        flex-direction: column;
        gap: 10px;
    }

    .toast-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border: 1px solid var(--dhcp-border);
        border-left: 4px solid var(--dhcp-accent);
        border-radius: 12px;
        background: #fff;
        color: var(--dhcp-text);
        box-shadow: 0 12px 30px rgba(28, 43, 74, .18);
        font-size: 12.5px;
        font-weight: 600;
        animation: dhcp-toast-in .3s ease both;
    }

    .toast-item.success { border-left-color: #16a34a; }
    .toast-item.error { border-left-color: #dc2626; }
    .toast-item .toast-icon { color: var(--dhcp-accent); font-size: 16px; }
    .toast-item.success .toast-icon { color: #16a34a; }
    .toast-item.error .toast-icon { color: #dc2626; }

    @keyframes dhcp-toast-in {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @media (max-width: 1100px) {
        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .dhcp-header {
            align-items: flex-start;
        }

        .dhcp-header-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .dhcp-panel {
            padding: 14px;
        }

        .filter-bar {
            align-items: stretch;
        }

        .filter-field {
            flex: 1 1 calc(50% - 6px);
        }

        .filter-field.search-field {
            flex-basis: 100%;
        }

        .filter-select,
        .filter-search {
            width: 100%;
            min-width: 0;
        }

        .filter-actions {
            flex: 1 1 100%;
        }

        .dhcp-table {
            min-width: 680px;
        }

        .dhcp-table thead th,
        .dhcp-table tbody td {
            padding: 10px 8px;
        }

        .dhcp-table .col-mac,
        .dhcp-table .col-router,
        .dhcp-table .col-lease,
        .dhcp-table .col-expires {
            display: none;
        }

        .dhcp-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .bootstrap-pagination .pagination {
            justify-content: flex-start;
        }
    }

    @media (max-width: 575.98px) {
        .kpi-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .stat-card {
            padding: 14px;
        }

        .dhcp-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            font-size: 17px;
        }

        .dhcp-title h1 {
            font-size: 18px;
        }

        .dhcp-title p {
            font-size: 11.5px;
        }

        .filter-field {
            flex-basis: 100%;
        }

        .dhcp-header-actions {
            gap: 6px;
        }

        .dhcp-meta-chip {
            font-size: 10px;
        }

        .modal-backdrop-dhcp {
            padding: 10px;
        }

        .modal-body-dhcp {
            padding: 14px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-item.full {
            grid-column: auto;
        }

        .modal-foot-dhcp {
            align-items: stretch;
            flex-direction: column;
        }

        .modal-foot-dhcp .device-link {
            width: 100%;
            margin-right: 0;
        }

        .modal-foot-dhcp .btn-dhcp-outline {
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .btn-dhcp-primary,
        .btn-dhcp-outline,
        .btn-dhcp-icon,
        .dhcp-table tbody tr.lease-row,
        .toast-item,
        .modal-backdrop-dhcp,
        .modal-box-dhcp {
            animation: none;
            transition: none;
        }
    }
</style>
@endpush

@section('content')
<div class="nk-block dhcp-page">
    <div class="dhcp-breadcrumb">
        <nav aria-label="Breadcrumb">
            <ol class="breadcrumb breadcrumb-arrow mb-0">
                <li class="breadcrumb-item active" aria-current="page">DHCP Lease</li>
            </ol>
        </nav>
    </div>

    <div class="dhcp-header">
        <div class="dhcp-title-group">
            <div class="dhcp-title-icon" aria-hidden="true"><em class="icon ni ni-wifi"></em></div>
            <div class="dhcp-title">
                <h1>DHCP LEASE</h1>
                <p>Daftar perangkat yang mendapat alamat IP dari DHCP, diidentifikasi terhadap database device resmi RADNET.</p>
            </div>
        </div>
        <div class="dhcp-header-actions">
            <button type="button" class="btn-dhcp-outline btn-sm" data-reload>
                <em class="icon ni ni-refresh"></em>
                <span>Muat Ulang</span>
            </button>
            <button type="button" class="btn-dhcp-icon" data-reload aria-label="Refresh">
                <em class="icon ni ni-refresh"></em>
            </button>
            <span class="dhcp-meta-chip">
                <em class="icon ni ni-database"></em>
                <strong>{{ number_format($knownDeviceCount) }}</strong> Known Device
            </span>
            <span class="dhcp-meta-chip">
                <em class="icon ni ni-clock"></em>
                Diperbarui {{ $lastUpdatedAt }}
            </span>
        </div>
    </div>

    @if($hasData)
        <div class="kpi-grid">
            @php
                $shareAktif = $kpi['total'] > 0 ? round($kpi['aktif'] / $kpi['total'] * 100) : 0;
                $shareExpired = $kpi['total'] > 0 ? round($kpi['expired'] / $kpi['total'] * 100) : 0;
                $shareUnknown = $kpi['total'] > 0 ? round($kpi['unknown'] / $kpi['total'] * 100) : 0;
            @endphp
            <div class="stat-card stat-total">
                <div class="stat-icon"><em class="icon ni ni-list"></em></div>
                <div class="stat-label">Total Lease</div>
                <div class="stat-value">{{ number_format($kpi['total']) }}</div>
                <div class="stat-sub">
                    <span>Semua lease terpantau</span>
                    <span class="trend-chip">{{ number_format($kpi['aktif'] + $kpi['expired']) }} dikenali</span>
                </div>
            </div>
            <div class="stat-card stat-aktif">
                <div class="stat-icon"><em class="icon ni ni-check-circle"></em></div>
                <div class="stat-label">Aktif</div>
                <div class="stat-value">{{ number_format($kpi['aktif']) }}</div>
                <div class="stat-sub"><span>Lease masih berlaku</span><span class="trend-chip trend-green">{{ $shareAktif }}%</span></div>
            </div>
            <div class="stat-card stat-expired">
                <div class="stat-icon"><em class="icon ni ni-alert-circle"></em></div>
                <div class="stat-label">Expired</div>
                <div class="stat-value">{{ number_format($kpi['expired']) }}</div>
                <div class="stat-sub"><span>Melewati masa berlaku</span><span class="trend-chip trend-red">{{ $shareExpired }}%</span></div>
            </div>
            <div class="stat-card stat-unknown">
                <div class="stat-icon"><em class="icon ni ni-question"></em></div>
                <div class="stat-label">Unknown</div>
                <div class="stat-value">{{ number_format($kpi['unknown']) }}</div>
                <div class="stat-sub"><span>Perangkat tidak dikenali</span><span class="trend-chip trend-amber">{{ $shareUnknown }}%</span></div>
            </div>
        </div>
    @endif

    @if($hasData)
        <div class="dhcp-panel">
            <form method="GET" action="{{ route('admin.dhcp-lease') }}" class="filter-bar" id="dhcpFilterForm">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <div class="filter-field">
                    <label class="ff-label" for="statusFilter">Status</label>
                    <select id="statusFilter" name="status" class="filter-select">
                        <option value="all" @selected($status === 'all')>Semua Status</option>
                        <option value="aktif" @selected($status === 'aktif')>Aktif</option>
                        <option value="expired" @selected($status === 'expired')>Expired</option>
                        <option value="unknown" @selected($status === 'unknown')>Unknown</option>
                    </select>
                </div>
                <div class="filter-field">
                    <label class="ff-label" for="locationFilter">Lokasi / POP</label>
                    <select id="locationFilter" name="location" class="filter-select">
                        <option value="">Semua Lokasi / POP</option>
                        @foreach($locations as $locationOption)
                            <option value="{{ $locationOption }}" @selected($location === $locationOption)>{{ $locationOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field">
                    <label class="ff-label" for="vendorFilter">Vendor</label>
                    <select id="vendorFilter" name="vendor" class="filter-select">
                        <option value="">Semua Vendor</option>
                        @foreach($vendors as $vendorOption)
                            <option value="{{ $vendorOption }}" @selected($vendor === $vendorOption)>{{ $vendorOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field">
                    <label class="ff-label" for="routerFilter">Router</label>
                    <select id="routerFilter" name="router" class="filter-select">
                        <option value="">Semua Router</option>
                        @foreach($routers as $routerOption)
                            <option value="{{ $routerOption }}" @selected($router === $routerOption)>{{ $routerOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field search-field">
                    <label class="ff-label" for="hostnameSearch">Hostname</label>
                    <input id="hostnameSearch" type="search" name="q" value="{{ $q }}" class="filter-search" placeholder="Cari nama device...">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-dhcp-primary">
                        <em class="icon ni ni-search"></em>
                        Terapkan
                    </button>
                    <a href="{{ route('admin.dhcp-lease') }}" class="filter-reset">
                        <em class="icon ni ni-refresh"></em>
                        Reset Filter
                    </a>
                </div>
            </form>

            @if(count($activeFilters) > 0)
                <div class="filter-chips" aria-label="Filter aktif">
                    <span class="filter-chips-label">Filter aktif:</span>
                    @foreach($activeFilters as $filter)
                        <a href="{{ $filter['url'] }}" class="filter-chip" title="Hapus filter {{ $filter['label'] }}">
                            <span class="chip-key">{{ $filter['label'] }}:</span>
                            <strong>{{ $filter['value'] }}</strong>
                            <em class="icon ni ni-cross" aria-hidden="true"></em>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @if(!$hasData)
        <div class="dhcp-panel">
            @if($syncNotice)
                <div class="sync-notice">
                    <em class="icon ni ni-info me-1"></em>{{ $syncNotice }}
                </div>
            @endif
            <div class="empty-state">
                <div class="empty-icon" aria-hidden="true"><em class="icon ni ni-wifi"></em></div>
                <h5>Belum Ada Data Lease DHCP</h5>
                <p>Siapkan file <strong>devices.csv</strong> di folder <strong>storage/app/dhcp/</strong>, lalu jalankan perintah berikut dari project.</p>
                <div class="command-box">php artisan dhcp:import</div>
            </div>
        </div>
    @elseif($leases->total() === 0)
        <div class="dhcp-panel">
            <div class="no-result">
                <em class="icon ni ni-inbox" style="font-size:20px;display:block;margin-bottom:8px"></em>
                Tidak ada lease yang cocok dengan filter aktif.
                <br>
                <a href="{{ route('admin.dhcp-lease') }}" class="btn-dhcp-outline btn-sm">Reset Filter</a>
            </div>
        </div>
    @else
        <div class="dhcp-panel">
            <div class="dhcp-panel-title">
                <em class="icon ni ni-list"></em>
                Daftar DHCP Lease
                <span class="count-pill">{{ number_format($leases->total()) }}</span>
            </div>
            <div class="dhcp-table-wrap">
                <table class="dhcp-table">
                    <thead>
                        <tr>
                            <th>Hostname</th>
                            <th>IP Address</th>
                            <th class="col-mac">MAC Address</th>
                            <th>Vendor</th>
                            <th class="col-router">Router / Lokasi</th>
                            <th class="col-lease">Lease Time</th>
                            <th class="col-expires">Expires</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leases as $lease)
                            @php
                                $statusLabel = $lease->statusLabel();
                                $duration = $lease->leaseDurationLabel();
                                $expires = $lease->expiresLabel();
                                $isKnown = $lease->isKnownDevice();
                                $deviceUrl = $isKnown ? route('admin.device.detail', $lease->device_id) : '';
                                $rowClass = $statusLabel === 'expired' ? 'row-expired' : ($statusLabel === 'unknown' ? 'row-unknown' : '');
                                $hostIcon = $isKnown ? 'ni-wifi' : ($lease->classification === 'new' ? 'ni-plus' : 'ni-question');
                                $hostIconClass = $isKnown ? 'hc-known' : ($lease->classification === 'new' ? 'hc-new' : 'hc-unknown');
                                $hostName = $lease->hostname ?: ($isKnown ? ($lease->device?->name ?: 'Known Device') : 'Unknown Device');
                                $routerMain = $lease->router ?: ($lease->location ?: '—');
                                $routerSub = ($lease->router && $lease->location && $lease->location !== $lease->router) ? $lease->location : '';
                            @endphp
                            <tr class="lease-row {{ $rowClass }}" tabindex="0" role="button" title="Klik untuk lihat detail"
                                data-hostname="{{ $lease->hostname ?: '—' }}"
                                data-client-id="{{ $lease->client_id ?: '—' }}"
                                data-mac="{{ $lease->mac_address ?: '—' }}"
                                data-ip="{{ $lease->ip_address }}"
                                data-vendor="{{ $lease->vendor ?: '—' }}"
                                data-router="{{ $lease->router ?: '—' }}"
                                data-lokasi="{{ $lease->location ?: '—' }}"
                                data-start="{{ $lease->lease_start ? $lease->lease_start->format('d M Y H:i') : '—' }}"
                                data-end="{{ $lease->lease_end ? $lease->lease_end->format('d M Y H:i') : '—' }}"
                                data-first="{{ $lease->first_seen ? $lease->first_seen->format('d M Y H:i') : '—' }}"
                                data-last="{{ $lease->last_seen ? $lease->last_seen->format('d M Y H:i') : '—' }}"
                                data-source="{{ $lease->source ?: '—' }}"
                                data-comment="{{ $lease->comment ?: '—' }}"
                                data-status="{{ $statusLabel }}"
                                data-class="{{ $lease->classification }}"
                                data-device-url="{{ $deviceUrl }}">
                                <td>
                                    <div class="lease-host">
                                        <div class="hc-icon {{ $hostIconClass }}"><em class="icon ni {{ $hostIcon }}"></em></div>
                                        <div>
                                            <div class="hc-name">
                                                @if($isKnown)
                                                    <a href="{{ $deviceUrl }}" class="dev-link">{{ $hostName }}</a>
                                                @else
                                                    {{ $hostName }}
                                                @endif
                                            </div>
                                            <div class="hc-sub">{{ $lease->classification === 'known' ? 'Known Device' : ($lease->classification === 'new' ? 'New Device' : 'Tidak Dikenali') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="ip-badge">{{ $lease->ip_address }}</span></td>
                                <td class="col-mac"><span class="mac-text">{{ $lease->mac_address ?: '—' }}</span></td>
                                <td><span class="vendor-badge">{{ $lease->vendor ?: '—' }}</span></td>
                                <td class="col-router">
                                    <div class="router-main">{{ $routerMain }}</div>
                                    @if($routerSub !== '')
                                        <div class="router-sub">{{ $routerSub }}</div>
                                    @endif
                                </td>
                                <td class="col-lease"><strong>{{ $duration ?: '—' }}</strong></td>
                                <td class="col-expires">
                                    <span style="white-space:nowrap;font-weight:600;color:{{ $statusLabel === 'expired' ? '#dc2626' : '#475569' }}">{{ $expires ?: '—' }}</span>
                                </td>
                                <td><span class="badge-lease badge-{{ $statusLabel }}">{{ $statusLabel === 'aktif' ? 'Aktif' : ($statusLabel === 'expired' ? 'Expired' : 'Unknown') }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="dhcp-footer">
                <form method="GET" action="{{ route('admin.dhcp-lease') }}" id="perPageForm" class="d-flex align-items-center gap-2 flex-wrap">
                    @if($status !== 'all')
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    @if($location !== '')
                        <input type="hidden" name="location" value="{{ $location }}">
                    @endif
                    @if($vendor !== '')
                        <input type="hidden" name="vendor" value="{{ $vendor }}">
                    @endif
                    @if($router !== '')
                        <input type="hidden" name="router" value="{{ $router }}">
                    @endif
                    @if($q !== '')
                        <input type="hidden" name="q" value="{{ $q }}">
                    @endif
                    <select name="per_page" class="form-select" aria-label="Jumlah data per halaman" onchange="document.getElementById('perPageForm').submit()">
                        @foreach($perPageOptions as $option)
                            <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }} / halaman</option>
                        @endforeach
                    </select>
                    <span class="pg-info">Menampilkan {{ $leases->firstItem() ?: 0 }}–{{ $leases->lastItem() ?: 0 }} dari {{ number_format($leases->total()) }}</span>
                </form>
                <div class="bootstrap-pagination">{{ $leases->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    @endif
</div>

<div class="modal-backdrop-dhcp hidden" id="leaseModal" aria-hidden="true">
    <div class="modal-box-dhcp" role="dialog" aria-modal="true" aria-labelledby="leaseModalTitle">
        <div class="modal-head-dhcp head-status-unknown" id="leaseModalHead">
            <div class="mh-icon" aria-hidden="true"><em class="icon ni ni-wifi"></em></div>
            <div class="mh-title">
                <strong id="leaseModalTitle">Detail Lease</strong>
                <span id="leaseModalMac">—</span>
            </div>
            <button type="button" class="modal-close-dhcp" data-close-modal aria-label="Tutup">
                <em class="icon ni ni-cross"></em>
            </button>
        </div>
        <div class="modal-body-dhcp">
            <div class="detail-grid">
                <div class="detail-item full">
                    <div class="di-label"><em class="icon ni ni-desktop"></em> Hostname</div>
                    <div class="di-value mono" id="leaseModalHostname">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-key"></em> Client ID</div>
                    <div class="di-value mono" id="leaseModalClientId">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-tag"></em> MAC Address</div>
                    <div class="di-value mono" id="leaseModalMacValue">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-globe"></em> IP Address</div>
                    <div class="di-value mono" id="leaseModalIp">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-box"></em> Vendor</div>
                    <div class="di-value" id="leaseModalVendor">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-server"></em> Router</div>
                    <div class="di-value" id="leaseModalRouter">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-map-pin"></em> Lokasi</div>
                    <div class="di-value" id="leaseModalLocation">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-arrow-left-circle"></em> Lease Start</div>
                    <div class="di-value mono" id="leaseModalStart">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-arrow-right-circle"></em> Lease End</div>
                    <div class="di-value mono" id="leaseModalEnd">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-clock"></em> First Seen</div>
                    <div class="di-value mono" id="leaseModalFirst">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-refresh"></em> Last Seen</div>
                    <div class="di-value mono" id="leaseModalLast">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label"><em class="icon ni ni-file-text"></em> Source</div>
                    <div class="di-value" id="leaseModalSource">—</div>
                </div>
                <div class="detail-item full">
                    <div class="di-label"><em class="icon ni ni-text"></em> Comment</div>
                    <div class="di-value muted" id="leaseModalComment">—</div>
                </div>
                <div class="detail-item full">
                    <div class="di-label"><em class="icon ni ni-activity"></em> Status</div>
                    <div class="modal-badges" id="leaseModalBadges"></div>
                </div>
            </div>
        </div>
        <div class="modal-foot-dhcp">
            <a href="#" class="btn-dhcp-primary btn-sm device-link" id="leaseModalDeviceLink">
                <em class="icon ni ni-external"></em>
                Lihat Device
            </a>
            <button type="button" class="btn-dhcp-outline btn-sm" id="leaseModalCopyMac">
                <em class="icon ni ni-clipboard"></em>
                Copy MAC
            </button>
            <button type="button" class="btn-dhcp-outline btn-sm" data-close-modal>Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        'use strict';

        var modal = document.getElementById('leaseModal');
        var modalHead = document.getElementById('leaseModalHead');
        var modalTitle = document.getElementById('leaseModalTitle');
        var modalDeviceLink = document.getElementById('leaseModalDeviceLink');
        var modalCopyMac = document.getElementById('leaseModalCopyMac');
        var activeRow = null;
        var toastStack = document.createElement('div');
        toastStack.className = 'toast-stack';
        document.body.appendChild(toastStack);

        function showToast(message, type) {
            var item = document.createElement('div');
            var icon = document.createElement('em');
            item.className = 'toast-item ' + (type || 'info');
            icon.className = 'icon ni toast-icon ' + (type === 'success' ? 'ni-check-circle' : (type === 'error' ? 'ni-alert-circle' : 'ni-info'));
            var text = document.createElement('span');
            text.textContent = message;
            item.appendChild(icon);
            item.appendChild(text);
            toastStack.appendChild(item);
            window.setTimeout(function () { item.style.opacity = '0'; }, 3600);
            window.setTimeout(function () { item.remove(); }, 4000);
        }

        function copyText(text) {
            if (!text || text === '—') {
                return Promise.reject(new Error('empty'));
            }
            if (navigator.clipboard && navigator.clipboard.writeText) {
                return navigator.clipboard.writeText(text);
            }
            return new Promise(function (resolve, reject) {
                var textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    resolve();
                } catch (error) {
                    reject(error);
                } finally {
                    textarea.remove();
                }
            });
        }

        function setText(id, value) {
            var element = document.getElementById(id);
            if (element) element.textContent = value || '—';
        }

        function openModal(row) {
            if (!row) return;
            activeRow = row;
            var status = row.dataset.status || 'unknown';
            var classification = row.dataset.class || 'unknown';
            modalTitle.textContent = row.querySelector('.hc-name')?.textContent.trim() || 'Detail Lease';
            setText('leaseModalMac', row.dataset.mac);
            setText('leaseModalHostname', row.dataset.hostname);
            setText('leaseModalClientId', row.dataset.clientId);
            setText('leaseModalMacValue', row.dataset.mac);
            setText('leaseModalIp', row.dataset.ip);
            setText('leaseModalVendor', row.dataset.vendor);
            setText('leaseModalRouter', row.dataset.router);
            setText('leaseModalLocation', row.dataset.lokasi);
            setText('leaseModalStart', row.dataset.start);
            setText('leaseModalEnd', row.dataset.end);
            setText('leaseModalFirst', row.dataset.first);
            setText('leaseModalLast', row.dataset.last);
            setText('leaseModalSource', row.dataset.source);
            setText('leaseModalComment', row.dataset.comment);

            var statusLabel = status === 'aktif' ? 'Aktif' : (status === 'expired' ? 'Expired' : 'Unknown');
            var classLabel = classification === 'known' ? 'Known Device' : (classification === 'new' ? 'New Device' : 'Tidak Dikenali');
            var statusClass = status === 'aktif' ? 'badge-aktif' : (status === 'expired' ? 'badge-expired' : 'badge-unknown');
            var classClass = classification === 'known' ? 'badge-known' : (classification === 'new' ? 'badge-aktif' : 'badge-unknown');
            document.getElementById('leaseModalBadges').innerHTML = '<span class="badge-lease ' + statusClass + '">' + statusLabel + '</span><span class="badge-lease ' + classClass + '">' + classLabel + '</span>';
            modalHead.className = 'modal-head-dhcp ' + (status === 'aktif' ? 'head-status-aktif' : (status === 'expired' ? 'head-status-expired' : 'head-status-unknown'));

            var deviceUrl = row.dataset.deviceUrl || '';
            modalDeviceLink.style.display = deviceUrl ? '' : 'none';
            if (deviceUrl) modalDeviceLink.href = deviceUrl;
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            var closeButton = modal.querySelector('[data-close-modal]');
            if (closeButton) closeButton.focus();
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            activeRow = null;
        }

        document.querySelectorAll('[data-reload]').forEach(function (button) {
            button.addEventListener('click', function () {
                button.classList.add('loading');
                button.disabled = true;
                window.setTimeout(function () { window.location.reload(); }, 250);
            });
        });

        document.querySelectorAll('.lease-row').forEach(function (row) {
            row.addEventListener('click', function (event) {
                if (event.target.closest('a, button')) return;
                openModal(row);
            });
            row.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openModal(row);
                }
            });
            row.querySelectorAll('a, button').forEach(function (element) {
                element.addEventListener('click', function (event) { event.stopPropagation(); });
            });
        });

        document.querySelectorAll('[data-close-modal]').forEach(function (button) {
            button.addEventListener('click', closeModal);
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
        });

        modalCopyMac.addEventListener('click', function () {
            if (!activeRow) return;
            copyText(activeRow.dataset.mac).then(function () {
                showToast('MAC berhasil disalin.', 'success');
            }).catch(function () {
                showToast('Gagal menyalin MAC.', 'error');
            });
        });
    })();
</script>
@endpush
