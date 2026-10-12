@extends('layouts.admin')

@section('title', 'Notifikasi')

@push('styles')
<style>
    /* ── Filter bar ────────────────────────────────────── */
    .notif-filters .btn {
        min-width: 110px;
        font-weight: 500;
        font-size: .85rem;
    }
    .notif-filters .btn .notif-count {
        display: inline-block;
        min-width: 20px;
        padding: .15em .45em;
        margin-left: .35rem;
        font-size: .7rem;
        font-weight: 600;
        border-radius: 10px;
        background: rgba(255,255,255,.25);
        line-height: 1.3;
    }

    .status-guide__trigger {
        width: 100%;
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: 1rem;
        margin: 0 0 1.5rem;
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        background: #fff;
        color: #1a2233;
        font: inherit;
        text-align: left;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .status-guide__trigger:hover {
        background: #f8fafc;
        border-color: #c7d2fe;
        box-shadow: 0 6px 16px rgba(79,70,229,.08);
        transform: translateY(-1px);
    }
    .status-guide__trigger:focus-visible {
        outline: 2px solid rgba(79,70,229,.35);
        outline-offset: 2px;
    }
    .status-guide__header-icon {
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #eef0ff;
        color: #4F46E5;
        font-size: 1.1rem;
    }
    .status-guide__heading {
        flex: 1;
        min-width: 0;
    }
    .status-guide__title {
        display: block;
        margin: 0;
        font-size: .9rem;
        font-weight: 700;
        line-height: 1.3;
    }
    .status-guide__hint {
        display: block;
        margin-top: .12rem;
        color: #8094ae;
        font-size: .72rem;
        font-weight: 400;
    }
    .status-guide__chevron {
        flex-shrink: 0;
        color: #8094ae;
        font-size: .9rem;
        transition: transform .2s ease;
    }
    .status-guide__trigger:hover .status-guide__chevron,
    .status-guide__trigger:focus-visible .status-guide__chevron {
        transform: translateX(4px);
    }
    .status-guide-modal {
        z-index: 1055;
    }
    .status-guide-modal .modal-dialog {
        width: calc(100% - 2rem);
        max-width: 780px;
        margin: 1.75rem auto;
    }
    .status-guide-modal .modal-content {
        z-index: 1050;
        display: flex;
        flex-direction: column;
        max-height: 90vh;
        overflow: hidden;
        border: 0;
        border-radius: 16px;
        background: #fff;
        opacity: 1;
        pointer-events: auto;
        box-shadow: 0 24px 70px rgba(15,23,42,.24);
    }
    .status-guide-modal .modal-header,
    .status-guide-modal .modal-footer {
        flex-shrink: 0;
    }
    .status-guide-modal__header {
        display: flex;
        align-items: center;
        padding: 1.2rem 1.4rem;
        border: 0;
        background: #4F46E5;
        color: #fff;
    }
    .status-guide-modal__title {
        margin: 0;
        color: #fff;
        font-size: 1.05rem;
        font-weight: 700;
    }
    .status-guide-modal__close {
        flex-shrink: 0;
        margin-left: auto;
        background-color: transparent;
        opacity: 1;
        filter: invert(1);
    }
    .status-guide-modal__close:hover,
    .status-guide-modal__close:focus {
        opacity: .85;
    }
    .status-guide-modal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        max-height: 65vh;
        overflow-y: auto;
        padding: 1.25rem;
        background: #fff;
        overscroll-behavior: contain;
    }
    .status-guide-modal__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .85rem;
    }
    .status-guide-modal__item {
        --status-color: #4F46E5;
        --status-soft: #eef0ff;
        display: flex;
        align-items: flex-start;
        gap: .8rem;
        min-width: 0;
        height: 100%;
        padding: 1rem;
        border-left: 3px solid var(--status-color);
        border-radius: 10px;
        background: #f8fafc;
    }
    .status-guide-modal__item--online {
        --status-color: #198754;
        --status-soft: #eaf7ef;
    }
    .status-guide-modal__item--down {
        --status-color: #e8534f;
        --status-soft: #feeeee;
    }
    .status-guide-modal__item--warning {
        --status-color: #f1b332;
        --status-soft: #fff7e6;
    }
    .status-guide-modal__item--transition {
        --status-color: #4F46E5;
        --status-soft: #eef0ff;
    }
    .status-guide-modal__item-icon {
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 50%;
        background: var(--status-soft);
        color: var(--status-color);
        font-size: 1.2rem;
    }
    .status-guide-modal__item-content {
        flex: 1;
        min-width: 0;
    }
    .status-guide-modal__item-title {
        margin: 0 0 .4rem;
        color: var(--status-color);
        font-size: .9rem;
        font-weight: 700;
    }
    .status-guide-modal__description {
        color: #5e6b7b;
        font-size: .8rem;
        line-height: 1.45;
    }
    .status-guide-modal__description p {
        margin: 0 0 .3rem;
    }
    .status-guide-modal__description p:last-child {
        margin-bottom: 0;
    }
    .status-guide-modal__label {
        color: #3d4b5f;
        font-weight: 600;
    }
    .status-guide-modal__example {
        color: #8094ae;
        font-style: italic;
    }
    .status-guide-modal__note {
        margin: 1rem 0 0;
        color: #8094ae;
        font-size: .75rem;
        font-style: italic;
    }
    .status-guide-modal__footer {
        justify-content: space-between;
        gap: 1rem;
        padding: .9rem 1.25rem;
        border-top: 1px solid #e8ecf3;
        background: #fff;
    }
    .status-guide-modal__meta {
        display: flex;
        align-items: center;
        gap: .4rem;
        color: #8094ae;
        font-size: .72rem;
    }
    .status-guide-modal__done {
        flex-shrink: 0;
        min-width: 130px;
        padding: .6rem 1rem;
        border: 0;
        border-radius: 9px;
        background: #4F46E5;
        color: #fff;
        font-size: .8rem;
        font-weight: 600;
        box-shadow: 0 6px 14px rgba(79,70,229,.2);
        transition: background .18s ease, transform .18s ease, box-shadow .18s ease;
    }
    .status-guide-modal__done:hover,
    .status-guide-modal__done:focus {
        background: #4338CA;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(79,70,229,.28);
    }
    .status-guide-modal.show.fade .modal-dialog {
        animation: statusGuideModalEnter .2s ease both;
    }
    @keyframes statusGuideModalEnter {
        from {
            opacity: .98;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .notif-modal-open {
        overflow: hidden !important;
    }
    .modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 1040;
        background: rgba(15,23,42,.45);
    }
    .filter-notif-modal {
        --filter-color: #4F46E5;
        --filter-soft: #eef2ff;
        z-index: 1055;
    }
    .filter-notif-modal--down {
        --filter-color: #e8534f;
        --filter-soft: #feeeee;
    }
    .filter-notif-modal--warning {
        --filter-color: #f1b332;
        --filter-soft: #fff7e6;
    }
    .filter-notif-modal--online {
        --filter-color: #198754;
        --filter-soft: #eaf7ef;
    }
    .filter-notif-modal .modal-dialog {
        max-width: 720px;
        margin: 1.75rem auto;
    }
    .filter-notif-modal .modal-content {
        z-index: 1050;
        background: #fff;
        opacity: 1;
        border: 0;
        border-radius: 16px;
        max-height: 90vh;
        overflow: hidden;
        pointer-events: auto;
        box-shadow: 0 24px 70px rgba(15,23,42,.24);
    }
    .filter-notif-modal .modal-header,
    .filter-notif-modal .modal-footer {
        flex-shrink: 0;
    }
    .filter-notif-modal__header {
        position: relative;
        overflow: hidden;
        padding: 1.15rem 1.35rem;
        border: 0;
        background: var(--filter-color);
        color: #fff;
    }
    .filter-notif-modal__header::after {
        content: "";
        position: absolute;
        top: -54px;
        right: 44px;
        width: 150px;
        height: 150px;
        border: 24px solid rgba(255,255,255,.1);
        border-radius: 50%;
        pointer-events: none;
    }
    .filter-notif-modal__heading {
        position: relative;
        z-index: 1;
        min-width: 0;
    }
    .filter-notif-modal__eyebrow {
        display: block;
        margin-bottom: .2rem;
        color: rgba(255,255,255,.76);
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .filter-notif-modal__title {
        margin: 0;
        color: #fff;
        font-size: 1.05rem;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(15,23,42,.14);
    }
    .filter-notif-modal__close {
        position: relative;
        z-index: 2;
        flex-shrink: 0;
        width: 34px;
        height: 34px;
        padding: 0;
        background-color: transparent;
        opacity: 1;
        filter: invert(1);
        box-shadow: 0 4px 12px rgba(15,23,42,.14);
    }
    .filter-notif-modal__close::after {
        top: 50%;
        left: 50%;
        width: 14px;
        height: 14px;
        background-size: 14px 14px;
        transform: translate(-50%, -50%);
    }
    .filter-notif-modal__close:hover,
    .filter-notif-modal__close:focus {
        background-color: rgba(255,255,255,.16);
        opacity: 1;
    }
    .filter-notif-modal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        max-height: 60vh;
        overflow-y: auto;
        padding: 1rem 1.1rem;
        background: #f8fafc;
        overscroll-behavior: contain;
        scrollbar-gutter: stable;
    }
    .filter-notif-modal__list {
        display: flex;
        flex-direction: column;
        gap: .65rem;
    }
    .filter-notif-item {
        --item-color: #4F46E5;
        --item-soft: #eef2ff;
        --item-hover: #f5f3ff;
        --item-shadow: rgba(79,70,229,.12);
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        gap: .8rem;
        padding: .85rem .9rem;
        border: 1px solid #e5eaf0;
        border-left: 3px solid var(--item-color);
        border-radius: 12px;
        background: #fff;
        color: #1a2233;
        text-align: left;
        pointer-events: auto;
        transition: background .18s ease, border-color .18s ease, transform .18s ease, box-shadow .18s ease;
    }
    .filter-notif-item:hover,
    .filter-notif-item:focus-visible {
        background: var(--item-hover);
        border-color: var(--item-color);
        transform: translateX(4px);
        box-shadow: 0 7px 18px var(--item-shadow);
    }
    .filter-notif-item:focus-visible {
        outline: 2px solid var(--item-color);
        outline-offset: 1px;
    }
    .filter-notif-item--online {
        --item-color: #198754;
        --item-soft: #eaf7ef;
        --item-hover: #f2fbf6;
        --item-shadow: rgba(25,135,84,.12);
    }
    .filter-notif-item--down {
        --item-color: #e8534f;
        --item-soft: #feeeee;
        --item-hover: #fff5f5;
        --item-shadow: rgba(232,83,79,.12);
    }
    .filter-notif-item--warning {
        --item-color: #f1b332;
        --item-soft: #fff7e6;
        --item-hover: #fffaf0;
        --item-shadow: rgba(241,179,50,.14);
    }
    .filter-notif-item.is-unread::after {
        content: "";
        position: absolute;
        top: .85rem;
        right: .8rem;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }
    .filter-notif-item__indicator {
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: var(--item-soft);
        color: var(--item-color);
        font-size: 1.05rem;
    }
    .filter-notif-item__copy {
        flex: 1;
        min-width: 0;
    }
    .filter-notif-item__name {
        display: block;
        overflow: hidden;
        color: #1a2233;
        font-size: .86rem;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .filter-notif-item__ip {
        display: block;
        margin-top: .15rem;
        overflow: hidden;
        color: #8094ae;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: .72rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .filter-notif-item__meta {
        flex-shrink: 0;
        margin-left: .5rem;
        padding-right: .65rem;
        text-align: right;
    }
    .filter-notif-item.is-unread .filter-notif-item__meta {
        padding-right: 1rem;
    }
    .filter-notif-item__event {
        display: inline-flex;
        align-items: center;
        padding: .22rem .5rem;
        border: 1px solid var(--item-color);
        border-radius: 999px;
        background: var(--item-soft);
        color: var(--item-color);
        font-size: .66rem;
        font-weight: 700;
        letter-spacing: .03em;
    }
    .filter-notif-item__time {
        display: block;
        margin-top: .35rem;
        color: #94a3b8;
        font-size: .7rem;
        white-space: nowrap;
    }
    .filter-notif-modal__empty {
        min-height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 1rem;
        text-align: center;
    }
    .filter-notif-modal__empty-icon {
        width: 68px;
        height: 68px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: .9rem;
        border-radius: 50%;
        background: #e8ecf3;
        color: #8094ae;
        font-size: 2rem;
    }
    .filter-notif-modal__empty-title {
        margin: 0;
        color: #334155;
        font-size: .92rem;
        font-weight: 700;
    }
    .filter-notif-modal__empty-text {
        max-width: 360px;
        margin: .4rem 0 1rem;
        color: #8094ae;
        font-size: .78rem;
        line-height: 1.5;
    }
    .filter-notif-modal__empty-close {
        min-width: 92px;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        background: #fff;
        color: #475569;
        font-size: .78rem;
        font-weight: 600;
    }
    .filter-notif-modal__empty-close:hover,
    .filter-notif-modal__empty-close:focus {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #1a2233;
    }
    .filter-notif-modal .modal-footer {
        justify-content: space-between;
        gap: 1rem;
        padding: .85rem 1.2rem;
        border-top: 1px solid #e8ecf3;
        background: #fff;
    }
    .filter-notif-modal__summary {
        display: flex;
        align-items: center;
        gap: .4rem;
        color: #8094ae;
        font-size: .75rem;
    }
    .filter-notif-modal__close-button {
        min-width: 88px;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        background: #fff;
        color: #475569;
        font-size: .78rem;
        font-weight: 600;
    }
    .filter-notif-modal__close-button:hover,
    .filter-notif-modal__close-button:focus {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #1a2233;
    }

    /* ── Notification list ─────────────────────────────── */
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        padding: 1rem 1.15rem;
        background: #fff;
        border: 1px solid #edf0f3;
        border-radius: .65rem;
        cursor: pointer;
        transition: background .15s, border-color .15s, box-shadow .15s;
        position: relative;
    }
    .notif-item:hover {
        background: #f8f9fc;
        border-color: #d9e2ec;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .notif-item.unread {
        background: #f0f7ff;
        border-left: 3px solid #3b82f6;
    }
    .notif-item.unread:hover {
        background: #e8f2ff;
    }

    /* unread dot */
    .notif-item .unread-dot {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #3b82f6;
    }

    /* status indicator */
    .notif-indicator {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .notif-indicator.down {
        background: rgba(239,68,68,.1);
        color: #ef4444;
    }
    .notif-indicator.warning {
        background: rgba(234,179,8,.1);
        color: #d97706;
    }
    .notif-indicator.online {
        background: rgba(34,197,94,.1);
        color: #22c55e;
    }

    .notif-body {
        flex: 1;
        min-width: 0;
    }
    .notif-body .notif-title {
        font-weight: 600;
        font-size: .925rem;
        color: #1a2233;
        margin-bottom: .15rem;
    }
    .notif-body .notif-ip {
        font-size: .8rem;
        color: #8094ae;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    }
    .notif-body .notif-event {
        display: inline-block;
        font-size: .7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: .2em .55em;
        border-radius: 4px;
        margin-top: .35rem;
    }
    .notif-body .notif-event.down {
        background: rgba(239,68,68,.1);
        color: #dc2626;
    }
    .notif-body .notif-event.warning {
        background: rgba(234,179,8,.12);
        color: #b45309;
    }
    .notif-body .notif-event.online {
        background: rgba(34,197,94,.1);
        color: #16a34a;
    }
    .notif-body .notif-message {
        font-size: .84rem;
        color: #5e6b7b;
        margin-top: .3rem;
    }
    .notif-meta {
        flex-shrink: 0;
        text-align: right;
        min-width: 100px;
    }
    .notif-meta .notif-time {
        font-size: .78rem;
        color: #8094ae;
        white-space: nowrap;
    }

    /* ── Modal overrides ───────────────────────────────── */
    .modal-notif .modal-content {
        border: none;
        border-radius: .75rem;
        box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .modal-notif .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.1rem 1.25rem;
    }
    .modal-notif .modal-body {
        padding: 1.25rem;
    }
    .modal-notif .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .85rem 1.5rem;
    }
    .modal-notif .detail-label {
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #8094ae;
        margin-bottom: .2rem;
    }
    .modal-notif .detail-value {
        font-size: .9rem;
        color: #1a2233;
        font-weight: 500;
    }
    .modal-notif .detail-message {
        grid-column: 1 / -1;
        background: #f8f9fc;
        border-radius: .5rem;
        padding: .85rem 1rem;
        margin-top: .35rem;
        font-size: .85rem;
        color: #5e6b7b;
    }

    @media (max-width: 575.98px) {
        .notif-item { padding: .85rem; gap: .65rem; }
        .notif-indicator { width: 34px; height: 34px; font-size: .85rem; }
        .notif-meta { min-width: auto; }
        .notif-meta .notif-time { font-size: .72rem; }
        .modal-notif .detail-grid { grid-template-columns: 1fr; }
        .notif-filters .btn { min-width: 0; font-size: .78rem; padding: .35rem .6rem; }
        .status-guide__trigger { padding: .85rem .9rem; }
        .status-guide__header-icon { flex-basis: 34px; width: 34px; height: 34px; font-size: 1rem; }
        .status-guide-modal .modal-dialog { width: calc(100% - 1rem); margin: .5rem; }
        .status-guide-modal__header { padding: 1rem; }
        .status-guide-modal__title { font-size: .96rem; }
        .status-guide-modal .modal-body { max-height: 65vh; padding: .85rem; }
        .status-guide-modal__grid { grid-template-columns: 1fr; }
        .status-guide-modal__item { padding: .85rem; gap: .65rem; }
        .status-guide-modal__item-icon { flex-basis: 36px; width: 36px; height: 36px; font-size: 1rem; }
        .status-guide-modal__footer { flex-wrap: wrap; padding: .8rem 1rem; }
        .filter-notif-modal .modal-dialog { margin: .5rem; }
        .filter-notif-modal .modal-content { max-height: 96vh; }
        .filter-notif-modal__header { padding: 1rem 1.1rem; }
        .filter-notif-modal__title { font-size: .96rem; }
        .filter-notif-modal .modal-body { max-height: 62vh; padding: .8rem; }
        .filter-notif-item { gap: .65rem; padding: .75rem .7rem; }
        .filter-notif-item__indicator { flex-basis: 36px; width: 36px; height: 36px; font-size: .95rem; }
        .filter-notif-item__name { font-size: .8rem; }
        .filter-notif-item__ip { font-size: .68rem; }
        .filter-notif-item__meta { margin-left: .2rem; }
        .filter-notif-item__time { font-size: .66rem; }
        .filter-notif-modal .modal-footer { padding: .75rem 1rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .status-guide__trigger,
        .status-guide__chevron,
        .filter-notif-item {
            transition: none;
        }
        .status-guide-modal.show.fade .modal-dialog {
            animation: none;
        }
    }
</style>
@endpush

@section('content')
{{-- =========================================================
     PAGE HEAD
========================================================== --}}
<x-admin.page-head
    title="Notifikasi"
    subtitle="Pantau perubahan status dan gangguan perangkat jaringan RADNET."
/>

{{-- FILTER --}}
<div class="d-flex flex-wrap gap-2 notif-filters" id="notifFilters">
    <button type="button" class="btn btn-outline-primary active" data-filter="all">
        Semua<span class="notif-count" id="countAll">0</span>
    </button>
    <button type="button" class="btn btn-outline-danger" data-filter="down">
        DOWN<span class="notif-count" id="countDown">0</span>
    </button>
    <button type="button" class="btn btn-outline-warning" data-filter="warning">
        WARNING<span class="notif-count" id="countWarning">0</span>
    </button>
    <button type="button" class="btn btn-outline-success" data-filter="online">
        BACK ONLINE<span class="notif-count" id="countOnline">0</span>
    </button>
</div>

<button type="button" class="status-guide__trigger status-guide__header" id="statusGuideToggle" aria-haspopup="dialog" aria-controls="statusGuideModal">
    <em class="icon ni ni-info status-guide__header-icon" aria-hidden="true"></em>
    <span class="status-guide__heading">
        <span class="status-guide__title">Panduan Status Notifikasi</span>
        <span class="status-guide__hint">Klik untuk lihat panduan status</span>
    </span>
    <em class="icon ni ni-chevron-right status-guide__chevron" aria-hidden="true"></em>
</button>

{{-- DAFTAR NOTIFIKASI --}}
<div class="notif-list" id="notifList"></div>

{{-- EMPTY STATE --}}
<div class="text-center py-5 d-none" id="notifEmpty">
    <em class="icon ni ni-bell-off text-soft" style="font-size:2.5rem;opacity:.35"></em>
    <p class="text-soft mt-2 mb-0">Tidak ada notifikasi untuk filter ini.</p>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade modal-notif" id="notifDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notifDetailTitle">Detail Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="notifDetailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade filter-notif-modal" id="notifFilterModal" tabindex="-1" aria-hidden="true" aria-labelledby="notifFilterModalTitle" aria-describedby="notifFilterModalSummary">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header filter-notif-modal__header">
                <div class="filter-notif-modal__heading">
                    <span class="filter-notif-modal__eyebrow">Daftar Notifikasi</span>
                    <h5 class="filter-notif-modal__title" id="notifFilterModalTitle">Notifikasi Semua (0)</h5>
                </div>
                <button type="button" class="btn-close filter-notif-modal__close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="filter-notif-modal__list" id="notifFilterList" aria-live="polite"></div>
                <div class="filter-notif-modal__empty" id="notifFilterEmpty" hidden>
                    <div class="filter-notif-modal__empty-icon" aria-hidden="true">
                        <em class="icon ni ni-bell-off"></em>
                    </div>
                    <h3 class="filter-notif-modal__empty-title">Tidak ada notifikasi saat ini</h3>
                    <p class="filter-notif-modal__empty-text">Belum ada notifikasi pada kategori yang dipilih.</p>
                    <button type="button" class="btn filter-notif-modal__empty-close" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
            <div class="modal-footer">
                <span class="filter-notif-modal__summary" id="notifFilterModalSummary">
                    <em class="icon ni ni-clock" aria-hidden="true"></em>
                    <span id="notifFilterSummaryText">0 notifikasi</span>
                </span>
                <button type="button" class="btn filter-notif-modal__close-button" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade status-guide-modal" id="statusGuideModal" tabindex="-1" aria-hidden="true" aria-labelledby="statusGuideModalTitle">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header status-guide-modal__header">
                <h5 class="status-guide-modal__title" id="statusGuideModalTitle">Panduan Status Notifikasi</h5>
                <button type="button" class="btn-close status-guide-modal__close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="status-guide-modal__grid">
                    <div class="status-guide-modal__item status-guide-modal__item--online">
                        <div class="status-guide-modal__item-icon">
                            <em class="icon ni ni-check-circle" aria-hidden="true"></em>
                        </div>
                        <div class="status-guide-modal__item-content">
                            <h3 class="status-guide-modal__item-title">UP / BACK ONLINE</h3>
                            <div class="status-guide-modal__description">
                                <p><span class="status-guide-modal__label">Arti:</span> Device merespons ping normal, 4/4 paket balas, latency di bawah threshold.</p>
                                <p><span class="status-guide-modal__label">Kapan muncul:</span> Saat device baru pulih dari DOWN atau sedang sehat.</p>
                                <p class="status-guide-modal__example">Contoh: “POP Krian kembali online setelah 12 menit DOWN.”</p>
                            </div>
                        </div>
                    </div>
                    <div class="status-guide-modal__item status-guide-modal__item--down">
                        <div class="status-guide-modal__item-icon">
                            <em class="icon ni ni-cross-c" aria-hidden="true"></em>
                        </div>
                        <div class="status-guide-modal__item-content">
                            <h3 class="status-guide-modal__item-title">DOWN</h3>
                            <div class="status-guide-modal__description">
                                <p><span class="status-guide-modal__label">Arti:</span> Device tidak merespons sama sekali, 0/4 ping balas selama 3x check berturut-turut.</p>
                                <p><span class="status-guide-modal__label">Kapan muncul:</span> Setelah gagal konsisten, bukan sekali gagal.</p>
                                <p class="status-guide-modal__example">Contoh: “SW 35.7 tidak merespons sejak 11:42.”</p>
                            </div>
                        </div>
                    </div>
                    <div class="status-guide-modal__item status-guide-modal__item--warning">
                        <div class="status-guide-modal__item-icon">
                            <em class="icon ni ni-alert" aria-hidden="true"></em>
                        </div>
                        <div class="status-guide-modal__item-content">
                            <h3 class="status-guide-modal__item-title">WARNING</h3>
                            <div class="status-guide-modal__description">
                                <p><span class="status-guide-modal__label">Arti:</span> Device masih hidup tapi kondisi tidak stabil — packet loss ≥ 25%, latency &gt; 150ms, atau flapping (naik-turun dalam 10 menit).</p>
                                <p><span class="status-guide-modal__label">Kapan muncul:</span> Sinyal awal sebelum device benar-benar DOWN.</p>
                                <p class="status-guide-modal__example">Contoh: “POP YPM packet loss 50% selama 3 menit terakhir.”</p>
                            </div>
                        </div>
                    </div>
                    <div class="status-guide-modal__item status-guide-modal__item--transition">
                        <div class="status-guide-modal__item-icon">
                            <em class="icon ni ni-reload" aria-hidden="true"></em>
                        </div>
                        <div class="status-guide-modal__item-content">
                            <h3 class="status-guide-modal__item-title">TRANSISI / RECOVERED</h3>
                            <div class="status-guide-modal__description">
                                <p><span class="status-guide-modal__label">Arti:</span> Status perpindahan, bukan kondisi tetap. Muncul saat device baru berubah status.</p>
                                <p><span class="status-guide-modal__label">Kapan muncul:</span> Saat device mengalami perubahan status.</p>
                                <p class="status-guide-modal__example">Contoh: “SW 35.7 berubah dari WARNING ke BACK ONLINE.”</p>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="status-guide-modal__note">Status di-refresh otomatis setiap 60 detik. Threshold bisa diatur per-device di halaman Kelola Router.</p>
            </div>
            <div class="modal-footer status-guide-modal__footer">
                <span class="status-guide-modal__meta">
                    <em class="icon ni ni-clock" aria-hidden="true"></em>
                    Diperbarui mengikuti kondisi perangkat
                </span>
                <button type="button" class="btn status-guide-modal__done" data-bs-dismiss="modal">Saya mengerti</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var statusGuideToggle = document.getElementById('statusGuideToggle');
    var statusGuideModal = document.getElementById('statusGuideModal');
    var statusGuideModalInstance = bootstrap.Modal.getOrCreateInstance(statusGuideModal);

    statusGuideToggle.addEventListener('click', function () {
        statusGuideModalInstance.show();
    });

    statusGuideModal.addEventListener('show.bs.modal', function () {
        document.body.classList.add('notif-modal-open');
    });

    /* ── DATA DARI BACKEND (tabel device_notifications) ── */
    var notifications = @json($notifications ?? []);

    var currentFilter = 'all';
    var notifList     = document.getElementById('notifList');
    var notifEmpty    = document.getElementById('notifEmpty');
    var notifFilterModalElement = document.getElementById('notifFilterModal');
    var notifFilterModalTitle = document.getElementById('notifFilterModalTitle');
    var notifFilterList = document.getElementById('notifFilterList');
    var notifFilterEmpty = document.getElementById('notifFilterEmpty');
    var notifFilterEmptyTitle = notifFilterEmpty.querySelector('.filter-notif-modal__empty-title');
    var notifFilterSummaryText = document.getElementById('notifFilterSummaryText');
    var notifFilterModalInstance = bootstrap.Modal.getOrCreateInstance(notifFilterModalElement);
    var detailHandoffPending = false;

    /* ── Status color map ──────────────────────────────────────── */
    function indicatorClass(status) {
        if (status === 'down')     return 'down';
        if (status === 'warning')  return 'warning';
        return 'online';
    }

    function iconFor(status) {
        if (status === 'down')     return 'ni ni-cross-c';
        if (status === 'warning')  return 'ni ni-alert';
        return 'ni ni-check';
    }

    /* ── Render list ───────────────────────────────────────────── */
    function getFilteredNotifications() {
        return notifications.filter(function (n) {
            return currentFilter === 'all' || n.status === currentFilter;
        });
    }

    function render() {
        var filtered = getFilteredNotifications();

        if (!filtered.length) {
            notifList.innerHTML = '';
            notifEmpty.classList.remove('d-none');
            return;
        }

        notifEmpty.classList.add('d-none');

        var html = '';
        filtered.forEach(function (n) {
            var cls = indicatorClass(n.status);
            html +=
                '<div class="notif-item' + (n.read ? '' : ' unread') + '" data-id="' + n.id + '">' +
                    (n.read ? '' : '<span class="unread-dot"><\/span>') +
                    '<div class="notif-indicator ' + cls + '">' +
                        '<em class="icon ' + iconFor(n.status) + '"><\/em>' +
                    '<\/div>' +
                    '<div class="notif-body">' +
                        '<div class="notif-title">' + n.device + '<\/div>' +
                        '<div class="notif-ip">' + n.ip + '<\/div>' +
                        '<span class="notif-event ' + cls + '">' + n.event + '<\/span>' +
                        '<div class="notif-message">' + n.message + '<\/div>' +
                    '<\/div>' +
                    '<div class="notif-meta">' +
                        '<div class="notif-time">' + n.timeLabel + '<\/div>' +
                    '<\/div>' +
                '<\/div>';
        });

        notifList.innerHTML = html;

        /* attach click handlers */
        notifList.querySelectorAll('.notif-item').forEach(function (el) {
            el.addEventListener('click', function () {
                var id = parseInt(el.getAttribute('data-id'), 10);
                openDetail(id);
            });
        });
    }

    function createFilterItem(notification) {
        var cls = indicatorClass(notification.status);
        var item = document.createElement('button');
        var indicator = document.createElement('span');
        var icon = document.createElement('em');
        var copy = document.createElement('span');
        var name = document.createElement('span');
        var ip = document.createElement('span');
        var meta = document.createElement('span');
        var event = document.createElement('span');
        var time = document.createElement('span');

        item.type = 'button';
        item.className = 'filter-notif-item filter-notif-item--' + cls + (notification.read ? '' : ' is-unread');
        item.dataset.id = notification.id;
        item.setAttribute('aria-label', 'Buka detail notifikasi ' + notification.device);

        indicator.className = 'filter-notif-item__indicator';
        icon.className = 'icon ' + iconFor(notification.status);
        icon.setAttribute('aria-hidden', 'true');
        indicator.appendChild(icon);

        copy.className = 'filter-notif-item__copy';
        name.className = 'filter-notif-item__name';
        name.textContent = notification.device;
        ip.className = 'filter-notif-item__ip';
        ip.textContent = notification.ip;
        copy.appendChild(name);
        copy.appendChild(ip);

        meta.className = 'filter-notif-item__meta';
        event.className = 'filter-notif-item__event';
        event.textContent = notification.event;
        time.className = 'filter-notif-item__time';
        time.textContent = notification.timeLabel;
        meta.appendChild(event);
        meta.appendChild(time);

        item.appendChild(indicator);
        item.appendChild(copy);
        item.appendChild(meta);

        item.addEventListener('click', function () {
            if (detailHandoffPending) return;

            var id = parseInt(item.dataset.id, 10);
            detailHandoffPending = true;
            notifFilterModalElement.addEventListener('hidden.bs.modal', function () {
                detailHandoffPending = false;
                openDetail(id);
            }, { once: true });
            notifFilterModalInstance.hide();
        });

        return item;
    }

    function renderFilterModal() {
        var labels = {
            all: 'Semua',
            down: 'DOWN',
            warning: 'WARNING',
            online: 'BACK ONLINE'
        };
        var label = labels[currentFilter] || labels.all;
        var filtered = getFilteredNotifications();
        var fragment = document.createDocumentFragment();

        notifFilterModalElement.classList.remove(
            'filter-notif-modal--down',
            'filter-notif-modal--warning',
            'filter-notif-modal--online'
        );
        if (currentFilter !== 'all') {
            notifFilterModalElement.classList.add('filter-notif-modal--' + currentFilter);
        }

        notifFilterModalTitle.textContent = 'Notifikasi ' + label + ' (' + filtered.length + ')';
        notifFilterEmptyTitle.textContent = 'Tidak ada notifikasi ' + label + ' saat ini';
        notifFilterSummaryText.textContent = filtered.length + ' notifikasi';

        filtered.forEach(function (notification) {
            fragment.appendChild(createFilterItem(notification));
        });

        notifFilterList.replaceChildren(fragment);
        notifFilterList.hidden = filtered.length === 0;
        notifFilterEmpty.hidden = filtered.length > 0;
    }

    function syncModalBodyLock() {
        document.body.classList.toggle('notif-modal-open', Boolean(document.querySelector('.modal.show')));
    }

    document.querySelectorAll('.modal').forEach(function (modalElement) {
        modalElement.addEventListener('shown.bs.modal', syncModalBodyLock);
        modalElement.addEventListener('hidden.bs.modal', syncModalBodyLock);
    });

    /* ── Update filter counts ──────────────────────────────────── */
    function updateCounts() {
        var counts = { all: 0, down: 0, warning: 0, online: 0 };
        notifications.forEach(function (n) {
            counts.all++;
            if (counts[n.status] !== undefined) counts[n.status]++;
        });
        document.getElementById('countAll').textContent    = counts.all;
        document.getElementById('countDown').textContent   = counts.down;
        document.getElementById('countWarning').textContent = counts.warning;
        document.getElementById('countOnline').textContent  = counts.online;
    }

    /* ── Filter click ──────────────────────────────────────────── */
    document.querySelectorAll('#notifFilters .btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#notifFilters .btn').forEach(function (filterButton) {
                filterButton.classList.remove('active');
            });
            btn.classList.add('active');
            currentFilter = btn.getAttribute('data-filter');
            render();
            renderFilterModal();
        });
    });

    notifFilterModalElement.addEventListener('show.bs.modal', function () {
        notifFilterList.scrollTop = 0;
    });

    /* ── Detail modal ──────────────────────────────────────────── */
    function openDetail(id) {
        var n = notifications.find(function (x) { return x.id === id; });
        if (!n) return;

        /* mark as read (frontend + backend) */
        var wasUnread = !n.read;
        n.read = true;
        if (wasUnread) {
            var url = '{{ route('admin.notifikasi.read', ['notification' => '__ID__']) }}'.replace('__ID__', id);
            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(function (response) {
                /* Badge lonceng ikut turun sesuai hitungan unread terbaru. */
                if (response.ok && window.AdminNotificationBadge) {
                    return response.json().then(function (data) {
                        window.AdminNotificationBadge.set(data.unread);
                    });
                }
            })
            .catch(function () {
                if (window.AdminNotificationBadge) {
                    window.AdminNotificationBadge.refresh();
                }
            });
        }
        updateCounts();
        render();

        var cls = indicatorClass(n.status);

        document.getElementById('notifDetailTitle').textContent = 'Detail Notifikasi';

        var latencyRow = n.latency !== null
            ? '<div><div class="detail-label">Response / Latency<\/div><div class="detail-value">' + n.latency + ' ms<\/div><\/div>'
            : '<div><div class="detail-label">Response / Latency<\/div><div class="detail-value text-muted">-<\/div><\/div>';

        document.getElementById('notifDetailBody').innerHTML =
            '<div class="detail-grid">' +
                '<div><div class="detail-label">Nama Perangkat<\/div><div class="detail-value">' + n.device + '<\/div><\/div>' +
                '<div><div class="detail-label">IP Address<\/div><div class="detail-value"><code>' + n.ip + '<\/code><\/div><\/div>' +
                '<div><div class="detail-label">Status<\/div><div class="detail-value"><span class="notif-event ' + cls + '">' + n.event + '<\/span><\/div><\/div>' +
                '<div><div class="detail-label">Jenis Monitoring<\/div><div class="detail-value">' + n.monitorType + '<\/div><\/div>' +
                '<div><div class="detail-label">Waktu Kejadian<\/div><div class="detail-value">' + new Date(n.time).toLocaleString('id-ID', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) + '<\/div><\/div>' +
                latencyRow +
                '<div class="detail-message"><div class="detail-label">Pesan / Keterangan<\/div><div class="detail-value mt-1">' + n.message + '<\/div><\/div>' +
            '<\/div>';

        var modal = new bootstrap.Modal(document.getElementById('notifDetailModal'));
        modal.show();
    }

    /* ── Init ──────────────────────────────────────────────────── */
    updateCounts();
    render();
    renderFilterModal();

});
</script>
@endpush
