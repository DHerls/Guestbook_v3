@extends('layouts.app')

@section('content')
<div class="container">
    <paginator v-model="currentPage" v-bind:max="lastPage"></paginator>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/test.js"></script>
@endsection