@extends('templates.base')

@section('footer', '')

@section('header', view('components.header._header-admin'))

@section('body')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        * {
            color: var(--primary-text-color);
        }

        a {
            color: var(--primary-link-color);
        }

        a.button {
            display: inline-block;
        }

        a.button:hover {
            color: var(--contrast-text-color);
            text-decoration: none;
        }

        .table td, .table th {
            line-height: 1.5rem;
        }

        .EasyMDEContainer ul, ol, dl {
            display: block;
            list-style-type: disc;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            padding-inline-start: 40px;
        }
    </style>
@endsection
