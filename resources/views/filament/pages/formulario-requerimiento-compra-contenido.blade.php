<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulario de Requerimiento de Compra, Contratación o Arrendamiento — PRZ — v0.65</title>
    <style>
        :root {
            --green: #176b4a;
            --green-dark: #0f4e37;
            --green-soft: #eaf5ef;
            --ink: #17211d;
            --muted: #617069;
            --line: #d8e0dc;
            --soft: #f6f8f7;
            --amber: #a86200;
            --amber-soft: #fff6e5;
            --red: #9d2f2f;
            --red-soft: #fff0f0;
            --blue: #285c8f;
            --blue-soft: #edf4fb;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: #edf1ef;
            color: var(--ink);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            line-height: 1.35;
        }

        .toolbar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            padding: 10px 18px;
            background: rgba(255, 255, 255, .96);
            border-bottom: 1px solid var(--line);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .toolbar .grow {
            flex: 1
        }

        button {
            border: 1px solid var(--line);
            background: white;
            border-radius: 9px;
            padding: 9px 13px;
            font-weight: 700;
            cursor: pointer;
            color: var(--ink);
        }

        button.primary {
            background: var(--green);
            border-color: var(--green);
            color: white
        }

        button:hover {
            filter: brightness(.98)
        }

        .progress-wrap {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 250px
        }

        .progress {
            height: 8px;
            background: #e5ebe8;
            border-radius: 99px;
            overflow: hidden;
            flex: 1
        }

        .progress>span {
            display: block;
            height: 100%;
            background: var(--green);
            width: 0%;
            transition: .2s
        }

        .progress-label {
            font-size: 12px;
            color: var(--muted);
            white-space: nowrap
        }

        .page {
            max-width: 1100px;
            margin: 24px auto 50px;
            background: var(--white);
            box-shadow: 0 10px 35px rgba(26, 49, 38, .10);
            border-radius: 16px;
            overflow: hidden;
        }

        header {
            background: linear-gradient(135deg, var(--green-dark), var(--green));
            color: white;
            padding: 28px 34px 24px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: start;
        }

        .brandline {
            font-size: 12px;
            letter-spacing: .12em;
            text-transform: uppercase;
            opacity: .8;
            font-weight: 700
        }

        h1 {
            font-size: 25px;
            line-height: 1.16;
            margin: 7px 0 6px
        }

        header p {
            margin: 0;
            color: rgba(255, 255, 255, .84);
            font-size: 13px;
            max-width: 760px
        }

        .docmeta {
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 12px;
            padding: 12px 14px;
            min-width: 210px;
            font-size: 12px;
        }

        .docmeta strong {
            display: inline-block;
            min-width: 72px
        }

        main {
            padding: 26px 30px 34px
        }

        .intro {
            border: 1px solid #cfe3d8;
            background: var(--green-soft);
            border-radius: 12px;
            padding: 13px 15px;
            margin-bottom: 22px;
            font-size: 13px;
        }

        .intro b {
            color: var(--green-dark)
        }

        section {
            border: 1px solid var(--line);
            border-radius: 13px;
            margin: 0 0 18px;
            overflow: hidden;
            break-inside: avoid;
        }

        .section-head {
            display: flex;
            gap: 11px;
            align-items: center;
            padding: 13px 16px;
            background: var(--soft);
            border-bottom: 1px solid var(--line);
        }

        .num {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--green);
            color: white;
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 13px;
            flex: none;
        }

        .section-head h2 {
            font-size: 15px;
            margin: 0
        }

        .section-head small {
            display: block;
            color: var(--muted);
            font-weight: 400;
            margin-top: 2px
        }

        .section-body {
            padding: 16px
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 13px
        }

        .c12 {
            grid-column: span 12
        }

        .c9 {
            grid-column: span 9
        }

        .c8 {
            grid-column: span 8
        }

        .c7 {
            grid-column: span 7
        }

        .c6 {
            grid-column: span 6
        }

        .c5 {
            grid-column: span 5
        }

        .c4 {
            grid-column: span 4
        }

        .c3 {
            grid-column: span 3
        }

        .field label,
        .label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 800;
            color: #44514b;
            margin-bottom: 5px;
        }

        .planning-control-align>label {
            min-height: 38px;
            display: flex;
            align-items: flex-end;
        }

        .planning-control-align>select {
            min-height: 42px;
        }

        .planning-control-align>.choice-row {
            min-height: 42px;
            align-items: stretch;
        }

        .planning-control-align>.choice-row .choice {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
        }

        .circular-align>label {
            min-height: 38px;
            display: flex;
            align-items: flex-end;
        }

        .circular-align>select,
        .circular-align>input {
            min-height: 42px;
        }

        .integrity-align>label {
            min-height: 46px;
            display: flex;
            align-items: flex-end;
        }

        .integrity-align>select {
            min-height: 42px;
        }

        .section-budget-market .grid>.field:nth-child(-n+3)>label {
            min-height: 42px;
            display: flex;
            align-items: flex-end;
        }

        .req::after {
            content: " *";
            color: var(--red)
        }

        input[type=text],
        input[type=date],
        input[type=time],
        input[type=number],
        input[type=email],
        select,
        textarea {
            width: 100%;
            border: 1px solid #cbd5d0;
            border-radius: 8px;
            padding: 9px 10px;
            background: white;
            color: var(--ink);
            font: inherit;
            font-size: 13px;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(23, 107, 74, .10)
        }

        textarea {
            min-height: 82px;
            resize: vertical
        }

        textarea.short {
            min-height: 58px
        }

        .hint {
            font-size: 11px;
            color: var(--muted);
            margin-top: 5px
        }

        .choice-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap
        }

        .choice {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #cbd5d0;
            border-radius: 8px;
            padding: 8px 10px;
            background: white;
            font-size: 12px;
        }

        .choice input {
            accent-color: var(--green)
        }

        .subbox {
            background: #fafcfb;
            border: 1px dashed #c7d4ce;
            border-radius: 10px;
            padding: 13px;
            margin-top: 13px;
        }

        .subbox-title {
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 9px
        }

        .alert {
            border-radius: 9px;
            padding: 10px 12px;
            font-size: 12px;
            margin-top: 10px;
        }

        .alert.amber {
            background: var(--amber-soft);
            border: 1px solid #f0d39c;
            color: #684000
        }

        .alert.blue {
            background: var(--blue-soft);
            border: 1px solid #c9dff2;
            color: #244b6c
        }

        .alert.red {
            background: var(--red-soft);
            border: 1px solid #efcaca;
            color: #782525
        }

        .hidden {
            display: none !important
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px
        }

        th {
            background: #f2f5f3;
            text-align: left;
            color: #435049;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .035em
        }

        th,
        td {
            border: 1px solid #d9e0dc;
            padding: 7px;
            vertical-align: top
        }

        td input,
        td select,
        td textarea {
            border: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 3px !important;
            min-height: auto
        }

        td textarea {
            height: 48px;
            resize: vertical
        }

        .right {
            text-align: right
        }

        .amount-total {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            margin-top: 9px;
            font-size: 13px;
        }

        .amount-total strong {
            font-size: 18px;
            color: var(--green-dark)
        }

        .pill {
            display: inline-block;
            border-radius: 99px;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: 800;
            background: var(--green-soft);
            color: var(--green-dark);
        }

        .sign-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr .8fr .9fr 1.4fr;
            gap: 0;
            border: 1px solid var(--line);
            border-radius: 9px;
            overflow: hidden
        }

        .sign-grid>div {
            padding: 8px;
            border-right: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            font-size: 11px;
            min-height: 54px
        }

        .sign-grid>div:nth-child(5n) {
            border-right: 0
        }

        .sign-grid .head {
            background: #f2f5f3;
            font-size: 9.5px;
            text-transform: uppercase;
            font-weight: 800;
            min-height: auto
        }

        .signature-line {
            height: 28px;
            border-bottom: 1px dotted #8e9b95;
            margin-top: 3px
        }

        details {
            border: 1px solid var(--line);
            border-radius: 12px;
            margin-top: 20px;
            background: #fbfcfb;
        }

        summary {
            cursor: pointer;
            padding: 13px 15px;
            font-weight: 800;
            font-size: 13px
        }

        details .details-body {
            padding: 0 15px 15px
        }

        .source-card {
            padding: 10px 0;
            border-top: 1px solid #e2e7e4;
            font-size: 12px;
        }

        .source-card:first-child {
            border-top: 0
        }

        .source-card a {
            color: var(--green-dark);
            font-weight: 700;
            text-decoration: none
        }

        .footer-note {
            font-size: 10.5px;
            color: var(--muted);
            margin-top: 18px
        }

        .internal {
            border-left: 4px solid var(--blue)
        }

        .conditional {
            border-left: 4px solid var(--amber)
        }

        .mini-title {
            font-weight: 800;
            font-size: 12px;
            margin-bottom: 7px
        }

        .checklist {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px 14px
        }

        .checklist label {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            font-size: 12px
        }

        .checklist input {
            margin-top: 2px;
            accent-color: var(--green)
        }

        .emergency-action-highlight {
            background: var(--green) !important;
            color: #fff !important;
            border-color: var(--green) !important;
            box-shadow: 0 0 0 4px rgba(23, 107, 74, .12), 0 6px 14px rgba(23, 107, 74, .18);
            font-weight: 800 !important;
        }

        .emergency-action-highlight:hover,
        .emergency-action-highlight:focus {
            background: var(--green-dark) !important;
            color: #fff !important;
            border-color: var(--green-dark) !important;
            box-shadow: 0 0 0 4px rgba(23, 107, 74, .16), 0 7px 16px rgba(23, 107, 74, .20);
        }

        .eui-help-label {
            position: relative;
            cursor: help;
            text-decoration: underline;
            text-decoration-style: dotted;
            text-decoration-thickness: 1px;
            text-underline-offset: 3px;
            text-decoration-color: #91a099;
        }

        .eui-help-label::after {
            content: " ?";
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 15px;
            height: 15px;
            margin-left: 5px;
            border-radius: 999px;
            background: #eef5f1;
            color: var(--green-dark);
            font-size: 9px;
            font-weight: 900;
            vertical-align: 1px;
        }

        .eui-help-popover {
            position: fixed;
            z-index: 1700;
            width: min(390px, calc(100vw - 32px));
            padding: 12px 13px;
            border: 1px solid #c9d7d0;
            border-radius: 10px;
            background: #fff;
            color: #53615a;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .18);
            font-size: 11px;
            line-height: 1.48;
            pointer-events: none;
        }

        .eui-help-popover strong {
            display: block;
            margin-bottom: 5px;
            color: var(--green-dark);
            font-size: 12px;
        }

        .eui-help-popover .eui-help-example {
            margin-top: 7px;
            padding-top: 7px;
            border-top: 1px solid #e5ebe8;
            color: #66736d;
        }

        .status-badge {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            padding: 5px 8px;
            border-radius: 7px;
            background: white;
            border: 1px solid #cdd7d2;
            font-size: 11px
        }

        .print-only {
            display: none
        }

        .no-print {}

        label[title],
        th[title],
        .help-hover {
            cursor: help
        }

        .help-note {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px
        }

        .table-tools {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px
        }

        .tool-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center
        }

        .small-btn {
            border: 1px solid var(--line);
            background: white;
            color: var(--ink);
            border-radius: 8px;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer
        }

        .small-btn.danger {
            border-color: #e0b7b7;
            color: #7a2c2c
        }

        .row-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            align-items: center
        }

        .mini-file-list {
            font-size: 10.5px;
            color: var(--muted);
            margin-top: 4px;
            line-height: 1.25
        }

        .file-badge {
            display: inline-block;
            border-radius: 999px;
            padding: 2px 7px;
            font-size: 10px;
            font-weight: 800;
            background: var(--blue-soft);
            color: var(--blue)
        }

        .inline-select {
            min-height: 110px;
        }

        .soft-card {
            background: #fafcfb;
            border: 1px dashed #c7d4ce;
            border-radius: 10px;
            padding: 12px;
            margin-top: 12px;
        }

        .compact-title {
            font-size: 12px;
            font-weight: 800;
            margin: 0 0 7px;
        }

        .icon-btn {
            width: 34px;
            height: 34px;
            border: 0;
            background: transparent;
            border-radius: 50%;
            display: inline-grid;
            place-items: center;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: .15s ease;
        }

        .icon-btn.attach {
            width: auto;
            height: auto;
            border: 0;
            background: transparent;
            border-radius: 0;
            color: #315c4b;
            box-shadow: none;
            padding: 0;
            font-size: 18px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn.attach:hover {
            color: #1f4336;
            transform: translateY(-1px);
        }

        .cotiz-cell {
            position: relative;
            min-width: 68px;
        }

        .cotiz-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
        }

        .trash-row {
            width: 22px;
            height: 22px;
            border: 0;
            background: transparent;
            border-radius: 6px;
            display: inline-grid;
            place-items: center;
            cursor: pointer;
            color: rgba(90, 101, 96, .18);
            font-size: 14px;
            font-weight: 500;
            transition: .15s ease;
            position: absolute;
            right: 4px;
            bottom: 4px;
        }

        .trash-row:hover {
            color: #3d4944;
            background: #f2f5f3;
            font-weight: 800;
        }

        .trash-row:focus {
            outline: 2px solid rgba(23, 107, 74, .18);
            outline-offset: 1px;
        }

        .table-bottom-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .row-helper {
            font-size: 11px;
            color: var(--muted)
        }

        .currency-wrap {
            display: flex;
            align-items: center;
            border: 1px solid #cbd5d0;
            border-radius: 8px;
            background: white;
            overflow: hidden;
        }

        .currency-wrap span {
            padding: 0 6px;
            font-size: 11px;
            color: #5f6c66;
            font-weight: 600;
            border-right: 1px solid #e5ebe7;
            background: transparent;
            min-width: 20px;
            text-align: center;
        }

        .currency-wrap input {
            border: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 9px 10px !important;
        }

        .criteria-builder {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .criteria-combo {
            position: relative;
            flex: 1;
            min-width: 280px;
        }

        .criteria-combo-row {
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .criteria-combo input {
            flex: 1;
            min-width: 0;
            border: 1px solid #cbd5d0;
            border-right: 0;
            border-radius: 8px 0 0 8px;
            padding: 9px 10px;
            font: inherit;
            font-size: 13px;
            outline: none;
        }

        .criteria-combo input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(23, 107, 74, .10);
        }

        .criteria-combo-toggle {
            width: 42px;
            border: 1px solid #cbd5d0;
            border-left: 0;
            border-radius: 0 8px 8px 0;
            background: white;
            cursor: pointer;
            font-size: 15px;
            display: grid;
            place-items: center;
        }

        .criteria-combo-toggle:hover {
            background: #f7faf8;
        }

        .definition-combo {
            position: relative;
        }

        .definition-combo-button {
            width: 100%;
            min-height: 42px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border: 1px solid #cbd5d0;
            border-radius: 8px;
            padding: 9px 10px;
            background: #fff;
            color: var(--ink);
            font: inherit;
            font-size: 13px;
            cursor: pointer;
            text-align: left;
        }

        .definition-combo-button:hover,
        .definition-combo-button:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(23, 107, 74, .10);
            outline: none;
        }

        .definition-combo-chevron {
            flex: 0 0 auto;
            font-size: 15px;
            transition: transform .14s ease;
        }

        .definition-combo.open .definition-combo-chevron {
            transform: rotate(180deg);
        }

        .definition-dropdown {
            position: absolute;
            z-index: 80;
            top: calc(100% + 5px);
            left: 0;
            width: 100%;
            min-width: 220px;
            padding: 5px;
            border: 1px solid #cbd5d0;
            border-radius: 9px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .16);
        }

        .definition-option {
            width: 100%;
            display: block;
            border: 0;
            background: #fff;
            border-radius: 7px;
            padding: 9px 10px;
            text-align: left;
            color: var(--ink);
            font: inherit;
            font-size: 13px;
            cursor: pointer;
        }

        .definition-option:hover,
        .definition-option:focus {
            background: var(--green-soft);
            color: var(--green-dark);
            outline: none;
        }

        .definition-popover {
            position: absolute;
            z-index: 90;
            left: calc(100% + 10px);
            top: 0;
            width: min(360px, 42vw);
            border: 1px solid #c9d7d0;
            border-radius: 10px;
            background: #fff;
            padding: 11px 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .16);
            pointer-events: none;
        }

        .definition-popover::before {
            content: "";
            position: absolute;
            left: -7px;
            top: 18px;
            width: 12px;
            height: 12px;
            background: #fff;
            border-left: 1px solid #c9d7d0;
            border-bottom: 1px solid #c9d7d0;
            transform: rotate(45deg);
        }

        .definition-popover-title {
            font-size: 12px;
            font-weight: 800;
            color: var(--green-dark);
            margin-bottom: 5px;
        }

        .definition-popover-text {
            font-size: 11px;
            line-height: 1.45;
            color: #53615a;
        }

        .delivery-place-combo {
            position: relative;
        }

        .delivery-place-button {
            width: 100%;
            min-height: 42px;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border: 1px solid #cbd5d0;
            border-radius: 8px;
            padding: 10px 12px;
            background: #fff;
            color: var(--ink);
            font: inherit;
            font-size: 13px;
            line-height: 1.35;
            text-align: left;
            cursor: pointer;
            white-space: normal;
        }

        .delivery-place-button:hover,
        .delivery-place-button:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(23, 107, 74, .10);
            outline: none;
        }

        .delivery-place-button #deliveryPlaceLabel {
            display: block;
            flex: 1;
            min-width: 0;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .delivery-place-chevron {
            flex: 0 0 auto;
            font-size: 15px;
            transition: transform .14s ease;
        }

        .delivery-place-combo.open .delivery-place-chevron {
            transform: rotate(180deg);
        }

        .delivery-place-dropdown {
            position: absolute;
            z-index: 75;
            top: calc(100% + 5px);
            left: 0;
            width: 100%;
            padding: 5px;
            border: 1px solid #cbd5d0;
            border-radius: 9px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .16);
        }

        .delivery-place-option {
            width: 100%;
            display: block;
            border: 0;
            background: #fff;
            border-radius: 7px;
            padding: 9px 10px;
            text-align: left;
            color: var(--ink);
            font: inherit;
            font-size: 12px;
            line-height: 1.4;
            white-space: normal;
            cursor: pointer;
        }

        .delivery-place-option:hover,
        .delivery-place-option:focus {
            background: var(--green-soft);
            color: var(--green-dark);
            outline: none;
        }

        @media (max-width: 900px) {
            .definition-popover {
                left: 0;
                top: calc(100% + 170px);
                width: min(440px, 90vw);
            }

            .definition-popover::before {
                display: none;
            }
        }

        .criteria-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            z-index: 30;
            max-height: 260px;
            overflow-y: auto;
            overscroll-behavior: contain;
            touch-action: pan-y;
            background: white;
            border: 1px solid #cbd5d0;
            border-radius: 9px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            padding: 4px;
        }

        .criteria-option {
            width: 100%;
            text-align: left;
            border: 0;
            background: white;
            border-radius: 7px;
            padding: 8px 9px;
            font-size: 12px;
            line-height: 1.3;
            cursor: pointer;
            color: var(--ink);
        }

        .criteria-option:hover,
        .criteria-option.is-active {
            background: var(--green-soft);
        }

        .criteria-example-tag {
            font-weight: 800;
            color: var(--green-dark);
        }

        .criteria-list {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .criteria-pill {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #d9e2dd;
            color: var(--ink);
            font-size: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
        }

        .criteria-pill-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 6px;
            border-radius: 999px;
            background: #f3f6f4;
            border: 1px solid #d4ddd8;
            color: #42514a;
            font-size: 11px;
            font-weight: 800;
            line-height: 1;
            flex-shrink: 0;
        }

        .criteria-pill-text {
            line-height: 1.35;
            color: #2f3d37;
            font-weight: 600;
            flex: 1;
            padding-top: 2px;
        }

        .criteria-pill button {
            width: 24px;
            height: 24px;
            border: 1px solid #e0e5e2;
            background: #fff;
            border-radius: 999px;
            padding: 0;
            cursor: pointer;
            font-size: 13px;
            line-height: 1;
            color: #7a2c2c;
            flex-shrink: 0;
        }

        .criteria-pill button:hover {
            background: #fbf3f3;
            border-color: #e2caca;
        }

        .criteria-status {
            margin-top: 8px;
            font-size: 12px;
            line-height: 1.35;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #ecd9a8;
            background: #fff9ea;
            color: #755d1a;
            display: none;
        }

        .criteria-status.show {
            display: block;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100dvh;
            background: rgba(17, 28, 23, 0.45);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
        }

        .modal-card {
            width: min(760px, 96vw);
            max-height: 88vh;
            overflow: auto;
            background: white;
            border-radius: 14px;
            box-shadow: 0 18px 55px rgba(0, 0, 0, .25);
            border: 1px solid var(--line);
        }

        .modal-card {
            position: relative;
            width: min(760px, 100%);
            max-height: calc(100dvh - 40px);
            margin: auto;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: 0 18px 55px rgba(0, 0, 0, 0.25);
            overflow-y: auto;
        }

        .modal-head h3 {
            margin: 0;
            font-size: 17px
        }

        .modal-head p {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--muted)
        }

        .modal-close {
            width: 30px;
            height: 30px;
            border: 0;
            background: transparent;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
        }

        .modal-close:hover {
            background: #eef2f0
        }

        .modal-body {
            padding: 18px 20px
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 14px 20px;
            border-top: 1px solid var(--line);
            background: #fafcfb;
        }

        .modal-footer button.danger {
            border: 1px solid #d6a9a9;
            background: #fff7f7;
            color: #8b3c3c;
            font-weight: 800;
        }

        .modal-footer button.danger:hover {
            background: #fbecec;
        }

        .attach-drop {
            border: 1px dashed #b9c8c0;
            border-radius: 10px;
            padding: 14px;
            background: #fbfdfc;
            margin-bottom: 14px;
        }

        .attach-drop .tool-group {
            margin-top: 8px
        }

        .attach-file-name {
            font-size: 12px;
            color: var(--muted);
            margin-top: 7px
        }

        .item-link-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 7px;
            margin-top: 9px;
        }

        .item-link {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 9px 10px;
            border: 1px solid #dde4e0;
            border-radius: 8px;
            background: #fff;
        }

        .item-link input {
            margin-top: 2px;
            accent-color: var(--green)
        }

        .item-link strong {
            font-size: 12px
        }

        .item-link small {
            display: block;
            color: var(--muted);
            margin-top: 2px
        }

        .attachment-register {
            margin-top: 14px
        }

        .attachment-card {
            border: 1px solid #dce4e0;
            border-radius: 10px;
            padding: 11px 12px;
            margin-top: 8px;
            background: #fff;
        }

        .attachment-card-top {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: flex-start;
        }

        .attachment-card-title {
            font-size: 12px;
            font-weight: 800;
            word-break: break-word
        }

        .attachment-links {
            margin-top: 6px;
            display: flex;
            gap: 5px;
            flex-wrap: wrap
        }

        .link-pill {
            display: inline-block;
            font-size: 10px;
            font-weight: 800;
            padding: 3px 7px;
            border-radius: 999px;
            background: var(--green-soft);
            color: var(--green-dark)
        }

        .attachment-remove {
            border: 0;
            background: transparent;
            color: #8b3c3c;
            cursor: pointer;
            font-size: 13px;
            padding: 2px 4px;
        }

        .pending-file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .pending-file-card {
            position: relative;
            border: 1px solid #d9e2dd;
            border-radius: 10px;
            background: #fff;
            padding: 9px;
            cursor: pointer;
            transition: .15s ease;
        }

        .pending-file-card:hover {
            border-color: #aebdb5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
        }

        .pending-file-card.selected {
            border-color: var(--green);
            box-shadow: 0 0 0 2px rgba(23, 107, 74, .10);
            background: #fbfdfc;
        }

        .pending-file-check {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 20px;
            height: 20px;
            accent-color: var(--green);
            z-index: 2;
        }

        .pending-file-preview {
            height: 108px;
            border-radius: 8px;
            background: #f4f6f5;
            border: 1px solid #e2e8e4;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .pending-file-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .pending-file-preview embed,
        .pending-file-preview iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
            background: white;
            pointer-events: none;
        }

        .document-mini-preview {
            position: absolute;
            inset: 0;
            padding: 10px 9px 34px;
            overflow: hidden;
            background: #fff;
            color: #333;
            text-align: left;
            font-family: Arial, sans-serif;
            font-size: 7px;
            line-height: 1.25;
            pointer-events: none;
        }

        .document-mini-preview .doc-page-title {
            font-size: 7.5px;
            font-weight: 700;
            margin-bottom: 4px;
            border-bottom: 1px solid #e3e3e3;
            padding-bottom: 3px;
        }

        .document-mini-preview .doc-text {
            white-space: pre-wrap;
            word-break: break-word;
        }

        .document-mini-preview table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 6.5px;
            background: white;
        }

        .document-mini-preview td,
        .document-mini-preview th {
            border: 1px solid #ddd;
            padding: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .document-mini-preview .loading-doc {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #77817c;
            font-size: 8px;
            text-align: center;
        }

        .office-preview-large {
            width: 100%;
            height: 100%;
            overflow: auto;
            background: #fff;
            padding: 26px 32px;
            color: #222;
            font-family: Arial, sans-serif;
        }

        .office-preview-large .word-content {
            max-width: 850px;
            margin: 0 auto;
            font-size: 13px;
            line-height: 1.5;
        }

        .office-preview-large table {
            border-collapse: collapse;
            width: max-content;
            min-width: 100%;
            font-size: 12px;
        }

        .office-preview-large td,
        .office-preview-large th {
            border: 1px solid #d8d8d8;
            padding: 6px 8px;
            vertical-align: top;
        }

        .attachment-thumb embed,
        .attachment-thumb iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
            background: white;
            pointer-events: none;
        }

        .pending-file-icon {
            font-size: 34px;
            line-height: 1;
        }

        .pending-file-card input[type="text"] {
            font-size: 11px;
            padding: 7px 8px;
            margin-top: 2px;
        }

        .pending-file-meta {
            font-size: 10px;
            color: var(--muted);
            margin-top: 5px;
        }

        .pending-file-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 5px;
        }

        .preview-expand-btn {
            position: absolute;
            right: 8px;
            bottom: 8px;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .75);
            background: rgba(20, 30, 25, .72);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .18);
            z-index: 3;
        }

        .preview-expand-btn:hover {
            background: rgba(20, 30, 25, .90);
        }

        .pending-file-preview {
            position: relative;
        }

        .item-chip-selector {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .item-select-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #cfd9d4;
            background: #fff;
            color: #31423a;
            border-radius: 999px;
            padding: 8px 11px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: .14s ease;
        }

        .item-select-chip:hover {
            border-color: #aebdb5;
            background: #fafcfb;
        }

        .item-select-chip.selected {
            border-color: var(--green);
            background: var(--green-soft);
            color: var(--green-dark);
            box-shadow: 0 0 0 2px rgba(23, 107, 74, .08);
        }

        .item-select-chip .item-chip-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            border-radius: 999px;
            background: #f1f4f2;
            color: #435049;
            font-size: 10px;
            font-weight: 800;
        }

        .item-select-chip.selected .item-chip-number {
            background: #fff;
            color: var(--green-dark);
        }

        .drag-item-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            margin-top: 10px;
        }

        .drag-item-card {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px 12px;
            border: 1px solid #d9e2dd;
            border-radius: 10px;
            background: #fff;
            cursor: grab;
            transition: .14s ease;
        }

        .drag-item-card:hover {
            border-color: #aebdb5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
        }

        .drag-item-card.selected {
            border-color: var(--green);
            background: #fbfdfc;
            box-shadow: 0 0 0 2px rgba(23, 107, 74, .08);
        }

        .drag-item-card.drag-over,
        .pending-file-card.drag-over {
            border-color: var(--green);
            background: #f1faf5;
            box-shadow: 0 0 0 3px rgba(23, 107, 74, .12);
        }

        .drag-item-card .item-drag-handle {
            font-size: 16px;
            color: #849089;
            line-height: 1.2;
            margin-top: 2px;
        }

        .drag-item-card .item-drag-content {
            flex: 1;
            min-width: 0;
        }

        .drag-item-card strong {
            display: block;
            font-size: 12px;
            color: var(--ink);
        }

        .drag-item-card small {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.35;
        }

        .drag-item-card .item-select-box {
            margin-top: 2px;
            accent-color: var(--green);
        }

        .draft-link-pills {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            margin-top: 7px;
        }

        .draft-link-pill {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 22px 5px 9px;
            border-radius: 999px;
            background: #eef7f2;
            color: var(--green-dark);
            font-size: 9.5px;
            font-weight: 800;
            line-height: 1.1;
        }

        .draft-link-pill-remove {
            position: absolute;
            top: 2px;
            right: 4px;
            width: 13px;
            height: 13px;
            border: 0;
            border-radius: 999px;
            background: rgba(23, 107, 74, .12);
            color: var(--green-dark);
            font-size: 10px;
            font-weight: 900;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
        }

        .draft-link-pill-remove:hover {
            background: rgba(139, 60, 60, .14);
            color: #8b3c3c;
        }

        .drag-helper-text {
            margin-top: 8px;
            padding: 8px 10px;
            border-radius: 8px;
            background: #f8faf9;
            border: 1px dashed #cbd6d0;
            color: #66736d;
            font-size: 10.5px;
        }

        .link-workflow-note {
            border: 1px solid #dfe6e2;
            background: #fbfdfc;
            border-radius: 9px;
            padding: 9px 11px;
            font-size: 11px;
            color: var(--muted);
            margin-top: 8px;
        }

        .image-lightbox {
            position: fixed;
            inset: 0;
            z-index: 1400;
            background: rgba(10, 14, 12, .88);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .image-lightbox-card {
            position: relative;
            width: min(1100px, 96vw);
            height: min(820px, 90vh);
            background: #111;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 70px rgba(0, 0, 0, .45);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-lightbox-card img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .image-lightbox-close {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, .9);
            color: #1d2722;
            font-size: 22px;
            cursor: pointer;
            z-index: 2;
        }

        .image-lightbox-name {
            position: absolute;
            left: 14px;
            bottom: 12px;
            max-width: 70%;
            padding: 7px 10px;
            border-radius: 8px;
            background: rgba(0, 0, 0, .62);
            color: white;
            font-size: 12px;
        }

        .file-preview-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1500;
            background: rgba(10, 14, 12, .88);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 22px;
        }

        .file-preview-card {
            width: min(1180px, 97vw);
            height: min(880px, 92vh);
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 70px rgba(0, 0, 0, .48);
            display: flex;
            flex-direction: column;
        }

        .file-preview-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 12px 15px;
            border-bottom: 1px solid #dce4e0;
            background: #fafcfb;
        }

        .file-preview-title {
            min-width: 0;
        }

        .file-preview-title strong {
            display: block;
            font-size: 13px;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-preview-title small {
            display: block;
            margin-top: 2px;
            font-size: 10px;
            color: var(--muted);
        }

        .file-preview-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        .file-preview-body {
            position: relative;
            flex: 1;
            min-height: 0;
            background: #eef1ef;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-preview-body iframe,
        .file-preview-body embed {
            width: 100%;
            height: 100%;
            border: 0;
            background: white;
        }

        .file-preview-body img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .file-preview-fallback {
            width: min(560px, 90%);
            text-align: center;
            padding: 28px;
            background: white;
            border: 1px solid #dce4e0;
            border-radius: 12px;
        }

        .file-preview-fallback .big-icon {
            font-size: 54px;
            margin-bottom: 10px;
        }

        .file-preview-fallback strong {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .file-preview-fallback p {
            margin: 0;
            font-size: 11px;
            color: var(--muted);
            line-height: 1.45;
        }

        .pending-remove {
            border: 0;
            background: transparent;
            color: #8b3c3c;
            cursor: pointer;
            font-size: 11px;
            padding: 2px 4px;
        }

        .file-selection-summary {
            margin-top: 8px;
            font-size: 11px;
            color: var(--muted);
        }

        .attachment-card-main {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .attachment-thumb {
            width: 56px;
            height: 56px;
            border-radius: 8px;
            border: 1px solid #dfe6e2;
            background: #f5f7f6;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .attachment-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .attachment-card-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .attachment-rename {
            border: 0;
            background: transparent;
            color: #2f5f4a;
            cursor: pointer;
            font-size: 12px;
            padding: 2px 4px;
        }

        #itemsTable td {
            vertical-align: middle;
        }

        .money-input {
            position: relative;
        }

        .money-input span {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #5f6c66;
            font-weight: 600;
            pointer-events: none;
        }

        #itemsTable .unit-price {
            text-align: right;
            padding-left: 24px !important;
        }

        #estimatedAmount {
            padding-left: 32px !important;
        }

        #itemsTable .qty {
            text-align: center;
        }

        .table-struct-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin: 0 0 8px;
        }

        .table-lock-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            border-radius: 999px;
            padding: 7px 11px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
        }

        .table-lock-btn:hover {
            background: #f6faf8;
        }

        .table-lock-btn.is-locked {
            background: #f2f4f3;
            color: #596660;
        }

        .table-lock-btn .icon {
            font-size: 15px;
        }

        .table-lock-btn .text {
            font-size: 11px;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        #itemsTable {
            table-layout: fixed;
            width: 100%;
            min-width: 100%;
            max-width: 100%;
        }

        #itemsTable th,
        #itemsTable td {
            position: relative;
            min-width: 0;
        }

        #itemsTable th:first-child,
        #itemsTable td:first-child {
            min-width: 34px !important;
        }

        #itemsTable th.col-resize-hover::after,
        #itemsTable td.col-resize-hover::after {
            content: "";
            position: absolute;
            top: 0;
            right: -1px;
            width: 3px;
            height: 100%;
            background: rgba(23, 107, 74, .28);
            pointer-events: none;
        }

        #itemsTable tbody tr.row-resize-hover td {
            box-shadow: inset 0 -2px 0 rgba(23, 107, 74, .22);
        }

        body.table-resizing {
            user-select: none;
        }

        .quote-linked {
            color: var(--green-dark);
            font-weight: 800;
            font-size: 10px;
            text-align: center;
            margin-top: 3px;
        }


        @media(max-width:800px) {
            .page {
                margin: 0;
                border-radius: 0
            }

            header {
                grid-template-columns: 1fr;
                padding: 22px
            }

            main {
                padding: 18px
            }

            .grid>[class^=c] {
                grid-column: span 12
            }

            .sign-grid {
                grid-template-columns: 1fr
            }

            .sign-grid>div {
                border-right: 0
            }

            .sign-grid .head {
                display: none
            }

            .checklist {
                grid-template-columns: 1fr
            }

            .toolbar {
                position: static
            }
        }

        .fill-mode-control {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px;
            border: 1px solid #cfd9d4;
            border-radius: 10px;
            background: #f8faf9;
        }

        .fill-mode-label {
            padding: 0 6px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #5f6c65;
            white-space: nowrap;
        }

        .fill-mode-btn {
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            padding: 7px 10px !important;
            border-radius: 7px !important;
            color: #53615a !important;
            font-weight: 700 !important;
        }

        .fill-mode-btn.active {
            background: #fff !important;
            color: var(--green-dark) !important;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .10) !important;
        }

        .step-navigator {
            margin: 14px 18px 0;
            padding: 13px 15px;
            border: 1px solid #cfdad4;
            border-radius: 12px;
            background: #fbfdfc;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .04);
        }

        .step-nav-top {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: flex-start
        }

        .step-nav-eyebrow {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--green-dark)
        }

        .step-nav-title {
            margin-top: 3px;
            font-size: 14px;
            font-weight: 800;
            color: var(--ink)
        }

        .step-nav-completion {
            font-size: 10.5px;
            color: var(--muted);
            text-align: right
        }

        .step-nav-progress {
            height: 5px;
            margin: 10px 0;
            background: #e8eeeb;
            border-radius: 999px;
            overflow: hidden
        }

        .step-nav-progress span {
            display: block;
            height: 100%;
            width: 10%;
            background: var(--green);
            transition: width .18s ease
        }

        .step-nav-actions {
            display: flex;
            align-items: center;
            gap: 8px
        }

        body.step-mode main>.form-step {
            display: none
        }

        body.step-mode main>.form-step.active-step {
            display: block
        }

        .validation-missing {
            border-color: #c94b4b !important;
            box-shadow: 0 0 0 3px rgba(201, 75, 75, .11) !important;
            background: #fffafa !important
        }

        .validation-missing-group {
            border: 1px solid #e1a8a8;
            background: #fffafa;
            border-radius: 9px;
            padding: 6px
        }

        .structured-cell-editor {
            min-width: 150px
        }

        .structured-entry-list {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .structured-entry {
            display: flex;
            align-items: center;
            gap: 5px
        }

        .structured-entry input {
            width: 100%;
            min-width: 0;
            padding: 7px 8px;
            font-size: 11px
        }

        .structured-entry-remove {
            flex: 0 0 auto;
            width: 25px;
            height: 25px;
            padding: 0;
            border-radius: 7px;
            border: 1px solid #e2b9b9;
            color: #9d2f2f;
            background: #fffafa;
            font-size: 13px;
            line-height: 1
        }

        .structured-add-btn {
            width: 100%;
            margin-top: 6px;
            padding: 6px 7px;
            border: 1px dashed #aac7b9;
            background: #f7fbf9;
            color: var(--green-dark);
            font-size: 10px;
            font-weight: 800;
            border-radius: 7px
        }

        .structured-add-btn:hover {
            background: var(--green-soft)
        }

        .quote-view-btn:disabled,
        #lotQuoteViewBtn:disabled {
            opacity: .38;
            cursor: not-allowed;
            filter: none
        }

        .quote-view-list {
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .quote-view-card {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 10px;
            align-items: center;
            padding: 10px 11px;
            border: 1px solid var(--line);
            border-radius: 9px;
            background: #fff
        }

        .quote-view-name {
            min-width: 0;
            font-size: 12px;
            font-weight: 700;
            color: var(--ink);
            overflow-wrap: anywhere
        }

        .quote-view-meta {
            margin-top: 2px;
            font-size: 10px;
            color: var(--muted)
        }

        .form-timer {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 130px;
            padding: 5px 10px;
            border: 1px solid #cfd9d4;
            border-radius: 9px;
            background: #f8faf9;
        }

        .form-timer-icon {
            font-size: 18px;
            color: var(--green-dark);
            line-height: 1
        }

        .form-timer-label {
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #69766f
        }

        .form-timer-value {
            font-size: 13px;
            font-weight: 800;
            color: var(--green-dark);
            font-variant-numeric: tabular-nums
        }

        .submission-area {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 16px 18px;
            margin: 6px 0 18px;
            border: 1px solid #bcd6c9;
            border-radius: 12px;
            background: #f3faf6;
        }

        .submission-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--green-dark)
        }

        .submission-help {
            margin-top: 3px;
            max-width: 760px;
            font-size: 11px;
            line-height: 1.45;
            color: var(--muted)
        }

        .submission-btn {
            min-width: 185px
        }

        .submission-statistics {
            margin: 6px 0 18px;
            padding: 16px 18px;
            border: 1px solid #cbd8d1;
            border-radius: 12px;
            background: #fbfdfc;
        }

        .submission-statistics-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .submission-statistics-kicker {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--green-dark)
        }

        .submission-statistics h3 {
            margin: 3px 0 0;
            font-size: 15px
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 9px;
            margin: 12px 0;
        }

        .metric-card {
            padding: 10px;
            border: 1px solid #dce5e0;
            border-radius: 9px;
            background: #fff;
        }

        .metric-card .metric-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--muted);
            font-weight: 800
        }

        .metric-card .metric-value {
            margin-top: 4px;
            font-size: 15px;
            font-weight: 800;
            color: var(--ink);
            overflow-wrap: anywhere
        }

        .metrics-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 12px;
        }

        .metrics-list {
            margin-top: 6px;
            border: 1px solid #e1e7e4;
            border-radius: 9px;
            background: #fff;
            overflow: hidden;
        }

        .metrics-list-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 10px;
            border-bottom: 1px solid #edf1ef;
            font-size: 11px;
        }

        .metrics-list-row:last-child {
            border-bottom: 0
        }

        .metrics-list-row strong {
            color: var(--green-dark);
            white-space: nowrap
        }

        .metrics-note {
            margin-top: 12px;
            font-size: 10px;
            color: var(--muted);
            line-height: 1.4
        }

        /* Campo hora - Formulario PRZ */
        .input-hora-prz {
            display: block;
            width: 100%;
            min-width: 0;
            height: 50px;
            padding: 10px 14px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 400;
            color: #34423d;
            background-color: #ffffff;
            border: 1px solid #cbd5d1;
            border-radius: 11px;
            box-sizing: border-box;
        }

        .input-hora-prz:focus {
            border-color: #176b4a;
            box-shadow: 0 0 0 3px rgba(23, 107, 74, 0.12);
            outline: none;
        }

        /* Icono del reloj */
        .input-hora-prz::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.75;
        }

        /* Igualar altura de fecha, hora, select y texto */
        .formulario-calificacion select,
        .formulario-calificacion input[type="date"],
        .formulario-calificacion input[type="time"],
        .formulario-calificacion input[type="text"] {
            height: 50px;
            box-sizing: border-box;
        }

        /* Fondo del modal */
        #emergencyEvidenceModal {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.45);
        }

        #emergencyEvidenceModal.hidden {
            display: none !important;
        }

        #emergencyEvidenceModal .modal-card {
            width: min(1120px, 100%);
            max-height: calc(100dvh - 40px);
            overflow-y: auto;
            margin: 0;
            background: white;
            border-radius: 14px;
        }

        /* Mantener oculto cuando corresponda */
        #emergencyEvidenceModal.hidden {
            display: none !important;
        }

        /* Ventana blanca centrada */
        #emergencyEvidenceModal {
            position: fixed;
            inset: 0;
            z-index: 10000;

            background: rgba(17, 28, 23, 0.45);
        }

        #emergencyEvidenceModal.hidden {
            display: none !important;
        }

        #emergencyEvidenceModal>.modal-card {
            position: absolute;
            width: min(1120px, calc(100% - 40px));
            background: white;
            border-radius: 14px;
            border: 1px solid #d8e0dc;
            box-shadow: 0 18px 55px rgba(0, 0, 0, 0.25);
        }

        /* Encabezado del modal E/U/I */
        #emergencyEvidenceModal .modal-head {
            position: relative;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;

            padding: 18px 20px;
            border-bottom: 1px solid #d8e0dc;
            background: #fafcfb;
        }

        /* Texto del encabezado */
        #emergencyEvidenceModal .modal-head>div {
            flex: 1;
            min-width: 0;
        }

        /* Botón X en esquina superior derecha */
        #emergencyEvidenceModal .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
            border: none;
            border-radius: 50%;
            background: transparent;

            color: #34423d;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            z-index: 10;
        }

        #emergencyEvidenceModal .modal-close:hover {
            background: #eef2f0;
            color: #176b4a;
        }

        /* Evitar que el texto se superponga con la X */
        #emergencyEvidenceModal .modal-head {
            padding-right: 60px;
        }

        @media(max-width:760px) {
            .metrics-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .metrics-columns {
                grid-template-columns: 1fr
            }

            .submission-area {
                align-items: stretch;
                flex-direction: column
            }

            .submission-btn {
                width: 100%
            }

            .form-timer {
                min-width: 0
            }
        }

        @media print {
            @page {
                size: A4;
                margin: 9mm
            }

            body {
                background: white;
                font-size: 10pt
            }

            .toolbar,
            .no-print,
            details {
                display: none !important
            }

            .submission-statistics {
                display: block !important;
                break-inside: avoid
            }

            .page {
                max-width: none;
                margin: 0;
                box-shadow: none;
                border-radius: 0
            }

            header {
                padding: 14px 18px;
                background: white !important;
                color: black;
                border-bottom: 2px solid var(--green)
            }

            header p {
                color: #444
            }

            .docmeta {
                border: 1px solid #aaa;
                background: white
            }

            main {
                padding: 12px 0
            }

            section {
                margin-bottom: 10px;
                border-radius: 6px
            }

            .section-head {
                padding: 8px 10px
            }

            .section-body {
                padding: 10px
            }

            input,
            select,
            textarea {
                border: 0 !important;
                border-bottom: 1px solid #777 !important;
                border-radius: 0 !important;
                padding: 3px !important
            }

            .choice {
                padding: 3px 6px
            }

            .print-only {
                display: block
            }

            .subbox {
                padding: 8px
            }

            table {
                font-size: 8.5pt
            }

            th,
            td {
                padding: 4px
            }
        }

        /* Fondo de advertencia PRZ */
        .emergency-alert-backdrop {
            position: fixed;
            inset: 0;
            z-index: 20000;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;
            background: rgba(17, 28, 23, 0.55);
        }

        .emergency-alert-backdrop.hidden {
            display: none !important;
        }

        /* Ventana de advertencia */
        .emergency-alert-card {
            width: min(460px, 100%);
            max-height: calc(100dvh - 40px);

            background: #ffffff;
            border: 1px solid #d8e0dc;
            border-radius: 14px;

            box-shadow: 0 18px 55px rgba(0, 0, 0, 0.25);

            overflow-y: auto;
        }

        /* Encabezado */
        .emergency-alert-card .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 16px 20px;

            background: #fafcfb;
            border-bottom: 1px solid #d8e0dc;
        }

        .emergency-alert-card .modal-head h3 {
            font-size: 16px;
            margin: 0;
            color: #17211d;
        }

        /* Botón X */
        .emergency-alert-card .modal-close {
            position: static;
            width: 30px;
            height: 30px;
            padding: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border: none;
            border-radius: 50%;
            background: transparent;

            color: #34423d;
            font-size: 22px;
            cursor: pointer;
        }

        /* Mensaje */
        .emergency-alert-card .modal-body {
            padding: 22px 20px;
            font-size: 13px;
            color: #44514b;
            line-height: 1.6;
        }

        .emergency-alert-card .modal-body p {
            margin: 0;
        }

        /* Pie */
        .emergency-alert-card .modal-footer {
            display: flex;
            justify-content: flex-end;

            padding: 14px 20px;

            background: #fafcfb;
            border-top: 1px solid #d8e0dc;
        }

        /* Botón institucional */
        .emergency-alert-card .modal-footer .primary {
            background: #176b4a;
            color: #ffffff;
            border: 1px solid #176b4a;
            border-radius: 8px;

            padding: 10px 18px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .emergency-alert-card .modal-footer .primary:hover {
            background: #0f4e37;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/mammoth@1.8.0/mammoth.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</head>

<body>
    <div class="toolbar no-print">
        <div class="fill-mode-control" aria-label="Modo de llenado">
            <span class="fill-mode-label">Modo de llenado</span>
            <button id="modeFormBtn" type="button" class="fill-mode-btn active"
                onclick="setFillMode('form')">Formulario
                completo</button>
            <button id="modeStepBtn" type="button" class="fill-mode-btn" onclick="setFillMode('step')">Paso a
                paso</button>
        </div>
        <button type="button" onclick="validateWholeForm()">Validar obligatorios</button>
        <button class="primary" onclick="window.print()">Imprimir / guardar PDF</button>
        <button onclick="clearForm()">Limpiar formulario</button>
        <div class="grow"></div>
        <div class="form-timer" title="Tiempo transcurrido desde que se abrió el formulario">
            <span class="form-timer-icon">◷</span>
            <div>
                <div class="form-timer-label">Tiempo de llenado</div>
                <div id="formElapsedTimer" class="form-timer-value">00:00:00</div>
            </div>
        </div>
        <div class="progress-wrap">
            <div class="progress"><span id="progressBar"></span></div>
            <div class="progress-label" id="progressLabel">0% esenciales</div>
        </div>
    </div>

    <div class="page">
        <header>
            <div>
                <div class="brandline">Parque Pedro del Río Zañartu · Abastecimiento</div>
                <h1>Formulario de Requerimiento de Compra, Contratación o Arrendamiento</h1>
                <p>Antecedentes mínimos para analizar la necesidad, verificar planificación y presupuesto, definir el
                    mecanismo de compra y preparar correctamente el proceso de contratación.</p>
            </div>
            <div class="docmeta">
                <div><strong>Código</strong> RQ-01</div>
                <div><strong>Versión</strong> 0.65 — propuesta</div>
                <div><strong>Estado</strong> En revisión</div>
                <div><strong>Folio</strong> <input id="folio" type="text"
                        style="margin-top:5px;background:rgba(255,255,255,.95)"></div>
            </div>
        </header>
        <div id="stepNavigator" class="step-navigator no-print hidden">
            <div class="step-nav-top">
                <div>
                    <div class="step-nav-eyebrow">Llenado paso a paso</div>
                    <div id="stepNavTitle" class="step-nav-title">Paso 1</div>
                </div>
                <div id="stepNavCompletion" class="step-nav-completion">Complete los campos obligatorios para avanzar.
                </div>
            </div>
            <div class="step-nav-progress"><span id="stepNavProgressBar"></span></div>
            <div class="step-nav-actions">
                <button id="stepPrevBtn" type="button" onclick="previousStep()">← Anterior</button>
                <div class="grow"></div>
                <button id="stepNextBtn" type="button" class="primary" onclick="nextStep()">Siguiente →</button>
            </div>
        </div>


        <main>

            <section class="form-step" data-step="1">
                <div class="section-head">
                    <div class="num">1</div>
                    <div>
                        <h2>Identificación de la solicitud</h2><small>Quién requiere, quién autoriza y cuándo se
                            necesita.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div class="grid">
                        <div class="field c4">

                            <label class="req">
                                Área / unidad requirente
                            </label>

                            @php
                                $usuario = auth()->user();

                                $departamento = $usuario?->departamento;
                            @endphp

                            <input class="essential" id="requestingUnit" name="departamento_nombre" type="text"
                                value="{{ $departamento?->nombre ?? '' }}" placeholder="Sin departamento asignado"
                                readonly required
                                style="
                                        background-color: #ffffff;
                                        cursor: not-allowed;
                                        opacity: 1;
                                      ">

                            <input type="hidden" name="departamento_id" value="{{ $departamento?->id ?? '' }}">

                            @if (!$departamento)
                                <div class="alert amber">
                                    Tu usuario no tiene un departamento asignado.
                                    Solicita al administrador que actualice tu información.
                                </div>
                            @endif

                        </div>
                        <div class="field c4"><label class="req">Responsable del requerimiento</label>
                            @php
                                $usuario = auth()->user();

                                $nombreCompleto = trim(($usuario?->name ?? '') . ' ' . ($usuario?->apellido ?? ''));

                                $cargo = $usuario?->cargo;

                                $responsable = $nombreCompleto . ($cargo?->nombre ? ' / ' . $cargo->nombre : '');
                            @endphp

                            <input class="essential" id="responsableRequerimiento" name="responsable_requerimiento"
                                type="text" value="{{ $responsable }}" placeholder="Sin responsable asignado"
                                readonly required
                                style="
                                    background-color: #ffffff;
                                    cursor: not-allowed;
                                    opacity: 1;
                                ">
                        </div>
                        <div class="field c4"><label>Correo</label>
                            @php
                                $usuario = auth()->user();
                            @endphp

                            <input class="essential" id="email" name="correo" type="email"
                                value="{{ $usuario?->email ?? '' }}" placeholder="Sin correo registrado" readonly
                                required
                                style="
                                    background-color: #ffffff;
                                    color: #334155;
                                    cursor: not-allowed;
                                ">
                        </div>

                        <div class="field c4"><label class="req">Fecha de solicitud</label><input type="date"
                                name="fecha" id="fecha" class="form-control"
                                min="{{ now()->timezone('America/Santiago')->format('Y-m-d') }}"
                                value="{{ old('fecha', now()->timezone('America/Santiago')->format('Y-m-d')) }}"
                                required>
                        </div>

                        <div class="field c4"><label class="req">Fecha en que se requiere</label><input
                                class="essential" type="date"></div>
                        <div class="field c4"><label>Prioridad</label>
                            <select id="priority" onchange="togglePriority()">
                                <option value="normal">Planificada / normal</option>
                                <option value="urgent">Urgente</option>
                                <option value="critical">Crítica para continuidad</option>
                            </select>
                        </div>

                        <div id="urgentBox" class="field c12 hidden">
                            <label class="req">Fundamento de la urgencia o criticidad</label>
                            <textarea class="short"
                                placeholder="Indique hechos concretos, fecha conocida de la necesidad y consecuencias de no contratar oportunamente."></textarea>
                            <div class="alert amber">La urgencia declarada por la unidad no determina por sí sola el
                                mecanismo de compra; Abastecimiento deberá revisar si existe una causal legal aplicable.
                            </div>
                        </div>



                        <div class="field c12">
                            <label
                                title="Indique si este requerimiento necesita, además, una solicitud de calificación como emergencia, urgencia o imprevisto.">¿El
                                requerimiento requiere una solicitud de calificación de emergencia, urgencia o
                                imprevisto?</label>
                            <div class="choice-row">
                                <label class="choice"><input type="radio" name="emergencyNeedFlag" value="si"
                                        onchange="toggleEmergencyNeed()"> Sí</label>
                                <label class="choice"><input type="radio" name="emergencyNeedFlag" value="no"
                                        onchange="toggleEmergencyNeed()"> No</label>
                            </div>
                            <div class="hint">Si selecciona “Sí”, se habilita el módulo para incorporar la solicitud
                                y sus antecedentes. Si selecciona “No”, quedará registrado que esta acción no aplica.
                            </div>
                        </div>

                        <div id="emergencyRequestOptionBox" class="field c12 hidden">
                            <label
                                title="Utilice esta opción cuando, además del requerimiento de compra, sea necesario dejar formulada una solicitud de calificación como emergencia, urgencia o imprevisto, con sus antecedentes de respaldo.">Solicitud
                                de calificación del requerimiento como emergencia, urgencia o imprevisto</label>
                            <div class="soft-card">
                                <div class="compact-title">Activación opcional de calificación especial</div>
                                <div class="tool-group">
                                    <button id="emergencyRequestToggleBtn" type="button" class="small-btn"
                                        onclick="toggleEmergencyRequestPanel()">Incorporar solicitud de calificación
                                        E/U/I</button>
                                    <span id="emergencyRequestStatus" class="status-badge">No incorporada</span>
                                </div>
                                <div class="hint">Si el área requirente necesita solicitar una evaluación por
                                    emergencia, urgencia o imprevisto, utilice este botón y complete los antecedentes.
                                    El bloque se despliega dentro de esta misma sección.</div>
                            </div>
                        </div>


                        <div id="emergencyRequestPanel" class="field c12 hidden">
                            <div class="subbox" style="margin:0;">
                                <div class="mini-title">Solicitud de calificación de emergencia, urgencia o imprevisto
                                </div>
                                <div class="hint" style="margin-bottom:12px;">Complete los antecedentes necesarios
                                    para que la autoridad o unidad que evalúe la calificación cuente, desde el inicio,
                                    con la narración de los hechos, fundamentos, afectación, solución requerida y medios
                                    de respaldo.</div>

                                <div class="grid">
                                    <div class="field c3">
                                        <label class="req eui-help-label"
                                            title="Seleccione el tipo de calificación que se está solicitando. Al abrir la lista, pose el cursor sobre cada alternativa para revisar su definición operativa."
                                            data-eui-help="tipo" tabindex="0">Tipo de calificación
                                            solicitada</label>

                                        <div id="emergencyTypeCombo">

                                            <select id="emergencyQualificationType" name="emergencyQualificationType"
                                                class="essential" required>
                                                <option value="">Seleccione</option>
                                                <option value="Emergencia">Emergencia</option>
                                                <option value="Urgencia">Urgencia</option>
                                                <option value="Imprevisto">Imprevisto</option>
                                            </select>

                                        </div>

                                        <div class="hint">Pose el cursor sobre “Emergencia”, “Urgencia” o
                                            “Imprevisto” para ver qué se entiende por cada concepto antes de
                                            seleccionarlo.</div>
                                    </div>
                                    <div class="field c3">
                                        <label
                                            title="Fecha en que ocurrió el hecho o en que fue detectada formalmente la situación."
                                            class="eui-help-label" data-eui-help="fecha" tabindex="0">Fecha del
                                            hecho o detección</label>
                                        <input type="date">
                                    </div>
                                    <div class="field c3">
                                        <label
                                            title="Hora del hecho o del momento en que la situación fue advertida, si se conoce."
                                            class="eui-help-label" data-eui-help="hora" tabindex="0">Hora del hecho
                                            o detección</label>
                                        <input type="time" id="hora_hecho" name="hora_hecho"
                                            value="{{ old('hora_hecho') }}" required>
                                    </div>
                                    <div class="field c3">
                                        <label
                                            title="Lugar, recinto, dependencia o punto específico donde ocurre o impacta el hecho."
                                            class="eui-help-label" data-eui-help="lugar" tabindex="0">Lugar o
                                            dependencia afectada</label>
                                        <input type="text"
                                            placeholder="Ej.: Museo, acceso principal, sendero, oficina, sistema, bodega">
                                    </div>

                                    <div class="field c12">
                                        <label class="req eui-help-label"
                                            title="Describa cronológicamente qué ocurrió, cómo se detectó, quién intervino inicialmente y cuál es la situación actual."
                                            data-eui-help="narracion" tabindex="0">Narración circunstanciada de los
                                            hechos</label>
                                        <textarea
                                            placeholder="Relate en forma clara y cronológica lo ocurrido: contexto, hecho gatillante, fecha u hora, personas o áreas involucradas, acciones iniciales y situación actual."></textarea>
                                    </div>

                                    <div class="field c6">
                                        <label class="req eui-help-label"
                                            title="Explique por qué correspondería calificar la situación como emergencia, urgencia o imprevisto."
                                            data-eui-help="fundamentos" tabindex="0">Fundamentos de la solicitud de
                                            calificación</label>
                                        <textarea
                                            placeholder="Fundamente la procedencia de la calificación solicitada, indicando por qué la necesidad no puede esperar la tramitación ordinaria o qué elemento sobreviniente la justifica."></textarea>
                                    </div>
                                    <div class="field c6">
                                        <label
                                            title="Señale el servicio, función, actividad, instalación o continuidad operacional afectada por la situación."
                                            class="eui-help-label" data-eui-help="continuidad"
                                            tabindex="0">Servicio, función o continuidad afectada</label>
                                        <textarea placeholder="Indique qué actividad, servicio o función institucional se ve afectada y en qué magnitud."></textarea>
                                    </div>

                                    <div class="field c6">
                                        <label
                                            title="Explique qué consecuencias prácticas se producirían si no se actúa de inmediato."
                                            class="eui-help-label" data-eui-help="consecuencias"
                                            tabindex="0">Consecuencias de no actuar oportunamente</label>
                                        <textarea
                                            placeholder="Ej.: riesgo para personas, interrupción del servicio, pérdida de bienes, incumplimiento de obligaciones, daño reputacional o patrimonial, afectación a usuarios, etc."></textarea>
                                    </div>
                                    <div class="field c6">
                                        <label
                                            title="Señale qué bienes, servicios, trabajos o medidas se requieren para enfrentar la situación."
                                            class="eui-help-label" data-eui-help="medidas" tabindex="0">Bienes,
                                            servicios o medidas requeridas</label>
                                        <textarea
                                            placeholder="Describa brevemente la solución inmediata requerida, incluyendo si se trata de compra, contratación, arrendamiento, reparación, reposición o apoyo externo."></textarea>
                                    </div>

                                    <div class="field c4">
                                        <label
                                            title="Indique el plazo máximo en que debe resolverse o iniciarse la respuesta."
                                            class="eui-help-label" data-eui-help="plazo" tabindex="0">Plazo máximo
                                            de atención</label>
                                        <input type="text"
                                            placeholder="Ej.: dentro de 24 horas / inicio inmediato / antes de 48 horas">
                                    </div>
                                    <div class="field c4">
                                        <label
                                            title="Indique si existe una alternativa de mitigación o continuidad temporal mientras se resuelve la situación."
                                            class="eui-help-label" data-eui-help="mitigacion" tabindex="0">¿Existe
                                            medida de mitigación temporal?</label>
                                        <select>
                                            <option value="">Seleccione</option>
                                            <option>No</option>
                                            <option>Sí, parcial</option>
                                            <option>Sí, suficiente de forma temporal</option>
                                        </select>
                                    </div>
                                    <div class="field c4">
                                        <label
                                            title="Monto aproximado de la solución requerida, si puede estimarse preliminarmente."
                                            class="eui-help-label" data-eui-help="monto" tabindex="0">Monto
                                            aproximado, si se conoce</label>
                                        <input type="text" placeholder="Ej.: $ 1.500.000 aprox.">
                                    </div>

                                    <div class="field c6">
                                        <label
                                            title="Medidas inmediatas que ya se adoptaron antes de solicitar la calificación."
                                            class="eui-help-label" data-eui-help="provisorias" tabindex="0">Medidas
                                            provisorias adoptadas</label>
                                        <textarea class="short"
                                            placeholder="Describa medidas preventivas, contenciones o acciones preliminares ya ejecutadas."></textarea>
                                    </div>
                                    <div class="field c6">
                                        <label
                                            title="Indique si existe una jefatura, contraparte o funcionario que respalde o informe técnicamente la solicitud."
                                            class="eui-help-label" data-eui-help="responsable"
                                            tabindex="0">Responsable o contraparte que informa</label>
                                        <textarea class="short"
                                            placeholder="Nombre, cargo, área y forma de contacto de quien informa o respalda técnicamente la situación."></textarea>
                                    </div>

                                    <div class="field c12">
                                        <label title="Indique cualquier antecedente adicional útil para la evaluación."
                                            class="eui-help-label" data-eui-help="observaciones"
                                            tabindex="0">Observaciones complementarias</label>
                                        <textarea class="short"
                                            placeholder="Antecedentes complementarios, restricciones operativas, referencias a informes, inspecciones, visitas en terreno, comunicaciones o instrucciones previas."></textarea>
                                    </div>

                                    <div class="field c12">
                                        <label
                                            title="Adjunte antecedentes de respaldo para la evaluación de la solicitud de calificación. Los archivos pueden vincularse a uno o más tipos de antecedente, con miniaturas, vista previa ampliada y arrastrar y soltar."
                                            class="eui-help-label" data-eui-help="antecedentes"
                                            tabindex="0">Antecedentes de respaldo para evaluación</label>
                                        <div class="soft-card">
                                            <div class="compact-title">Adjuntar archivos de respaldo para la
                                                calificación</div>
                                            <div class="tool-group">
                                                <button type="button" class="small-btn"
                                                    onclick="openEmergencyEvidenceManager()">Adjuntar
                                                    antecedentes</button>
                                                <span id="emergencyEvidenceStatus" class="status-badge">Sin
                                                    antecedentes vinculados</span>
                                            </div>
                                            <div id="emergencyEvidenceList" class="mini-file-list">Puede adjuntar
                                                imágenes, PDF, Word, Excel u otros archivos y vincularlos al tipo de
                                                antecedente que respaldan.</div>
                                            <div class="attachment-register">
                                                <div class="mini-title">Antecedentes ya vinculados</div>
                                                <div id="emergencyEvidenceRegisterEmpty" class="hint">Aún no hay
                                                    antecedentes de respaldo vinculados.</div>
                                                <div id="emergencyEvidenceRegister"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field c6"><label class="req">Jefatura que autoriza el
                                requerimiento</label>
                            @php
                                $usuario = auth()->user();

                                $jefe = $usuario?->jefe;

                                $nombreJefe = $jefe ? trim($jefe->name . ' ' . $jefe->apellido) : '';

                                $cargoJefe = $jefe?->cargo?->nombre ?? '';

                                $textoJefe = $nombreJefe;

                                if ($cargoJefe) {
                                    $textoJefe .= ' / ' . $cargoJefe;
                                }
                            @endphp

                            <input class="essential" id="jefatura" name="jefatura" type="text"
                                value="{{ $textoJefe }}" placeholder="Sin jefatura asignada" readonly required
                                style="
                                    background-color: #ffffff;
                                    color: #334155;
                                    cursor: not-allowed;
                                ">
                        </div>
                        <div class="field c6"><label>Firma / visto bueno de jefatura</label>
                            <div class="signature-line"></div>
                            <div class="hint">Firma manuscrita o electrónica, según procedimiento interno.</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="form-step" data-step="2">
                <div class="section-head">
                    <div class="num">2</div>
                    <div>
                        <h2>Necesidad y objeto de la contratación</h2><small>Primero se explica el problema o necesidad;
                            luego la solución a adquirir.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div class="grid">
                        <div class="field c12">
                            <label class="req">Tipo de requerimiento</label>
                            <div class="choice-row">
                                <label class="choice"><input class="essential" type="radio" name="kind"
                                        value="bien" onchange="toggleKind()"> Compra de bienes</label>
                                <label class="choice"><input class="essential" type="radio" name="kind"
                                        value="servicio" onchange="toggleKind()"> Contratación de servicios</label>
                                <label class="choice"><input class="essential" type="radio" name="kind"
                                        value="arriendo" onchange="toggleKind()"> Arrendamiento</label>
                                <label class="choice"><input class="essential" type="radio" name="kind"
                                        value="mixto" onchange="toggleKind()"> Mixto</label>
                            </div>
                        </div>
                        <div class="field c12"><label class="req">Nombre breve / objeto de la compra <span
                                    style="font-weight:600;text-transform:none;letter-spacing:normal;font-size:11px;">(Debe
                                    sintetizar de forma clara la necesidad de compra)</span></label><input
                                class="essential" type="text"
                                placeholder="Ej.: Servicio de producción técnica para actividad institucional"></div>
                        <div class="field c12"><label class="req">Necesidad que se busca satisfacer y
                                justificación</label>
                            <textarea class="essential"
                                placeholder="Explique qué problema o necesidad institucional existe, por qué se requiere la contratación y qué ocurriría si no se realiza. Evite justificar solamente indicando el nombre del producto."></textarea>
                        </div>
                        <div class="field c6"><label>Resultado esperado / finalidad institucional</label>
                            <textarea class="short" placeholder="Resultado verificable que debe obtenerse con la compra."></textarea>
                        </div>
                        <div class="field c3"><label>Nº beneficiarios / usuarios</label><input type="number"
                                min="0" placeholder="Si aplica"></div>
                        <div class="field c3"><label>Frecuencia de uso</label>
                            <select id="freqUseSelect" onchange="toggleOtherFrequency()">
                                <option>No aplica</option>
                                <option>Única</option>
                                <option>Ocasional</option>
                                <option>Mensual</option>
                                <option>Semanal</option>
                                <option>Continua</option>
                                <option value="otra">Otra</option>
                            </select>
                        </div>
                        <div id="otherFrequencyBox" class="field c3 hidden"><label>Otra frecuencia
                                (especifique)</label><input type="text"
                                placeholder="Escriba otra frecuencia de uso"></div>
                        <div class="field c6"><label class="req">Lugar de entrega o prestación</label>
                            <div id="deliveryPlaceCombo" class="delivery-place-combo">
                                <button id="deliveryPlaceButton" type="button"
                                    class="delivery-place-button essential" aria-haspopup="listbox"
                                    aria-expanded="false" onclick="toggleDeliveryPlaceDropdown()">
                                    <span id="deliveryPlaceLabel">Parque Museo Pedro del Río Zañartu — Fundo Hualpén
                                        S/N, Camino a la Desembocadura, Hualpén, Concepción, Biobío</span>
                                    <span class="delivery-place-chevron">⌄</span>
                                </button>
                                <div id="deliveryPlaceDropdown" class="delivery-place-dropdown hidden" role="listbox"
                                    aria-label="Lugar de entrega o prestación">
                                    <button type="button" class="delivery-place-option"
                                        onclick="selectDeliveryPlace('prz','Parque Museo Pedro del Río Zañartu — Fundo Hualpén S/N, Camino a la Desembocadura, Hualpén, Concepción, Biobío')">Parque
                                        Museo Pedro del Río Zañartu — Fundo Hualpén S/N, Camino a la Desembocadura,
                                        Hualpén, Concepción, Biobío</button>
                                    <button type="button" class="delivery-place-option"
                                        onclick="selectDeliveryPlace('otro','Otro lugar')">Otro lugar</button>
                                </div>
                                <select class="essential hidden" id="deliveryPlace" aria-hidden="true"
                                    tabindex="-1" onchange="toggleOtherDeliveryPlace()">
                                    <option value="prz">Parque Museo Pedro del Río Zañartu — Fundo Hualpén S/N,
                                        Camino a la Desembocadura, Hualpén, Concepción, Biobío</option>
                                    <option value="otro">Otro lugar</option>
                                </select>
                            </div>
                            <div class="hint">Dirección obtenida del sitio oficial del Parque.</div>
                        </div>
                        <div id="otherDeliveryPlaceBox" class="field c6 hidden"><label class="req">Otro lugar de
                                entrega o prestación</label><input class="essential" type="text"
                                placeholder="Indique dependencia, dirección o lugar de ejecución"></div>
                        <div class="field c6"><label>Contraparte técnica propuesta</label>
                            <div class="choice-row" style="margin-bottom:8px;">
                                <label class="choice"><input type="radio" name="technicalCounterpartMode"
                                        value="same" onchange="toggleTechnicalCounterpartMode()"> Mismo
                                    requirente</label>
                                <label class="choice"><input type="radio" name="technicalCounterpartMode"
                                        value="different" onchange="toggleTechnicalCounterpartMode()"> Contraparte
                                    técnica distinta al requirente</label>
                            </div>
                            <div class="hint">Seleccione si la contraparte técnica será la misma persona que formuló
                                el requerimiento o si corresponde designar a otra persona.</div>

                            <div id="technicalCounterpartFields" class="grid hidden" style="margin-top:10px;">
                                <div class="field c12"><label
                                        style="margin-top:2px;text-transform:none;letter-spacing:normal;font-size:11px;">Nombre</label><input
                                        id="technicalCounterpartName" type="text" placeholder="Nombre"></div>
                                <div class="field c6"><label
                                        style="margin-top:2px;text-transform:none;letter-spacing:normal;font-size:11px;">Unidad</label><input
                                        id="technicalCounterpartUnit" type="text" placeholder="Unidad"></div>
                                <div class="field c6"><label
                                        style="margin-top:2px;text-transform:none;letter-spacing:normal;font-size:11px;">Cargo</label><input
                                        id="technicalCounterpartRole" type="text" placeholder="Cargo"></div>
                                <div id="technicalCounterpartAutofillHint" class="field c12 hidden">
                                    <div class="hint">Los datos se completaron a partir de la información ingresada
                                        en la sección 1. Puede corregirlos si es necesario.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="form-step" data-step="3">
                <div class="section-head">
                    <div class="num">3</div>
                    <div>
                        <h2>PAC, planificación y control de duplicidades</h2><small>Permite detectar compras fuera de
                            planificación, acumulación de demanda y riesgos de fragmentación.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div class="grid">
                        <div class="field c4 planning-control-align">
                            <label class="req">¿La compra está identificada en el PAC?</label>
                            <div class="choice-row">
                                <label class="choice"><input class="essential" type="radio" name="pac"
                                        value="yes" onchange="togglePac()"> Sí</label>
                                <label class="choice"><input class="essential" type="radio" name="pac"
                                        value="no" onchange="togglePac()"> No</label>
                            </div>
                        </div>
                        <div id="pacYes" class="c8 hidden">
                            <div class="grid">
                                <div class="field c5"><label>ID / código PAC</label>
                                    <input type="text" placeholder="Código generado por el sistema PAC">
                                    <div class="hint">Este campo deberá vincularse automáticamente al código único de
                                        la compra planificada en el sistema PAC del PRZ.</div>
                                </div>
                                <div class="field c3">
                                    <label
                                        title="Mes en que esta compra se encuentra programada en el Plan Anual de Compras institucional.">Mes
                                        planificado de compra en el PAC</label>
                                    <select id="pacPlannedMonth">
                                        <option value="">Seleccione mes</option>
                                        <option>Enero</option>
                                        <option>Febrero</option>
                                        <option>Marzo</option>
                                        <option>Abril</option>
                                        <option>Mayo</option>
                                        <option>Junio</option>
                                        <option>Julio</option>
                                        <option>Agosto</option>
                                        <option>Septiembre</option>
                                        <option>Octubre</option>
                                        <option>Noviembre</option>
                                        <option>Diciembre</option>
                                    </select>
                                    <div class="hint">Seleccione el mes en que la compra está programada en el Plan
                                        Anual de Compras.</div>
                                </div>
                                <div class="field c4">
                                    <label
                                        title="Monto estimado que figura para esta compra en el Plan Anual de Compras institucional.">Monto
                                        estimado registrado en el PAC</label>
                                    <input id="pacEstimatedAmount" type="text" inputmode="numeric"
                                        placeholder="$ 0" oninput="formatPacAmount(this)">
                                    <div class="hint">Ingrese el monto estimado consignado para esta compra en el
                                        PAC, no el presupuesto disponible actual.</div>
                                </div>
                            </div>
                        </div>
                        <div id="pacNo" class="field c12 hidden">
                            <label class="req">Justificación de compra no contemplada en PAC</label>
                            <textarea class="short"
                                placeholder="Indique por qué la necesidad no pudo ser prevista y antecedente para gestionar la modificación/regularización del PAC, según corresponda."></textarea>
                            <div class="alert amber">El antecedente deberá conservarse para fundar el acto que autorice
                                contratar fuera del PAC y/o su modificación, según corresponda.</div>
                        </div>

                        <div class="field c4 planning-control-align"><label>¿Existe stock o bien institucional que
                                cubra la necesidad?</label>
                            <select>
                                <option value="">Seleccione</option>
                                <option>No</option>
                                <option>Sí, total</option>
                                <option>Sí, parcialmente</option>
                                <option>No aplica</option>
                            </select>
                        </div>
                        <div class="field c4 planning-control-align"><label>¿Existe contrato, convenio u OC vigente que
                                la cubra?</label>
                            <select>
                                <option value="">Seleccione</option>
                                <option>No</option>
                                <option>Sí</option>
                                <option>No se conoce</option>
                            </select>
                        </div>
                        <div class="field c4"><label
                                title="Antes de responder, revise el Plan Anual de Compras institucional y otros antecedentes disponibles para verificar si existen compras similares en esta u otras áreas.">¿Hay
                                requerimientos similares de otras áreas o dentro de la misma?</label>
                            <select id="similarRequirementsSelect"
                                onchange="handleSimilarRequirementsCheck(this.value)">
                                <option value="">Seleccione</option>
                                <option value="no">No</option>
                                <option value="si">Sí</option>
                                <option value="desconocido">No se conoce</option>
                            </select>
                            <div class="hint">La respuesta debe considerar, cuando sea posible, una revisión del PAC
                                institucional y de compras relacionadas.</div>
                        </div>
                        <div class="field c12">
                            <label>Antecedentes de compras relacionadas / posibilidad de agrupar demanda</label>
                            <textarea id="relatedPurchasesBackground" class="short"
                                placeholder="Luego de revisar el PAC institucional y otros antecedentes disponibles, indique OC, contratos, solicitudes relacionadas, compras similares del año y/o áreas que podrían requerir el mismo bien o servicio."></textarea>
                            <div class="hint">Consigne aquí el resultado de la revisión realizada, incluso cuando
                                concluya que no se identificaron compras similares.</div>
                        </div>
                    </div>
                    <div class="alert blue">Control recomendado: Abastecimiento debe revisar compras relacionadas antes
                        de definir el procedimiento, tanto para agrupar demanda cuando sea eficiente como para prevenir
                        una fragmentación improcedente.</div>
                </div>
            </section>




            <section class="form-step" data-step="4">
                <div class="section-head">
                    <div class="num">4</div>
                    <div>
                        <h2>Detalle, cantidades y especificaciones técnicas</h2><small>Especificaciones suficientes para
                            cotizar o preparar bases sin direccionar indebidamente la compra.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div class="table-struct-bar no-print">
                        <button id="tableStructureLockBtn" type="button" class="table-lock-btn"
                            title="Bloquear o desbloquear modificaciones estructurales de la tabla"
                            onclick="toggleTableStructureLock()">
                            <span class="icon">🔓</span>
                            <span class="text">Tabla editable</span>
                        </button>
                    </div>
                    <div style="overflow:auto">
                        <table id="itemsTable">
                            <thead>
                                <tr>
                                    <th title="Número correlativo de la línea o ítem del requerimiento."
                                        style="width:5%">Nº</th>
                                    <th title="Nombre del bien o servicio específico que se requiere contratar o adquirir."
                                        style="width:17%">Bien / servicio</th>
                                    <th title="Requisitos técnicos mínimos por cada ítem: prestaciones, compatibilidades, dimensiones, materiales, rendimiento u otros elementos indispensables."
                                        style="width:22%">Especificaciones técnicas</th>
                                    <th title="Características funcionales o de uso esperadas para cada ítem: propósito, desempeño esperado, condiciones de operación u otras características complementarias."
                                        style="width:19%">Características funcionales</th>
                                    <th title="Cantidad requerida para ese ítem. Se expresa en números enteros."
                                        style="width:8%">Cantidad</th>
                                    <th title="Unidad de medida del ítem. Ejemplos: unidad, caja, pack, litro, hora, jornada, mes o servicio."
                                        style="width:10%">Unidad de medida</th>
                                    <th title="Valor unitario estimado para cada ítem, expresado en pesos chilenos. El sistema separa automáticamente los miles con puntos mientras escribe."
                                        style="width:12%">Valor unitario estimado (CLP)</th>
                                    <th title="Resultado de multiplicar la cantidad por el valor unitario estimado."
                                        style="width:11%">Subtotal estimado</th>
                                    <th title="Adjunte una o más cotizaciones específicas para esta línea. En la parte inferior derecha de esta misma celda podrá eliminarse la fila."
                                        style="width:9%">Cotiz.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-item-key="item-1">
                                    <td>1</td>
                                    <td>
                                        <textarea class="item-name" placeholder="Nombre del bien o servicio"
                                            oninput="if(!document.getElementById('attachmentModal')?.classList.contains('hidden')) renderManagerItemList(); if(!document.getElementById('refImageModal')?.classList.contains('hidden')) renderRefImageItemList()"></textarea>
                                    </td>
                                    <td>
                                        <div class="structured-cell-editor item-spec-editor" data-kind="spec">
                                            <div class="structured-entry-list">
                                                <div class="structured-entry"><input class="item-spec-entry"
                                                        type="text" placeholder="Ej.: 16 GB de RAM"></div>
                                            </div>
                                            <button type="button" class="structured-add-btn"
                                                onclick="addStructuredEntry(this,'spec')">＋ Agregar
                                                especificación</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="structured-cell-editor item-feature-editor" data-kind="feature">
                                            <div class="structured-entry-list">
                                                <div class="structured-entry"><input class="item-feature-entry"
                                                        type="text" placeholder="Ej.: compatible con red 5G"></div>
                                            </div>
                                            <button type="button" class="structured-add-btn"
                                                onclick="addStructuredEntry(this,'feature')">＋ Agregar
                                                característica</button>
                                        </div>
                                    </td>
                                    <td><input class="qty" type="number" min="0" step="1"
                                            oninput="calcTotals()"></td>
                                    <td><input type="text" placeholder="Ej.: unidad, caja, pack, litro, hora"></td>
                                    <td>
                                        <div class="money-input"><span>$</span><input class="unit-price"
                                                type="text" inputmode="numeric" placeholder="0"
                                                oninput="sanitizeCurrencyInput(this); calcTotals()"></div>
                                    </td>
                                    <td class="subtotal right">$0</td>
                                    <td class="cotiz-cell">
                                        <div class="cotiz-wrap">

                                            <button type="button" class="icon-btn attach"
                                                title="Adjuntar o gestionar cotizaciones de esta fila"
                                                onclick="openAttachmentManager(this)">📎</button>
                                            <button type="button" class="icon-btn quote-view-btn"
                                                title="Visualizar cotizaciones vinculadas a esta fila"
                                                onclick="openRowQuoteViewer(this)" disabled>👁</button>
                                        </div>
                                        <div class="quote-linked quote-info">Sin cotización vinculada</div>
                                        <button type="button" class="trash-row" title="Eliminar esta fila"
                                            onclick="removeItemRow(this)">🗑</button>
                                    </td>
                                </tr>
                                <tr data-item-key="item-2">
                                    <td>2</td>
                                    <td>
                                        <textarea class="item-name" placeholder="Nombre del bien o servicio"
                                            oninput="if(!document.getElementById('attachmentModal')?.classList.contains('hidden')) renderManagerItemList(); if(!document.getElementById('refImageModal')?.classList.contains('hidden')) renderRefImageItemList()"></textarea>
                                    </td>
                                    <td>
                                        <div class="structured-cell-editor item-spec-editor" data-kind="spec">
                                            <div class="structured-entry-list">
                                                <div class="structured-entry"><input class="item-spec-entry"
                                                        type="text" placeholder="Ej.: 16 GB de RAM"></div>
                                            </div>
                                            <button type="button" class="structured-add-btn"
                                                onclick="addStructuredEntry(this,'spec')">＋ Agregar
                                                especificación</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="structured-cell-editor item-feature-editor" data-kind="feature">
                                            <div class="structured-entry-list">
                                                <div class="structured-entry"><input class="item-feature-entry"
                                                        type="text" placeholder="Ej.: compatible con red 5G"></div>
                                            </div>
                                            <button type="button" class="structured-add-btn"
                                                onclick="addStructuredEntry(this,'feature')">＋ Agregar
                                                característica</button>
                                        </div>
                                    </td>
                                    <td><input class="qty" type="number" min="0" step="1"
                                            oninput="calcTotals()"></td>
                                    <td><input type="text" placeholder="Ej.: unidad, caja, pack, litro, hora"></td>
                                    <td>
                                        <div class="money-input"><span>$</span><input class="unit-price"
                                                type="text" inputmode="numeric" placeholder="0"
                                                oninput="sanitizeCurrencyInput(this); calcTotals()"></div>
                                    </td>
                                    <td class="subtotal right">$0</td>
                                    <td class="cotiz-cell">
                                        <div class="cotiz-wrap">

                                            <button type="button" class="icon-btn attach"
                                                title="Adjuntar o gestionar cotizaciones de esta fila"
                                                onclick="openAttachmentManager(this)">📎</button>
                                            <button type="button" class="icon-btn quote-view-btn"
                                                title="Visualizar cotizaciones vinculadas a esta fila"
                                                onclick="openRowQuoteViewer(this)" disabled>👁</button>
                                        </div>
                                        <div class="quote-linked quote-info">Sin cotización vinculada</div>
                                        <button type="button" class="trash-row" title="Eliminar esta fila"
                                            onclick="removeItemRow(this)">🗑</button>
                                    </td>
                                </tr>
                                <tr data-item-key="item-3">
                                    <td>3</td>
                                    <td>
                                        <textarea class="item-name" placeholder="Nombre del bien o servicio"
                                            oninput="if(!document.getElementById('attachmentModal')?.classList.contains('hidden')) renderManagerItemList(); if(!document.getElementById('refImageModal')?.classList.contains('hidden')) renderRefImageItemList()"></textarea>
                                    </td>
                                    <td>
                                        <div class="structured-cell-editor item-spec-editor" data-kind="spec">
                                            <div class="structured-entry-list">
                                                <div class="structured-entry"><input class="item-spec-entry"
                                                        type="text" placeholder="Ej.: 16 GB de RAM"></div>
                                            </div>
                                            <button type="button" class="structured-add-btn"
                                                onclick="addStructuredEntry(this,'spec')">＋ Agregar
                                                especificación</button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="structured-cell-editor item-feature-editor" data-kind="feature">
                                            <div class="structured-entry-list">
                                                <div class="structured-entry"><input class="item-feature-entry"
                                                        type="text" placeholder="Ej.: compatible con red 5G"></div>
                                            </div>
                                            <button type="button" class="structured-add-btn"
                                                onclick="addStructuredEntry(this,'feature')">＋ Agregar
                                                característica</button>
                                        </div>
                                    </td>
                                    <td><input class="qty" type="number" min="0" step="1"
                                            oninput="calcTotals()"></td>
                                    <td><input type="text" placeholder="Ej.: unidad, caja, pack, litro, hora"></td>
                                    <td>
                                        <div class="money-input"><span>$</span><input class="unit-price"
                                                type="text" inputmode="numeric" placeholder="0"
                                                oninput="sanitizeCurrencyInput(this); calcTotals()"></div>
                                    </td>
                                    <td class="subtotal right">$0</td>
                                    <td class="cotiz-cell">
                                        <div class="cotiz-wrap">

                                            <button type="button" class="icon-btn attach"
                                                title="Adjuntar o gestionar cotizaciones de esta fila"
                                                onclick="openAttachmentManager(this)">📎</button>
                                            <button type="button" class="icon-btn quote-view-btn"
                                                title="Visualizar cotizaciones vinculadas a esta fila"
                                                onclick="openRowQuoteViewer(this)" disabled>👁</button>
                                        </div>
                                        <div class="quote-linked quote-info">Sin cotización vinculada</div>
                                        <button type="button" class="trash-row" title="Eliminar esta fila"
                                            onclick="removeItemRow(this)">🗑</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-bottom-bar">
                        <div class="tool-group">
                            <button type="button" class="small-btn" onclick="addItemRow()">＋ Agregar fila</button>
                        </div>
                        <div class="tool-group">
                            <button type="button" class="small-btn"
                                onclick="document.getElementById('lotQuoteInput').click()">Adjuntar cotización por
                                lote</button>
                            <button id="lotQuoteViewBtn" type="button" class="small-btn"
                                onclick="openLotQuoteViewer()" disabled>👁 Visualizar cotizaciones</button>
                            <span id="lotQuoteStatus" class="status-badge">Sin cotizaciones de lote</span>
                            <input id="lotQuoteInput" type="file" multiple
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" class="hidden"
                                onchange="updateLotQuoteInfo(this)">
                        </div>
                    </div>

                    <div class="amount-total"><span>Total referencial de líneas</span><strong
                            id="lineTotal">$0</strong></div>

                    <div class="grid" style="margin-top:15px">
                        <div class="field c12">
                            <label
                                title="Use este espacio solo para condiciones o requisitos que afecten transversalmente al conjunto de ítems, no a un producto específico. Las especificaciones técnicas y características funcionales por producto deben registrarse en la tabla superior.">Condiciones
                                o requisitos generales complementarios</label>
                            <textarea
                                placeholder="Use este espacio solo si existen condiciones generales comunes a todos los ítems o al lote completo: compatibilidades generales, exigencias transversales, coordinación operativa, etc."></textarea>
                            <div class="hint">Las especificaciones técnicas y las características funcionales de cada
                                producto o servicio deben consignarse por línea en la tabla superior.</div>
                        </div>

                        <div class="field c12">
                            <label
                                title="Permite adjuntar una o varias imágenes de referencia para facilitar la identificación del bien, servicio, terminaciones, medidas, estado esperado u otros aspectos visuales relevantes, vinculándolas además a los ítems que correspondan.">Imágenes
                                referenciales</label>
                            <div class="soft-card">
                                <div class="compact-title">Adjuntar imagen o imágenes referenciales</div>
                                <div class="tool-group">
                                    <button type="button" class="small-btn"
                                        onclick="openReferenceImageManager()">Adjuntar imágenes</button>
                                    <span id="refImagesStatus" class="status-badge">Sin imágenes vinculadas</span>
                                </div>
                                <div id="refImagesList" class="mini-file-list">Puede cargar un lote de imágenes,
                                    seleccionar una o varias miniaturas y vincularlas a uno o más ítems del
                                    requerimiento.</div>
                                <div class="attachment-register">
                                    <div class="mini-title">Imágenes ya vinculadas a ítems</div>
                                    <div id="refImagesRegisterEmpty" class="hint">Aún no hay imágenes vinculadas a
                                        ítems.</div>
                                    <div id="refImagesRegister"></div>
                                </div>
                            </div>
                        </div>

                        <div class="field c6">
                            <label class="req"
                                title="Fecha calendario en que el área requiere que el bien esté entregado o que el servicio se encuentre iniciado. Es distinta del plazo contractual que tendrá el proveedor para cumplir.">Fecha
                                requerida de entrega / inicio</label>
                            <input class="essential" id="requiredDeliveryStartDate" type="date">
                            <div class="hint">Indique la fecha concreta en que se necesita contar con el bien o
                                iniciar la prestación. Puede escribirla o seleccionarla desde el calendario.</div>
                        </div>

                        <div class="field c6">
                            <label class="req"
                                title="Período máximo que se otorgará al proveedor para entregar el bien o iniciar la prestación, contado desde el hito que defina el procedimiento, por ejemplo la aceptación de la orden de compra o la firma del contrato.">Plazo
                                máximo para entrega / inicio</label>
                            <input class="essential" id="deliveryStartDeadline" type="text"
                                placeholder="Ej.: 10 días corridos / 5 días hábiles">
                            <div class="hint">Este campo expresa un período de tiempo, no una fecha. En el sistema
                                integral deberá vincularse con los plazos del procedimiento y sus fases.</div>
                        </div>

                        <div class="field c6">
                            <label
                                title="Duración total estimada del contrato, servicio, arriendo o actividad, una vez iniciada su ejecución.">Duración
                                total</label>
                            <input type="text" placeholder="Si aplica: 12 meses, 3 jornadas…">
                            <div class="hint">Corresponde al tiempo total que durará la prestación; no debe
                                confundirse con el plazo para iniciar o entregar.</div>
                        </div>

                        <div class="field c6">
                            <label
                                title="Franja horaria, días determinados o ventanas específicas en que debe realizarse la entrega o ejecutarse la prestación.">Horario
                                / ventanas de ejecución</label>
                            <input type="text"
                                placeholder="Ej.: lunes a viernes de 09:00 a 17:00 / fines de semana">
                            <div class="hint">Complete solo cuando existan restricciones de días u horarios para la
                                entrega o ejecución.</div>
                        </div>

                        <div class="field c6">
                            <label class="req"
                                title="Seleccione criterios de ejemplo para verificar que el bien o servicio fue recibido correctamente. Puede agregarlos uno por uno desde la lista desplegable.">Criterios
                                de recepción conforme</label>

                            <div class="criteria-builder">
                                <div class="criteria-combo">
                                    <div class="criteria-combo-row">
                                        <input id="receptionCriteriaInput" type="text" autocomplete="off"
                                            placeholder="Escriba un criterio o seleccione un Ej. de la lista"
                                            oninput="filterReceptionCriteria()"
                                            onfocus="openReceptionCriteriaDropdown()">
                                        <button type="button" class="criteria-combo-toggle"
                                            title="Mostrar ejemplos de criterios"
                                            onclick="toggleReceptionCriteriaDropdown()">⌄</button>
                                    </div>
                                    <div id="receptionCriteriaDropdown" class="criteria-dropdown hidden"></div>
                                </div>
                                <button type="button" class="small-btn" onclick="addReceptionCriterion()">Agregar
                                    criterio</button>
                            </div>
                            <div id="receptionCriteriaStatus" class="criteria-status" aria-live="polite"></div>
                            <div id="receptionCriteriaList" class="criteria-list"></div>
                            <div class="hint">Puede escribir un criterio propio o seleccionar uno de los ejemplos
                                identificados con “Ej.”. La lista desplegable permite hacer scroll entre los distintos
                                ejemplos.</div>
                        </div>

                        <div class="field c6">
                            <label
                                title="Agregue criterios adicionales de recepción, observaciones o detalles específicos que no estén en la lista.">Otros
                                criterios u observaciones de recepción</label>
                            <textarea class="short"
                                placeholder="Especifique otros criterios aplicables o detalle cómo se comprobarán los seleccionados."></textarea>
                        </div>

                        <div class="field c6"><label
                                title="Indique si el proveedor debe otorgar garantía, soporte técnico, instalación, capacitación, mantención, reposición, asistencia postventa u otras prestaciones posteriores a la entrega, cuando sean necesarias para el correcto uso del bien o servicio.">Garantía,
                                soporte, instalación, capacitación o postventa</label>
                            <textarea class="short" placeholder="Indique solo las exigencias necesarias para esta contratación."></textarea>
                        </div>
                        <div class="field c6"><label
                                title="Espacio para cualquier antecedente operativo complementario relevante para ejecutar correctamente la compra o contratación.">Antecedentes
                                operativos complementarios</label>
                            <textarea class="short"
                                placeholder="Observaciones adicionales, coordinación logística, restricciones de acceso, necesidad de visita técnica u otros antecedentes relevantes."></textarea>
                        </div>
                    </div>

                    <div id="leaseBox" class="subbox hidden">
                        <div class="subbox-title">Antecedentes adicionales para arrendamiento</div>
                        <div class="grid">
                            <div class="field c3"><label
                                    title="Tiempo total durante el cual se requiere el bien o servicio en arriendo.">Período
                                    de arriendo</label><input type="text"></div>
                            <div class="field c3"><label
                                    title="Cantidad de bienes o unidades que se requieren en arriendo.">Nº
                                    unidades</label><input type="number" min="0"></div>
                            <div class="field c6"><label
                                    title="Prestaciones o coberturas incluidas en el arriendo, tales como mantención, seguros, transporte u operador.">Servicios
                                    incluidos</label><input type="text"
                                    placeholder="Mantención, seguros, reposición, transporte, operador…"></div>
                            <div class="field c6"><label
                                    title="Condiciones bajo las cuales se recibirá y devolverá el bien arrendado.">Condiciones
                                    de entrega y devolución</label>
                                <textarea class="short"></textarea>
                            </div>
                            <div class="field c6"><label
                                    title="Explique, cuando corresponda, por qué resulta más adecuado arrendar que comprar.">Justificación
                                    compra vs. arriendo / costo de ciclo</label>
                                <textarea class="short"
                                    placeholder="Cuando sea pertinente, explique por qué el arriendo resulta adecuado para la necesidad."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </section>




            <section class="section-budget-market form-step" data-step="5">
                <div class="section-head">
                    <div class="num">5</div>
                    <div>
                        <h2>Presupuesto y estimación de mercado</h2><small>El monto estimado debe ser trazable y
                            considerar impuestos y costos asociados.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div class="grid">
                        <div class="field c4"><label class="req">Valor total aproximado</label>
                            <div class="money-input">
                                <span>$</span>
                                <input class="essential" id="estimatedAmount" type="text" inputmode="numeric"
                                    placeholder="0"
                                    oninput="markEstimatedAmountManual(); sanitizeCurrencyInput(this)">
                            </div>
                            <div class="hint">Se completa automáticamente con el total referencial de líneas de la
                                sección 4. Puede editarse manualmente.</div>
                        </div>
                        <div class="field c4"><label>Moneda</label><select>
                                <option>CLP</option>
                                <option>UF</option>
                                <option>USD</option>
                                <option>Otra</option>
                            </select></div>
                        <div class="field c4"><label>¿Incluye IVA, impuestos, fletes y otros costos?</label><select>
                                <option>Sí, monto total estimado</option>
                                <option>No / requiere ajuste</option>
                                <option>No aplica IVA</option>
                            </select></div>

                        <div class="field c6"><label class="req">Fuente de la estimación</label>
                            <div class="choice-row">
                                <label class="choice"><input type="checkbox"> Compras anteriores</label>
                                <label class="choice"><input type="checkbox"> Cotizaciones</label>
                                <label class="choice"><input type="checkbox"> Catálogo / precios públicos</label>
                                <label class="choice"><input type="checkbox"> Consulta al mercado</label>
                                <label class="choice"><input type="checkbox"> Otra</label>
                            </div>
                        </div>
                        <div class="field c6"><label>Fecha y antecedente de referencia</label><input type="text"
                                placeholder="OC/ID, proveedor consultado, catálogo, enlace o documento adjunto"></div>

                    </div>
                </div>
            </section>

            <section class="form-step" data-step="6">
                <div class="section-head">
                    <div class="num">6</div>
                    <div>
                        <h2>Condiciones de contratación y evaluación</h2><small>Antecedentes para que Abastecimiento
                            pueda convertir el requerimiento en una compra ejecutable.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div class="grid">
                        <div class="field c6"><label>Requisitos técnicos indispensables / admisibilidad
                                sugerida</label>
                            <textarea class="short"
                                placeholder="Solo requisitos necesarios y proporcionales para asegurar la correcta prestación."></textarea>
                        </div>
                        <div class="field c6">
                            <label>Criterios de evaluación sugeridos</label>
                            <div class="criteria-builder">
                                <div class="criteria-combo evaluation-criteria-combo">
                                    <div class="criteria-combo-row">
                                        <input id="evaluationCriteriaInput" type="text" autocomplete="off"
                                            placeholder="Escriba un criterio o seleccione un Ej. de la lista"
                                            oninput="filterEvaluationCriteria()"
                                            onfocus="openEvaluationCriteriaDropdown()">
                                        <button type="button" class="criteria-combo-toggle"
                                            title="Mostrar ejemplos de criterios de evaluación"
                                            onclick="toggleEvaluationCriteriaDropdown()">⌄</button>
                                    </div>
                                    <div id="evaluationCriteriaDropdown" class="criteria-dropdown hidden"></div>
                                </div>
                                <button type="button" class="small-btn" onclick="addEvaluationCriterion()">Agregar
                                    criterio</button>
                            </div>
                            <div id="evaluationCriteriaStatus" class="criteria-status" aria-live="polite"></div>
                            <div id="evaluationCriteriaList" class="criteria-list"></div>
                            <div class="hint">Puede escribir un criterio propio o seleccionar uno de los ejemplos
                                identificados con “Ej.”. Abastecimiento deberá validar su pertinencia, fórmula, escala y
                                ponderación.</div>
                        </div>
                        <div class="field c4"><label>Forma de pago propuesta</label><input type="text"
                                placeholder="Ej.: contra recepción conforme / por hitos"></div>
                        <div class="field c4"><label>¿Pago por hitos?</label><select>
                                <option>No</option>
                                <option>Sí</option>
                            </select></div>
                        <div class="field c4"><label>¿Anticipo?</label><select>
                                <option>No</option>
                                <option>Sí, requiere revisión</option>
                            </select></div>
                        <div class="field c6">
                            <label>Niveles de servicio / hitos / entregables</label>
                            <div class="criteria-builder">
                                <div class="criteria-combo service-level-combo">
                                    <div class="criteria-combo-row">
                                        <input id="serviceLevelInput" type="text" autocomplete="off"
                                            placeholder="Escriba un hito, entregable o nivel de servicio, o seleccione un Ej."
                                            oninput="filterServiceLevels()" onfocus="openServiceLevelsDropdown()">
                                        <button type="button" class="criteria-combo-toggle"
                                            title="Mostrar ejemplos de hitos, entregables y niveles de servicio"
                                            onclick="toggleServiceLevelsDropdown()">⌄</button>
                                    </div>
                                    <div id="serviceLevelsDropdown" class="criteria-dropdown hidden"></div>
                                </div>
                                <button type="button" class="small-btn" onclick="addServiceLevel()">Agregar</button>
                            </div>
                            <div id="serviceLevelStatus" class="criteria-status" aria-live="polite"></div>
                            <div id="serviceLevelList" class="criteria-list"></div>
                            <div class="hint">Puede escribir un hito, un entregable o un nivel de servicio propio, o
                                seleccionar un “Ej.” de la lista. Los ejemplos están identificados expresamente como
                                Hito, Entregable o Nivel de servicio y puede agregar varios elementos.</div>
                        </div>
                        <div class="field c6"><label>Incumplimientos relevantes a regular / eventuales multas</label>
                            <textarea class="short"
                                placeholder="Describa riesgos de ejecución que deban quedar regulados; la redacción final será revisada por Abastecimiento/Jurídica cuando corresponda."></textarea>
                        </div>
                    </div>
                </div>
            </section>

            <section class="conditional form-step" data-step="7">
                <div class="section-head">
                    <div class="num">7</div>
                    <div>
                        <h2>Controles especiales — solo cuando corresponda</h2><small>Evita recargar las compras
                            simples, pero activa respaldos para situaciones que requieren mayor análisis.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div id="circularBlock" class="subbox">
                        <div class="mini-title">A. Economía circular / bienes muebles</div>
                        <div class="grid">
                            <div class="field c4 circular-align"><label>¿Aplica consulta a plataforma de economía
                                    circular?</label><select>
                                    <option>Por determinar por Abastecimiento</option>
                                    <option>Sí</option>
                                    <option>No, por naturaleza</option>
                                    <option>No aplica</option>
                                </select></div>
                            <div class="field c4 circular-align"><label>Fecha de consulta</label><input
                                    type="date"></div>
                            <div class="field c4 circular-align"><label>Resultado</label><input type="text"
                                    placeholder="Sin bienes aptos / existe alternativa / exento"></div>
                            <div class="field c12"><label>Respaldo / fundamento si no aplica</label>
                                <textarea class="short"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="subbox">
                        <div class="mini-title">B. Riesgos y revisiones sectoriales</div>
                        <div class="checklist">
                            <label><input type="checkbox"> Involucra datos personales o acceso a información
                                institucional.</label>
                            <label><input type="checkbox"> Involucra software, licencias, sistemas o
                                ciberseguridad.</label>
                            <label><input type="checkbox"> Requiere instalación, obras menores o intervención de
                                infraestructura.</label>
                            <label><input type="checkbox"> Involucra seguridad de personas, alimentos, transporte u
                                otro riesgo operacional.</label>
                            <label><input type="checkbox"> Puede requerir permisos, autorizaciones, seguros o
                                certificaciones especiales.</label>
                            <label><input type="checkbox"> Corresponde a publicidad, difusión u otro gasto sujeto a
                                instrucciones especiales.</label>
                        </div>
                        <div class="field" style="margin-top:10px"><label>Detalle y revisión requerida</label>
                            <textarea class="short"
                                placeholder="Identifique antecedentes que deban revisar TI, Jurídica, Prevención, Administración u otra unidad."></textarea>
                        </div>
                    </div>

                    <div class="subbox">
                        <div class="mini-title">C. Integridad y contactos previos con el mercado</div>
                        <div class="grid">
                            <div class="field c4"><label>¿Hubo contacto previo con potenciales
                                    proveedores?</label><select>
                                    <option>No</option>
                                    <option>Sí</option>
                                </select></div>
                            <div class="field c4"><label>¿Se recibieron cotizaciones o antecedentes
                                    técnicos?</label><select>
                                    <option>No</option>
                                    <option>Sí, se adjuntan</option>
                                </select></div>
                            <div class="field c4"><label>¿Existe alguna situación que deba informarse por eventual
                                    conflicto de interés?</label><select>
                                    <option>No se conoce</option>
                                    <option>Sí, informar a jefatura/Abastecimiento</option>
                                </select></div>
                            <div class="field c12"><label>Detalle / trazabilidad</label>
                                <textarea class="short" placeholder="Individualice gestiones previas relevantes y adjunte respaldos."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="form-step" data-step="8">
                <div class="section-head">
                    <div class="num">8</div>
                    <div>
                        <h2>Antecedentes adjuntos</h2><small>Checklist de respaldos. Marcar solo los que
                            correspondan.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div class="checklist">
                        <label><input type="checkbox"> Especificaciones técnicas / términos de referencia.</label>
                        <label><input type="checkbox"> Cotizaciones, referencias de mercado o compras
                            anteriores.</label>
                        <label><input type="checkbox"> Respaldo PAC / modificación o justificación de compra no
                            planificada.</label>
                        <label><input type="checkbox"> Antecedentes de disponibilidad presupuestaria / CDP.</label>
                        <label><input type="checkbox"> Respaldo de consulta a economía circular, si aplica.</label>
                        <label><input type="checkbox"> Fotografías, planos, medidas, inventario u otros anexos
                            técnicos.</label>
                        <label><input type="checkbox"> Antecedente de contrato/OC vigente o compra
                            relacionada.</label>
                        <label><input type="checkbox"> Informe, autorización o revisión especial, si aplica.</label>
                    </div>
                    <div class="field" style="margin-top:12px"><label>Otros documentos</label><input type="text">
                    </div>
                </div>
            </section>

            <section class="form-step" data-step="9">
                <div class="section-head">
                    <div class="num">9</div>
                    <div>
                        <h2>Registro de autorizaciones y visaciones</h2><small>Propuesta inicial. La secuencia y los
                            umbrales se definirán en el procedimiento interno del PRZ.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div class="sign-grid">
                        <div class="head">Etapa / responsable</div>
                        <div class="head">Decisión</div>
                        <div class="head">Fecha</div>
                        <div class="head">Nombre / firma</div>
                        <div class="head">Observación</div>

                        <div><b>1. Unidad Requirente</b><br>Elabora y asume responsabilidad técnica.</div>
                        <div>☐ Conforme<br>☐ Corregir</div>
                        <div></div>
                        <div>
                            <div class="signature-line"></div>
                        </div>
                        <div></div>

                        <div><b>2. Jefatura de la Unidad</b><br>Autoriza la necesidad y prioridad.</div>
                        <div>☐ Autoriza<br>☐ Rechaza</div>
                        <div></div>
                        <div>
                            <div class="signature-line"></div>
                        </div>
                        <div></div>

                        <div><b>3. Presupuesto / Finanzas</b><br>Valida imputación y disponibilidad.</div>
                        <div>☐ Visa<br>☐ Observa</div>
                        <div></div>
                        <div>
                            <div class="signature-line"></div>
                        </div>
                        <div></div>

                        <div><b>4. Abastecimiento / Compras</b><br>Revisa suficiencia y procedimiento.</div>
                        <div>☐ Admite<br>☐ Devuelve</div>
                        <div></div>
                        <div>
                            <div class="signature-line"></div>
                        </div>
                        <div></div>

                        <div><b>5. Administración / autoridad competente</b><br>Autoriza cuando proceda según delegación
                            o monto.</div>
                        <div>☐ Autoriza<br>☐ Rechaza<br>☐ N/A</div>
                        <div></div>
                        <div>
                            <div class="signature-line"></div>
                        </div>
                        <div></div>

                        <div><b>6. Jurídica / control especializado</b><br>Solo en casos definidos por procedimiento.
                        </div>
                        <div>☐ Visa<br>☐ Observa<br>☐ N/A</div>
                        <div></div>
                        <div>
                            <div class="signature-line"></div>
                        </div>
                        <div></div>
                    </div>
                    <div class="alert blue">Para mantener agilidad, no recomiendo exigir todas las visaciones en todas
                        las compras. La matriz de autorizaciones debería variar por monto, complejidad, mecanismo y
                        riesgo.</div>
                </div>
            </section>

            <section class="internal form-step" data-step="10">
                <div class="section-head">
                    <div class="num" style="background:var(--blue)">10</div>
                    <div>
                        <h2>Uso exclusivo de Abastecimiento / Compras Públicas</h2><small>Control de admisibilidad
                            interna y definición del curso de compra.</small>
                    </div>
                </div>
                <div class="section-body">
                    <div class="grid">
                        <div class="field c4"><label>Fecha recepción en Abastecimiento</label><input type="date">
                        </div>
                        <div class="field c4"><label>Analista / responsable</label><input type="text"></div>
                        <div class="field c4"><label>Estado</label><select>
                                <option>En revisión</option>
                                <option>Admitido</option>
                                <option>Devuelto para completar</option>
                                <option>Desistido</option>
                                <option>En proceso</option>
                                <option>Finalizado</option>
                            </select></div>

                        <div class="field c12"><label>Control mínimo de suficiencia</label>
                            <div class="checklist">
                                <label><input type="checkbox"> Necesidad y objeto claros.</label>
                                <label><input type="checkbox"> Cantidad / alcance definidos.</label>
                                <label><input type="checkbox"> Especificaciones suficientes y no restrictivas.</label>
                                <label><input type="checkbox"> Fecha requerida compatible con tiempos del
                                    proceso.</label>
                                <label><input type="checkbox"> PAC revisado y documentado.</label>
                                <label><input type="checkbox"> Presupuesto / CDP revisado según etapa.</label>
                                <label><input type="checkbox"> Compras relacionadas / fragmentación revisadas.</label>
                                <label><input type="checkbox"> Stock / contratos existentes revisados.</label>
                                <label><input type="checkbox"> Economía circular revisada cuando corresponde.</label>
                                <label><input type="checkbox"> Estimación de mercado suficientemente
                                    respaldada.</label>
                                <label><input type="checkbox"> Antecedentes para evaluación/condiciones
                                    suficientes.</label>
                                <label><input type="checkbox"> Anexos y revisiones especiales completos.</label>
                            </div>
                        </div>

                        <div class="field c6"><label class="req">Procedimiento de contratación definido /
                                validado</label>
                            <select>
                                <option value="">Seleccione</option>
                                <option>Compra Ágil</option>
                                <option>Convenio Marco</option>
                                <option>Gran Compra en Convenio Marco</option>
                                <option>Licitación Pública</option>
                                <option>Licitación Privada — si procede</option>
                                <option>Trato Directo / Contratación Excepcional Directa con Publicidad — si procede
                                </option>
                                <option>Compra por Cotización — si procede</option>
                                <option>Subasta Inversa Electrónica — si procede</option>
                                <option>Contrato para la Innovación / Diálogo Competitivo — si procede</option>
                                <option>Otro autorizado por normativa</option>
                            </select>
                        </div>
                        <div class="field c6"><label>Fundamento / antecedentes de la elección</label>
                            <textarea class="short"></textarea>
                        </div>

                        <div class="field c4"><label>Consulta Convenio Marco</label><select>
                                <option>Pendiente</option>
                                <option>Realizada</option>
                                <option>No aplica</option>
                            </select></div>
                        <div class="field c4"><label>ID proceso Mercado Público</label><input type="text"></div>
                        <div class="field c4"><label>ID / Nº orden de compra</label><input type="text"></div>
                        <div class="field c12"><label>Observaciones / antecedentes faltantes</label>
                            <textarea class="short"></textarea>
                        </div>
                    </div>
                </div>
            </section>




            <div id="requestSubmissionArea" class="submission-area no-print">
                <div>
                    <div class="submission-title">Finalización del requerimiento</div>
                    <div class="submission-help">Al enviar, el sistema valida los campos obligatorios de la parte
                        requirente, cierra la medición de tiempo y genera un registro estadístico interno para acompañar
                        el expediente.</div>
                </div>
                <button id="submitRequestBtn" type="button" class="primary submission-btn"
                    onclick="submitPurchaseRequest()">Enviar requerimiento</button>
            </div>

            <div id="submissionStatisticsPanel" class="submission-statistics hidden">
                <div class="submission-statistics-head">
                    <div>
                        <div class="submission-statistics-kicker">Registro estadístico interno</div>
                        <h3>Estadísticas de confección del requerimiento</h3>
                    </div>
                    <div id="submissionGeneratedBadge" class="status-badge">Pendiente de envío</div>
                </div>
                <div id="submissionStatisticsSummary" class="metrics-grid"></div>
                <div class="metrics-columns">
                    <div>
                        <div class="mini-title">Campos con más alertas de validación</div>
                        <div id="submissionErrorRanking" class="metrics-list"></div>
                    </div>
                    <div>
                        <div class="mini-title">Información de trazabilidad</div>
                        <div id="submissionTraceability" class="metrics-list"></div>
                    </div>
                </div>
                <div class="metrics-note">Este registro es de uso interno y busca apoyar la mejora continua del
                    formulario. No sustituye los antecedentes técnicos, presupuestarios o administrativos del
                    requerimiento.</div>
                <div class="tool-group no-print" style="margin-top:10px;">
                    <button type="button" class="small-btn" onclick="downloadSubmissionMetrics()">Descargar
                        registro estadístico (.json)</button>
                    <button type="button" class="small-btn" onclick="copySubmissionMetricsSummary()">Copiar
                        resumen</button>
                </div>
            </div>

            <div class="footer-note">
                Versión de trabajo v0.65 · Este formulario está diseñado para integrarse posteriormente al procedimiento
                interno de adquisiciones del PRZ y a una matriz de autorizaciones por monto/riesgo. Los campos marcados
                con * conforman el núcleo mínimo sugerido.
            </div>
        </main>
    </div>

    <script>
        const formAnalytics = {
            startedAt: Date.now(),
            submittedAt: null,
            activeMilliseconds: 0,
            lastInteractionAt: Date.now(),
            idleThresholdMs: 120000,
            validationAttempts: 0,
            validationAttemptsWithErrors: 0,
            totalMissingDetections: 0,
            validationErrorCounts: {},
            modeSwitches: 0,
            modesUsed: new Set(['form']),
            helpInteractions: 0,
            submissionCount: 0,
            lastSubmissionSnapshot: null
        };

        function analyticsRecordActivity() {
            const now = Date.now();
            const delta = now - formAnalytics.lastInteractionAt;
            if (delta > 0 && delta <= formAnalytics.idleThresholdMs) {
                formAnalytics.activeMilliseconds += delta;
            }
            formAnalytics.lastInteractionAt = now;
        }

        function analyticsFinalizeActiveTime() {
            analyticsRecordActivity();
        }

        function formatDuration(milliseconds) {
            const totalSeconds = Math.max(0, Math.floor((milliseconds || 0) / 1000));
            const h = Math.floor(totalSeconds / 3600);
            const m = Math.floor((totalSeconds % 3600) / 60);
            const s = totalSeconds % 60;
            if (h > 0) return `${h} h ${String(m).padStart(2,'0')} min ${String(s).padStart(2,'0')} s`;
            if (m > 0) return `${m} min ${String(s).padStart(2,'0')} s`;
            return `${s} s`;
        }

        function formatClockDuration(milliseconds) {
            const totalSeconds = Math.max(0, Math.floor((milliseconds || 0) / 1000));
            const h = Math.floor(totalSeconds / 3600);
            const m = Math.floor((totalSeconds % 3600) / 60);
            const s = totalSeconds % 60;
            return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }

        function updateFormElapsedTimer() {
            const timer = document.getElementById('formElapsedTimer');
            if (!timer) return;
            const end = formAnalytics.submittedAt || Date.now();
            timer.textContent = formatClockDuration(end - formAnalytics.startedAt);
        }

        function recordValidationAttempt(missing) {
            formAnalytics.validationAttempts++;
            if (missing?.length) {
                formAnalytics.validationAttemptsWithErrors++;
                formAnalytics.totalMissingDetections += missing.length;
                missing.forEach(item => {
                    const label = (item?.label || 'Campo no identificado').trim();
                    formAnalytics.validationErrorCounts[label] = (formAnalytics.validationErrorCounts[label] || 0) +
                        1;
                });
            }
        }

        function topValidationErrors(limit = 5) {
            return Object.entries(formAnalytics.validationErrorCounts)
                .sort((a, b) => b[1] - a[1] || a[0].localeCompare(b[0], 'es'))
                .slice(0, limit);
        }

        function submissionMetricSnapshot() {
            const submittedAt = formAnalytics.submittedAt || Date.now();
            const elapsed = submittedAt - formAnalytics.startedAt;
            const active = Math.min(formAnalytics.activeMilliseconds, elapsed);
            const idle = Math.max(0, elapsed - active);
            const errors = topValidationErrors(10);

            return {
                schemaVersion: '1.0',
                formVersion: '0.65',
                formCode: 'RQ-01',
                startedAt: new Date(formAnalytics.startedAt).toISOString(),
                submittedAt: new Date(submittedAt).toISOString(),
                elapsedMilliseconds: elapsed,
                elapsedHuman: formatDuration(elapsed),
                activeMilliseconds: active,
                activeHuman: formatDuration(active),
                idleMilliseconds: idle,
                idleHuman: formatDuration(idle),
                fillingModesUsed: [...formAnalytics.modesUsed],
                currentMode: currentFillMode,
                modeSwitches: formAnalytics.modeSwitches,
                validationAttempts: formAnalytics.validationAttempts,
                validationAttemptsWithErrors: formAnalytics.validationAttemptsWithErrors,
                totalMissingDetections: formAnalytics.totalMissingDetections,
                validationErrorsByField: Object.fromEntries(errors),
                helpInteractions: formAnalytics.helpInteractions,
                submissionCount: formAnalytics.submissionCount,
                folio: (document.getElementById('folio')?.value || '').trim() || null
            };
        }

        function metricsCard(label, value) {
            return `<div class="metric-card"><div class="metric-label">${escapeHtml(label)}</div><div class="metric-value">${escapeHtml(String(value))}</div></div>`;
        }

        function renderSubmissionMetrics(snapshot) {
            const panel = document.getElementById('submissionStatisticsPanel');
            const summary = document.getElementById('submissionStatisticsSummary');
            const ranking = document.getElementById('submissionErrorRanking');
            const trace = document.getElementById('submissionTraceability');
            const badge = document.getElementById('submissionGeneratedBadge');
            if (!panel || !summary || !ranking || !trace || !badge) return;

            panel.classList.remove('hidden');
            badge.textContent = 'Generado';

            summary.innerHTML = [
                metricsCard('Tiempo total', snapshot.elapsedHuman),
                metricsCard('Tiempo activo estimado', snapshot.activeHuman),
                metricsCard('Validaciones con alerta', snapshot.validationAttemptsWithErrors),
                metricsCard('Alertas detectadas', snapshot.totalMissingDetections)
            ].join('');

            const ranked = Object.entries(snapshot.validationErrorsByField || {});
            ranking.innerHTML = ranked.length ?
                ranked.map(([label, count], index) =>
                    `<div class="metrics-list-row"><span>${index+1}. ${escapeHtml(label)}</span><strong>${count} alerta(s)</strong></div>`
                ).join('') :
                '<div class="metrics-list-row"><span>No se registraron alertas de campos obligatorios.</span><strong>0</strong></div>';

            const modes = (snapshot.fillingModesUsed || []).map(x => x === 'step' ? 'Paso a paso' : 'Formulario completo')
                .join(' + ');
            trace.innerHTML = [
                `<div class="metrics-list-row"><span>Inicio</span><strong>${new Date(snapshot.startedAt).toLocaleString('es-CL')}</strong></div>`,
                `<div class="metrics-list-row"><span>Finalización</span><strong>${new Date(snapshot.submittedAt).toLocaleString('es-CL')}</strong></div>`,
                `<div class="metrics-list-row"><span>Modo(s) utilizado(s)</span><strong>${escapeHtml(modes||'Formulario completo')}</strong></div>`,
                `<div class="metrics-list-row"><span>Cambios de modo</span><strong>${snapshot.modeSwitches}</strong></div>`,
                `<div class="metrics-list-row"><span>Consultas de ayuda contextual</span><strong>${snapshot.helpInteractions}</strong></div>`
            ].join('');

            const modalMetrics = document.getElementById('submissionModalMetrics');
            if (modalMetrics) {
                modalMetrics.innerHTML = [
                    metricsCard('Tiempo total', snapshot.elapsedHuman),
                    metricsCard('Tiempo activo', snapshot.activeHuman),
                    metricsCard('Intentos de validación', snapshot.validationAttempts),
                    metricsCard('Campos observados', snapshot.totalMissingDetections)
                ].join('');
            }

            const headline = document.getElementById('submissionModalHeadline');
            const detail = document.getElementById('submissionModalDetail');
            if (headline) headline.textContent = `Formulario generado en ${snapshot.elapsedHuman}.`;
            if (detail) {
                const top = Object.entries(snapshot.validationErrorsByField || {})[0];
                detail.textContent = top ?
                    `El campo con más alertas durante el llenado fue “${top[0]}” (${top[1]}).` :
                    'No se registraron alertas de validación durante el llenado.';
            }
        }

        function validateRequesterSubmission() {
            const sections = getStepSections().filter(section => !section.classList.contains('internal'));
            clearValidationMarks(document);
            const allMissing = [];

            sections.forEach((section, index) => {
                const missing = validateScopeRequired(section, {
                    mark: true
                });
                missing.forEach(item => allMissing.push({
                    ...item,
                    step: index
                }));
            });

            recordValidationAttempt(allMissing);

            if (allMissing.length) {
                showRequiredValidationModal(allMissing, 'Antes de enviar, complete los campos obligatorios');
                return false;
            }
            return true;
        }

        function submitPurchaseRequest() {
            analyticsRecordActivity();
            if (!validateRequesterSubmission()) return;

            formAnalytics.submissionCount++;
            if (!formAnalytics.submittedAt) {
                formAnalytics.submittedAt = Date.now();
                analyticsFinalizeActiveTime();
            }

            const snapshot = submissionMetricSnapshot();
            formAnalytics.lastSubmissionSnapshot = snapshot;
            renderSubmissionMetrics(snapshot);

            const btn = document.getElementById('submitRequestBtn');
            if (btn) {
                btn.textContent = 'Requerimiento finalizado';
                btn.disabled = true;
            }

            updateFormElapsedTimer();
            document.getElementById('submissionMetricsModal')?.classList.remove('hidden');
        }

        function closeSubmissionMetricsModal() {
            document.getElementById('submissionMetricsModal')?.classList.add('hidden');
        }

        function downloadSubmissionMetrics() {
            const snapshot = formAnalytics.lastSubmissionSnapshot || submissionMetricSnapshot();
            const blob = new Blob([JSON.stringify(snapshot, null, 2)], {
                type: 'application/json;charset=utf-8'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            const folio = (snapshot.folio || 'sin-folio').replace(/[^\w.-]+/g, '_');
            a.href = url;
            a.download = `registro_estadistico_RQ-01_${folio}.json`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(() => URL.revokeObjectURL(url), 500);
        }

        async function copySubmissionMetricsSummary() {
            const snapshot = formAnalytics.lastSubmissionSnapshot || submissionMetricSnapshot();
            const ranked = Object.entries(snapshot.validationErrorsByField || {});
            const top = ranked.length ? ranked.map(([k, v]) => `${k}: ${v}`).join('; ') : 'Sin alertas registradas';
            const text = [
                `Formulario RQ-01 v${snapshot.formVersion}`,
                `Tiempo total: ${snapshot.elapsedHuman}`,
                `Tiempo activo estimado: ${snapshot.activeHuman}`,
                `Validaciones con alerta: ${snapshot.validationAttemptsWithErrors}`,
                `Alertas detectadas: ${snapshot.totalMissingDetections}`,
                `Campos con más alertas: ${top}`
            ].join('\n');

            try {
                await navigator.clipboard.writeText(text);
                alert('Resumen estadístico copiado.');
            } catch (e) {
                prompt('Copie el resumen estadístico:', text);
            }
        }

        ['input', 'change', 'click', 'keydown', 'pointerdown'].forEach(eventName => {
            document.addEventListener(eventName, analyticsRecordActivity, {
                passive: true
            });
        });
        setInterval(updateFormElapsedTimer, 1000);

        function setTodayOnRequestDate() {
            const input = document.getElementById('requestDate');
            if (!input || input.value) return;
            const now = new Date();
            const localDate = new Date(now.getTime() - now.getTimezoneOffset() * 60000);
            input.value = localDate.toISOString().slice(0, 10);
        }

        function togglePriority() {
            const v = document.getElementById('priority').value;
            document.getElementById('urgentBox').classList.toggle('hidden', v === 'normal');
        }

        function formatPacAmount(input) {
            if (!input) return;
            const digits = (input.value || '').replace(/\D/g, '');
            if (!digits) {
                input.value = '';
                return;
            }
            input.value = new Intl.NumberFormat('es-CL').format(Number(digits));
        }

        function handleSimilarRequirementsCheck(value) {
            if (value === 'no' || value === 'desconocido') {
                document.getElementById('pacReviewPromptModal')?.classList.remove('hidden');
            }
        }

        function closePacReviewPrompt() {
            document.getElementById('pacReviewPromptModal')?.classList.add('hidden');
        }

        function goToRelatedPurchasesBackground() {
            closePacReviewPrompt();
            const field = document.getElementById('relatedPurchasesBackground');
            if (field) {
                field.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                setTimeout(() => field.focus(), 250);
            }
        }

        function togglePac() {
            const v = document.querySelector('input[name="pac"]:checked')?.value;
            document.getElementById('pacYes').classList.toggle('hidden', v !== 'yes');
            document.getElementById('pacNo').classList.toggle('hidden', v !== 'no');
        }

        function toggleKind() {
            const v = document.querySelector('input[name="kind"]:checked')?.value;
            document.getElementById('leaseBox').classList.toggle('hidden', !(v === 'arriendo' || v === 'mixto'));
        }

        function toggleBrand() {
            const v = document.getElementById('brandSelect').value;
            document.getElementById('brandBox').classList.toggle('hidden', v !== 'yes');
        }

        function toggleOtherFrequency() {
            const v = document.getElementById('freqUseSelect')?.value;
            document.getElementById('otherFrequencyBox')?.classList.toggle('hidden', v !== 'otra');
        }

        function toggleDeliveryPlaceDropdown() {
            const combo = document.getElementById('deliveryPlaceCombo');
            const dropdown = document.getElementById('deliveryPlaceDropdown');
            const button = document.getElementById('deliveryPlaceButton');
            if (!combo || !dropdown || !button) return;
            const opening = dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden', !opening);
            combo.classList.toggle('open', opening);
            button.setAttribute('aria-expanded', opening ? 'true' : 'false');
        }

        function selectDeliveryPlace(value, label) {
            const select = document.getElementById('deliveryPlace');
            const labelEl = document.getElementById('deliveryPlaceLabel');
            const combo = document.getElementById('deliveryPlaceCombo');
            const dropdown = document.getElementById('deliveryPlaceDropdown');
            const button = document.getElementById('deliveryPlaceButton');
            if (select) {
                select.value = value;
                select.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }
            if (labelEl) labelEl.textContent = label;
            if (dropdown) dropdown.classList.add('hidden');
            if (combo) combo.classList.remove('open');
            if (button) button.setAttribute('aria-expanded', 'false');
        }

        function parseResponsibleNameAndRole(raw) {
            const text = (raw || '').trim();
            if (!text) return {
                name: '',
                role: ''
            };

            const separators = [' — ', ' – ', ' - ', ' / ', ' | ', ', '];
            for (const sep of separators) {
                const parts = text.split(sep).map(x => x.trim()).filter(Boolean);
                if (parts.length >= 2) {
                    return {
                        name: parts[0],
                        role: parts.slice(1).join(sep.trim() === '-' ? ' - ' : sep)
                    };
                }
            }
            return {
                name: text,
                role: ''
            };
        }

        function autofillTechnicalCounterpart() {
            const responsible = document.getElementById('requestingResponsible')?.value || '';
            const unit = document.getElementById('requestingUnit')?.value || '';
            const parsed = parseResponsibleNameAndRole(responsible);

            const name = document.getElementById('technicalCounterpartName');
            const unitInput = document.getElementById('technicalCounterpartUnit');
            const role = document.getElementById('technicalCounterpartRole');

            if (name) name.value = parsed.name;
            if (unitInput) unitInput.value = unit;
            if (role) role.value = parsed.role;
        }

        function toggleTechnicalCounterpartMode() {
            const mode = document.querySelector('input[name="technicalCounterpartMode"]:checked')?.value;
            const fields = document.getElementById('technicalCounterpartFields');
            const hint = document.getElementById('technicalCounterpartAutofillHint');
            const name = document.getElementById('technicalCounterpartName');
            const unit = document.getElementById('technicalCounterpartUnit');
            const role = document.getElementById('technicalCounterpartRole');
            if (!fields) return;

            if (mode === 'same') {
                fields.classList.remove('hidden');
                autofillTechnicalCounterpart();
                hint?.classList.remove('hidden');
            } else if (mode === 'different') {
                fields.classList.remove('hidden');
                if (name) name.value = '';
                if (unit) unit.value = '';
                if (role) role.value = '';
                hint?.classList.add('hidden');
            } else {
                fields.classList.add('hidden');
                hint?.classList.add('hidden');
            }
        }

        function toggleOtherDeliveryPlace() {
            const v = document.getElementById('deliveryPlace')?.value;
            document.getElementById('otherDeliveryPlaceBox')?.classList.toggle('hidden', v !== 'otro');
        }

        function fmt(n) {
            return new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP',
                maximumFractionDigits: 0
            }).format(n || 0);
        }
        let estimatedAmountEditedManually = false;
        let syncingEstimatedAmount = false;

        function markEstimatedAmountManual() {
            if (!syncingEstimatedAmount) estimatedAmountEditedManually = true;
        }

        function syncEstimatedAmountFromLines(total) {
            const input = document.getElementById('estimatedAmount');
            if (!input || estimatedAmountEditedManually) return;
            syncingEstimatedAmount = true;
            input.value = total ?
                new Intl.NumberFormat('es-CL', {
                    maximumFractionDigits: 0
                }).format(total) :
                '';
            syncingEstimatedAmount = false;
        }

        function calcTotals() {
            let total = 0;
            document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
                const q = parseInt(row.querySelector('.qty')?.value || '0', 10) || 0;
                const priceRaw = (row.querySelector('.unit-price')?.value || '').replace(/[^\d]/g, '');
                const p = parseInt(priceRaw || '0', 10) || 0;
                const s = q * p;
                total += s;
                const subtotalCell = row.querySelector('.subtotal');
                if (subtotalCell) subtotalCell.textContent = fmt(s);
            });
            const totalBox = document.getElementById('lineTotal');
            if (totalBox) totalBox.textContent = fmt(total);
            syncEstimatedAmountFromLines(total);
        }

        function renumberItemRows() {
            document.querySelectorAll('#itemsTable tbody tr').forEach((row, idx) => {
                const firstCell = row.querySelector('td');
                if (firstCell) firstCell.textContent = idx + 1;
            });
            if (typeof renderManagerItemList === 'function' && !document.getElementById('attachmentModal')?.classList
                .contains('hidden')) renderManagerItemList();
            if (typeof renderAttachmentRegister === 'function') renderAttachmentRegister();
        }

        function rowHasUserContent(row) {
            const fields = row.querySelectorAll('input:not([type="file"]), textarea, select');
            for (const field of fields) {
                const value = (field.value || '').trim();
                if (value !== '') return true;
            }
            return false;
        }

        function addStructuredEntry(button, kind) {
            const editor = button?.closest('.structured-cell-editor');
            const list = editor?.querySelector('.structured-entry-list');
            if (!editor || !list) return;

            const entry = document.createElement('div');
            entry.className = 'structured-entry';

            const input = document.createElement('input');
            input.type = 'text';
            input.className = kind === 'feature' ? 'item-feature-entry' : 'item-spec-entry';
            input.placeholder = kind === 'feature' ?
                'Ej.: compatible con red 5G' :
                'Ej.: 512 GB de almacenamiento';

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'structured-entry-remove';
            remove.title = kind === 'feature' ? 'Eliminar característica' : 'Eliminar especificación';
            remove.textContent = '×';
            remove.onclick = () => removeStructuredEntry(remove);

            entry.appendChild(input);
            entry.appendChild(remove);
            list.appendChild(entry);
            input.focus();
            updateProgress();
        }

        function removeStructuredEntry(button) {
            const entry = button?.closest('.structured-entry');
            const list = entry?.parentElement;
            if (!entry || !list) return;

            const entries = [...list.querySelectorAll(':scope > .structured-entry')];
            if (entries.length <= 1) {
                const input = entry.querySelector('input');
                if (input) input.value = '';
                input?.focus();
                return;
            }

            entry.remove();
            updateProgress();
        }

        function addItemRow() {
            if (tableStructureLocked) {
                alert(
                    'La estructura de la tabla está bloqueada. Desbloquee el candado para agregar filas o modificar tamaños.'
                );
                return;
            }
            const tbody = document.querySelector('#itemsTable tbody');
            const rowCount = tbody.querySelectorAll('tr').length + 1;
            const tr = document.createElement('tr');
            tr.dataset.itemKey = 'item-' + (++itemKeyCounter);
            tr.innerHTML = `
      <td>${rowCount}</td>
      <td><textarea class="item-name" placeholder="Nombre del bien o servicio" oninput="if(!document.getElementById('attachmentModal')?.classList.contains('hidden')) renderManagerItemList(); if(!document.getElementById('refImageModal')?.classList.contains('hidden')) renderRefImageItemList()"></textarea></td>
      <td>
        <div class="structured-cell-editor item-spec-editor" data-kind="spec">
          <div class="structured-entry-list">
            <div class="structured-entry"><input class="item-spec-entry" type="text" placeholder="Ej.: 16 GB de RAM"></div>
          </div>
          <button type="button" class="structured-add-btn" onclick="addStructuredEntry(this,'spec')">＋ Agregar especificación</button>
        </div>
      </td>
      <td>
        <div class="structured-cell-editor item-feature-editor" data-kind="feature">
          <div class="structured-entry-list">
            <div class="structured-entry"><input class="item-feature-entry" type="text" placeholder="Ej.: compatible con red 5G"></div>
          </div>
          <button type="button" class="structured-add-btn" onclick="addStructuredEntry(this,'feature')">＋ Agregar característica</button>
        </div>
      </td>
      <td><input class="qty" type="number" min="0" step="1" oninput="calcTotals()"></td>
      <td><input type="text" placeholder="Ej.: unidad, caja, pack, litro, hora"></td>
      <td><div class="money-input"><span>$</span><input class="unit-price" type="text" inputmode="numeric" placeholder="0" oninput="sanitizeCurrencyInput(this); calcTotals()"></div></td>
      <td class="subtotal right">$0</td>
      <td class="cotiz-cell">
        <div class="cotiz-wrap">
          <button type="button" class="icon-btn attach" title="Adjuntar o gestionar cotizaciones de esta fila" onclick="openAttachmentManager(this)">📎</button>
          <button type="button" class="icon-btn quote-view-btn" title="Visualizar cotizaciones vinculadas a esta fila" onclick="openRowQuoteViewer(this)" disabled>👁</button>
        </div>
        <div class="quote-linked quote-info">Sin cotización vinculada</div>
        <button type="button" class="trash-row" title="Eliminar esta fila" onclick="removeItemRow(this)">🗑</button>
      </td>`;
            tbody.appendChild(tr);
            renumberItemRows();
            refreshQuoteIndicators();
            if (!document.getElementById('attachmentModal')?.classList.contains('hidden')) renderManagerItemList();
            if (!document.getElementById('refImageModal')?.classList.contains('hidden')) renderRefImageItemList();
            calcTotals();
        }

        function sanitizeCurrencyInput(input) {
            const digits = (input.value || '').replace(/[^\d]/g, '');
            if (!digits) {
                input.value = '';
                return;
            }
            const value = parseInt(digits, 10);
            input.value = new Intl.NumberFormat('es-CL', {
                maximumFractionDigits: 0
            }).format(value);
        }
        let pendingRowDelete = null;

        function rowHasLinkedDocuments(row) {
            const key = row?.dataset?.itemKey;
            if (!key) return false;
            const hasQuotes = attachmentsRegistry.some(a => (a.itemKeys || []).includes(key));
            const hasReferenceImages = referenceImagesRegistry.some(a => (a.itemKeys || []).includes(key));
            return hasQuotes || hasReferenceImages;
        }

        function describeRowDeleteRisk(row) {
            const key = row?.dataset?.itemKey;
            const hasInfo = rowHasUserContent(row);
            const quoteCount = key ? attachmentsRegistry.filter(a => (a.itemKeys || []).includes(key)).length : 0;
            const imageCount = key ? referenceImagesRegistry.filter(a => (a.itemKeys || []).includes(key)).length : 0;

            const parts = [];
            if (hasInfo) parts.push('información ingresada en una o más columnas');
            if (quoteCount) parts.push(`${quoteCount} cotización(es) vinculada(s)`);
            if (imageCount) parts.push(`${imageCount} imagen(es) referencial(es) vinculada(s)`);

            return parts;
        }

        function openRowDeleteConfirmation(row) {
            pendingRowDelete = row;
            const num = [...row.parentElement.children].indexOf(row) + 1;
            const details = describeRowDeleteRisk(row);

            const title = document.getElementById('rowDeleteConfirmTitle');
            const text = document.getElementById('rowDeleteConfirmText');
            const detail = document.getElementById('rowDeleteConfirmDetail');

            if (title) title.textContent = `¿Eliminar el ítem ${num}?`;
            if (text) text.textContent = 'Esta fila contiene antecedentes que podrían perderse si se elimina.';
            if (detail) detail.textContent = details.length ?
                `Se detectó: ${details.join(' y ')}.` :
                'Se detectó información asociada a esta fila.';

            document.getElementById('rowDeleteConfirmModal')?.classList.remove('hidden');
        }

        function closeRowDeleteConfirmation() {
            pendingRowDelete = null;
            document.getElementById('rowDeleteConfirmModal')?.classList.add('hidden');
        }

        function confirmRowDelete() {
            const row = pendingRowDelete;
            if (!row) {
                closeRowDeleteConfirmation();
                return;
            }
            document.getElementById('rowDeleteConfirmModal')?.classList.add('hidden');
            pendingRowDelete = null;
            performItemRowDeletion(row);
        }

        function performItemRowDeletion(row) {
            const removedKey = row.dataset.itemKey;
            row.remove();

            attachmentsRegistry.forEach(a => a.itemKeys = a.itemKeys.filter(k => k !== removedKey));
            for (let i = attachmentsRegistry.length - 1; i >= 0; i--) {
                if (attachmentsRegistry[i].itemKeys.length === 0) attachmentsRegistry.splice(i, 1);
            }

            referenceImagesRegistry.forEach(a => a.itemKeys = a.itemKeys.filter(k => k !== removedKey));
            for (let i = referenceImagesRegistry.length - 1; i >= 0; i--) {
                if (referenceImagesRegistry[i].itemKeys.length === 0) referenceImagesRegistry.splice(i, 1);
            }

            renumberItemRows();
            refreshQuoteIndicators();
            renderAttachmentRegister();
            renderReferenceImageSummary();
            renderReferenceImageRegister();

            if (!document.getElementById('attachmentModal')?.classList.contains('hidden')) renderManagerItemList();
            if (!document.getElementById('refImageModal')?.classList.contains('hidden')) renderRefImageItemList();

            calcTotals();
        }

        function removeItemRow(button) {
            if (tableStructureLocked) {
                alert(
                    'La estructura de la tabla está bloqueada. Desbloquee el candado para eliminar filas o modificar tamaños.'
                );
                return;
            }

            const row = button.closest('tr');
            const tbody = row.closest('tbody');
            const rows = tbody.querySelectorAll('tr');

            if (rows.length <= 1) {
                alert('Debe mantenerse al menos una fila en la tabla.');
                return;
            }

            const hasInformation = rowHasUserContent(row);
            const hasDocuments = rowHasLinkedDocuments(row);

            if (hasInformation || hasDocuments) {
                openRowDeleteConfirmation(row);
                return;
            }

            // Si la fila está completamente vacía y sin documentos, se elimina de inmediato.
            performItemRowDeletion(row);
        }

        function updateLotQuoteInfo(input) {
            const status = document.getElementById('lotQuoteStatus');
            const viewBtn = document.getElementById('lotQuoteViewBtn');
            const files = [...(input.files || [])];

            lotQuoteRegistry.forEach(record => {
                if (record.objectUrl) {
                    try {
                        URL.revokeObjectURL(record.objectUrl);
                    } catch (e) {}
                }
            });
            lotQuoteRegistry.length = 0;

            files.forEach(file => {
                const objectUrl = URL.createObjectURL(file);
                lotQuoteRegistry.push({
                    id: 'lot-' + Date.now() + '-' + Math.random().toString(36).slice(2, 7),
                    file,
                    name: file.name,
                    displayName: file.name,
                    objectUrl,
                    previewUrl: fileLooksLikeImage(file) ? objectUrl : null
                });
            });

            if (!files.length) {
                status.textContent = 'Sin cotizaciones de lote';
                if (viewBtn) viewBtn.disabled = true;
                return;
            }

            status.textContent = `${files.length} cotización(es) de lote adjunta(s)`;
            if (viewBtn) viewBtn.disabled = false;
        }

        function updateReferenceImagesInfo(input) {
            const files = [...(input.files || [])];
            const status = document.getElementById('refImagesStatus');
            const list = document.getElementById('refImagesList');
            if (!files.length) {
                status.textContent = 'Sin imágenes adjuntas';
                list.textContent = 'Puede adjuntar una o más imágenes de apoyo.';
                return;
            }
            status.textContent = `${files.length} imagen(es) adjunta(s)`;
            list.textContent = files.map(f => f.name).join(', ');
        }
        const receptionCriteriaExamples = [
            'Ej. Entrega completa de la cantidad solicitada',
            'Ej. Cumplimiento de las especificaciones técnicas definidas',
            'Ej. Cumplimiento de las características funcionales requeridas',
            'Ej. Funcionamiento o prueba operativa satisfactoria',
            'Ej. Instalación correctamente ejecutada',
            'Ej. Puesta en marcha realizada y operativa',
            'Ej. Capacitación impartida al personal definido',
            'Ej. Entrega de manuales, fichas técnicas o certificados',
            'Ej. Cumplimiento del plazo de entrega comprometido',
            'Ej. Cumplimiento de la fecha de inicio del servicio',
            'Ej. Bien recibido sin daños, defectos visibles ni deterioros',
            'Ej. Compatibilidad técnica verificada con equipos o sistemas existentes',
            'Ej. Integración satisfactoria con infraestructura o software institucional',
            'Ej. Recepción de todos los entregables comprometidos',
            'Ej. Cumplimiento de los hitos definidos para la prestación',
            'Ej. Calidad del servicio conforme a los estándares solicitados',
            'Ej. Materiales, terminaciones o componentes conforme a lo requerido',
            'Ej. Dimensiones, medidas o capacidad conforme a lo solicitado',
            'Ej. Rendimiento o desempeño mínimo verificado',
            'Ej. Cantidad, peso, volumen o metraje conforme a lo requerido',
            'Ej. Documentación de respaldo completa y vigente',
            'Ej. Garantía del producto o servicio entregada en los términos requeridos',
            'Ej. Servicio de soporte habilitado y disponible',
            'Ej. Mantención inicial o preventiva realizada, cuando corresponda',
            'Ej. Reposición de elementos defectuosos efectuada, cuando corresponda',
            'Ej. Cumplimiento de condiciones sanitarias o de inocuidad aplicables',
            'Ej. Cumplimiento de condiciones de seguridad exigidas',
            'Ej. Cumplimiento de certificaciones técnicas obligatorias',
            'Ej. Cumplimiento de permisos o autorizaciones exigibles',
            'Ej. Entrega realizada en el lugar indicado',
            'Ej. Entrega realizada dentro del horario o ventana de ejecución definida',
            'Ej. Retiro de residuos, embalajes o materiales sobrantes efectuado',
            'Ej. Limpieza y orden del lugar luego de la prestación',
            'Ej. Personal requerido presente y debidamente identificado',
            'Ej. Equipamiento comprometido disponible durante la ejecución',
            'Ej. Informe final entregado y aprobado',
            'Ej. Informe técnico entregado y aprobado',
            'Ej. Evidencia fotográfica o documental de la ejecución entregada',
            'Ej. Servicio prestado en todas las jornadas o sesiones comprometidas',
            'Ej. Cumplimiento de niveles de servicio acordados',
            'Ej. Respuesta a incidencias dentro del plazo exigido',
            'Ej. Corrección de observaciones dentro del plazo otorgado',
            'Ej. Bien o servicio aceptado por la contraparte técnica sin observaciones pendientes'
        ];

        function renderReceptionCriteriaDropdown(filterText = '') {
            const dropdown = document.getElementById('receptionCriteriaDropdown');
            if (!dropdown) return;
            const q = (filterText || '').trim().toLowerCase();
            const matches = receptionCriteriaExamples.filter(x => !q || x.toLowerCase().includes(q));
            dropdown.innerHTML = '';
            if (!matches.length) {
                const empty = document.createElement('div');
                empty.className = 'criteria-option';
                empty.style.cursor = 'default';
                empty.textContent = 'No hay ejemplos coincidentes. Puede escribir su propio criterio.';
                dropdown.appendChild(empty);
                return;
            }
            matches.forEach(example => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'criteria-option';
                const text = example.replace(/^Ej\.\s*/i, '');
                btn.innerHTML = `<span class="criteria-example-tag">Ej.</span> ${text}`;
                btn.onclick = () => selectReceptionCriteriaExample(example);
                dropdown.appendChild(btn);
            });
        }

        function handleReceptionCriteriaDropdownWheel(event) {
            const dropdown = event.currentTarget;
            const delta = event.deltaY;
            const atTop = dropdown.scrollTop <= 0;
            const atBottom = Math.ceil(dropdown.scrollTop + dropdown.clientHeight) >= dropdown.scrollHeight;
            if ((delta < 0 && atTop) || (delta > 0 && atBottom)) {
                event.preventDefault();
            }
            event.stopPropagation();
        }

        function openReceptionCriteriaDropdown() {
            const dropdown = document.getElementById('receptionCriteriaDropdown');
            if (!dropdown) return;
            renderReceptionCriteriaDropdown(document.getElementById('receptionCriteriaInput')?.value || '');
            dropdown.classList.remove('hidden');
            if (!dropdown.dataset.wheelBound) {
                dropdown.addEventListener('wheel', handleReceptionCriteriaDropdownWheel, {
                    passive: false
                });
                dropdown.dataset.wheelBound = '1';
            }
        }

        function closeReceptionCriteriaDropdown() {
            document.getElementById('receptionCriteriaDropdown')?.classList.add('hidden');
        }

        function toggleReceptionCriteriaDropdown() {
            const dropdown = document.getElementById('receptionCriteriaDropdown');
            if (!dropdown) return;
            if (dropdown.classList.contains('hidden')) {
                const input = document.getElementById('receptionCriteriaInput');
                if (input) input.value = '';
                renderReceptionCriteriaDropdown('');
                dropdown.classList.remove('hidden');
                if (!dropdown.dataset.wheelBound) {
                    dropdown.addEventListener('wheel', handleReceptionCriteriaDropdownWheel, {
                        passive: false
                    });
                    dropdown.dataset.wheelBound = '1';
                }
            } else {
                closeReceptionCriteriaDropdown();
            }
        }

        function filterReceptionCriteria() {
            openReceptionCriteriaDropdown();
        }

        function selectReceptionCriteriaExample(example) {
            const input = document.getElementById('receptionCriteriaInput');
            if (input) input.value = example;
            closeReceptionCriteriaDropdown();
        }
        let receptionCriteriaStatusTimer = null;

        function showReceptionCriteriaStatus(message) {
            const box = document.getElementById('receptionCriteriaStatus');
            if (!box) return;
            box.textContent = message;
            box.classList.add('show');
            if (receptionCriteriaStatusTimer) clearTimeout(receptionCriteriaStatusTimer);
            receptionCriteriaStatusTimer = setTimeout(() => {
                box.classList.remove('show');
                box.textContent = '';
            }, 2200);
        }

        function hideReceptionCriteriaStatus() {
            const box = document.getElementById('receptionCriteriaStatus');
            if (!box) return;
            box.classList.remove('show');
            box.textContent = '';
            if (receptionCriteriaStatusTimer) clearTimeout(receptionCriteriaStatusTimer);
        }

        function stripReceptionCriterionExamplePrefix(text) {
            return (text || '').trim().replace(/^Ej\.\s*/i, '').trim();
        }

        function normalizeReceptionCriterion(text) {
            return stripReceptionCriterionExamplePrefix(text)
                .toLowerCase()
                .replace(/\s+/g, ' ');
        }

        function renumberReceptionCriteria() {
            const pills = [...document.querySelectorAll('#receptionCriteriaList .criteria-pill')];
            pills.forEach((pill, idx) => {
                const num = pill.querySelector('.criteria-pill-num');
                if (num) num.textContent = String(idx + 1);
            });
        }

        function removeReceptionCriterion(button) {
            const pill = button.closest('.criteria-pill');
            if (pill) pill.remove();
            renumberReceptionCriteria();
        }

        function addReceptionCriterion() {
            const input = document.getElementById('receptionCriteriaInput');
            const rawValue = (input?.value || '').trim();
            if (!rawValue) return;
            const cleanValue = stripReceptionCriterionExamplePrefix(rawValue);
            const normalized = normalizeReceptionCriterion(cleanValue);
            const list = document.getElementById('receptionCriteriaList');
            const existing = [...list.querySelectorAll('.criteria-pill')].map(el => el.dataset.normalized ||
                normalizeReceptionCriterion(el.dataset.value));
            if (existing.includes(normalized)) {
                showReceptionCriteriaStatus('Criterio ya agregado.');
                input.value = '';
                closeReceptionCriteriaDropdown();
                return;
            }
            const nextNumber = list.querySelectorAll('.criteria-pill').length + 1;
            const pill = document.createElement('div');
            pill.className = 'criteria-pill';
            pill.dataset.value = cleanValue;
            pill.dataset.normalized = normalized;
            pill.innerHTML =
                `<span class="criteria-pill-num">${nextNumber}</span><span class="criteria-pill-text">${cleanValue}</span><button type="button" title="Quitar criterio" onclick="removeReceptionCriterion(this)">×</button>`;
            list.appendChild(pill);
            input.value = '';
            closeReceptionCriteriaDropdown();
            hideReceptionCriteriaStatus();
            renumberReceptionCriteria();
        }


        const evaluationCriteriaExamples = [
            'Ej. Precio de la oferta',
            'Ej. Costo total de la contratación',
            'Ej. Costo del ciclo de vida',
            'Ej. Calidad técnica de la oferta',
            'Ej. Cumplimiento técnico superior al mínimo exigido',
            'Ej. Desempeño o rendimiento ofrecido',
            'Ej. Características funcionales adicionales pertinentes',
            'Ej. Metodología de trabajo',
            'Ej. Plan de trabajo',
            'Ej. Programa o cronograma de ejecución',
            'Ej. Plazo de entrega',
            'Ej. Plazo de implementación',
            'Ej. Tiempo de respuesta',
            'Ej. Tiempo de reposición',
            'Ej. Continuidad operativa ofrecida',
            'Ej. Nivel de servicio ofrecido',
            'Ej. Disponibilidad del servicio',
            'Ej. Garantía ofrecida',
            'Ej. Extensión de la garantía',
            'Ej. Soporte técnico',
            'Ej. Tiempo de respuesta del soporte',
            'Ej. Cobertura del soporte',
            'Ej. Servicio postventa',
            'Ej. Mantención incluida',
            'Ej. Frecuencia de mantenciones',
            'Ej. Capacitación incluida',
            'Ej. Alcance de la capacitación',
            'Ej. Experiencia pertinente del oferente',
            'Ej. Experiencia específica en prestaciones similares',
            'Ej. Experiencia del equipo de trabajo',
            'Ej. Calificación o competencias del equipo de trabajo',
            'Ej. Certificaciones técnicas pertinentes',
            'Ej. Organización y composición del equipo',
            'Ej. Disponibilidad del equipo profesional o técnico',
            'Ej. Calidad de los entregables propuestos',
            'Ej. Propuesta de control de calidad',
            'Ej. Gestión de riesgos de la ejecución',
            'Ej. Plan de contingencia',
            'Ej. Seguridad de la información',
            'Ej. Medidas de ciberseguridad',
            'Ej. Protección de datos personales',
            'Ej. Compatibilidad e interoperabilidad',
            'Ej. Escalabilidad de la solución',
            'Ej. Facilidad de implementación',
            'Ej. Usabilidad',
            'Ej. Accesibilidad universal',
            'Ej. Eficiencia energética',
            'Ej. Consumo energético',
            'Ej. Desempeño ambiental',
            'Ej. Gestión de residuos',
            'Ej. Reducción de embalajes',
            'Ej. Uso de materiales reciclados o reciclables',
            'Ej. Criterios de economía circular',
            'Ej. Huella ambiental asociada a la prestación',
            'Ej. Medidas de sustentabilidad aplicables',
            'Ej. Condiciones de igualdad de género',
            'Ej. Medidas de inclusión laboral',
            'Ej. Programa de integridad',
            'Ej. Mejores condiciones de pago o facturación para la institución',
            'Ej. Valor agregado directamente relacionado con el objeto de la contratación',
            'Ej. Mejoras técnicas pertinentes y verificables',
            'Ej. Muestra, demostración o prueba técnica',
            'Ej. Resultado de prueba de concepto',
            'Ej. Calidad de muestra presentada',
            'Ej. Cobertura territorial de la prestación, cuando sea pertinente',
            'Ej. Disponibilidad logística',
            'Ej. Capacidad de distribución',
            'Ej. Tiempo de despacho',
            'Ej. Condiciones de instalación',
            'Ej. Tiempo de puesta en marcha',
            'Ej. Recursos técnicos comprometidos',
            'Ej. Equipamiento destinado a la prestación',
            'Ej. Plan de transferencia de conocimiento',
            'Ej. Documentación técnica ofrecida',
            'Ej. Reportabilidad y seguimiento',
            'Ej. Calidad de los mecanismos de supervisión',
            'Ej. Gestión de incidencias',
            'Ej. Acuerdos de nivel de servicio propuestos',
            'Ej. Cumplimiento de hitos propuestos',
            'Ej. Mejoras en plazo, calidad o desempeño respecto del estándar mínimo'
        ];

        let evaluationCriteriaStatusTimer = null;

        function stripEvaluationCriterionExamplePrefix(text) {
            return (text || '').trim().replace(/^Ej\.\s*/i, '').trim();
        }

        function normalizeEvaluationCriterion(text) {
            return stripEvaluationCriterionExamplePrefix(text)
                .toLowerCase()
                .replace(/\s+/g, ' ');
        }

        function renderEvaluationCriteriaDropdown(filterText = '') {
            const dropdown = document.getElementById('evaluationCriteriaDropdown');
            if (!dropdown) return;
            const q = (filterText || '').trim().toLowerCase();
            const matches = evaluationCriteriaExamples.filter(x => !q || x.toLowerCase().includes(q));
            dropdown.innerHTML = '';
            if (!matches.length) {
                const empty = document.createElement('div');
                empty.className = 'criteria-option';
                empty.style.cursor = 'default';
                empty.textContent = 'No hay ejemplos coincidentes. Puede escribir su propio criterio.';
                dropdown.appendChild(empty);
                return;
            }
            matches.forEach(example => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'criteria-option';
                const text = example.replace(/^Ej\.\s*/i, '');
                btn.innerHTML = `<span class="criteria-example-tag">Ej.</span> ${text}`;
                btn.onclick = () => selectEvaluationCriteriaExample(example);
                dropdown.appendChild(btn);
            });
        }

        function handleEvaluationCriteriaDropdownWheel(event) {
            const dropdown = event.currentTarget;
            const delta = event.deltaY;
            const atTop = dropdown.scrollTop <= 0;
            const atBottom = Math.ceil(dropdown.scrollTop + dropdown.clientHeight) >= dropdown.scrollHeight;
            if ((delta < 0 && atTop) || (delta > 0 && atBottom)) {
                event.preventDefault();
            }
            event.stopPropagation();
        }

        function openEvaluationCriteriaDropdown() {
            const dropdown = document.getElementById('evaluationCriteriaDropdown');
            if (!dropdown) return;
            renderEvaluationCriteriaDropdown(document.getElementById('evaluationCriteriaInput')?.value || '');
            dropdown.classList.remove('hidden');
            if (!dropdown.dataset.wheelBound) {
                dropdown.addEventListener('wheel', handleEvaluationCriteriaDropdownWheel, {
                    passive: false
                });
                dropdown.dataset.wheelBound = '1';
            }
        }

        function closeEvaluationCriteriaDropdown() {
            document.getElementById('evaluationCriteriaDropdown')?.classList.add('hidden');
        }

        function toggleEvaluationCriteriaDropdown() {
            const dropdown = document.getElementById('evaluationCriteriaDropdown');
            if (!dropdown) return;
            if (dropdown.classList.contains('hidden')) {
                const input = document.getElementById('evaluationCriteriaInput');
                if (input) input.value = '';
                renderEvaluationCriteriaDropdown('');
                dropdown.classList.remove('hidden');
                if (!dropdown.dataset.wheelBound) {
                    dropdown.addEventListener('wheel', handleEvaluationCriteriaDropdownWheel, {
                        passive: false
                    });
                    dropdown.dataset.wheelBound = '1';
                }
            } else {
                closeEvaluationCriteriaDropdown();
            }
        }

        function filterEvaluationCriteria() {
            openEvaluationCriteriaDropdown();
        }

        function selectEvaluationCriteriaExample(example) {
            const input = document.getElementById('evaluationCriteriaInput');
            if (input) input.value = example;
            closeEvaluationCriteriaDropdown();
        }

        function showEvaluationCriteriaStatus(message) {
            const box = document.getElementById('evaluationCriteriaStatus');
            if (!box) return;
            box.textContent = message;
            box.classList.add('show');
            if (evaluationCriteriaStatusTimer) clearTimeout(evaluationCriteriaStatusTimer);
            evaluationCriteriaStatusTimer = setTimeout(() => {
                box.classList.remove('show');
                box.textContent = '';
            }, 2200);
        }

        function hideEvaluationCriteriaStatus() {
            const box = document.getElementById('evaluationCriteriaStatus');
            if (!box) return;
            box.classList.remove('show');
            box.textContent = '';
            if (evaluationCriteriaStatusTimer) clearTimeout(evaluationCriteriaStatusTimer);
        }

        function renumberEvaluationCriteria() {
            const items = [...document.querySelectorAll('#evaluationCriteriaList .criteria-pill')];
            items.forEach((item, idx) => {
                const num = item.querySelector('.criteria-pill-num');
                if (num) num.textContent = String(idx + 1);
            });
        }

        function removeEvaluationCriterion(button) {
            const item = button.closest('.criteria-pill');
            if (item) item.remove();
            renumberEvaluationCriteria();
        }

        function addEvaluationCriterion() {
            const input = document.getElementById('evaluationCriteriaInput');
            const rawValue = (input?.value || '').trim();
            if (!rawValue) return;
            const cleanValue = stripEvaluationCriterionExamplePrefix(rawValue);
            const normalized = normalizeEvaluationCriterion(cleanValue);
            const list = document.getElementById('evaluationCriteriaList');
            const existing = [...list.querySelectorAll('.criteria-pill')]
                .map(el => el.dataset.normalized || normalizeEvaluationCriterion(el.dataset.value));
            if (existing.includes(normalized)) {
                showEvaluationCriteriaStatus('Criterio ya agregado.');
                input.value = '';
                closeEvaluationCriteriaDropdown();
                return;
            }
            const nextNumber = list.querySelectorAll('.criteria-pill').length + 1;
            const item = document.createElement('div');
            item.className = 'criteria-pill';
            item.dataset.value = cleanValue;
            item.dataset.normalized = normalized;
            item.innerHTML =
                `<span class="criteria-pill-num">${nextNumber}</span><span class="criteria-pill-text">${cleanValue}</span><button type="button" title="Quitar criterio" onclick="removeEvaluationCriterion(this)">×</button>`;
            list.appendChild(item);
            input.value = '';
            closeEvaluationCriteriaDropdown();
            hideEvaluationCriteriaStatus();
            renumberEvaluationCriteria();
        }


        const serviceLevelExamples = [
            'Ej. Hito: reunión de inicio y validación del plan de trabajo',
            'Ej. Hito: levantamiento de antecedentes completado',
            'Ej. Hito: aprobación del cronograma de ejecución',
            'Ej. Hito: entrega de primera versión para revisión',
            'Ej. Hito: validación técnica de la primera etapa',
            'Ej. Hito: instalación de equipos o componentes finalizada',
            'Ej. Hito: puesta en marcha realizada',
            'Ej. Hito: prueba de funcionamiento ejecutada satisfactoriamente',
            'Ej. Hito: capacitación inicial realizada',
            'Ej. Hito: entrega parcial N° 1 completada',
            'Ej. Hito: entrega parcial N° 2 completada',
            'Ej. Hito: aprobación de etapa intermedia por la contraparte técnica',
            'Ej. Hito: corrección de observaciones de etapa realizada',
            'Ej. Hito: entrega final realizada',
            'Ej. Hito: validación final por la contraparte técnica',
            'Ej. Hito: cierre técnico de la prestación',
            'Ej. Hito: recepción conforme final',
            'Ej. Entregable: plan de trabajo',
            'Ej. Entregable: carta Gantt o cronograma detallado',
            'Ej. Entregable: informe de levantamiento o diagnóstico',
            'Ej. Entregable: informe de avance N° 1',
            'Ej. Entregable: informe de avance N° 2',
            'Ej. Entregable: informe mensual de ejecución',
            'Ej. Entregable: informe técnico de resultados',
            'Ej. Entregable: informe final',
            'Ej. Entregable: memoria técnica',
            'Ej. Entregable: manual de usuario',
            'Ej. Entregable: manual de operación o mantención',
            'Ej. Entregable: ficha técnica del bien o servicio',
            'Ej. Entregable: planos, diagramas o esquemas técnicos',
            'Ej. Entregable: archivos editables o fuentes',
            'Ej. Entregable: base de datos o archivo consolidado',
            'Ej. Entregable: registro fotográfico de la ejecución',
            'Ej. Entregable: acta de instalación',
            'Ej. Entregable: acta de puesta en marcha',
            'Ej. Entregable: certificado de garantía',
            'Ej. Entregable: certificados técnicos o de conformidad',
            'Ej. Entregable: actas o registros de capacitación',
            'Ej. Entregable: listado de participantes o asistencia',
            'Ej. Entregable: documentación de respaldo de la prestación',
            'Ej. Entregable: reporte de incidencias y su resolución',
            'Ej. Entregable: reporte de cumplimiento de niveles de servicio',
            'Ej. Entregable: inventario de bienes instalados o entregados',
            'Ej. Entregable: respaldos digitales y credenciales de administración',
            'Ej. Entregable: plan de mantenimiento',
            'Ej. Entregable: plan de contingencia',
            'Ej. Entregable: matriz de observaciones y respuestas',
            'Ej. Nivel de servicio: disponibilidad mínima mensual del servicio',
            'Ej. Nivel de servicio: atención de requerimientos en horario hábil',
            'Ej. Nivel de servicio: soporte disponible 24/7',
            'Ej. Nivel de servicio: tiempo máximo de primera respuesta ante incidente crítico',
            'Ej. Nivel de servicio: tiempo máximo de primera respuesta ante incidente no crítico',
            'Ej. Nivel de servicio: tiempo máximo de resolución de incidente crítico',
            'Ej. Nivel de servicio: tiempo máximo de resolución de incidente no crítico',
            'Ej. Nivel de servicio: tiempo máximo de reposición de un bien defectuoso',
            'Ej. Nivel de servicio: tiempo máximo de despacho desde la solicitud',
            'Ej. Nivel de servicio: tiempo máximo de atención en terreno',
            'Ej. Nivel de servicio: frecuencia mínima de mantención preventiva',
            'Ej. Nivel de servicio: frecuencia mínima de visitas técnicas',
            'Ej. Nivel de servicio: porcentaje mínimo de cumplimiento del cronograma',
            'Ej. Nivel de servicio: porcentaje máximo de indisponibilidad permitido',
            'Ej. Nivel de servicio: porcentaje mínimo de entregas dentro de plazo',
            'Ej. Nivel de servicio: plazo máximo para corregir observaciones',
            'Ej. Nivel de servicio: plazo máximo para reemplazar personal clave',
            'Ej. Nivel de servicio: continuidad del servicio sin interrupciones no programadas',
            'Ej. Nivel de servicio: cumplimiento de ventana horaria de prestación',
            'Ej. Nivel de servicio: frecuencia de reportabilidad acordada',
            'Ej. Nivel de servicio: actualización periódica del estado de tickets o solicitudes'
        ];

        let serviceLevelStatusTimer = null;

        function stripServiceLevelExamplePrefix(text) {
            return (text || '').trim().replace(/^Ej\.\s*/i, '').trim();
        }

        function normalizeServiceLevel(text) {
            return stripServiceLevelExamplePrefix(text)
                .toLowerCase()
                .replace(/\s+/g, ' ');
        }

        function renderServiceLevelsDropdown(filterText = '') {
            const dropdown = document.getElementById('serviceLevelsDropdown');
            if (!dropdown) return;
            const q = (filterText || '').trim().toLowerCase();
            const matches = serviceLevelExamples.filter(x => !q || x.toLowerCase().includes(q));
            dropdown.innerHTML = '';
            if (!matches.length) {
                const empty = document.createElement('div');
                empty.className = 'criteria-option';
                empty.style.cursor = 'default';
                empty.textContent =
                    'No hay ejemplos coincidentes. Puede escribir su propio hito, entregable o nivel de servicio.';
                dropdown.appendChild(empty);
                return;
            }
            matches.forEach(example => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'criteria-option';
                const text = example.replace(/^Ej\.\s*/i, '');
                btn.innerHTML = `<span class="criteria-example-tag">Ej.</span> ${text}`;
                btn.onclick = () => selectServiceLevelExample(example);
                dropdown.appendChild(btn);
            });
        }

        function handleServiceLevelsDropdownWheel(event) {
            const dropdown = event.currentTarget;
            const delta = event.deltaY;
            const atTop = dropdown.scrollTop <= 0;
            const atBottom = Math.ceil(dropdown.scrollTop + dropdown.clientHeight) >= dropdown.scrollHeight;
            if ((delta < 0 && atTop) || (delta > 0 && atBottom)) {
                event.preventDefault();
            }
            event.stopPropagation();
        }

        function openServiceLevelsDropdown() {
            const dropdown = document.getElementById('serviceLevelsDropdown');
            if (!dropdown) return;
            renderServiceLevelsDropdown(document.getElementById('serviceLevelInput')?.value || '');
            dropdown.classList.remove('hidden');
            if (!dropdown.dataset.wheelBound) {
                dropdown.addEventListener('wheel', handleServiceLevelsDropdownWheel, {
                    passive: false
                });
                dropdown.dataset.wheelBound = '1';
            }
        }

        function closeServiceLevelsDropdown() {
            document.getElementById('serviceLevelsDropdown')?.classList.add('hidden');
        }

        function toggleServiceLevelsDropdown() {
            const dropdown = document.getElementById('serviceLevelsDropdown');
            if (!dropdown) return;
            if (dropdown.classList.contains('hidden')) {
                const input = document.getElementById('serviceLevelInput');
                if (input) input.value = '';
                renderServiceLevelsDropdown('');
                dropdown.classList.remove('hidden');
                if (!dropdown.dataset.wheelBound) {
                    dropdown.addEventListener('wheel', handleServiceLevelsDropdownWheel, {
                        passive: false
                    });
                    dropdown.dataset.wheelBound = '1';
                }
            } else {
                closeServiceLevelsDropdown();
            }
        }

        function filterServiceLevels() {
            openServiceLevelsDropdown();
        }

        function selectServiceLevelExample(example) {
            const input = document.getElementById('serviceLevelInput');
            if (input) input.value = example;
            closeServiceLevelsDropdown();
        }

        function showServiceLevelStatus(message) {
            const box = document.getElementById('serviceLevelStatus');
            if (!box) return;
            box.textContent = message;
            box.classList.add('show');
            if (serviceLevelStatusTimer) clearTimeout(serviceLevelStatusTimer);
            serviceLevelStatusTimer = setTimeout(() => {
                box.classList.remove('show');
                box.textContent = '';
            }, 2200);
        }

        function hideServiceLevelStatus() {
            const box = document.getElementById('serviceLevelStatus');
            if (!box) return;
            box.classList.remove('show');
            box.textContent = '';
            if (serviceLevelStatusTimer) clearTimeout(serviceLevelStatusTimer);
        }

        function renumberServiceLevels() {
            const items = [...document.querySelectorAll('#serviceLevelList .criteria-pill')];
            items.forEach((item, idx) => {
                const num = item.querySelector('.criteria-pill-num');
                if (num) num.textContent = String(idx + 1);
            });
        }

        function removeServiceLevel(button) {
            const item = button.closest('.criteria-pill');
            if (item) item.remove();
            renumberServiceLevels();
        }

        function addServiceLevel() {
            const input = document.getElementById('serviceLevelInput');
            const rawValue = (input?.value || '').trim();
            if (!rawValue) return;
            const cleanValue = stripServiceLevelExamplePrefix(rawValue);
            const normalized = normalizeServiceLevel(cleanValue);
            const list = document.getElementById('serviceLevelList');
            const existing = [...list.querySelectorAll('.criteria-pill')]
                .map(el => el.dataset.normalized || normalizeServiceLevel(el.dataset.value));
            if (existing.includes(normalized)) {
                showServiceLevelStatus('Elemento ya agregado.');
                input.value = '';
                closeServiceLevelsDropdown();
                return;
            }
            const nextNumber = list.querySelectorAll('.criteria-pill').length + 1;
            const item = document.createElement('div');
            item.className = 'criteria-pill';
            item.dataset.value = cleanValue;
            item.dataset.normalized = normalized;
            item.innerHTML =
                `<span class="criteria-pill-num">${nextNumber}</span><span class="criteria-pill-text">${cleanValue}</span><button type="button" title="Quitar elemento" onclick="removeServiceLevel(this)">×</button>`;
            list.appendChild(item);
            input.value = '';
            closeServiceLevelsDropdown();
            hideServiceLevelStatus();
            renumberServiceLevels();
        }

        let itemKeyCounter = 3;

        function currentItemRows() {
            return [...document.querySelectorAll('#itemsTable tbody tr')];
        }

        function visibleItemNumberForKey(key) {
            const rows = currentItemRows();
            const idx = rows.findIndex(r => r.dataset.itemKey === key);
            return idx >= 0 ? idx + 1 : null;
        }

        function itemLabel(row, index) {
            const name = (row.querySelector('.item-name')?.value || '').trim();
            return name ? `Ítem ${index+1} — ${name}` : `Ítem ${index+1}`;
        }

        function fileLooksLikeImage(file) {
            return !!file && ((file.type || '').startsWith('image/') || /\.(png|jpe?g|gif|webp|bmp|svg)$/i.test(file.name ||
                ''));
        }

        function fileKindIcon(file) {
            const name = (file?.name || '').toLowerCase();
            const type = file?.type || '';
            if (fileLooksLikeImage(file)) return '🖼️';
            if (type.includes('pdf') || name.endsWith('.pdf')) return '📄';
            if (name.endsWith('.doc') || name.endsWith('.docx')) return '📝';
            if (name.endsWith('.xls') || name.endsWith('.xlsx')) return '📊';
            return '📎';
        }

        function makePendingFileEntry(file, prefix) {
            const objectUrl = URL.createObjectURL(file);
            return {
                id: `${prefix}-pending-${Date.now()}-${Math.random().toString(36).slice(2,8)}`,
                file,
                displayName: file.name,
                selected: false,
                draftItemKeys: [],
                objectUrl,
                previewUrl: fileLooksLikeImage(file) ? objectUrl : null
            };
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function clearItemChecks(containerId) {
            document.querySelectorAll(`#${containerId} input[type="checkbox"]`).forEach(cb => cb.checked = false);
        }

        function fileExtension(file) {
            const name = (file?.name || '').toLowerCase();
            const m = name.match(/\.([^.]+)$/);
            return m ? m[1] : '';
        }

        function fileIsWord(file) {
            return ['doc', 'docx'].includes(fileExtension(file));
        }

        function fileIsSpreadsheet(file) {
            return ['xls', 'xlsx'].includes(fileExtension(file));
        }

        function fileIsDocx(file) {
            return fileExtension(file) === 'docx';
        }
        async function extractLegacyDocText(file) {
            const buf = await file.arrayBuffer();
            const bytes = new Uint8Array(buf);
            const chunks = [];

            // UTF-16LE printable sequences
            let current = '';
            for (let i = 0; i < bytes.length - 1; i += 2) {
                const a = bytes[i],
                    b = bytes[i + 1];
                if (b === 0 && a >= 32 && a <= 126) {
                    current += String.fromCharCode(a);
                } else {
                    if (current.length >= 12) chunks.push(current);
                    current = '';
                }
            }
            if (current.length >= 12) chunks.push(current);

            // ASCII printable sequences as secondary fallback
            current = '';
            for (let i = 0; i < bytes.length; i++) {
                const b = bytes[i];
                if ((b >= 32 && b <= 126) || b === 10 || b === 13 || b === 9) {
                    current += String.fromCharCode(b);
                } else {
                    if (current.trim().length >= 30) chunks.push(current.trim());
                    current = '';
                }
            }
            if (current.trim().length >= 30) chunks.push(current.trim());

            const unique = [...new Set(chunks)]
                .filter(x => /[A-Za-zÁÉÍÓÚáéíóúÑñ]/.test(x))
                .join('\n')
                .replace(/\s{3,}/g, ' ')
                .trim();

            return unique.slice(0, 8000);
        }
        async function getWordPreviewContent(file, asHtml = false) {
            if (fileIsDocx(file) && window.mammoth) {
                const arrayBuffer = await file.arrayBuffer();
                if (asHtml) {
                    const result = await window.mammoth.convertToHtml({
                        arrayBuffer
                    });
                    return result.value || '';
                }
                const result = await window.mammoth.extractRawText({
                    arrayBuffer
                });
                return result.value || '';
            }
            return await extractLegacyDocText(file);
        }
        async function getSpreadsheetPreviewHtml(file, large = false) {
            if (!window.XLSX) return '';
            const arrayBuffer = await file.arrayBuffer();
            const workbook = window.XLSX.read(arrayBuffer, {
                type: 'array'
            });
            const sheet = workbook.Sheets[workbook.SheetNames[0]];
            if (!sheet) return '';
            const range = window.XLSX.utils.decode_range(sheet['!ref'] || 'A1:A1');
            const maxRows = large ? 80 : 8;
            const maxCols = large ? 20 : 6;
            range.e.r = Math.min(range.e.r, range.s.r + maxRows - 1);
            range.e.c = Math.min(range.e.c, range.s.c + maxCols - 1);

            let out = '<table>';
            for (let r = range.s.r; r <= range.e.r; r++) {
                out += '<tr>';
                for (let c = range.s.c; c <= range.e.c; c++) {
                    const address = window.XLSX.utils.encode_cell({
                        r,
                        c
                    });
                    const cell = sheet[address];
                    out += `<td>${escapeHtml(cell?.w ?? cell?.v ?? '')}</td>`;
                }
                out += '</tr>';
            }
            out += '</table>';
            return out;
        }
        async function getTextFilePreview(file, maxChars = 5000) {
            try {
                return (await file.text()).slice(0, maxChars);
            } catch (e) {
                return '';
            }
        }
        async function hydrateDocumentThumbnail(entry, container) {
            if (!container || !entry?.file) return;
            const file = entry.file;
            try {
                if (fileIsWord(file)) {
                    const text = await getWordPreviewContent(file, false);
                    container.innerHTML = text ?
                        `<div class="doc-page-title">Documento Word</div><div class="doc-text">${escapeHtml(text.slice(0,1200))}</div>` :
                        `<div class="loading-doc">No se pudo extraer texto del documento.</div>`;
                    return;
                }
                if (fileIsSpreadsheet(file)) {
                    const table = await getSpreadsheetPreviewHtml(file, false);
                    container.innerHTML = table || `<div class="loading-doc">No se pudo leer la hoja de cálculo.</div>`;
                    return;
                }
                if (fileIsBrowserTextPreviewable(file)) {
                    const text = await getTextFilePreview(file, 1400);
                    container.innerHTML = `<div class="doc-text">${escapeHtml(text)}</div>`;
                    return;
                }
                container.innerHTML =
                    `<div class="loading-doc">${fileKindIcon(file)}<br>Vista previa disponible al ampliar</div>`;
            } catch (err) {
                container.innerHTML = `<div class="loading-doc">No se pudo generar la miniatura.</div>`;
            }
        }

        function fileIsPdf(file) {
            const name = (file?.name || '').toLowerCase();
            const type = (file?.type || '').toLowerCase();
            return type.includes('pdf') || name.endsWith('.pdf');
        }

        function fileIsBrowserTextPreviewable(file) {
            const name = (file?.name || '').toLowerCase();
            const type = (file?.type || '').toLowerCase();
            return type.startsWith('text/') || /\.(txt|csv|html?|xml|json)$/i.test(name);
        }

        function openGenericFilePreview(record) {
            if (!record) return;
            const modal = document.getElementById('filePreviewModal');
            const body = document.getElementById('filePreviewBody');
            const title = document.getElementById('filePreviewTitle');
            const meta = document.getElementById('filePreviewMeta');
            const openBtn = document.getElementById('filePreviewOpenBtn');
            if (!modal || !body || !title || !meta || !openBtn) return;

            const file = record.file;
            const src = record.objectUrl || record.previewUrl || (file ? URL.createObjectURL(file) : null);
            if (!src) return;

            title.textContent = record.displayName || record.name || file?.name || 'Archivo adjunto';
            const kb = file?.size ? Math.max(1, Math.round(file.size / 1024)) : null;
            meta.textContent = [file?.type || 'Tipo no informado', kb ? `${kb} KB` : null].filter(Boolean).join(' · ');
            openBtn.onclick = () => window.open(src, '_blank');

            body.innerHTML = '';

            if (fileLooksLikeImage(file)) {
                const img = document.createElement('img');
                img.src = src;
                img.alt = record.displayName || record.name || file?.name || 'Imagen';
                body.appendChild(img);
            } else if (fileIsPdf(file)) {
                const iframe = document.createElement('iframe');
                iframe.src = src;
                iframe.title = 'Vista previa PDF';
                body.appendChild(iframe);
            } else if (fileIsWord(file)) {
                const box = document.createElement('div');
                box.className = 'office-preview-large';
                box.innerHTML = '<div class="loading-doc">Generando vista previa del documento Word…</div>';
                body.appendChild(box);
                (async () => {
                    try {
                        const content = await getWordPreviewContent(file, true);
                        if (fileIsDocx(file) && content) {
                            box.innerHTML = `<div class="word-content">${content}</div>`;
                        } else {
                            const text = content || await getWordPreviewContent(file, false);
                            box.innerHTML =
                                `<div class="word-content"><pre style="white-space:pre-wrap;font:inherit">${escapeHtml(text||'No se pudo extraer contenido del documento.')}</pre></div>`;
                        }
                    } catch (e) {
                        box.innerHTML =
                            '<div class="file-preview-fallback"><strong>No fue posible generar la vista previa de este Word.</strong></div>';
                    }
                })();
            } else if (fileIsSpreadsheet(file)) {
                const box = document.createElement('div');
                box.className = 'office-preview-large';
                box.innerHTML = '<div class="loading-doc">Generando vista previa de la hoja de cálculo…</div>';
                body.appendChild(box);
                (async () => {
                    try {
                        const table = await getSpreadsheetPreviewHtml(file, true);
                        box.innerHTML = table ||
                            '<div class="file-preview-fallback"><strong>No fue posible leer la hoja de cálculo.</strong></div>';
                    } catch (e) {
                        box.innerHTML =
                            '<div class="file-preview-fallback"><strong>No fue posible generar la vista previa de esta hoja de cálculo.</strong></div>';
                    }
                })();
            } else if (fileIsBrowserTextPreviewable(file)) {
                const iframe = document.createElement('iframe');
                iframe.src = src;
                iframe.title = 'Vista previa del archivo';
                body.appendChild(iframe);
            } else {
                const fallback = document.createElement('div');
                fallback.className = 'file-preview-fallback';
                fallback.innerHTML =
                    `
      <div class="big-icon">${fileKindIcon(file)}</div>
      <strong>${escapeHtml(record.displayName || record.name || file?.name || 'Archivo adjunto')}</strong>
      <p>No fue posible interpretar automáticamente este formato en el navegador. Use “Abrir archivo” para revisarlo en una aplicación compatible.</p>`;
                body.appendChild(fallback);
            }

            modal.classList.remove('hidden');
        }

        function closeGenericFilePreview(event) {
            if (event && event.target !== event.currentTarget) return;
            const modal = document.getElementById('filePreviewModal');
            const body = document.getElementById('filePreviewBody');
            if (modal) modal.classList.add('hidden');
            if (body) body.innerHTML = '';
        }

        /* ===========================
           GESTOR DE COTIZACIONES
           =========================== */
        let currentAttachmentOriginKey = null;
        let managerPendingFiles = [];
        const attachmentsRegistry = [];
        const lotQuoteRegistry = [];
        let quoteDragPayload = null;

        function setQuoteDragPayload(payload) {
            quoteDragPayload = payload;
        }

        function clearQuoteDragPayload() {
            quoteDragPayload = null;
            document.querySelectorAll('#managerPendingGrid .drag-over, #managerItemList .drag-over').forEach(el => el
                .classList.remove('drag-over'));
        }

        function itemNumberLabelForQuote(key) {
            const num = visibleItemNumberForKey(key);
            return num === null ? null : `Ítem ${num}`;
        }

        function getDraftLinkedQuotesForItem(itemKey) {
            return managerPendingFiles.filter(x => (x.draftItemKeys || []).includes(itemKey));
        }

        function buildDraftItemPillsForQuote(entry) {
            return (entry.draftItemKeys || [])
                .map(key => {
                    const label = itemNumberLabelForQuote(key);
                    if (!label) return '';
                    return `<span class="draft-link-pill">${label}<button type="button" class="draft-link-pill-remove" title="Quitar vínculo" data-file-id="${entry.id}" data-item-key="${key}">×</button></span>`;
                }).join('');
        }

        function buildDraftQuotePillsForItem(itemKey, linkedDrafts) {
            return linkedDrafts
                .map(file =>
                    `<span class="draft-link-pill">${escapeHtml(file.displayName||file.file.name)}<button type="button" class="draft-link-pill-remove" title="Quitar vínculo" data-file-id="${file.id}" data-item-key="${itemKey}">×</button></span>`
                )
                .join('');
        }

        function addDraftQuoteItemLink(fileId, itemKey) {
            const entry = managerPendingFiles.find(x => x.id === fileId);
            if (!entry) return;
            if (!entry.draftItemKeys) entry.draftItemKeys = [];
            if (!entry.draftItemKeys.includes(itemKey)) entry.draftItemKeys.push(itemKey);
            renderPendingQuoteFiles();
            renderManagerItemList();
        }

        function removeDraftQuoteItemLink(fileId, itemKey) {
            const entry = managerPendingFiles.find(x => x.id === fileId);
            if (!entry || !entry.draftItemKeys) return;
            entry.draftItemKeys = entry.draftItemKeys.filter(k => k !== itemKey);
            renderPendingQuoteFiles();
            renderManagerItemList();
        }

        function openAttachmentManager(button) {
            const row = button.closest('tr');
            currentAttachmentOriginKey = row?.dataset.itemKey || null;
            const input = document.getElementById('managerFileInput');
            if (input) input.value = '';
            renderPendingQuoteFiles();
            renderManagerItemList();
            renderAttachmentRegister();
            document.getElementById('attachmentModal').classList.remove('hidden');
        }

        function closeAttachmentManager(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('attachmentModal').classList.add('hidden');
        }

        function handleManagerFile(input) {
            const incoming = [...(input.files || [])];
            incoming.forEach(file => managerPendingFiles.push(makePendingFileEntry(file, 'quote')));
            input.value = '';
            renderPendingQuoteFiles();
            renderManagerItemList();
        }

        function togglePendingQuote(id, force = null) {
            const entry = managerPendingFiles.find(x => x.id === id);
            if (!entry) return;
            entry.selected = force === null ? !entry.selected : !!force;
            renderPendingQuoteFiles();
        }

        function renamePendingQuote(id, value) {
            const entry = managerPendingFiles.find(x => x.id === id);
            if (entry) entry.displayName = value;
        }

        function removePendingQuote(id) {
            const idx = managerPendingFiles.findIndex(x => x.id === id);
            if (idx < 0) return;
            if (managerPendingFiles[idx].objectUrl) {
                try {
                    URL.revokeObjectURL(managerPendingFiles[idx].objectUrl);
                } catch (e) {}
            }
            managerPendingFiles.splice(idx, 1);
            renderPendingQuoteFiles();
            renderManagerItemList();
        }

        function renderPendingQuoteFiles() {
            const grid = document.getElementById('managerPendingGrid');
            const summary = document.getElementById('managerFileName');
            if (!grid || !summary) return;
            grid.innerHTML = '';

            const selectedCount = managerPendingFiles.filter(x => x.selected).length;
            if (!managerPendingFiles.length) {
                summary.textContent = 'Ningún archivo seleccionado.';
                return;
            }

            const totalKb = managerPendingFiles.reduce((sum, x) => sum + Math.max(1, Math.round(x.file.size / 1024)), 0);
            const draftLinkCount = managerPendingFiles.reduce((sum, x) => sum + (x.draftItemKeys || []).length, 0);
            summary.textContent =
                `${managerPendingFiles.length} archivo(s) cargado(s) · ${selectedCount} seleccionado(s) · ${draftLinkCount} vínculo(s) preparados · ${totalKb} KB en total.`;

            managerPendingFiles.forEach(entry => {
                const card = document.createElement('div');
                card.className = 'pending-file-card' + (entry.selected ? ' selected' : '');
                card.draggable = true;
                card.dataset.fileId = entry.id;

                const needsContentPreview = fileIsWord(entry.file) || fileIsSpreadsheet(entry.file) ||
                    fileIsBrowserTextPreviewable(entry.file);
                const preview = entry.previewUrl ?
                    `<div class="pending-file-preview"><img src="${entry.previewUrl}" alt=""><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>` :
                    fileIsPdf(entry.file) ?
                    `<div class="pending-file-preview"><embed src="${entry.objectUrl}#page=1&toolbar=0&navpanes=0&scrollbar=0" type="application/pdf"><button type="button" class="preview-expand-btn" title="Vista previa del PDF">⤢</button></div>` :
                    needsContentPreview ?
                    `<div class="pending-file-preview"><div class="document-mini-preview"><div class="loading-doc">Generando vista previa…</div></div><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>` :
                    `<div class="pending-file-preview"><span class="pending-file-icon">${fileKindIcon(entry.file)}</span><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>`;

                const draftPills = buildDraftItemPillsForQuote(entry);

                card.innerHTML = `
      <input class="pending-file-check" type="checkbox" ${entry.selected?'checked':''} aria-label="Seleccionar archivo">
      ${preview}
      <input type="text" value="${escapeHtml(entry.displayName)}" aria-label="Nombre editable del archivo">
      <div class="pending-file-meta">${Math.max(1,Math.round(entry.file.size/1024))} KB</div>
      ${draftPills?`<div class="draft-link-pills">${draftPills}</div>`:''}
      <div class="pending-file-actions"><button type="button" class="pending-remove">Quitar</button></div>`;

                const checkbox = card.querySelector('.pending-file-check');
                checkbox.addEventListener('click', e => e.stopPropagation());
                checkbox.addEventListener('change', () => togglePendingQuote(entry.id, checkbox.checked));

                const nameInput = card.querySelector('input[type="text"]');
                nameInput.addEventListener('click', e => e.stopPropagation());
                nameInput.addEventListener('mousedown', e => e.stopPropagation());
                nameInput.addEventListener('input', () => renamePendingQuote(entry.id, nameInput.value));

                card.querySelector('.pending-remove').addEventListener('click', e => {
                    e.stopPropagation();
                    removePendingQuote(entry.id);
                });

                const expandBtn = card.querySelector('.preview-expand-btn');
                if (expandBtn) {
                    expandBtn.addEventListener('click', e => {
                        e.stopPropagation();
                        openGenericFilePreview(entry);
                    });
                }

                card.querySelectorAll('.draft-link-pill-remove').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.stopPropagation();
                        removeDraftQuoteItemLink(btn.dataset.fileId, btn.dataset.itemKey);
                    });
                });

                card.addEventListener('click', e => {
                    if (e.target.closest('input,button')) return;
                    togglePendingQuote(entry.id);
                });

                card.addEventListener('dragstart', e => {
                    if (e.target.closest('input,button')) {
                        e.preventDefault();
                        return;
                    }
                    setQuoteDragPayload({
                        type: 'file',
                        fileId: entry.id
                    });
                    e.dataTransfer.effectAllowed = 'link';
                    e.dataTransfer.setData('text/plain', `file:${entry.id}`);
                });
                card.addEventListener('dragend', clearQuoteDragPayload);

                card.addEventListener('dragover', e => {
                    if (quoteDragPayload?.type === 'item') {
                        e.preventDefault();
                        card.classList.add('drag-over');
                        e.dataTransfer.dropEffect = 'link';
                    }
                });
                card.addEventListener('dragleave', () => card.classList.remove('drag-over'));
                card.addEventListener('drop', e => {
                    e.preventDefault();
                    card.classList.remove('drag-over');
                    if (quoteDragPayload?.type === 'item') {
                        addDraftQuoteItemLink(entry.id, quoteDragPayload.itemKey);
                    }
                    clearQuoteDragPayload();
                });

                grid.appendChild(card);

                const docPreview = card.querySelector('.document-mini-preview');
                if (docPreview) hydrateDocumentThumbnail(entry, docPreview);
            });
        }

        function renderManagerItemList() {
            const container = document.getElementById('managerItemList');
            if (!container) return;

            const previouslySelected = new Set(
                [...container.querySelectorAll('.drag-item-card.selected')].map(el => el.dataset.itemKey)
            );

            container.innerHTML = '';
            currentItemRows().forEach((row, idx) => {
                const key = row.dataset.itemKey;
                const name = (row.querySelector('.item-name')?.value || '').trim();
                const linkedDrafts = getDraftLinkedQuotesForItem(key);

                const card = document.createElement('div');
                card.className = 'drag-item-card';
                card.dataset.itemKey = key;
                card.draggable = true;

                const shouldSelect = previouslySelected.has(key) || (previouslySelected.size === 0 && key ===
                    currentAttachmentOriginKey);
                if (shouldSelect) card.classList.add('selected');

                card.innerHTML = `
      <input type="checkbox" class="item-select-box" ${shouldSelect?'checked':''} aria-label="Seleccionar ítem">
      <span class="item-drag-handle" title="Arrastrar ítem">⋮⋮</span>
      <div class="item-drag-content">
        <strong>Ítem ${idx+1}</strong>
        <small>${escapeHtml(name||'Sin nombre ingresado')}</small>
        ${linkedDrafts.length?`<div class="draft-link-pills">${buildDraftQuotePillsForItem(key, linkedDrafts)}</div>`:''}
      </div>`;

                const checkbox = card.querySelector('.item-select-box');
                checkbox.addEventListener('click', e => e.stopPropagation());
                checkbox.addEventListener('change', () => {
                    card.classList.toggle('selected', checkbox.checked);
                });

                card.addEventListener('click', e => {
                    if (e.target.closest('input,button')) return;
                    card.classList.toggle('selected');
                    checkbox.checked = card.classList.contains('selected');
                });

                card.querySelectorAll('.draft-link-pill-remove').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.stopPropagation();
                        removeDraftQuoteItemLink(btn.dataset.fileId, btn.dataset.itemKey);
                    });
                });

                card.addEventListener('dragstart', e => {
                    if (e.target.closest('input,button')) {
                        e.preventDefault();
                        return;
                    }
                    setQuoteDragPayload({
                        type: 'item',
                        itemKey: key
                    });
                    e.dataTransfer.effectAllowed = 'link';
                    e.dataTransfer.setData('text/plain', `item:${key}`);
                });
                card.addEventListener('dragend', clearQuoteDragPayload);

                card.addEventListener('dragover', e => {
                    if (quoteDragPayload?.type === 'file') {
                        e.preventDefault();
                        card.classList.add('drag-over');
                        e.dataTransfer.dropEffect = 'link';
                    }
                });
                card.addEventListener('dragleave', () => card.classList.remove('drag-over'));
                card.addEventListener('drop', e => {
                    e.preventDefault();
                    card.classList.remove('drag-over');
                    if (quoteDragPayload?.type === 'file') {
                        addDraftQuoteItemLink(quoteDragPayload.fileId, key);
                    }
                    clearQuoteDragPayload();
                });

                container.appendChild(card);
            });
        }

        function saveAttachmentLink() {
            const selectedFiles = managerPendingFiles.filter(x => x.selected);
            const selectedItems = [...document.querySelectorAll('#managerItemList .drag-item-card.selected')]
                .map(x => x.dataset.itemKey);

            if (selectedFiles.length && selectedItems.length) {
                selectedFiles.forEach(entry => {
                    entry.draftItemKeys = [...new Set([...(entry.draftItemKeys || []), ...selectedItems])];
                });
            }

            const ready = managerPendingFiles.filter(x => (x.draftItemKeys || []).length > 0);
            if (!ready.length) {
                alert(
                    'Vincule al menos una cotización con un ítem. Puede hacerlo seleccionando archivo(s) e ítem(s), o arrastrando el archivo sobre el ítem —o el ítem sobre el archivo.'
                );
                return;
            }

            ready.forEach(entry => {
                attachmentsRegistry.push({
                    id: 'att-' + Date.now() + '-' + Math.random().toString(36).slice(2, 7),
                    file: entry.file,
                    name: (entry.displayName || entry.file.name).trim() || entry.file.name,
                    type: entry.file.type || 'archivo',
                    itemKeys: [...new Set(entry.draftItemKeys)],
                    objectUrl: entry.objectUrl,
                    previewUrl: entry.previewUrl
                });
            });

            managerPendingFiles = managerPendingFiles.filter(x => (x.draftItemKeys || []).length === 0);
            currentAttachmentOriginKey = null;
            refreshQuoteIndicators();
            renderPendingQuoteFiles();
            renderAttachmentRegister();
            renderManagerItemList();
        }

        function refreshQuoteIndicators() {
            currentItemRows().forEach(row => {
                const key = row.dataset.itemKey;
                const linked = attachmentsRegistry.filter(a => a.itemKeys.includes(key));
                const box = row.querySelector('.quote-info');
                const viewBtn = row.querySelector('.quote-view-btn');
                if (viewBtn) viewBtn.disabled = linked.length === 0;
                if (!box) return;
                if (!linked.length) {
                    box.textContent = 'Sin cotización vinculada';
                    box.style.color = '';
                    return;
                }
                box.textContent = `${linked.length} cotización(es)`;
                box.style.color = 'var(--green-dark)';
            });
        }

        function openRowQuoteViewer(button) {
            const row = button?.closest('tr');
            const key = row?.dataset?.itemKey;
            if (!key) return;
            const linked = attachmentsRegistry.filter(a => (a.itemKeys || []).includes(key));
            const num = visibleItemNumberForKey(key);
            openQuoteViewer(linked, `Cotizaciones vinculadas · Ítem ${num ?? ''}`);
        }

        function openLotQuoteViewer() {
            openQuoteViewer(lotQuoteRegistry, 'Cotizaciones adjuntas por lote');
        }

        function openQuoteViewer(records, titleText) {
            const modal = document.getElementById('quoteViewerModal');
            const title = document.getElementById('quoteViewerTitle');
            const list = document.getElementById('quoteViewerList');
            const empty = document.getElementById('quoteViewerEmpty');
            if (!modal || !title || !list || !empty) return;

            title.textContent = titleText || 'Cotizaciones';
            list.innerHTML = '';

            if (!records?.length) {
                empty.classList.remove('hidden');
            } else {
                empty.classList.add('hidden');
                records.forEach(record => {
                    const card = document.createElement('div');
                    card.className = 'quote-view-card';

                    const kb = record.file?.size ? Math.max(1, Math.round(record.file.size / 1024)) : null;
                    const type = record.file?.type || 'Archivo';
                    const name = record.displayName || record.name || record.file?.name || 'Cotización';

                    const info = document.createElement('div');
                    info.innerHTML = `<div class="quote-view-name">${escapeHtml(name)}</div>
        <div class="quote-view-meta">${escapeHtml(type)}${kb ? ` · ${kb} KB` : ''}</div>`;

                    const actions = document.createElement('div');
                    const view = document.createElement('button');
                    view.type = 'button';
                    view.className = 'small-btn';
                    view.textContent = '👁 Visualizar';
                    view.onclick = () => openGenericFilePreview(record);
                    actions.appendChild(view);

                    card.appendChild(info);
                    card.appendChild(actions);
                    list.appendChild(card);
                });
            }

            modal.classList.remove('hidden');
        }

        function closeQuoteViewer(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('quoteViewerModal')?.classList.add('hidden');
        }

        function renameAttachment(id) {
            const att = attachmentsRegistry.find(a => a.id === id);
            if (!att) return;
            const value = prompt('Nuevo nombre para el archivo de cotización:', att.name);
            if (value === null) return;
            const clean = value.trim();
            if (clean) att.name = clean;
            renderAttachmentRegister();
        }

        function renderAttachmentRegister() {
            const wrap = document.getElementById('attachmentRegister');
            const empty = document.getElementById('attachmentRegisterEmpty');
            if (!wrap || !empty) return;
            wrap.innerHTML = '';
            empty.classList.toggle('hidden', attachmentsRegistry.length > 0);

            attachmentsRegistry.forEach(att => {
                const card = document.createElement('div');
                card.className = 'attachment-card';

                const itemNums = att.itemKeys.map(k => visibleItemNumberForKey(k)).filter(n => n !== null).sort((a,
                    b) => a - b);
                const needsContentPreview = fileIsWord(att.file) || fileIsSpreadsheet(att.file) ||
                    fileIsBrowserTextPreviewable(att.file);
                const thumb = att.previewUrl ?
                    `<div class="attachment-thumb" style="position:relative;"><img src="${att.previewUrl}" alt=""><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>` :
                    fileIsPdf(att.file) ?
                    `<div class="attachment-thumb" style="position:relative;"><embed src="${att.objectUrl}#page=1&toolbar=0&navpanes=0&scrollbar=0" type="application/pdf"><button type="button" class="preview-expand-btn" title="Vista previa del PDF">⤢</button></div>` :
                    needsContentPreview ?
                    `<div class="attachment-thumb" style="position:relative;"><div class="document-mini-preview"><div class="loading-doc">Cargando…</div></div><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>` :
                    `<div class="attachment-thumb" style="position:relative;">${fileKindIcon(att.file)}<button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>`;

                card.innerHTML = `
      <div class="attachment-card-top">
        <div class="attachment-card-main">
          ${thumb}
          <div>
            <div class="attachment-card-title">${escapeHtml(att.name)}</div>
            <div class="attachment-links">${itemNums.map(n=>`<span class="link-pill">Ítem ${n}</span>`).join('')}</div>
          </div>
        </div>
        <div class="attachment-card-actions">
          <button type="button" class="attachment-rename">Renombrar</button>
          <button type="button" class="attachment-remove" title="Eliminar cotización">Eliminar</button>
        </div>
      </div>`;

                card.querySelector('.attachment-rename').addEventListener('click', () => {
                    renameAttachment(att.id);
                });
                card.querySelector('.attachment-remove').addEventListener('click', () => {
                    removeAttachment(att.id);
                });

                const expandBtn = card.querySelector('.preview-expand-btn');
                if (expandBtn) {
                    expandBtn.addEventListener('click', () => {
                        openGenericFilePreview(att);
                    });
                }

                wrap.appendChild(card);

                const docPreview = card.querySelector('.document-mini-preview');
                if (docPreview) hydrateDocumentThumbnail(att, docPreview);
            });
        }

        function removeAttachment(id) {
            const idx = attachmentsRegistry.findIndex(a => a.id === id);
            if (idx < 0) return;

            const removed = attachmentsRegistry[idx];
            attachmentsRegistry.splice(idx, 1);

            if (removed.objectUrl) {
                try {
                    URL.revokeObjectURL(removed.objectUrl);
                } catch (e) {}
            }

            currentAttachmentOriginKey = null;
            refreshQuoteIndicators();
            renderAttachmentRegister();
            renderManagerItemList();
        }

        /* ===========================
           GESTOR DE IMÁGENES REFERENCIALES
           =========================== */
        let currentRefImageOriginKey = null;
        let refImagePendingFiles = [];
        const referenceImagesRegistry = [];

        function openReferenceImageManager(originKey = null) {
            currentRefImageOriginKey = originKey;
            const input = document.getElementById('refImageManagerInput');
            if (input) input.value = '';
            renderPendingReferenceImages();
            renderRefImageItemList();
            renderReferenceImageSummary();
            renderReferenceImageRegister();
            document.getElementById('refImageModal').classList.remove('hidden');
        }

        function closeReferenceImageManager(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('refImageModal').classList.add('hidden');
        }

        function handleRefImageManagerFile(input) {
            const incoming = [...(input.files || [])];
            incoming.forEach(file => refImagePendingFiles.push(makePendingFileEntry(file, 'refimg')));
            input.value = '';
            renderPendingReferenceImages();
        }

        function togglePendingRefImage(id, force = null) {
            const entry = refImagePendingFiles.find(x => x.id === id);
            if (!entry) return;
            entry.selected = force === null ? !entry.selected : !!force;
            renderPendingReferenceImages();
        }

        function renamePendingRefImage(id, value) {
            const entry = refImagePendingFiles.find(x => x.id === id);
            if (entry) entry.displayName = value;
        }

        function removePendingRefImage(id) {
            const idx = refImagePendingFiles.findIndex(x => x.id === id);
            if (idx < 0) return;
            if (refImagePendingFiles[idx].previewUrl) URL.revokeObjectURL(refImagePendingFiles[idx].previewUrl);
            refImagePendingFiles.splice(idx, 1);
            renderPendingReferenceImages();
        }
        let refImageDragPayload = null;

        function setRefImageDragPayload(payload) {
            refImageDragPayload = payload;
        }

        function clearRefImageDragPayload() {
            refImageDragPayload = null;
            document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
        }

        function addDraftImageItemLink(imageId, itemKey) {
            const entry = refImagePendingFiles.find(x => x.id === imageId);
            if (!entry) return;
            if (!entry.draftItemKeys) entry.draftItemKeys = [];
            if (!entry.draftItemKeys.includes(itemKey)) entry.draftItemKeys.push(itemKey);
            renderPendingReferenceImages();
            renderRefImageItemList();
        }

        function removeDraftImageItemLink(imageId, itemKey) {
            const entry = refImagePendingFiles.find(x => x.id === imageId);
            if (!entry || !entry.draftItemKeys) return;
            entry.draftItemKeys = entry.draftItemKeys.filter(k => k !== itemKey);
            renderPendingReferenceImages();
            renderRefImageItemList();
        }

        function itemNumberLabelFromKey(key) {
            const num = visibleItemNumberForKey(key);
            return num === null ? null : `Ítem ${num}`;
        }

        function getDraftLinkedImagesForItem(itemKey) {
            return refImagePendingFiles.filter(x => (x.draftItemKeys || []).includes(itemKey));
        }

        function buildDraftItemPillsForImage(entry) {
            return (entry.draftItemKeys || [])
                .map(key => {
                    const label = itemNumberLabelFromKey(key);
                    if (!label) return '';
                    return `<span class="draft-link-pill">${label}<button type="button" class="draft-link-pill-remove" title="Quitar vínculo" data-image-id="${entry.id}" data-item-key="${key}">×</button></span>`;
                }).join('');
        }

        function buildDraftImagePillsForItem(itemKey, linkedDrafts) {
            return linkedDrafts
                .map(img =>
                    `<span class="draft-link-pill">${escapeHtml(img.displayName||img.file.name)}<button type="button" class="draft-link-pill-remove" title="Quitar vínculo" data-image-id="${img.id}" data-item-key="${itemKey}">×</button></span>`
                )
                .join('');
        }

        function renderPendingReferenceImages() {
            const grid = document.getElementById('refImagePendingGrid');
            const summary = document.getElementById('refImageManagerFileName');
            if (!grid || !summary) return;
            grid.innerHTML = '';

            const selectedCount = refImagePendingFiles.filter(x => x.selected).length;
            if (!refImagePendingFiles.length) {
                summary.textContent = 'Ninguna imagen cargada.';
                return;
            }

            const totalKb = refImagePendingFiles.reduce((sum, x) => sum + Math.max(1, Math.round(x.file.size / 1024)), 0);
            const draftLinkCount = refImagePendingFiles.reduce((sum, x) => sum + (x.draftItemKeys || []).length, 0);
            summary.textContent =
                `${refImagePendingFiles.length} imagen(es) cargada(s) · ${selectedCount} seleccionada(s) · ${draftLinkCount} vínculo(s) preparados · ${totalKb} KB en total.`;

            refImagePendingFiles.forEach(entry => {
                const card = document.createElement('div');
                card.className = 'pending-file-card' + (entry.selected ? ' selected' : '');
                card.draggable = true;
                card.dataset.imageId = entry.id;

                const draftPills = buildDraftItemPillsForImage(entry);

                card.innerHTML = `
      <input class="pending-file-check" type="checkbox" ${entry.selected?'checked':''} aria-label="Seleccionar imagen">
      <div class="pending-file-preview">
        ${entry.previewUrl?`<img src="${entry.previewUrl}" alt="">`:'<span class="pending-file-icon">🖼️</span>'}
        ${entry.previewUrl?`<button type="button" class="preview-expand-btn" title="Ampliar imagen">⤢</button>`:''}
      </div>
      <input type="text" value="${escapeHtml(entry.displayName)}" aria-label="Nombre editable de la imagen">
      <div class="pending-file-meta">${Math.max(1,Math.round(entry.file.size/1024))} KB</div>
      ${draftPills?`<div class="draft-link-pills">${draftPills}</div>`:''}
      <div class="pending-file-actions"><button type="button" class="pending-remove">Quitar</button></div>`;

                const checkbox = card.querySelector('.pending-file-check');
                checkbox.addEventListener('click', e => e.stopPropagation());
                checkbox.addEventListener('change', () => togglePendingRefImage(entry.id, checkbox.checked));

                const nameInput = card.querySelector('input[type="text"]');
                nameInput.addEventListener('click', e => e.stopPropagation());
                nameInput.addEventListener('mousedown', e => e.stopPropagation());
                nameInput.addEventListener('input', () => renamePendingRefImage(entry.id, nameInput.value));

                card.querySelector('.pending-remove').addEventListener('click', e => {
                    e.stopPropagation();
                    removePendingRefImage(entry.id);
                });

                const expandBtn = card.querySelector('.preview-expand-btn');
                if (expandBtn) {
                    expandBtn.addEventListener('click', e => {
                        e.stopPropagation();
                        openImageLightbox(entry.previewUrl, entry.displayName || entry.file.name);
                    });
                }

                card.querySelectorAll('.draft-link-pill-remove').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.stopPropagation();
                        removeDraftImageItemLink(btn.dataset.imageId, btn.dataset.itemKey);
                    });
                });

                card.addEventListener('click', e => {
                    if (e.target.closest('input,button')) return;
                    togglePendingRefImage(entry.id);
                });

                card.addEventListener('dragstart', e => {
                    if (e.target.closest('input,button')) {
                        e.preventDefault();
                        return;
                    }
                    setRefImageDragPayload({
                        type: 'image',
                        imageId: entry.id
                    });
                    e.dataTransfer.effectAllowed = 'link';
                    e.dataTransfer.setData('text/plain', `image:${entry.id}`);
                });
                card.addEventListener('dragend', clearRefImageDragPayload);

                card.addEventListener('dragover', e => {
                    if (refImageDragPayload?.type === 'item') {
                        e.preventDefault();
                        card.classList.add('drag-over');
                        e.dataTransfer.dropEffect = 'link';
                    }
                });
                card.addEventListener('dragleave', () => card.classList.remove('drag-over'));
                card.addEventListener('drop', e => {
                    e.preventDefault();
                    card.classList.remove('drag-over');
                    if (refImageDragPayload?.type === 'item') {
                        addDraftImageItemLink(entry.id, refImageDragPayload.itemKey);
                    }
                    clearRefImageDragPayload();
                });

                grid.appendChild(card);
            });
        }

        function renderRefImageItemList() {
            const container = document.getElementById('refImageItemList');
            if (!container) return;

            const previouslySelected = new Set(
                [...container.querySelectorAll('.drag-item-card.selected')].map(el => el.dataset.itemKey)
            );

            container.innerHTML = '';

            currentItemRows().forEach((row, idx) => {
                const key = row.dataset.itemKey;
                const name = (row.querySelector('.item-name')?.value || '').trim();
                const linkedDrafts = getDraftLinkedImagesForItem(key);

                const card = document.createElement('div');
                card.className = 'drag-item-card';
                card.dataset.itemKey = key;
                card.draggable = true;

                const shouldSelect = previouslySelected.has(key) || (previouslySelected.size === 0 && key ===
                    currentRefImageOriginKey);
                if (shouldSelect) card.classList.add('selected');

                card.innerHTML = `
      <input type="checkbox" class="item-select-box" ${shouldSelect?'checked':''} aria-label="Seleccionar ítem">
      <span class="item-drag-handle" title="Arrastrar ítem">⋮⋮</span>
      <div class="item-drag-content">
        <strong>Ítem ${idx+1}</strong>
        <small>${escapeHtml(name||'Sin nombre ingresado')}</small>
        ${linkedDrafts.length?`<div class="draft-link-pills">${buildDraftImagePillsForItem(key, linkedDrafts)}</div>`:''}
      </div>`;

                const checkbox = card.querySelector('.item-select-box');
                checkbox.addEventListener('click', e => e.stopPropagation());
                checkbox.addEventListener('change', () => {
                    card.classList.toggle('selected', checkbox.checked);
                });

                card.addEventListener('click', e => {
                    if (e.target.closest('input')) return;
                    card.classList.toggle('selected');
                    checkbox.checked = card.classList.contains('selected');
                });

                card.querySelectorAll('.draft-link-pill-remove').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.stopPropagation();
                        removeDraftImageItemLink(btn.dataset.imageId, btn.dataset.itemKey);
                    });
                });

                card.addEventListener('dragstart', e => {
                    if (e.target.closest('input')) {
                        e.preventDefault();
                        return;
                    }
                    setRefImageDragPayload({
                        type: 'item',
                        itemKey: key
                    });
                    e.dataTransfer.effectAllowed = 'link';
                    e.dataTransfer.setData('text/plain', `item:${key}`);
                });
                card.addEventListener('dragend', clearRefImageDragPayload);

                card.addEventListener('dragover', e => {
                    if (refImageDragPayload?.type === 'image') {
                        e.preventDefault();
                        card.classList.add('drag-over');
                        e.dataTransfer.dropEffect = 'link';
                    }
                });
                card.addEventListener('dragleave', () => card.classList.remove('drag-over'));
                card.addEventListener('drop', e => {
                    e.preventDefault();
                    card.classList.remove('drag-over');
                    if (refImageDragPayload?.type === 'image') {
                        addDraftImageItemLink(refImageDragPayload.imageId, key);
                    }
                    clearRefImageDragPayload();
                });

                container.appendChild(card);
            });
        }

        function saveReferenceImageLink() {
            const selectedImages = refImagePendingFiles.filter(x => x.selected);
            const selectedItems = [...document.querySelectorAll('#refImageItemList .drag-item-card.selected')]
                .map(x => x.dataset.itemKey);

            // Selección por clic: aplica los ítems elegidos a todas las imágenes seleccionadas.
            if (selectedImages.length && selectedItems.length) {
                selectedImages.forEach(entry => {
                    entry.draftItemKeys = [...new Set([...(entry.draftItemKeys || []), ...selectedItems])];
                });
            }

            const ready = refImagePendingFiles.filter(x => (x.draftItemKeys || []).length > 0);
            if (!ready.length) {
                alert(
                    'Vincule al menos una imagen con un ítem. Puede hacerlo seleccionando imagen(es) e ítem(s), o arrastrando la imagen sobre el ítem —o el ítem sobre la imagen.'
                );
                return;
            }

            ready.forEach(entry => {
                referenceImagesRegistry.push({
                    id: 'refimg-' + Date.now() + '-' + Math.random().toString(36).slice(2, 7),
                    file: entry.file,
                    name: (entry.displayName || entry.file.name).trim() || entry.file.name,
                    type: entry.file.type || 'imagen',
                    itemKeys: [...new Set(entry.draftItemKeys)],
                    previewUrl: entry.previewUrl
                });
            });

            refImagePendingFiles = refImagePendingFiles.filter(x => (x.draftItemKeys || []).length === 0);
            currentRefImageOriginKey = null;

            renderPendingReferenceImages();
            renderReferenceImageSummary();
            renderReferenceImageRegister();
            renderRefImageItemList();
        }

        function renderReferenceImageSummary() {
            const status = document.getElementById('refImagesStatus');
            const list = document.getElementById('refImagesList');
            if (!status || !list) return;
            if (!referenceImagesRegistry.length) {
                status.textContent = 'Sin imágenes vinculadas';
                list.textContent =
                    'Puede cargar un lote de imágenes, seleccionar una o varias miniaturas y vincularlas a uno o más ítems del requerimiento.';
                return;
            }
            const uniqueItemCount = new Set(referenceImagesRegistry.flatMap(img => img.itemKeys)).size;
            status.textContent = `${referenceImagesRegistry.length} imagen(es) vinculada(s)`;
            list.textContent =
                `${referenceImagesRegistry.length} imagen(es) vinculada(s) a ${uniqueItemCount} ítem(s). Revise el detalle y trazabilidad más abajo.`;
        }

        function renameReferenceImage(id) {
            const att = referenceImagesRegistry.find(a => a.id === id);
            if (!att) return;
            const value = prompt('Nuevo nombre para la imagen referencial:', att.name);
            if (value === null) return;
            const clean = value.trim();
            if (clean) att.name = clean;
            renderReferenceImageRegister();
        }

        function renderReferenceImageRegister() {
            const targets = [{
                    wrapId: 'refImagesRegister',
                    emptyId: 'refImagesRegisterEmpty'
                },
                {
                    wrapId: 'refImageModalRegister',
                    emptyId: 'refImageModalRegisterEmpty'
                }
            ];
            targets.forEach(({
                wrapId,
                emptyId
            }) => {
                const wrap = document.getElementById(wrapId);
                const empty = document.getElementById(emptyId);
                if (!wrap || !empty) return;
                wrap.innerHTML = '';
                empty.classList.toggle('hidden', referenceImagesRegistry.length > 0);

                referenceImagesRegistry.forEach(att => {
                    const card = document.createElement('div');
                    card.className = 'attachment-card';
                    card.dataset.refImageId = att.id;

                    const itemNums = att.itemKeys
                        .map(k => visibleItemNumberForKey(k))
                        .filter(n => n !== null)
                        .sort((a, b) => a - b);

                    card.innerHTML = `
        <div class="attachment-card-top">
          <div class="attachment-card-main">
            <div class="attachment-thumb" style="position:relative;">
              ${att.previewUrl?`<img src="${att.previewUrl}" alt="">`:'🖼️'}
              ${att.previewUrl?`<button type="button" class="preview-expand-btn" title="Ampliar imagen" data-preview-id="${att.id}">⤢</button>`:''}
            </div>
            <div>
              <div class="attachment-card-title">${escapeHtml(att.name)}</div>
              <div class="attachment-links">${itemNums.map(n=>`<span class="link-pill">Ítem ${n}</span>`).join('')}</div>
            </div>
          </div>
          <div class="attachment-card-actions">
            <button type="button" class="attachment-rename">Renombrar</button>
            <button type="button" class="attachment-remove" title="Eliminar imagen referencial">Eliminar</button>
          </div>
        </div>`;

                    card.querySelector('.attachment-rename').addEventListener('click', () => {
                        renameReferenceImage(att.id);
                    });

                    card.querySelector('.attachment-remove').addEventListener('click', () => {
                        removeReferenceImage(att.id);
                    });

                    const expandBtn = card.querySelector('.preview-expand-btn');
                    if (expandBtn) {
                        expandBtn.addEventListener('click', () => {
                            openImageLightbox(att.previewUrl, att.name);
                        });
                    }

                    wrap.appendChild(card);
                });
            });
        }

        function openImageLightbox(src, name = 'Imagen') {
            if (!src) return;
            const viewer = document.getElementById('imageLightbox');
            const img = document.getElementById('imageLightboxImg');
            const label = document.getElementById('imageLightboxName');
            if (!viewer || !img || !label) return;
            img.src = src;
            label.textContent = name || 'Imagen';
            viewer.classList.remove('hidden');
        }

        function closeImageLightbox(event) {
            if (event && event.target !== event.currentTarget) return;
            const viewer = document.getElementById('imageLightbox');
            const img = document.getElementById('imageLightboxImg');
            if (viewer) viewer.classList.add('hidden');
            if (img) img.removeAttribute('src');
        }

        function removeReferenceImage(id) {
            const idx = referenceImagesRegistry.findIndex(a => a.id === id);
            if (idx < 0) return;

            const removed = referenceImagesRegistry[idx];
            referenceImagesRegistry.splice(idx, 1);

            if (removed.previewUrl) {
                try {
                    URL.revokeObjectURL(removed.previewUrl);
                } catch (e) {}
            }

            // Limpia cualquier selección transitoria y actualiza inmediatamente
            // tanto el modal como el registro visible del formulario.
            document.querySelectorAll('#refImageItemList .item-select-chip.selected').forEach(el => el.classList.remove(
                'selected'));
            currentRefImageOriginKey = null;
            renderReferenceImageSummary();
            renderReferenceImageRegister();
            renderRefImageItemList();
        }

        let tableStructureLocked = false;
        let activeColResize = null;
        let activeRowResize = null;
        let tableStructureEventsBound = false;

        function toggleTableStructureLock(forceState = null) {
            tableStructureLocked = forceState === null ? !tableStructureLocked : !!forceState;
            const btn = document.getElementById('tableStructureLockBtn');
            if (btn) {
                btn.classList.toggle('is-locked', tableStructureLocked);
                const icon = btn.querySelector('.icon');
                const text = btn.querySelector('.text');
                if (icon) icon.textContent = tableStructureLocked ? '🔒' : '🔓';
                if (text) text.textContent = tableStructureLocked ? 'Tabla bloqueada' : 'Tabla editable';
                btn.title = tableStructureLocked ?
                    'La estructura está bloqueada: no se pueden agregar, eliminar ni redimensionar filas o columnas.' :
                    'La estructura está editable: puede agregar, eliminar y redimensionar filas o columnas.';
            }
            clearResizeHoverStates();
            stopTableResize();
        }

        function clearResizeHoverStates() {
            document.querySelectorAll('#itemsTable th, #itemsTable td').forEach(cell => cell.classList.remove(
                'col-resize-hover'));
            document.querySelectorAll('#itemsTable tbody tr').forEach(tr => tr.classList.remove('row-resize-hover'));
            const table = document.getElementById('itemsTable');
            if (table) table.style.cursor = '';
        }

        function getColumnResizeTarget(event) {
            if (tableStructureLocked) return null;
            const cell = event.target.closest('#itemsTable th, #itemsTable td');
            if (!cell) return null;
            const rect = cell.getBoundingClientRect();
            const distanceToRight = rect.right - event.clientX;
            if (distanceToRight < 0 || distanceToRight > 8) return null;
            const colIndex = cell.cellIndex;
            // No se usa el borde derecho de la última columna, porque no separa dos columnas.
            const table = document.getElementById('itemsTable');
            const totalCols = table.querySelectorAll('thead th').length;
            if (colIndex >= totalCols - 1) return null;
            return {
                cell,
                colIndex
            };
        }

        function getRowResizeTarget(event) {
            if (tableStructureLocked) return null;
            const td = event.target.closest('#itemsTable tbody td');
            if (!td) return null;
            const row = td.parentElement;
            const rect = td.getBoundingClientRect();
            return (rect.bottom - event.clientY <= 7) ? row : null;
        }

        function applyColumnWidth(colIndex, px) {
            const table = document.getElementById('itemsTable');
            const header = table.querySelector('thead tr');
            if (header && header.children[colIndex]) {
                const th = header.children[colIndex];
                th.style.width = px + 'px';
                th.style.minWidth = px + 'px';
                th.style.maxWidth = px + 'px';
            }
            table.querySelectorAll('tbody tr').forEach(row => {
                const cell = row.children[colIndex];
                if (cell) {
                    cell.style.width = px + 'px';
                    cell.style.minWidth = px + 'px';
                    cell.style.maxWidth = px + 'px';
                }
            });
        }

        function setColumnPairWidth(colIndex, leftPx, rightPx) {
            applyColumnWidth(colIndex, leftPx);
            applyColumnWidth(colIndex + 1, rightPx);
        }

        function setRowHeight(row, px) {
            const height = Math.max(74, px);
            row.style.height = height + 'px';
            row.querySelectorAll('td').forEach(td => {
                td.style.height = height + 'px';
            });
            row.querySelectorAll('textarea').forEach(ta => {
                ta.style.height = Math.max(58, height - 28) + 'px';
            });
        }

        function startColumnResize(event, target) {
            if (tableStructureLocked) return;
            event.preventDefault();
            const {
                cell,
                colIndex
            } = target;
            const table = document.getElementById('itemsTable');
            const header = table.querySelector('thead tr');
            const leftCell = header.children[colIndex];
            const rightCell = header.children[colIndex + 1];
            if (!leftCell || !rightCell) return;
            const leftWidth = leftCell.getBoundingClientRect().width;
            const rightWidth = rightCell.getBoundingClientRect().width;
            activeColResize = {
                startX: event.clientX,
                leftStart: leftWidth,
                rightStart: rightWidth,
                pairTotal: leftWidth + rightWidth,
                colIndex
            };
            document.body.classList.add('table-resizing');
        }

        function startRowResize(event, row) {
            if (tableStructureLocked) return;
            event.preventDefault();
            activeRowResize = {
                startY: event.clientY,
                startHeight: row.getBoundingClientRect().height,
                row
            };
            document.body.classList.add('table-resizing');
        }

        function onTableMouseMove(event) {
            if (activeColResize) {
                const delta = event.clientX - activeColResize.startX;
                const leftMin = activeColResize.colIndex === 0 ? 34 : 60;
                const rightMin = (activeColResize.colIndex + 1) === 0 ? 34 : 60;
                let left = activeColResize.leftStart + delta;
                let right = activeColResize.pairTotal - left;
                if (left < leftMin) {
                    left = leftMin;
                    right = activeColResize.pairTotal - left;
                }
                if (right < rightMin) {
                    right = rightMin;
                    left = activeColResize.pairTotal - right;
                }
                setColumnPairWidth(activeColResize.colIndex, left, right);
                return;
            }
            if (activeRowResize) {
                const newHeight = activeRowResize.startHeight + (event.clientY - activeRowResize.startY);
                setRowHeight(activeRowResize.row, newHeight);
                return;
            }
            clearResizeHoverStates();
            if (tableStructureLocked) return;

            const colTarget = getColumnResizeTarget(event);
            if (colTarget) {
                colTarget.cell.classList.add('col-resize-hover');
                document.getElementById('itemsTable').style.cursor = 'col-resize';
                return;
            }
            const rowTarget = getRowResizeTarget(event);
            if (rowTarget) {
                rowTarget.classList.add('row-resize-hover');
                document.getElementById('itemsTable').style.cursor = 'row-resize';
                return;
            }
        }

        function onTableMouseDown(event) {
            if (tableStructureLocked) return;
            const colTarget = getColumnResizeTarget(event);
            if (colTarget) {
                startColumnResize(event, colTarget);
                return;
            }
            const rowTarget = getRowResizeTarget(event);
            if (rowTarget) {
                startRowResize(event, rowTarget);
            }
        }

        function stopTableResize() {
            activeColResize = null;
            activeRowResize = null;
            document.body.classList.remove('table-resizing');
        }

        function normalizeTableToContainer() {
            const table = document.getElementById('itemsTable');
            if (!table) return;
            // Limpia anchos absolutos aplicados previamente para volver al ancho A4/predefinido.
            table.querySelectorAll('thead th, tbody td').forEach(cell => {
                cell.style.minWidth = '';
                cell.style.maxWidth = '';
            });
        }

        function initTableStructureTools() {
            if (tableStructureEventsBound) return;
            const table = document.getElementById('itemsTable');
            if (!table) return;
            table.addEventListener('mousemove', onTableMouseMove);
            table.addEventListener('mousedown', onTableMouseDown);
            table.addEventListener('mouseleave', () => {
                if (!activeColResize && !activeRowResize) clearResizeHoverStates();
            });
            document.addEventListener('mouseup', stopTableResize);
            tableStructureEventsBound = true;
        }

        function applyFieldHelp() {
            const helpMap = {
                "área / unidad requirente": "Unidad interna que formula la necesidad de compra o contratación.",
                "responsable del requerimiento": "Persona que prepara la solicitud y puede aclarar sus antecedentes.",
                "correo / teléfono": "Dato de contacto interno del responsable del requerimiento.",
                "fecha de solicitud": "Fecha en que se ingresa o formaliza la solicitud en el formulario.",
                "fecha en que se requiere": "Fecha límite o fecha esperada para contar con el bien o servicio.",
                "prioridad": "Nivel de urgencia de la necesidad. No determina por sí sola el mecanismo de compra.",
                "jefatura que autoriza el requerimiento": "Nombre y cargo de la jefatura que autoriza la necesidad del área.",
                "tipo de requerimiento": "Seleccione si la necesidad corresponde a compra de bienes, contratación de servicios, arrendamiento o un requerimiento mixto.",
                "nombre breve / objeto de la compra (debe sintetizar de forma clara la necesidad de compra)": "Síntesis breve y clara del requerimiento. Debe permitir identificar rápidamente qué se necesita contratar o adquirir.",
                "necesidad que se busca satisfacer y justificación": "Explique el problema o necesidad institucional y por qué la compra es necesaria.",
                "resultado esperado / finalidad institucional": "Resultado concreto o beneficio institucional que se espera obtener con la compra.",
                "n° beneficiarios / usuarios": "Cantidad estimada de personas, usuarios o unidades que utilizarán o se beneficiarán con la compra.",
                "frecuencia de uso": "Periodicidad con que se utilizará el bien o servicio requerido.",
                "otra frecuencia (especifique)": "Si la lista no representa adecuadamente la frecuencia, indíquela en forma libre.",
                "lugar de entrega o prestación": "Lugar en que deben entregarse los bienes o ejecutarse los servicios.",
                "otro lugar de entrega o prestación": "Ingrese una dirección o dependencia distinta cuando la ejecución no sea en el Parque.",
                "contraparte técnica propuesta": "Persona o unidad que supervisará técnicamente la compra y su correcta ejecución.",
                "¿la compra está identificada en el pac?": "Indique si la necesidad se encuentra contemplada en el Plan Anual de Compras.",
                "id / código pac": "Código identificador de la compra dentro del sistema PAC del PRZ.",
                "mes planificado": "Mes en que la compra se encontraba programada dentro del PAC.",
                "monto pac estimado": "Monto estimado con el que fue considerada la compra en el PAC.",
                "¿existe stock o bien institucional que cubra la necesidad?": "Permite verificar si la necesidad ya puede satisfacerse con inventario o bienes existentes.",
                "¿existe contrato, convenio u oc vigente que la cubra?": "Permite verificar si ya existe un instrumento vigente que satisfaga la necesidad.",
                "¿hay requerimientos similares de otras áreas o dentro de la misma?": "Busca detectar necesidades repetidas para evaluar agrupación de demanda o prevenir fragmentación.",
                "antecedentes de compras relacionadas / posibilidad de agrupar demanda": "Registre compras, órdenes, contratos o solicitudes relacionadas que ayuden a evaluar agrupación o fragmentación.",
                "condiciones o requisitos generales complementarios": "Espacio para registrar requisitos comunes a todo el lote o conjunto de ítems, cuando no corresponden a un ítem individual.",
                "imágenes referenciales": "Adjunte imágenes de referencia que ayuden a identificar mejor el requerimiento.",
                "fecha requerida de entrega / inicio": "Fecha concreta en que el área necesita contar con el bien o que comience la prestación. Puede seleccionarse desde el calendario.",
                "plazo máximo para entrega / inicio": "Período máximo otorgado al proveedor para entregar o iniciar, contado desde el hito que corresponda del procedimiento.",
                "duración total": "Tiempo total de duración del contrato, servicio, arriendo o actividad una vez iniciada su ejecución.",
                "horario / ventanas de ejecución": "Días, horarios o ventanas específicas en que debe ejecutarse la prestación o realizarse la entrega.",
                "criterios de recepción conforme": "Criterios que servirán para verificar objetivamente que la entrega o servicio fue recibido correctamente.",
                "otros criterios u observaciones de recepción": "Espacio para detallar criterios adicionales o precisiones de la recepción.",
                "garantía, soporte, instalación, capacitación o postventa": "Prestaciones adicionales posteriores o asociadas a la entrega que resultan necesarias para el correcto uso del bien o servicio.",
                "antecedentes operativos complementarios": "Observaciones logísticas, coordinaciones, restricciones u otros antecedentes relevantes para la ejecución.",
                "período de arriendo": "Tiempo durante el cual se requiere el arriendo.",
                "nº unidades": "Cantidad de unidades a arrendar.",
                "servicios incluidos": "Prestaciones incorporadas en el arriendo, como transporte, mantención o seguros.",
                "condiciones de entrega y devolución": "Condiciones con que se recibirá y devolverá el bien arrendado.",
                "justificación compra vs. arriendo / costo de ciclo": "Explique por qué, para esta necesidad, el arriendo resulta más conveniente o adecuado que la compra."
            };

            document.querySelectorAll('label, th').forEach(el => {
                const raw = (el.textContent || '').replace(/\s+/g, ' ').trim().toLowerCase();
                if (helpMap[raw] && !el.getAttribute('title')) {
                    el.setAttribute('title', helpMap[raw]);
                }
            });
        }

        const emergencyTypeDefinitions = {
            emergencia: {
                title: 'Emergencia',
                text: 'Situación grave o crítica que exige una respuesta inmediata para evitar, contener o reducir una afectación relevante a personas, bienes, infraestructura, funcionamiento institucional o continuidad operacional, y que no puede ser atendida oportunamente mediante la planificación o tramitación ordinaria.'
            },
            urgencia: {
                title: 'Urgencia',
                text: 'Necesidad real, concreta y actual que requiere una respuesta en un plazo sustancialmente menor al ordinario, porque la demora produciría una afectación relevante al funcionamiento, continuidad o cumplimiento institucional. La sola conveniencia, presión de tiempo o falta de planificación no configuran por sí mismas una urgencia.'
            },
            imprevisto: {
                title: 'Imprevisto',
                text: 'Hecho o circunstancia sobreviniente que no era razonablemente previsible mediante una planificación y diligencia ordinarias y que genera una necesidad que, por su oportunidad, no puede ser atendida adecuadamente a través de la planificación o del procedimiento ordinario.'
            }
        };
        let emergencyDefinitionHideTimer = null;

        function toggleEmergencyTypeDropdown() {
            const combo = document.getElementById('emergencyTypeCombo');
            const dropdown = document.getElementById('emergencyTypeDropdown');
            const button = document.getElementById('emergencyTypeComboButton');
            if (!combo || !dropdown || !button) return;

            const opening = dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden', !opening);
            combo.classList.toggle('open', opening);
            button.setAttribute('aria-expanded', opening ? 'true' : 'false');

            if (opening) {
                hideEmergencyTypeDefinition();
            } else {
                hideEmergencyTypeDefinition();
            }
        }

        function showEmergencyTypeDefinition(key, optionEl) {
            if (emergencyDefinitionHideTimer) {
                clearTimeout(emergencyDefinitionHideTimer);
                emergencyDefinitionHideTimer = null;
            }

            const info = emergencyTypeDefinitions[key];
            const pop = document.getElementById('emergencyTypeDefinition');
            const title = document.getElementById('emergencyTypeDefinitionTitle');
            const text = document.getElementById('emergencyTypeDefinitionText');
            const dropdown = document.getElementById('emergencyTypeDropdown');
            const combo = document.getElementById('emergencyTypeCombo');
            if (!info || !pop || !title || !text || !dropdown || !combo) return;

            title.textContent = info.title;
            text.textContent = info.text;

            if (optionEl) {
                const dropdownTop = dropdown.offsetTop || 0;
                const optionTop = optionEl.offsetTop || 0;
                const optionHeight = optionEl.offsetHeight || 44;
                const pointerCenter = 24;
                const optionCenter = dropdownTop + optionTop + (optionHeight / 2);
                const relativeTop = Math.max(0, optionCenter - pointerCenter);
                pop.style.top = `${relativeTop}px`;
            }

            pop.classList.remove('hidden');
        }

        function scheduleHideEmergencyTypeDefinition() {
            if (emergencyDefinitionHideTimer) clearTimeout(emergencyDefinitionHideTimer);
            emergencyDefinitionHideTimer = setTimeout(hideEmergencyTypeDefinition, 90);
        }

        function hideEmergencyTypeDefinition() {
            const pop = document.getElementById('emergencyTypeDefinition');
            if (pop) pop.classList.add('hidden');
        }

        function selectEmergencyType(value) {
            const select = document.getElementById('emergencyQualificationType');
            const label = document.getElementById('emergencyTypeComboLabel');
            const combo = document.getElementById('emergencyTypeCombo');
            const dropdown = document.getElementById('emergencyTypeDropdown');
            const button = document.getElementById('emergencyTypeComboButton');

            if (select) {
                select.value = value;
                select.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }
            if (label) label.textContent = value || 'Seleccione';
            if (dropdown) dropdown.classList.add('hidden');
            if (combo) combo.classList.remove('open');
            if (button) button.setAttribute('aria-expanded', 'false');
            hideEmergencyTypeDefinition();
        }

        function resetEmergencyTypeCombo() {
            const select = document.getElementById('emergencyQualificationType');
            const label = document.getElementById('emergencyTypeComboLabel');
            const dropdown = document.getElementById('emergencyTypeDropdown');
            const combo = document.getElementById('emergencyTypeCombo');
            if (select) select.value = '';
            if (label) label.textContent = 'Seleccione';
            if (dropdown) dropdown.classList.add('hidden');
            if (combo) combo.classList.remove('open');
            hideEmergencyTypeDefinition();
        }

        const euiFieldHelp = {
            tipo: {
                title: 'Tipo de calificación solicitada',
                text: 'Este campo identifica la categoría bajo la cual se pide evaluar la situación. No define por sí solo la procedencia de la calificación: la decisión debe fundarse en los hechos y antecedentes aportados.',
                example: 'Revise las definiciones de Emergencia, Urgencia e Imprevisto en el desplegable antes de escoger.'
            },
            fecha: {
                title: 'Fecha del hecho o detección',
                text: 'Corresponde a la fecha en que ocurrió la situación que origina la necesidad o, si no se conoce con precisión, a la fecha en que fue detectada formalmente. Sirve para establecer la secuencia temporal y evaluar la oportunidad de la respuesta.',
                example: 'Ejemplo: una falla pudo haberse producido durante la noche y ser detectada por el equipo al iniciar la jornada.'
            },
            hora: {
                title: 'Hora del hecho o detección',
                text: 'Permite precisar cuándo ocurrió o cuándo se tomó conocimiento de la situación. Complete este dato solo si es conocido o resulta relevante para entender la urgencia y la cronología.',
                example: 'Si no se conoce la hora exacta, no invente una: utilice la hora de detección o deje constancia de que es aproximada.'
            },
            lugar: {
                title: 'Lugar o dependencia afectada',
                text: 'Identifica el punto físico, instalación, sistema o dependencia donde ocurrió o produce efectos la situación. Debe permitir a quien evalúa ubicar claramente el problema.',
                example: 'Ejemplo: acceso principal del Parque, Museo, sendero específico, bodega, oficina, red eléctrica o sistema informático.'
            },
            narracion: {
                title: 'Narración circunstanciada de los hechos',
                text: 'Es el relato objetivo y cronológico de lo sucedido. Debe explicar qué ocurrió, cómo se detectó, cuándo, dónde, quiénes intervinieron inicialmente, qué acciones se realizaron y cuál es la situación actual. Evite conclusiones genéricas sin hechos verificables.',
                example: 'La narración debe permitir que una persona que no estuvo presente comprenda la situación completa sin depender de explicaciones verbales posteriores.'
            },
            fundamentos: {
                title: 'Fundamentos de la solicitud de calificación',
                text: 'Aquí debe explicarse por qué los hechos descritos justificarían evaluar el caso como emergencia, urgencia o imprevisto. El foco está en la relación entre los hechos, la necesidad de actuar y la imposibilidad o insuficiencia de esperar la tramitación ordinaria.',
                example: 'No basta señalar “es urgente”. Explique qué circunstancia concreta hace necesaria una respuesta extraordinaria y por qué.'
            },
            continuidad: {
                title: 'Servicio, función o continuidad afectada',
                text: 'Este campo identifica qué actividad institucional se encuentra afectada o amenazada y la magnitud de esa afectación. Puede tratarse de atención a público, seguridad, operación del recinto, conservación, administración, funcionamiento de infraestructura u otra función del Parque.',
                example: 'Indique qué deja de funcionar, qué funciona parcialmente o qué riesgo de interrupción existe y a quiénes afecta.'
            },
            consecuencias: {
                title: 'Consecuencias de no actuar oportunamente',
                text: 'Debe describir qué ocurriría si la institución espera y no adopta una solución dentro del plazo requerido. La consecuencia debe ser concreta y verificable, no una afirmación abstracta.',
                example: 'Puede incluir riesgo para personas, cierre de instalaciones, interrupción del servicio, agravamiento del daño, pérdida patrimonial o incumplimiento de obligaciones.'
            },
            medidas: {
                title: 'Bienes, servicios o medidas requeridas',
                text: 'Corresponde a la solución que se estima necesaria para enfrentar la situación. Indique qué debe adquirirse, contratarse, repararse, reponerse o ejecutar, sin confundir este campo con la descripción del problema.',
                example: 'Ejemplo: contratar reparación eléctrica de emergencia, adquirir una bomba de reemplazo o contratar retiro inmediato de material peligroso.'
            },
            plazo: {
                title: 'Plazo máximo de atención',
                text: 'Indica el tiempo máximo dentro del cual la solución debe comenzar o quedar ejecutada para evitar o contener las consecuencias descritas. Debe relacionarse con la urgencia real del caso.',
                example: 'Ejemplo: inicio dentro de 4 horas, reparación antes de 24 horas o solución operativa dentro de 48 horas.'
            },
            mitigacion: {
                title: 'Medida de mitigación temporal',
                text: 'Busca saber si existe una alternativa provisional que permita mantener total o parcialmente la operación mientras se tramita una solución definitiva. Su existencia puede ser relevante para evaluar la necesidad de una respuesta extraordinaria.',
                example: 'Ejemplo: habilitar un acceso alternativo, utilizar un equipo de respaldo o restringir temporalmente una zona.'
            },
            monto: {
                title: 'Monto aproximado de la solución requerida',
                text: 'Este campo se refiere al costo estimado de adquirir o contratar la solución necesaria para atender la situación. No corresponde al valor del daño, pérdida o afectación ocasionada, salvo que ese antecedente se informe separadamente como parte de los fundamentos.',
                example: 'Ejemplo: si reparar una bomba cuesta aproximadamente $1.500.000, ese es el monto que debe informarse aquí, no el valor de los daños provocados por la falla.'
            },
            provisorias: {
                title: 'Medidas provisorias adoptadas',
                text: 'Registra las acciones inmediatas que ya fueron ejecutadas para contener el problema, reducir riesgos o mantener la operación antes de solicitar la calificación.',
                example: 'Ejemplo: cierre preventivo de un sector, aislamiento del equipo, traslado temporal de actividades o contratación de una medida de seguridad provisoria.'
            },
            responsable: {
                title: 'Responsable o contraparte que informa',
                text: 'Corresponde a la persona que conoce técnicamente los hechos y puede aclarar o respaldar la información de la solicitud. No necesariamente es quien autoriza la compra ni quien decide la calificación.',
                example: 'Indique nombre, cargo, área y contacto de la persona que puede explicar la situación y responder consultas técnicas del evaluador.'
            },
            observaciones: {
                title: 'Observaciones complementarias',
                text: 'Utilice este campo para antecedentes relevantes que no tengan un espacio específico y que ayuden a evaluar la situación, su contexto o sus restricciones.',
                example: 'Puede señalar visitas técnicas, instrucciones previas, restricciones de acceso, coordinación con terceros, antecedentes históricos u otros elementos pertinentes.'
            },
            antecedentes: {
                title: 'Antecedentes de respaldo para evaluación',
                text: 'Aquí deben adjuntarse los documentos o evidencias que permitan corroborar los hechos, fundamentos y necesidad de la solución solicitada. El objetivo es que la evaluación no dependa únicamente del relato escrito.',
                example: 'Pueden incluir fotografías, informes, cotizaciones, correos, actas, certificados, fichas técnicas, reportes, planos u otros archivos relevantes.'
            }
        };

        let euiHelpPinned = false;
        let euiHelpCurrentLabel = null;
        let euiHelpHideTimer = null;

        function positionEuiHelpPopover(label) {
            const pop = document.getElementById('euiHelpPopover');
            if (!pop || !label) return;

            pop.classList.remove('hidden');

            const rect = label.getBoundingClientRect();
            const gap = 8;
            const margin = 12;
            const popRect = pop.getBoundingClientRect();

            let left = rect.left;
            let top = rect.bottom + gap;

            if (left + popRect.width > window.innerWidth - margin) {
                left = Math.max(margin, window.innerWidth - popRect.width - margin);
            }
            if (top + popRect.height > window.innerHeight - margin) {
                top = Math.max(margin, rect.top - popRect.height - gap);
            }

            pop.style.left = `${left}px`;
            pop.style.top = `${top}px`;
        }

        function showEuiFieldHelp(label, pinned = false) {
            if (pinned && typeof formAnalytics !== 'undefined') formAnalytics.helpInteractions++;
            const key = label?.dataset?.euiHelp;
            const info = euiFieldHelp[key];
            const pop = document.getElementById('euiHelpPopover');
            const title = document.getElementById('euiHelpPopoverTitle');
            const text = document.getElementById('euiHelpPopoverText');
            const example = document.getElementById('euiHelpPopoverExample');
            if (!info || !pop || !title || !text || !example) return;

            if (euiHelpHideTimer) {
                clearTimeout(euiHelpHideTimer);
                euiHelpHideTimer = null;
            }

            title.textContent = info.title;
            text.textContent = info.text;
            if (info.example) {
                example.textContent = info.example;
                example.classList.remove('hidden');
            } else {
                example.textContent = '';
                example.classList.add('hidden');
            }

            euiHelpPinned = pinned;
            euiHelpCurrentLabel = label;
            positionEuiHelpPopover(label);
        }

        function hideEuiFieldHelp(force = false) {
            if (euiHelpPinned && !force) return;
            const pop = document.getElementById('euiHelpPopover');
            if (pop) pop.classList.add('hidden');
            if (force) {
                euiHelpPinned = false;
                euiHelpCurrentLabel = null;
            }
        }

        function scheduleHideEuiFieldHelp() {
            if (euiHelpPinned) return;
            if (euiHelpHideTimer) clearTimeout(euiHelpHideTimer);
            euiHelpHideTimer = setTimeout(() => hideEuiFieldHelp(false), 100);
        }

        function initEuiFieldHelp() {
            document.querySelectorAll('#emergencyRequestPanel .eui-help-label').forEach(label => {
                label.addEventListener('mouseenter', () => showEuiFieldHelp(label, false));
                label.addEventListener('mouseleave', scheduleHideEuiFieldHelp);
                label.addEventListener('focus', () => showEuiFieldHelp(label, false));
                label.addEventListener('blur', scheduleHideEuiFieldHelp);
                label.addEventListener('click', event => {
                    event.preventDefault();
                    event.stopPropagation();
                    if (euiHelpPinned && euiHelpCurrentLabel === label) {
                        hideEuiFieldHelp(true);
                    } else {
                        showEuiFieldHelp(label, true);
                    }
                });
            });

            window.addEventListener('resize', () => {
                if (euiHelpCurrentLabel && !document.getElementById('euiHelpPopover')?.classList.contains(
                        'hidden')) {
                    positionEuiHelpPopover(euiHelpCurrentLabel);
                }
            });

            window.addEventListener('scroll', () => {
                if (euiHelpCurrentLabel && !document.getElementById('euiHelpPopover')?.classList.contains(
                        'hidden')) {
                    positionEuiHelpPopover(euiHelpCurrentLabel);
                }
            }, true);
        }

        /* ===========================
           SOLICITUD DE CALIFICACIÓN E/U/I EN SECCIÓN 1
           =========================== */
        function toggleEmergencyNeed() {
            const selected = document.querySelector('input[name="emergencyNeedFlag"]:checked')?.value;
            const optionBox = document.getElementById('emergencyRequestOptionBox');
            const panel = document.getElementById('emergencyRequestPanel');
            const btn = document.getElementById('emergencyRequestToggleBtn');
            const status = document.getElementById('emergencyRequestStatus');

            if (!optionBox || !panel || !btn || !status) return;

            if (selected === 'si') {
                optionBox.classList.remove('hidden');
                btn.classList.add('emergency-action-highlight');
                btn.textContent = panel.classList.contains('hidden') ? 'Incorporar solicitud de calificación E/U/I' :
                    'Ocultar solicitud de calificación E/U/I';
                status.textContent = panel.classList.contains('hidden') ? 'Pendiente de incorporar' :
                    'Solicitud incorporada';
            } else {
                optionBox.classList.add('hidden');
                panel.classList.add('hidden');
                btn.classList.remove('emergency-action-highlight');
                btn.textContent = 'Incorporar solicitud de calificación E/U/I';
                status.textContent = selected === 'no' ? 'No requerida' : 'No incorporada';
                hideEuiFieldHelp(true);
            }
        }

        function toggleEmergencyRequestPanel(forceState) {
            const panel = document.getElementById('emergencyRequestPanel');
            const btn = document.getElementById('emergencyRequestToggleBtn');
            const status = document.getElementById('emergencyRequestStatus');
            const needSelected = document.querySelector('input[name="emergencyNeedFlag"]:checked')?.value;
            if (!panel || !btn || !status) return;
            if (needSelected !== 'si') return;
            const show = (typeof forceState === 'boolean') ? forceState : panel.classList.contains('hidden');
            panel.classList.toggle('hidden', !show);
            btn.textContent = show ? 'Ocultar solicitud de calificación E/U/I' :
                'Incorporar solicitud de calificación E/U/I';
            status.textContent = show ? 'Solicitud incorporada' : 'No incorporada';
        }

        /* ===========================
           GESTOR DE ANTECEDENTES E/U/I
           =========================== */
        let emergencyEvidencePendingFiles = [];
        const emergencyEvidenceRegistry = [];
        let emergencyEvidenceDragPayload = null;
        const emergencyEvidenceCategories = [{
                key: 'narracion',
                title: 'Narración circunstanciada de los hechos',
                hint: 'Archivos que documenten cronológicamente lo ocurrido: relato formal, minuta, acta, reporte o detalle del hecho.'
            },
            {
                key: 'fundamentos',
                title: 'Fundamentos y justificación',
                hint: 'Respaldo que explique por qué correspondería calificar la situación como emergencia, urgencia o imprevisto.'
            },
            {
                key: 'informe',
                title: 'Informe técnico o inspección',
                hint: 'Informes, diagnósticos, reportes de terreno, revisión técnica, constatación de falla o evaluación especializada.'
            },
            {
                key: 'evidencia',
                title: 'Evidencia visual o documental',
                hint: 'Fotografías, capturas, videos convertidos, documentos o archivos que permitan corroborar visualmente la situación.'
            },
            {
                key: 'mercado',
                title: 'Cotizaciones o antecedente de mercado',
                hint: 'Cotizaciones, catálogos, fichas, consultas o antecedentes de mercado asociados a la solución requerida.'
            },
            {
                key: 'comunicaciones',
                title: 'Comunicaciones, oficios o instrucciones',
                hint: 'Correos, memorandos, instrucciones, comunicaciones internas o externas y oficios relacionados.'
            },
            {
                key: 'otro',
                title: 'Otro antecedente de respaldo',
                hint: 'Cualquier otro archivo útil para la evaluación que no calce en las categorías anteriores.'
            }
        ];

        function openEmergencyEvidenceManager() {

            const modal = document.getElementById('emergencyEvidenceModal');

            if (!modal) return;

            document.body.appendChild(modal);

            // Obtener posición del iframe en la página principal
            const iframe = window.frameElement;
            const iframeRect = iframe.getBoundingClientRect();

            // Centro visible de la pantalla principal
            const parentHeight = window.parent.innerHeight;

            const visibleTop = Math.max(0, iframeRect.top);
            const visibleBottom = Math.min(parentHeight, iframeRect.bottom);

            // Convertir al sistema de coordenadas del iframe
            const centerY = (
                (visibleTop + visibleBottom) / 2
            ) - iframeRect.top;

            // Mostrar modal
            modal.classList.remove('hidden');

            // Ubicar la ventana blanca en el centro visible
            const card = modal.querySelector('.modal-card');

            if (card) {
                card.style.position = 'absolute';
                card.style.top = centerY + 'px';
                card.style.left = '50%';
                card.style.transform = 'translate(-50%, -50%)';
                card.style.margin = '0';
                card.style.maxHeight = (visibleBottom - visibleTop - 40) + 'px';
                card.style.overflowY = 'auto';
            }

            renderEmergencyEvidencePendingGrid();
            renderEmergencyEvidenceCategoryList();
        }

        function closeEmergencyEvidenceManager(event) {
            if (event && event.target !== event.currentTarget) return;
            const modal = document.getElementById('emergencyEvidenceModal');
            if (modal) modal.classList.add('hidden');
            clearEmergencyEvidenceDragPayload();
        }

        function setEmergencyEvidenceDragPayload(payload) {
            emergencyEvidenceDragPayload = payload;
        }

        function clearEmergencyEvidenceDragPayload() {
            emergencyEvidenceDragPayload = null;
            document.querySelectorAll('#emergencyEvidencePendingGrid .drag-over, #emergencyEvidenceCategoryList .drag-over')
                .forEach(el => el.classList.remove('drag-over'));
        }

        function getEmergencyCategoryTitle(key) {
            return emergencyEvidenceCategories.find(x => x.key === key)?.title || key;
        }

        function getDraftLinkedEmergencyFilesForCategory(categoryKey) {
            return emergencyEvidencePendingFiles.filter(x => (x.draftCategoryKeys || []).includes(categoryKey));
        }

        function buildEmergencyCategoryPillsForFile(entry) {
            return (entry.draftCategoryKeys || [])
                .map(key =>
                    `<span class="draft-link-pill">${escapeHtml(getEmergencyCategoryTitle(key))}<button type="button" title="Quitar vínculo" data-file-id="${entry.id}" data-category-key="${key}">×</button></span>`
                )
                .join('');
        }

        function buildEmergencyFilePillsForCategory(categoryKey) {
            return getDraftLinkedEmergencyFilesForCategory(categoryKey)
                .map(entry =>
                    `<span class="draft-link-pill">${escapeHtml(entry.displayName || entry.name || entry.file?.name || 'Archivo')}<button type="button" title="Quitar vínculo" data-file-id="${entry.id}" data-category-key="${categoryKey}">×</button></span>`
                )
                .join('');
        }

        function summarizeEmergencyEvidencePending() {
            const box = document.getElementById('emergencyEvidenceFileName');
            if (!box) return;
            if (!emergencyEvidencePendingFiles.length) {
                box.textContent = 'Ningún archivo cargado.';
                return;
            }
            const totalKB = emergencyEvidencePendingFiles.reduce((acc, x) => acc + Math.max(1, Math.round((x.file?.size ||
                0) / 1024)), 0);
            const selected = emergencyEvidencePendingFiles.filter(x => x.selected).length;
            const linked = emergencyEvidencePendingFiles.reduce((acc, x) => acc + ((x.draftCategoryKeys || []).length), 0);
            box.textContent =
                `${emergencyEvidencePendingFiles.length} archivo(s) cargado(s) · ${selected} seleccionado(s) · ${linked} vínculo(s) preparados · ${totalKB} KB en total.`;
        }

        function handleEmergencyEvidenceFiles(input) {
            const files = [...(input.files || [])];
            if (!files.length) return;
            files.forEach(file => {
                const entry = makePendingFileEntry(file, 'evi');
                entry.selected = false;
                entry.draftCategoryKeys = [];
                emergencyEvidencePendingFiles.push(entry);
            });
            summarizeEmergencyEvidencePending();
            renderEmergencyEvidencePendingGrid();
            renderEmergencyEvidenceCategoryList();
            input.value = '';
        }

        function toggleEmergencyEvidenceSelection(fileId, checked) {
            const entry = emergencyEvidencePendingFiles.find(x => x.id === fileId);
            if (!entry) return;
            entry.selected = !!checked;
            renderEmergencyEvidencePendingGrid();
        }

        function renamePendingEmergencyEvidence(fileId, value) {
            const entry = emergencyEvidencePendingFiles.find(x => x.id === fileId);
            if (!entry) return;
            entry.displayName = (value || '').trim() || entry.file?.name || 'archivo';
            renderEmergencyEvidenceCategoryList();
        }

        function removePendingEmergencyEvidence(fileId) {
            const idx = emergencyEvidencePendingFiles.findIndex(x => x.id === fileId);
            if (idx === -1) return;
            const entry = emergencyEvidencePendingFiles[idx];
            if ((entry.draftCategoryKeys || []).length || entry.selected) {
                const ok = confirm('Este archivo tiene selección o vínculos preparados. ¿Desea quitarlo?');
                if (!ok) return;
            }
            if (entry.objectUrl) URL.revokeObjectURL(entry.objectUrl);
            emergencyEvidencePendingFiles.splice(idx, 1);
            summarizeEmergencyEvidencePending();
            renderEmergencyEvidencePendingGrid();
            renderEmergencyEvidenceCategoryList();
        }

        function linkEmergencyFileToCategory(fileId, categoryKey) {
            const entry = emergencyEvidencePendingFiles.find(x => x.id === fileId);
            if (!entry) return;
            if (!entry.draftCategoryKeys) entry.draftCategoryKeys = [];
            if (!entry.draftCategoryKeys.includes(categoryKey)) entry.draftCategoryKeys.push(categoryKey);
        }

        function unlinkEmergencyFileFromCategory(fileId, categoryKey) {
            const entry = emergencyEvidencePendingFiles.find(x => x.id === fileId);
            if (!entry?.draftCategoryKeys) return;
            entry.draftCategoryKeys = entry.draftCategoryKeys.filter(x => x !== categoryKey);
        }

        function toggleEmergencyCategoryChecked(categoryKey, checked) {
            const card = document.querySelector(
                `#emergencyEvidenceCategoryList .drag-item-card[data-category-key="${categoryKey}"]`);
            if (card) {
                card.classList.toggle('selected', !!checked);
            }
        }

        function applySelectedEmergencyLinks() {
            const selectedFiles = emergencyEvidencePendingFiles.filter(x => x.selected);
            const selectedCats = [...document.querySelectorAll('#emergencyEvidenceCategoryList .drag-item-check:checked')]
                .map(x => x.dataset.categoryKey);
            if (!selectedFiles.length || !selectedCats.length) return false;
            selectedFiles.forEach(file => selectedCats.forEach(cat => linkEmergencyFileToCategory(file.id, cat)));
            return true;
        }

        function renderEmergencyEvidencePendingGrid() {
            const grid = document.getElementById('emergencyEvidencePendingGrid');
            if (!grid) return;
            grid.innerHTML = '';
            emergencyEvidencePendingFiles.forEach(entry => {
                const needsContentPreview = fileIsWord(entry.file) || fileIsSpreadsheet(entry.file) ||
                    fileIsBrowserTextPreviewable(entry.file);
                const draftPills = buildEmergencyCategoryPillsForFile(entry);

                let previewHtml = '';
                if (entry.previewUrl) {
                    previewHtml =
                        `<div class="pending-file-preview"><img src="${entry.previewUrl}" alt=""><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>`;
                } else if (fileIsPdf(entry.file)) {
                    previewHtml =
                        `<div class="pending-file-preview"><embed src="${entry.objectUrl}#page=1&toolbar=0&navpanes=0&scrollbar=0" type="application/pdf"><button type="button" class="preview-expand-btn" title="Vista previa del PDF">⤢</button></div>`;
                } else if (needsContentPreview) {
                    previewHtml =
                        `<div class="pending-file-preview"><div class="document-mini-preview"><div class="loading-doc">Generando vista previa…</div></div><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>`;
                } else {
                    previewHtml =
                        `<div class="pending-file-preview"><span class="pending-file-icon">${fileKindIcon(entry.file)}</span><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>`;
                }

                const card = document.createElement('div');
                card.className = `pending-file-card${entry.selected?' selected':''}`;
                card.dataset.fileId = entry.id;
                card.draggable = true;
                card.innerHTML = `
      <input class="pending-file-check" type="checkbox" ${entry.selected?'checked':''} aria-label="Seleccionar archivo">
      ${previewHtml}
      <input type="text" value="${escapeHtml(entry.displayName)}" aria-label="Nombre editable del archivo">
      <div class="pending-file-meta">${Math.max(1,Math.round((entry.file?.size||0)/1024))} KB</div>
      ${draftPills?`<div class="draft-link-pills">${draftPills}</div>`:''}
      <button type="button" class="pending-remove">Quitar</button>`;

                card.addEventListener('click', e => {
                    if (e.target.closest('input,button')) return;
                    entry.selected = !entry.selected;
                    renderEmergencyEvidencePendingGrid();
                });

                card.addEventListener('dragstart', () => {
                    setEmergencyEvidenceDragPayload({
                        type: 'file',
                        fileId: entry.id
                    });
                });
                card.addEventListener('dragend', clearEmergencyEvidenceDragPayload);
                card.addEventListener('dragover', e => {
                    if (emergencyEvidenceDragPayload?.type === 'category') {
                        e.preventDefault();
                        card.classList.add('drag-over');
                    }
                });
                card.addEventListener('dragleave', () => card.classList.remove('drag-over'));
                card.addEventListener('drop', e => {
                    e.preventDefault();
                    card.classList.remove('drag-over');
                    if (emergencyEvidenceDragPayload?.type === 'category') {
                        linkEmergencyFileToCategory(entry.id, emergencyEvidenceDragPayload.categoryKey);
                        renderEmergencyEvidencePendingGrid();
                        renderEmergencyEvidenceCategoryList();
                    }
                    clearEmergencyEvidenceDragPayload();
                });

                const check = card.querySelector('.pending-file-check');
                check.addEventListener('click', e => e.stopPropagation());
                check.addEventListener('change', () => toggleEmergencyEvidenceSelection(entry.id, check.checked));

                const nameInput = card.querySelector('input[type="text"]');
                nameInput.addEventListener('click', e => e.stopPropagation());
                nameInput.addEventListener('mousedown', e => e.stopPropagation());
                nameInput.addEventListener('input', () => renamePendingEmergencyEvidence(entry.id, nameInput
                    .value));

                const removeBtn = card.querySelector('.pending-remove');
                removeBtn.addEventListener('click', e => {
                    e.stopPropagation();
                    removePendingEmergencyEvidence(entry.id);
                });

                const expandBtn = card.querySelector('.preview-expand-btn');
                if (expandBtn) {
                    expandBtn.addEventListener('click', e => {
                        e.stopPropagation();
                        openGenericFilePreview(entry);
                    });
                }

                card.querySelectorAll('.draft-link-pill button').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.stopPropagation();
                        unlinkEmergencyFileFromCategory(btn.dataset.fileId, btn.dataset
                            .categoryKey);
                        renderEmergencyEvidencePendingGrid();
                        renderEmergencyEvidenceCategoryList();
                    });
                });

                grid.appendChild(card);

                const docPreview = card.querySelector('.document-mini-preview');
                if (docPreview) hydrateDocumentThumbnail(entry, docPreview);
            });
        }

        function renderEmergencyEvidenceCategoryList() {
            const container = document.getElementById('emergencyEvidenceCategoryList');
            if (!container) return;

            const previousChecked = new Set([...container.querySelectorAll('.drag-item-check:checked')].map(x => x.dataset
                .categoryKey));
            container.innerHTML = '';

            emergencyEvidenceCategories.forEach(cat => {
                const linkedFiles = getDraftLinkedEmergencyFilesForCategory(cat.key);
                const pills = buildEmergencyFilePillsForCategory(cat.key);

                const card = document.createElement('div');
                card.className = `drag-item-card${previousChecked.has(cat.key)?' selected':''}`;
                card.dataset.categoryKey = cat.key;
                card.draggable = true;
                card.innerHTML = `
      <input class="drag-item-check" data-category-key="${cat.key}" type="checkbox" ${previousChecked.has(cat.key)?'checked':''} aria-label="Seleccionar categoría">
      <div style="flex:1;min-width:0;">
        <strong style="font-size:13px;">${escapeHtml(cat.title)}</strong>
        <small style="display:block;color:var(--muted);margin-top:4px;">${escapeHtml(cat.hint)}</small>
        ${pills?`<div class="draft-link-pills" style="margin-top:8px;">${pills}</div>`:''}
      </div>`;

                card.addEventListener('click', e => {
                    if (e.target.closest('input,button')) return;
                    const check = card.querySelector('.drag-item-check');
                    check.checked = !check.checked;
                    card.classList.toggle('selected', check.checked);
                });

                card.addEventListener('dragstart', () => {
                    setEmergencyEvidenceDragPayload({
                        type: 'category',
                        categoryKey: cat.key
                    });
                });
                card.addEventListener('dragend', clearEmergencyEvidenceDragPayload);
                card.addEventListener('dragover', e => {
                    if (emergencyEvidenceDragPayload?.type === 'file') {
                        e.preventDefault();
                        card.classList.add('drag-over');
                    }
                });
                card.addEventListener('dragleave', () => card.classList.remove('drag-over'));
                card.addEventListener('drop', e => {
                    e.preventDefault();
                    card.classList.remove('drag-over');
                    if (emergencyEvidenceDragPayload?.type === 'file') {
                        linkEmergencyFileToCategory(emergencyEvidenceDragPayload.fileId, cat.key);
                        renderEmergencyEvidencePendingGrid();
                        renderEmergencyEvidenceCategoryList();
                    }
                    clearEmergencyEvidenceDragPayload();
                });

                const check = card.querySelector('.drag-item-check');
                check.addEventListener('click', e => e.stopPropagation());
                check.addEventListener('change', () => card.classList.toggle('selected', check.checked));

                card.querySelectorAll('.draft-link-pill button').forEach(btn => {
                    btn.addEventListener('click', e => {
                        e.stopPropagation();
                        unlinkEmergencyFileFromCategory(btn.dataset.fileId, btn.dataset
                            .categoryKey);
                        renderEmergencyEvidencePendingGrid();
                        renderEmergencyEvidenceCategoryList();
                    });
                });

                container.appendChild(card);
            });
        }


        function saveEmergencyEvidenceLinks() {

            // PRUEBA 1: No existen archivos cargados
            if (emergencyEvidencePendingFiles.length === 0) {

                mostrarAlertaAntecedentes(
                    'Falta adjuntar documentos',
                    'Debes adjuntar al menos un documento antes de guardar.'
                );

                return;
            }

            // Aplicar los vínculos seleccionados manualmente
            applySelectedEmergencyLinks();

            // Obtener archivos que tienen al menos una categoría vinculada
            const ready = emergencyEvidencePendingFiles.filter(
                x => (x.draftCategoryKeys || []).length > 0
            );

            // PRUEBA 2: Hay archivos, pero ninguno está vinculado
            if (!ready.length) {

                mostrarAlertaAntecedentes(
                    'Falta vincular antecedentes',
                    'Debes vincular al menos un archivo a una categoría antes de guardar.'
                );

                return;
            }

            // PRUEBA 3: Guardar los archivos vinculados
            ready.forEach(entry => {
                emergencyEvidenceRegistry.push({
                    id: `emg_saved_${Date.now()}_${Math.random().toString(36).slice(2,8)}`,
                    file: entry.file,
                    objectUrl: entry.objectUrl,
                    previewUrl: entry.previewUrl,
                    name: entry.name,
                    displayName: entry.displayName,
                    categoryKeys: [...(entry.draftCategoryKeys || [])]
                });
            });

            // Mantener pendientes los archivos que no fueron vinculados
            emergencyEvidencePendingFiles = emergencyEvidencePendingFiles.filter(
                x => (x.draftCategoryKeys || []).length === 0
            );

            // Actualizar la interfaz
            summarizeEmergencyEvidencePending();
            renderEmergencyEvidencePendingGrid();
            renderEmergencyEvidenceCategoryList();
            renderEmergencyEvidenceRegister();
            refreshEmergencyEvidenceIndicators();
        }


        let antecedenteModalFocus = null;

        function crearModalAccionAntecedente() {
            const doc = window.parent.document;
            let modal = doc.getElementById('przModalAccionAntecedente');

            if (modal) return modal;

            const estilos = doc.createElement('style');
            estilos.id = 'przModalAccionAntecedenteStyles';
            estilos.textContent = `
        #przModalAccionAntecedente {
            position: fixed !important;
            inset: 0 !important;
            z-index: 2147483001 !important;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: rgba(17,28,23,.55);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            box-sizing: border-box;
        }

        #przModalAccionAntecedente.hidden {
            display: none !important;
        }

        #przModalAccionAntecedente .prz-accion-card {
            width: min(480px, 100%);
            max-height: calc(100dvh - 40px);
            overflow-y: auto;
            background: white;
            border: 1px solid #d8e0dc;
            border-radius: 14px;
            box-shadow: 0 18px 55px rgba(0,0,0,.25);
            color: #17211d;
            font-size: 13px;
        }

        #przModalAccionAntecedente .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: #fafcfb;
            border-bottom: 1px solid #d8e0dc;
        }

        #przModalAccionAntecedente .modal-head h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #17211d;
        }

        #przModalAccionAntecedente .modal-close {
            width: 30px;
            height: 30px;
            border: 0;
            background: transparent;
            color: #44514b;
            border-radius: 50%;
            cursor: pointer;
            font-size: 21px;
        }

        #przModalAccionAntecedente .modal-close:hover {
            background: #eef2f0;
        }

        #przModalAccionAntecedente .modal-body {
            padding: 22px 20px;
            line-height: 1.5;
            color: #44514b;
        }

        #przModalAccionAntecedente .modal-body label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        #przModalAccionAntecedente input {
            box-sizing: border-box;
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5d0;
            border-radius: 8px;
            background: white;
            color: #17211d;
            font-size: 13px;
        }

        #przModalAccionAntecedente input:focus {
            outline: none;
            border-color: #176b4a;
            box-shadow: 0 0 0 3px rgba(23,107,74,.10);
        }

        #przModalAccionAntecedente .prz-error {
            display: none;
            margin-top: 8px;
            color: #9d2f2f;
            font-size: 12px;
        }

        #przModalAccionAntecedente .prz-error.visible {
            display: block;
        }

        #przModalAccionAntecedente .prz-aviso {
            padding: 12px;
            border: 1px solid #f0d39c;
            background: #fff6e5;
            border-radius: 9px;
            color: #684000;
            overflow-wrap: anywhere;
        }

        #przModalAccionAntecedente .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 14px 20px;
            background: #fafcfb;
            border-top: 1px solid #d8e0dc;
        }

        #przModalAccionAntecedente .modal-footer button {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #d8e0dc;
            background: white;
            color: #17211d;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        #przModalAccionAntecedente .modal-footer .primary {
            background: #176b4a;
            border-color: #176b4a;
            color: white;
        }

        #przModalAccionAntecedente .modal-footer .primary:hover {
            background: #0f4e37;
        }

        #przModalAccionAntecedente .modal-footer .danger {
            background: #fff7f7;
            border-color: #d6a9a9;
            color: #8b3c3c;
        }

        #przModalAccionAntecedente .modal-footer .danger:hover {
            background: #fbecec;
        }
    `;
            doc.head.appendChild(estilos);

            modal = doc.createElement('div');
            modal.id = 'przModalAccionAntecedente';
            modal.className = 'hidden';
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-modal', 'true');
            modal.setAttribute('aria-labelledby', 'przAccionTitulo');

            modal.innerHTML = `
        <div class="prz-accion-card">
            <div class="modal-head">
                <h3 id="przAccionTitulo"></h3>
                <button type="button" class="modal-close"
                        aria-label="Cerrar">&times;</button>
            </div>

            <div class="modal-body">
                <div id="przAccionContenido"></div>
            </div>

            <div class="modal-footer">
                <button type="button" id="przAccionCancelar">
                    Cancelar
                </button>
                <button type="button" id="przAccionConfirmar"
                        class="primary">
                    Guardar cambios
                </button>
            </div>
        </div>
    `;

            doc.body.appendChild(modal);

            const cerrar = () => cerrarModalAccionAntecedente();

            modal.querySelector('.modal-close')
                .addEventListener('click', cerrar);

            modal.querySelector('#przAccionCancelar')
                .addEventListener('click', cerrar);

            modal.addEventListener('click', event => {
                if (event.target === modal) cerrar();
            });

            modal.addEventListener('keydown', event => {
                if (event.key === 'Escape') {
                    event.preventDefault();
                    cerrar();
                }
            });

            return modal;
        }

        function cerrarModalAccionAntecedente() {
            const modal = window.parent.document.getElementById(
                'przModalAccionAntecedente'
            );

            if (!modal) return;

            modal.classList.add('hidden');

            const confirmar = modal.querySelector('#przAccionConfirmar');
            confirmar.onclick = null;

            if (antecedenteModalFocus?.isConnected) {
                antecedenteModalFocus.focus();
            }

            antecedenteModalFocus = null;
        }


        // MODAL RENOMBRAR
        function renameEmergencyEvidence(id) {
            const entry = emergencyEvidenceRegistry.find(x => x.id === id);
            if (!entry) return;

            const doc = window.parent.document;
            const modal = crearModalAccionAntecedente();

            antecedenteModalFocus = document.activeElement;

            modal.querySelector('#przAccionTitulo').textContent =
                'Renombrar antecedente';

            const contenido = modal.querySelector('#przAccionContenido');
            contenido.replaceChildren();

            const label = doc.createElement('label');
            label.textContent = 'Nombre del documento';
            label.htmlFor = 'przNuevoNombre';

            const input = doc.createElement('input');
            input.type = 'text';
            input.id = 'przNuevoNombre';
            input.maxLength = 255;
            input.value = entry.displayName || entry.file?.name || entry.name || '';

            const error = doc.createElement('div');
            error.className = 'prz-error';
            error.textContent = 'Debe ingresar un nombre para el documento.';

            contenido.append(label, input, error);

            const confirmar = modal.querySelector('#przAccionConfirmar');
            confirmar.textContent = 'Guardar cambios';
            confirmar.className = 'primary';

            const guardar = () => {
                const nuevoNombre = input.value.trim();

                if (!nuevoNombre) {
                    error.classList.add('visible');
                    input.focus();
                    return;
                }

                entry.displayName = nuevoNombre;

                renderEmergencyEvidenceRegister();
                refreshEmergencyEvidenceIndicators();
                cerrarModalAccionAntecedente();
            };

            confirmar.onclick = guardar;

            input.addEventListener('input', () => {
                error.classList.remove('visible');
            });

            input.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    guardar();
                }
            });

            modal.classList.remove('hidden');
            input.focus();
            input.select();
        }


        // MODAL ELIMINAR
        function removeEmergencyEvidence(id) {
            const entry = emergencyEvidenceRegistry.find(x => x.id === id);
            if (!entry) return;

            const doc = window.parent.document;
            const modal = crearModalAccionAntecedente();

            antecedenteModalFocus = document.activeElement;

            modal.querySelector('#przAccionTitulo').textContent =
                'Eliminar antecedente';

            const contenido = modal.querySelector('#przAccionContenido');
            contenido.replaceChildren();

            const aviso = doc.createElement('div');
            aviso.className = 'prz-aviso';

            const pregunta = doc.createElement('div');
            pregunta.textContent =
                '¿Está seguro de que desea eliminar el siguiente antecedente?';

            const nombre = doc.createElement('strong');
            nombre.textContent = entry.displayName || entry.name || entry.file?.name || '';
            nombre.style.display = 'block';
            nombre.style.marginTop = '8px';

            aviso.append(pregunta, nombre);

            const detalle = doc.createElement('p');
            detalle.textContent =
                'Al confirmar se eliminará el antecedente y todos sus vínculos del formulario.';
            detalle.style.margin = '12px 0 0';
            detalle.style.fontSize = '12px';

            contenido.append(aviso, detalle);

            const confirmar = modal.querySelector('#przAccionConfirmar');
            confirmar.textContent = 'Sí, eliminar';
            confirmar.className = 'danger';

            confirmar.onclick = () => {
                const idx = emergencyEvidenceRegistry.findIndex(x => x.id === id);
                if (idx === -1) {
                    cerrarModalAccionAntecedente();
                    return;
                }

                const registro = emergencyEvidenceRegistry[idx];

                if (registro.objectUrl) {
                    URL.revokeObjectURL(registro.objectUrl);
                }

                emergencyEvidenceRegistry.splice(idx, 1);

                renderEmergencyEvidenceRegister();
                refreshEmergencyEvidenceIndicators();

                cerrarModalAccionAntecedente();
            };

            modal.classList.remove('hidden');
            confirmar.focus();
        }


        function removeEmergencyEvidenceCategory(id, categoryKey) {
            const entry = emergencyEvidenceRegistry.find(x => x.id === id);
            if (!entry?.categoryKeys) return;
            entry.categoryKeys = entry.categoryKeys.filter(x => x !== categoryKey);
            if (!entry.categoryKeys.length) {
                emergencyEvidenceRegistry.splice(emergencyEvidenceRegistry.findIndex(x => x.id === id), 1);
            }
            renderEmergencyEvidenceRegister();
            refreshEmergencyEvidenceIndicators();
        }

        function renderEmergencyEvidenceRegister() {
            const wrap = document.getElementById('emergencyEvidenceRegister');
            const empty = document.getElementById('emergencyEvidenceRegisterEmpty');
            if (!wrap || !empty) return;
            wrap.innerHTML = '';
            empty.classList.toggle('hidden', emergencyEvidenceRegistry.length > 0);

            emergencyEvidenceRegistry.forEach(entry => {
                const needsContentPreview = fileIsWord(entry.file) || fileIsSpreadsheet(entry.file) ||
                    fileIsBrowserTextPreviewable(entry.file);
                const thumb = entry.previewUrl ?
                    `<div class="attachment-thumb" style="position:relative;"><img src="${entry.previewUrl}" alt=""><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>` :
                    fileIsPdf(entry.file) ?
                    `<div class="attachment-thumb" style="position:relative;"><embed src="${entry.objectUrl}#page=1&toolbar=0&navpanes=0&scrollbar=0" type="application/pdf"><button type="button" class="preview-expand-btn" title="Vista previa del PDF">⤢</button></div>` :
                    needsContentPreview ?
                    `<div class="attachment-thumb" style="position:relative;"><div class="document-mini-preview"><div class="loading-doc">Cargando…</div></div><button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>` :
                    `<div class="attachment-thumb" style="position:relative;">${fileKindIcon(entry.file)}<button type="button" class="preview-expand-btn" title="Vista previa del archivo">⤢</button></div>`;

                const tags = (entry.categoryKeys || []).map(key =>
                    `<span class="link-pill">${escapeHtml(getEmergencyCategoryTitle(key))}<button type="button" title="Quitar vínculo" data-id="${entry.id}" data-category-key="${key}" style="margin-left:6px;border:0;background:none;color:inherit;cursor:pointer;font-weight:700;">×</button></span>`
                ).join('');

                const card = document.createElement('div');
                card.className = 'attachment-card';
                card.innerHTML = `
      <div class="attachment-card-top">
        <div style="display:flex;gap:10px;align-items:flex-start;min-width:0;flex:1;">
          ${thumb}
          <div style="min-width:0;flex:1;">
            <div class="attachment-card-title">${escapeHtml(entry.displayName || entry.name || entry.file?.name || 'Archivo')}</div>
            <div class="attachment-links">${tags}</div>
          </div>
        </div>
        <div class="tool-group" style="justify-content:flex-end;">
          <button type="button" class="link-btn" onclick="renameEmergencyEvidence('${entry.id}')">Renombrar</button>
          <button type="button" class="link-btn danger" onclick="removeEmergencyEvidence('${entry.id}')">Eliminar</button>
        </div>
      </div>`;

                wrap.appendChild(card);

                const expandBtn = card.querySelector('.preview-expand-btn');
                if (expandBtn) {
                    expandBtn.addEventListener('click', () => openGenericFilePreview(entry));
                }
                const docPreview = card.querySelector('.document-mini-preview');
                if (docPreview) hydrateDocumentThumbnail(entry, docPreview);

                card.querySelectorAll('.attachment-links button[data-category-key]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        removeEmergencyEvidenceCategory(btn.dataset.id, btn.dataset.categoryKey);
                    });
                });
            });
        }

        function refreshEmergencyEvidenceIndicators() {
            const status = document.getElementById('emergencyEvidenceStatus');
            const list = document.getElementById('emergencyEvidenceList');
            if (!status || !list) return;
            const count = emergencyEvidenceRegistry.length;
            status.textContent = count ? `${count} antecedente(s) vinculado(s)` : 'Sin antecedentes vinculados';
            if (!count) {
                list.textContent =
                    'Puede adjuntar imágenes, PDF, Word, Excel u otros archivos y vincularlos al tipo de antecedente que respaldan.';
                return;
            }
            const sample = emergencyEvidenceRegistry.slice(0, 4).map(x => x.displayName || x.name || x.file?.name ||
                'archivo');
            list.textContent = `${count} antecedente(s) vinculado(s): ${sample.join(', ')}${count>4 ? '…' : ''}`;
        }

        let currentFillMode = 'form';
        let currentStepIndex = 0;
        let lastMissingControls = [];

        function getStepSections() {
            return [...document.querySelectorAll('main > .form-step')];
        }

        function isVisibleForValidation(el) {
            if (!el) return false;
            let node = el;
            while (node && node !== document.body) {
                if (node.classList?.contains('hidden')) return false;
                node = node.parentElement;
            }
            return true;
        }

        function fieldLabelForControl(control) {
            if (!control) return 'Campo obligatorio';
            const field = control.closest('.field');
            if (field) {
                const direct = [...field.children].find(el => el.tagName === 'LABEL');
                if (direct) return direct.textContent.replace(/\*/g, '').trim();
            }
            if (control.type === 'radio') return control.name || 'Selección obligatoria';
            return control.placeholder || control.name || 'Campo obligatorio';
        }

        function collectRequiredControls(scope) {
            const out = [];
            const seen = new Set();

            scope.querySelectorAll('.essential').forEach(control => {
                const customHidden =
                    control.id === 'emergencyQualificationType' ||
                    control.id === 'deliveryPlace';
                if (isVisibleForValidation(control) || customHidden) {
                    if (!seen.has(control)) {
                        seen.add(control);
                        out.push(control);
                    }
                }
            });

            scope.querySelectorAll('.field').forEach(field => {
                if (!isVisibleForValidation(field)) return;
                const reqLabel = [...field.children].find(el => el.tagName === 'LABEL' && el.classList.contains(
                    'req'));
                if (!reqLabel) return;
                const control = field.querySelector('input:not([type="hidden"]),select,textarea');
                if (control && !seen.has(control)) {
                    seen.add(control);
                    out.push(control);
                }
            });

            return out;
        }

        function controlComplete(control, scope) {
            if (control.type === 'radio') {
                return !!scope.querySelector(`input[type="radio"][name="${control.name}"]:checked`);
            }
            if (control.type === 'checkbox') return control.checked;
            return String(control.value ?? '').trim() !== '';
        }

        function clearValidationMarks(scope = document) {
            scope.querySelectorAll('.validation-missing').forEach(el => el.classList.remove('validation-missing'));
            scope.querySelectorAll('.validation-missing-group').forEach(el => el.classList.remove(
                'validation-missing-group'));
        }

        function validateScopeRequired(scope, {
            mark = true
        } = {}) {
            clearValidationMarks(scope);
            const missing = [];
            const handledRadioNames = new Set();

            collectRequiredControls(scope).forEach(control => {
                if (control.type === 'radio') {
                    if (handledRadioNames.has(control.name)) return;
                    handledRadioNames.add(control.name);
                    if (!controlComplete(control, scope)) {
                        const group = control.closest('.choice-row') || control.closest('.field');
                        if (mark && group) group.classList.add('validation-missing-group');
                        missing.push({
                            control: group || control,
                            label: fieldLabelForControl(control)
                        });
                    }
                    return;
                }

                if (!controlComplete(control, scope)) {
                    let target = control;
                    if (control.id === 'emergencyQualificationType') target = document.getElementById(
                        'emergencyTypeComboButton') || control;
                    if (control.id === 'deliveryPlace') target = document.getElementById('deliveryPlaceButton') ||
                        control;
                    if (mark && !target.classList.contains('hidden')) target.classList.add('validation-missing');
                    missing.push({
                        control: target,
                        label: fieldLabelForControl(control)
                    });
                }
            });
            return missing;
        }

        function showRequiredValidationModal(missing, titleText = 'Faltan campos obligatorios') {
            lastMissingControls = missing || [];
            const modal = document.getElementById('requiredValidationModal');
            const title = document.getElementById('requiredValidationTitle');
            const subtitle = document.getElementById('requiredValidationSubtitle');
            const list = document.getElementById('requiredValidationList');
            if (!modal || !title || !subtitle || !list) return;

            title.textContent = titleText;
            subtitle.textContent =
                `Se detectaron ${lastMissingControls.length} antecedente(s) obligatorio(s) sin completar.`;

            const unique = [...new Set(lastMissingControls.map(x => x.label))];
            list.innerHTML = unique.map((label, i) => `
    <div style="display:flex;gap:9px;align-items:flex-start;padding:9px 0;border-bottom:1px solid #e8ecea;">
      <span style="display:inline-flex;align-items:center;justify-content:center;min-width:22px;height:22px;border-radius:999px;background:#fff0f0;color:#8b3c3c;font-size:10px;font-weight:800;">${i+1}</span>
      <span style="font-size:12px;color:#44514b;padding-top:2px;">${escapeHtml(label)}</span>
    </div>`).join('');
            modal.classList.remove('hidden');
        }

        function closeRequiredValidationModal() {
            document.getElementById('requiredValidationModal')?.classList.add('hidden');
        }

        function focusFirstMissingField() {
            closeRequiredValidationModal();
            const target = lastMissingControls[0]?.control;
            if (!target) return;
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            setTimeout(() => target.focus?.(), 250);
        }

        function validateStep(index, showModal = true) {
            const section = getStepSections()[index];
            if (!section) return true;
            const missing = validateScopeRequired(section, {
                mark: true
            });
            if (showModal) recordValidationAttempt(missing);
            if (missing.length && showModal) {
                const title = section.querySelector('.section-head h2')?.textContent?.trim() || `Paso ${index+1}`;
                showRequiredValidationModal(missing, `Falta completar el paso ${index+1}: ${title}`);
            }
            return missing.length === 0;
        }

        function validateWholeForm() {
            const sections = getStepSections();
            clearValidationMarks(document);
            const allMissing = [];

            sections.forEach((section, index) => {
                validateScopeRequired(section, {
                    mark: true
                }).forEach(item => allMissing.push({
                    ...item,
                    step: index
                }));
            });

            recordValidationAttempt(allMissing);

            if (!allMissing.length) {
                const modal = document.getElementById('requiredValidationModal');
                document.getElementById('requiredValidationTitle').textContent = 'Campos obligatorios completos';
                document.getElementById('requiredValidationSubtitle').textContent =
                    'No se detectaron antecedentes obligatorios pendientes en los campos actualmente habilitados.';
                document.getElementById('requiredValidationList').innerHTML =
                    '<div class="alert blue" style="margin-top:0;">La validación fue completada correctamente.</div>';
                modal?.classList.remove('hidden');
                return true;
            }

            if (currentFillMode === 'step' && Number.isInteger(allMissing[0].step)) {
                currentStepIndex = allMissing[0].step;
                renderFillMode();
            }
            showRequiredValidationModal(allMissing, 'Revisión de campos obligatorios');
            return false;
        }

        function setFillMode(mode) {
            const nextMode = mode === 'step' ? 'step' : 'form';
            if (typeof formAnalytics !== 'undefined') {
                formAnalytics.modesUsed.add(nextMode);
                if (currentFillMode !== nextMode) formAnalytics.modeSwitches++;
            }
            currentFillMode = nextMode;
            document.body.classList.toggle('step-mode', currentFillMode === 'step');
            document.getElementById('modeFormBtn')?.classList.toggle('active', currentFillMode === 'form');
            document.getElementById('modeStepBtn')?.classList.toggle('active', currentFillMode === 'step');
            document.getElementById('stepNavigator')?.classList.toggle('hidden', currentFillMode !== 'step');

            if (currentFillMode === 'step') {
                const sections = getStepSections();
                const firstIncomplete = sections.findIndex(section => validateScopeRequired(section, {
                    mark: false
                }).length > 0);
                currentStepIndex = firstIncomplete >= 0 ? firstIncomplete : 0;
            }
            renderFillMode();
        }

        function renderFillMode() {
            const sections = getStepSections();
            if (currentFillMode === 'form') {
                sections.forEach(s => s.classList.remove('active-step'));
                return;
            }

            currentStepIndex = Math.max(0, Math.min(currentStepIndex, sections.length - 1));
            sections.forEach((s, i) => s.classList.toggle('active-step', i === currentStepIndex));

            const section = sections[currentStepIndex];
            const title = section?.querySelector('.section-head h2')?.textContent?.trim() || '';
            const missing = section ? validateScopeRequired(section, {
                mark: false
            }) : [];

            document.getElementById('stepNavTitle').textContent =
                `Paso ${currentStepIndex+1} de ${sections.length} · ${title}`;
            document.getElementById('stepNavCompletion').textContent = missing.length ?
                `${missing.length} campo(s) obligatorio(s) pendiente(s) en este paso.` :
                'Este paso cumple actualmente los campos obligatorios.';
            document.getElementById('stepNavProgressBar').style.width = `${((currentStepIndex+1)/sections.length)*100}%`;
            document.getElementById('stepPrevBtn').disabled = currentStepIndex === 0;
            document.getElementById('stepNextBtn').textContent = currentStepIndex === sections.length - 1 ?
                'Finalizar y validar' : 'Siguiente →';
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function renderFillModeStatusOnly() {
            if (currentFillMode !== 'step') return;
            const section = getStepSections()[currentStepIndex];
            if (!section) return;
            const missing = validateScopeRequired(section, {
                mark: false
            });
            document.getElementById('stepNavCompletion').textContent = missing.length ?
                `${missing.length} campo(s) obligatorio(s) pendiente(s) en este paso.` :
                'Este paso cumple actualmente los campos obligatorios.';
        }

        function nextStep() {
            const sections = getStepSections();
            if (!validateStep(currentStepIndex, true)) return;
            if (currentStepIndex >= sections.length - 1) {
                validateWholeForm();
                return;
            }
            currentStepIndex++;
            renderFillMode();
        }

        function previousStep() {
            if (currentStepIndex <= 0) return;
            currentStepIndex--;
            renderFillMode();
        }

        function updateProgress() {
            const fields = [...document.querySelectorAll('.essential')];
            let done = 0,
                groups = new Set();
            fields.forEach(f => {
                if (f.type === 'radio') {
                    if (groups.has(f.name)) return;
                    groups.add(f.name);
                    if (document.querySelector(`input[name="${f.name}"]:checked`)) done++;
                } else if ((f.value || '').trim() !== '') done++;
            });
            const singles = fields.filter(f => f.type !== 'radio').length;
            const total = singles + groups.size;
            const pct = total ? Math.round(done / total * 100) : 0;
            document.getElementById('progressBar').style.width = pct + '%';
            document.getElementById('progressLabel').textContent = pct + '% esenciales';
        }

        function clearForm() {
            if (!confirm('¿Limpiar todos los campos del formulario?')) return;
            formAnalytics.startedAt = Date.now();
            formAnalytics.submittedAt = null;
            formAnalytics.activeMilliseconds = 0;
            formAnalytics.lastInteractionAt = Date.now();
            formAnalytics.validationAttempts = 0;
            formAnalytics.validationAttemptsWithErrors = 0;
            formAnalytics.totalMissingDetections = 0;
            formAnalytics.validationErrorCounts = {};
            formAnalytics.modeSwitches = 0;
            formAnalytics.modesUsed = new Set([currentFillMode]);
            formAnalytics.helpInteractions = 0;
            formAnalytics.submissionCount = 0;
            formAnalytics.lastSubmissionSnapshot = null;
            document.getElementById('submissionStatisticsPanel')?.classList.add('hidden');
            const submitBtn = document.getElementById('submitRequestBtn');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enviar requerimiento';
            }
            document.querySelectorAll('input,textarea').forEach(e => {
                if (e.type === 'checkbox' || e.type === 'radio') e.checked = false;
                else e.value = '';
            });
            document.querySelectorAll('select').forEach(e => e.selectedIndex = 0);
            lotQuoteRegistry.forEach(record => {
                if (record.objectUrl) {
                    try {
                        URL.revokeObjectURL(record.objectUrl);
                    } catch (e) {}
                }
            });
            lotQuoteRegistry.length = 0;
            const lotStatus = document.getElementById('lotQuoteStatus');
            const lotViewBtn = document.getElementById('lotQuoteViewBtn');
            if (lotStatus) lotStatus.textContent = 'Sin cotizaciones de lote';
            if (lotViewBtn) lotViewBtn.disabled = true;
            resetEmergencyTypeCombo();
            document.querySelectorAll('input[name="technicalCounterpartMode"]').forEach(r => r.checked = false);
            toggleTechnicalCounterpartMode();
            currentStepIndex = 0;
            togglePriority();
            togglePac();
            toggleKind();
            toggleBrand();
            calcTotals();
            updateProgress();
            renderFillMode();
        }
        document.addEventListener('input', () => {
            updateProgress();
            renderFillModeStatusOnly();
        });
        document.addEventListener('change', () => {
            updateProgress();
            renderFillModeStatusOnly();
        });
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.eui-help-label') && !event.target.closest('#euiHelpPopover')) {
                hideEuiFieldHelp(true);
            }
            if (!event.target.closest('#deliveryPlaceCombo')) {
                document.getElementById('deliveryPlaceDropdown')?.classList.add('hidden');
                document.getElementById('deliveryPlaceCombo')?.classList.remove('open');
                document.getElementById('deliveryPlaceButton')?.setAttribute('aria-expanded', 'false');
            }
            if (!event.target.closest('#emergencyTypeCombo')) {
                document.getElementById('emergencyTypeDropdown')?.classList.add('hidden');
                document.getElementById('emergencyTypeCombo')?.classList.remove('open');
                document.getElementById('emergencyTypeComboButton')?.setAttribute('aria-expanded', 'false');
                hideEmergencyTypeDefinition();
            }
            if (!event.target.closest('.criteria-combo')) {
                closeReceptionCriteriaDropdown();
                closeEvaluationCriteriaDropdown();
                closeServiceLevelsDropdown();
                return;
            }
            if (!event.target.closest('.evaluation-criteria-combo')) closeEvaluationCriteriaDropdown();
            if (!event.target.closest('.service-level-combo')) closeServiceLevelsDropdown();
            if (!event.target.closest('.criteria-combo:not(.evaluation-criteria-combo):not(.service-level-combo)'))
                closeReceptionCriteriaDropdown();
        });
        setTodayOnRequestDate();
        updateFormElapsedTimer();
        setFillMode('form');
        ['requestingResponsible', 'requestingUnit'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', () => {
                if (document.querySelector('input[name="technicalCounterpartMode"]:checked')?.value ===
                    'same') {
                    autofillTechnicalCounterpart();
                }
            });
        });
        initEuiFieldHelp();
        const emergencyTypeDropdownEl = document.getElementById('emergencyTypeDropdown');
        if (emergencyTypeDropdownEl) {
            emergencyTypeDropdownEl.addEventListener('mouseleave', scheduleHideEmergencyTypeDefinition);
        }
        document.querySelectorAll('.subbox .mini-title').forEach(titleEl => {
            const text = (titleEl.textContent || '').trim();
            if (text.includes('Integridad y contactos previos con el mercado')) {
                const grid = titleEl.parentElement?.querySelector('.grid');
                if (grid) {
                    [...grid.children].slice(0, 3).forEach(field => field.classList.add('integrity-align'));
                }
            }
        });

        let emergencyAlertPreviousFocus = null;

        function mostrarAlertaAntecedentes(titulo, mensaje) {

            const parentDoc = window.parent.document;

            const modal =
                parentDoc.getElementById('emergencyEvidenceAlert') ||
                document.getElementById('emergencyEvidenceAlert');

            if (!modal) {
                console.error('No se encontró emergencyEvidenceAlert');
                return;
            }

            // Guardar el foco anterior
            emergencyAlertPreviousFocus = document.activeElement;

            // Incorporar los estilos al documento principal de Filament
            if (!parentDoc.getElementById('prz-emergency-alert-styles')) {

                const estilos = parentDoc.createElement('style');

                estilos.id = 'prz-emergency-alert-styles';

                estilos.textContent = `
            #emergencyEvidenceAlert {
                position: fixed !important;
                inset: 0 !important;
                z-index: 2147483000 !important;

                display: flex !important;
                align-items: center !important;
                justify-content: center !important;

                padding: 20px !important;
                background: rgba(17, 28, 23, 0.55) !important;
                box-sizing: border-box;
            }

            #emergencyEvidenceAlert.hidden {
                display: none !important;
            }

            #emergencyEvidenceAlert .emergency-alert-card {
                width: min(460px, 100%);
                max-height: calc(100dvh - 40px);
                overflow-y: auto;

                background: white;
                border: 1px solid #d8e0dc;
                border-radius: 14px;
                box-shadow: 0 18px 55px rgba(0,0,0,0.25);

                color: #17211d;
                font-family: Inter, "Segoe UI", Arial, sans-serif;
                font-size: 13px;
            }

            #emergencyEvidenceAlert .modal-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;

                padding: 16px 20px;
                background: #fafcfb;
                border-bottom: 1px solid #d8e0dc;
            }

            #emergencyEvidenceAlert .modal-head h3 {
                margin: 0;
                font-size: 16px;
                font-weight: 700;
                color: #17211d;
            }

            #emergencyEvidenceAlert .modal-close {
                position: static !important;
                display: flex;
                align-items: center;
                justify-content: center;

                width: 30px;
                height: 30px;
                padding: 0;
                margin: 0;

                border: 0;
                border-radius: 50%;
                background: transparent;
                color: #34423d;
                font-size: 22px;
                cursor: pointer;
            }

            #emergencyEvidenceAlert .modal-close:hover {
                background: #eef2f0;
            }

            #emergencyEvidenceAlert .modal-body {
                padding: 22px 20px;
                color: #44514b;
                line-height: 1.6;
            }

            #emergencyEvidenceAlert .modal-body p {
                margin: 0;
            }

            #emergencyEvidenceAlert .modal-footer {
                display: flex;
                justify-content: flex-end;
                padding: 14px 20px;

                background: #fafcfb;
                border-top: 1px solid #d8e0dc;
            }

            #emergencyEvidenceAlert .modal-footer .primary {
                padding: 10px 18px;

                background: #176b4a;
                color: white;
                border: 1px solid #176b4a;
                border-radius: 8px;

                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
            }

            #emergencyEvidenceAlert .modal-footer .primary:hover {
                background: #0f4e37;
            }
        `;

                parentDoc.head.appendChild(estilos);
            }

            // Mover la alerta al documento principal
            if (modal.parentElement !== parentDoc.body) {
                parentDoc.body.appendChild(modal);
            }

            // Asignar correctamente los eventos a los botones
            if (!modal.dataset.eventosConfigurados) {

                modal.querySelectorAll(
                    '.modal-close, .modal-footer .primary'
                ).forEach(boton => {

                    boton.removeAttribute('onclick');

                    boton.addEventListener('click', function() {
                        cerrarAlertaAntecedentes();
                    });
                });

                modal.dataset.eventosConfigurados = 'true';
            }

            // Actualizar textos
            modal.querySelector('#emergencyAlertTitle').textContent = titulo;
            modal.querySelector('#emergencyAlertMessage').textContent = mensaje;

            // Mostrar advertencia
            modal.classList.remove('hidden');

            // Enfocar el botón Entendido
            modal.querySelector('.modal-footer .primary')?.focus();
        }


        function cerrarAlertaAntecedentes() {

            const modal = window.parent.document.getElementById(
                'emergencyEvidenceAlert'
            );

            if (!modal) return;

            modal.classList.add('hidden');

            if (emergencyAlertPreviousFocus?.isConnected) {
                emergencyAlertPreviousFocus.focus();
            }
        }

        toggleOtherFrequency();
        toggleOtherDeliveryPlace();
        toggleEmergencyNeed();
        applyFieldHelp();
        calcTotals();
        refreshQuoteIndicators();
        renderEmergencyEvidenceRegister();
        refreshEmergencyEvidenceIndicators();
        summarizeEmergencyEvidencePending();
        normalizeTableToContainer();
        initTableStructureTools();
        toggleTableStructureLock(false);
        updateProgress();
    </script>


    <div id="filePreviewModal" class="file-preview-backdrop hidden" onclick="closeGenericFilePreview(event)">
        <div class="file-preview-card" onclick="event.stopPropagation()">
            <div class="file-preview-head">
                <div class="file-preview-title">
                    <strong id="filePreviewTitle">Vista previa del archivo</strong>
                    <small id="filePreviewMeta"></small>
                </div>
                <div class="file-preview-actions">
                    <button id="filePreviewOpenBtn" type="button">Abrir archivo</button>
                    <button type="button" class="modal-close" title="Cerrar"
                        onclick="closeGenericFilePreview()">×</button>
                </div>
            </div>
            <div id="filePreviewBody" class="file-preview-body"></div>
        </div>
    </div>

    <div id="imageLightbox" class="image-lightbox hidden" onclick="closeImageLightbox(event)">
        <div class="image-lightbox-card" onclick="event.stopPropagation()">
            <button type="button" class="image-lightbox-close" title="Cerrar"
                onclick="closeImageLightbox()">×</button>
            <img id="imageLightboxImg" alt="Vista ampliada">
            <div id="imageLightboxName" class="image-lightbox-name"></div>
        </div>
    </div>

    <div id="refImageModal" class="modal-backdrop hidden" onclick="closeReferenceImageManager(event)">
        <div class="modal-card" onclick="event.stopPropagation()">
            <div class="modal-head">
                <div>
                    <h3>Gestor de imágenes referenciales</h3>
                    <p>Cargue un lote de imágenes, seleccione una o varias miniaturas y vincúlelas a uno o más ítems.
                    </p>
                </div>
                <button type="button" class="modal-close" title="Cerrar"
                    onclick="closeReferenceImageManager()">×</button>
            </div>
            <div class="modal-body">
                <div class="attach-drop">
                    <div class="mini-title">1. Cargue las imágenes y seleccione cuáles va a vincular</div>
                    <div class="hint">Puede cargar todas las imágenes de una sola vez. Luego seleccione
                        individualmente una o varias miniaturas. Cada archivo permite editar su nombre antes de
                        vincularlo.</div>
                    <div class="tool-group">
                        <button type="button" class="small-btn"
                            onclick="document.getElementById('refImageManagerInput').click()">Seleccionar
                            imagen(es)</button>
                        <input id="refImageManagerInput" type="file" multiple accept="image/*" class="hidden"
                            onchange="handleRefImageManagerFile(this)">
                    </div>
                    <div id="refImageManagerFileName" class="attach-file-name">Ninguna imagen cargada.</div>
                    <div id="refImagePendingGrid" class="pending-file-grid"></div>
                </div>

                <div class="soft-card">
                    <div class="mini-title">2. Elija los ítems a los que pertenecen las imágenes seleccionadas</div>
                    <div class="hint">Cada ítem vuelve a mostrar su número y descripción. Puede seleccionarlo con la
                        casilla o usar arrastrar y soltar: lleve una imagen hasta el ítem o lleve el ítem hasta una
                        imagen.</div>
                    <div id="refImageItemList" class="drag-item-list"></div>
                    <div class="link-workflow-note">Puede vincular de dos formas: (1) seleccionar una o varias
                        imágenes y uno o varios ítems; o (2) arrastrar una imagen sobre un ítem, o arrastrar el ítem
                        sobre la imagen. Los vínculos preparados se mostrarán antes de guardarlos.</div>
                </div>

                <div class="attachment-register">
                    <div class="mini-title">Imágenes ya vinculadas</div>
                    <div id="refImageModalRegisterEmpty" class="hint">Aún no hay imágenes referenciales
                        vinculadas.</div>
                    <div id="refImageModalRegister"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeReferenceImageManager()">Cancelar</button>
                <button type="button" class="primary" onclick="saveReferenceImageLink()">Guardar vínculo de
                    seleccionadas</button>
            </div>
        </div>
    </div>


    <div id="quoteViewerModal" class="modal-backdrop hidden" onclick="closeQuoteViewer(event)">
        <div class="modal-card" style="width:min(760px,94vw);max-height:86vh;" onclick="event.stopPropagation()">
            <div class="modal-head">
                <div>
                    <h3 id="quoteViewerTitle">Cotizaciones</h3>
                    <p>Revise los archivos adjuntos directamente desde el formulario.</p>
                </div>
                <button type="button" class="modal-close" title="Cerrar"
                    onclick="closeQuoteViewer()">×</button>
            </div>
            <div class="modal-body">
                <div id="quoteViewerEmpty" class="hint hidden">No hay cotizaciones disponibles para visualizar.
                </div>
                <div id="quoteViewerList" class="quote-view-list"></div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeQuoteViewer()">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="attachmentModal" class="modal-backdrop hidden" onclick="closeAttachmentManager(event)">
        <div class="modal-card" onclick="event.stopPropagation()">
            <div class="modal-head">
                <div>
                    <h3>Gestor de cotizaciones</h3>
                    <p>Cargue un lote de cotizaciones y vincule cada archivo al ítem o a los ítems que correspondan.</p>
                </div>
                <button type="button" class="modal-close" title="Cerrar"
                    onclick="closeAttachmentManager()">×</button>
            </div>
            <div class="modal-body">
                <div class="attach-drop">
                    <div class="mini-title">1. Cargue las cotizaciones y seleccione cuáles va a vincular</div>
                    <div class="hint">Puede cargar varios PDF, imágenes, Word o Excel de una sola vez. Luego puede
                        seleccionar archivos manualmente, o usar arrastrar y soltar para vincularlos con los ítems. Si
                        el archivo es una imagen podrá ampliarlo; en los demás casos se mostrará su tipo de archivo.
                        También puede renombrar el archivo antes de guardarlo.</div>
                    <div class="tool-group">
                        <button type="button" class="small-btn"
                            onclick="document.getElementById('managerFileInput').click()">Seleccionar
                            archivo(s)</button>
                        <input id="managerFileInput" type="file" multiple
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" class="hidden"
                            onchange="handleManagerFile(this)">
                    </div>
                    <div id="managerFileName" class="attach-file-name">Ningún archivo seleccionado.</div>
                    <div id="managerPendingGrid" class="pending-file-grid"></div>
                </div>

                <div class="soft-card">
                    <div class="mini-title">2. Elija los ítems a los que pertenecen los archivos seleccionados</div>
                    <div class="hint">Cada ítem muestra su número y descripción. Puede seleccionarlo con la casilla
                        o usar arrastrar y soltar: lleve un archivo hasta el ítem o lleve el ítem hasta el archivo.
                        También puede quitar vínculos parciales mediante la cruz de cada etiqueta verde.</div>
                    <div id="managerItemList" class="drag-item-list"></div>
                    <div class="link-workflow-note">Puede vincular de dos formas: (1) seleccionar uno o varios
                        archivos y uno o varios ítems; o (2) arrastrar un archivo sobre un ítem, o arrastrar el ítem
                        sobre el archivo. Los vínculos preparados se mostrarán antes de guardarlos.</div>
                </div>

                <div class="attachment-register">
                    <div class="mini-title">Cotizaciones ya vinculadas</div>
                    <div id="attachmentRegisterEmpty" class="hint">Aún no hay cotizaciones vinculadas.</div>
                    <div id="attachmentRegister"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeAttachmentManager()">Cancelar</button>
                <button type="button" class="primary" onclick="saveAttachmentLink()">Guardar vínculo de
                    seleccionadas</button>
            </div>
        </div>
    </div>

    <!-- Modal de advertencia para antecedentes -->
    <div id="emergencyEvidenceAlert" class="emergency-alert-backdrop hidden" role="alertdialog"
        aria-modal="true" aria-labelledby="emergencyAlertTitle" aria-describedby="emergencyAlertMessage">

        <div class="emergency-alert-card">

            <div class="modal-head">
                <div>
                    <h3 id="emergencyAlertTitle">
                        Atención
                    </h3>
                </div>

                <button type="button" class="modal-close" onclick="cerrarAlertaAntecedentes()"
                    aria-label="Cerrar">
                    &times;
                </button>
            </div>

            <div class="modal-body">
                <p id="emergencyAlertMessage"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="primary" onclick="cerrarAlertaAntecedentes()">
                    Entendido
                </button>
            </div>

        </div>
    </div>

    <div id="emergencyEvidenceModal" class="modal-backdrop hidden" onclick="closeEmergencyEvidenceManager(event)">
        <div class="modal-card" style="width:min(1120px,96vw);max-height:90vh;" onclick="event.stopPropagation()">
            <div class="modal-head">
                <div>
                    <h3>Gestor de antecedentes para calificación E/U/I</h3>
                    <p>Cargue uno o varios archivos y vincule cada antecedente al tipo de respaldo que corresponda para
                        la evaluación de la solicitud.</p>
                </div>
                <button type="button" class="modal-close" onclick="closeEmergencyEvidenceManager()"
                    aria-label="Cerrar ventana">
                    &times;
                </button>
            </div>

            <div class="modal-body">
                <div class="attach-drop">
                    <div class="mini-title">1. Cargue los antecedentes y seleccione cuáles va a vincular</div>
                    <div class="hint">Puede cargar imágenes, PDF, Word, Excel y otros archivos de una sola vez.
                        Luego puede seleccionar archivos manualmente o usar arrastrar y soltar para relacionarlos con el
                        tipo de antecedente que respaldan. Cada archivo permite editar su nombre y ampliarlo para
                        verificar su contenido.</div>
                    <div class="tool-group">
                        <button type="button" class="small-btn"
                            onclick="document.getElementById('emergencyEvidenceInput').click()">Seleccionar
                            archivo(s)</button>
                        <input id="emergencyEvidenceInput" type="file" multiple class="hidden"
                            onchange="handleEmergencyEvidenceFiles(this)">
                    </div>
                    <div id="emergencyEvidenceFileName" class="attach-file-name">Ningún archivo cargado.</div>
                    <div id="emergencyEvidencePendingGrid" class="pending-file-grid"></div>
                </div>

                <div class="attach-drop">
                    <div class="mini-title">2. Elija a qué tipo de antecedente corresponde cada archivo</div>
                    <div class="hint">Cada categoría muestra su nombre y finalidad. Puede seleccionar archivos y
                        categorías manualmente o usar arrastrar y soltar: lleve un archivo hasta la categoría, o la
                        categoría hasta el archivo. También puede quitar vínculos parciales mediante la cruz de cada
                        etiqueta verde.</div>
                    <div id="emergencyEvidenceCategoryList" class="item-link-list"
                        style="display:grid;grid-template-columns:1fr;gap:10px;"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeEmergencyEvidenceManager()">Cancelar</button>
                <button type="button" class="primary" onclick="saveEmergencyEvidenceLinks()">Guardar vínculo de
                    antecedentes</button>
            </div>
        </div>
    </div>


    <div id="rowDeleteConfirmModal" class="modal-backdrop hidden"
        onclick="if(event.target===this) closeRowDeleteConfirmation()">
        <div class="modal-card" style="width:min(520px,94vw);max-height:none;" onclick="event.stopPropagation()">
            <div class="modal-head">
                <div>
                    <h3 id="rowDeleteConfirmTitle">¿Eliminar este ítem?</h3>
                    <p>Confirmación requerida antes de borrar una fila con antecedentes.</p>
                </div>
                <button type="button" class="modal-close" title="Cerrar"
                    onclick="closeRowDeleteConfirmation()">×</button>
            </div>
            <div class="modal-body">
                <div class="alert amber" style="margin-top:0;">
                    <strong id="rowDeleteConfirmText">Esta fila contiene antecedentes que podrían perderse si se
                        elimina.</strong>
                    <div id="rowDeleteConfirmDetail" style="margin-top:6px;"></div>
                </div>
                <div class="hint" style="margin-top:10px;">
                    Si selecciona “Sí, eliminar”, se eliminará la fila y también sus vínculos con cotizaciones e
                    imágenes referenciales. Si selecciona “No, conservar”, no se realizará ningún cambio.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeRowDeleteConfirmation()">No, conservar</button>
                <button type="button" class="danger" onclick="confirmRowDelete()">Sí, eliminar</button>
            </div>
        </div>
    </div>


    <div id="euiHelpPopover" class="eui-help-popover hidden" role="tooltip">
        <strong id="euiHelpPopoverTitle"></strong>
        <div id="euiHelpPopoverText"></div>
        <div id="euiHelpPopoverExample" class="eui-help-example hidden"></div>
    </div>


    <div id="pacReviewPromptModal" class="modal-backdrop hidden"
        onclick="if(event.target===this) closePacReviewPrompt()">
        <div class="modal-card" style="width:min(610px,94vw);max-height:none;" onclick="event.stopPropagation()">
            <div class="modal-head">
                <div>
                    <h3>Verificación de compras similares</h3>
                    <p>Revisión recomendada antes de confirmar esta respuesta.</p>
                </div>
                <button type="button" class="modal-close" title="Cerrar"
                    onclick="closePacReviewPrompt()">×</button>
            </div>
            <div class="modal-body">
                <div class="alert blue" style="margin-top:0;">
                    <strong>Revise el Plan Anual de Compras institucional.</strong>
                    <div style="margin-top:7px;">
                        Antes de mantener una respuesta “No” o “No se conoce”, revise el PAC general de la institución
                        y, cuando corresponda, otros antecedentes disponibles para verificar si otra área tiene
                        programada una compra igual o similar.
                    </div>
                </div>
                <div class="hint" style="margin-top:12px;">
                    Esta verificación ayuda a detectar oportunidades de agrupación de demanda y a prevenir compras
                    separadas que debieran analizarse conjuntamente. Una vez realizada la revisión, deje constancia del
                    resultado en “Antecedentes de compras relacionadas / posibilidad de agrupar demanda”.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closePacReviewPrompt()">Cerrar</button>
                <button type="button" class="primary" onclick="goToRelatedPurchasesBackground()">Entendido,
                    registrar revisión</button>
            </div>
        </div>
    </div>


    <div id="requiredValidationModal" class="modal-backdrop hidden"
        onclick="if(event.target===this) closeRequiredValidationModal()">
        <div class="modal-card" style="width:min(600px,94vw);max-height:80vh;" onclick="event.stopPropagation()">
            <div class="modal-head">
                <div>
                    <h3 id="requiredValidationTitle">Faltan campos obligatorios</h3>
                    <p id="requiredValidationSubtitle">Complete la información indicada antes de continuar.</p>
                </div>
                <button type="button" class="modal-close" onclick="closeRequiredValidationModal()">×</button>
            </div>
            <div class="modal-body">
                <div id="requiredValidationList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="primary" onclick="focusFirstMissingField()">Ir al primer campo
                    pendiente</button>
                <button type="button" onclick="closeRequiredValidationModal()">Cerrar</button>
            </div>
        </div>
    </div>

    <div id="submissionMetricsModal" class="modal-backdrop hidden"
        onclick="if(event.target===this) closeSubmissionMetricsModal()">
        <div class="modal-card" style="width:min(760px,94vw);max-height:88vh;" onclick="event.stopPropagation()">
            <div class="modal-head">
                <div>
                    <h3>Requerimiento finalizado</h3>
                    <p>Se generó el registro estadístico interno de confección.</p>
                </div>
                <button type="button" class="modal-close" onclick="closeSubmissionMetricsModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="alert blue" style="margin-top:0;">
                    <strong id="submissionModalHeadline">Formulario generado.</strong>
                    <div id="submissionModalDetail" style="margin-top:6px;"></div>
                </div>
                <div id="submissionModalMetrics" class="metrics-grid" style="margin-bottom:0;"></div>
                <div class="metrics-note">La estadística queda visible al final del formulario y puede descargarse
                    como archivo JSON para incorporarla al expediente o a un repositorio de analítica interna.</div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="downloadSubmissionMetrics()">Descargar registro</button>
                <button type="button" class="primary" onclick="closeSubmissionMetricsModal()">Aceptar</button>
            </div>
        </div>
    </div>
</body>

</html>
